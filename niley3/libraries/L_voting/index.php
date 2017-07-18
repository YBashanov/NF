<?php if ( ! defined('andromed')) exit('');

//необходимо подключить javascript-класс voting
//необходимо подключить jquery-библиотеку
//необходимо подключить css файл, где первый блок видимый, остальные - не видимые
//radio_0_x - вступительная часть. Она сразу в стилях видимая. Остальные - нет!
//radio_1 - 482.._x - вопросы
//finish_1 - 5..x - варианты ответов
class L_voting{
	//порядковый номер голосования
	private $vote_number = 0;
	private $type = "table"; //2 типа голосования - табличное (table) и суммируемое (summa)
	private $parameters = "";//вида "16-30|31-40|...", для типа голосования Summa. Неравенство проверки НЕ СТРОГОЕ!
	private $iterator = 0;
	private $textVoting = "";
	
	public function __construct ($vote_number, $type = "table", $parameters = "") {
		$this->vote_number 	= $vote_number;
		$this->type 		= $type;
		$this->parameters 	= $parameters;
		$this->iterator 	= 0;
		$this->textVoting 	= "";
		$this->setJS();
	}
	
	private function setJS(){
		$in = "<script>var voting{$this->vote_number} = new Voting(); voting{$this->vote_number}.setParameters(\"{$this->vote_number}\", \"{$this->type}\", \"{$this->parameters}\")</script>";
		$this->textVoting .= $in;
	}
	
	//если потребуется начать не с представления о вопросе, а сразу с вопроса
	public function setIterator ($i = 1) {
		$this->iterator = $i;
	}
	//создание блока с вопросом
	//$questVar - одномерный числовой массив, ключей у которого нет. Value и текст разделены |
	public function quest ($questText, $questVar, $action = "question"){
		$in = "";
		$in .= "<div class='radio_{$this->iterator}_{$this->vote_number}'>{$questText}";
		
		if ($action == "question") {
			if ($questVar) {
				for($i=0; $i<count($questVar); $i++) {
					$exp = explode("|", $questVar[$i]);
					$in .= "<br /><input class='voting_radio' id='rb_{$i}_{$this->iterator}_{$this->vote_number}' type='radio' name='radio_{$this->iterator}_{$this->vote_number}' value='{$exp[0]}'/><span class='voting_rb' onclick='voting{$this->vote_number}.change_this_radio(\"{$i}\", \"{$this->iterator}\", \"{$this->vote_number}\")'>{$exp[1]}</span>";
				}
			}
			$in .= "<br /><button class='voting_button' id='radio_{$this->iterator}_{$this->vote_number}' onclick='voting{$this->vote_number}.change_radio(\"radio_{$this->iterator}\")'>Ок</button>";
		}
		elseif ($action == "start") {
			$in .= "<button class='voting_button' id='radio_{$this->iterator}_{$this->vote_number}' onclick='voting{$this->vote_number}.start()'>Начать тест</button>";
		}
		elseif ($action == "finish") {
			$in .= "<button class='voting_button' id='radio_{$this->iterator}_{$this->vote_number}' onclick='voting{$this->vote_number}.finish(\"radio_{$this->iterator}\")'>Результаты</button>";
		}
		$in .= "</div>";
		
		$this->iterator++;
		$this->textVoting .= $in;
		return $in;
	}
	
	//a_result - числовой массив результатов, где каждый результат находится на порядке своего номера
	//номера начинаются с 1
	public function setFinishBlocks ($a_result) {
		$in = "";
		
		if (is_array($a_result)){
			for ($i=0; $i<count($a_result); $i++) {
				$key = $i+1;
				$in .= "<div class='finish_{$key}_{$this->vote_number}'>{$a_result[$i]}</div>";
			}
		}
		
		$this->textVoting .= $in;
		return $in;
	}
	
	public function getTextVoting() {
		return $this->textVoting;
	}
}
?>