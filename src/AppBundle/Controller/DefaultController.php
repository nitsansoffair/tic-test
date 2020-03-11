<?php

namespace AppBundle\Controller;

use AppBundle\Tic\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render(
            'AppBundle:Default:index.html.twig'
        );
    }

    public function startAction($size)
    {
        $this->get('app.model.game')->startGame();
        $game = $this->get('app.model.game')->getGame();
        $this->get('app.model.game')->setSize($size);

        return $this->render(
            'AppBundle:Default:start.html.twig', array(
            'grid' => $game->getBoard()->getGrid(),
            'currentPlayer' => $game->getCurrentPlayer(),
            'size' => $this->get('app.model.game')->getSize()
        ));
    }

    public function playAction($row, $col)
    {
        $messages = array();
        $game = $this->get('app.model.game')->getGame();

        if(!$game->isMoveLegal($row, $col)) {
            $messages []= 'illegal move';
        } else {
            $game->makeMove($row, $col);
            $this->get('app.model.game')->setGame($game);
            if($this->isGameOver($game)) {
                return $this->redirectToRoute('end');
            }
        }

        return $this->render(
            'AppBundle:Default:play.html.twig', array(
            'row' => $row,
            'col' => $col,
            'messages' => $messages,
            'grid' => $game->getBoard()->getGrid(),
            'currentPlayer' => $game->getCurrentPlayer(),
            'size' => $this->get('app.model.game')->getSize()
        ));
    }

    public function endAction()
    {
        $message = '';
        $game = $this->get('app.model.game')->getGame();

        if(Game::STATE_TIE == $game->getState()) {
            $message = 'Game Over: tie! how boring!';
        } else {
            $message = 'Game Over: ' . $game->getWinner() . ' has won!';
        }

        return $this->render(
            'AppBundle:Default:end.html.twig', array(
            'message' => $message,
            'grid' => $game->getBoard()->getGrid(),
            'size' => $this->get('app.model.game')->getSize()
        ));
    }

    private function isGameOver(Game $game)
    {
        return in_array($game->getState(), array(Game::STATE_TIE, Game::STATE_WON));
    }
}
