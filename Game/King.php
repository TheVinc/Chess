<?php

Class King extends Piece
{
    private $type;

    public function __construct()
    {
        parent::__construct();
        $this->type = "king";
    }

    public function getAvailablePosition($board)
    {
        $available_positions = array();
        if ($this->getPosY() < 8)
        {
            $piece = $board->getPieceFor($this->getPosX(), $this->getPosY() + 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX(), $this->getPosY() + 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX(), $this->getPosY() + 1);
            }
        }

        if ($this->getPosY() > 1)
        {
            $piece = $board->getPieceFor($this->getPosX(), $this->getPosY() - 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX(), $this->getPosY() - 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX(), $this->getPosY() - 1);
            }
        }

        if ($this->getPosX() > 1)
        {
            $piece = $board->getPieceFor($this->getPosX() - 1, $this->getPosY());
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY());
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY());
            }
        }

        if ($this->getPosX() < 8)
        {
            $piece = $board->getPieceFor($this->getPosX() + 1, $this->getPosY());
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY());
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY());
            }
        }

        if ($this->getPosX() < 8 && $this->getPosY() < 8)
        {
            $piece = $board->getPieceFor($this->getPosX() + 1, $this->getPosY() + 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() + 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() + 1);
            }
        }

        if ($this->getPosX() < 8 && $this->getPosY() > 1)
        {
            $piece = $board->getPieceFor($this->getPosX() + 1, $this->getPosY() - 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() - 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() + 1, $this->getPosY() - 1);
            }
        }

        if ($this->getPosX() > 1 && $this->getPosY() > 1)
        {
            $piece = $board->getPieceFor($this->getPosX() - 1, $this->getPosY() - 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() - 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() - 1);
            }
        }

        if ($this->getPosX() > 1 && $this->getPosY() < 8)
        {
            $piece = $board->getPieceFor($this->getPosX() - 1, $this->getPosY() + 1);
            if ($piece != null && is_object($piece))
            {
                if ($piece->getColor() != $this->getColor())
                    $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() + 1);
            }
            else
            {
                $available_positions[] = new Position($this->getPosX() - 1, $this->getPosY() + 1);
            }
        }

        return $available_positions;
    }

    public function toArray()
    {
        $myArray = array();
        $myArray["posX"] = $this->getPosX();
        $myArray["posY"] = $this->getPosY();
        $myArray["color"] = $this->getColor();
        $myArray["type"] = $this->getType();

        return $myArray;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
}