<?php
define('__APPROOT__', dirname(dirname(__FILE__)) . '/app');
define('__APPURL__', 'http://localhost:8000');
require_once(__APPROOT__ . '/helpers/Utils.php');
require_once(__APPROOT__ . '/helpers/DatabaseHelper.php');
require_once(__APPROOT__ . '/helpers/AuthenticationHelper.php');

$_USER_ = AuthenticationHelper::authenticate();