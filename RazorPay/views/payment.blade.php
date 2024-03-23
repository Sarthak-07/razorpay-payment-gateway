<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RazorPay Integration by Sarthak</title>
</head>
<body>
<form id="RazorPayForm" method="POST" action="https://api.razorpay.com/v1/checkout/embedded">
  <input type="hidden" name="key_id" value="{{ $key_id }}"/>
  <input type="hidden" name="amount" value="{{ $order_amount }}"/>
  <input type="hidden" name="currency" value="INR"/>
  <input type="hidden" name="order_id" value="{{ $id }}"/>
  <input type="hidden" name="name" value="{{ config('app.name', 'Paymenter') }}"/>
  <input type="hidden" name="callback_url" value="{{ route('razorpay.callback', ['invoiceId' => $invoiceId]) }}"/>
  <input type="hidden" name="cancel_url" value="{{ route('razorpay.cancel', ['invoiceId' => $invoiceId]) }}"/>
</form>

<script>
window.onload = function() {
    document.getElementById("RazorPayForm").submit();
};
</script>
</body>
</html>
