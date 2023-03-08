<template>
    <div>
        <h2 class="sr-only">Checkout</h2>

        <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-6" role="alert">
            <strong class="font-bold">{{ successMessage }}</strong>
        </div>

        <div v-else-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-6" role="alert">
            <strong class="font-bold">{{ errorMessage }}</strong>
        </div>

        <form class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
            <div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Contact information</h2>

                    <div class="mt-4">
                        <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                        <input v-model="orderData.email" type="email" id="email-address" name="email-address" autocomplete="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">                        </div>
                    </div>
                </div>

                <div class="mt-10 border-t border-gray-200 pt-10">
                    <h2 class="text-lg font-medium text-gray-900">Shipping information</h2>

                    <div class="mt-4 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-4">
                        <div>
                            <label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
                            <div class="mt-1">
                                <input v-model="orderData.first_name" type="text" id="first-name" name="first-name" autocomplete="given-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                            <div class="mt-1">
                                <input v-model="orderData.last_name" type="text" id="last-name" name="last-name" autocomplete="family-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <div class="mt-1">
                                <input v-model="orderData.address_line1" type="text" name="address" id="address" autocomplete="street-address" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="apartment" class="block text-sm font-medium text-gray-700">Apartment, suite, etc.</label>
                            <div class="mt-1">
                                <input v-model="orderData.address_line2" type="text" name="apartment" id="apartment" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <div class="mt-1">
                                <input v-model="orderData.city" type="text" name="city" id="city" autocomplete="address-level2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <div class="mt-1">
                                <select v-model="orderData.country" id="country" name="country" autocomplete="country-name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select a country</option>
                                    <option v-for="country in countries" :value="country">{{ country }}</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="region" class="block text-sm font-medium text-gray-700">State / Province</label>
                            <div class="mt-1">
                                <input v-model="orderData.state" type="text" name="region" id="region" autocomplete="address-level1" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="postal-code" class="block text-sm font-medium text-gray-700">Postal code</label>
                            <div class="mt-1">
                                <input v-model="orderData.zip" type="text" name="postal-code" id="postal-code" autocomplete="postal-code" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <div class="mt-1">
                                <input v-model="orderData.phone" type="text" name="phone" id="phone" autocomplete="tel" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order summary -->
            <div class="mt-10 lg:mt-0">
                <h2 class="text-lg font-medium text-gray-900">Order summary</h2>

                <div class="mt-4 rounded-lg border border-gray-200 bg-white shadow-sm">
                    <h3 class="sr-only">Items in your cart</h3>
                    <ul role="list" class="divide-y divide-gray-200">
                        <li v-for="product in products" :key="product.id" class="flex py-6 px-4 sm:px-6">
                            <div class="flex-shrink-0">
                                <img :src="product.image" :alt="product.name" class="w-20 rounded-md">
                            </div>

                            <div class="ml-6 flex flex-1 flex-col">
                                <div class="flex">
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm">
                                            <a :href="`/products/${product.id}`" class="font-medium text-gray-700 hover:text-gray-800">{{ product.name }}</a>
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-500">{{ product.color }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-1 items-end justify-between pt-2">
                                    <p class="mt-1 text-sm font-medium text-gray-900">${{ product.price.toFixed(2) }}</p>

                                    <div class="ml-4">
                                        <label :for="`quantity-${product.id}`" class="sr-only">Quantity</label>
                                        <input :id="`quantity-${product.id}`" :name="`quantity-${product.id}`" type="number" v-model.number="product.quantity" class="rounded-md border border-gray-300 text-left text-base font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm w-16">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <dl class="space-y-6 border-t border-gray-200 py-6 px-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ subtotal.toFixed(2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-sm">Shipping</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ shipping.toFixed(2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                            <dt class="text-base font-medium">Total</dt>
                            <dd class="text-base font-medium text-gray-900">${{ total.toFixed(2) }}</dd>
                        </div>
                    </dl>

                    <div class="border-t border-gray-200 py-6 px-4 sm:px-6">
                        <button type="submit" class="w-full rounded-md border border-transparent bg-indigo-600 py-3 px-4 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Submit order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            contries:[],
            products: [], 
            orderData: {
                email: '',
                first_name: '',
                last_name: '',
                address_line1: '',
                address_line2: '',
                city: '',
                state: '',
                country: '',
                postalCode: '',
                phone: '',
            }, 
            shipping: 0, 
            subtotal: 0, 
            total: 0,
            successMessage: null,
            errorMessage: null
        }
    },
    mounted() {

        axios.get('https://restcountries.com/v3.1/all')
        .then(response => {
            this.countries = response.data.map(country => country.name.common);
        })
        .catch(error => {
            console.log(error);
        });

        axios.get('/api/products')
        .then(response => {
            this.products = response.data.map(product => ({
                ...product,
                quantity: product.quantity || 0,
            }));
        })
        .catch(error => {
            console.log(error);
        });

        this.orderData.country = 'United States';
    },
    methods: {
    calculateShippingCost() {
        const country = this.orderData.country;
        if (country === 'United States') {
            this.shipping = 5;
        } else if (country === 'Canada') {
            this.shipping = 10;
        } else {
            this.shipping = 20;
        }
    },
    calculateTotal() {
        this.subtotal = this.products.reduce((total, product) => {
            console.log(product.price, product.quantity);
        return total + (product.price * product.quantity);
        }, 0);
            console.log(this.subtotal, this.shipping);
        this.total = this.subtotal + this.shipping;
            console.log(this.total);
    },
    submitOrder() {
        axios.post('/api/orders', {
            products: this.products,
            orderData: this.orderData,
            shipping: this.shipping,
            subtotal: this.subtotal,
            total: this.total,
        })
        .then(response => {
            this.successMessage = 'Order submitted successfully!'
            console.log(response);
        })
        .catch(error => {
            this.errorMessage = 'Order failed!'
            console.log(error);
        });
    },
    },
    watch: {
    'orderData.country': function() {
        this.calculateShippingCost();
        this.calculateTotal();
    },
    'products': {
        handler: function() {
            this.calculateTotal();
        },
        deep: true,
    },
    },
};
</script>