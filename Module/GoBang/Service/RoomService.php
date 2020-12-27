<?php


namespace ImiApp\Module\GoBang\Service;


use Imi\Redis\Redis;
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
}