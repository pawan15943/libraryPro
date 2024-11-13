<html xmlns="https://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Library Registration Successful</title>

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
                    <p style="margin: 0; margin-top: 15px; font-weight: 500; line-height: 24px;">>Welcome to the Library, {{ $name }}!</p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;">Thank you for registering with our library. We're excited to have you as a member!</p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;">Enjoy our collection and the benefits of being part of our community.</p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;">Library No.</p>
                    <h2 style="margin: 0; font-weight: 600; padding: 1rem 0;">{{$library_no}}</h2>
                  
                </td>
            </tr>
            <tr>
                <td style="padding: 0px 15px;">
                    <p style="margin: 0; font-weight: 500; line-height: 24px;">Should you have any questions or
                        require further assistance, please do not hesitate to contact our technical support team using the following
                        details:</p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;"><b>Technical Support Email ID:</b>
                        <a href="mailto:support@allenoverseas.com">support@allenoverseas.com</a></p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;"><b>Technical Support Mobile
                            Number:</b> <a href="tel:+971 45461696">+971 45461696</a></p>
                    <p style="margin: 0; margin-top: 10px; font-weight: 500; line-height: 24px;">We wish you the best of luck in
                        your endeavors.</p>
                </td>
            </tr>
            <tr>
                <td style="padding: 0px 15px; margin-top: 16px;">
                    <p style="margin: 0; margin-top: 10px; font-weight: 700; line-height: 24px;"><b>Best Regards,</b></p>
                    <p style="margin: 0; margin-bottom: 15px; font-weight: 500; line-height: 24px;">-Team Libraro</p>
                </td>
            </tr>
            <tr>
         
        </tbody>
    </table>

</body>

</html>