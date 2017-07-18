<?php if ( ! defined('andromed')) exit('');

/*
Класс, расширяющий работу с форматом XML. Для работы с денежным форматом требуется подключение файла modules/functions/universal.php

2013-03-12 - Создание (использовался в koral)
2016-01-17 - добавление метода getTable для формирования таблиц <table>
*/

class L_xml{
	private static $thisObject = null;
	private static $error;
	private $separator;
    
    public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_xml();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			self::error_add("Объект L_xml уже создан ранее!");
			exit();
		}
	}
    private static function error_add($text){
        if (self::$error) {
            self::$error->add(2, "L_xml: ".$text, 'L_xml');
        }
        else {
            echo $text;
        }
    }
    public function setSeparator ($separator){
        $this->separator = $separator;
    }
	
    //подключен ли javascript при вызове метода getTable
    private $is_table_javascript = false;
    private $t_class = 0;
    
    //получить таблицу из xml-файла
    /*
        xmlObject   - параметры
            path    - путь до файла xml
            i_head  - сколько строк используется в шапке
            i_footer- нижние строки (подвал)
            widths  - Массив. Явное задание ширины каждой ячейки (обычно применяется для 1й ячейки)
            str_to_num      - если в главной части таблицы есть числа как строки, и их надо преобразовать в числа
            num_to_money    - числа из главной таблицы перевести в денежный формат (разделяя каждые 3 числа пробелами)
            multiplicate    - коэффициент, на который умножатся все числа в главной части таблицы
        
        Пример:
        $xmlObject = new xmlObject();
        $xmlObject->path = "../text_table/paketi/paketi_1.xml";
        $xmlObject->widths = array(300);
        $xmlObject->i_footer = 3;
        $xmlObject->str_to_num = true;
        $xmlObject->num_to_money = true;
        $xmlObject->multiplicate = 1.5;
        echo $xml->getTable($xmlObject);
    */
    public function getTable($xmlObject=null){
        if ($xmlObject) {
            $in = "";
            $class = "unique_" . $this->t_class;
            $this->t_class++;
            $i=0; //номер строки
            $j=0; //номер столбца
            
            //$style_1td = 'noactive';//стиль для первого столбца - мы его сохраним в переменную
            $style_1td = '';//стиль для первого столбца - мы его сохраним в переменную
            
            if ($this->is_table_javascript == false) {
                $in .= "<script src='{$this->separator}libraries/L_xml/xmlTable.js' type='text/javascript'></script>";
                $this->is_table_javascript = true;
            }
            
            if (file_exists($xmlObject->path)) {
                $dom = DOMDocument::load($xmlObject->path);
                $rows = $dom->getElementsByTagName('Row');
                if ($rows) {
                
                    if (! $xmlObject->i_head) $xmlObject->i_head = 1;

                    $in .= "<table class='xmlTable {$class}' cellpadding='0' cellspacing='0'>";
                    foreach ($rows as $row) {
                        
                        //шапка таблицы
                        if ($i < $xmlObject->i_head) {
                            $in .= "<tr>";
                            
                            $cells = $row->getElementsByTagName('Cell');
                            foreach( $cells as $cell ) {
                                $td_width = ""; //установка ширины, если она задана извне
                                if ($xmlObject->widths[$j]) {
                                    $td_width = " width='{$xmlObject->widths[$j]}'";
                                }

                                $in .= "<td{$td_width} class='head'
                                    onmouseover='XmlTable.overtd (\"{$class}\",\"{$i}\",\"{$j}\")'
                                    onmouseout='XmlTable.outtd (\"{$class}\",\"{$i}\",\"{$j}\",\"{$style_1td}\")'
                                >";
                                $in .= $cell->nodeValue;
                                $in .= "</td>";
                                $j++;
                            }
                            $in .= "</tr>";
                        }
                        //подвал таблицы (нижние строки)
                        elseif ($xmlObject->i_footer && $i >= $xmlObject->i_footer){
                            $in .= "<tr>";
                            
                            $cells = $row->getElementsByTagName('Cell');
                            foreach( $cells as $cell ) {
                                $in .= "<td class='footer'
                                    onmouseover='XmlTable.overtd (\"{$class}\",\"{$i}\",\"{$j}\")'
                                    onmouseout='XmlTable.outtd (\"{$class}\",\"{$i}\",\"{$j}\",\"{$style_1td}\")'
                                >";
                                $in .= $cell->nodeValue;
                                $in .= "</td>";
                                $j++;
                            }
                            $in .= "</tr>";
                        }
                        //обычные строки
                        else {
                            $in .= "<tr>";
                            
                            $cells = $row->getElementsByTagName('Cell');
                            foreach( $cells as $cell ) {
                                $in .= "<td class='{$class}tr{$i} {$class}td{$j} {$class}tr{$i}td{$j}'
                                    onmouseover='XmlTable.overtd (\"{$class}\",\"{$i}\",\"{$j}\")'
                                    onmouseout='XmlTable.outtd (\"{$class}\",\"{$i}\",\"{$j}\",\"{$style_1td}\")'
                                >";
                                //str_replace(" ", "", $str);
                                //$in .= $cell->nodeValue * $xmlObject->multiplicate;
                                if ($xmlObject->str_to_num) {
                                    if ($j == 0){
                                        $in .= $cell->nodeValue;
                                    }
                                    else {
                                        $new = str_replace(" ", "", $cell->nodeValue);
                                        if ($xmlObject->multiplicate) $new *= $xmlObject->multiplicate;
                                        if ($xmlObject->num_to_money) $new = $this->num_to_money($new);
                                        $in .= $new;
                                    }
                                }
                                else {
                                    $in .= $cell->nodeValue;
                                }
                                
                                $in .= "</td>";
                                $j++;
                            }
                            $in .= "</tr>";
                        }
                        
                        $i++;
                        $j=0;
                    }
                    
                    $in .= "</table>";
                }
                else {
                    self::error_add("В файле нет ни одной строки");
                }
            }
            else {
                self::error_add("Файл не найден");
            }
        }
        else {
            self::error_add("Нет объекта-параметров");
        }
        
        return $in;
    }
    
    
    //преобразовать число в денежный формат
    private function num_to_money($num){
        return moneyFormat($num);
    }
    
    
//вытаскиваем данные из XML файла, сохраняем их в массив
	public function getXMLData ($pathToXmlFile, $tr="Row", $td="Cell"){
		$dom = @DOMDocument::load($pathToXmlFile);
		
		if ($dom) {
			$rows = $dom->getElementsByTagName($tr);

			$dataXML = array();
			foreach($rows as $row){
				$cells = $row->getElementsByTagName($td);
				$datarow = array();
				
				foreach ($cells as $cell) {
					$datarow[] = $cell->nodeValue;
				}
				$dataXML[] = $datarow;
			}
			
			return $dataXML;
		}
		else {
			return false;
		}
	}
	
	//возвращает массив стоимостей драгоценных металлов
	//путь в виде http://www.cbr.ru/scripts/xml_metall.asp?date_req1=27/07/2013&date_req2=28/07/2013
	//возвращает одномерный числовой массив
	//0 - золото
	//1 - серебро
	//2 - платина
	//3 - палладий
	public function getMetalsData ($time, $tr="Record", $td="Buy"){
		$date1 = date("d/m/Y", $time-86400);
		$date2 = date("d/m/Y", $time);
		$path = "http://www.cbr.ru/scripts/xml_metall.asp?date_req1={$date1}&date_req2={$date2}";
		
		$array = $this->getXMLData($path, $tr, $td);
		
		if ($array) {
			$result = array();
			for ($i=0; $i<count($array); $i++){
				$result[$i] = $array[$i][0];
			}
			return $result;
		}
		else return false;
	}
}
//вспомогательный класс, позволяющий создавать объекты-параметры для getTable
class xmlObject{}
?>