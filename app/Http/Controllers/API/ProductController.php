<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit'); // batas paggination
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if ($id) {
            // jika datanya ada/tidak return ke API
            $product = Product::with(['category', 'galleries'])->find($id);

            // conditional
            if ($product) { // jika data ada
                return ResponseFormatter::success(
                    $product,
                    'Data Produk berhasil diambil'
                );
            } else { // jika data tidak ada
                return ResponseFormatter::error(
                    null,
                    'Data tidak ada',
                    404
                );
            }
        }

        $product = Product::with(['category', 'galleries']);

        // untuk filltering
        if ($name) {
            $product->where('name', 'like', '%' . $name . '%');
        }

        if ($description) {
            $product->where('descript$description', 'like', '%' . $description . '%');
        }

        if ($tags) {
            $product->where('descript$tags', 'like', '%' . $tags . '%');
        }

        if ($price_from) {
            $product->where('price', '>=', $price_from);
        }

        if ($price_from) {
            $product->where('price', '<=', $price_to);
        }

        if ($categories) {
            $product->where('categories', $categories);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Produk berhasil diambil xx'
        );
    }
}
