<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/22
 * Time: 上午10:57
 */

namespace app\admin\controller;

use app\admin\requestValidate\NationRequest;
use app\common\repository\NationRepository;

class Nation extends Base
{
    protected $repository;

    public function __construct(NationRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * 获得nation列表
     *
     * @param NationRequest $request
     * @return \think\response\Json
     */
    public function getNextNations(NationRequest $request)
    {
        $input = $request->getNextNations();

        return $this->repository->getNextNations($input);
    }
}