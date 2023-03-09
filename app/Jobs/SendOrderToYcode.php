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
    public function __construct($orderId)
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
            $orderItems = OrderItem::whereNull('ycode_id')->get();
            $products = Product::whereNull('ycode_id')->get();
        } else {
            $order = Order::findOrFail($this->orderId);
            $orderItems = OrderItem::where('order_id', $this->orderId)->get();
            $products = Product::whereIn('id', $orderItems->pluck('product_id'))->get();
        }

        $response = $apiService->createOrder($order->toArray());

        if (isset($response['id'])) {
            $order->update(['ycode_id' => $response['id']]);
        } else {
            return;
        }

        foreach ($orderItems as $orderItem) {
            $response = $apiService->createOrderItem($orderItem->toArray());

            if (isset($response['id'])) {
                $orderItem->update(['ycode_id' => $response['id']]);
            } else {
                return;
            }
        }

        foreach ($products as $product) {
            $response = $apiService->createProduct($product->toArray());

            if (isset($response['id'])) {
                $product->update(['ycode_id' => $response['id']]);
            } else {
                return;
            }
        }
    }
}
