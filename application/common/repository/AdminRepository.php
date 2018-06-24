<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午6:03
 */

namespace app\common\repository;

use app\common\model\Admin;
use app\common\tool\Session;

class AdminRepository extends Base
{

    public function __construct(Admin $admin)
    {
        $this->model = $admin;
    }

    /**
     * 登录验证
     *
     * @param $input
     * @return \think\response\Json
     */
    public function auth($input)
    {
        $user = $this->firstOrFail(['username' => $input['username']]);
        if ($user->password !== md5($input['password'])) {
            return json([
                'code' => 403,
                'msg' => '用户名或者密码错误'
            ]);
        }

        unset($user['password']);
        $session = Session::getInstance();

        $session->set('user', $user->toArray());

        $user = [
            'username' => $user->nickname
        ];

        return json([
            'code' => 0,
            'msg' => '登录成功',
            'user' => $user
        ]);
    }

    public function logout()
    {
        $session = Session::getInstance();
        $session->destroy('user');

        return json([
            'code' => 0,
            'msg' => '退出成功'
        ]);
    }
}