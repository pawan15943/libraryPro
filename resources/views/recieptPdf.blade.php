<?php
function getIndianCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' fils' : '';
    return ($Rupees ? $Rupees : '') . $paise;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allen Overseas Transportation Fee Receipt Acknowledgement</title>

    <style>
        body {
            font-size: 13px;
            color: #333;
            font-family: 'Roboto', sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px 12px;
        }

        td ul>li {
            line-height: 20px;
            margin-bottom: 3px;
            font-size: 13px;
        }

        h2,
        h3,
        h4,
        h5,
        h6 {
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
            /* display: block; */
        }

        .text-right {
            text-align: right;
        }

        span.text-center {
            display: block;
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
            white-space: wrap;
            margin-left: auto;
            font-size: 14px;
            line-height: 22px;
            margin-bottom: 15px;
        }

        .address_header a {
            color: #333;
            text-decoration: none;
        }

        table::before {
            background-image: url(https://www.allenoverseas.com/tallentex/wp-content/themes/Allen_Theme/tallentex_assets/images/logo/logo.png);
            background-size: 70px;
            position: absolute;
            top: -37%;
            left: -10%;
            content: "";
            width: 200%;
            height: 200%;
            transform: rotate(-10deg);
            opacity: 0.07;
            background-repeat: round;
        }

        table {
            position: relative;
            overflow: hidden;
        }

        .pdf_descContent li,
        .pdf_descContent p {
            line-height: 26px;
        }
    </style>
</head>

<body>
    <div class="recipet_wrapper">

        <!-- header -->
        <div class="receipt_header"
            style="display: flex; align-items:center;justify-content: space-between;padding-bottom: 30px;">
            <div class="logo" style="display:inline-block; margin-top:15px;">
                <img src="{{ asset('img/logo.png') }}" alt="Allenoverseas Logo">
            </div>
            <div class="address_header text-right" style="margin-top:-80px;">
                <h5>Corporate Office:</h5>
                <div class="address">
                    Unit 408-409, The Business Centre,Khalid Bin Al Waleed Rd<br>
                    Bur Dubai, (near Burjuman Metro station), Dubai
                </div>
                <a href="www.allenoverseas.com" title="Allen overseas">Website : www.allenoverseas.com</a><br>
                <a href="mailto:enquiry@allenoverseas.com" title="Allen overseas">Email : enquiry@allenoverseas.com</a>
            </div>
        </div>
        <!-- Main content-->
        <table>
            <thead class="text-center">
                <tr>
                    <th colspan="4" class="tab_title">Fee Acknowledgement Receipt
                        <?php if(isset($month)){ echo $month; } ?> <b>month</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:30%"><b>ALLEN Overseas Form No.</b></td>
                    <td style="width:15%">
                        <?php if(isset($data['form_num'])){ echo $data['form_num']; } ?>
                    </td>
                    <td style="width:45%"><b>Acknowledgement Date:</b></td>
                    <td style="width:15%">
                        <?php if(isset($transactiondate)){ echo $transactiondate; } ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Invoice Reference No. </b></td>
                    <td>
                        <?php if(isset($invoice_ref_no)){ echo $invoice_ref_no; } ?>
                    </td>
                    

                </tr>
                <tr>
                    <td><b>Candidate Name:</b></td>
                    <td colspan="3">
                        <?php if(isset($data['name'])){ echo $data['name']; } ?>
                    </td>

                </tr>
                <tr>
                    <td><b>Father's Name:</b></td>
                    <td colspan="3">
                        <?php if(isset($data['father_name'])){ echo $data['father_name']; } ?>
                    </td>

                </tr>
               
                <tr>
                    <td><b>Payment Mode:</b></td>
                    <td>
                        <?php if(isset($payment_mode)){ echo $payment_mode; } ?>
                    </td>
                    <td><b>Amount Received:</b></td>
                    <td>
                        <?php if(isset($paid_amount)){ echo $paid_amount; } ?>
                        <?php if(isset($currency)){ echo $currency; } ?>
                    </td>
                </tr>
                <tr>
                    <td><b>Total amount </b></td>
                    <td>
                        <?php if(isset($total_amount)){ echo $total_amount; } ?>
                    </td>
                   
                </tr>
               
              
                <tr>
                  
                    <td colspan="4">
                        <h4 class="text-left">Terms & Conditions</h4>
                        <ul class="pdf_descContent">
                            <li>This is not a VAT Invoice.</li> 
                            <li>VAT Invoice Cum Receipt will be uploaded on student portal within 30 days.</li>
                            <li>This is a computer-generated acknowledgement; hence no signature is required.</li>
                            <li>The first installment and the subsequent installments of the course fee paid by you for
                                enrolling in the ALLEN Overseas Classroom Program are non-refundable. You acknowledge
                                that because of any reason whatsoever, you decide not to continue with your classes for
                                the academic year that you have enrolled for, You will not be entitled to any refund
                                from ALLEN Overseas or any of its partners/subsidiaries/associates/third-party service
                                providers.</li>
                            <li>1st installment to be paid at the time of enrollment. 2nd installment to be paid within
                                45 days from course commencement date.</li>
                        </ul>

                        <h4 class="text-left">Refund Rules</h4>
                        <ul class="pdf_descContent">
                            <li>We at ALLEN would like to inform you that we have a strict NO REFUND / MONEY RETURN / ADMISSION CANCELLATION POLICY regarding all matters of monetary transactions. Once the payment is made, it will not be returned or refunded under any circumstances.
                                </li>
                        </ul>

                    </td>
                </tr>

            </tbody>
        </table>


    </div>

</body>

</html>