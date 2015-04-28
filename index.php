<?php
ini_set(ERROR_REPORTING, E_ALL);

// UTF-8 only
mb_internal_encoding('UTF-8');

require_once('system/application.php');
require_once('config/config.php');
$app = Application::getApp();
$app->start();

