<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-23
 * Time: 10:28
 */

//key为在前台访问的格式 value代表命名空间写法->function|[T|F]
//T 表示需要验证JWT
//F 表示不需要验证JWT
return [
    '/Index' => '\application\Index->index|T',
    '/Login' => '\application\Index->login|F',
    '/SIndex' => '\application\Server->index|F',
];
