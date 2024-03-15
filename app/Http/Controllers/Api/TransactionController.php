<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use App\Models\transaction;
use App\Models\transactionItem;
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

    public function checkout(Request $request) {
        // request validasi
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists|products.id',
            'total_price' => 'required',
            'shipping_price' => 'required',
            'status' => 'required|in:PENDING,SUCCESS,FAILED,SHIPPING,SHIPPED',
        ]);

        // membuat transaksi
        $transaction = Transaction::create([
            'usersID' => Auth::user()->id,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'shipping_price' => $request->shipping_price,
            'status' => $request->status,
        ]);

        // membuat transaksi item
        foreach ($request->items as $product) {
            transactionItem::create([
                'usersID' => Auth::user()->id,
                'productsID' => $product['id'],
                'transactionsID' => $transaction['id'],
                'quantity' => $product['quantity']
            ]);
        }

        return ResponseFormatter::success([
            $transaction->load('items.product'),
            'Transaksi Berhasil !'
        ]);
    }
}
