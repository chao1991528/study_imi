<?php


namespace ImiApp\Module\GoBang\Listener;


use Imi\ConnectContext;
use Imi\Server\Event\Listener\ICloseEventListener;
use Imi\Server\Event\Param\CloseEventParam;
use ImiApp\Module\GoBang\Logic\RoomLogic;
use Imi\Aop\Annotation\Inject;

class OnClose implements ICloseEventListener{
    /**
     * @Inject("RoomLogic")
     * @var RoomLogic
     */
    protected $roomLogic;

    public function handle(CloseEventParam $e)
    {
        // TODO: Implement handle() method.
//        $httpRouteResult = ConnectContext::get('httpRouteResult');
        $httpRouteResult = ConnectContext::get('routeResult');
        if('/ws' === ($httpRouteResult->routeItem->annotation->url ?? null)){
            $userId = ConnectContext::get('userId');
            if($userId){
                $this->roomLogic->onUserClose($userId);
            }
        }
    }
}