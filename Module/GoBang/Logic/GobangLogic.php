<?php


namespace ImiApp\Module\GoBang\Logic;


use Imi\ConnectContext;
use Imi\Redis\Redis;
use Imi\RequestContext;
use Imi\Server\Server;
use ImiApp\Exception\BusinessException;
use ImiApp\Module\GoBang\Enum\GobangCell;
use ImiApp\Module\GoBang\Enum\MessageActions;
use ImiApp\Module\GoBang\Enum\RoomStatus;
use ImiApp\Module\GoBang\Model\GobangModel;
use ImiApp\Module\GoBang\Model\RoomModel;
use ImiApp\Module\GoBang\Service\GobangService;
use ImiApp\Module\GoBang\Service\RoomService;
use Imi\Aop\Annotation\Inject;
use Imi\Bean\Annotation\Bean;
use ImiApp\Module\User\Service\UserService;

/**
 * @Bean("GobangLogic")
 */
class GobangLogic
{
    /**
     * @Inject("GobangService")
     * @var GobangService
     */
    protected $gobangService;
    /**
     * @Inject("RoomService")
     * @var RoomService
     */
    protected $roomService;
    /**
     * @Inject("UserService")
     * @var UserService
     */
    protected $userService;

    /**
     * @Inject("RoomLogic")
     * @var RoomLogic
     */
    protected $roomLogic;


    /**
     * 落子
     */
    public function go(int $roomId, int $userId, int $x, int $y) : GobangModel
    {
        $this->roomService->lock($roomId, function () use ($roomId, $userId, $x, $y){
            $data = [];
            $game = $this->gobangService->go($roomId, $userId, $x, $y);
            $color = $game->referee($x, $y);
            if($color == GobangCell::NONE) {
                $data['winner'] = null;
            } else {
                $room = $this->roomService->info($roomId);
                if($color == $game->getPlayer1Color()) {
                    $winnerUserId = $room->getPlayerId1();
                } elseif($color == $game->getPlayer2Color()) {
                    $winnerUserId = $room->getPlayerId2();
                } else {
                    throw new BusinessException('数据错误');
                }

                $data['winner'] = $this->userService->get($winnerUserId);
                $room->setPlayer1Ready(false);
                $room->setPlayer2Ready(false);
                $room->setStatus(RoomStatus::WAIT_START);
                $room->save();
                defer(function () use ($roomId, $data){
                    $this->roomLogic->pushRoomMessage($roomId, MessageActions::ROOM_INFO, $data);
                });
            }
            $data['game'] = $game;
            $this->roomLogic->pushRoomMessage($roomId, MessageActions::GOBANG_INFO, $data);
        });

    }
}