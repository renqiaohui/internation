<?php
/**
 * Created by PhpStorm.
 * User: wj
 * Date: 18-6-8
 * Time: 下午1:25
 */

namespace app\distribution\library\gta;


use think\facade\Env;
use think\facade\Log;

class SearchCountry extends GtaBase
{
    private $country_name;

    function setCountryName($value){
        $this->country_name = $value;
    }


    function invoke()
    {
        $this->requestXml = simplexml_load_file(Env::get("app_path")."distribution/xml/gta/SearchCountryRequest.xml");
        $this->userInfoSet($this->requestXml);
        $this->requestDataSetCountry($this->requestXml);

        Log::write($this->requestXml->asXML());

        $res = $this->postXmlSSLCurl($this->url,$this->requestXml->asXML());

        Log::write("GTA_response_xml: ".$res);

        return $res;
    }

    private function requestDataSetCountry($xml)
    {
        $data_xml = $xml->RequestDetails->addChild("SearchCountryRequest");
        /** @var \SimpleXMLElement $data_xml */
        $data_xml->addChild("CountryName",$this->country_name);
    }

}