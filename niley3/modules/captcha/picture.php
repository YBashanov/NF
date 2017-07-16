<?php if ( ! defined('andromed')) exit(''); 

//создание переменной сессии
$number='';
for ($i=1; $i<=5; $i++) {
	$a = rand (97, 132);
	if ($a > 122) {
		$a = $a - 75;
	};
	$number = $number.chr($a);
};
$_SESSION[$name_sess_key] = $number;


//Настройка главной подложки
$width = 120;
$height = 45;
$arrayFonts = array(
	"../../modules/fonts/arial.ttf",
	"../../modules/fonts/calibri.ttf",
	"../../modules/fonts/georgia.ttf",
	"../../modules/fonts/pala.ttf",
	"../../modules/fonts/tahoma.ttf",
	"../../modules/fonts/times.ttf"
);


//создание большого изображения
$im = ImageCreateTrueColor($width, $height);
$black = ImageColorAllocate ($im, 0, 0, 0);
$gray = ImageColorAllocate ($im, 200, 200, 200);
$background = ImageColorAllocate ($im, 165, 165, 255);

//островки букв (маленькие подложки)
$height_m = 25;
$width_m = 25;

//Отрисовка  изображения
ImageFill($im, 0, 0, $background);
//$font_size = 16; - рандом

//координаты островков букв
$text_x = 10;
$text_y = 10;

$number = $_SESSION[$name_sess_key];//изменим на любой текст


for ($i=0; $i<5; $i++) {
	$im_m = imageCreateTrueColor ( $width_m, $height_m );
	$rand_color = ImageColorAllocate ($im_m, rand (0,256), rand (0,256), rand (0,256));
	ImageFill($im_m, 0, 0, $background);
	$text = substr($number, $i, 1);
	$keyFonts = rand (0, count($arrayFonts)-1);
	$pathFont = $arrayFonts[$keyFonts];
	$font_size = rand (10, 18);
	
	imagettftext ($im_m, $font_size, 0, 4, 17, $black, $pathFont, $text);
	
	//угол поворота
	$angle = rand(-25, 25);
	$rotate = imagerotate($im_m, $angle, 0);
		
	imageCopy($im, $rotate, $text_x, $text_y, 0, 0, $width_m, $height_m); 

	$text_x = $text_x+20;
	ImageDestroy($im_m);
};

Header ('Content-type: image/png');
imagepng($im);
ImageDestroy($im);
?>