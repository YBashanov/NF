<?php if ( ! defined('andromed')) exit('');
/*
недоработано:
если пользователь ушел, а кукис в его браузере сохранились
приходит второй человек, и его скрипт удаляет мертвые кукис.
Первый возвращается на следующий день, и в базе его кукис отмечены как удаленные.
Однако - сейчас его user_id, sid - те же, что и вчера.
Их надо заменять!!!
*/
$db_table = "{$config['prefix']}users_online";
$time = time();
$delay = 60*3;//минуты (секунд) - время задержки, более которого пользователь признан ОффЛайн
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$remote_addr = $_SERVER['REMOTE_ADDR'];


//занесение созданных кукис в базу данных
if ( isset ($_COOKIE['PHPSESSID'] ) ) {

	//если в прошлый раз создалась кукис user_id
	if ( isset ($_COOKIE['user_id']) && isset ($_COOKIE['gen_6']) ){
		//проверяем запись в БД с PHPSESSID, соответствует ли она user_id?
		$where = "`id`='{$_COOKIE['user_id']}' AND `secret_number`={$_COOKIE['gen_6']}";
		$what = "`id`, `sid`, `secret_number`, `deleted`";
		$users_online = $db->select_line($db_table, $where, $what, 'cookies.php');
		$global['user_online'] = $users_online;
		
		if (! $users_online) {
			//генерируем secret_number - небольшой код для пользователей, чтобы они забирали свой товар
			
			$datas = array(
				'id'=>$_COOKIE['user_id'],
				'sid'=>$_COOKIE['PHPSESSID'],
				'secret_number'=>$_COOKIE['gen_6'],
				'time_create'=>$time,
				'time_online'=>$time,
				'user_info'=>'browser='.substr($user_agent, 0, 25).'|ip='.$remote_addr
			);
			if (! $db->insert($db_table, $datas, 'cookies.php')){
				//в случае, если такой $id уже записан в базу, и будет вызываться конфликт - insert не запишет такой же id снова
				createNew_deleteOld($db, $global['libraries']['cookie'], $time, $delay, $global['libraries']['generator'], $db_table);
			}
		}
		//если такая переменная есть - происходит обновление параметра
		else {

			//перезапись параметра time_online Этой сессии!
			$datas = array(
				'sid'=>$_COOKIE['PHPSESSID'],
				'time_online'=>$time,
				'deleted'=>0
			);
			$where = "`id`='{$_COOKIE['user_id']}'";
			$db->update($db_table, $datas, $where, 'cookies.php');

			//все остальные сессии проверяются
			//перезаписываются все строки таблицы users_online, в которых нет активности более КонкретногоВремени!
			$time_delay = $time - $delay;//время "опоздания"
			$datas = array('deleted'=>'1');
			$where = "`time_online`<'{$time_delay}' AND NOT(`deleted`) AND NOT(`id`='{$_COOKIE['user_id']}')";
			$db->update($db_table, $datas, $where, 'cookies.php');
		}
	}
	//повторяем код, который ниже. Странно, что при созданном PHPSESSID нет user_id. 
	//вот как раз на этот случай я copyPaste этот код - для подстраховки
	else {
		createNew_deleteOld($db, $global['libraries']['cookie'], $time, $delay, $global['libraries']['generator'], $db_table);
	}
}
//если переменной phpsessid еще нет, создаем кукис
//а после перезагрузки будет запись в базу данных
else {
	createNew_deleteOld($db, $global['libraries']['cookie'], $time, $delay, $global['libraries']['generator'], $db_table);
}


//создает новые кукис, удаляет старые записи из таблицы
//оба параметра - объекты, $time - врем.метка, $delay - ожидание
//$generator - библиотека
function createNew_deleteOld($db, $cookie, $time, $delay, $generator, $db_table){
	//выясняем, какой id в таблице users_online является максимальным,
	//чтобы взять следующий 
	//создание ЭТОЙ сессии
	$what = 'MAX(`id`)';
	$maxId = $db->select_line($db_table,'true',$what,'cookies.php');
	if ( $maxId == NULL ) $nextId = 1;
	else $nextId = $maxId['MAX(`id`)'] + 1;

	$gen_6 = $generator->gen20_return(6);
	
	$cookie->set_cookie('user_id',$nextId);
	$cookie->set_cookie('gen_6',$gen_6);

	//все остальные сессии проверяются
	//перезаписываются все строки таблицы users_online, в которых нет активности более КонкретногоВремени!
	$time_delay = $time - $delay;//время "опоздания"
	$datas = array('deleted'=>'1');
	$where = "`time_online`<'{$time_delay}' AND NOT(`deleted`)";
	$db->update($db_table, $datas, $where, 'cookies.php');
}
?>