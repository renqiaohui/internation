<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/5/29
 * Time: 下午2:46
 */

namespace app\distribution\library\gta;

use think\facade\Env;

use think\facade\Log;

class DownloadCompleteFile extends GtaBase
{

    function invoke(){
        $this->requestXml = simplexml_load_file(Env::get("app_path")."distribution/xml/gta/DownloadItemInformation.xml");

        $this->userInfoSet($this->requestXml);

        Log::write($this->requestXml->asXML());

        $res = $this->postDownloadSSLCurl($this->url,$this->requestXml->asXML());

        return $res;
        Log::write("gta complete file download res: {$res}");
        return ["code"=>0,"msg"=>"Success"];
    }

}