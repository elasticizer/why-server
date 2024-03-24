<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

ob_start();

$dsn = sprintf(
	'smtp://%s:%s@%s:%u',
	'ac2e5218-c554-467c-801e-effefa44d81c',
	'8cebc4a5-ddbc-4206-a2fa-05430c93ac34',
	'app.debugmail.io',
	'9025'
);
$token = $argv[2];

include './mail.phtml';
require '../vendor/autoload.php';

$html = ob_get_clean();
$mail = new Email();
$mailer = new Mailer(Transport::fromDsn($dsn));
$sender = new Address('sender@localhost');
$receiver = new Address($argv[1]);

$mailer->send(
	$mail
		->from($sender)
		->to($receiver)
		->priority(Email::PRIORITY_HIGHEST)
		->subject('啟用您的帳戶')
		->text(strip_tags($html))
		->html($html)
);
