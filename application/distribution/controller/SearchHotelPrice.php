<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/11
 * Time: 下午2:32
 */

namespace app\distribution\controller;

use think\Controller;
use app\distribution\library\gta\SearchHotelPricePax as SearchHotelPriceLibrary;

class SearchHotelPrice extends Controller
{
    public function requestRoomPricePax()
    {
        $searchHotelPriceLibrary = new SearchHotelPriceLibrary();

        $searchHotelPriceLibrary->setDestinationCode("NCE");
        $searchHotelPriceLibrary->setDestinationType(0);
        $searchHotelPriceLibrary->setCheckInDate("2018-06-23");
        $searchHotelPriceLibrary->setDuration(1);
        $searchHotelPriceLibrary->setItemCode("COM");
        $searchHotelPriceLibrary->setPaxRoom(["adults"=>1,"cots"=>0,"roomIndex"=>"1","id"=>"001:COM:9848:S9660:11135:45851"]);

        $searchHotelPrice  = $searchHotelPriceLibrary->invoke();
//        dump($searchHotelPrice);

    }
}