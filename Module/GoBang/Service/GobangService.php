<?php


namespace ImiApp\Module\GoBang\Service;


use Imi\Aop\Annotation\Inject;
use Imi\Config;
use Imi\Redis\Redis;
use ImiApp\Exception\BusinessException;
use ImiApp\Exception\NotFoundException;
use ImiApp\Module\GoBang\Enum\GobangCell;
use ImiApp\Module\GoBang\Model\GobangModel;
use ImiApp\Module\GoBang\Model\RoomModel;
use Imi\Bean\Annotation\Bean;

/**
 * @Bean("GobangService")
 */
class GobangService
{
    /**
     * @Inject("RoomService")
     * @var RoomService
     */
    protected $roomService;

    public function getByRoomId($roomId)
    {
        $record = GobangModel::find([
            'roomId' => $roomId
        ]);
        if(!$record) {
            throw new NotFoundException('战局不存在');
        }
        return $record;
    }

    public function create(int $roomId){
        $gobangGame = new GobangModel([
            'roomId' => $roomId,
            'size' => 15
        ]);
        $gobangGame->initMap();
        if(1 === mt_rand(0,1)) {
            $gobangGame->setPlayer1Color(GobangCell::BLACK_PIECE);
            $gobangGame->setPlayer2Color(GobangCell::RED_PIECE);
        } else {
            $gobangGame->setPlayer1Color(GobangCell::RED_PIECE);
            $gobangGame->setPlayer2Color(GobangCell::BLACK_PIECE);
        }
        $gobangGame->setCurrentColor(GobangCell::BLACK_PIECE);
        $gobangGame->save();
        return $gobangGame;
    }

    public function go(int $roomId, int $userId, int $x, int $y) : GobangModel
    {
        $room = $this->roomService->info($roomId);
        $game = $this->getByRoomId($roomId);
        $currentColor = $game->getCurrentColor();
        if($currentColor == $game->getPlayer1Color() && $userId != $room->getPlayerId1()) {
            throw new BusinessException('非法操作');
        }
        if($currentColor == $game->getPlayer2Color() && $userId != $room->getPlayerId2()) {
            throw new BusinessException('非法操作');
        }
        $game->setCell($x, $y, $currentColor);
        if($currentColor == GobangCell::RED_PIECE) {
            $game->setCurrentColor(GobangCell::BLACK_PIECE);
        }
        if($currentColor == GobangCell::BLACK_PIECE) {
            $game->setCurrentColor(GobangCell::BLACK_PIECE);
        }
        $game->save();
        return $game;
    }
}