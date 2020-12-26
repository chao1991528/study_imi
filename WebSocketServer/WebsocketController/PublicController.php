<?php


namespace ImiApp\WebSocketServer\WebsocketController;

use Imi\Controller\WebSocketController;
use Imi\Server\Route\Annotation\WebSocket\WSRoute;
use Imi\Server\Route\Annotation\WebSocket\WSAction;
use Imi\Server\Route\Annotation\WebSocket\WSController;

/**
 * @WSController
 */
class PublicController extends WebSocketController
{
    /**
     * @WSAction
     * @WSRoute({"action":"ping"})
     */
    public function ping($data)
    {
        return [
            'action' => 'pong',
            'msg' => 'hello'
        ];
    }
}