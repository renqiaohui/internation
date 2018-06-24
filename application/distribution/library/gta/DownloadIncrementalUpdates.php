<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/5/29
 * Time: 下午5:01
 */

namespace app\distribution\library\gta;

use think\facade\Env;
use think\facade\Log;


class DownloadIncrementalUpdates extends GtaBase
{
    //格式为 2018-01-01
    private $from_date;
    private $to_date;
    function setFromDate($value){
        $this->from_date = $value;
    }
    function setToDate($value){
        $this->to_date = $value;
    }
    function invoke(){
        $this->requestXml = simplexml_load_file(Env::get("app_path")."distribution/xml/gta/DownloadItemInformation.xml");

        $this->userInfoSet($this->requestXml);

        $this->requestDateSet($this->requestXml);

        Log::write($this->requestXml->asXML());

        $res = $this->postDownloadSSLCurl($this->url,$this->requestXml->asXML());

        $zip = fopen('test.zip', 'w');
        fwrite($zip, $res);
        fclose($zip);

        return ["code"=>0,"msg"=>"Success"];
    }

    /**
     * @param $xml
     */
    private function requestDateSet($xml){
        $dateXml = $xml->RequestDetails->ItemInformationDownloadRequest->addChild("IncrementalDownloads");
        /** @var \SimpleXMLElement $dateXml */
        $dateXml->addChild("FromDate",$this->from_date);
        $dateXml->addChild("ToDate",$this->to_date);

    }
}