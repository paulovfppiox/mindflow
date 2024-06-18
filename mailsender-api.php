<?php
require_once 'cors.php';
include("vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$data = null;

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' )
{
     $jsonStrIn = file_get_contents( 'php://input' );
	 $data = json_decode( $jsonStrIn, true );
}

$errorMsg = null;
if ( !isset( $data['destino'] ) )
	  $errorMsg = "Email de destino indefinio";
if ( !isset( $data['assunto'] ) )
	  $errorMsg = "Assunto do email indefinio";
if ( !isset( $data['corpo'] ) )
	  $errorMsg = "Corpo do email indefinio";

if ( $errorMsg != null )
	 die( "{'code':'99','msg': '" . $errorMsg . "'}" ); 	  

try {
	// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
	$mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'paulo.fariaspaiva@gmail.com';
	$mail->Password = 'lvha jtwm flak gkls';
	$mail->Port = 587;
 
	$mail->setFrom('paulo.fariaspaiva@gmail.com');
	$mail->addAddress( $data['destino'] );
 
	$mail->isHTML(true);
	$mail->Subject = $data['assunto'];
	$mail->Body = $data['corpo'];
	$mail->AltBody = 'Chegou o email teste do Canal TI';
 
	if($mail->send()) {
		echo "{'code':'0','msg': 'Email enviado com sucesso.'}";

	} else {
		echo "{'code':'99','msg': 'Email não enviado.'}";
	}
} catch (Exception $e) {
	echo "{'code':'99','msg': 'Email não enviado.'}";
}