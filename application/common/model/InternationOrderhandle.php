<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/6
 * Time: 下午5:24
 */

namespace app\common\model;


class InternationOrderHandle extends Base
{
    protected $table = "internation_order_handle";
    protected $observerClass = "";

    /**
     * @return \think\model\relation\HasOne
     */
    function internationOrderDetail(){
        return $this->hasOne(InternationOrderDetail::class,"handle_id","id");
    }

    /**
     *
     * @return \think\model\relation\HasMany
     */
    function internationOrderMsgs(){
        return $this->hasMany(InternationOrderMsg::class,"handle_id","id");
    }

    /**
     * @return \think\model\relation\HasMany
     */
    function internationOrderNotes(){
        return $this->hasMany(InternationOrderNote::class,"order_id","order_id");
    }




}