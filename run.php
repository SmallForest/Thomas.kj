<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-22
 * Time: 13:21
 */

use Swoole\Http\Server;
use ToolSpace\Main;

include_once __DIR__ . '/tool/Tool.php';
include_once __DIR__ . '/library/Medoo/Medoo.php';
include_once __DIR__ . '/library/php-jwt/src/JWT.php';


$http = new Server("127.0.0.1", 9501);
$http->set([
    'worker_num' => 4,
]);
$http->on('request', function ($request, $response) {
    if ($request->server['request_uri'] == '/favicon.ico' || $request->server['request_uri'] == '/undefined') {
        $response->status(404);
        $response->end();
    }
    //注册自动引入方法
    spl_autoload_register("ToolSpace\Tool::autoload");
    $obj = new Main();
    //返回json格式的数据
    $response->header('Content-type', 'application/json');
    $response->end($obj->do($request));
    unset($obj);
});
echo '服务启动' . PHP_EOL . 'http://127.0.0.1:9501';
$http->start();

