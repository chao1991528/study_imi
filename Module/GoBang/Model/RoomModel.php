<?php


namespace ImiApp\Module\GoBang\Model;


use Imi\Model\Annotation\Column;
use Imi\Model\RedisModel;
use ImiApp\Module\GoBang\Enum\RoomStatus;
use Imi\Model\Annotation\Entity;
use Imi\Model\Annotation\RedisEntity;
use Imi\Aop\Annotation\Inject;

/**
 * Test
 * @Entity
 * @RedisEntity(storage="hash", key="imi:gobang:rooms", member="{roomId}")
 */
class RoomModel extends RedisModel
{
    /**
     * @Inject("UserService")
     * @var \ImiApp\Module\User\Service\UserService
     */
    protected $userService;
    /**
     * 房间id
     * @Column
     * @var int
     */
    protected $roomId;
    /**
     * 创建者id
     * @Column
     * @var int
     */
    protected $creatorId;
    /**
     * 玩家1
     * @Column
     * @var int
     */
    protected $playerId1;
    /**
     * 玩家2
     * @Column
     * @var int
     */
    protected $playerId2;

    /**
     * 房间标题
     * @Column
     * @var string
     */
    protected $title;

    /**
     * 游戏状态
     * @Column
     * @var int
     */
    protected $status = RoomStatus::WAIT_START;

    public function setCreatorId(int $id)
    {
        $this->creatorId = $id;

        return $this;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set 房间id
     *
     * @param int $roomId  房间id
     *
     * @return self
     */
    public function setRoomId(int $roomId)
    {
        $this->roomId = $roomId;

        return $this;
    }

    public function getRoomId(){
        return $this->roomId;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getCreatorId(){
        return $this->creatorId;
    }

    public function setPlayerId1($userId){
        $this->playerId1 = $userId;
    }

    public function setPlayerId2($userId){
        $this->playerId2 = $userId;
    }

    public function getPlayerId1(){
        return $this->playerId1 ?? 0;
    }

    public function getPlayerId2(){
        return $this->playerId2 ?? 0;
    }

}