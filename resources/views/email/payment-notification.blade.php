<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Ebitans Notification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Responsive styles for mobile */
        @media only screen and (max-width: 600px) {
            table[role="presentation"][width="600"] {
                width: 100% !important;
                padding: 15px !important;
            }

            img {
                max-width: 120px !important;
                height: auto !important;
            }

            a {
                padding: 12px 24px !important;
                font-size: 16px !important;
            }

            td[style*="font-size:16px"] {
                font-size: 18px !important;
            }
        }
    </style>
</head>

<body
    style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif; font-size:14px; color:#333; line-height:1.5;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f4f4f4; padding:20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background:#ffffff; border-radius:6px; padding:20px;">
                    <tr>
                        <td style="padding-bottom:20px;">
                            <img src="/logo.png" alt="Ebitans Logo" width="160"
                                style="display:block; max-width:100%; height:auto; border:none;" />
                            <hr style="border:none; border-top:1px solid #ddd; margin:20px 0;" />
                        </td>
                    </tr>

                    @if(isset($name))
                        <tr>
                            <td style="font-size:16px; padding-bottom:10px; font-weight:bold;">
                                Hey, {{ $name }}
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding-bottom:20px;">
                            {{-- Render dynamic HTML here safely --}}
                            <div style="font-size:14px; color:#333; line-height:1.6;">
                                {!! $text ?? '' !!}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:10px 0 20px;">
                            <a href="{{ (request()->secure() ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'ebitans.com.bd') }}"
                                style="background-color:#ff5722; color:#ffffff; padding:10px 20px; text-decoration:none; border-radius:4px; font-weight:bold; display:inline-block;">Visit
                                Now</a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:30px; font-size:14px; color:#666;">
                            <p style="margin:4px 0;"><strong>Thank you,</strong></p>
                            <p style="margin:4px 0;"><strong>eBitans Team</strong></p>
                            <p style="margin:4px 0;">📞 +8801886 515579 | +8801886 515578</p>
                            <p style="margin:4px 0;">📧 <a href="mailto:info@ebitans.com"
                                    style="color:#ff5722; text-decoration:none;">info@ebitans.com</a>
                            </p>
                            <p style="margin:4px 0;">📧 <a href="mailto:support@ebitans.com"
                                    style="color:#ff5722; text-decoration:none;">support@ebitans.com</a>
                            </p>
                            <p style="margin:4px 0;">🏢 4th Floor, House: 39, Road: 20, Nikunja 2, Dhaka-1229</p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding-top:20px; font-size:12px; color:#aaaaaa;">
                            &copy; {{ date('Y') }} eBitans | All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>