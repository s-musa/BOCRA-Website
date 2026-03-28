<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resource;
use App\Models\Ecommerce\EcmOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
    public function dataTable()
    {
        $orders = QueryBuilder::for(
            EcmOrder::with('cart', 'customer', 'billing', 'shipping', 'payment_method')
                ->orderBy('created_at', 'desc')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('customer_id'),
            AllowedFilter::partial('code'),
        ])->jsonPaginate();
        
        return Resource::collection($orders);
    }
    
    public function index()
    {
        return Inertia::render('Customer/Orders/Index', []);
    }
    
    public function show(EcmOrder $order)
    {
        $order->load(['cart', 'details', 'customer', 'addresses', 'shippingAddress', 'payment_method']);
        
        return Inertia::render('Customer/Orders/Show', [
            'order' => $order
        ]);
    }
}
