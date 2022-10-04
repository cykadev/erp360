<?php

namespace Erp360\Core\Mails;

use Erp360\Core\Helpers\BaseMailer;
use Erp360\Core\Helpers\SiteHelper;

class ForgotPasswordMail extends BaseMailer {

    public $data;
    public $subject = "You requested for reset password on ERP360";

    public function __construct( string $recipient , array $data )
    {
        parent::__construct();
        $this->setRecipient($recipient);
        $this->setSubject($this->subject);
        $this->data = $data;
        $this->htmlView();
    }

    public function htmlView(): void
    {
        $body = SiteHelper::renderEmail("mails/forgot-password-mail", $this->data);
        $this->setBody($body);
    }

    public function sendMail(): bool
    {
        return $this->send();
    }

}