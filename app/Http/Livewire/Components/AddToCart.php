<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Lunar\Base\Purchasable;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;

class AddToCart extends Component
{
    /**
     * The purchasable model we want to add to the cart.
     *
     * @var Purchasable
     */
    public ?Purchasable $purchasable = null;


    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'quantity' => 'required|numeric|min:1|max:10000',
        ];
    }

    public function addToCart($variantId)
    {
        $this->validate();

        //Find variant by id then set it to purchasable
        $this->purchasable = ProductVariant::find($variantId);

        if ($this->purchasable->stock < $this->quantity) {
            $this->addError('quantity', 'The quantity exceeds the available stock.');
            return;
        }

        CartSession::manager()->add($this->purchasable, $this->quantity);
        $this->emit('add-to-cart');
    }

    public function render()
    {
        return view('livewire.components.add-to-cart');
    }
}
