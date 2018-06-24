<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/5/29
 * Time: 下午3:36
 */

namespace app\distribution\library\gta;



use app\distribution\library\ConfigGta;

trait UserInfoSetTrait
{
    /**
     * @param $xml
     */
    protected function userInfoSet($xml)
    {
        $xml->Source->RequestorID->attributes()->Client=ConfigGta::CLIENT;
        $xml->Source->RequestorID->attributes()->EMailAddress=ConfigGta::EMAIL_ADDRESS;
        $xml->Source->RequestorID->attributes()->Password=ConfigGta::PASSWORD;
    }

    protected function currencySet($xml)
    {
        $xml->Source->RequestorPreferences->attributes()->Language=ConfigGta::LANGUAGE;
        $xml->Source->RequestorPreferences->attributes()->Currency=ConfigGta::CURRENCY;

    }
}