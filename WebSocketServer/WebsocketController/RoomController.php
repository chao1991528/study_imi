<?php


namespace ImiApp\WebSocketServer\WebsocketController;

use Imi\App;
use Imi\ConnectContext;
use Imi\Controller\WebSocketController;
use Imi\Server\Route\Annotation\WebSocket\WSController;
use Imi\Aop\Annotation\Inject;
use ImiApp\Module\GoBang\Enum\MessageActions;
use Imi\Server\Route\Annotation\WebSocket\WSAction;
use Imi\Server\Route\Annotation\WebSocket\WSRoute;

/**
 * @WSController
 */
class RoomController extends WebSocketController
{
    /**
     * @Inject("RoomLogic")
     * @var \ImiApp\Module\GoBang\Logic\RoomLogic
     */
    protected $roomLogic;

    /**
     * @WSAction
     * @WSRoute({"action":"room.create"})
     */
    public function create($data)
    {
        $roomInfo = $this->roomLogic->create(ConnectContext::get('userId'), $data['title'] ?? null);
        return [
            'action' => MessageActions::ROOM_CREATE,
            'msg' => $roomInfo
        ];
    }

    /**
     * @WSAction
     * @WSRoute({"action":"room.list"})
     */
    public function getList($data)
    {
        $this->server->joinGroup("rooms", $this->frame->getFd());
        $list = $this->roomLogic->getList();
        return [
            'action' => MessageActions::ROOM_LIST,
            'list' => $list
        ];
    }

    /**
     * @WSAction
     * @WSRoute({"action":"room.info"})
     */
    public function info($data)
    {
        return [
            'action' => MessageActions::ROOM_INFO,
            'roomInfo' => $this->roomLogic->info($data['roomId'])
        ];
    }

    /**
     * @WSAction
     * @WSRoute({"action":"room.join"})
     */
    public function join($data)
    {
        return [
            'action' => MessageActions::ROOM_JOIN,
            'roomInfo' => $this->roomLogic->join(ConnectContext::get('userId') ,$data['roomId'])
        ];
    }

    /**
     * @WSAction
     * @WSRoute({"action":"room.leave"})
     */
    public function leave($data)
    {
        return [
            'action' => MessageActions::ROOM_LEAVE,
            'roomInfo' => $this->roomLogic->leave(ConnectContext::get('userId') ,$data['roomId'])
        ];
    }

}