<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2020/12/22
 * Time: 14:46
 */


namespace ImiApp\Exception;


use ImiApp\Enum\MessageCode;
use Throwable;

class BusinessException extends \Exception
{
    public function __construct($message = "网络错误", $code = MessageCode::ERROR, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}