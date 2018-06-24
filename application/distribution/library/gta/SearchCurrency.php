<?php
/**
 * Created by PhpStorm.
 * User: wj
 * Date: 18-6-7
 * Time: 下午1:21
 */

namespace app\distribution\library\gta;


use app\distribution\library\ConfigGta;
use think\facade\Env;
use think\facade\Log;

class SearchCurrency extends GtaBase
{
    private $currency_code;
    private $currency_name;

    function setCurrencyName($value){
        $this->currency_name = $value;
    }

    function setCurrencyCode($value){
        $this->currency_code = $value;
    }

    function invoke()
    {
        $this->requestXml = simplexml_load_file(Env::get("app_path")."distribution/xml/gta/SearchCurrencyRequest.xml");

        $this->userInfoSet($this->requestXml);
        $this->requestDataSetCurrency($this->requestXml);

        Log::write("request_xml: ".$this->requestXml->asXML());

        $res = $this->postXmlSSLCurl($this->url,$this->requestXml->asXML());

        Log::write("GTA_response_xml: ".$res);

        return $res;
    }

    private function requestDataSetCurrency($xml)
    {
        $dataXml = $xml->RequestDetails->addChild("SearchCurrencyRequest");
        /** @var \SimpleXMLElement $dataXml */
        $dataXml->addChild("CurrencyName",ConfigGta::CURRENCY);
        $dataXml->addChild("CurrencyCode",$this->currency_code);
    }

}