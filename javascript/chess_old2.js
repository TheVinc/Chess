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
            url:"/Chess/entry.php/?initialize",
            type:"get",
            data:{
            },
            beforeSend:function () {
                $("#game_info").html("Initializing game...");
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
        base.AVAILABLE_BLOCK_COLOUR = "#00ff7f";
        base.selectedPiece = undefined;
        base.playerTurnColor = "white";
        base.draw();
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

//            base.pieces_image = new Array();
//            for (var k in base.pieces) {
//                base.pieces_image.push(base.getFullPieceImg(base.pieces[k]))
//            }

            base.initializeEvents();
            $("#game_info").html("Game ready !");
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
        for (var k in base.availablePositions) {
            var posX = base.availablePositions[k].posX - 1;
            var posY = 8 - base.availablePositions[k].posY;
            base.ctx.fillStyle = base.AVAILABLE_BLOCK_COLOUR;
            base.ctx.fillRect(posX * base.BLOCK_SIZE, posY * base.BLOCK_SIZE, base.BLOCK_SIZE, base.BLOCK_SIZE);
            base.ctx.stroke();
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
    animate:function () {
        var base = this;
        base.ctx.clearRect(0, 0, base.canvas.width, base.canvas.height);
        base.drawBoard();
        base.drawCurrentSelectedPiece();
        base.drawAvailableCase();
        base.drawPieces();
//        base.drawPieceImg();

        // request new frame
        requestAnimFrame(function () {
            base.animate();
        });
    },
    getPieces:function () {
        var base = this;
        $.ajax({
            url:"/Chess/entry.php/?showPieces",
            type:"get",
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.pieces = new Array();
                $(data).each(function () {
                    base.pieces.push(this);
                });
                $("#loader").hide();
            }
        });
    },
    getPlayerTurnColor:function () {
        var base = this;
        $.ajax({
            url:"/Chess/entry.php/?showPlayerTurnColor",
            type:"get",
            beforeSend:function () {
                $("#loader").show();
            },
            success:function (data, status) {
                base.playerTurnColor = data;
                console.log(base.playerTurnColor);
                $("#loader").hide();
            }
        });
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
            base.drawCaseWithColor(base.selectedPiece.posX - 1, 8 - base.selectedPiece.posY, base.SELECTED_BLOCK_COLOUR);
        }
    },
    clearPiece:function (piece) {
        var base = this;
        base.ctx.clearRect((piece.posX - 1) * base.BLOCK_SIZE, (8 - piece.posY) * base.BLOCK_SIZE,
            base.BLOCK_SIZE, base.BLOCK_SIZE);
    },
    drawPiecesImg:function () {
        var base = this;
        for (var k in base.pieces_image) {
            base.drawPieceImg(base.pieces_image[k]);
        }
    },
    drawPieceImg:function (piece_img) {
        var base = this;
        base.ctx.drawImage(piece_img,
            (piece_img.posX - 1) * base.BLOCK_SIZE, (8 - piece_img.posY) * base.BLOCK_SIZE,
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
    getFullPieceImg:function (raw_piece) {
        var base = this;
        var piece = new Image();
        piece.src = 'images/close.png';
        piece.posX = raw_piece.posX;
        piece.poxY = raw_piece.posY;
        piece.color = raw_piece.color;
        piece.type = raw_piece.type;

        if (piece.color == "white") {
            if (piece.type == "pawn") {
                piece.src = 'images/wp.gif';
            }
            if (piece.type == "rook") {
                piece.src = 'images/wr.gif';
            }
            if (piece.type == "knight") {
                piece.src = 'images/wn.gif';
            }
            if (piece.type == "bishop") {
                piece.src = 'images/wb.gif';
            }
            if (piece.type == "queen") {
                piece.src = 'images/wq.gif';
            }
            if (piece.type == "king") {
                piece.src = 'images/wk.gif';
            }
        }
        else {
            if (piece.type == "pawn") {
                piece.src = 'images/bp.gif';
            }
            if (piece.type == "rook") {
                piece.src = 'images/br.gif';
            }
            if (piece.type == "knight") {
                piece.src = 'images/bn.gif';
            }
            if (piece.type == "bishop") {
                piece.src = 'images/bb.gif';
            }
            if (piece.type == "queen") {
                piece.src = 'images/bq.gif';
            }
            if (piece.type == "king") {
                piece.src = 'images/bk.gif';
            }
        }

        return piece;
    },
    moveTo:function (posX, posY, destX, destY) {
        var base = this;
        $.ajax({
            url:"/Chess/entry.php/?moveTo",
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
                $("#loader").hide();
                console.log("success move to");

                base.selectedPiece = undefined;
                base.getPieces();
                base.getPlayerTurnColor();
                base.availablePositions = new Array();
                $("#last_move").html("("+posX+","+posY+") --> ("+destX+","+destY+")");
            }
        });
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

            console.log("-----------------");
            var newSelectedPiece = false;
            for (var k in base.pieces) {
                if (base.pieces[k].posX == piece_x && base.pieces[k].posY == piece_y) {
                    if (base.pieces[k].color == base.playerTurnColor) {
                        base.selectedPieceOld = base.selectedPiece;
                        base.selectedPiece = base.pieces[k];
                        newSelectedPiece = true;

                        $.ajax({
                            url:"/Chess/entry.php/?getPosFor",
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
                                $("#game_info").html(base.playerTurnColor + " player turn :  "
                                    + "piece selected :  "
                                    + "x = " + base.selectedPiece.posX + ";  "
                                    + "y = " + base.selectedPiece.posY + "; ");
                                $("#loader").hide();
                            }
                        });
                    }
                }
            }

            if (base.selectedPiece != undefined && !newSelectedPiece) {
                console.log("moveTo base.selectedPiece.posX", base.selectedPiece.posX);
                console.log("moveTo base.selectedPiece.posY", base.selectedPiece.posY);
                console.log("piece_x", piece_x);
                console.log("piece_y", piece_y);
                base.moveTo(base.selectedPiece.posX, base.selectedPiece.posY, piece_x, piece_y);
            }
        });
    }
}