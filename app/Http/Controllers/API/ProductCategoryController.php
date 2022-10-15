<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name'); //(id)??
        $show_product = $request->input('show_product');

        if ($id) {
            // jika datanya ada/tidak return ke API
            $category = ProductCategory::with(['products'])->find($id);

            // conditional
            if ($category) { // jika data ada
                return ResponseFormatter::success(
                    $category,
                    'Data kategori berhasil diambil'
                );
            } else { // jika data tidak ada
                return ResponseFormatter::error(
                    null,
                    'Data kategori tidak ada',
                    404
                );
            }
        }

        $category = ProductCategory::query();

        // untuk filltering
        if ($name) {
            $category->where('name', 'like', '%' . $name . '%');
        }

        if ($show_product) {
            $category->with('products');
        }

        return ResponseFormatter::success(
            $category->paginate($limit),
            'Data list kategori berhasil diambil'
        );
    }
}
