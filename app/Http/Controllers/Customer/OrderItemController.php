<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Resource;
use App\Models\Ecommerce\EcmOrderItem;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class OrderItemController extends Controller
{
    public function dataTable()
    {
        $orderItems = QueryBuilder::for(
            EcmOrderItem::with('order', 'product.media', 'variant')
                ->orderBy('id')
        )->allowedFilters([
            AllowedFilter::exact('id'),
            AllowedFilter::exact('order_id'),
            AllowedFilter::partial('code'),
        ])->jsonPaginate();
        
        return Resource::collection($orderItems);
    }
}
