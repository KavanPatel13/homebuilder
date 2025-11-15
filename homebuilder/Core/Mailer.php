<?php
namespace Core;

class Mailer
{
    protected $from;
    protected $enabled;

    public function __construct()
    {
        $cfg = require __DIR__ . '/../Config/config.php';
        $this->from = $cfg['MAIL_FROM'] ?? 'no-reply@localhost';
        $this->enabled = !empty($cfg['MAIL_ENABLED']);
    }

    public function send($to, $subject, $message)
    {
        $headers = 'From: ' . $this->from . "\r\n" . 'Content-Type: text/plain; charset=utf-8';
        if ($this->enabled && function_exists('mail')) {
            @mail($to, $subject, $message, $headers);
            return true;
        }

        $storage = __DIR__ . '/../storage';
        if (!is_dir($storage)) {
            @mkdir($storage, 0777, true);
        }
        $logFile = $storage . '/mail.log';
        $entry = "[" . date('Y-m-d H:i:s') . "] To: $to | Subject: $subject\n$message\n\n";
        @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
        return true;
    }
}
