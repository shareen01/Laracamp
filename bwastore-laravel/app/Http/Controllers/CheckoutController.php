<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cart;
use App\Transaction;
use App\TransactionDetail;

use Exception;
use Midtrans\Snap;
use Midtrans\config;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        //Save User Data
        $user = Auth::user();
        $user->update($request->except("total_price"));

        //Proses Checkout
        $code = "STORE-" . mt_rand(0000, 9999);
        $carts = Cart::with(["product", "user"])
            ->where("users_id", Auth::user()->id)
            ->get();

        // Transaction Create
        $transaction = Transaction::create([
            "users_id" => Auth::user()->id,
            "inscurance_price" => 0,
            "shipping_price" => 0,
            "total_price" => $request->total_price,
            "transaction_status" => "PENDING",
            "code" => $code,
        ]);

        foreach ($carts as $cart) {
            $trx = "TRX-" . mt_rand(0000, 9999);

            TransactionDetail::create([
                "transactions_id" => $transaction->id,
                "products_id" => $cart->product->id,
                "price" => $cart->product->price,
                "shipping_status" => "PENDING",
                "resi" => "",
                "code" => $trx,
            ]);
        }

        // Delete Cart Data
        Cart::where("users_id", Auth::user()->id)->delete();

        // Konfigurasi midtrans
        Config::$serverKey = config("services.midtrans.serverKey");
        Config::$isProduction = config("services.midtrans.isProduction");
        Config::$isSanitized = config("services.midtrans.isSanitized");
        Config::$is3ds = config("services.midtrans.is3ds");

        // Buat array untuk dikirim ke midtrans
        $midtrans = [
            "transaction_details" => [
                "order_id" => $code,
                "gross_amount" => (int) $request->total_price,
            ],

            "customer_details" => [
                "first_name" => Auth::user()->name,
                "email" => Auth::user()->email,
            ],

            "enabled_payments" => ["gopay", "permata_va", "bank_transfer"],

            "vtweb" => [],
        ];

        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            // Redirect to Snap Payment Page
            return redirect($paymentUrl);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function callback(Request $request)
    {
        // Set konfigurasi midtrans
        Config::$serverKey = config("services.midtrans.serverKey");
        Config::$serverKey = config("services.midtrans.isProduction");
        Config::$serverKey = config("services.midtrans.isSanitized");
        Config::$serverKey = config("services.midtrans.is3ds");

        // instance midtrans notification
        $notification = new Notification();

        // Assign ke variabel untuk memudahkan coding
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        // Cari transaksi berdasarkan id
        $transaction = Transaction::findOrdFail($order_id);

        // Handle notification status
        if ($status == "capture") {
            if ($type == "credit_card") {
                if ($fraud == "challenge") {
                    $transaction->status = "PENDING";
                } else {
                    $transaction->status = "SUCCESS";
                }
            }
        } elseif ($status == "settlement") {
            $transaction->status = "SUCCESS";
        } elseif ($status == "pending") {
            $transaction->status = "PENDING";
        } elseif ($status == "deny") {
            $transaction->status = "CANCELLED";
        } elseif ($status == "expire") {
            $transaction->status = "CANCELLED";
        } elseif ($status == "cancel") {
            $transaction->status = "CANCELLED";
        }

        // Simpan transaksi
        $transaction->save();
    }
}
