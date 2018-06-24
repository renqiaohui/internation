<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/21
 * Time: 上午9:42
 */
namespace app\common\redis;

class RoomRedisSingleton extends \Redis
{
    private static $redisInstance;
    private static $redisConf = [
        'test' => true,
        'host' => 'localhost',
        'port' => '6379',
        'database' => 1
    ];



    /**
     * 获取redis连接的唯一出口
     */
    static public function getRedisConn(){
        if(!self::$redisInstance instanceof self){
            self::$redisInstance = new self;

            self::$redisInstance->connect(self::$redisConf["host"], self::$redisConf["port"]);
            self::$redisInstance->select(0);
        }
        return self::$redisInstance;

    }
}