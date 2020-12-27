<?php


namespace ImiApp\Module\GoBang\Listener;


use Imi\Event\EventParam;
use Imi\Event\IEventListener;
use Imi\Redis\Redis;

class AppInit implements IEventListener
{
    public function handle(EventParam $e)
    {
        // TODO: Implement handle() method.
        Redis::del('imi:gobang:rooms');
        Redis::del('imi:gobang:games');
        Redis::del('imi:gobang:roomAtomic');
    }
}