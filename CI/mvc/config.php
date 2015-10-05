<?php
	DEFINE('BASE_URL', 'http://clients.inteliclic.com/bailswipe/bailswipe-website/forms/');
	DEFINE('URL_BASE_PATH', '/bailswipe/bailswipe-website/forms/');
    DEFINE('FORM_LIST_URL', 'http://clients.inteliclic.com/bailswipe/bailswipe-website/form-list.php');
	
	
	DEFINE('BASE_PATH',dirname(__FILE__).'/');
	DEFINE('MYSQL_HOST', 'localhost');  
	DEFINE('MYSQL_DATABASE', 'inteli_bail');
	DEFINE('MYSQL_USER', 'inteli_bail');
	DEFINE('MYSQL_PASSWORD', 'inteli_bail');
	//DEFINE('EMAIL', 'notices@inteliclic.com');
	DEFINE('EMAIL', 'artashespapikyan@gmail.com');
    DEFINE("FORMS", serialize(array("promise", "setup_checklist", /*"bondsman",*/ "disclosure", "merchantapp", "w9", "drivers", "voideds", "miscs")));
    DEFINE("FORMS_COMMON_FIELDS", serialize(array("merchantName", "merchantAddress", "merchantPhone")));

?>
