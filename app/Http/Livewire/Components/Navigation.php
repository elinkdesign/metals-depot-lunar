<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use Lunar\Models\Collection;

class Navigation extends Component
{
    /**
     * The search term for the search input.
     *
     * @var string
     */
    public $term = null;

    /**
     * {@inheritDoc}
     */
    protected $queryString = [
        'term',
    ];

    /**
     * Return the collections in a tree.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    // TODO: Temp fix for duplicate collections, I'll fix this later
    public function getCollectionsProperty()
    {
        return Collection::with(['defaultUrl'])
            ->get()
            ->filter(function ($collection) {
                return !str_ends_with($collection->defaultUrl->slug, '-2');
            })
            ->toTree();
    }

    public function render()
    {
        return view('livewire.components.navigation');
    }
}
