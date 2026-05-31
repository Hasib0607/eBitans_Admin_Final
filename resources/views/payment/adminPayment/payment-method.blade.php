<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('fav-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('fav-icon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tour/0.12.0/js/bootstrap-tour-standalone.min.js"></script>
    <title>eBitans - Registration Fee</title>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto');

        body {
            font-family: 'Roboto', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .payment-method {
            border: 2px solid #81aabc;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #c2ebfd;
        }

        .payment-method:hover, .payment-method:active {
            border-color: #4a6bff;
            background-color: #f5f8ff;
        }

        .payment-method img {
            height: 50px;
        }

        .payment-method.selected {
            border-color: #4a6bff;
            background-color: #f5f8ff;
            box-shadow: 0 0 0 2px rgba(74, 107, 255, 0.3);
        }

        .payment-method:active {
            transform: scale(0.98);
        }

    </style>
</head>

<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
    <form id="registrationForm" action="{{ route("registrationFeePayment") }}"
          method="POST"
          style="text-align: center;">
        @csrf
        <input type="hidden" name="payment_method" id="payment_method" value="bkash">
        <input type="hidden" name="order_id" id="order_id" value="{{ $addonsOrder->id ?? "" }}">

        <h1 style="margin-bottom: 25px;font-weight: bold">রেজিস্ট্রেশন করতে </br>পেমেন্ট সম্পন্ন করুন</h1>
        <h3 style="margin-bottom: 5px;">আপনার রেজিস্ট্রেশন ফি</h3>
        <h4 style="margin-top: 0; color: #2d3748; font-weight: bold;">{{ $addonsOrder->total ?? "0" }} টাকা</h4>

        <div style="margin: 20px 0; padding: 15px; background: #f3f4f6; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="margin-bottom: 10px;">পেমেন্ট করতে 'বিকাশ/ নগদ' বাছাই করুন</p>
            <div style="display: flex; justify-content: center; gap: 15px;">
                <div class="payment-method selected" onclick="selectPaymentMethod('bkash', this)">
                    <img src="{{ asset("img/payment/bkashLogo.png") }}" style="height: 50px;" alt="">
                </div>
                <div class="payment-method" onclick="selectPaymentMethod('nagad', this)">
                    <img src="{{ asset("img/payment/nagadLogo.png") }}" style="height: 50px;" alt="">
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary" onclick="myFunction(event)">Pay Now</button>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
</script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function myFunction() {
        // e.preventDefault();
        const payment_method = $('#payment_method').val();
        const order_id = $('#order_id').val();

        if (payment_method == "") {
            swal.fire(
                'Warning!',
                "Please select a payment method!",
                'warning'
            );
            return false
        }

        if (order_id == '') {
            swal.fire(
                'Warning!',
                "Order ID Missing!",
                'warning'
            );
            return false
        }

        $("#registrationForm").submit();
    }

    function selectPaymentMethod(method, element) {
        // Remove selected class from all payment methods
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });

        // Add selected class to clicked element
        element.classList.add('selected');
        $('#payment_method').val(method);
    }
</script>

<script>
    @if (Session::has('message'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("{{ session('message') }}");
    @endif

        @if (Session::has('error'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.error("{{ session::get('error') }}");
    @endif

        @if (Session::has('info'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.info("{{ session('info') }}");
    @endif

        @if (Session::has('warning'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.warning("{{ session('warning') }}");
    @endif

        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.error("{{ $error }}");
    @endforeach
        @endif

        @if (Session::has('success'))
        toastr.options = {
        "closeButton": true,
        "progressBar": true
    }
    toastr.success("{{ session('success') }}");
    @endif
</script>

</body>

</html>
