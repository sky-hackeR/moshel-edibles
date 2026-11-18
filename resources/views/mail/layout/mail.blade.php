<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ config('app.name', 'Moshel Edibles') }}</title>
    <style>
        /* Base resets */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        body {
            width: 100% !important;
            min-width: 100%;
            margin: 0;
            padding: 0;
            background-color: #f7f3f8;
            color: #38283d;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
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
            -ms-interpolation-mode: bicubic;
        }

        /* Outer shell */
        .email-shell {
            width: 100%;
            background-color: #f7f3f8;
        }

        /* Card Container */
        .email-card {
            width: 100%;
            max-width: 600px;
            background-color: #ffffff;
            border-collapse: separate;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #ebddec;
            box-shadow: 0 8px 30px rgba(88, 26, 98, 0.05);
        }

        /* Header */
        .header-cell {
            padding: 32px 36px 24px;
            background-color: #ffffff;
            text-align: center;
        }

        .brand-subtitle {
            margin: 10px 0 0;
            color: #9c8a9e;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        /* Content area */
        .content {
            padding: 8px 36px 36px;
            color: #4f3f52;
            font-size: 15px;
            line-height: 1.65;
        }

        .content h1 {
            margin: 0 0 10px;
            color: #4a1253 !important;
            font-size: 26px;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -0.3px;
        }

        .content h2 {
            margin: 0 0 10px;
            color: #4a1253 !important;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.3;
            letter-spacing: -0.2px;
        }

        .content h3 {
            margin: 20px 0 10px;
            color: #581a62 !important;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .content p {
            margin: 0 0 16px;
            color: #5c4d60;
            font-size: 15px;
            line-height: 1.6;
        }

        .content a:not(.button) {
            color: #b82285 !important;
            font-weight: 600;
            text-decoration: underline;
        }

        /* Modern Action Button */
        .button {
            display: inline-block;
            padding: 13px 30px;
            border-radius: 8px;
            background: #74267d !important;
            background: linear-gradient(135deg, #74267d 0%, #b82285 100%) !important;
            color: #ffffff !important;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none !important;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 14px rgba(184, 34, 133, 0.28);
        }

        .button-secondary {
            background: #2d3748 !important;
            box-shadow: 0 4px 12px rgba(45, 55, 72, 0.2);
        }

        .button-danger {
            background: #c53030 !important;
            box-shadow: 0 4px 12px rgba(197, 48, 48, 0.25);
        }

        .button-success {
            background: #059669 !important;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        /* Modern Alert / Callout Box */
        .alert-box {
            margin: 20px 0;
            padding: 16px 18px;
            border-left: 4px solid #ce278c;
            border-radius: 6px;
            background-color: #fcf6fc;
            border-top: 1px solid #f6e8f4;
            border-right: 1px solid #f6e8f4;
            border-bottom: 1px solid #f6e8f4;
            font-size: 14px;
            line-height: 1.55;
            color: #581a62;
        }

        .alert-box-success {
            border-left-color: #10b981;
            background-color: #f0fdf4;
            border-color: #dcfce7;
            color: #166534;
        }

        .alert-box-warning {
            border-left-color: #f59e0b;
            background-color: #fffbeb;
            border-color: #fef3c7;
            color: #92400e;
        }

        .alert-box-danger {
            border-left-color: #ef4444;
            background-color: #fef2f2;
            border-color: #fee2e2;
            color: #991b1b;
        }

        /* Modern Data Table */
        .data-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 1px solid #f0e6f1;
            border-radius: 8px;
            overflow: hidden;
        }

        .data-table th {
            padding: 12px 14px;
            background-color: #faf5fb;
            border-bottom: 1px solid #ecddec;
            color: #7b6580;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .data-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #f4ecf5;
            color: #3d2f40;
            font-size: 14px;
            vertical-align: top;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* Metric Tile */
        .metric-card {
            background-color: #faf5fb;
            border: 1px solid #eedfed;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 22px;
        }

        .metric-value {
            font-size: 30px;
            font-weight: 800;
            color: #581a62;
            margin: 6px 0;
            line-height: 1.1;
        }

        .metric-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #938096;
            margin: 0;
        }

        /* Pill Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .badge-purple { background-color: #f3e8f5; color: #581a62; }
        .badge-pink { background-color: #fde8f5; color: #ce278c; }
        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-danger { background-color: #fee2e2; color: #b91c1c; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }

        /* Footer */
        .email-footer {
            padding: 26px 36px 30px;
            background-color: #2b1130;
            color: #e4d7e7;
            font-size: 12px;
            line-height: 1.65;
            text-align: center;
        }

        .email-footer p {
            margin: 4px 0;
            color: #d8c9dc;
        }

        .email-footer a {
            color: #f3a3cf !important;
            text-decoration: underline;
        }

        /* Preheader */
        .preheader {
            display: none !important;
            visibility: hidden;
            opacity: 0;
            color: transparent;
            height: 0;
            width: 0;
            overflow: hidden;
            mso-hide: all;
        }

        /* Mobile overrides */
        @media only screen and (max-width: 600px) {
            .email-gutter {
                padding: 16px 8px !important;
            }
            .email-card {
                width: 100% !important;
                border-radius: 8px !important;
            }
            .header-cell {
                padding: 24px 20px 16px !important;
            }
            .content {
                padding: 6px 20px 28px !important;
            }
            .email-footer {
                padding: 22px 20px 26px !important;
            }
            .button {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box !important;
                text-align: center !important;
            }
            .metric-value {
                font-size: 24px !important;
            }
        }
    </style>
</head>
<body>
    <div class="preheader">@yield('preheader', 'A message from ' . config('app.name', 'Moshel Edibles'))</div>
    
    <table role="presentation" class="email-shell" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td class="email-gutter" align="center" style="padding: 32px 12px;">
                <table role="presentation" class="email-card" width="600" cellpadding="0" cellspacing="0" border="0">
                    <!-- Modern Gradient Accent Bar -->
                    <tr>
                        <td height="5" style="height: 5px; background: linear-gradient(90deg, #581a62 0%, #ce278c 50%, #f472b6 100%); font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Header with Clean Hosted Logo (Zero attachment chip & renders in all clients) -->
                    <tr>
                        <td class="header-cell" align="center">
                            @php
                                $logoSetting = data_get($pageGlobalData ?? null, 'setting.logo');
                                $logoPath = $logoSetting ? ltrim(str_replace('\\', '/', $logoSetting), '/') : null;
                                $logoPath = $logoPath ? preg_replace('#^public/#i', '', $logoPath) : 'uploads/siteInfo/logo.png';
                                
                                $appUrl = rtrim(config('app.url', url('/')), '/');
                                $isLocalApp = preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?#i', $appUrl);
                                
                                // Hosted Public Asset URL: In production use APP_URL.
                                // In local dev (localhost/127.0.0.1), use the live public HTTPS site (https://moshedibles.com)
                                // so Google's Image Proxy can fetch and render it without showing broken images OR attachment chips!
                                $publicHost = env('MAIL_ASSET_URL', config('mail.asset_url', 'https://moshedibles.com'));
                                $baseUrl = ($isLocalApp && $publicHost) ? rtrim($publicHost, '/') : $appUrl;
                                
                                $logoSource = $baseUrl . '/' . ltrim($logoPath, '/');
                            @endphp
                            
                            <a href="{{ $isLocalApp ? url('/') : $baseUrl }}" target="_blank" style="text-decoration: none; display: inline-block;">
                                <img src="{{ $logoSource }}" width="190" alt="{{ config('app.name', 'Moshel Edibles') }}" style="display: block; width: 190px; max-width: 100%; height: auto; margin: 0 auto; border: 0;">
                            </a>
                            <p class="brand-subtitle">Thoughtfully Made &middot; Artisanal Edibles</p>
                        </td>
                    </tr>

                    <!-- Subtle Divider -->
                    <tr>
                        <td height="1" style="height: 1px; background-color: #f2e7f3; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Email Body Content -->
                    <tr>
                        <td class="content">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Modern Luxury Footer -->
                    <tr>
                        <td class="email-footer">
                            <p style="margin: 0 0 6px; font-weight: 700; font-size: 13px; color: #ffffff; letter-spacing: 0.5px;">{{ config('app.name', 'Moshel Edibles') }}</p>
                            <p style="margin: 0 0 10px; color: #c4b5c7; font-size: 11px;">Exquisite Flavors &middot; Handcrafted Confectionery</p>
                            <p style="margin: 0; color: #a493a7; font-size: 11px;">
                                Automated business communication. Visit <a href="{{ url('/') }}">our online store</a> for inquiries.
                            </p>
                            <p style="margin: 10px 0 0; color: #87748b; font-size: 10px;">
                                &copy; {{ date('Y') }} {{ config('app.name', 'Moshel Edibles') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
