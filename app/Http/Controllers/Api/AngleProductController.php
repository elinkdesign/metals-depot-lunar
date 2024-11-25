<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lunar\Models\Product;
use Lunar\Models\ProductType;

class AngleProductController extends Controller
{
    /**
     * Get all products with the product type "Angle".
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $angleProductType = ProductType::where('name', 'Angle')->first();

        if (!$angleProductType) {
            return response()->json(['message' => 'Angle product type not found'], 404);
        }

        $products = Product::where('product_type_id', $angleProductType->id)
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