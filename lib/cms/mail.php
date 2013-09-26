<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms;

use Cms\Mail\PHPMailer;
use Cms\Mail\HTML2Text;

/**
 * Wrapper function around the php mailer for easy mail sending.
 */
class Mail
{
    /**
     * Sends an email using phpmailer with system configurations for mail on admin/settings/mailer.
     *
     * @param array $to In the format $to["John Smoth"] = "jsmith@domain.com"
     * @param string $html_message html code to send
     * @param string $alt_message optional plain text message in case email client doesn't supports html
     * @param array $attachments files path list to attach
     * @param array $reply_to In the format $reply_to["John Smith"] = "jsmith@domain.com"
     * @param array $bcc In the format $bcc["John Smith"] = "jsmith@domain.com"
     * @param array $cc In the format $cc["John Smith"] = "jsmith@domain.com"
     * @param array $from In the format $cc["John Smith"] = "jsmith@domain.com"
     * 
     * @return bool True if sent false if not.
     */
    public static function Send($to, $subject, $html_message, $alt_message = null, $attachments = array(), $reply_to = array(), $bcc = array(), $cc = array(), $from = array())
    {
        $mail = new PHPMailer();

        $mail->isHTML();
        $mail->CharSet = "utf-8";
        $mail->Subject = $subject;
        $mail->msgHTML($html_message);
        $mail->WordWrap = 50;
        
        if($alt_message)
        {
            $mail->AltBody = $alt_message;
        }
        else
        {
            $plain_text = new HTML2Text;
            $plain_text->set_html($html_message);
            $mail->AltBody = $plain_text->get_text();
        }
            

        if(count($from) > 0)
        {
            foreach($from as $from_name => $from_email)
            {
                $mail->setFrom($from_email, $from_name);
                break;
            }
        }
        else
        {
            $mail->setFrom(
                System::GetSiteSettings()->Get('mailer_from_email'), 
                System::GetSiteSettings()->Get('mailer_from_name')
            );
        }

        switch(System::GetSiteSettings()->Get('mailer'))
        {
            case 'sendmail':
                $mail->isSendmail();
                break;
            case 'smtp':
            {
                    $mail->isSMTP();

                    $mail->SMTPAuth = System::GetSiteSettings()->Get('smtp_auth');
                    if(System::GetSiteSettings()->Get('smtp_ssl'))
                    {
                        $mail->SMTPSecure = 'ssl';
                    }
                    $mail->Host = System::GetSiteSettings()->Get('smtp_host');
                    $mail->Port = System::GetSiteSettings()->Get('smtp_port');

                    $mail->Username = System::GetSiteSettings()->Get('smtp_user');
                    $mail->Password = System::GetSiteSettings()->Get('smtp_pass');
                    break;
            }
            default:
                $mail->isMail();
        }

        foreach($reply_to as $name => $email)
        {
            $mail->addReplyTo($email, $name);
        }

        //Add email addresses
        foreach($to as $name => $email)
        {
            $mail->addAddress($email, $name);
        }

        //Add hidden carbon copies
        foreach($bcc as $name => $email)
        {
            $mail->addBCC($email, $name);
        }

        //Add carbon copies
        foreach($cc as $name => $email)
        {
            $mail->addCC($email, $name);
        }

        foreach($attachments as $file_name => $file_path)
        {
            if(!is_int($file_name))
                $mail->addAttachment($file_path, $file_name);
            else
                $mail->addAttachment($file_path);
        }

        return $mail->send();
    }

}

?>
