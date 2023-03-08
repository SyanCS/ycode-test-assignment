<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'orderData.email' => 'required|email',
			'orderData.firstName' => 'required',
			'orderData.lastName' => 'required',
			'orderData.addressLine1' => 'required',
			'orderData.city' => 'required',
			'orderData.country' => 'required',
			'orderData.postalCode' => 'required',
			'orderData.phone' => 'required',
			'products.*.id' => 'required|exists:products,id',
			'products.*.quantity' => 'required|integer|min:1',
		];
	}
}
