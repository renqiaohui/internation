<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/6
 * Time: 下午5:33
 */

namespace app\common\repository;

use app\common\model\InternationOrderHandle;

class InternationOrderHandleRepository extends Base
{
    function __construct()
    {
        $this->model = new InternationOrderHandle();
    }






    /**
     * @param $id
     * @return array|null|\PDOStatement|string|\think\Model
     */
    function getOneWithNotes($id){
        return $this->model
            ->with("internationOrderNotes")
            ->where("id","=",$id)
            ->find();
    }

}