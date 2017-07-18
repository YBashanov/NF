<?php if ( ! defined('andromed')) exit('');
//L_browser
/*
2015-02-26

*/

class L_browser{
	private static $thisObject = null;
	
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_browser();
			return self::$thisObject;
		}
		else {
			echo "L_browser: Объект L_browser уже создан ранее!";
			exit();
		}
	}

	public function get(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$typeBrowser = false;
		$typeOS = false;

		if ( strpos($user_agent, 'Firefox') ) 		$typeBrowser = 'firefox';
		elseif ( strpos($user_agent, 'Chrome') ) 	$typeBrowser = 'chrome';
		elseif ( strpos($user_agent, 'Safari') ) 	$typeBrowser = 'safari';
		elseif ( strpos($user_agent, 'Opera') ) 	$typeBrowser = 'opera';
		elseif ( strpos($user_agent, 'MSIE 6.0') ) 	$typeBrowser = 'ie6';
		elseif ( strpos($user_agent, 'MSIE 7.0') ) 	$typeBrowser = 'ie7';
		elseif ( strpos($user_agent, 'MSIE 8.0') ) 	$typeBrowser = 'ie8';
		elseif ( strpos($user_agent, 'MSIE 9.0') ) 	$typeBrowser = 'ie9';
		elseif ( strpos($user_agent, 'MSIE 10.0') ) $typeBrowser = 'ie10';
		elseif ( strpos($user_agent, 'Trident/7') ) $typeBrowser = 'ie11';

		$oses = array (
			'iPhone' => '(iPhone)',
			'Windows 3.11' => 'Win16',
			'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)', // Используем регулярное выражение
			'Windows 98' => '(Windows 98)|(Win98)',
			'Windows 2000' => '(Windows NT 5.0)|(Windows 2000)',
			'Windows XP' => '(Windows NT 5.1)|(Windows XP)',
			'Windows 2003' => '(Windows NT 5.2)',
			'Windows Vista' => '(Windows NT 6.0)|(Windows Vista)',
			'Windows 7' => '(Windows NT 6.1)|(Windows 7)',
			'Windows NT 4.0' => '(Windows NT 4.0)|(WinNT4.0)|(WinNT)|(Windows NT)',
			'Windows ME' => 'Windows ME',
			'Open BSD'=>'OpenBSD',
			'Sun OS'=>'SunOS',
			'Linux'=>'(Linux)|(X11)',
			'Safari' => '(Safari)',
			'Macintosh'=>'(Mac_PowerPC)|(Macintosh)',
			'QNX'=>'QNX',
			'BeOS'=>'BeOS',
			'OS/2'=>'OS/2',
			'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)'
		);
	  
		foreach($oses as $os=>$pattern){
			if(preg_match("/".$pattern."/", $user_agent)) { // Пройдемся по массиву $oses для поиска соответствующей операционной системы.
				$typeOS = $os;
				break;
			}
		}
		
		
		return array(
			"browser"=>$typeBrowser,
			"os"=>$typeOS
		);
	}
}
//L_browser
?>