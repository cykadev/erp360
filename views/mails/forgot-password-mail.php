<?php
Erp360\Core\Helpers\SiteHelper::renderView('mails/layout/base-email-top');
?>

<!-- START MAIN CONTENT AREA -->
<tr>
    <td class="wrapper">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <p>Hi <?php echo $data['name']; ?>,</p>
                    <p>Sometimes you just want to send a simple HTML email with a simple design and clear call to action. This is it.</p>
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                        <tbody>
                            <tr>
                                <td align="left">
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo Erp360\Core\Helpers\SiteHelper::navigator('auth/reset/pass/token?identifier=') . $data['token']; ?> 
                                                    "target="_blank">Reset Password</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>This is a really simple email template. Its sole purpose is to get the recipient to click the button with no distractions.</p>
                    <p>Good luck! Hope it works.</p>
                </td>
            </tr>
        </table>
    </td>
</tr>
<!-- END MAIN CONTENT AREA -->

<?php
Erp360\Core\Helpers\SiteHelper::renderView('mails/layout/base-email-bottom');
?>