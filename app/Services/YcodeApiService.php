<?php

namespace App\Services;

use Exception;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Collection;
use GuzzleHttp\Client;
use Config;

class YcodeApiService
{
	private $apiKey;
	private $collections;

	const BASE_URL = "https://app.ycode.com/api/v1/";

	public function __construct()
	{
		$this->apiKey = env('YCODE_API_TOKEN');
		$this->collections = Config::get('ycodecollections');
	}

	private function getBaseheaders()
	{
		return [
			'accept' => 'application/json',
			'authorization' => 'Bearer ' . $this->apiKey,
			'content-type' => 'application/json',
		];
	}

	private function formatPayloadForYcode(array $array): array
	{
		$formattedArray = [];

    foreach ($array as $key => $value) {
			$formattedKey = ucwords(str_replace('_', ' ', $key));
			$formattedArray[$formattedKey] = $value;
		}

    return $formattedArray;
	}

	private function getBaseUrl()
	{
		return self::BASE_URL;
	}

	public function getOrders()
  {
		$response = $this->getRequest('collections/' . $this->collections['ORDERS'] . '/items');
		if ($response->getStatusCode() == 200) {
			$data = json_decode($response->getBody(), true);
			return collect($data);
		} else {
			throw new Exception('Failed to get orders');
		}
  }

	public function getOrderItems()
	{
		$response = $this->getRequest('collections/' . $this->collections['ORDER_ITEMS'] . '/items');

		if ($response->getStatusCode() == 200) {
			$data = json_decode($response->getBody(), true);
			return collect($data);
		} else {
			throw new Exception('Failed to get order items');
		}
	}

	public function getProducts()
	{
		$response = $this->getRequest('collections/' . $this->collections['PRODUCTS'] . '/items');

		if ($response->getStatusCode() == 200) {
			$data = json_decode($response->getBody(), true);
			return collect($data);
		} else {
			throw new Exception('Failed to get products');
		}
	}

	public function createOrder($payload)
	{
		$payload = $this->formatPayloadForYcode($payload);

		$response = $this->postRequest('collections/' . $this->collections['ORDERS'] . '/items', $payload);
		if ($response->getStatusCode() == 200) {
			return $response->json();
		} else {
			throw new Exception('Failed to create order');
		}
	}

	public function createOrderItem($payload)
	{
		$payload = $this->formatPayloadForYcode($payload);
		$response = $this->postRequest('collections/' . $this->collections['ORDER_ITEMS'] . '/items', $payload);
		if ($response->getStatusCode() == 200) {
			return $response->json();
		} else {
			throw new Exception('Failed to create order item');
		}
	}

	public function createProduct($payload)
	{
		$payload = $this->formatPayloadForYcode($payload);

		$response = $this->postRequest('collections/' . $this->collections['PRODUCTS'] . '/items', $payload);
		if ($response->getStatusCode() == 200) {
			return $response->json();
		} else {
			throw new Exception('Failed to create product');
		}
	}

	public function postRequest(string $endpoint, array $data)
	{
		return  Http::withHeaders( $this->getBaseheaders() )->post( $this->getBaseUrl().$endpoint, $data );
	}

	public function putRequest(string $endpoint, array $data = [])
	{
		return Http::withHeaders( $this->getBaseheaders() )->put( $this->getBaseUrl().$endpoint, $data );
	}

	public function getRequest(string $endpoint)
	{
		return  Http::withHeaders( $this->getBaseheaders() )->get( $this->getBaseUrl().$endpoint );
	}

	public function deleteRequest(string $endpoint)
	{
		return  Http::withHeaders( $this->getBaseheaders() )->delete( $this->getBaseUrl().$endpoint );
	}
}
