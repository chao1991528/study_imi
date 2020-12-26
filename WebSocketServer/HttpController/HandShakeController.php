<?php
namespace ImiApp\WebSocketServer\HttpController;

use Imi\ConnectContext;
use Imi\Controller\HttpController;
use Imi\RequestContext;
use Imi\Server\View\Annotation\View;
use Imi\Server\Route\Annotation\Route;
use Imi\Server\Route\Annotation\Action;
use Imi\Server\Route\Annotation\Controller;
use Imi\Server\Route\Annotation\WebSocket\WSConfig;
use ImiApp\Module\User\Annotation\LoginRequired;

/**
 * 测试
 * @Controller
 * @View(renderType="html")
 */
class HandShakeController extends HttpController
{
    /**
     * 
     * @Action
     * @Route("/ws")
     * @WSConfig(parserClass=\Imi\Server\DataParser\JsonArrayParser::class)
     * @LoginRequired
     * @return void
     */
    public function ws()
    {
        // 握手处理，什么都不做，框架会帮你做好
        $userService = RequestContext::getBean('UserSessionService');
        $userId = $userService->getUserId();
        ConnectContext::set('userId', $userId);
        $flag = 'ws-' . $userId;
        $currentFd = $this->request->getSwooleRequest()->fd;
        if(!ConnectContext::bindNx($flag, $currentFd)) {
            $fd = ConnectContext::getFdByFlag($flag);
            if($fd) {
                $this->request->getServerInstance()->getSwooleServer()->close($fd);
            }
            if(!ConnectContext::bindNx($flag, $currentFd)) {
                $this->request->getServerInstance()->getSwooleServer()->close($currentFd);
            }
        }

    }

}