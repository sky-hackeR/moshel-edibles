<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{{ config('app.name', 'Moshel Edibles') }}</title>
    <style>
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            width: 100% !important;
            min-width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f5eff6;
            color: #38283d;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-spacing: 0;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        .email-shell {
            width: 100%;
            background-color: #f5eff6;
        }

        .email-card {
            width: 100%;
            max-width: 640px;
            background-color: #ffffff;
            border-collapse: separate;
            border-radius: 10px;
            overflow: hidden;
        }

        .content {
            padding: 38px 42px 42px;
            color: #514455;
            font-size: 15px;
            line-height: 1.65;
        }

        .content h1,
        .content h2 {
            margin-top: 0;
            color: #64236f !important;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 25px;
            font-weight: 700;
            line-height: 1.25;
        }

        .content h3 {
            color: #64236f !important;
            font-size: 14px;
            line-height: 1.4;
        }

        .content p {
            color: #716676;
        }

        .content a:not(.button) {
            color: #a51e78 !important;
        }

        .button {
            display: inline-block;
            padding: 13px 24px;
            border: 1px solid #74267d;
            border-radius: 4px;
            background-color: #74267d !important;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
        }

        .alert-box {
            margin: 22px 0;
            padding: 16px 18px;
            border-left: 4px solid #ce278c;
            border-radius: 2px;
            background-color: #fbf4fa;
        }

        .data-table {
            width: 100%;
            margin: 22px 0;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 11px 12px;
            border-bottom: 2px solid #e9dfea;
            color: #765c7b;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #eee7ef;
            color: #493a4d;
            font-size: 14px;
            vertical-align: top;
        }

        .email-footer {
            padding: 24px 30px 28px;
            background-color: #402047;
            color: #e8ddea;
            font-size: 12px;
            line-height: 1.7;
            text-align: center;
        }

        .email-footer p {
            margin: 4px 0;
            color: #e8ddea;
        }

        .email-footer a {
            color: #f19acb !important;
            text-decoration: underline;
        }

        .preheader {
            display: none !important;
            visibility: hidden;
            opacity: 0;
            height: 0;
            width: 0;
            overflow: hidden;
            mso-hide: all;
        }

        @media only screen and (max-width: 600px) {
            .email-gutter {
                padding: 18px 10px !important;
            }

            .email-card {
                width: 100% !important;
            }

            .content {
                padding: 28px 20px 32px !important;
            }

            .email-logo {
                width: 205px !important;
            }
        }
    </style>
</head>
<body>
    <div class="preheader">@yield('preheader', 'A business update from ' . config('app.name', 'Moshel Edibles'))</div>
    <table role="presentation" class="email-shell" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td class="email-gutter" align="center" style="padding: 34px 16px;">
                <table role="presentation" class="email-card" width="640" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 640px; background-color: #ffffff; border-radius: 10px; overflow: hidden;">
                    <tr>
                        <td height="6" style="height: 6px; background-color: #ce278c; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 24px 24px 20px; background-color: #ffffff;">
                            @php
                                $logoPath = data_get($pageGlobalData ?? null, 'setting.logo');
                                $logoPath = $logoPath ? ltrim(str_replace('\\', '/', $logoPath), '/') : null;
                                $logoPath = $logoPath ? preg_replace('#^public/#i', '', $logoPath) : null;
                                $logoFile = $logoPath ? public_path($logoPath) : null;
                                $logoSource = $logoFile && is_file($logoFile) && isset($message) && method_exists($message, 'embed')
                                    ? $message->embed($logoFile)
                                    : ($logoPath ? asset($logoPath) : null);
                            @endphp
                            @if($logoSource)
                                <img src="{{ $logoSource }}" width="230" alt="{{ config('app.name', 'Moshel Edibles') }}" style="display: block; width: 230px; max-width: 100%; height: auto;">
                            @endif
                            <p style="margin: 14px 0 0; color: #8b748e; font-size: 10px; font-weight: 700; letter-spacing: 1px; line-height: 1.4; text-transform: uppercase;">Business communication</p>
                        </td>
                    </tr>
                    <tr>
                        <td height="1" style="height: 1px; background-color: #eee5ef; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="content" style="padding: 38px 42px 42px;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td class="email-footer" style="padding: 24px 30px 28px; background-color: #402047; color: #e8ddea; font-size: 12px; line-height: 1.7; text-align: center;">
                            <p style="margin: 4px 0; color: #ffffff; font-size: 13px; font-weight: 700;">{{ config('app.name', 'Moshel Edibles') }}</p>
                            <p style="margin: 4px 0; color: #e8ddea;">Thoughtfully made. Professionally managed.</p>
                            <p style="margin: 10px 0 0; color: #e8ddea;">&copy; {{ date('Y') }} {{ config('app.name', 'Moshel Edibles') }}. All rights reserved.</p>
                            <p style="margin: 8px 0 0; color: #e8ddea;">Automated business communication. Visit <a href="{{ url('/') }}" style="color: #f19acb;">our website</a> for assistance.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>