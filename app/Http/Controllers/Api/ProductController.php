<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use Lunar\Models\ProductType;

class ProductController extends Controller
{
    /**
     * Get all products of a specific product type.
     *
     * @param Request $request
     * @param string $productType
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, string $productType)
    {
        $productType = ProductType::where('name', $productType)->first();

        if (!$productType) {
            return response()->json(['message' => "{$productType} product type not found"], 404);
        }

        $products = Product::where('product_type_id', $productType->id)
            ->with([
                'variants.prices',
                'variants.values.option',
                'media',
                'thumbnail'
            ])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->translateAttribute('name'),
                    'description' => $product->translateAttribute('description'),
                    'thumbnail' => $product->thumbnail?->getUrl('medium'),
                    'variants' => $product->variants->map(function ($variant) {
                        $price = $variant->prices->first();
                        return [
                            'id' => $variant->id,
                            'price' => $price ? $price->price->decimal : null,
                            'sku' => $variant->sku,
                            'options' => $variant->values->map(function ($value) {
                                return [
                                    'name' => $value->option->name,
                                    'value' => $value->name,
                                ];
                            }),
                        ];
                    }),
                ];
            });

        return response()->json($products);
    }
}