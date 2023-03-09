<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\YcodeApiService;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;

class OrderItemsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$ycodeApiService = new YcodeApiService();

		$ycodeOrderItems = $ycodeApiService->getOrderItems();


		foreach ($ycodeOrderItems['data'] as $ycodeOrderItem) {
			$orderItem = new OrderItem();
			$orderItem->ycode_id = $ycodeOrderItem['_ycode_id'];
			$orderItem->quantity = $ycodeOrderItem['Quantity'];
			$orderItem->ycode_order_item_number = $ycodeOrderItem['ID'];
			$orderItem->name = $ycodeOrderItem['ID'];
			
			// Map Order
			$order = Order::where('ycode_id', $ycodeOrderItem['Order'])->first();
			if (!$order) {
				throw new Exception("Failed to find order");
			}
			$orderItem->order_id = $order->id;

			// Map Product
			$product = Product::where('ycode_id', $ycodeOrderItem['Product'])->first();
			if (!$product) {
					throw new Exception("Failed to find product");
			}
			$orderItem->product_id = $product->id;

			$orderItem->save();
		}

		$this->command->info('Order Items table seeded!');
	}
}
