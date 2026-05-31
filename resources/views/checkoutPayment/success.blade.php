<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Success</title>
</head>
<body>
<div style="text-align: center;">
    <h1>Congratulations !! Your payment has been successfully done.</h1>
</div>
<br><br>
<div style="text-align: center;">
    @if (Session::has('success'))
        <h2>{{ session("success") }}</h2>
    @endif

    @if (Session::has('transaction_id'))
        <h4>Transaction ID is : {{ session("transaction_id") }}</h4>
    @endif
    <a href="{{route('payment.payments')}}">back</a>
</div>
</body>
</html>
