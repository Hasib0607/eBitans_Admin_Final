<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Email from Ebitans</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset styles for consistent rendering */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            width: 100% !important;
        }

        a {
            color: #ff5722;
            text-decoration: none;
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 15px !important;
            }

            .button {
                width: 100% !important;
                box-sizing: border-box;
                text-align: center !important;
            }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f4f4;">
    <tr>
        <td align="center">
            <table class="container" width="600" cellpadding="0" cellspacing="0" border="0"
                   style="max-width:600px; background:#ffffff; border-radius:6px; padding:20px; font-family:Arial, sans-serif; color:#333333;">
                <tr>
                    <td align="left" style="padding-bottom:20px;">
                        <img src="https://ebitans.com/static/media/logo-dark.602bcd5a22dae84824fe.png"
                             alt="Ebitans Logo" width="160" style="display:block; max-width:100%; height:auto;">
                        <hr style="border:0; border-top:1px solid #ddd; margin:20px 0;">
                    </td>
                </tr>

                @if(isset($data['name']))
                    <tr>
                        <td style="font-size:18px; padding-bottom:10px;">
                            <strong>Hey, {{ $data['name'] }}</strong>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="font-size:16px; line-height:1.5; padding-bottom:20px;">
                        {!! $data['text'] !!}
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding:20px 0;">
                        <a href="{{ (request()->secure() ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'ebitans.com.bd') }}"
                           class="button"
                           style="background-color:#ff5722; color:#ffffff; text-decoration:none; padding:12px 24px; border-radius:5px; display:inline-block; font-size:16px; font-weight:bold;">
                            Visit Now
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="font-size:14px; line-height:1.6; color:#666666; padding-top:30px;">
                        <p style="margin:0 0 10px;">Thank You,</p>
                        <p style="margin:0;"><strong>Ebitans Team</strong></p>
                        <p style="margin:5px 0;">Phone: +8801886 515579 | +8801886 515578</p>
                        <p style="margin:5px 0;">Email: <a href="mailto:info@ebitans.com" style="color:#ff5722;">info@ebitans.com</a>
                        </p>
                        <p style="margin:5px 0;">Office: 4th Floor, House: 39, Road: 20, Nikunja 2, Dhaka-1229</p>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-top:30px; font-size:12px; color:#aaaaaa;">
                        © {{ date('Y') }} Ebitans. All rights reserved.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
