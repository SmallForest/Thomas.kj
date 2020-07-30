<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-24
 * Time: 08:55
 */

namespace application;

use Tool\Tool;

include_once __DIR__ . '/Base.php';

class Server extends Base
{

    public function index()
    {
        return Tool::print_json(1, "获取成功", ['name' => '娃哈哈']);
    }
}
