<?php


namespace ImiApp\Module\GoBang\Service;


use Imi\Config;
use Imi\Redis\Redis;
use ImiApp\Exception\BusinessException;
use ImiApp\Module\GoBang\Model\RoomModel;
use Imi\Bean\Annotation\Bean;

/**
 * @Bean("RoomService")
 */
class RoomService
{
    public function create($creatorId, $title){
        $room = RoomModel::newInstance();
        $room->setCreatorId($creatorId);
        $room->setTitle($title);
        $room->setRoomId(Redis::incr('imi:gobang:roomAtomic'));
        $room->save();
        return $room;
    }

    public function info($roomId) : RoomModel
    {
        var_dump($roomId);
        $room = RoomModel::find($roomId);
        var_dump($room);
        if(!$room) {
            throw new BusinessException('房间不存在');
        }
        return $room;
    }

    public function join(int $userId, int $roomId) : RoomModel {
        $room = $this->info($roomId);
        if(0 === $room->getPlayerId1()) {
            $room->setPlayerId1($userId);
        } elseif (0 === $room->getPlayerId2()) {
            $room->setPlayerId2($userId);
        } else {
            throw new BusinessException('房间已满');
        }
        $room->save();
        return $room;
    }

    public function lock(int $roomId, callable $callback, $afterCallback = null)
    {
        $option = Config::get('@app.room.lock.options');
        $lock = new \Imi\Lock\Handler\Redis($roomId, $option);
        return $lock->lock($callback, $afterCallback);
    }

    public function leave(int $userId, int $roomId) : RoomModel
    {
        $room = RoomModel::find($roomId);
        if($userId == $room->getPlayerId1()) {
            $room->setPlayerId1(0);
            $room->setPlayer1Ready(false);
        } elseif($userId == $room->getPlayerId2()) {
            $room->setPlayerId2(0);
            $room->setPlayer2Ready(false);
        } else {
            throw new BusinessException('玩家不在房间中');
        }
        $room->save();
        return $room;
    }
}