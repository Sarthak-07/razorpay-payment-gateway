<?php

use Illuminate\Support\Facades\Route;
use App\Helpers\ExtensionHelper;

Route::post('/razorpay/webhook', [App\Extensions\Gateways\RazorPay\RazorPay::class, 'webhook'])->name('razorpay.webhook');
Route::get('/razorpay/payment/{id}/{order_amount}/{invoiceId}/{key_id}', function ($id, $order_amount, $invoiceId, $key_id) {
    return view('RazorPay::payment', compact('id', 'order_amount', 'invoiceId', 'key_id'));
})->name('razorpay.payment');
Route::post('/razorpay/callback/{invoiceId}', function ($invoiceId) {
    return redirect()->route('clients.invoice.show', $invoiceId);
})->name('razorpay.callback');
Route::get('/razorpay/cancel/{invoiceId}', function ($invoiceId) {
    return redirect()->route('clients.invoice.show', $invoiceId);
})->name('razorpay.cancel');
