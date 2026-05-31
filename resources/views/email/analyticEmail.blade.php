<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Website Traffic Summary - Ebitans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        /* Email Reset Styles */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table, td {
            border-collapse: collapse !important;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333333;
            line-height: 1.5;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            table.container {
                width: 100% !important;
                padding: 15px !important;
            }

            table.stats-box {
                width: 100% !important;
                display: block !important;
                margin: 0 auto 20px auto !important;
            }

            img {
                max-width: 120px !important;
                height: auto !important;
            }

            h3 {
                font-size: 18px !important;
            }

            h4 {
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background-color:#f4f4f4; padding: 20px 0;">
    <tr>
        <td align="center">
            <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" border="0"
                   style="max-width: 600px; background-color: #ffffff; border-radius: 6px; padding: 20px;">
                <tr>
                    <td align="left" style="padding-bottom: 20px;">
                        <img src="https://ebitans.com/static/media/logo-dark.602bcd5a22dae84824fe.png"
                             alt="Ebitans" width="160"
                             style="display:block; max-width:100%; height:auto; border:none;"/>
                        <hr style="border:0; border-top:1px solid #ddd; margin:20px 0;"/>
                    </td>
                </tr>

                <tr>
                    <td style="padding-bottom: 10px;">
                        <h3 style="margin:0; font-size: 20px; color: #333;">Website Traffic Summary</h3>
                    </td>
                </tr>

                @if(isset($data['storeName']))
                    <tr>
                        <td style="font-size: 16px; padding-bottom: 10px;">
                            Hey, <strong>{{ $data['storeName'] }}</strong>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="font-size: 15px; color: #555555; padding-bottom: 20px;">
                        Let's take a look at how your website traffic performed in the past {{ $data['days'] ?? "few" }}
                        days.
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding: 20px 0;">
                        <table role="presentation" class="stats-box" cellpadding="0" cellspacing="0" border="0"
                               style="text-align:center; width:300px; margin: 0 auto;">
                            <tr>
                                <td>
                                    <img src="https://admin.ebitans.com/img/icons/web-traffic.png" alt="Visitors"
                                         width="40" style="margin-bottom: 10px; display: inline-block;"/>
                                    <h4 style="margin: 5px 0; color: #333;">Total Visitors</h4>
                                    <p style="font-size: 24px; margin: 0; color: #333;">{{ $data['visitors'] }}</p>
                                    <p style="color: green; margin: 5px 0 0;">
                                        {{ $data['increase'] }} vs previous {{ $data['days'] ?? "few" }} days
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="font-size: 14px; color: #666666; padding-top: 20px;">
                        <p style="margin: 0 0 5px;">Thank you,</p>
                        <p style="margin: 0 0 10px;"><strong>Ebitans Team</strong></p>
                        <p style="margin: 5px 0;">📞 +8801886 515579 | +8801886 515578</p>
                        <p style="margin: 5px 0;">📧 <a href="mailto:info@ebitans.com"
                                                       style="color:#ff5722; text-decoration:none;">info@ebitans.com</a>
                        </p>
                        <p style="margin: 5px 0;">📧 <a href="mailto:support@ebitans.com"
                                                       style="color:#ff5722; text-decoration:none;">support@ebitans.com</a>
                        </p>
                        <p style="margin: 5px 0;">🏢 4th Floor, House: 39, Road: 20, Nikunja 2, Dhaka-1229</p>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-top: 30px; font-size: 12px; color: #aaaaaa;">
                        © {{ date('Y') }} Ebitans. All rights reserved.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>

</html>
