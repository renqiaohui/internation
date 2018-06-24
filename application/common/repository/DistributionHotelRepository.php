<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/22
 * Time: 下午4:44
 */

namespace app\common\repository;

use app\common\model\DistributionHotel;

class DistributionHotelRepository extends Base
{
    public function __construct(DistributionHotel $model)
    {
        $this->model = $model;
    }

    /**
     * 获得代理商酒店列表
     *
     * @param $input
     * @return \think\response\Json
     */
    public function getDistributionHotels($input)
    {
        $distributionHotels = $this->model->where([])->page($input['currentPage'], 15)->select();
        $total = $this->model->where([])->count();

        return json([
            'code' => 0,
            'distributionHotels' => $distributionHotels,
            'total' => $total,
            'pageSize' => 15
        ]);
    }
}