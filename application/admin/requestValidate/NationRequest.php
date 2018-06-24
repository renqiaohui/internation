<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/22
 * Time: 上午10:59
 */

namespace app\admin\requestValidate;

class NationRequest extends BaseRequestValidate
{
    /**
     * 获得下一级地区参数验证
     *
     * @return mixed
     */
    public function getNextNations()
    {
        $input = $this->request->param();
        $rules = [
            'pid' => 'require|number'
        ];
        $this->validate($input, $rules);

        return $input;
    }
}