<?php
/**
 * Created by PhpStorm.
 * User: ami
 * Date: 10/29/15
 * Time: 10:02 AM
 */

namespace AppBundle\Tic;



class Board
{
    private $grid;

    const NOTHING = '';
    const O = 'o';
    const X = 'x';

    public $cols = 3;

    /**
     * Board constructor.
     * @param int $cols
     */
    public function __construct($cols)
    {
        $this->cols = $cols;
        $this->initGrid();
        $this->clear();
    }

    private function initGrid()
    {
        $this->grid = array();

        for($i = 0; $i < $this->cols; $i++){
            array_push($this->grid, array());
        }
    }

    public function clear()
    {
        for($i = 0; $i < $this->cols; $i++) {
            for($j = 0; $j < $this->cols; $j++) {
                $this->setSquare($i, $j, self::NOTHING);
            }
        }
    }

    public function getSquare($row, $col)
    {
        return $this->grid[$row][$col];
    }

    public function setSquare($row, $col, $val)
    {
        $this->grid[$row][$col] = $val;
        return $this->getSquare($row, $col);
    }

    public function isFull()
    {
        for($i = 0; $i < $this->cols; $i++) {
            for($j = 0; $j < $this->cols; $j++) {
                if(self::NOTHING == $this->getSquare($i, $j)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function isEmpty()
    {
        for($i = 0; $i < $this->cols; $i++) {
            for($j = 0; $j < $this->cols; $j++) {
                if(self::NOTHING != $this->getSquare($i, $j)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function loadBoard($grid)
    {
        $this->grid = $grid;
    }

    public function isBoardWon()
    {
        $res = false;
        for($i = 0; $i < $this->cols; $i++) {
            $res = $res || $this->isColWon($i) || $this->isRowWon($i);
        }
        $res = $res || $this->isMainDiagonWon();
        $res = $res || $this->isSecondDiagonWon();
        return $res;
    }

    public function isRowWon($row)
    {
        $square = $this->getSquare($row, 0);
        if(self::NOTHING == $square) {
            return false;
        }
        for($i = 1; $i < $this->cols; $i++) {
            if($square != $this->getSquare($row, $i)) {
                return false;
            }
        }
        return true;
    }

    public function isColWon($col)
    {
        $square = $this->getSquare(0, $col);
        if(self::NOTHING == $square) {
            return false;
        }
        for($i = 1; $i < $this->cols; $i++) {
            if($square != $this->getSquare($i, $col)) {
                return false;
            }
        }
        return true;
    }

    public function isMainDiagonWon()
    {
        $square = $this->getSquare(0, 0);
        if(self::NOTHING == $square) {
            return false;
        }
        for($i = 1; $i < $this->cols; $i++) {
            if($square != $this->getSquare($i, $i)) {
                return false;
            }
        }
        return true;
    }

    public function isSecondDiagonWon()
    {
        $square = $this->getSquare(0, $this->cols - 1);
        if(self::NOTHING == $square) {
            return false;
        }
        for($i = 1; $i < $this->cols; $i++) {
            if($square != $this->getSquare($i, $this->cols - 1 - $i)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getGrid()
    {
        return $this->grid;
    }

    public function markWinner(){
        for($col = 0; $col < $this->cols; $col++) {
            if ($this->isColWon($col)) {
                for ($row = 0; $row < $this->cols; $row++) {
                    $this->setSquare($row, $col, strtoupper($this->getSquare($row, $col)));
                }
            }
        }

        for($row = 0; $row < $this->cols; $row++) {
            if($this->isRowWon($row)){
                for($col = 0; $col < $this->cols; $col++) {
                    $this->setSquare($row, $col, strtoupper($this->getSquare($row, $col)));
                }
            }
        }

        if($this->isMainDiagonWon()){
            for($i = 0; $i < $this->cols; $i++) {
                $this->setSquare($i, $i, strtoupper($this->getSquare($i, $i)));
            }
        }

        if($this->isSecondDiagonWon()){
            for($i = 0; $i < $this->cols; $i++) {
                $this->setSquare($i, $this->cols - 1 - $i, strtoupper($this->getSquare($i, $this->cols - 1 - $i)));
            }
        }
    }


}