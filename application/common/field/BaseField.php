<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/15
 * Time: 上午9:58
 */

namespace app\common\field;


class BaseField
{

    public function getData()
    {
        $data = get_object_vars($this);
        foreach ($data as $key=>$value){
            if (is_null($value)){
                unset($data[$key]);
            }
        }
        return $data;

    }

}