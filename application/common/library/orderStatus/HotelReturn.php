<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/14
 * Time: 下午5:30
 */

class HotelReturn
{
    private $handleId;
    private $hotelReturn;
    private $confirmType;
    private $confirmReason;

    function setHandleId($value)
    {
        $this->handleId = $value;
    }
    function setHotelReturn($value)
    {
        $this->hotelReturn = $value;
    }
    function setConfirmType($value)
    {
        $this->confirmType = $value;
    }
    function setConfirmReason($value)
    {
        $this->confirmReason = $value;
    }

    function invoke()
    {


    }
}