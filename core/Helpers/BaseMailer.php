<?php

namespace Erp360\Core\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class BaseMailer {

    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mail->isSMTP();
        $this->mail->Host       = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $_ENV['MAIL_USER'];
        $this->mail->Password   = $_ENV['MAIL_PASS'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = $_ENV['MAIL_PORT'];
        $this->mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_NAME']);
    }

    public function setRecipient(string $email): void
    {
        $this->mail->addAddress($email);
    }

    public function setSubject(string $subject): void
    {
        $this->mail->Subject = $subject;
    }

    public function setBody( string $body ): void
    {
        $this->mail->Body = $body;
    }

    public function send(): bool
    {
        $this->mail->isHTML(true);

        $response = true;
        try {
            $this->mail->send();
        } catch (\Throwable $e) {
            $response = false;
        }

        return $response;

    }

}