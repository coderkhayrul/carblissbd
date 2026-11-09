<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminOrderNotificationService;
use Illuminate\Http\Request;

class OrderNotificationController extends Controller
{
    public function index(Request $request, AdminOrderNotificationService $service)
    {
        return response()->json($service->feed((int) $request->user()->id))->header('Cache-Control', 'no-store, private');
    }

    public function read(Request $request, int $notification, AdminOrderNotificationService $service)
    {
        $service->markRead((int) $request->user()->id, $notification);
        return response()->json(['success' => true]);
    }

    public function readAll(Request $request, AdminOrderNotificationService $service)
    {
        $data = $request->validate(['through_id' => ['required', 'integer', 'min:0']]);
        $service->markAllRead((int) $request->user()->id, (int) $data['through_id']);
        return response()->json(['success' => true]);
    }
}
