<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        /* Base Reset */
        body { 
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; 
            margin: 0; padding: 0; width: 100% !important; 
            background-color: #f8f9fa; color: #3d4852; 
        }
        
        .wrapper { 
            width: 100%; table-layout: fixed; background-color: #f8f9fa; padding: 40px 0; 
        }

        .main { 
            background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; 
            border-radius: 12px; border-spacing: 0; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;
        }

        /* Header Area */
        .header { 
            padding: 40px 30px; text-align: center; 
            background: linear-gradient(135deg, #2a3042 0%, #495057 100%);
        }
        .header h1 { 
            margin: 0; color: #ffffff; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;
        }

        /* Content Area */
        .content { 
            padding: 40px; line-height: 1.6; font-size: 16px; 
        }
        .content h2 { 
            margin-top: 0; color: #2d3748; font-size: 19px; font-weight: 600; 
        }
        .content p { margin-bottom: 20px; color: #718096; }

        /* Utility Components */
        .button { 
            display: inline-block; padding: 12px 30px; 
            background-color: #556ee6; color: #ffffff !important; 
            text-decoration: none; border-radius: 8px; font-weight: 600; 
            font-size: 15px; margin-top: 10px;
        }

        .alert-box {
            background-color: #fcf8f3; border-left: 4px solid #f1b44c;
            padding: 15px 20px; margin-bottom: 25px; border-radius: 4px;
        }

        .data-table {
            width: 100%; border-collapse: collapse; margin: 25px 0;
        }
        .data-table th {
            text-align: left; padding: 12px; border-bottom: 2px solid #edf2f7;
            color: #a0aec0; text-transform: uppercase; font-size: 12px; font-weight: 700;
        }
        .data-table td {
            padding: 12px; border-bottom: 1px solid #edf2f7; font-size: 14px;
        }

        /* Footer Area */
        .footer { 
            padding: 30px; text-align: center; font-size: 13px; color: #a0aec0; 
        }
        .footer p { margin: 5px 0; }
        
        /* Mobile Adjustments */
        @media only screen and (max-width: 600px) {
            .main { width: 95% !important; }
            .content { padding: 30px 20px !important; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" align="center">
            <tr>
                <td class="header">
                    <h1>{{ config('app.name', 'Enterprise POS') }}</h1>
                </td>
            </tr>

            <tr>
                <td class="content">
                    @yield('content')
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <p>&copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. All rights reserved.</p>
                    <p>Building 42, Admin Square, Lagos, Nigeria.</p>
                    <p style="margin-top: 15px; font-size: 11px; opacity: 0.7;">
                        This is an automated operational notification. Please do not reply to this email.
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>