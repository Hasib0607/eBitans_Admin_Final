<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Print Invoices</title>

    <style>
        @media print {
            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
    </style>
</head>

<body onload="window.print()">

    @foreach($invoices as $invoice)

        {{-- BEST OPTION: reuse your existing single invoice view --}}
        {!! app(\App\Http\Controllers\PosController::class)->invoiceview(encrypt($invoice->id))->render() !!}

        <div class="page-break"></div>

    @endforeach

</body>

</html>