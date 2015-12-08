<?php
use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
// вместо '/cron/cron_action' должно быть URL маршрута который вы выбрали для экшена с логикой,
// именно URL, а не имя маршрута
//$request = Request::create('/cron/cron_action');
$request = Request::create('/cron');
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response); 