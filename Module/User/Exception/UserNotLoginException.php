<?php


namespace ImiApp\Module\User\Exception;


use ImiApp\Enum\MessageCode;

class UserNotLoginException extends \Exception
{
    public function __construct($message = "用户未登陆", $code = MessageCode::USER_NOT_LOGIN, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}