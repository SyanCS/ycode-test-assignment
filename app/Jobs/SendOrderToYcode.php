<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\YcodeApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrderToYcode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     *
     * @param  int  $orderId
     * @return void
     */
    public function __construct($orderId = null)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apiService = new YcodeApiService();

        if (!$this->orderId) {
            // This is to send the orders that failed to sync when
            // the order was placed for some reason
            $orders = Order::whereNull('ycode_id')->get();
            $products = Product::whereNull('ycode_id')->get();
        } else {
            $orders = Order::where('id', $this->orderId)->whereNull('ycode_id')->get();
            $orderItems = OrderItem::where('order_id', $this->orderId)->get();
            $products = 
                Product::whereIn('id', $orderItems->pluck('product_id'))
                ->whereNull('ycode_id')
                ->get();
        }

        foreach ($orders as $order) {
            $response = $apiService->createOrder($order->toArray());
            if (isset($response['_ycode_id'])) {
                $order->update(['ycode_id' => $response['_ycode_id']]);
            } else {
                return;
            }
        }

        foreach ($products as $product) {
            $response = $apiService->createProduct($product->toArray());

            if (isset($response['_ycode_id'])) {
                $product->update(['ycode_id' => $response['_ycode_id']]);
            } else {
                return;
            }
        }

        if (!$this->orderId) {
            $orderItems = OrderItem::select('order_items.name', 'order_items.quantity' ,'products.ycode_id as product', 'orders.ycode_id as order')                
                ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereNull('order_items.ycode_id')
                ->get();
        } else {
            $orderItems = OrderItem::select('order_items.name', 'order_items.quantity' ,'products.ycode_id as product', 'orders.ycode_id as order')                
                ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
                ->leftJoin('orders', 'orders.id', '=', 'order_items.order_id')
                ->where('order_id', $this->orderId)
                ->whereNull('order_items.ycode_id')
                ->get();
        }


        foreach ($orderItems as $orderItem) {
            $response = $apiService->createOrderItem($orderItem->toArray());

            if (isset($response['_ycode_id'])) {
                $orderItem->update(['ycode_id' => $response['_ycode_id']]);
            } else {
                return;
            }
        }
    }
}
