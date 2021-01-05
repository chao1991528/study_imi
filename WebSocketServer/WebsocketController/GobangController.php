<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2021/1/5
 * Time: 13:53
 */


namespace ImiApp\WebSocketServer\WebsocketController;


use Imi\Aop\Annotation\Inject;
use Imi\ConnectContext;
use Imi\Controller\WebSocketController;
use Imi\Server\Route\Annotation\WebSocket\WSController;
use ImiApp\Module\GoBang\Logic\GobangLogic;
use Imi\Server\Route\Annotation\WebSocket\WSAction;
use Imi\Server\Route\Annotation\WebSocket\WSRoute;

/**
 * 五子棋控制器
 * @WSController(route="/ws")
 * Class GobangController
 * @package ImiApp\WebSocketServer\WebsocketController
 */
class GobangController extends WebSocketController
{
    /**
     * @Inject("GobangLogic")
     * @var GobangLogic
     */
    protected $gobangLogic;

    /**
     * @WSAction
     * @WSRoute({"action"="gobang.go"})
     * @param $data
     */
    public function go($data)
    {
        $userId = ConnectContext::get('userId');

        $this->gobangLogic->go($data['roomId'], $userId, $data['x'], $data['y']);
    }
}