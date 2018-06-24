<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午6:11
 */

namespace app\admin\controller;

use app\admin\requestValidate\AdminRequest;
use app\common\repository\AdminRepository;

class Admin extends Base
{
    protected $repository;

    public function __construct(AdminRepository $adminRepository)
    {
        parent::__construct();
        $this->repository = $adminRepository;
    }

    /**
     * 登录验证
     *
     * @param AdminRequest $request
     * @return \think\response\Json
     */
    public function login(AdminRequest $request)
    {
        $input = $request->login();

        return $this->repository->auth($input);
    }

    /**
     * 登出
     *
     * @return \think\response\Json
     */
    public function logout()
    {
        return $this->repository->logout();
    }
}