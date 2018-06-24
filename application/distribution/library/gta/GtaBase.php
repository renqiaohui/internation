<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/5/29
 * Time: 下午2:46
 */

namespace app\distribution\library\gta;

use app\distribution\library\ConfigCommon;
use app\distribution\library\ConfigGta;
use app\distribution\library\CurlTrait;

abstract class GtaBase
{

    use CurlTrait;
    use UserInfoSetTrait;
    protected $requestXml;
    protected $url = ConfigCommon::GTA_URL;
    //入口文件
    abstract function invoke();
}