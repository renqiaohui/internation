<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/4/23
 * Time: 下午1:12
 */

namespace app\common\model;

use think\Model;

class Base extends Model
{
    protected $connection = 'internation';
    protected $autoWriteTimestamp = 'datetime';
}