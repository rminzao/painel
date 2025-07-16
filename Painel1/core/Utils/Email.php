<?php

namespace Core\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    /** @var */
    private $data;

    /** @var PHPMailer */
    private $mail;

    /** @var Message */
    private $message;

    /**
     * Email constructor.
     */
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->data = new \stdClass();
        $this->message = new Message();

        //setup
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Host       = (string)$_ENV['MAIL_HOST'];                     //Set the SMTP server to send through
        $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $this->mail->Username   = (string)$_ENV['MAIL_USERNAME'];                     //SMTP username
        $this->mail->Password   = (string)$_ENV['MAIL_PASSWORD'];                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $this->mail->Port       = (int)$_ENV['MAIL_PORT'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        $this->mail->isHTML(true);
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $recipient
     * @param string $recipientName
     * @return Email
     */
    public function bootstrap(string $subject, string $body, string $recipient, string $recipientName): Email
    {
        $this->data->subject = $subject;
        $this->data->body = $body;
        $this->data->recipient_email = $recipient;
        $this->data->recipient_name = $recipientName;
        return $this;
    }

    /**
     * @param $from
     * @param $fromName
     * @return bool
     */
    public function send(): bool
    {
        $from = (string)$_ENV['MAIL_FROM_ADDRESS'];
        $fromName = (string)$_ENV['MAIL_FROM_NAME'];

        if (!is_email($this->data->recipient_email)) {
            $this->message->warning("O e-mail de destinatário não é válido");
            return false;
        }

        if (!is_email($from)) {
            $this->message->warning("O e-mail de remetente não é válido");
            return false;
        }

        try {
            $this->mail->Subject = $this->data->subject;
            $this->mail->Body = $this->data->body;
            $this->mail->setFrom($from, $fromName);
            $this->mail->addAddress($this->data->recipient_email, $this->data->recipient_name);

            $this->mail->send();
            return true;
        } catch (Exception $exception) {
            $this->message->error($exception->getMessage());
            return false;
        }
    }

    /**
     * @return PHPMailer
     */
    public function mail(): PHPMailer
    {
        return $this->mail;
    }

    /**
     * @return Message
     */
    public function message(): Message
    {
        return $this->message;
    }
}
