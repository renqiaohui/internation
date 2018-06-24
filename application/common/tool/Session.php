<?php
/**
 * session封装
 * User: cengweinan
 * Date: 18/6/5
 * Time: 下午1:58
 */

namespace app\common\tool;

class Session
{
    private $lifeTime = 24 * 7 * 3600;
    private static $instance = null;

    private function __construct()
    {
        session_set_cookie_params($this->lifeTime);
        if (!session_id()) session_start();
    }

    /**
     * 获取当前对象唯一方式
     *
     * @return Session
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * 禁止对象克隆
     */
    private function __clone(){}

    /**
     * 禁止对象重建
     */
    private function __wakeup(){}

    /**
     * 设置session数据 $key允许是一个sessionKey和value的一维数组
     * 如果$key是单个sessionKey,需要设置$value的值
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        if (is_array($key)) {
            foreach ($key as $sessionKey => $value) {
                $_SESSION[$sessionKey] = $value;
            }

            return true;
        }

        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * 获得session数据
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * 终结session
     *
     * @param $key
     * @return bool
     */
    public function destroy($key = null)
    {
        if ($key !== null && empty($_SESSION[$key])) {
            return true;
        }

        if ($key) {
            unset($_SESSION[$key]);
        } else {
            session_destroy();
        }
    }
}