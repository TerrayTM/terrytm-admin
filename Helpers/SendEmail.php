<?php

if (!function_exists("send_email")) {
    function send_email($destination, $from, $message, $subject = "Message", $sanitize = false) {
        if ($sanitize) {
            $from = htmlspecialchars(strip_tags($from));
            $message = nl2br(htmlspecialchars(strip_tags($message)));
            $subject = htmlspecialchars(strip_tags($subject));
        }

        $headers = "From: " . $from . "\n";
        $headers .= "Return-Path: " . $from . "\n";
        $headers .= "Reply-To: " . $from . "\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1";

        $body = '
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>' . $subject . '</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            </head>
            
            <body style="margin: 0; padding: 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="padding: 10px 0 30px 0;">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; border: 1px solid cyan;">
                                <tr>
                                    <td bgcolor="#4d72de" style="padding: 30px; font-size: 16px; font-weight: bold; font-family: Arial, sans-serif;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="left">
                                                    <h1 style="margin: 0; color: white;">Terry&trade; API</h1>
                                                </td>
                                                <td align="right">
                                                    <h1 style="margin: 0; color: white;">&lt;(&#9733;_&#9733;&lt;)</h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#ffffff" style="padding: 40px 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;"><b>' . $subject . '</b></td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 20px 0; color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">' . $message . '</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td bgcolor="#002b36" style="padding: 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td align="left" style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;">
                                                    Copyright &reg; Terry Zheng 2020
                                                </td>
                                                <td align="right" style="font-family: Arial, sans-serif; font-size: 14px;">
                                                    <a href="mailto:contact@terrytm.com" style="color: #ffffff;">contact@terrytm.com</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            
            </html>
        ';

        return mail($destination, $subject, $body, $headers, "-f" . $from);
    }
}

?>