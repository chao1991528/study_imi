<?php
namespace ImiApp\Module\User\Model;

use Imi\Bean\Annotation\Inherit;
use Imi\Model\Annotation\Serializables;
use ImiApp\Base\UserBase;

/**
 * tb_user
 * @Inherit
 * @Serializables(mode="deny", fields={"password"})
 */
class User extends UserBase
{

}
