<?php if ( ! defined('andromed')) exit(''); 

if ($errorTrue) {
	if ($dbTrue) {
		$wrap->write();
		if (! $isAjax) {
			if ($global['errorView']) {
				$error->printErrors();
				$error->viewingOld($db);
			}
		}
	}
	else {
		echo "Нет разрешения пользоваться базой данных (error/index). Смотри config";
	}
}
?>