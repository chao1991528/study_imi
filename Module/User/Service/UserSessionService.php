<?php

namespace ImiApp\Module\User\Service;

use Imi\RequestContext;
use ImiApp\Module\User\Model\User;

class UserSessionService
{
    /**
     * @Inject("UserService")
     * @var userService
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
            $userId = RequestContext::get('userId', null, $fd);
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