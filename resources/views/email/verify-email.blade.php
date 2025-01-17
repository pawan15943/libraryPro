<html xmlns="https://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Libraro</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="https://www.allenoverseas.com/wp-content/uploads/2022/06/favicon.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link
        href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">


    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Raleway';
        }
    </style>
</head>

<body style="display: block;">


    <table class="main-mailer" width="722" border="0" cellspacing="0" cellpadding="0" align="center"
        style="border:1px solid #cccccc; padding:0;">
        <tbody>
            <tr>
                <td style="text-align: center;">
                    <img style="display:block; width:730px;"
                        src="{{asset('public/emailer/txo-logo.jpg')}}">
                </td>
            </tr>
            <tr>
                <td style="padding: 0px 15px;">
                    <p style="margin: 0; margin-top: 15px; font-weight: 500; line-height: 24px;">Dear {{$name}},</p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;">Thank you for signing up with <b>Library Manager!</b> To verify your email address, please use the following OTP (One-Time Password):</p>
                    <h2 style="margin: 0; font-weight: 600; padding: 1rem 0;">{{$otp}}</h2>
                  
                </td>
            </tr>
            <tr>
                <td style="padding: 0px 15px;">
                    <p style="margin: 0; font-weight: 500; line-height: 24px;">This OTP is valid for the next 10 minutes.</p>
                    <p style="margin: 0; font-weight: 500; line-height: 24px;">Need Assistance?</p>
                    <p style="margin: 0; font-weight: 500; line-height: 24px;">If you have any questions or encounter any issues, feel free to contact our support team.</p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;"><b>Email:</b>
                        <a href="mailto:info@librarymanager.in">info@librarymanager.in</a></p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;"><b>Phone Number:</b> <a href="tel:+918114479678">+91-8114479678</a>, 
                        <a href="tel:+917737918848">+91-7737918848</a></p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;">Thank you for choosing Library Manager!</p>
                </td>
            </tr>
            <tr>
                <td style="padding: 0px 15px; margin-top: 16px;">
                    <p style="margin: 0; margin-top: 10px; font-weight: 700; line-height: 24px;"><b>Team,</b></p>
                    <p style="margin: 0; margin-bottom: 15px; font-weight: 500; line-height: 24px;">Libraro- A product by Techito</p>
                </td>
            </tr>
            <tr>
         
        </tbody>
    </table>

</body>

</html>