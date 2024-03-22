<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\ExtensionHelper;

Route::post('/razorpay/webhook', [App\Extensions\Gateways\RazorPay\RazorPay::class, 'webhook'])->name('razorpay.webhook');
Route::get('/razorpay/payment/{id}/{order_amount}/{invoiceId}/{key_id}', function ($id, $order_amount, $invoiceId, $key_id) {
    return view('RazorPay::payment', compact('id', 'order_amount', 'invoiceId', 'key_id'));
})->name('razorpay.payment');