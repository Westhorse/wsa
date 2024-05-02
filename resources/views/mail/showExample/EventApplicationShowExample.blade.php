<div style="width:100%;font-family:lato,'helvetica neue',helvetica,arial,sans-serif;padding:0;Margin:0">
    <div style="background-color:#151719">
        @php
        $template = DB::table('email_templates')->where('slug', 'conference_new_application_confirmation_email_template')->value('body');
        $faker = Faker\Factory::create();
        $fakeEmail = $faker->email;
        $fakeName = $faker->name;
        $fakePassword = $faker->password;
        $login_button = '<a href="https://wsa-events.com/login" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:18px;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:5px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center">LOGIN HERE</a>';
        $template = str_replace('{{email}}', $fakeEmail, $template);
        $template = str_replace('{{delegate_name}}', $fakeName, $template);
        $template = str_replace('{{password}}', $fakePassword, $template);
        $template = str_replace('{{website_button}}', $login_button, $template);
       @endphp
        <table cellspacing="0" cellpadding="0" width="100%"
            style="border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#151719">
            <tbody>
                <tr>
                    <td valign="top" style="padding:0;Margin:0">


                        <table cellspacing="0" cellpadding="0" align="center"
                            style="border-collapse:collapse;border-spacing:0px;table-layout:fixed!important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                            <tbody>
                                <tr>
                                    <td align="center" bgcolor="#000"
                                        style="padding:0;Margin:0;background-color:#000000">
                                        <table
                                            style="border-collapse:collapse;border-spacing:0px;background-color:#151719;width:600px;border-bottom:3px solid #f1c232"
                                            cellspacing="0" cellpadding="0" bgcolor="#151719" align="center">
                                            <tbody>
                                                <tr>
                                                    <td style="padding:10px;Margin:0;background-color:#151719"
                                                        bgcolor="#151719" align="left">
                                                        <table cellspacing="0" cellpadding="0" width="100%"
                                                            style="border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr>
                                                                    <td valign="top" align="center"
                                                                        style="padding:0;Margin:0;width:580px">
                                                                        <table cellspacing="0" cellpadding="0"
                                                                            width="100%" role="presentation"
                                                                            style="border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center"
                                                                                        style="padding:0;Margin:0;font-size:0px">
                                                                                        <img src="https://dashboard.wsa-network.com/storage/media/2024/02/25/1002/Events-Logo-Istanbul-dark.png"
                                                                                            alt=""
                                                                                            style="display:block;border:0;outline:none;text-decoration:none"
                                                                                            height="99"
                                                                                            class="CToWUd"
                                                                                            data-bit="iit"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>








                        <table cellspacing="0" cellpadding="0" align="center"
                            style="border-collapse:collapse;border-spacing:0px;table-layout:fixed!important;width:100%">
                            <tbody>
                                <tr>
                                    <td align="center" style="padding:0;Margin:0">
                                        <table cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center"
                                            style="padding:0 15px;border-collapse:collapse;border-spacing:0px;background-color:#ffffff;width:600px">
                                            <tbody>
                                                <tr>
                                                    <td align="left" style="padding:40px;Margin:0">
                                                        <table cellspacing="0" cellpadding="0" width="100%"
                                                            style="border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="left"
                                                                        style="padding:0;Margin:0;width:520px">
                                                                        <table cellspacing="0" cellpadding="0"
                                                                            width="100%" role="presentation"
                                                                            style="border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                {!! $template !!}
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>





                        <table cellpadding="0" cellspacing="0" align="center"
                            style="text-align: center ; border-collapse:collapse;border-spacing:0px;table-layout:fixed!important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
                            <tbody>
                                <tr>
                                    <td align="center" bgcolor="#000000"
                                        style="padding:0;Margin:0;background-color:#000000">
                                        <table
                                            style="border-collapse:collapse;border-spacing:0px;background-color:#151719;border-top:4px solid #666666;width:600px"
                                            cellspacing="0" cellpadding="0" bgcolor="#151719" align="center">
                                            <tbody>
                                                <tr>
                                                    <td align="left"
                                                        style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
                                                        <table width="100%" cellspacing="0" cellpadding="0"
                                                            style="border-collapse:collapse;border-spacing:0px">
                                                            <tbody>
                                                                <tr>
                                                                    <td valign="top" align="center"
                                                                        style="padding:0;Margin:0;width:560px">
                                                                        <table width="100%" cellspacing="0"
                                                                            cellpadding="0" role="presentation"
                                                                            style="border-collapse:collapse;border-spacing:0px">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td align="center"
                                                                                        style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;font-size:0px">
                                                                                        <table cellspacing="0"
                                                                                            cellpadding="0"
                                                                                            role="presentation"
                                                                                            style="border-collapse:collapse;border-spacing:0px">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td valign="top"
                                                                                                        align="center"
                                                                                                        style="padding:0;Margin:0;padding-right:10px">
                                                                                                        <a href="https://www.facebook.com/World-Shipping-Alliance-221034105342295/"
                                                                                                            style="text-decoration:underline;color:#ffffff;font-size:14px"
                                                                                                            target="_blank"
                                                                                                            data-saferedirecturl="https://www.google.com/url?q=https://www.facebook.com/World-Shipping-Alliance-221034105342295/&amp;source=gmail&amp;ust=1707128937641000&amp;usg=AOvVaw3rEIYk4krpzFnDomlo7f4-"><img
                                                                                                                title="Facebook"
                                                                                                                src="https://ci3.googleusercontent.com/meips/ADKq_NYFf98E8uKzz2u1fB8LTy7gQ-JK4Xi6LBj8F0eZmyBmr5SyQhbsdPsx9QGcLMSLvJxBdqWkYKqlmDqbu3fal0ICbMav6Y-TyyxGhFgPniy2KvcBWfo=s0-d-e1-ft#https://ap.wsa-events.com/storage/emails/facebook-logo-gray.png"
                                                                                                                alt="Fb"
                                                                                                                width="32"
                                                                                                                style="display:block;border:0;outline:none;text-decoration:none"
                                                                                                                class="CToWUd"
                                                                                                                data-bit="iit"></a>
                                                                                                    </td>
                                                                                                    <td valign="top"
                                                                                                        align="center"
                                                                                                        style="padding:0;Margin:0;padding-right:10px">
                                                                                                        <a href="https://www.youtube.com/channel/UCjJjs3gL--CmteQskFyCzZw"
                                                                                                            style="text-decoration:underline;color:#ffffff;font-size:14px"
                                                                                                            target="_blank"
                                                                                                            data-saferedirecturl="https://www.google.com/url?q=https://www.youtube.com/channel/UCjJjs3gL--CmteQskFyCzZw&amp;source=gmail&amp;ust=1707128937641000&amp;usg=AOvVaw1tg6nt_gmQorGDD79tNT8s"><img
                                                                                                                title="Youtube"
                                                                                                                src="https://ci3.googleusercontent.com/meips/ADKq_Nah8gL0ef3z5RyEqf91ODXxp43BdfR3Lakum0CuAqHSUZiQ4vWiyyKQ_HgdbjuaEnGsWIui7-zUC2_ymvdyzlLnLx2ugnUB9yTqDJd4Kxjzg8h29w=s0-d-e1-ft#https://ap.wsa-events.com/storage/emails/youtube-logo-gray.png"
                                                                                                                alt="Yt"
                                                                                                                width="32"
                                                                                                                style="display:block;border:0;outline:none;text-decoration:none"
                                                                                                                class="CToWUd"
                                                                                                                data-bit="iit"></a>
                                                                                                    </td>
                                                                                                    <td valign="top"
                                                                                                        align="center"
                                                                                                        style="padding:0;Margin:0">
                                                                                                        <a href="https://www.linkedin.com/company/worldshippingalliance/?viewAsMember=true"
                                                                                                            style="text-decoration:underline;color:#ffffff;font-size:14px"
                                                                                                            target="_blank"
                                                                                                            data-saferedirecturl="https://www.google.com/url?q=https://www.linkedin.com/company/worldshippingalliance/?viewAsMember%3Dtrue&amp;source=gmail&amp;ust=1707128937641000&amp;usg=AOvVaw1EXUOqH6bv9ucPskDrMFnf"><img
                                                                                                                title="Linkedin"
                                                                                                                src="https://ci3.googleusercontent.com/meips/ADKq_NZXyyXUR01OU0crJI70WnikButjMPAg7AnIgWNuBYk1ctmORmWkFyhP4sTnVcq3ONG28dr1-eb8IyCLB1GX5MPYYSjYP9sluGR8pC3hEYHo2cPq4Fc=s0-d-e1-ft#https://ap.wsa-events.com/storage/emails/linkedin-logo-gray.png"
                                                                                                                alt="In"
                                                                                                                width="32"
                                                                                                                style="display:block;border:0;outline:none;text-decoration:none"
                                                                                                                class="CToWUd"
                                                                                                                data-bit="iit"></a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="center"
                                                                                        style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px;margin: auto ; text-align: center" >
                                                                                        <p
                                                                                            style="text-align:center !important;margin:0;font-family:lato,'helvetica neue',helvetica,arial,sans-serif;line-height:21px;color:#999999;font-size:14px">
                                                                                            for more information please
                                                                                            contact us at&nbsp;</p>
                                                                                        <p
                                                                                            style="text-align:center !important; margin:0;font-family:lato,'helvetica neue',helvetica,arial,sans-serif;line-height:21px;color:#ffffff;font-size:14px">
                                                                                            <a href="mailto:info@worldshippingalliance.com"
                                                                                                target="_blank">info@worldshippingalliance.com</a>
                                                                                        </p>
                                                                                        <p
                                                                                            style="text-align:center !important;margin:0;font-family:lato,'helvetica neue',helvetica,arial,sans-serif;line-height:21px;color:#999999;font-size:14px;display:none">
                                                                                            &nbsp;</p>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
            </tbody>
        </table>
        <div class="yj6qo"></div>
        <div class="adL">
        </div>
    </div>
    <div class="adL">
    </div>
</div>
