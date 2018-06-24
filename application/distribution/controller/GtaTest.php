<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/5/29
 * Time: 下午3:26
 */

namespace app\distribution\controller;

use app\distribution\library\gta\AddBooking;
use app\distribution\library\gta\CancelBooking;
use app\distribution\library\gta\DownloadCompleteFile;
use app\distribution\library\gta\DownloadIncrementalUpdates;
use app\distribution\library\gta\SearchCity;
use app\distribution\library\gta\SearchCountry;
use app\distribution\library\gta\SearchCurrency;
use function PHPSTORM_META\type;
use think\facade\App;

class GtaTest
{
    function completeFile()
    {
        $api = new DownloadCompleteFile();
        $api->invoke();
    }

    function incrementalUpdates(){
        $api = new DownloadIncrementalUpdates();
        $api->setFromDate("2018-05-01");
        $api->setToDate("2018-05-02");
        $api->invoke();
    }

    function addBooking(){
        $api = new AddBooking();
        $api->setHandleId(1);
        $api->setAddType("roomPax");
        $api->invoke();
    }
    function cancelBooking(){
        $api = new CancelBooking();
        $api->invoke();
    }


    function searchCurrency(){
        $api = new SearchCurrency();
        $api->setCurrencyCode("");
        $res = $api->invoke();
        dump($res);
    }

    function searchCountry(){
        $api = new SearchCountry();
        $api->setCountryName("");
        $res = $api->invoke();
        dump($res);
    }

    function searchCity(){
        $api = new SearchCity();
        $api->setCountryCode("AZ");
        $api->setCityName("");
        $res = $api->invoke();
        dump($res);
    }
}