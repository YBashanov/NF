<?php 
$separator = "../../../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

$thisFile = "tpl//modules/rating//help_message_send.php";

$name 	= $regular->ext($_POST["name"]);
$phone 	= $regular->ext($_POST["phone"]);
$mail 	= $regular->mail($_POST["mail"]);

if (
	$name 	!== false &&
	$phone 	!== false &&
	$mail 	!== false
){
	//отправка на mail
	include "{$separator}libraries/L_mail/Email.php";
	include "{$separator}data/_mail/toManager_helpSend.php";

	$config['wrapchars'] = "200";
	$email = new Email($config);
	$email->clear();
	$email->from($mailText['from_mail'], $mailText['from_info']);
	$email->to($mailText['to']);
	$email->subject($mailText['theme']);
	$email->message($mailText['mess']);

	if ($email->send() ){
		echo "1|<div style='text-align:center;font-size:14px;font-weight:700;color:#080'>Спасибо!</div>|";
	}
	else {
		echo "2|<div style='text-align:center;font-size:14px;font-weight:700;color:#f00'>Не вышло. Ошибка.</div>|";
		$error->add (2, "0", "Не удалось отправить письмо на mail", $thisFile);
	}
}
else {
	echo "2|Не вышло. Ошибка.";
	$wrap->add (3, "regular не пропустила", $thisFile);
}
include "{$separator}error/index.php";
?>