<?php
namespace Survey;
class Email {
    protected $mail = null;

    public function __construct($email_config) {
        $this->mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $email_config['server'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $email_config['username'];
        $this->mail->Password = $email_config['password'];
        $this->mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = $email_config['port'];
        $this->mail->setFrom($email_config['admin_email'], 'Survey App');
        $this->mail->isHTML(true);
    }

    public function send_email(string $user_email, $config) {
        $this->mail->addAddress($user_email);
        $this->mail->Subject = $config['subject'];
        $this->mail->Body    = $config['body'];
        $this->mail->AltBody = $config['altBody'];
        $this->mail->send();
        return true;
    }
}
?>