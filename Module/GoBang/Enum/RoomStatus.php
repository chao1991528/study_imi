<?php


namespace ImiApp\Module\GoBang\Enum;

use Imi\Enum\Annotation\EnumItem;
use Imi\Enum\BaseEnum;

 abstract class RoomStatus extends BaseEnum
{
     /**
      * @EnumItem("等待开始")
      */
    const WAIT_START = 1;
     /**
      * @EnumItem("游戏中")
      */
    const GAMING = 2;
}