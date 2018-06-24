<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午5:51
 */

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $connection = 'initial';
}