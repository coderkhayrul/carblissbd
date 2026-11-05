<?php

namespace App\Services;

use App\Models\{Customer, Order, Product};
use Carbon\CarbonImmutable;

class DashboardService
{
    public const PERIODS = [
        'today' => 'Today', 'yesterday' => 'Yesterday', 'week' => 'This Week',
        'month' => 'This Month', 'year' => 'This Year', 'all' => 'All Time',
    ];

    public function report(array $filters): array
    {
        $today = CarbonImmutable::now('Asia/Dhaka')->startOfDay();
        $period = $filters['period'] ?? 'today';
        [$start, $end] = match ($period) {
            'yesterday' => [$today->subDay(), $today->subDay()],
            'week' => [$today->startOfWeek(1), $today],
            'month' => [$today->startOfMonth(), $today],
            'year' => [$today->startOfYear(), $today],
            'all' => [null, null],
            default => [$today, $today],
        };
        if (!empty($filters['from']) && !empty($filters['to'])) {
            $start = CarbonImmutable::parse($filters['from'], 'Asia/Dhaka')->startOfDay();
            $end = CarbonImmutable::parse($filters['to'], 'Asia/Dhaka')->startOfDay();
        }
        $orders = Order::query();
        if ($start) $orders->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
        if (!empty($filters['source'])) $orders->where('order_source', $filters['source']);
        if (!empty($filters['staff'])) $orders->where('created_by', $filters['staff']);
        $cancelNames = ['cancelled', 'canceled', 'cancel'];
        $returnNames = ['returned', 'return'];
        $sales = (clone $orders)->whereDoesntHave('currentStatus', fn ($query) => $query->whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(TRIM(name))'), array_merge($cancelNames, $returnNames)));
        $aggregate = fn ($query) => $query->selectRaw('COUNT(*) as quantity, COALESCE(SUM(grand_total), 0) as amount')->first();
        $total = $aggregate(clone $sales);
        $gsale = $aggregate((clone $sales)->where('order_source', 'GSale'));
        $website = $aggregate((clone $sales)->where('order_source', 'Website'));
        $countStatus = fn ($names) => (clone $orders)->whereHas('currentStatus', fn ($query) => $query->whereIn(\Illuminate\Support\Facades\DB::raw('LOWER(TRIM(name))'), $names))->count();
        $createdCount = function ($model) use ($start, $end) {
            $query = $model::query();
            if ($start) $query->whereBetween('created_at', [
                $start->setTimezone(config('app.timezone'))->toDateTimeString(),
                $end->endOfDay()->setTimezone(config('app.timezone'))->toDateTimeString(),
            ]);
            return $query->count();
        };
        $daily = (clone $sales)->selectRaw('DATE(date) as period, COUNT(*) as quantity, SUM(grand_total) as amount')
            ->groupByRaw('DATE(date)')->orderBy('period')->get()->keyBy('period');
        $monthly = (clone $sales)->selectRaw('SUBSTR(date, 1, 7) as period, COUNT(*) as quantity, SUM(grand_total) as amount')
            ->groupByRaw('SUBSTR(date, 1, 7)')->orderBy('period')->get()->keyBy('period');
        $chartStart = $start ?? ($daily->isNotEmpty() ? CarbonImmutable::parse($daily->keys()->first(), 'Asia/Dhaka') : $today);
        $chartEnd = $end ?? ($daily->isNotEmpty() ? CarbonImmutable::parse($daily->keys()->last(), 'Asia/Dhaka')->max($today) : $today);
        $point = fn ($key, $row) => ['date' => $key, 'amount' => (float) ($row?->amount ?? 0), 'quantity' => (int) ($row?->quantity ?? 0)];
        $dailyPoints = [];
        if ($chartStart->diffInDays($chartEnd) <= 3660) {
            for ($day = $chartStart; $day->lte($chartEnd); $day = $day->addDay()) {
                $key = $day->toDateString();
                $dailyPoints[] = $point($key, $daily->get($key));
            }
        } else foreach ($daily as $key => $row) $dailyPoints[] = $point($key, $row);
        $monthlyPoints = [];
        for ($month = $chartStart->startOfMonth(); $month->lte($chartEnd); $month = $month->addMonth()) {
            $key = $month->format('Y-m');
            $monthlyPoints[] = $point($key, $monthly->get($key));
        }
        return [
            'stats' => [
                'sales_amount' => (float) $total->amount, 'sales_count' => (int) $total->quantity,
                'gsale_amount' => (float) $gsale->amount, 'gsale_count' => (int) $gsale->quantity,
                'website_amount' => (float) $website->amount, 'website_count' => (int) $website->quantity,
                'cancelled' => $countStatus($cancelNames), 'returned' => $countStatus($returnNames),
                'customers' => $createdCount(Customer::class), 'products' => $createdCount(Product::class),
            ],
            'daily' => $dailyPoints, 'monthly' => $monthlyPoints,
            'period' => $period, 'from' => $start?->toDateString(), 'to' => $end?->toDateString(),
            'rangeLabel' => $start ? $start->format('d M Y') . ' - ' . $end->format('d M Y') : 'All Time',
            'customRange' => !empty($filters['from']) && !empty($filters['to']),
        ];
    }
}
