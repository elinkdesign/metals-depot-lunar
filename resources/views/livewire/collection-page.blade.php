<section>
    <div class="max-w-screen-xl px-4 py-12 mx-auto sm:px-6 lg:px-8 flex">
        <div>
            <div>
                <h1 class="text-3xl font-bold">
                    {{ $this->collection->translateAttribute('name') }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    {!! $this->collection->translateAttribute('description') !!}
                </p>
                <table class=" mt-8">
            <thead>
                <tr>
                    <th>Product name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Options</th>
                    <th>Add to cart</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->collection->products as $product)
                    <tr wire:key="{{ $product->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900x">
                                        {{ $product->translateAttribute('name') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-900">
                                <x-product-price class="font-medium" :product="$product" :variantId="$selectedVariant[$product->id]['id'] ?? null"/>
                            </div>
                        </td>
                        <td class=" whitespace-nowrap">
                            <input wire:model="selectedVariant.{{ $product->id }}.quantity"
                                type="number"
                                min="1"
                                max="10000"
                                value="1"
                                class="px-2 py-1 text-sm border rounded-md" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select wire:model="selectedVariant.{{ $product->id }}.id"
                            class="block w-full text-base border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach ($product->variants as $variant)
                                    <option value="{{ $variant->id }}">
                                        {{ $variant->values->pluck('name.en')->first() ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <button wire:click="addToCart({{ $product->id }}, {{ $selectedVariant[$product->id]['id'] ?? null }}, {{ $selectedVariant[$product->id]['quantity'] ?? 1 }})"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none">
                                Add to cart
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
            </div>
        </div>
        <div class="flex justify-center items-center" style="
            width: 140%;">
            @if ($this->collection->thumbnail)
                <div class="aspect-w-1 aspect-h-1">
                    <img class="object-cover rounded-xl"
                        src="{{ $this->collection->thumbnail->getUrl('large') }}"
                        alt="{{ $this->collection->translateAttribute('name') }}" />
                </div>
            @endif
        </div>
        </div>
    </div>
</section>
