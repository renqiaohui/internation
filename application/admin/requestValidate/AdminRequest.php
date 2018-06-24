<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午6:21
 */

namespace app\admin\requestValidate;

class AdminRequest extends BaseRequestValidate
{
    /**
     * 登录参数验证
     *
     * @return mixed
     */
    public function login()
    {
        $input = $this->request->param();
        $rules = [
            'username' => 'require',
            'password' => 'require'
        ];

        $this->validate($input, $rules);

        return $input;
    }
}