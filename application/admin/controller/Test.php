<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午5:55
 */

namespace app\admin\controller;

use app\common\field\InternationOrderHandleField;

use app\common\model\DistributionHotel;

use app\distribution\library\gta\DownloadCompleteFile;
use app\distribution\library\gta\SearchHotelPricePax;
use think\Controller;

use think\facade\Env;
use app\common\library\OrderGuestChildDeal;

use think\facade\App;

use think\facade\Log;


class Test extends Controller
{
    use OrderGuestChildDeal;
    public function index()
    {
        set_time_limit(0);
        $list = scandir('gta/hotel');
        unset($list[0], $list[1], $list[2]);

        $path = App::getRootPath(). 'public/gta/hotel/';
        foreach ($list as  $key => $hotel) {
            Log::write($key);
//            $xml = simplexml_load_string(file_get_contents($path . $hotel), null, LIBXML_NOCDATA);
            $xml = simplexml_load_string(file_get_contents($path . $hotel), null, LIBXML_NOCDATA);
            $hotelName = (string)$xml->ResponseDetails->SearchItemInformationResponse->ItemDetails->ItemDetail->Item;
            $hotelId = (string)$xml->ResponseDetails->SearchItemInformationResponse->ItemDetails->ItemDetail->Item->attributes();
            $cityId = (string)$xml->ResponseDetails->SearchItemInformationResponse->ItemDetails->ItemDetail->City->attributes();
            $data = [
                'distribution_hotel_id' => $hotelId,
                'distribution_hotel_name' => $hotelName,
                'distribution_city_id' => $cityId
            ];
            DistributionHotel::create($data);


        }
    }

    public function searchPriceTest()
    {
        $searchHotelPrice = new SearchHotelPricePax();
        $res = $searchHotelPrice->invoke();
        dump(simplexml_load_string($res));exit;

    }
    function testField(){
        $field = new InternationOrderHandleField();
        $field->id = 1;
        $field->order_id = "123";
        dump(get_object_vars($field));
        dump($field->getData());
    }

}