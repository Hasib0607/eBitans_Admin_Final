<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>Ebitans Store Created</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        /* Responsive styles - Gmail supports media queries */
        @media only screen and (max-width: 600px) {
            .email-content {
                width: 100% !important;
                padding: 15px !important;
            }

            .visit-button {
                padding: 10px !important;
                font-size: 16px !important;
            }

            img.logo {
                width: 120px !important;
                height: auto !important;
            }

            h2 {
                font-size: 22px !important;
            }
        }
    </style>
</head>

<body
    style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif; font-size:14px; color:#333; line-height:1.6;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background-color:#f4f4f4; padding:20px 0;">
    <tr>
        <td align="center">
            <table role="presentation" class="email-content" width="600" cellpadding="0" cellspacing="0" border="0"
                   style="background-color:#ffffff; border-radius:6px; padding:20px; width:600px; max-width:600px;">
                <tr>
                    <td align="left" style="padding-bottom:20px;">
                        <img src="https://ebitans.com/static/media/logo-dark.602bcd5a22dae84824fe.png"
                             alt="Ebitans Logo" width="160"
                             style="display:block; max-width:100%; height:auto; border:none;" class="logo"/>
                        <hr style="border:none; border-top:1px solid #ddd; margin:20px 0;"/>
                    </td>
                </tr>

                @if(isset($name))
                    <tr>
                        <td style="font-size: 20px; font-weight: bold; padding-bottom: 15px;">
                            Hey, {{ $name }}
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding-bottom: 20px; font-size:14px; line-height:1.6; color:#333;">
                        {!! $text ?? '' !!}
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding: 10px 0 20px;">
                        <a href="{{ (request()->secure() ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'ebitans.com') }}"
                           style="background-color:#ff9f1c; color:#fff; padding:12px 24px; text-decoration:none; border-radius:5px; font-weight:bold; display:inline-block; border:2px solid #ff9f1c;"
                           class="visit-button">
                            Visit Now
                        </a>
                    </td>
                </tr>

                <tr>
                    <td style="padding-top: 20px; font-size: 14px; color: #666;">
                        <p style="margin:4px 0;"><strong>Thank You</strong></p>
                        <p style="margin:4px 0;"><strong>Ebitans</strong></p>
                        <p style="margin:4px 0;">Phone: +8801886 515579 | +8801886 515578</p>
                        <p style="margin:4px 0;">Email: <a href="mailto:info@ebitans.com"
                                                           style="color:#ff5722; text-decoration:none;">info@ebitans.com</a>
                        </p>
                        <p style="margin:4px 0;">Email: <a href="mailto:support@ebitans.com"
                                                           style="color:#ff5722; text-decoration:none;">support@ebitans.com</a>
                        </p>
                        <p style="margin:4px 0;">Office: 4th Floor, House: 39, Road: 20, Nikunja 2, Dhaka-1229</p>
                    </td>
                </tr>

                <tr>
                    <td align="center" style="padding-top: 15px; font-size: 12px; color: #aaaaaa;">
                        &copy; {{ date('Y') }} Ebitans. All rights reserved.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>

</html>
