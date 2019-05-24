<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2019-05-23
 * Time: 13:27
 */

namespace ToolSpace;

use Firebase\JWT\JWT;

class Tool
{
    protected static $jwt_key = 'jismkkhdllajdfi*7323)12-';
    protected static $live_time = 3600;//JWT有效期

    /**
     * @param       $code 正确返回正值 错误返回小于等于0的数字
     * @param       $msg 提示信息 必填
     * @param array $data
     * @return false|string
     */
    public static function print_json($code, $msg, $data = [])
    {
        if ($code <= 0)
            return json_encode(['code' => $code, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
        return json_encode(['code' => $code, 'msg' => $msg, 'data' => $data], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取jwt TOKEN
     * @param $data 需要保存的数据
     * @return string
     */
    public static function get_jwt_token($data)
    {
        $token = array(
            "data"        => $data,
            "expire_time" => time() + self::$live_time,
        );
        $jwt   = JWT::encode($token, self::$jwt_key);
        return $jwt;

    }

    /**
     * 校验jwt 这里是按照自己设定的规则校验，并不通用
     * @param $jwt_token
     * @return bool|mixed
     */
    public static function verify_jwt_token($jwt_token)
    {
        $decoded = JWT::decode($jwt_token, self::$jwt_key, array('HS256'));
        $arr     = (array)$decoded;
        if ($arr['expire_time'] < time())
            return false;
        return $arr['data'];
    }

    /**
     * 自动加载方法 可以加载Tool和application一级类文件
     * @param $class
     */
    public static function autoload($class)
    {
        if (strpos($class, '\\') !== false) {
            $class = explode('\\', $class)[1];
        }
        $classFile = __DIR__ . '/' . $class . '.php';
        if (is_file($classFile) && !class_exists($class)) {
            include_once $classFile;
            return;
        }

        $classFile = __DIR__ . '/../application/' . $class . '.php';
        if (is_file($classFile) && !class_exists($class)) {
            include_once $classFile;
            return;
        }

    }
}
