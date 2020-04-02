<?php

const SIGNUP_SUBJECT = "WEB START LINE - Overenie E-mailovaj adresy";
const ERROR_EMAIL_NOT_SENT = "Nepodarilo sa odoslať overovací E-mail na vašu mailovú adresu! Proces registrácie skončil neúspešne!";


class EmailSender
{
    public function sendSignUpMail($recipientMail, $link) {

        $text = "<html><body>";
        $text .= "<h1>Web Start Line</h1>";
        $text .= "<p>Pre overenie Vašej E-mailovej adresy <a href='" . ADMIN_PAGE . "?verif={$link}'>kliknite sem</a>.</p>";
        $text .= "<p>Po overení tejto E-mailovej adresy, môže chvíľu trvať, kým Vašu žiadosť potvrdí niektorý z administrátorov. Až potom budete môcť začať používať svoj účet.</p>";
        $text .= "</body></html>";

        $this->sendEmail($recipientMail, SIGNUP_SUBJECT, $text);
    }

    private function sendEmail($recipientMail, $subject, $text) {
        $header = "From: " . MAIL_ADDR . "\n";
        $header .= "MIME-Version: 1.0\nContent-Type: text/html; charset=utf-8";

        if (!mb_send_mail($recipientMail, $subject, $text, $header)) {
            throw new MyException(ERROR_EMAIL_NOT_SENT);
        }
    }
}