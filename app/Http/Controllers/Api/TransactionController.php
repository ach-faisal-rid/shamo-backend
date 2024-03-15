<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $status = $request->input('status');

        if($id) {
            $product = transaction::with(['items.product'])
                ->find($id);

            if($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data Transaksi berhasil diambil'
                );
            }else {
                return ResponseFormatter::error(
                    null,
                    'Data Transaksi tidak ada',
                    404
                );
            }
        }
        $transaction = transaction::with(['items.product'])
        ->where('usersID', Auth::user());

        if ($status) {
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'data list transaksi  berhasil diambil'
        );
    }
}
