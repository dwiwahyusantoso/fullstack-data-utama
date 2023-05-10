<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Transaction::paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'product_id'   => 'required',
           'quantity' => 'required|integer'
        ]);

        if ($validator->fails()) {
           return response()->json($validator->errors(), 400);
        }

        $product = Product::where('id', $request->product_id)->first();
        if ($product) {
            $inv = $this->generateInv($request->quantity, $product->price);
            if ($inv == '0') {
                return response()->json([
                    'message' => 'server generate invoice error',
                    'status' => false
                ], 400);
            }

            $trx = Transaction::create([
                'reference_no'     => $inv,
                'price'   => $product->price,
                'quantity'   => $request->quantity,
                'payment_amount'   => $request->quantity * $product->price,
                'product_id' => $product->id
            ]);

            if ($trx) {
                return response()->json([
                    'message' => 'transaction successfully save',
                    'status' => true,
                    'data' => $trx
                ], 200);
            }

            return response()->json([
                'message' => 'transaction failed save',
                'status' => false
            ], 400);
        }

        return response()->json([
            'message' => 'product not found',
            'status' => false
        ], 404);
    }

    public function generateInv($qty, $price)
    {
        $payload = [
            "quantity" => $qty,
            "price" => $price,
            "payment_amount" => $qty * $price
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://pay.saebo.id/test-dau/api/v1/transactions',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($payload),
          CURLOPT_HTTPHEADER => array(
            'X-API-KEY: DATAUTAMA',
            'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response);
        if (empty($res) || $res->code != '20000') {
            return 0;
        }

        return $res->data->reference_no;
    }
}
