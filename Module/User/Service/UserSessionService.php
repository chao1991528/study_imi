<?php

namespace ImiApp\Module\User\Service;

use Imi\Aop\Annotation\Inject;
use Imi\ConnectContext;
use Imi\RequestContext;
use ImiApp\Module\User\Model\User;
use ImiApp\Module\User\Service\UserService;
use Imi\Bean\Annotation\Bean;

/**
 * @Bean("UserSessionService")
 */
class UserSessionService
{
    /**
     * @Inject("UserService")
     *
     * @var UserService
     */
    protected $userService;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var User
     */
    protected $userInfo;

    /**
     * @var bool
     */
    protected $isLogin = false;


    public function __init(){
        $this->init();
    }

    public function init(){
        if($fd = RequestContext::get('fd')){
            $userId = ConnectContext::get('userId', null, $fd);
        } else {
            $userId = false;
        }
        if(!$userId) {
            $userId = \Imi\Server\Session\Session::get('userId');
        }
        if(!$userId){
            return;
        }
        $this->userId = $userId;
        $this->isLogin = true;
    }

    public function isLogin(){
        return $this->isLogin;
    }

    public function getUserInfo(){
        if(!$this->userInfo) {
            $this->userInfo = $this->userService->get($this->userId);
        }
        return $this->userInfo;
    }

    public function getUserId(){
        return $this->userId;
    }

}