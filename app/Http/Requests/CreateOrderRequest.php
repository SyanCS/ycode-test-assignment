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
			'orderData.first_name' => 'required',
			'orderData.last_name' => 'required',
			'orderData.address' => 'required',
			'orderData.apartment' => 'nullable',
			'orderData.city' => 'required',
			'orderData.country' => 'required',
			'orderData.state' => 'required',
			'orderData.zip' => 'required',
			'orderData.phone' => 'required',
			'products.*.id' => 'required|exists:products,id',
			'products.*.quantity' => 'required|integer|min:1',
		];
	}
}
