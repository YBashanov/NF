<?php
//показывает новую или отредактированную запись
$separator = "../../../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

$thisFile = "m_mailing/save";

$mail = $regular->mail ($_POST['mail']);

if (
	$mail !== false
) {
	$where = "NOT(`deleted`) AND `mail`='{$mail}'";
	$what = "*";
	$mailing = $db->select_line ("{$config['prefix']}mailing", $where, $what, $thisFile);

	if (! $mailing) {
		$data = array(
			"time_create"=>$time,
			"user_create"=>$global['user_online']['id'],
			"mail"=>$mail
		);
		if ($db->insert ("{$config['prefix']}mailing", $data, $thisFile)) {
			//отправка на mail
			include "{$separator}libraries/L_mail/Email.php";
			include "{$separator}templates/default/modules/m_mailing/mail/toClient_Mailing.php";

			$config['wrapchars'] = "200";
			$email = new Email($config);
			$email->clear();
			$email->from($mailText['from_mail'], $mailText['from_info']);
			$email->to($mailText['to']);
			$email->subject($mailText['theme']);
			$email->message($mailText['mess']);

			if ($email->send() ){
				$template->setPath("modules/m_mailing/html_reply/reply_ok.html");
				$in = $template->parse();
				echo "1|{$in}|";
			}
			else {
				$template->setPath("modules/m_mailing/html_reply/reply_not.html");
				$in = $template->parse();
				echo "2|{$in}|";
				$error->add (2, "Регистрация успешно, но письмо не отправлено", $thisFile);
			}
		}
		else {
			$template->setPath("modules/m_mailing/html_reply/reply_not.html");
			$in = $template->parse();
			echo "2|{$in}|";
			$error->add (0, "Не удалось записать данные в Базу, mail={$mail}", $thisFile);
		}
	}
	else {
		$template->setPath("modules/m_mailing/html_reply/reply_repeat.html");
		$in = $template->parse();
		echo "2|{$in}|";
		$error->add (3, "Оформление повторной подписки: mail={$mail}, id={$subscript['id']}", $thisFile);
	}
}
//на самом деле - неверные символы не пропустила regular
else {
	$template->setPath("modules/m_mailing/html_reply/reply_not.html");
	$in = $template->parse();
	echo "2|{$in}|";
	$error->add (0, "Введение некорректных символов минуя javascript", $thisFile);
}

include "{$separator}error/index.php";
?>