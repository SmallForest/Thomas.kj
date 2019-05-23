<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-22
 * Time: 13:21
 */

use Swoole\Http\Server;
use ToolSpace\Main;

include_once __DIR__.'/tool/Main.php';
include_once __DIR__.'/tool/Tool.php';
include_once __DIR__.'/library/Medoo/Medoo.php';
include_once __DIR__.'/application/Index.php';
include_once __DIR__.'/library/php-jwt/src/JWT.php';


$http = new Server("127.0.0.1", 9501);
$http->on('request', function ($request, $response) {
    $obj = new Main();
    $response->end($obj->do($request));
    unset($obj);
});
echo '服务启动' . PHP_EOL . 'http://127.0.0.1:9501';
$http->start();

