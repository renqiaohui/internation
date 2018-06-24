<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/13
 * Time: 下午2:18
 */

namespace app\distribution\library\gta;


use app\common\repository\InternationOrderHandleRepository;
use think\facade\App;
use think\facade\Log;

class CancelBooking extends GtaBase
{

    private $orderId;
    function setOrderId($value)
    {
        $this->orderId = $value;
    }
    function __construct()
    {
        $this->requestXml = simplexml_load_file(App::getAppPath()."distribution/xml/gta/CancelBookingRequest.xml");
    }

    function invoke()
    {
        $orderRepos = new InternationOrderHandleRepository();
        $orderInfo = $orderRepos->getOne($this->orderId);
        //设置请求用户信息
        $this->userInfoSet($this->requestXml);

        //设置取消订单信息
        $cancelXml = $this->requestXml->RequestDetails->CancelBookingRequest;
        /** @var \SimpleXMLElement $cancelXml */
        $cancelXml->BookingReference->attributes()->ReferenceSource = "client";

        $cancelXml->BookingReference=$orderInfo["order_sn"];

        Log::write("gta order cancel request ".$this->requestXml->asXML());

        $res = $this->postXmlSSLCurl($this->url,$this->requestXml->asXML());

        Log::write("gta order cancel response ".$this->requestXml->asXML());







    }

}