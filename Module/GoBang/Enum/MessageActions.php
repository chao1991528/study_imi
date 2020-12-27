<?php


namespace ImiApp\Module\GoBang\Enum;


use Imi\Enum\Annotation\EnumItem;
use Imi\Enum\BaseEnum;

abstract class MessageActions extends  BaseEnum
{
    /**
     * @EnumItem("房间列表")
     */
    const ROOM_LIST = 'room.list';
    /**
     * @EnumItem("房间创建")
     */
    const ROOM_CREATE = 'room.create';
}