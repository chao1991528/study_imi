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
    /**
     * @EnumItem("房间信息")
     */
    const ROOM_INFO = 'room.info';
    /**
     * @EnumItem("加入房间")
     */
    const ROOM_JOIN = 'room.join';
    /**
     * @EnumItem("加入房间")
     */
    const ROOM_LEAVE = 'room.leave';
    /**
     * @EnumItem("删除房间")
     */
    const ROOM_DESTROY = 'room.destroy';
}