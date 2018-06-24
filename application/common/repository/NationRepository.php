<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/22
 * Time: 上午11:03
 */

namespace app\common\repository;

use app\common\model\Nation;

class NationRepository extends Base
{
    public function __construct(Nation $nation)
    {
        $this->model = $nation;
    }

    /**
     * 获得下一级地区
     *
     * @param $input
     * @return \think\response\Json
     */
    public function getNextNations($input)
    {
        $nations = $this->model->where($input)->select();

        return json([
            'code' => 0,
            'nations' => $nations
        ]);
    }
}