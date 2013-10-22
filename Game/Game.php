<?php

function autoload_game($classname)
{
    include $classname . '.php';
}

Class Game
{
    private $_game_board;

    public function __construct()
    {
        spl_autoload_register('autoload_game');

        if (isset($_GET["delete_chess_game"]))
        {
            session_destroy();
            echo "Destroying session. </br>";
        }
        else
        {
            if (isset($_SESSION["chess_game"]))
            {
                $this->_game_board = unserialize($_SESSION["chess_game"]);
            }
            else
            {
                $this->_game_board = new Board();
                $this->_game_board->initialize();
                $_SESSION["chess_game"] = serialize($this->_game_board);
            }

            if (isset($_GET["showPieces"]))
            {
                $this->showPieces();
            }

            if (isset($_GET["getPosFor"]))
            {
                $this->getPosFor();
            }

            if (isset($_GET["moveTo"]))
            {
                $this->moveTo();
            }

            if (isset($_GET["moveBack"]))
            {
                $this->moveBack();
            }

            if (isset($_GET["getPiecesThatCanTransform"]))
            {
                $this->getPiecesThatCanTransform();
            }

            if (isset($_GET["transformPiece"]))
            {
                $this->transformPiece();
            }

            if (isset($_GET["showPlayerTurnColor"]))
            {
                $this->showPlayerTurnColor();
            }

            if (isset($_GET["showHistory"]))
            {
                $this->showHistory();
            }
        }
    }

    public function moveTo()
    {
        if (isset($_GET["posX"]) && isset($_GET["posY"]) && isset($_GET["destX"]) && isset($_GET["destY"]))
        {
            $posX = intval($_GET["posX"]);
            $posY = intval($_GET["posY"]);
            $destX = intval($_GET["destX"]);
            $destY = intval($_GET["destY"]);

            if ($this->_game_board->movePiece($posX, $posY, $destX, $destY))
            {
                $_SESSION["chess_game"] = serialize($this->_game_board);
            }
        }

        $this->showPieces();
    }

    public function moveBack()
    {
        $this->_game_board->moveBack();
        $_SESSION["chess_game"] = serialize($this->_game_board);
    }

    public function getPiecesThatCanTransform()
    {
        if (isset($_GET["color"]))
        {
            $pieces = $this->_game_board->getPiecesThatCanTransform($_GET["color"]);
            header("Content-Type: application/json");
            $response = array();

            foreach ($pieces as $piece)
            {
                $response[] = $piece->toArray();
            }
            echo json_encode($response);
        }
    }

    public function transformPiece()
    {
        if (isset($_GET["posX"]) && isset($_GET["posY"]) && isset($_GET["type"]) && isset($_GET["color"]))
        {
            if ($this->_game_board->transformPiece(intval($_GET["posX"]), intval($_GET["posY"]), $_GET["type"], $_GET["color"]))
            {
                $_SESSION["chess_game"] = serialize($this->_game_board);
            }
        }
    }

    public function getPosFor()
    {
        $available_positions = array();
        if (isset($_GET["posX"]) && isset($_GET["posY"]))
        {
            $available_positions = $this->_game_board->getAvailablePositionFor(intval($_GET["posX"]), intval($_GET["posY"]));
        }

        header("Content-Type: application/json");
        $response = array();

        foreach ($available_positions as $position)
        {
            $response[] = $position->toArray();
        }
        echo json_encode($response);
    }

    public function showPieces()
    {
        header("Content-Type: application/json");
        $response = array();

        foreach ($this->_game_board->getPieces() as $piece)
        {
            $response[] = $piece->toArray();
        }
        echo json_encode($response);
    }

    public function showPlayerTurnColor()
    {
        echo $this->_game_board->getPlayerTurnColor();
    }

    public function showHistory()
    {
        header("Content-Type: application/json");

        $response = array();

        if ($this->_game_board != null && $this->_game_board->getPositionsHistory() != null)
        {
            foreach ($this->_game_board->getPositionsHistory() as $moveHistory)
            {
                $moveHistoryArray = array();
                $moveHistoryArray[] = $moveHistory[0]->toArray();
                $moveHistoryArray[] = $moveHistory[1]->toArray();
                $response[] = $moveHistoryArray;
            }
        }
        echo json_encode($response);
    }

    public function setGameBoard($game_board)
    {
        $this->_game_board = $game_board;
    }

    public function getGameBoard()
    {
        return $this->_game_board;
    }
}