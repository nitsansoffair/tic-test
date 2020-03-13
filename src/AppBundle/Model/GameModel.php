<?php
/**
 * Created by PhpStorm.
 * User: ami
 * Date: 10/29/15
 * Time: 12:30 PM
 */

namespace AppBundle\Model;


use AppBundle\Tic\Game;
use Symfony\Component\HttpFoundation\Session\Session;

class GameModel
{
    /** @var  Session */
    private $session;

    /** @var  Game */
    private $game;

    /**
     * GameModel constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->loadGame();
        $this->storeGame();
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
        $this->storeGame();
    }

    private function loadGame()
    {
        $cols = $this->session->get('cols');
        $json = $this->session->get('game', $this->emptyGameJson($cols));
        $game = new Game();
        $game->unserialize($json, $cols);
        $this->game = $game;
        return $this->game;
    }

    private function storeGame()
    {
        $this->session->set('game', $this->game->serialize());
    }

    private function emptyGameJson($cols)
    {
        $game = new Game();
        $game->start($cols);
        return $game->serialize();
    }

    public function startGame($cols)
    {
        $this->game->start($cols);
        $this->storeGame();
    }

    public function setCols($cols){
        $this->session->set('cols', $cols);
    }

    public function getCols(){
        $cols = $this->session->get('cols');

        $array = array();

        for($i = 0; $i < $cols; $i++){
            array_push($array, $i);
        }

        return $array;
    }
}