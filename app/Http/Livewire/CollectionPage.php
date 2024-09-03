<?php

namespace App\Http\Livewire;

use App\Traits\FetchesUrls;
use Livewire\Component;
use Livewire\ComponentConcerns\PerformsRedirects;
use Lunar\Models\Collection;
use Lunar\Models\Product;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;

class CollectionPage extends Component
{
    use PerformsRedirects,
        FetchesUrls;

        public $products;
        public $selectedVariant;

    /**
     * {@inheritDoc}
     *
     * @param  string  $slug
     * @return void
     *
     * @throws \Http\Client\Exception\HttpException
     */
    public function mount($slug)
    {
        $this->url = $this->fetchUrl(
            $slug,
            Collection::class,
            [
                'element.thumbnail',
                'element.products.variants.basePrices',
                'element.products.defaultUrl',
            ]
        );

        foreach ($this->url->element->products as $product) {
            $this->selectedVariant[$product->id]['id'] = $product->variants->first()->id;
            $this->selectedVariant[$product->id]['quantity'] = 1;
        }

        if (! $this->url) {
            abort(404);
        }
    }

    public function addToCart($productId , $variantId, $quantity)
    {

        //Check if variant is null and if so, set it to the first variant
        if ($variantId == null) {
            $variant = Product::find($productId)->variants->first();
        } else {
            $variant = ProductVariant::find($variantId);
        }

        if ($variant->stock < $quantity) {
            $this->addError('quantity', 'The quantity exceeds the available stock.');
            return;
        }

        CartSession::manager()->add($variant, $quantity);
        $this->emit('add-to-cart');
    }

    /**
     * Computed property to return the collection.
     *
     * @return \Lunar\Models\Collection
     */
    public function getCollectionProperty()
    {
        return $this->url->element;
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return view('livewire.collection-page', [
            'products' => $this->collection->products,
        ]);
    }
}
