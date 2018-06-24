<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/8
 * Time: 下午3:58
 */

namespace app\common\library;

trait OrderGuestChildDeal
{
    private function orderGuestChildDeal($child_string)
    {
        $reg = "/(.*?)\((.*?)\)/";
        preg_match_all($reg,$child_string,$match);

        return $match;



    }

}