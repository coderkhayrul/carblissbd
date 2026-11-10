<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Customer, Status};
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ReturnReason;
use App\Models\Product;
use App\Models\ShippingChargeSetting;
use App\Support\BangladeshPhone;
use App\Services\AdminOrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $isCreate = true;
        $order = new Order(['paid_amount' => 0, 'shipping_cost' => 0, 'extra_discount_amount' => 0]);
        $order->setRelation('details', collect());
        $order->setRelation('customerInfo', null);
        $order->setRelation('customer', null);
        $statuses = Status::where('status', true)->orderBy('id')->get();
        $order->current_status = $statuses->first(fn ($status) => strtolower(trim($status->name)) === 'pending')?->id ?? $statuses->first()?->id;
        $shippingOptions = ShippingChargeSetting::where('status', true)->orderBy('id')->get();
        $initialItems = $request->old('items', []);
        if (!is_array($initialItems)) $initialItems = [];
        $ids = collect($initialItems)->filter(fn ($item) => is_array($item))->pluck('product_id')->filter(fn ($id) => is_numeric($id));
        $catalog = $this->productCatalog(Product::whereIn('id', $ids)->with('variations.variationAttributes.attribute')->get());
        $selectedCustomer = Customer::find(is_numeric($request->old('customer_id')) ? $request->old('customer_id') : 0);
        $checkoutToken = (string) Str::uuid();
        $tokens = $request->session()->get('admin_sale_tokens', []);
        $tokens[] = $checkoutToken;
        $request->session()->put('admin_sale_tokens', array_slice($tokens, -30));
        return view('admin.orders.edit', compact('isCreate', 'order', 'statuses', 'shippingOptions', 'initialItems', 'catalog', 'selectedCustomer', 'checkoutToken'));
    }

    public function searchCustomers(Request $request)
    {
        $term = trim($request->validate(['q' => ['required', 'string', 'max:120']])['q']);
        if (mb_strlen($term) < 2) return response()->json(['customers' => []]);
        $phone = preg_replace('/[^0-9]/', '', BangladeshPhone::normalize($term));
        $customers = Customer::where('status', true)->where(function ($query) use ($term, $phone) {
            $query->where('name', 'like', '%' . $term . '%')->orWhere('mobile', 'like', '%' . $term . '%');
            if (strlen($phone) >= 4) $query->orWhere('mobile', 'like', '%' . substr($phone, -11) . '%');
        })->orderBy('name')->limit(20)->get(['id', 'name', 'mobile', 'email', 'address', 'shipping_address']);
        return response()->json(['customers' => $customers]);
    }

    public function createCustomer(Request $request, \App\Services\OrderService $service)
    {
        if (is_string($request->input('phone'))) $request->merge(['phone' => BangladeshPhone::normalize($request->input('phone'))]);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'], 'phone' => ['required', 'string', 'regex:/^\+8801[0-9]{9}$/'],
            'email' => ['nullable', 'email', 'max:255'], 'address' => ['required', 'string', 'max:255'],
        ]);
        try {
            $customer = $service->registerAdminCustomer($data);
        } catch (ValidationException $exception) { throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return response()->json(['message' => 'Unable to save customer. Please retry or search the mobile number for an existing account.'], 500);
        }
        return response()->json(['customer' => $customer->only(['id', 'name', 'mobile', 'email', 'address', 'shipping_address']), 'message' => 'Customer selected successfully. Existing phone accounts are reused.']);
    }

    public function store(Request $request, AdminOrderService $service)
    {
        $data = $this->validateOrderInput($request, true);
        if (!in_array($data['checkout_token'], $request->session()->get('admin_sale_tokens', []), true)) {
            throw ValidationException::withMessages(['order' => 'This sale form has expired. Open New Sale again.']);
        }
        try {
            $order = $service->create($data, $request->user()->id);
        } catch (ValidationException $exception) { throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to create the order. No order or payment was saved.');
        }
        return redirect()->route('admin.orders.show', $order)->with('success', 'New sale created successfully. Invoice: ' . $order->order_no)->with('new_order_sound', $order->id);
    }

    private function productCatalog($products): array
    {
        return $products->map(fn ($product) => [
            'id' => $product->id, 'name' => $product->name, 'sku' => $product->sku,
            'sale_price' => (float) $product->sale_price, 'is_variation' => (bool) $product->is_variation,
            'active' => (bool) $product->status,
            'image' => asset($product->thumbnail_image ?: (($product->gallery_images ?? [])[0] ?? 'backend/assets/extra/preview-image.png')),
            'variations' => $product->variations->map(fn ($variation) => [
                'id' => $variation->id, 'label' => $variation->optionLabel($product->name),
                'sku' => $variation->sku, 'sale_price' => (float) $variation->sale_price,
                'active' => (bool) $variation->status, 'image' => $variation->image ? asset($variation->image) : null,
            ])->values()->all(),
        ])->values()->all();
    }

    public function searchProducts(Request $request)
    {
        $data = $request->validate(['q' => ['nullable', 'string', 'max:120']]);
        $term = trim($data['q'] ?? '');
        if (mb_strlen($term) < 2) return response()->json(['products' => []]);
        $products = Product::where('status', true)->where(function ($query) use ($term) {
            $query->where('name', 'like', '%' . $term . '%')->orWhere('sku', 'like', '%' . $term . '%')
                ->orWhere('bangla_name', 'like', '%' . $term . '%')
                ->orWhereHas('variations', fn ($query) => $query->where('status', true)->where(fn ($query) => $query->where('sku', 'like', '%' . $term . '%')->orWhere('name', 'like', '%' . $term . '%')));
        })->with(['variations' => fn ($query) => $query->where('status', true), 'variations.variationAttributes.attribute'])
            ->orderBy('name')->limit(15)->get();
        return response()->json(['products' => $this->productCatalog($products)]);
    }

    public function edit(Request $request, Order $order, AdminOrderService $service)
    {
        $order->load(['details', 'customerInfo', 'customer', 'currentStatus', 'payments']);
        $defaults = $order->details->map(fn ($line) => [
            'id' => $line->id, 'product_id' => $line->product_id, 'product_variation_id' => $line->product_variation_id,
            'quantity' => $line->quantity, 'sale_price' => $line->sale_price,
        ])->all();
        $initialItems = $request->session()->getOldInput('items', $defaults);
        if (!is_array($initialItems)) $initialItems = $defaults;
        $ids = collect($initialItems)->filter(fn ($item) => is_array($item))->pluck('product_id')->filter(fn ($id) => is_numeric($id))->unique();
        $catalog = $this->productCatalog(Product::whereIn('id', $ids)->with(['variations.variationAttributes.attribute'])->get());
        $statuses = Status::where('status', true)->orWhere('id', $order->current_status)->orderBy('id')->get();
        $shippingOptions = ShippingChargeSetting::where('status', true)->orWhere('id', $order->shipping_charge_setting_id)->orderBy('id')->get();
        $editVersion = $service->editVersion($order);
        return view('admin.orders.edit', compact('order', 'initialItems', 'catalog', 'statuses', 'shippingOptions', 'editVersion'));
    }

    public function update(Request $request, Order $order, AdminOrderService $service)
    {
        $data = $this->validateOrderInput($request);
        try {
            $service->update($order, $data, $request->user()->id);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to update the order. No changes were saved.');
        }
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order updated successfully.');
    }

    private function validateOrderInput(Request $request, bool $creating = false): array
    {
        if (is_string($request->input('phone'))) $request->merge(['phone' => BangladeshPhone::normalize($request->input('phone'))]);
        $money = ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999.99'];
        return $request->validate([
            'edit_version' => [$creating ? 'exclude' : 'required', 'string', 'size:64'],
            'checkout_token' => [$creating ? 'required' : 'exclude', 'uuid'],
            'customer_id' => [$creating ? 'required' : 'exclude', 'integer', Rule::exists('customers', 'id')->where('status', true)],
            'date' => [$creating ? 'required' : 'exclude', 'date_format:Y-m-d'],
            'payment_type' => [$creating ? 'required' : 'exclude', Rule::in(['COD', 'Online'])],
            'payment_method' => [$creating ? 'required' : 'exclude', Rule::in(['Cash', 'Bkash', 'Nagad', 'Rocket', 'Bank Transfer'])],
            'paid_amount' => $creating ? $money : ['exclude'],
            'transaction_id' => [$creating ? 'nullable' : 'exclude', 'string', 'max:255', 'required_if:payment_type,Online'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.id' => $creating ? ['prohibited'] : ['nullable', 'integer', 'distinct'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.product_variation_id' => ['nullable', 'integer', 'exists:product_variations,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99999'],
            'items.*.sale_price' => $money,
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+8801[0-9]{9}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status_id' => ['required', 'integer', 'exists:statuses,id'],
            'cancel_reason' => ['nullable', 'string', 'max:2000'],
            'shipping_id' => [$creating ? 'required' : 'nullable', 'integer', 'exists:shipping_charge_settings,id'],
            'shipping_cost' => $money, 'extra_discount_amount' => $money,
        ]);
    }

    private function filteredOrders(Request $request)
    {
        $filters = $request->validate([
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'order_source' => ['nullable', Rule::in(['Website', 'GSale', 'App'])],
            'entry_channel' => ['nullable', Rule::in(['Checkout', 'Landing Page', 'Admin'])],
            'payment_type' => ['nullable', Rule::in(['COD', 'Online'])],
            'courier' => ['nullable', Rule::in(array_merge(AdminOrderService::COURIERS, ['unassigned']))],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to' => array_merge(['nullable', 'date_format:Y-m-d'], $request->filled('date_from') ? ['after_or_equal:date_from'] : []),
            'order_no' => ['nullable', 'string', 'max:255'],
        ]);
        $query = Order::with(['customerInfo', 'customer', 'currentStatus', 'createdBy', 'steadfastShipment', 'pathaoShipment'])->latest('id');
        if (!empty($filters['entry_channel'])) $query->where('entry_channel', $filters['entry_channel']);
        foreach (['customer_id' => 'customer_id', 'order_source' => 'order_source', 'payment_type' => 'payment_type', 'status_id' => 'current_status'] as $filter => $column) {
            if (isset($filters[$filter]) && $filters[$filter] !== '') $query->where($column, $filters[$filter]);
        }
        if (!empty($filters['courier'])) {
            if ($filters['courier'] === 'unassigned') $query->where('is_courier_assigned', false);
            else $query->whereRaw('TRIM(courier_name) = ?', [$filters['courier']])->where('is_courier_assigned', true);
        }
        if (!empty($filters['date_from'])) $query->whereDate('date', '>=', $filters['date_from']);
        if (!empty($filters['date_to'])) $query->whereDate('date', '<=', $filters['date_to']);
        if (!empty($filters['order_no'])) $query->where('order_no', 'like', '%' . trim($filters['order_no']) . '%');
        return $query;
    }

    public function index(Request $request)
    {
        $orders = $this->filteredOrders($request)->get();
        $customers = Customer::orderBy('name')->get(['id', 'name', 'mobile']);
        $statuses = Status::orderBy('id')->get(['id', 'name']);
        return view('admin.orders.index', compact('orders', 'customers', 'statuses'));
    }

    public function export(Request $request)
    {
        $query = $this->filteredOrders($request);
        return response()->streamDownload(function () use ($query) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Invoice', 'Date', 'Customer', 'Phone', 'Source', 'Quantity', 'Paid', 'Due', 'Total', 'Payment Type', 'Status', 'Courier', 'Entry Channel', 'Landing Page URL'], ',', '"', '');
            $safe = static function ($value) {
                $value = (string) $value;
                return preg_match('/^[\s]*[=+@-]/u', $value) ? "'" . $value : $value;
            };
            foreach ($query->lazy(500) as $order) {
                fputcsv($output, array_map($safe, [
                    $order->order_no, $order->date?->format('Y-m-d'),
                    $order->customerInfo?->name ?? $order->customer?->name,
                    $order->customerInfo?->phone ?? $order->customer?->mobile,
                    $order->order_source, $order->total_quantity, $order->paid_amount, $order->due_amount,
                    $order->grand_total, $order->payment_type, $order->currentStatus?->name,
                    $order->is_courier_assigned ? trim($order->courier_name ?? '') : 'Not Assigned',
                    $order->entry_channel, $order->landing_page_url,
                ]), ',', '"', '');
            }
            fclose($output);
        }, 'orders-' . now('Asia/Dhaka')->format('Y-m-d-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'customerInfo', 'details.product', 'details.variation', 'payments', 'statusHistory.status', 'currentStatus', 'createdBy']);
        $complaints = DB::table('order_complaints')->where('order_id', $order->id)->latest('id')->get();
        $returns = DB::table('order_return_records')->join('return_reasons', 'return_reasons.id', '=', 'order_return_records.return_reason_id')
            ->where('order_return_records.order_id', $order->id)->select('order_return_records.*', 'return_reasons.title as reason')->latest('order_return_records.id')->get();
        $editLogs = DB::table('order_edit_logs')->leftJoin('users', 'users.id', '=', 'order_edit_logs.edited_by')
            ->where('order_edit_logs.order_id', $order->id)->select('order_edit_logs.*', 'users.name as editor')
            ->latest('order_edit_logs.id')->limit(20)->get();
        return view('admin.orders.show', compact('order', 'complaints', 'returns', 'editLogs'));
    }

    public function invoice(Order $order, string $copy)
    {
        abort_unless(array_key_exists($copy, AdminOrderService::COPIES), 404);
        $order->load(['customerInfo', 'customer', 'details', 'currentStatus']);
        $copyLabel = AdminOrderService::COPIES[$copy];
        $company = app('company.branding');
        return response()->view('admin.orders.invoice', compact('order', 'copy', 'copyLabel', 'company'))->header('Cache-Control', 'no-store, private');
    }

    public function miniInvoice(Order $order)
    {
        $order->load(['customerInfo', 'customer', 'details', 'pathaoShipment', 'steadfastShipment']);
        return response()->view('admin.orders.print-invoices', ['orders' => collect([$order]), 'mode' => 'mini', 'columns' => 3, 'copy' => 'customer', 'copyLabel' => 'Customer Copy', 'company' => app('company.branding')])->header('Cache-Control', 'no-store, private');
    }

    public function printInvoices(Request $request)
    {
        $data = $request->validate([
            'order_ids' => ['required', 'array', 'min:1', 'max:200'],
            'order_ids.*' => ['required', 'integer', 'distinct', 'exists:orders,id'],
            'mode' => ['required', Rule::in(['mini', 'full'])],
            'columns' => ['required', 'integer', Rule::in([2, 3])],
            'copy' => ['required', Rule::in(array_keys(AdminOrderService::COPIES))],
        ]);
        $positions = array_flip(array_map('intval', $data['order_ids']));
        $orders = Order::whereIn('id', $data['order_ids'])->with(['customerInfo', 'customer', 'details', 'currentStatus', 'pathaoShipment', 'steadfastShipment'])
            ->get()->sortBy(fn ($order) => $positions[$order->id])->values();
        $copy = $data['copy'];
        return response()->view('admin.orders.print-invoices', ['orders' => $orders, 'mode' => $data['mode'], 'columns' => (int) $data['columns'], 'copy' => $copy, 'copyLabel' => AdminOrderService::COPIES[$copy], 'company' => app('company.branding')])->header('Cache-Control', 'no-store, private');
    }

    public function action(Order $order, string $action)
    {
        abort_unless(in_array($action, ['status', 'return', 'complaint', 'duplicate'], true), 404);
        $order->load(['details', 'currentStatus']);
        $statuses = Status::where('status', true)->orderBy('id')->get();
        $reasons = ReturnReason::where('status', true)->orderBy('serial_no')->get();
        $requestToken = (string) Str::uuid();
        return view('admin.orders.action', compact('order', 'action', 'statuses', 'reasons', 'requestToken'));
    }

    public function performAction(Request $request, Order $order, string $action, AdminOrderService $service)
    {
        $rules = match ($action) {
            'status' => ['status_id' => ['required', 'integer', Rule::exists('statuses', 'id')->where('status', true)], 'cancel_reason' => ['nullable', 'string', 'max:2000']],
            'complaint' => ['subject' => ['required', 'string', 'max:255'], 'message' => ['required', 'string', 'max:10000']],
            'duplicate' => ['request_token' => ['required', 'uuid'], 'confirm' => ['accepted']],
            'return' => [
                'request_token' => ['required', 'uuid'],
                'return_reason_id' => ['required', 'integer', Rule::exists('return_reasons', 'id')->where('status', true)],
                'note' => ['nullable', 'string', 'max:2000'],
                'items' => ['required', 'array', 'min:1', 'max:100'],
                'items.*.id' => ['required', 'integer', 'distinct', Rule::exists('order_details', 'id')->where('order_id', $order->id)],
                'items.*.quantity' => ['required', 'integer', 'min:0', 'max:999999'],
            ],
            default => abort(404),
        };
        $data = $request->validate($rules);
        try {
            if ($action === 'duplicate') {
                $copy = $service->duplicate($order, $data['request_token']);
                return redirect()->route('admin.orders.show', $copy)->with('success', 'Order duplicated with a new invoice number.')->with('new_order_sound', $copy->id);
            }
            match ($action) {
                'status' => $service->status($order, (int) $data['status_id'], $data['cancel_reason'] ?? null),
                'complaint' => $service->complaint($order, $data, $request->user()->id),
                'return' => $service->recordReturn($order, $data, $request->user()->id),
            };
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withInput()->with('error', 'Unable to save this order action. Please try again.');
        }
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order action saved successfully.');
    }

    public function assignCourier(Request $request, Order $order, AdminOrderService $service)
    {
        $data = $request->validate(['courier' => ['required', Rule::in(AdminOrderService::COURIERS)]]);
        if (!$order->canAssignCourier($data['courier'])) throw ValidationException::withMessages(['courier' => 'A courier is already assigned or a booking is pending. Its courier cannot be changed.']);
        if ($data['courier'] === 'Pathao') return redirect()->route('admin.pathao.booking', $order);
        try {
            $service->courier($order, $data['courier']);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'Unable to assign this courier. Please try again.');
        }
        return back()->with('success', 'Order booked with Steadfast successfully.');
    }
}
