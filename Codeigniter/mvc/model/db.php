<?php
class DB {
	public function __construct(){
		$dbcnx = @mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD);
		if($dbcnx){
		  mysql_select_db(MYSQL_DATABASE) or die("Database is not available");
		} else {
		 echo "Database is not available";
		 exit;
		}
		mysql_query("SET NAMES UTF8");
	}
}
?>
