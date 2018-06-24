<?php
/**
 * Created by PhpStorm.
 * User: mtt
 * Date: 2018/6/21
 * Time: 上午9:47
 */

namespace app\common\model;

use think\facade\Log;
use app\common\redis\RoomRedisSingleton;


class RoomRedis
{
    private $redis;
    function __construct()
    {
        $this->redis = RoomRedisSingleton::getRedisConn();
    }

    /**
     * @param $roomInfo
     */
    function setOneRoom($roomInfo)
    {
        $subId = $roomInfo['sub_id'];
        // $this->loadRoomFromDb($subid);
        $res = 0;
        if (empty($roomInfo['sub_id']) || empty($roomInfo['date'])) {
            return $res;
        }
        if(isset($roomInfo["supply_price"])){
            $roomInfo['supply_price'] = number_format($roomInfo['supply_price'], 2, '.', '');
        }
        if (isset($roomInfo['floor_price'])) {
            $roomInfo['floor_price'] = number_format($roomInfo['floor_price'], 2, '.', '');
        }
        if (isset($roomInfo['price'])) {
            $roomInfo['price'] = number_format($roomInfo['price'], 2, '.', '');
        }

        if(isset($roomInfo["breakfast"])){
            $roomInfo["breakfast"] = trim($roomInfo["breakfast"]);
        }
        if(isset($roomInfo["status"])){
            $roomInfo["status"] = trim($roomInfo["status"]);
        }

        $roomInfo['update_time'] = time();

        Log::write("room price set info:".json_encode($roomInfo));

        $res = $this->redis->hmset("room:{$roomInfo['sub_id']}:{$roomInfo['date']}", $roomInfo);
        if ($res) {
            $this->redis->zadd("room:change", 1, "{$roomInfo['sub_id']}:{$roomInfo['date']}");
        }
        return (int)$res;
    }

}