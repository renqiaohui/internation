<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/6
 * Time: 下午3:29
 */

namespace app\distribution\library\gta;

use app\common\exception\ResponseException;
use app\distribution\controller\Gta;
use think\Exception;
use think\facade\App;
use think\facade\Log;

class SearchHotelPricePax extends GtaBase
{

    const DESTINATION_TYPES = ["city","geocode"];
    //按酒店搜索参数
    private $destinationCode;//城市代码
    private $destinationType;//搜索类型
    private $checkInDate = null;
    private $duration = null;
    //数组
    private $itemCode = null;
    //数组 [roomIndex=>["adults"=>1,"cots"=>0，"roomIndex"=>1,"id"=>"1"]]
    private $paxRoom = null;

    function setDestinationCode(string $value)
    {
        $this->destinationCode = $value;

    }
    function setDestinationType(int $value=0)
    {
        $this->destinationType = self::DESTINATION_TYPES[$value]??self::DESTINATION_TYPES[0];

    }
    public function setCheckInDate(string $value)
    {
        $this->checkInDate = $value;
    }
    public function setDuration(int $value)
    {
        $this->duration = $value;
    }
    public function setItemCode(string $value)
    {
        $this->itemCode = $value;
    }
    public function setPaxRoom(array $value)
    {
        $this->paxRoom = $value;
    }

    /**
     * 统一入口
     *
     * @return mixed
     * @throws ResponseException
     */
    public function invoke()
    {
        $xml = $this->setXmlParam();
        Log::record("gta search hotel price request xml {$xml}");
        try {
            $res = $this->postXmlSSLCurl($this->url, $xml);
            $resXml = simplexml_load_string($res);

            Log::record("gta search hotel price res".$res);

            if ($resXml->Errors) {
                throw new ResponseException('gta search hotel price Error', -1);
            }
            $gtaPriceArr = $this->hotelPriceResultDeal($resXml);
            dump($gtaPriceArr);

        } catch (Exception $e) {
            Log::record("gta搜索酒店价格异常{$e->getMessage()}");
            throw new ResponseException('gta search hotel price res Exception', -1);
        }

        return $res;
    }

    /**
     * 设置参数
     *
     * @return mixed
     */
    private function setXmlParam()
    {

        $xmlPath = App::getAppPath() . 'distribution/xml/gta/SearchHotelPricePax.xml';
        $xml = simplexml_load_string(file_get_contents($xmlPath));
        $this->userInfoSet($xml);
        $this->currencySet($xml);

        $searchHotelPricePaxRequestXml = $xml->RequestDetails->SearchHotelPricePaxRequest;
        $searchHotelPricePaxRequestXml->ItemDestination->addAttribute("DestinationCode",$this->destinationCode);
        $searchHotelPricePaxRequestXml->ItemDestination->addAttribute("DestinationType",$this->destinationType);

        $searchHotelPricePaxRequestXml->PeriodOfStay->CheckInDate = $this->checkInDate;
        $searchHotelPricePaxRequestXml->PeriodOfStay->Duration = $this->duration;

        //设置酒店代码
        $searchHotelPricePaxRequestXml->ItemCode = $this->itemCode;

        //设置入住人数
        $paxRoomXml = $searchHotelPricePaxRequestXml->PaxRooms->addChild("PaxRoom");
        /** @var \SimpleXMLElement $paxRoomXml */
        $paxRoomXml->addAttribute("Adults",$this->paxRoom["adults"]);
        $paxRoomXml->addAttribute("Cots",$this->paxRoom["cots"]);
        $paxRoomXml->addAttribute("RoomIndex",$this->paxRoom["roomIndex"]);
        if(isset($this->paxRoom["id"]))
        {
            $paxRoomXml->addAttribute("Id",$this->paxRoom["id"]);
        }


        return $xml->asXML();
    }

    /**
     * gta 获取价格后 解析数据
     * @param $responseXml
     */
    private function hotelPriceResultDeal($responseXml)
    {
        $gtaPriceArr = [];
        $hotelDetails = $responseXml->ResponseDetails->SearchHotelPricePaxResponse->HotelDetails;
        if($hotelDetails->Hotel) {
            //paxRoom 节点  包含 roomPrice 信息
            $paxRoom = $hotelDetails->Hotel->PaxRoomSearchResults->PaxRoom;
            //房间 区间价格获取
            $roomCategories = $paxRoom->RoomCategories->RoomCategory;
            foreach ($roomCategories as $roomCategory)
            {
                /** @var \SimpleXMLElement $roomCategory*/
                //获取 gta roomId
                $roomId = (string)$roomCategory->attributes()->Id;

                $hotelRoom = $roomCategory->HotelRoomPrices->HotelRoom;
                $priceRanges = $hotelRoom->PriceRanges->children();
                foreach ($priceRanges as $priceRange)
                {
                    //获取gta 价格及适用日期
                    $fromDate = (string)$priceRange->DateRange->FromDate;
                    $toDate = (string)$priceRange->DateRange->ToDate;
                    $price = (string)$priceRange->Price->attributes()->Gross;
                    $gtaPriceArr[$roomId][] = [
                        "fromDate"=>$fromDate,
                        "toDate"=>$toDate,
                        "price"=>$price,
                        "adults"=>$this->paxRoom["adults"],
                        "cots"=>$this->paxRoom["cots"],
                    ];
                }
            }

        }
        return $gtaPriceArr;
    }

    private function setHotelPirce($priceArr)
    {
        foreach ($priceArr as $roomId=>$value)
        {
            //TODO:: 更改自有房价房态
        }


    }


}