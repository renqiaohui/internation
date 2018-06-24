<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/22
 * Time: 下午4:42
 */

namespace app\admin\requestValidate;

class DistributionHotelRequest extends BaseRequestValidate
{
    /**
     * 首页参数验证
     *
     * @return mixed
     */
    public function index()
    {
        $input = $this->request->param();
        $rules = [
            'currentPage' => 'require|number'
        ];
        $this->validate($input, $rules);
        return $input;
    }
}