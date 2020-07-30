<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-22
 * Time: 20:50
 */

namespace application;

use Noodlehaus\Config;
use think\facade\Db;

class Base
{
    protected $uid = 0;
    protected $params = [];
    protected $config = null;//conf/config.json内容

    public function __construct($params, $uid)
    {
        $this->params = $params;
        $this->uid    = $uid;
        //解析conf/config.json内容
        $this->config = new Config('conf/config.json');
        // 数据库配置信息设置（全局有效）
        Db::setConfig([
            // 默认数据连接标识
            'default'     => $this->config->get("db.database_type"),
            // 数据库连接信息
            'connections' => [
                'mysql' => [
                    // 数据库类型
                    'type'     => $this->config->get("db.database_type"),
                    // 主机地址
                    'hostname' => $this->config->get("db.host"),
                    // 用户名
                    'username' => $this->config->get("db.username"),
                    // 密码
                    'password' => $this->config->get("db.password"),
                    // 数据库名
                    'database' => $this->config->get("db.database_name"),
                    // 数据库编码默认采用utf8
                    'charset'  => $this->config->get("db.charset"),
                    // 数据库表前缀
                    'prefix'   => $this->config->get("db.prefix"),
                    // 端口
                    'hostport' => $this->config->get("db.port"),
                    // 数据库调试模式
                    'debug'    => true,
                ],
            ],
        ]);
    }
}
