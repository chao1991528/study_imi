<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2020/12/22
 * Time: 10:01
 */


namespace ImiApp\Enum;

use Imi\Enum\Annotation\EnumItem;
use Imi\Enum\BaseEnum;

abstract class MessageCode extends BaseEnum
{
    /**
     * @EnumItem("成功")
     */
    const SUCCESS = 0;

    /**
     * @EnumItem("失败")
     */
    const ERROR = 500;

    /**
     * @EnumItem("未找到记录")
     */
    const NOT_FOUND = 404;

    /**
     * @EnumItem("未登录")
     */
    const USER_NOT_LOGIN = 1001;

}