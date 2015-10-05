<?php
    $appId_read_only = $_SESSION['ic']['appId_read_only'];
	include_once "view/page_parts/header.php";
	include_once "view/pages/".$view.".php";
	include_once "view/page_parts/footer.php";
?>