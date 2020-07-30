<?php
/**
 * Created by PhpStorm.
 * User: smallForest<1032817724@qq.com>
 * Date: 2020/7/30
 * Time: 14:07
 */
namespace application\model;
use think\Model;
class user extends Model
{
    /**
     * 设置数据表名称
     * @var string
     */
    protected $table = 'user';


    /**
     * @param string $field 字段
     * @param array  $where 条件
     * @param bool   $only_one 是否只获取一条记录,一位数组，多条记录是二维数组
     * @return array
     */
    public function getUserInfo($field, $where, $only_one = false)
    {
        if ($only_one) {
            $res = $this->field($field)->where($where)->find();
            return is_null($res) ? $res : $res->toArray();
        }
        $res = $this->field($field)->where($where)->select();
        return is_null($res) ? $res : $res->toArray();
    }
}
