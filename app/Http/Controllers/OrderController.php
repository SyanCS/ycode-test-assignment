<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \App\Http\Requests\CreateOrderRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CreateOrderRequest $request)
	{
			// Begin a database transaction
			DB::beginTransaction();

			try {
				// Create a new order
				$orderData = $request->validated()['orderData'];
				$productsData = $request->validated()['products'];

				$order = Order::create([
					'ycode_order_number' => $this->generateOrderNumber(),
					'name' => $orderData['firstName'] . ' ' . $orderData['lastName'],
					'customer_name' => $orderData['firstName'] . ' ' . $orderData['lastName'],
					'email' => $orderData['email'],
					'phone' => $orderData['phone'],
					'address_line1' => $orderData['addressLine1'],
					'address_line2' => $orderData['addressLine2'],
					'city' => $orderData['city'],
					'country' => $orderData['country'],
					'state' => $orderData['state'],
					'zip' => $orderData['postalCode'],
					'subtotal' => $request->input('subtotal'),
					'shipping' => $request->input('shipping'),
					'total' => $request->input('total')
				]);

				// Create order items for each product in the order
				foreach ($productsData as $productData) {
						$product = \App\Models\Product::findOrFail($productData['id']);

						$orderItem = new OrderItem();
						$orderItem->ycode_order_item_number = $this->generateOrderItemNumber();
						$orderItem->name = $product->name;
						$orderItem->quantity = $productData['quantity'];
						$orderItem->product_id = $product->id;
						$orderItem->order_id = $order->id;
						$orderItem->save();
				}

				// Commit the transaction
				DB::commit();

				return response()->json(['success' => true]);
			} catch (\Exception $e) {
				// Rollback the transaction
				DB::rollBack();

				return response()->json(['success' => false, 'error' => $e->getMessage()]);
			}
    }

	/**
	 * Generate a unique order number.
	 *
	 * @return string
	 */
	private function generateOrderNumber(): int
	{
		$lastOrder = Order::orderBy('ycode_order_number', 'desc')->first();
		return $lastOrder ? $lastOrder->ycode_order_number + 1 : 1;
	}

	/**
	 * Generate a unique order item number.
	 *
	 * @return string
	 */
	private function generateOrderItemNumber()
	{
		$lastOrderItem = OrderItem::orderBy('ycode_order_item_number', 'desc')->first();
		return $lastOrderItem ? $lastOrderItem->ycode_order_number + 1 : 1;
	}
}
