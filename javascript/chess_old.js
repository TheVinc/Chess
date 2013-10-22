var BLOCK_COLOUR_1 = "#d2691e";
var BLOCK_COLOUR_2 = "#deb887";
var CHESS = {
    init:function () {

    },
    draw:function () {
        canvas = document.getElementById('game_canvas');
        if (canvas.getContext) {
            ctx = canvas.getContext('2d');

            BLOCK_SIZE = canvas.height / 8;

            drawBoard();
            get_pieces();
            canvas.addEventListener('click', board_click, false);
        }
        else {
            alert("Canvas not supported!");
        }
    },
    animate:function () {

    }
}

function draw() {
    canvas = document.getElementById('game_canvas');
    if (canvas.getContext) {
        ctx = canvas.getContext('2d');

        BLOCK_SIZE = canvas.height / 8;

        drawBoard();
        get_pieces();
        canvas.addEventListener('click', board_click, false);
    }
    else {
        alert("Canvas not supported!");
    }
}

$(document).ready(function () {
    img_pieces = new Array();
    init_game();

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

    function animate() {
//        var canvas = document.getElementById('myCanvas');
//        var context = canvas.getContext('2d');
//
//        // update
//
//        // clear
//        context.clearRect(0, 0, canvas.width, canvas.height);

        // draw stuff
        draw_pieces(img_pieces);
        // request new frame
        requestAnimFrame(function () {
            animate();
        });
    }

    animate();

//    $("#showPieces_button").click(function () {
//        get_pieces();
//    });
});

function draw_pieces(imgs_pieces) {
    for (var k in imgs_pieces) {
        console.log("k", k);
        drawPiece(k);
    }
}

function board_click(event) {
    var mouse_click_x = event.pageX;
    var mouse_click_y = event.pageY;

    console.log("canvas_LEFT", $(ctx).offsetLeft);
    console.log("canvas_TOP", $(ctx).offsetTop);

    console.log("mouse_click_x", event.pageX - $(ctx).offsetLeft);
    console.log("mouse_click_y", event.pageY - $(ctx).offsetTop);
//    var block_x = BLOCK_SIZE / event.pageX;
//    var block_y = BLOCK_SIZE / event.pageY;
//    console.log("img pieces", img_pieces);
//    console.log("block_x", block_x);
//    console.log("block_y", block_y);
//    for (var k in img_pieces)
//    {
//
//    }
}

function get_pieces() {
    $.ajax({
        url:"/Chess/entry.php/?showPieces",
        type:"get",
        beforeSend:function () {
        },
        success:function (data, status) {
            $(data).each(function () {
                img_pieces.push(getImgPiece(this));
            });
        }
    });
}

function init_game() {
    $.ajax({
        url:"/Chess/entry.php/?initialize",
        type:"get",
        data:{
        },
        beforeSend:function () {
        },
        success:function (data, status) {
            $("#game_info").html("Initializing game.");
            draw();
        }
    });
}

function drawBoard() {
    for (var iRow = 0; iRow < 8; iRow++) {
        for (var iBlock = 0; iBlock < 8; iBlock++) {
            ctx.fillStyle = getBlockColour(iRow, iBlock);
            ctx.fillRect(iRow * BLOCK_SIZE, iBlock * BLOCK_SIZE, BLOCK_SIZE, BLOCK_SIZE);
            ctx.stroke();
        }
    }
}

function getBlockColour(iRow, iBlock) {
    var cStartColour;
    if (iRow % 2)
        cStartColour = (iBlock % 2 ? BLOCK_COLOUR_1 : BLOCK_COLOUR_2);
    else
        cStartColour = (iBlock % 2 ? BLOCK_COLOUR_2 : BLOCK_COLOUR_1);

    return cStartColour;
}

function getImgPiece(raw_piece) {

    var piece = new Image();
    piece.posX = raw_piece.posX;
    piece.posY = raw_piece.posY;
    piece.color = raw_piece.color;
    piece.type = raw_piece.type;
    piece.src = "";

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
}

function getFullPiece(raw_piece) {

    var piece = {
        posX:raw_piece.posX,
        posY:raw_piece.posY,
        color:raw_piece.color,
        type:raw_piece.type,
        src:""
    };

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
}

function drawPiece(piece) {
//    console.log("drawPiece", piece);
    ctx.drawImage(piece,
        (piece.posX - 1) * BLOCK_SIZE, (piece.posY - 1) * BLOCK_SIZE,
        BLOCK_SIZE, BLOCK_SIZE);
}