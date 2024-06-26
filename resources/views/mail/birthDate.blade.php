{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>



    @foreach ($contact->media as $date)

     {{$date->file_path}}

          <img align="center" alt="" src="{{asset('storage/'.$date->file_path)}}" data-bit="iit">


    @endforeach
</body> --}}

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <!--[if gte mso 9]><xml>
 <o:OfficeDocumentSettings>
 <o:AllowPNG/>
 <o:PixelsPerInch>96</o:PixelsPerInch>
 </o:OfficeDocumentSettings>
 </xml><![endif]-->
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="format-detection" content="date=no" />
    <meta name="format-detection" content="address=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>Email Template</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style type="text/css" media="screen">
        body {
            padding: 0 !important;
            margin: 0 !important;
            display: block !important;
            background: #1e1e1e;
            -webkit-text-size-adjust: none
        }

        a {
            color: #a88123;
            text-decoration: none
        }

        p {
            padding: 0 !important;
            margin: 0 !important
        }


        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            position: relative;
            margin: 15% auto;
            padding: 20px;
            width: 100%;
        }

        .close {
            position: absolute;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaaaaa;
        }

        /* Mobile styles */
    </style>
    <style media="only screen and (max-device-width: 480px), only screen and (max-width: 480px)" type="text/css">
        @media only screen and (max-device-width: 480px),
        only screen and (max-width: 480px) {
            div[class='mobile-br-5'] {
                height: 5px !important;
            }

            div[class='mobile-br-10'] {
                height: 10px !important;
            }

            div[class='mobile-br-15'] {
                height: 15px !important;
            }

            div[class='mobile-br-20'] {
                height: 20px !important;
            }

            div[class='mobile-br-25'] {
                height: 25px !important;
            }

            div[class='mobile-br-30'] {
                height: 30px !important;
            }

            th[class='m-td'],
            td[class='m-td'],
            div[class='hide-for-mobile'],
            span[class='hide-for-mobile'] {
                display: none !important;
                width: 0 !important;
                height: 0 !important;
                font-size: 0 !important;
                line-height: 0 !important;
                min-height: 0 !important;
            }

            span[class='mobile-block'] {
                display: block !important;
            }

            div[class='wgmail'] img {
                min-width: 320px !important;
                width: 320px !important;
            }

            div[class='img-m-center'] {
                text-align: center !important;
            }

            div[class='fluid-img'] img,
            td[class='fluid-img'] img {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
            }

            table[class='mobile-shell'] {
                width: 100% !important;
                min-width: 100% !important;
            }

            td[class='td'] {
                width: 100% !important;
                min-width: 100% !important;
            }

            table[class='center'] {
                margin: 0 auto;
            }

            td[class='column-top'],
            th[class='column-top'],
            td[class='column'],
            th[class='column'] {
                float: left !important;
                width: 100% !important;
                display: block !important;
            }

            td[class='content-spacing'] {
                width: 15px !important;
            }

            div[class='h2'] {
                font-size: 44px !important;
                line-height: 48px !important;
            }
        }
    </style>
</head>

<body class="body"
    style="padding:0 !important; margin:0 !important; display:block !important; background:#8a7288; -webkit-text-size-adjust:none">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#9bddff">
        <tr>
            <td align="center" valign="top">


                <table width="600" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                    <tr>
                        <td class="td"
                            style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; width:600px; min-width:600px; Margin:0"
                            width="600">
                            <!-- Header -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left"
                                        width="20"></td>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                            class="spacer"
                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                            <tr>
                                                <td height="30" class="spacer"
                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                    &nbsp;</td>
                                            </tr>
                                        </table>

                                        <div class="img-center"
                                            style="font-size:0pt; line-height:0pt; text-align:center"><a href="#"
                                                target="_blank"><img
                                                    src="https://wsa-consol.com/storage/media/2023/07/19/300/WSA.png"
                                                    border="0" width="203" alt="" /></a></div>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                            class="spacer"
                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                            <tr>
                                                <td height="30" class="spacer"
                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                    &nbsp;</td>
                                            </tr>
                                        </table>



                                    </td>
                                    <td class="content-spacing" style="font-size:0pt; line-height:0pt; text-align:left"
                                        width="20"></td>
                                </tr>
                            </table>
                            <!-- END Header -->

                            <!-- Main -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <!-- Head -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                            bgcolor="#d2973b">
                                            <tr>
                                                <td>
                                                    <table width="100%" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <tr>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="27">
                                                            </td>
                                                            <td>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0">
                                                                    <tr>
                                                                        <td class="img"
                                                                            style="font-size:0pt; line-height:0pt; text-align:left"
                                                                            height="3" bgcolor="#e6ae57">&nbsp;
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="24" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                            </td>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="27">

                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table width="100%" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <tr>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="3" bgcolor="#e6ae57"></td>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="10"></td>
                                                            <td>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="20" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                                <div class="h3-2-center"
                                                                    style="color:#1e1e1e; font-family:Arial, sans-serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center; letter-spacing:5px">
                                                                    IT‘S YOUR</div>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="5" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>


                                                                <div class="h2"
                                                                    style="color:#ffffff; font-family:Georgia, serif; min-width:auto !important; font-size:60px; line-height:64px; text-align:center">
                                                                    <em>Birthday!</em>
                                                                </div>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="30" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                            </td>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="10"></td>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="3" bgcolor="#e6ae57"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- END Head -->

                                        <!-- Body -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                            bgcolor="#ffffff">
                                            <tr>
                                                <td>
                                                    <table width="100%" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <tr>
                                                            <td class="content-spacing"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="20"></td>
                                                            <td>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="35" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                                <div class="h3-1-center"
                                                                    style="color:#1e1e1e; font-family:Georgia, serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center">
                                                                    Another adventure filled year awaits you. Welcome it
                                                                    by celebrating your birthday with pomp and splendor.
                                                                    Wishing you a very happy and fun-filled birthday!.
                                                                </div>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="15" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>



                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="25" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>


                                                                <!-- Button -->
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0">
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table width="210" border="0"
                                                                                cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td align="center"
                                                                                        bgcolor="#d2973b">
                                                                                        <table border="0"
                                                                                            cellspacing="0"
                                                                                            cellpadding="0">
                                                                                            <tr>
                                                                                                <td class="img"
                                                                                                    style="font-size:0pt; line-height:0pt; text-align:left"
                                                                                                    width="15">
                                                                                                    <table
                                                                                                        width="100%"
                                                                                                        border="0"
                                                                                                        cellspacing="0"
                                                                                                        cellpadding="0"
                                                                                                        class="spacer"
                                                                                                        style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                                                        <tr>
                                                                                                            <td height="50"
                                                                                                                class="spacer"
                                                                                                                style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                                                                &nbsp;
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </table>
                                                                                                </td>
                                                                                                <td bgcolor="#d2973b">
                                                                                                    <div class="text-btn"
                                                                                                        style="color:hsl(0, 0%, 100%); font-family:Arial, sans-serif; min-width:auto !important; font-size:16px; line-height:20px; text-align:center">
                                                                                                        <a href="#"
                                                                                                            target="_blank"
                                                                                                            onclick="openModal(); return false;"
                                                                                                            class="link-white"
                                                                                                            style="color:#ffffff; text-decoration:none">
                                                                                                            <span
                                                                                                                class="link-white"
                                                                                                                onclick="openModal()"
                                                                                                                style="color:#ffffff; text-decoration:none">LET'S
                                                                                                                CELEBRATE</span>
                                                                                                        </a>
                                                                                                    </div>
                                                                                                </td>
                                                                                                <div id="myModal"
                                                                                                    class="modal">
                                                                                                    <div
                                                                                                        class="modal-content">
                                                                                                        <span
                                                                                                            class="close"
                                                                                                            onclick="closeModal()">&times;</span>
                                                                                                        <video controls
                                                                                                            autoplay>
                                                                                                            <source
                                                                                                                src="https://dashboard.wsa-network.com/storage/media/2023/08/20/518/HappyBirthday.mp4"
                                                                                                                width="30px"
                                                                                                                type="video/mp4">
                                                                                                            Your browser
                                                                                                            does not
                                                                                                            support the
                                                                                                            video tag.
                                                                                                        </video>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <td class="img"
                                                                                                    style="font-size:0pt; line-height:0pt; text-align:left"
                                                                                                    width="15">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <!-- END Button -->
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="35" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                            </td>
                                                            <td class="content-spacing"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="20"></td>
                                                        </tr>
                                                    </table>
                                                    <div class="fluid-img"
                                                        style="font-size:0pt; line-height:0pt; text-align:left"><a
                                                            href="#" target="_blank"><img
                                                                src="https://d1pgqke3goo8l6.cloudfront.net/INqi55TjRoOqXGNSngcN_img_1.jpg"
                                                                border="0" width="600" height="220"
                                                                alt="" /></a></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- END Body -->

                                        <!-- Foot -->
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                            bgcolor="#d2973b">
                                            <tr>
                                                <td>
                                                    <table width="100%" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <tr>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="3" bgcolor="#e6ae57"></td>
                                                            <td>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="30" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                                <div class="h3-1-center"
                                                                    style="color:#1e1e1e; font-family:Georgia, serif; min-width:auto !important; font-size:20px; line-height:26px; text-align:center">
                                                                    <em>Follow Us</em>
                                                                </div>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="15" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>


                                                                <!-- Socials -->
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0">
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table border="0" cellspacing="0"
                                                                                cellpadding="0">
                                                                                <tr>
                                                                                    <td class="img-center"
                                                                                        style="font-size:0pt; line-height:0pt; text-align:center"
                                                                                        width="38"><a
                                                                                            href="https://www.facebook.com/worldshippingalliance"
                                                                                            target="_blank"><img
                                                                                                style="border-radius: 50% 50%;"
                                                                                                src="https://dashboard.wsa-network.com/storage/media/2023/08/20/520/pngegg.png"
                                                                                                border="0"
                                                                                                width="28"
                                                                                                height="28"
                                                                                                alt="" /></a>
                                                                                    </td>
                                                                                    <td class="img-center"
                                                                                        style="font-size:0pt; line-height:0pt; text-align:center"
                                                                                        width="38"><a
                                                                                            style="color: red !important;"
                                                                                            href="https://www.linkedin.com/company/worldshippingalliance/?viewAsMember=true"
                                                                                            target="_blank"><img
                                                                                                style="border-radius: 50% 50%;"
                                                                                                src="https://dashboard.wsa-network.com/storage/media/2023/08/20/522/youtupe.png"
                                                                                                border="0"
                                                                                                width="40"
                                                                                                height="40"
                                                                                                alt="" /></a>
                                                                                    </td>
                                                                                    <td class="img-center"
                                                                                        style="font-size:0pt; line-height:0pt; text-align:center"
                                                                                        width="38"><a
                                                                                            href="https://www.youtube.com/channel/UCjJjs3gL--CmteQskFyCzZw"
                                                                                            target="_blank"><img
                                                                                                style="border-radius: 50% 50%;"
                                                                                                src="https://cdn.pnghd.pics/data/739/linkedin-logo-white-png-2.png"
                                                                                                border="0"
                                                                                                width="28"
                                                                                                height="28"
                                                                                                alt="" /></a>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                                <!-- END Socials -->
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="15" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                            </td>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="3" bgcolor="#e6ae57"></td>
                                                        </tr>
                                                    </table>
                                                    <table width="100%" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <tr>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="27">
                                                            </td>
                                                            <td>
                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0" class="spacer"
                                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                    <tr>
                                                                        <td height="24" class="spacer"
                                                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                                            &nbsp;</td>
                                                                    </tr>
                                                                </table>

                                                                <table width="100%" border="0" cellspacing="0"
                                                                    cellpadding="0">
                                                                    <tr>
                                                                        <td class="img"
                                                                            style="font-size:0pt; line-height:0pt; text-align:left"
                                                                            height="3" bgcolor="#e6ae57">&nbsp;
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td class="img"
                                                                style="font-size:0pt; line-height:0pt; text-align:left"
                                                                width="27">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- END Foot -->
                                    </td>
                                </tr>
                            </table>
                            <!-- END Main -->

                            <!-- Footer -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="content-spacing"
                                        style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                    <td>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                            class="spacer"
                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                            <tr>
                                                <td height="30" class="spacer"
                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                    &nbsp;</td>
                                            </tr>
                                        </table>

                                        <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                            class="spacer"
                                            style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                            <tr>
                                                <td height="30" class="spacer"
                                                    style="font-size:0pt; line-height:0pt; text-align:center; width:100%; min-width:100%">
                                                    &nbsp;</td>
                                            </tr>
                                        </table>

                                    </td>
                                    <td class="content-spacing"
                                        style="font-size:0pt; line-height:0pt; text-align:left" width="20"></td>
                                </tr>
                            </table>
                            <!-- END Footer -->
                        </td>
                    </tr>
                </table>
                <div class="wgmail" style="font-size:0pt; line-height:0pt; text-align:center"><img
                        src="https://d1pgqke3goo8l6.cloudfront.net/oD2XPM6QQiajFKLdePkw_gmail_fix.gif" width="600"
                        height="1" style="min-width:600px" alt="" border="0" /></div>
            </td>
        </tr>
    </table>

    <script>
        function showVideo() {
            var videoContainer = document.getElementById('video-container');
            videoContainer.style.display = 'block';
        }

        function openModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "block";

            var video = modal.querySelector("video");
            video.play();
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";

            var video = modal.querySelector("video");
            video.pause();
        }
    </script>
</body>

</html>
