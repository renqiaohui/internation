<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午6:21
 */

namespace app\distribution\requestValidate;

class SearchHotelPriceRequest extends BaseRequestValidate
{
    protected $failException = true;

    /**
     * gta房型增量更新验证
     *
     * @return mixed
     */
    public function updateIncreaseRooms()
    {
        $input = $this->request->param();
        $rules = [
            'itemCode' => 'require'
        ];

        $this->validate($input, $rules);

        return $input;
    }
}