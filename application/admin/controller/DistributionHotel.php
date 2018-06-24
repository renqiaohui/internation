<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/22
 * Time: 下午4:40
 */

namespace app\admin\controller;

use app\admin\requestValidate\DistributionHotelRequest;
use app\common\repository\DistributionHotelRepository;

class DistributionHotel extends Base
{
    protected $repository;

    public function __construct(DistributionHotelRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * 代理商酒店列表首页
     *
     * @param DistributionHotelRequest $request
     * @return \think\response\Json
     */
    public function index(DistributionHotelRequest $request)
    {
        $input = $request->index();

        return $this->repository->getDistributionHotels($input);
    }
}