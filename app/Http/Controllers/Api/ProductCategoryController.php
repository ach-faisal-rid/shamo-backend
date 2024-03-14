<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\productCategory;
use http\Env\Response;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('id');
        $show_product = $request->input('show.product');

        if($id) {
            $category = productCategory::with(['products'])->find($id);
            if($category) {
                return ResponseFormatter::success(
                    $category,
                    'Data Kategori berhasil diambil'
                );
            }else {
                return ResponseFormatter::error(
                    null,
                    'Data Kategori tidak ada',
                    404
                );
            }
        }
        $category = productCategory::query();

        if ($name) {
            $category->where('name', 'like', '%' . $name . '%');
        }

        if ($show_product) {
            $category->with('products');
        }

        return ResponseFormatter::success(
            $category->paginate($limit),
            'data Kategori berhasil diambil'
        );
    }
}
