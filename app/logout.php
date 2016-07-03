<?php
	require_once('shared.php');	
	AuthenticationHelper::logout();
	Utils::redirect(__APPURL__ . '/index.php');
?>
