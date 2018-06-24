<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/5/29
 * Time: 下午2:27
 */

namespace app\distribution\library;

use think\facade\Log;

trait CurlTrait
{

    protected function postXmlSSLCurl($url,$xml){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:text/xml; charset=utf-8"));//设置header
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // Post提交的数据包

        curl_setopt($curl, CURLOPT_TIMEOUT, 150); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($curl); // 执行操作

        $errorCode = curl_errno($curl);
        Log::write($errorCode);

        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据，json格式
    }


    protected function postDownloadSSLCurl($url,$xml){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_HTTPHEADER, Array("Content-Type:application/download; charset=utf-8"));//设置header
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // Post提交的数据包

        curl_setopt($curl, CURLOPT_TIMEOUT, 600); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $tmpInfo = curl_exec($curl); // 执行操作

        $errorCode = curl_errno($curl);
        Log::write($errorCode);

        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据，json格式
    }
}