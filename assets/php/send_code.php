<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

function sendCode($email, $subject, $code) {
    global $mail;
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                        //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                    //Enable SMTP authentication
        $mail->Username   = 'noteflixofficial@gmail.com';            //SMTP username
        $mail->Password   = 'qgny uquo xuyb oyme';                      //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;             //Enable implicit TLS encryption
        $mail->Port       = 465;                                     //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('noteflixofficial@gmail.com', 'NoteFlix');    //Add a recipient
        $mail->addAddress($email);                                   //Name is optional
    
        //Content
        $mail->isHTML(true);                                          //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = 'Dear user, <br><br>Your verification code is: <b>' . $code . '</b><br><br>Kind regards,<br>NoteFlix Team';
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
