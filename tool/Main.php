<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-22
 * Time: 13:37
 */

namespace ToolSpace;

use application\Index;

class Main
{
    protected $request = null;

    public function do($request)
    {
        $this->request = $request;

        //返回给前端
        return $this->exe();
    }

    protected function exe()
    {
        //根据URI判断调用那个对象
        $obj_arr = $this->route();
        if (empty($obj_arr)) {
            return '访问路径不存在，请检查route';
        }

        $c            = $obj_arr[0];
        $a            = $obj_arr[1];
        $a_arr        = explode('|', $a);
        $new_a        = $a_arr[0];
        $is_check_jwt = $a_arr[1];
        $r            = 0;
        if ($is_check_jwt == 'T') {
            //进行校验
            try {
                $r = Tool::verify_jwt_token($this->request->header['token']);
                if (!$r) {
                    return '失效的Token';
                }
            } catch (\Exception $e) {
                return 'JWT ERROR!:' . $e->getMessage();
            }
        }

        $obj = new $c($this->get_params(), $r);

        return $obj->$new_a();//这里不能直接写$obj->$obj_arr[1]();
    }


    protected function route()
    {
        $route_arr = require __DIR__ . '/Route.php';
        $v         = $route_arr[$this->get_request_uri()];
        if (is_null($v)) {
            return [];
        }
        return explode('->', $v);
    }

    /**
     * @return mixed
     */
    protected function get_request_uri()
    {
        return $this->request->server['request_uri'];
    }

    /**
     * 获取请求类型
     * @return string
     */
    protected function get_action_type()
    {
        return $this->request->server['request_method'];
    }

    /**
     * 获取请求参数
     * @return array
     */
    protected function get_params()
    {
        $result = [];
        if ($this->get_action_type() == 'GET') {
            if ($this->request->get != null) {
                $result = $this->request->get;
            }
        } elseif ($this->get_action_type() == 'POST') {
            if ($this->request->post != null) {
                $result = $this->request->post;
            }
        }
        return $result;
    }
}
