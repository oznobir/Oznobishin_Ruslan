<?php

namespace core\site\controllers;

use core\base\exceptions\DbException;
use core\site\models\Model;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SendMailController extends BaseSite
{
    private string $_body = '';
    private string $_ErrorInfo;


//    protected function inputData(): void
//    {
//        parent::inputData();
//    }
    /**
     * @param $body
     * @return $this
     */
    public function setMailBody($body): static
    {
        if (is_array($body)) {
            foreach ($body as $str)
                $this->_body .= $str;
        } else $this->_body .= $body;
        return $this;
    }

    /**
     * @param $email
     * @param $subject
     * @return bool
     * @throws DbException
     */
    public function send($email = null, $subject = null): bool
    {
        if (!$this->model) $this->model = Model::instance();
        $to = [];
        $this->set = $this->model->select('settings', [
            'order' => ['id'], 'limit' => 1
        ]);
        if ($this->set) $to[] = $this->set[0]['email'];
        if ($email) $to[] = $email;

        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host = 'smtp.yandex.by';                     //Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Username = 'test-myshop2@yandex.by';                     //SMTP username
            $mail->Password = '###########';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('test-myshop2@yandex.by', 'Заявка с магазина myshop2.by');
            //$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient

            foreach ($to as $address)
                $mail->addAddress($address);               //Name is optional

            $mail->addReplyTo('test-myshop2@yandex.by');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML();                                  //Set email format to HTML
            $mail->Subject = $subject ?? 'Заявка с магазина myshop2.by';
            $mail->Body = $this->_body;
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
        } catch (Exception $e) {
            $this->_ErrorInfo = $mail->ErrorInfo . ' ' . $e;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getErrorInfo(): string
    {
        return $this->_ErrorInfo;
    }
}