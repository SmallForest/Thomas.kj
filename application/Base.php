<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-22
 * Time: 20:50
 */

namespace application;

use Medoo\Medoo;

class Base
{
    protected $db = null;
    protected $uid = 0;
    protected $params = [];

    public function __construct($params, $uid)
    {
        $this->params = $params;
        $this->uid    = $uid;
        $this->db     = new Medoo([
            'database_type' => 'mysql',
            'database_name' => 'ceshi',
            'server'        => '127.0.0.1',
            'username'      => 'root',
            'password'      => 'root',
            'port'          => 8889,
            'prefix'        => '',
            'logging'       => false,
            'charset'       => 'utf8mb4',

        ]);
    }
}
