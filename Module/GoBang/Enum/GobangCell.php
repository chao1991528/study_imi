<?php


namespace ImiApp\Module\GoBang\Enum;

use Imi\Enum\Annotation\EnumItem;
use Imi\Enum\BaseEnum;

 abstract class GobangCell extends BaseEnum
{
     /**
      * @EnumItem("空")
      */
    const NONE = 0;
     /**
      * @EnumItem("黑棋")
      */
    const BLACK_PIECE = 1;
     /**
      * @EnumItem("红棋")
      */
     const RED_PIECE = 2;
}