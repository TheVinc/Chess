/* chess.js */

$(document).ready(function () {
    window.requestAnimFrame = (function (callback) {
        return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function (callback) {
                window.setTimeout(callback, 1000 / 60);
            };
    })();

    CHESS.initGame();
});

var CHESS = {
    initGame:function () {
        var base = this;
        $.ajax({
            url:"/entry.php/?initialize",
            type:"get",
            data:{
            },
            beforeSend:function () {
                $("#game_info").html("Initializing game...");
                $("#historyBoard").html("");
                $("#showHistoryButton").html(" ");
                $("#last_move").html(" ");
                $("#loader").show();
            },
            success:function (data, status) {
                $("#loader").hide();
                base.init();
            }
        });
    },
    init:function () {
        var base = this;
        base.BLOCK_COLOUR_1 = "#d2691e";
        base.BLOCK_COLOUR_2 = "#deb887";
        base.SELECTED_BLOCK_COLOUR = "#ffff00";
        base.SELECTED_BLOCK_COLOUR_1 = "#ffe64f";
        base.SELECTED_BLOCK_COLOUR_2 = "#e8c524";

        base.AVAILABLE_BLOCK_COLOUR = "#00ff7f";
        base.AVAILABLE_BLOCK_COLOUR_1 = "#7fe74f";
        base.AVAILABLE_BLOCK_COLOUR_2 = "#68c523";
        base.selectedPiece = undefined;
        base.availablePositions = undefined;
        base.pieces_transformable = new Array();
        base.playerTurnColor = "white";
        base.history = undefined;
        base.letter_array = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
        base.draw();
    },
    resetChessGame:function () {
        var base = this;
        $.ajax({
            url:"/entry.php/?delete_chess_game",
            type:"get",
            data:{
            },
            beforeSend:function () {
                $("#loader").show();
            },
            success:function () {
                $("#loader").hide();
                $(base.canvas).unbind("click");
                $("#resetGameButton").unbind("click");
                $("#moveBack").unbind("click");
                $("#moveForward").unbind("click");
                $("#showHistoryButton").unbind("click");
                base.initGame();
            }
        });
    },
    draw:function () {
        var base = this;
        base.canvas = document.getElementById('game_canvas');
        if (base.canvas.getContext) {
            base.ctx = base.canvas.getContext('2d');
            base.BLOCK_SIZE = base.canvas.height / 8;
            base.pieces = new Array();

            base.drawBoard();
            base.getPieces();
            base.getPlayerTurnColor();
            base.getHistory();
            base.initializeEvents();
            base.animate();
        }
        else {
            alert("Canvas not supported!");
        }
    },
    drawBoard:function () {
        var base = this;
        for (var iRow = 0; iRow < 8; iRow++) {
            for (var iBlock = 0; iBlock < 8; iBlock++) {
                base.ctx.fillStyle = base.getBlockColour(iRow, iBlock);
                base.ctx.fillRect(iRow * base.BLOCK_SIZE, iBlock * base.BLOCK_SIZE, base.BLOCK_SIZE, base.BLOCK_SIZE);
                base.ctx.stroke();
            }
        }
    },
    drawCase:function (x, y) {
        var base = this;
        base.ctx.fillStyle = base.getBlockColour(x, y);
        base.ctx.fillRect(x * base.BLOCK_SIZE, y * base.BLOCK_SIZE, base.BLOCK_SIZE, base.BLOCK_SIZE);
        base.ctx.stroke();
    },
    drawCaseWithColor:function (x, y, color) {
        var base = this;
        base.ctx.fillStyle = color;
        base.ctx.fillRect(x * base.BLOCK_SIZE, y * base.BLOCK_SIZE, base.BLOCK_SIZE, base.BLOCK_SIZE);
        base.ctx.stroke();
    },
    drawAvailableCase:function () {
        var base = this;
        if (base.availablePositions != undefined) {
            for (var k in base.availablePositions) {
                var posX = base.availablePositions[k].posX - 1;
                var posY = 8 - base.availablePositions[k].posY;
                base.ctx.fillStyle = base.getBlockColourAvailable(posX, posY);
                base.ctx.fillRect(posX * base.BLOCK_SIZE, posY * base.BLOCK_SIZE, base.BLOCK_SIZE, base.BLOCK_SIZE);
                base.ctx.stroke();
            }
        }
    },
    getBlockColour:function (iRow, iBlock) {
        var base = this;
        var cStartColour;
        if (iRow % 2)
            cStartColour = (iBlock % 2 ? base.BLOCK_COLOUR_1 : base.BLOCK_COLOUR_2);
        else
            cStartColour = (iBlock % 2 ? base.BLOCK_COLOUR_2 : base.BLOCK_COLOUR_1);

        return cStartColour;
    },
    getBlockColourSelected:function (iRow, iBlock) {
        var base = this;
        var cStartColour;
        if (iRow % 2)
            cStartColour = (iBlock % 2 ? base.SELECTED_BLOCK_COLOUR_1 : base.SELECTED_BLOCK_COLOUR_2);
        else
            cStartColour = (iBlock % 2 ? base.SELECTED_BLOCK_COLOUR_2 : base.SELECTED_BLOCK_COLOUR_1);

        return cStartColour;
    },
    getBlockColourAvailable:function (iRow, iBlock) {
        var base = this;
        var cStartColour;
        if (iRow % 2)
            cStartColour = (iBlock % 2 ? base.AVAILABLE_BLOCK_COLOUR_1 : base.AVAILABLE_BLOCK_COLOUR_2);
        else
            cStartColour = (iBlock % 2 ? base.AVAILABLE_BLOCK_COLOUR_2 : base.AVAILABLE_BLOCK_COLOUR_1);

        return cStartColour;
    },
    animate:function () {
        var base = this;
        base.ctx.clearRect(0, 0, base.canvas.width, base.canvas.height);
        base.drawBoard();
        base.drawCurrentSelectedPiece();
        base.drawAvailableCase();
        base.drawPieces();

        // request new frame
        requestAnimFrame(function () {
            base.animate();
        });
    },
    getPieces:function () {
        var base = this;
        $.ajax({
            url:"/entry.php/?showPieces",
            type:"get",
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.pieces = new Array();
                $(data).each(function () {
                    base.pieces.push(this);
                });
                base.getPiecesThatCanTransform();
                $("#loader").hide();
            }
        });
    },
    getPlayerTurnColor:function () {
        var base = this;
        $.ajax({
            url:"/entry.php/?showPlayerTurnColor",
            type:"get",
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.playerTurnColor = data;
                base.displayPlayerTurn();
                $("#loader").hide();
            }
        });
    },
    getHistory:function () {
        var base = this;
        $.ajax({
            url:"/entry.php/?showHistory",
            type:"get",
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.history = data;
                base.displayGameHistory();
                $("#loader").hide();
            }
        });
    },
    moveBack:function () {
        var base = this;
        $.ajax({
            url:"/entry.php/?moveBack",
            type:"get",
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.getPieces();
                base.getPlayerTurnColor();
                base.getHistory();
                $("#loader").hide();
            }
        });
    },
    getPiecesThatCanTransform:function () {
        var base = this;
        $.ajax({
            url:"/entry.php/?getPiecesThatCanTransform",
            type:"get",
            data:{
                color:base.playerTurnColor
            },
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.pieces_transformable = new Array();
                $(data).each(function () {
                    base.pieces_transformable.push(this);
                });
                base.dialogPiecesTransformation();
                $("#loader").hide();
            }
        });
    },
    dialogPiecesTransformation:function () {
        var base = this;
        for (var k in base.pieces_transformable) {
            if (base.pieces_transformable[k].color == base.playerTurnColor && base.pieces_transformable[k].type == "pawn") {
                if (confirm("Press \"yes\" if you want to transform the pawn (" + base.letter_array[base.pieces_transformable[k].posX - 1]
                    + "," + base.pieces_transformable[k].posY + ") into a queen. Press \"no\" if you want to transform it into a knight.")) {
                    base.transformPiece(base.pieces_transformable[k].posX, base.pieces_transformable[k].posY, "queen");
                }
                else {
                    base.transformPiece(base.pieces_transformable[k].posX, base.pieces_transformable[k].posY, "knight");
                }
            }
        }
        base.getPlayerTurnColor();
        base.getHistory();
    },
    transformPiece:function (posX, posY, type) {
        var base = this;
        $.ajax({
            url:"/entry.php/?transformPiece",
            type:"get",
            data:{
                posX:parseInt(posX),
                posY:parseInt(posY),
                type:type,
                color:base.playerTurnColor
            },
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.getPieces();
                base.getPlayerTurnColor();
                base.getHistory();
                $("#loader").hide();
            }
        });
    },
    moveTo:function (posX, posY, destX, destY) {
        var base = this;
        $.ajax({
            url:"/entry.php/?moveTo",
            type:"get",
            data:{
                posX:parseInt(posX),
                posY:parseInt(posY),
                destX:parseInt(destX),
                destY:parseInt(destY)
            },
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.selectedPiece = undefined;
                base.availablePositions = new Array();
                base.getPieces();
                $("#loader").hide();
            }
        });
    },
    displayPlayerTurn:function () {
        var base = this;
        var playerTurn;
        if (base.playerTurnColor == "white")
            playerTurn = "☺ ";
        else
            playerTurn = "☻ ";
        $("#game_info").html(playerTurn + base.playerTurnColor + " player turn. </br> ");
    },
    displayGameHistory:function () {
        var base = this;

        if (base.history != undefined) {
            var str = "";
            var last_str = "";
            for (var k in base.history) {
                var historyMove = base.history[k];

                var pos1 = historyMove[0];
                var piece1 = pos1.piece;
                var pos2 = historyMove[1];
                var piece2 = pos2.piece;
                var playerTurn;
                if (piece1.color == "white")
                    playerTurn = "☺ ";
                else
                    playerTurn = "☻ ";
                last_str = playerTurn + piece1.color + " " + piece1.type + " : (" + base.letter_array[pos1.posX - 1] + "," + pos1.posY + ") ► (" + base.letter_array[pos2.posX - 1] + "," + pos2.posY + ") </br>";
                str = last_str + str;
            }
            $("#historyBoard").html(str);
            if (last_str != "")
                $("#last_move").html("The last move was " + last_str);
            else
                $("#last_move").html(" ");
            if (($("#showHistoryButton").html() == "" || $("#showHistoryButton").html() == " ")
                && str != "")
                $("#showHistoryButton").html("hide ↑");
            if (str == "")
                $("#showHistoryButton").html(" ");
        }
    },
    drawPieces:function () {
        var base = this;
        for (var k in base.pieces) {
            base.drawPiece(base.pieces[k]);
        }
    },
    drawPiece:function (piece) {
        var base = this;
        var piece_img = base.getPieceImg(piece);
        base.ctx.drawImage(piece_img,
            (piece.posX - 1) * base.BLOCK_SIZE, (8 - piece.posY) * base.BLOCK_SIZE,
            base.BLOCK_SIZE, base.BLOCK_SIZE);
    },
    drawCurrentSelectedPiece:function () {
        var base = this;
        if (base.selectedPiece != undefined) {
            base.drawCaseWithColor(base.selectedPiece.posX - 1, 8 - base.selectedPiece.posY, base.getBlockColourSelected(base.selectedPiece.posX - 1, 8 - base.selectedPiece.posY));
        }
    },
    clearPiece:function (piece) {
        var base = this;
        base.ctx.clearRect((piece.posX - 1) * base.BLOCK_SIZE, (8 - piece.posY) * base.BLOCK_SIZE,
            base.BLOCK_SIZE, base.BLOCK_SIZE);
    },
    getPieceImg:function (raw_piece) {
        var base = this;
        var piece = new Image();
//        piece.src = 'images/close.png';

        if (raw_piece.color == "white") {
            if (raw_piece.type == "pawn") {
                piece.src = 'images/wp.gif';
            }
            if (raw_piece.type == "rook") {
                piece.src = 'images/wr.gif';
            }
            if (raw_piece.type == "knight") {
                piece.src = 'images/wn.gif';
            }
            if (raw_piece.type == "bishop") {
                piece.src = 'images/wb.gif';
            }
            if (raw_piece.type == "queen") {
                piece.src = 'images/wq.gif';
            }
            if (raw_piece.type == "king") {
                piece.src = 'images/wk.gif';
            }
        }
        else {
            if (raw_piece.type == "pawn") {
                piece.src = 'images/bp.gif';
            }
            if (raw_piece.type == "rook") {
                piece.src = 'images/br.gif';
            }
            if (raw_piece.type == "knight") {
                piece.src = 'images/bn.gif';
            }
            if (raw_piece.type == "bishop") {
                piece.src = 'images/bb.gif';
            }
            if (raw_piece.type == "queen") {
                piece.src = 'images/bq.gif';
            }
            if (raw_piece.type == "king") {
                piece.src = 'images/bk.gif';
            }
        }

        return piece;
    },
    initializeEvents:function () {
        var base = this;

        $(base.canvas).click(function () {
            var mouseX = event.pageX - event.srcElement.offsetLeft;
            var mouseY = event.pageY - event.srcElement.offsetTop;
            var block_x = mouseX / base.BLOCK_SIZE;
            var block_y = mouseY / base.BLOCK_SIZE;
            var piece_x = Math.floor(block_x) + 1;
            var piece_y = 8 - Math.floor(block_y);

          //  console.log("-----------------");
            var newSelectedPiece = false;
            for (var k in base.pieces) {
                if (base.pieces[k].posX == piece_x && base.pieces[k].posY == piece_y) {
                    if (base.pieces[k].color == base.playerTurnColor) {
                        base.selectedPiece = base.pieces[k];
                        newSelectedPiece = true;

                        $.ajax({
                            url:"/entry.php/?getPosFor",
                            type:"get",
                            data:{
                                posX:parseInt(base.selectedPiece.posX),
                                posY:parseInt(base.selectedPiece.posY)
                            },
                            beforeSend:function () {
                                $("#loader").show();
                            },
                            success:function (data, status) {
                                base.availablePositions = new Array();
                                $(data).each(function () {
                                    base.availablePositions.push(this);
                                });
                                $("#loader").hide();
                            }
                        });
                    }
                }
            }

            if (base.selectedPiece != undefined && !newSelectedPiece) {
//                console.log("moveTo base.selectedPiece.posX", base.selectedPiece.posX);
//                console.log("moveTo base.selectedPiece.posY", base.selectedPiece.posY);
//                console.log("piece_x", piece_x);
//                console.log("piece_y", piece_y);
                base.moveTo(base.selectedPiece.posX, base.selectedPiece.posY, piece_x, piece_y);
            }
        });

        $("#resetGameButton").click(function () {
            if (confirm("Do you really want to start a new game ?")) {
                base.resetChessGame();
            }
        });
        $("#moveBack").click(function () {
            base.moveBack();
        });
        $("#showHistoryButton").click(function () {
            if ($("#historyBoard").css("display") == "none") {
                $("#historyBoard").show(200);
                $("#showHistoryButton").html("hide ↑");
            }
            else {
                $("#historyBoard").hide(200);
                $("#showHistoryButton").html("show ↓");
            }
        });
    }
}