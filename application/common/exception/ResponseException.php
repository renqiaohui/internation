<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/5
 * Time: 上午10:21
 */

namespace app\common\exception;

class ResponseException extends \Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}