<?php
/**
 * Created by PhpStorm.
 * User: wj
 * Date: 18-6-11
 * Time: 上午9:16
 */

namespace app\distribution\library\gta;

use think\facade\Env;
use think\facade\Log;

class SearchCity extends GtaBase
{
    private $country_code;
    private $city_name;

    function setCountryCode($value)
    {
        $this->country_code = $value;
    }

    function setCityName($value)
    {
        $this->city_name = $value;
    }

    function invoke()
    {
        $this->requestXml = simplexml_load_file(Env::get("app_path")."distribution/xml/gta/SearchCityRequest.xml");

        $this->userInfoSet($this->requestXml);
        $this->requestDataSetCity($this->requestXml);

        Log::write($this->requestXml->asXML());

        $res = $this->postXmlSSLCurl($this->url,$this->requestXml->asXML());

        Log::write("GTA_response_xml: ".$res);

        return $res;
    }

    private function requestDataSetCity($xml)
    {
        $dataXml = $xml->RequestDetails->addChild("SearchCityRequest");
        /** @var \SimpleXMLElement $dataXml */
        $dataXml->addAttribute('CountryCode', $this->country_code);
        $dataXml->addChild("CityName",$this->city_name);
    }

}