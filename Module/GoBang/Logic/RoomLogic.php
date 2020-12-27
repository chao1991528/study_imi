<?php


namespace ImiApp\Module\GoBang\Logic;


use Imi\ConnectContext;
use Imi\Redis\Redis;
use Imi\RequestContext;
use Imi\Server\Server;
use ImiApp\Exception\BusinessException;
use ImiApp\Module\GoBang\Enum\MessageActions;
use ImiApp\Module\GoBang\Model\RoomModel;
use ImiApp\Module\GoBang\Service\RoomService;
use Imi\Aop\Annotation\Inject;
use Imi\Bean\Annotation\Bean;

/**
 * @Bean("RoomLogic")
 */
class RoomLogic
{
    /**
     * @Inject("RoomService")
     * @var RoomService
     */
    protected $roomService;

    /**
     * 创建房间
     */
    public function create(int $userId, string $title) : RoomModel
    {
        $roomId = ConnectContext::get("roomId", null, ConnectContext::getFdByFlag($userId));
        if($roomId) {
            throw new BusinessException('已在房间中，无法创建房间');
        }
        $room = $this->roomService->create($userId, $title);
        //创建房间分组
        RequestContext::getServer()->createGroup('room:' . $room->getRoomId());
        //进入房间
        defer(function (){
            $this->pushRooms();
        });

        return $room;
    }

    public function getList()
    {
//        Redis::del('imi:gobang:rooms');
        $list = Redis::hGetAll('imi:gobang:rooms');
        $result = [];
        var_dump($list);
        foreach ($list as $v){
            $result[] = RoomModel::newInstance($v);
        }
        return $result;
    }

    public function pushRooms(){
        Server::sendToGroup('rooms', [
            'action' => MessageActions::ROOM_LIST,
            'list' => $this->getList()
        ]);
    }
}