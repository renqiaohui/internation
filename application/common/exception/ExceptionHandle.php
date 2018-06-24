<?php
/**
 * Created by PhpStorm.
 * User: cengweinan
 * Date: 18/6/5
 * Time: 上午10:12
 */

namespace app\common\exception;

use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;

class ExceptionHandle extends Handle
{
    public function render(Exception $e)
    {
        // 参数验证异常
        if ($e instanceof ValidateException) {
            return json([
                'code' => 422,
                'msg' => $e->getMessage()
            ]);
        }

        // 自定义异常
        if ($e instanceof ResponseException) {
            // 401不要用，前端会返回401 不会返回200
            if ($e->getCode() === 401) {
                return json('登录过期', 401);
            }

            return json([
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);
        }

        // 请求异常
        if ($e instanceof HttpException && request()->isAjax()) {
            return response($e->getMessage(), $e->getStatusCode());
        }

        // 其他错误交给系统处理
        return parent::render($e);
    }
}