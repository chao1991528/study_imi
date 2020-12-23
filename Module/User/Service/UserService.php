<?php
/**
 * Created by PhpStorm
 * User: zyc 392318709@qq.com
 * Date: 2020/12/23
 * Time: 14:40
 */


namespace ImiApp\Module\User\Service;


use Imi\Bean\Annotation\Bean;
use ImiApp\Exception\BusinessException;
use ImiApp\Exception\NotFoundException;
use ImiApp\Module\User\Model\User;

/**
 * Class UserService
 * @package ImiApp\Module\User\Service
 * @Bean("UserService")
 */
class UserService
{
    public function register(string $username, string $password) {
        try {
            $record = $this->getByUsername($username);
            throw new BusinessException("用户名已存在");
        }catch (NotFoundException $e){
            $record = User::newInstance();
            $record->username = $username;
            $record->password = $password;
            $record->save();
        }
    }

    public function getByUsername(String $username) : User {
        $record = User::find(['username' => $username]);
        if(!$record) {
            throw new NotFoundException;
        }
        return $record;
    }

}