<?php if ( ! defined('andromed')) exit('');
//L_cookie
/*
�������� ���������� ������ - ����������� ���������
	�� ����������� ��������� ���� ��� ����� ����
	$_SESSION['trigger'] = array();
	$session = array(); - ����� ������
	
����������� 
- ��� �������� �������� ������ ��� ������,

5.06.12 - ��������� ������ � ������� �������� �����
2014-09-24 - �������� set_cookie, ��� timeLive=0 ���������� ����������
*/

class L_cookie{
	
	private static $timeLive = 0;//����� ����� ����� (����=0, ��� ����� ���������� �����)
	private $path = '/';
	private $domain = null;
	
	private static $error;
	private static $thisObject = null;
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_cookie();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_cookie: ������ L_cookie ��� ������ �����!";
			self::$error->add(2, 'Error: Object is exists!', 'L_cookie');
			exit();
		}
	}

//����� �������� ���������� ���������� ���������� ���������� 
	//���� ����� ���������� ��� ��� - ������� � ����������� ��������
	//$timeLive - ���� �� ����������, ��������������� �� �������� ��������
	public function set_cookie($name, $var, $timeLive = false, $rewrite = false){
		if ($rewrite == false) {
			if ($this->get_cookie($name)){
				return false;
			}
			else {
				$this->_set_cookie($name, $var, $timeLive);
				return true;
			}
		}
		else {
			$this->_set_cookie($name, $var, $timeLive);
			return true;
		}
	}
	
	//��������������� �����
	private function _set_cookie($name, $var, $timeLive){
		if ($timeLive == false) {
			@setcookie($name, $var, 0, $this->path, $this->domain);
		}
		else {
			$timeLive = time() + $timeLive;
			@setcookie($name, $var, $timeLive, $this->path, $this->domain);
		}
	}

//���������� �������� ���������� ���������� ���������� ����������
	public function get_cookie($name = false){
		if ($name == false) {
			return $_COOKIE;
		}
		else {
			if ( ! isset ($_COOKIE[$name]) ) {
				return null;
			}
			else return $_COOKIE[$name];
		}
	}
	
//������� ������������ �����
	public function delete_cookie($name){
		if ( isset ($_COOKIE[$name]) ) {
			$time = time() - 1036800;
			@setcookie($name, '', $time, $this->path, $this->domain);
			return true;
		}
		else return false;
	}
}
//L_cookie
?>