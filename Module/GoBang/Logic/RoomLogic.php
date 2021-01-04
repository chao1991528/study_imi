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

    public function info($roomId) : RoomModel
    {
        return $this->roomService->info($roomId);
    }

    public function join(int $userId, int $roomId) : RoomModel {
        $room = null;
        $this->roomService->lock($roomId, function () use ($userId, $roomId, &$room){
            $room = $this->roomService->join($userId, $roomId);
        });
        ConnectContext::set('roomId', $roomId);
        RequestContext::getServer()->joinGroup('room:' . $roomId, RequestContext::get('fd'));
        defer(function () use ($roomId, $room){
            $this->pushRoomMessage($roomId, MessageActions::ROOM_INFO, [
                'roomInfo' => $room
            ]);
            $this->pushRooms();
        });
        return $room;
    }

    public function pushRoomMessage($roomId,  string $action, array $data = [])
    {
        $data['action'] = $action;
        Server::sendToGroup('room:' . $roomId, $data);
    }

    public function leave(int $userId, int $roomId){
        $room = null;
        $isDestroyRoom = false;
        $this->roomService->lock($roomId, function () use ($roomId, $userId, &$room, &$isDestroyRoom){
            $room = $this->roomService->leave($userId, $roomId);
            //当房间人数为0时，销毁房间
            if($room->getPerson() <= 0) {
                $isDestroyRoom = true;
                $room->delete();
                return;
            }
        });
        ConnectContext::set('roomId', null);
        //离开房间分组
        RequestContext::getServer()->leaveGroup('room:'. $roomId, RequestContext::get('fd'));
        if($isDestroyRoom) {
            $this->pushRoomMessage($roomId, MessageActions::ROOM_DESTROY);
        }

        defer(function () use ($roomId, $room){
            $this->pushRoomMessage($roomId, MessageActions::ROOM_INFO, [
                'roomInfo' => $room
            ]);
            $this->pushRooms();
        });
        return $room;
    }

    public function onUserClose($userId)
    {
        $roomId = ConnectContext::get('roomId', null, ConnectContext::getFdByFlag($userId));
        if(!$roomId) {
            return;
        }
        $this->leave($userId, $roomId);
    }
}