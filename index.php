<!DOCTYPE html>
<html lang="en" >
<head >
    <meta charset="utf-8" />
    <title >Chess board</title >
    <script type="text/javascript" src="javascript/jquery.js" ></script >
    <script type="text/javascript" src="javascript/chess.js" ></script >
    <link rel="stylesheet" href="Css/chess.css" type="text/css" >
    <link rel="stylesheet" href="Css/button_color.css" type="text/css" >
</head >
<body >
<div id="innerBody" >
    <div id="chessBoardContainer" >
        <div class="letter_column" >
            <div class="letter_box" >
                a
            </div >
            <div class="letter_box" >
                b
            </div >
            <div class="letter_box" >
                c
            </div >
            <div class="letter_box" >
                d
            </div >
            <div class="letter_box" >
                e
            </div >
            <div class="letter_box" >
                f
            </div >
            <div class="letter_box" >
                g
            </div >
            <div class="letter_box" >
                h
            </div >
        </div >
        <div style="clear: both;" ></div >
        <div id="game_container" >
            <div class="number_column" >
                <div class="number_box" >
                    8
                </div >
                <div class="number_box" >
                    7
                </div >
                <div class="number_box" >
                    6
                </div >
                <div class="number_box" >
                    5
                </div >
                <div class="number_box" >
                    4
                </div >
                <div class="number_box" >
                    3
                </div >
                <div class="number_box" >
                    2
                </div >
                <div class="number_box" >
                    1
                </div >
            </div >
            <canvas id="game_canvas" width="600" height="600" style="border:1px solid #000000;" >
            </canvas >
            <div class="number_column" >
                <div class="number_box" >
                    8
                </div >
                <div class="number_box" >
                    7
                </div >
                <div class="number_box" >
                    6
                </div >
                <div class="number_box" >
                    5
                </div >
                <div class="number_box" >
                    4
                </div >
                <div class="number_box" >
                    3
                </div >
                <div class="number_box" >
                    2
                </div >
                <div class="number_box" >
                    1
                </div >
            </div >
        </div >
        <div style="clear: both;" ></div >
        <div class="letter_column" >
            <div class="letter_box" >
                a
            </div >
            <div class="letter_box" >
                b
            </div >
            <div class="letter_box" >
                c
            </div >
            <div class="letter_box" >
                d
            </div >
            <div class="letter_box" >
                e
            </div >
            <div class="letter_box" >
                f
            </div >
            <div class="letter_box" >
                g
            </div >
            <div class="letter_box" >
                h
            </div >
        </div >
        <div style="clear: both;" ></div >
    </div >
    <div id="gameControlPanel" >
        <div id="loader_container" >
            <img id="loader" style="display: none;" src="images/loader.gif" />
        </div >
        <div id="game_info_container" >
            <div id="game_info_inner_container" >
                <div id="game_info" >
                </div >
                <div id="last_move" >
                </div >
            </div >
        </div >
        <div class="sexy_line" ></div >
        <div class="ButtonContainer" >
            <a id="moveBack" class="chessButton blue" >move back</a >
        </div >
        <div class="sexy_line" ></div >
        <div class="ButtonContainer" >
            <a id="resetGameButton" class="chessButton blue" >Reset the game</a >
        </div >
        <div class="sexy_line" ></div >
        <div id="historyContainer" >
            <a id="showHistoryButton" ></a >

            <div id="historyBoard" >
            </div >
        </div >
    </div >
</div >
</body >
</html >



