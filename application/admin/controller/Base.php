<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/4
 * Time: 下午6:12
 */

namespace app\admin\controller;

use app\common\exception\ResponseException;
use app\common\tool\Session;
use think\Controller;
use think\facade\Request;

class Base extends Controller
{
    public function __construct()
    {
        parent::__construct();

        //设置跨域
        header("Access-Control-Allow-Origin: " . config('env.Access-Control-Allow-Origin'));
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId");

        if (Request::isOptions()) exit;

        $session = Session::getInstance();
        if (!$session->get('user')) {
            if (Request::url() !== '/admin/admin/login') {
                throw new ResponseException('登录过期', 401);
            }
        }
    }
}