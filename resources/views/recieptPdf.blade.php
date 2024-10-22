<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management Software Subscription Receipt</title>

    <style>
        body {
            font-size: 13px;
            color: #333;
            font-family: 'Roboto', sans-serif;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px 12px;
        }

        h2, h3, h4, h5, h6 {
            margin: 0px;
        }

        p {
            line-height: 22px;
            font-size: 13px;
        }

        b {
            color: #000;
        }

        .tab_title {
            font-size: 21px;
            font-family: 'PT Serif', serif;
        }

        .logo img {
            margin-top: 15px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .receipt_header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 30px;
        }

        .address_header h5 {
            color: #000;
            font-size: 15px;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .address_header .address {
            max-width: 270px;
            margin-left: auto;
            font-size: 14px;
            line-height: 22px;
            margin-bottom: 15px;
        }

        .address_header a {
            color: #333;
            text-decoration: none;
        }

        .pdf_descContent li, .pdf_descContent p {
            line-height: 26px;
        }
    </style>
</head>

<body>
    <div class="receipt_wrapper">
        <!-- header -->
        <div class="receipt_header">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Library Logo">
            </div>
            <div class="address_header text-right">
                <h5>Library Management System Headquarters:</h5>
                <div class="address">
                    123 Library Road, Knowledge City<br>
                    Near BookHub Station, Cityville, Countryland
                </div>
                <a href="www.librarysystem.com" title="Library System">Website: www.librarysystem.com</a><br>
                <a href="mailto:support@librarysystem.com" title="Library System">Email: support@librarysystem.com</a>
            </div>
        </div>

        <!-- Main content-->
        <table>
            <thead class="text-center">
                <tr>
                    <th colspan="4" class="tab_title">Subscription Receipt - Library Management Software</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:30%"><b>Subscription Plan:</b></td>
                    <td style="width:15%">
                        <?php if(isset($subscription)){ echo $subscription; } ?>
                    </td>
                    <td style="width:45%"><b>Subscription Date:</b></td>
                    <td style="width:15%">
                        <?php if(isset($transactiondate)){ echo $transactiondate; } ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Invoice Number:</b></td>
                    <td>
                        <?php if(isset($invoice_ref_no)){ echo $invoice_ref_no; } ?>
                    </td>
                    <td><b>Expiration Date:</b></td>
                    <td>
                        <?php if(isset($end_date)){ echo $end_date; } ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Subscriber Name:</b></td>
                    <td colspan="3">
                     
                        <?php if(isset($name)){ echo $name; } ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Email Address:</b></td>
                    <td colspan="3">
                        <?php if(isset($email)){ echo $email; } ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Payment Method:</b></td>
                    <td>
                        <?php 
                        if ($payment_mode == 1) { 
                            echo 'Online'; 
                        } elseif ($payment_mode == 2) {
                            echo 'Offline'; 
                        } else {
                            echo 'Pay Later'; 
                        }
                    ?>
                    
                    </td>
                    <td><b>Amount Paid:</b></td>
                    <td>
                        <?php if(isset($paid_amount)){ echo $paid_amount; } ?>
                        <?php if(isset($currency)){ echo $currency; } ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Total Amount:</b></td>
                    <td>
                        <?php if(isset($monthly_amount)){ echo $monthly_amount; } ?>
                    </td>
                    <td><b>Plan Duration:</b></td>
                    <td>
                        <?php if(isset($month)){ echo $month; } ?> <b>month</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <h4>Terms & Conditions</h4>
                        <ul class="pdf_descContent">
                            <li>This receipt is not a VAT Invoice.</li>
                            <li>VAT Invoice will be provided upon request within 30 days.</li>
                            <li>This is a computer-generated receipt; no signature is required.</li>
                            <li>All subscription plans (Basic, Standard, and Premium) are non-refundable and non-transferable.</li>
                            <li>Plan upgrades are available at any time with additional charges applied.</li>
                        </ul>

                        <h4>Refund Policy</h4>
                        <ul class="pdf_descContent">
                            <li>No refunds will be issued once the subscription is activated. Please review your plan carefully before making a purchase.</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
