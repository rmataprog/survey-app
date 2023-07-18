<?php
/*
declare(strict_types = 1);
include '../src/bootstrap.php';

$data = $cms->getUser()->getUser('rmata.ufs@gmail.com');

$valid = password_verify('18657395', $data['password_']);

echo $valid ? 'verified' : 'not verified';

$email = 'rmata.ufs@gmail.com';
$first_name = 'Ruben';
$last_name = 'Mata';
$coordinator = 1;
$password = '18657395';
$hash = password_hash($password, PASSWORD_DEFAULT);
$cms->getUser()->register($first_name, $last_name, $email, $coordinator, $hash);*/


$mail = new \PHPMailer\PHPMailer\PHPMailer(true);

$email_config = [
    'server'=>'smtp.gmail.com',
    'port'=>465,
    'username'=>'rmata.ufs@gmail.com',
    'password'=>'etgxmwmjtppflmwr',
    'security'=>'tls',
    'admin_email'=>'postmaster@localhost.com',
    'debug'=>(DEV) ? 2 : 0
];


// echo strlen(bin2hex(random_bytes(64)));

try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $email_config['server'];                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $email_config['username'];                     //SMTP username
    $mail->Password   = $email_config['password'];                               //SMTP password
    $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = $email_config['port'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($email_config['admin_email'], 'Survey App');
    $mail->addAddress('ruben.solid.mata.snake.88@gmail.com');     //Add a recipient

    //Content
    $mail->isHTML(true);                          //Set email format to HTML
    $mail->Subject = 'this is a test';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>