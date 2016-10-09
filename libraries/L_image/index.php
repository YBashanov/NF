<?php if ( ! defined('andromed')) exit(''); 
/*
набор логических функций, работающих с input=checkbox, radio, select

2013-06-06 - добавлена резка изображений (вместо старого L_resize)
2013-11-07 - 2.44.279 - Опытным путем выясняем, хватает ли памяти для обработки изображений
2013-12-11 - 2.44.292 + прозрачность png
2014-05-28 - 3.00.042 - доработа прозрачность png, теперь ясно почему миниатюра была белой
*/
class L_image{
	
	private static $error = null;
	private static $thisObject = null;
	private $separator = "../";
	private $prefixImage = "m_";
	
	public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_image();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_image: Объект L_image уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_image');
			exit();
		}
	}
	public function setSeparator($separator){$this->separator = $separator;}
	public function setPrefixImage($prefix){$this->prefixImage = $prefix;}
	
	//path = @/uploads/portfolio/
	//$name = 35
	//javascript - onclick='Portfolio.changeImg(\"{$val['id']}\")'
	public function slide ($path, $name, $width=false, $height=false, $class="", $javascript="", $extend="", $title="", $thistext=""){
		if ($extend == "") $extend = "jpg";
		
		$text = "";
		$text .= "<a href='{$this->separator}{$path}{$name}.{$extend}' class='highslide' onclick='return hs.expand(this)'>
			<img class='{$class}' src='{$this->separator}{$path}{$this->prefixImage}{$name}.{$extend}'";
			
			if ($width) $text .= " width='{$width}'";
			if ($height)$text .= " height='{$height}'";
			
			$text .= " {$javascript} title='{$title}' alt='{$thistext}'/>";
		$text .= "</a>";
		
		return $text;
	}
	
	//аналог предыдущей функции
	public function textslide ($path, $name, $thistext, $extend=""){
		if ($extend == "") $extend = "jpg";

		$text = "";
		$text .= "<a href='{$this->separator}{$path}{$name}.{$extend}' class='highslide' onclick='return hs.expand(this)'>{$thistext}</a>";
		
		return $text;
	}
	
	
	//резка изображений
	//f - имя файла 
	//f2 - имя записываемого файла (нового)
	//w - ширниа изображения 
	//$arr_image - массив реальных размеров изображения
	//factor - коэффициент отношения сторон (ширина к высоте) Если =0, то файл сжимается без обреза сторон
	//q - качество сжатия 
	public function resize ($f, $f2, $w=100, $arr_image, $factor=0, $q=100) {
		//src - исходное изображение 
		//dest - результирующее изображение 
		//ratio - коэффициент пропорциональности 

        if (! $w) {
            self::$error->add(2, "Ошибка! переменная w не заполнена: {$w}", 'L_image');
            return;
        }
        if (! $factor && $factor !== 0) {
            self::$error->add(2, "Ошибка! переменная factor не заполнена: {$factor}", 'L_image');
            return;
        }
        
		//создаём исходное изображение на основе исходного файла и опеределяем его размеры 
		if ($factor != 0)
			$h = $w/$factor;	//для ошибки LoadJpeg
		else 
			$h = $w;			//для ошибки LoadJpeg

		$a_loadJpeg = $this->LoadJpeg ($f);
		$src = $a_loadJpeg[0];
		$type = $a_loadJpeg[1];

		$w_real = imagesx($src);
		$h_real = imagesy($src);
		$factor_real = $w_real/$h_real;

		//операции для получения прямоугольного файла 
		if ( $factor == 0 ){		
		    //вычисление пропорций 
		    $ratio = $w_real/$w; 

		    $w_dest = round($w_real/$ratio); 
		    $h_dest = round($h_real/$ratio); 

		    //создаём пустую картинку
		    //важно именно truecolor!, иначе будем иметь 8-битный результат 
		    $dest = imagecreatetruecolor($w_dest,$h_dest); 
			if ($type == "png") {
				imagealphablending($dest, false);
				imagesavealpha($dest, true);
			}

		    if (! @imagecopyresized($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_real, $h_real)){
				self::$error->add(2, "Не удалось преобразовать изображение (где прямоугольный файл), размеры: {$w_real}, {$h_real}, {$w_dest}, {$h_dest}", 'L_image');
			}
		}
		
		//если установлен коэффициент для соотношения сторон
		else{
			$relationImage = $arr_image[0]/$arr_image[1];
			
			//посчитаем ширину и высоту, которые должны быть
			//отношение неуменьшенного изображения (стороны впритирку к сторонам исходного)
				//в отличие от factor_real
			//высота оригинала больше, чем по стандарту
			if ( $relationImage < $factor ) {
				$width = $arr_image[0];
				$height = $arr_image[0]/$factor;
			}
			//ширина оригинала больше, чем по стандарту
			elseif ( $relationImage > $factor ) {
				$width = $arr_image[1]*$factor;
				$height = $arr_image[1];
			}
			elseif ( $relationImage == $factor ) {
				$width = $arr_image[0];
				$height = $arr_image[1];
			}
			

			//высота реального изображения больше, чем то, что мы хотим получить 
			if ( $factor > $factor_real ){
				//создаем новый шаблон в памяти
				$w_dest = $w;
				$h_dest = round($w/$factor);

				//вычисляем то место, которое будем копировать с реального макета
				$x_factor = 0;
				$y_factor = round($h_real/2 - $w_real/($factor*2));
				$w_factor = round($w_real);
				$h_factor = round($w_real/$factor);

				//создаём пустую картинку 
				//важно именно truecolor!, иначе будем иметь 8-битный результат 
				$dest = imagecreatetruecolor($w_dest,$h_dest);
				if ($type == "png") {
					imagealphablending($dest, false);
					imagesavealpha($dest, true);
				}

				if (! @imagecopyresized($dest, $src,
					0, 0, $x_factor, $y_factor,
					$w_dest, $h_dest, $w_factor, $h_factor))
				{
					self::$error->add(2, "Не удалось преобразовать изображение (где высота реального изображения больше), размеры: {$w_real}, {$h_real}, {$w_dest}, {$h_dest}", 'L_image');
				}
			}
			//ширина реального изображения больше, чем то, что мы хотим получить
			elseif ( $factor < $factor_real ){
				//создаем новый шаблон в памяти
				$w_dest = $w;
				$h_dest = round($w/$factor);

				//вычисляем то место, которое будем копировать с реального макета
				$x_factor = $w_real/2 - $width/2;
				$y_factor = 0;
				$w_factor = $h_real*$factor;
				$h_factor = $h_real;
					
				//создаём пустую картинку 
				//важно именно truecolor!, иначе будем иметь 8-битный результат 
				$dest = imagecreatetruecolor($w_dest,$h_dest); 
				if ($type == "png") {
					imagealphablending($dest, false);
					imagesavealpha($dest, true);
				}

				if (! @imagecopyresized($dest, $src,
					0, 0, $x_factor, $y_factor,
					$w_dest, $h_dest, $w_factor, $h_factor))
				{
					self::$error->add(2, "Не удалось преобразовать изображение (где ширина реального изображения больше), размеры: {$w_real}, {$h_real}, {$w_dest}, {$h_dest}", 'L_image');
				}
			}
			elseif ( $factor == $factor_real ){
				//создаем новый шаблон в памяти
				$w_dest = $w;
				$h_dest = round($w/$factor);

				//вычисляем то место, которое будем копировать с реального макета
				$x_factor = 0;
				$y_factor = 0;
				$w_factor = $w_real;
				$h_factor = $h_real;
					
				//создаём пустую картинку 
				//важно именно truecolor!, иначе будем иметь 8-битный результат 
				$dest = imagecreatetruecolor($w_dest, $h_dest); 
				if ($type == "png") {
					imagealphablending($dest, false);
					imagesavealpha($dest, true);
				}

				if (! @imagecopyresized($dest, $src,
					0, 0, $x_factor, $y_factor,
					$w_dest, $h_dest, $w_factor, $h_factor))
				{
					self::$error->add(2, "Не удалось преобразовать изображение (где ширина реального изображения равна высоте), размеры: {$w_real}, {$h_real}, {$w_dest}, {$h_dest}", 'L_image');
				}
			}
		}

		//вывод картинки и очистка памяти 
		if ($type == "jpg") {
			if (! @imagejpeg($dest, $f2, $q)) {self::$error->add(3, "Не удалось создать JPG-файл", "L_image");}
		}
		elseif ($type == "png") {
			if (! @imagepng($dest, $f2)) {self::$error->add(3, "Не удалось создать PNG-файл", "L_image");}
		}
		elseif ($type == "gif") {
			if (! @imagegif($dest, $f2)) {self::$error->add(3, "Не удалось создать GIF-файл", "L_image");}
		}
		else {
			if (! @imagejpeg($dest, $f2, $q)) {self::$error->add(3, "Не удалось создать NONAME-файл", "L_image");}
		}
		
		
		if (! @imagedestroy($dest)) {
			self::$error->add(3, "Не удалось удалить результирующее изобажение из памяти", "L_image");
		}
		if (! @imagedestroy($src)) {
			self::$error->add(3, "Не удалось удалить исходное изобажение из памяти", "L_image");
		}
		return array($w_dest, $h_dest);
	}




	//возвращает идентификатор соответствующего оригинального изображения (на основе пути к файлу)
	//если идентификатор не создан, создает пустое изображение
	//imgname (String)
	//bgcolor - массив из 3х элементов
	private function LoadJpeg ($imgname, $width=0, $height=0, $bgcolor=array()) {
		$result = array();
		$type = "";
	
		//если путь к изображению есть
		if ( $imgname != '' && $imgname != null ) {
			$im = @imagecreatefromjpeg ($imgname); 
			$type = "jpg";
//self::$error->add(2, "im - $im", 'L_image');
//var_dump(imagecreatefromjpeg ($imgname)); 
//echo "\nтут проходит 1-2a\n";
//если сюда создание файла не проходит, либо нет ответа от imagecreatefromjpeg - 
//это означает НЕХВАТКУ ПАМЯТИ. Необходимо экспериментальным путем подобрать значение в htaccess
			if ( ! $im ) {
				$im = @imagecreatefromgif ($imgname);
				$type = "gif";

				if ( ! $im ) {
					$im = @imagecreatefrompng ($imgname);
					imagealphablending($im, true);
					imagesavealpha($im, true);
					$type = "png";

					if ( ! $im ) {
						$type = "";
						self::$error->add(3, "Не удалось создать ни один макет для основы преобразования (jpg, gif, png)", "L_image");

						if ($width == 0) $width = 150;
						if ($height == 0) $height = 100;
						$im  = imagecreate ($width, $height); // создать пустое изображение
						$bgc = imagecolorallocate ($im, 255, 255, 255);
						$tc  = imagecolorallocate ($im, 0, 0, 0);
						imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
						imagestring ($im, 1, 5, 5, "resize error", $tc);
					}
				}
			}
			$result[0] = $im;
			$result[1] = $type;
			return $result;
		}
		//если изображения нет, создается пустое (эта часть не доработана)
		else{
			$newImage = imagecreatetruecolor($width, $height);
			$bgcol = imagecolorallocate ($newImage, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
			if ( imagefilledrectangle ($newImage, 0, 0, $width, $height, $bgcol) )	{		
				
				$result[0] = $newImage;
				$result[1] = $type;
				return $result;
			}
			else {
				self::$error->add(3, "Не удалось заполнить макет предлагаемым цветом", "L_image");
				return false;
			}
		}
	}
	
}
?>