<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/6
 * Time: 下午2:13
 */

namespace app\distribution\library\gta;

use app\common\exception\ResponseException;
use think\Exception;
use think\facade\App;
use think\facade\Log;

use app\common\repository\InternationOrderHandleRepository;
use app\distribution\library\ConfigGta;

use app\common\library\OrderGuestChildDeal;

class AddBooking extends GtaBase
{
    use OrderGuestChildDeal;

    /**
     * 请求类型
     * room paxRoom
     */

    private $addType="room";
    private $handleId;

    function setAddType(string $value)
    {
        $this->addType = $value;
    }

    function setHandleId($value)
    {
        $this->handleId = $value;
    }

    private $orderInfo;

    function __construct()
    {
        $this->requestXml = simplexml_load_file(App::getAppPath()."distribution/xml/gta/AddBookingRequest.xml");
    }

    function invoke()
    {

        //set request xml
        $this->setRequestXml();

        $requestXml = $this->requestXml->asXML();
        Log::write("gta order new request ".$requestXml);
        //log api begin time
        Log::write("gta order new request begin time".time());

        //请求接口
        try{
            $res = $this->postXmlSSLCurl($this->url,$requestXml);

            //log api end time
            Log::write("gta order new request end time".time());

            Log::write("gta order new response ".$res);
            $resXml = simplexml_load_string($res);
            if($resXml->Errors){
                throw new ResponseException("gta新订订单接口返回Error",1);

            }

        }catch (Exception $e){
            Log::write("gta新订订单异常{$e->getMessage()}");
            throw new ResponseException('gta新订订单异常', 1);
        }
    }

    /**
     *set request xml content
     */
    private function setRequestXml(){

        $orderHandleRepos = new InternationOrderHandleRepository();
        $this->orderInfo = $orderHandleRepos->getOneWithNotes($this->handleId);

        //设置请求用户信息
        $this->userInfoSet($this->requestXml);
        $this->currencySet($this->requestXml);

        /**
         * 获取AddBookingRequest  xml部分
         * 用户设置 订单新增主体信息
         */

        $addBookingRequestXml = $this->requestXml->RequestDetails->AddBookingRequest;
        //设置请求货币类别
        /** @var \SimpleXMLElement $addBookingRequestXml */
        $addBookingRequestXml->attributes()->Currency = ConfigGta::CURRENCY;
        //订单号设置
        $addBookingRequestXml->addChild("BookingReference",(string)$this->orderInfo["order_sn"]);
        //客户出发时间设置，默认与入住时间相同
        $addBookingRequestXml->addChild("BookingDepartureDate",(string)$this->orderInfo["begin_date"]);

        //获取 客户 和 pid 属性 数组
        $guestPids = $this->getGuestPids();



        //客户姓名设置
        $this->setPaxNamesXml($addBookingRequestXml,$guestPids);

        //价格处理
        //计算预定房间数
        $roomNum = $this->orderInfo->internationOrderNotes[0]->room_number;
        $roomTotalPrice = number_format($this->orderInfo["floor_price_origin"]/$roomNum,2,".","");


        //booking item 设置
        $bookItemsXml = $addBookingRequestXml->addChild("BookingItems");
        foreach ($guestPids as $index=>$guestPid)
        {
            $this->setBookItemXml($bookItemsXml,$guestPid,$index,$roomTotalPrice);

        }

    }

    /**
     * guest 统一属性设置  pid 设置
     */
    private function getGuestPids()
    {
        //$guest  json => $array
        $guests = json_decode($this->orderInfo["guest"],true);
        $pidIndex = 1;
        $guestPids = [];
        foreach ($guests as $value)
        {
            $pid =[];
            foreach ($value["adult"] as $adult)
            {
                $pid["adult"][] = ["guest"=>$adult,"pid"=>$pidIndex];
                $pidIndex++;
            }
            if(isset($value["child"])){
                foreach ($value["child"] as $child)
                {
                    $pid["child"][] = ["guest"=>$child,"pid"=>$pidIndex];
                    $pidIndex++;
                }
            }
            $guestPids[] = $pid;
        }
        return $guestPids;

    }


    /**
     * 设置 paxNames 节点
     * @param $addBookingRequestXml
     * @param $guestPids
     */
    private function setPaxNamesXml($addBookingRequestXml,$guestPids)
    {
        /** @var \SimpleXMLElement $addBookingRequestXml */
        $paxNamesXml = $addBookingRequestXml->addChild("PaxNames");

        foreach ($guestPids as $value)
        {
            //adult set
            foreach ($value["adult"] as $adult)
            {
                $paxXml =addCdataNode("PaxName",$adult['guest'],$paxNamesXml);
                $paxXml->addAttribute("PaxId", "{$adult['pid']}");
                $paxXml->addAttribute("PaxType", "adult");
            }
            //child set
            if(isset($value["child"]))
            {
                foreach ($value["child"] as $child)
                {
                    $match = $this->orderGuestChildDeal($child["guest"]);
                    $paxXml = addCdataNode("PaxName",$match[1][0],$paxNamesXml);
                    $paxXml->addAttribute("PaxId", "{$child['pid']}");
                    $paxXml->addAttribute("PaxType", "child");
                    $paxXml->addAttribute("ChildAge", "{$match[2][0]}");

                }
            }
        }

    }

    private function setBookItemXml($bookItemsXml,$guestPid,$index,$price)
    {

        /** @var \SimpleXMLElement $bookItemsXml */
        $bookItemXml = $bookItemsXml->addChild("BookingItem");
        //设置 bookItem  属性
        $bookItemXml->addAttribute("ItemType","hotel");
        $bookItemXml->addAttribute("ExpectedPrice",$price);

        $bookItemXml->addChild("ItemReference",$index+1);

        //city code & item code TODO::
        $cityXml = $bookItemXml->addChild("ItemCity");
        $cityXml->addAttribute("Code","NCE");



        $itemXml = $bookItemXml->addChild("Item");
        $itemXml->addAttribute("Code","COM");


        //hotelItem 设置
        $hotelItemXml = $bookItemXml->addChild("HotelItem");

        //PeriodOfStay
        $periodOfStayXml = $hotelItemXml->addChild("PeriodOfStay");
        $periodOfStayXml->addChild("CheckInDate",$this->orderInfo["begin_date"]);
        $periodOfStayXml->addChild("CheckOutDate",$this->orderInfo["end_date"]);

        if($this->addType=="room")
        {
            //hotelRooms info
            $hotelRoomsXml = $hotelItemXml->addChild("HotelRooms");
            $hotelRoomXml = $hotelRoomsXml->addChild("HotelRoom");
            //TODO:: mapping
            $hotelRoomXml->addAttribute("Code","TB");
            $hotelRoomXml->addAttribute("Id","001:NAD");

            //pidId set
            $paxIdsXml = $hotelRoomXml->addChild("PaxIds");
            $this->paxIdSet($paxIdsXml,$guestPid);



        }else
        {
            $hotelPaxRoomXml = $hotelItemXml->addChild("HotelPaxRoom");
            //adult child attribute set
            $adultNumber = (string)count($guestPid["adult"]);
            $hotelPaxRoomXml->addAttribute("Adults",$adultNumber);
            //PaxId add
            if(isset($guestPid["child"])){
                $childNumber = (string)count($guestPid["child"]);
                $hotelPaxRoomXml->addAttribute("Children",$childNumber);
                $hotelPaxRoomXml->addAttribute("Cots","0");
            }
            //room Id set
            $hotelPaxRoomXml->addAttribute("Id","001:COM:9848:S9660:11135:45851");


            //pidId set
            $paxIdsXml = $hotelPaxRoomXml->addChild("PaxIds");
            $this->paxIdSet($paxIdsXml,$guestPid);

        }



    }

    /**
     * paxId xml set
     * @param $paxIdsXml
     * @param $guestPid
     */
    private function paxIdSet($paxIdsXml, $guestPid)
    {
        /** @var \SimpleXMLElement $paxIdsXml */
        //adult pid set
        foreach ($guestPid["adult"] as $adult)
        {
            $paxIdsXml->addChild("PaxId",$adult["pid"]);

        }
        //child pid set
        if(isset($guestPid["child"]))
        {
            foreach ($guestPid["child"] as $child)
            {
                $paxIdsXml->addChild("PaxId",$child["pid"]);

            }
        }

    }



}