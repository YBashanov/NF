<?php if ( ! defined('andromed')) exit(''); 
//содержит перечень mime-типов, используемых модулями.
//производит некоторые преобразования (со строками названий типов)

class L_mimeType{
	
	private static $thisObject = null;
	private static $error;
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_mimeType();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_mimeType: Объект L_mimeType уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_mimeType');
			exit();
		}
	}
	
//список типов в комплексе с соответствующими расширениями
	private $mimeList = array(
		'image/jpeg'=>'jpg',
		'image/gif'=>'gif',
		'image/png'=>'png',
		'image/bmp'=>'bmp',
		'application/pdf'=>'pdf',
		'audio/mpeg'=>'mp3',
		'video/mp4'=>'mp4',
		'video/mpeg'=>'mp4',
		'video/quicktime'=>'MOV',
		'video/x-ms-wmv'=>'wmv',
		'video/x-flv'=>'flv',
		'application/vnd.ms-excel'=>'xls',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'=>'xlsx',
		'text/xml'=>'xml',
		'application/msword'=>'doc',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docx',
		'application/x-force-download'=>'doc',
		'application/vnd.ms-powerpoint'=>'ppt'
	);

	
//вернуть расширение, если задан mime-тип
	public function getExpan($mime){
		$expan = false;
		if ( array_key_exists($mime, $this->mimeList) ){
			$expan = $this->mimeList[$mime];
		}
		return $expan;
	}

	
//вернуть mime-тип, если задано расширение
	public function getMime($expan){
		$mime = false;
		if ( in_array($expan, $this->mimeList) ){
			foreach ( $this->mimeList as $key=>$val ) {
				if ( $val == $expan ) {
					$mime = $key;
					break;
				}
			}
		}
		return $mime;
	}
}
?>