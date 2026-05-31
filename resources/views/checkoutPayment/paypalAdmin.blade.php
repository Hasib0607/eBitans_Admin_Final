<!DOCTYPE html>
<html>
<head>
    <title>How To Integrate Stripe Payment Gateway In Laravel 9 - Websolutionstuff</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <h3 style="text-align: center;margin-top: 40px;margin-bottom: 40px;">Paypal Payment</h3>
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">

                <div class="panel-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p><br>
                        </div>
                    @endif
                    <br>
                    <form role="form" action="{{ route('paypal.admin.payment') }}" method="post"
                          class="require-validation"
                          id="payment-form">
                        @csrf

                        <input type="hidden" name="order_id" value="1243">
                        <input type="hidden" name="store_id" value="5039">

                        <div class="form-row row">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

