<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Ecommerce\EcmOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        
        $stats = [
            'total_orders' => EcmOrder::where('customer_id', $customer->id)->count(),
            'pending_orders' => EcmOrder::where('customer_id', $customer->id)
                ->where('status', EcmOrder::STATUS_PENDING)
                ->count(),
            'completed_orders' => EcmOrder::where('customer_id', $customer->id)
                ->where('status', EcmOrder::STATUS_COMPLETE)
                ->count(),
        ];
        
        $recentOrders = EcmOrder::with(['payment_method'])
            ->where('customer_id', $customer->id)
            ->latest()
            ->take(5)
            ->get();
        
        return Inertia::render('Customer/Dashboard', [
            'customer' => $customer,
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ]);
    }
}
