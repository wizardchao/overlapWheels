<?php
use Inhere\Route\Router;
define(BASE_PATH, dirname(__DIR__) . '/');

// 需要先加载 autoload 文件
require BASE_PATH . 'vendor/autoload.php';
require BASE_PATH . 'Autoloader.php';
require BASE_PATH . 'bootstrap/helper.php';

$router = new Router();
$router->get('/rongchuang', function () {
	$param = $_GET;
	app\Service\ShareService::init($param['key'], $param['link']);
});

// 开始调度运行
$router->dispatch();