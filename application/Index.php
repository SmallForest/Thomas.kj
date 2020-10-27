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
     * 上传的例子
     * @return false|string
     */
    public function upload()
    {
        $r = file_put_contents("/Users/dada/Downloads/1.upload" . rand(1, 100) . ".png", file_get_contents($this->params["files"]["file"]["tmp_name"]));
        if ($r > 0) {
            return Tool::print_json(1, '上传成功');
        } else {
            return Tool::print_json(-1, '上传失败');
        }
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

    /**
     * 通过swoole的chan和协程处理秒杀
     * 思路
     * 设置一个通道channel数量为1，一个协程向里面写入用户的数据比如是用户的ID
     * 另一个协程来处理通道的数据写入beanstalkd或者Redis中的队列
     * 此例子我选用写入Redis的list，因为我本机没安装beanstalkd
     */
    public function skill()
    {
        $chan  = new channel(1);
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);

        $res = [];
        //生成者协程
        co::create(function () use ($chan, $redis, &$res) {
            //查看chan中的数量
            $num = $chan->length();
            //当数量等于0并且库存还有的情况下的时候表名，通道为空可以写入数据
            if ($num == 0 && $redis->get('stock')) {
                $chan->push(['id' => rand(100, 999)]);
            }
            $res = [0, "抢购失败"];
        });

        //消费者协程
        co::create(function () use ($chan, $redis, &$res) {
            $data = $chan->pop();
            if ($data) {
                //此处默认每个人抢购1件，如果需要抢购多件可以在data中携带购买数量
                //并写入到通道里面
                $redis->set('stock', $redis->get('stock') - 1);
                //写入Redis list 其他脚本进行订单处理之类的IO业务。此处不实现了
                $redis->lPush("skill_swoole", json_encode($data));
                $res = [1, "抢购成功"];
            }
        });

        return Tool::print_json($res[0], $res[1]);

    }
}
