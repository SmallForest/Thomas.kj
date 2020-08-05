<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-22
 * Time: 15:23
 */

namespace application;

use application\model\user;
use Tool\Tool;
use Swoole\Coroutine as co;
use Swoole\Coroutine\Channel;


class Index extends Base
{
    public function index()
    {
        //声明model
        $userModel = new user();
        $user      = $userModel->getUserInfo("*", ['id' => $this->uid], true);
        return Tool::print_json(1, '获取成功', $user);
    }

    /**
     * 登录
     * @return string
     */
    public function login()
    {
        $username = $this->params['username'];
        $pwd      = $this->params['password'];
        //声明model
        $userModel = new user();
        //构建SQL获取信息
        $user = $userModel->getUserInfo('id', ['username' => $username, 'password' => $pwd], true);
        if (empty($user)) {
            return Tool::print_json(-1, '账户或者密码错误！');
        }
        //假设登录成功并ID为1
        return Tool::print_json(1, '登录成功', ['token' => Tool::get_jwt_token($user['id'])]);
    }

    public function Go()
    {
        $userModel = new user();
        $info      = [];

        //创建协程
        $id = co::create(function () use ($userModel, &$info) {
            $info = $userModel->getUserInfo('*', ['id' => 1], true);
            var_dump($info);
            var_dump("当前协程ID" . co::getuid());
            var_dump("父级协程ID" . co::getPcid());
        });
        defer(function () {
            var_dump("我执行完毕了！我是defer");
        });
        return Tool::print_json(1, "success", ['协程ID' => $id, 'info' => $info]);
    }
}
