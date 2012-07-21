<?php

class Mailer
{
	private $recipient_to	= null;
	private $recipient_cc	= null;
	private $recipient_bcc	= null;
	
	private $from_name		= null;
	private $from_email		= null;
	
	private $reply_to_email	= null;
	
	private $subject		= null;
	private $message		= null;

	
	
	public static function sendMail()
	{
		$empfaenger	= "patrick.plocke@beyond-interactive.com";
		$betreff	= "Die Mail-Funktion";
		$from = "From: System <system@the-wire.de>\n";
		$from .= "Reply-To: system@the-wire.de\n";
		//$from .= "Cc: email2@domain.de\n";
		//$from .= "Bcc: email3@domain.de\n";
		$from .= "X-Mailer: PHP/" . phpversion(). "\n";
		//$from .= "X-Sender-IP: $_SERVER['REMOTE_ADDR']\n";
		$from .= "Content-Type: text/html";

		$text = "Hier lernt Ihr, wie man mit <b>PHP</b> Mails
		verschickt";
		return mail($empfaenger, $betreff, $text, $from);
	}
}

?>