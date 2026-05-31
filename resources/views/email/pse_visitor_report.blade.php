<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Top 10 Visited Products</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        body, table, td {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f4f4f4;
            width: 100% !important;
        }

        a {
            color: #ff5722;
            text-decoration: none;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff5722;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            white-space: nowrap;
        }

        table.container {
            max-width: 600px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 6px;
            padding: 20px;
            margin: 0 auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .table thead tr {
            background-color: #f8f8f8;
        }

        .footer p {
            margin: 6px 0;
            color: #666;
            font-size: 14px;
            line-height: 1.5;
        }

        @media only screen and (max-width: 600px) {
            table.container {
                padding: 15px !important;
            }

            .table th, .table td {
                padding: 8px 6px !important;
                font-size: 12px !important;
            }

            .button {
                width: 100% !important;
                box-sizing: border-box;
                text-align: center !important;
                display: block !important;
            }
        }
    </style>
</head>

<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f4; padding: 20px 0;">
    <tr>
        <td align="center">
            <table class="container" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="left" style="padding-bottom: 20px;">
                        <img src="https://ebitans.com/static/media/logo-dark.602bcd5a22dae84824fe.png"
                             alt="Ebitans Logo" width="160" style="display:block; max-width:100%; height:auto;"/>
                        <hr style="border:none; border-top:1px solid #ddd; margin: 20px 0;"/>
                    </td>
                </tr>

                <tr>
                    <td>
                        <h2 style="margin: 0 0 10px;">Hey, {{ $name }}</h2>
                        <p style="margin: 0;">List of last week’s top 10 products visited by customers from Product
                            খুঁজো.</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table class="table" role="presentation" cellspacing="0" cellpadding="0" border="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>SL NO</th>
                                <th>{{ $lang == 'bn' ? 'নাম' : 'Product Name' }}</th>
                                <th>{{ $lang == 'bn' ? 'মোট ভিজিটর' : 'Total Visitor' }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $sl = 1; @endphp
                            @if (!empty($visitors))
                                @php $totalBase = \App\Models\StaticVisitor::first()?->visitors ?? 0; @endphp
                                @foreach ($visitors as $visitor)
                                    <tr>
                                        <td>{{ $sl++ }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($visitor->name, 40) }}</td>
                                        <td>{{ $totalBase ? $visitor->totalVisitor * $totalBase : $visitor->totalVisitor }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3">Visitor data not found.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding: 20px 0;">
                        <a href="https://admin.ebitans.com" class="button">Visit Now</a>
                    </td>
                </tr>

                <tr>
                    <td class="footer" style="padding-top: 20px; color: #666;">
                        <p>Thank You,</p>
                        <p><strong>Ebitans Team</strong></p>
                        <p>📞 +8801886 515579 | +8801886 515578</p>
                        <p>📧 <a href="mailto:info@ebitans.com" style="color:#ff5722;">info@ebitans.com</a></p>
                        <p>📧 <a href="mailto:support@ebitans.com" style="color:#ff5722;">support@ebitans.com</a></p>
                        <p>🏢 4th Floor, House: 39, Road: 20, Nikunja 2, Dhaka-1229</p>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-top: 15px; font-size: 12px; color: #aaa;">
                        © {{ date('Y') }} Ebitans. All rights reserved.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>

</html>
