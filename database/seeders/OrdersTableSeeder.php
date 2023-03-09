<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\YcodeApiService;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$ycodeApiService = new YcodeApiService();

		$ycodeOrders = $ycodeApiService->getOrders();

		foreach($ycodeOrders['data'] as $ycodeOrder){
			$order = new Order([
				'ycode_id' => $ycodeOrder['_ycode_id'],
				"ycode_order_number" => $ycodeOrder['ID'],
				"name" => $ycodeOrder['ID'],
				'subtotal' => $ycodeOrder['Subtotal'],
				'shipping' => $ycodeOrder['Shipping'],
				'customer_name' => $ycodeOrder['Customer Name'],
				'email' => $ycodeOrder['Email'],
				'phone' => $ycodeOrder['Phone'],
				'address_line_1' => $ycodeOrder['Address Line 1'],
				'address_line_2' => $ycodeOrder['Address Line 2'],
				'city' => $ycodeOrder['City'],
				'country' => $ycodeOrder['Country'],
				'state' => $ycodeOrder['State'],
				'zip' => $ycodeOrder['Zip'],
				'total' => $ycodeOrder['Total'],
				'created_at' => $ycodeOrder['Created date'],
				'updated_at' => $ycodeOrder['Updated date'],
			]);
			$order->save();
		}

		$this->command->info('Orders table seeded!');
	}
}
