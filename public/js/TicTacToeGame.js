(function () {

    const canvas = document.createElement('canvas');
    const $MESSAGE_AREA = $('#message_area');
    const LINE_COLOR = 'brown';
    const FONT_SIZE = 100;

    canvas.width = 480;
    canvas.height = 480;
    canvas.style.display = 'block';
    canvas.style.margin = '100px auto';
    document.querySelector('#game').appendChild(canvas);

    const SECTION_SIZE = canvas.width / 3;
    const CTX = canvas.getContext('2d');
    CTX.translate(0.5, 0.5);

    let frizzMove = false;
    let computerMove = false;
    let boardState;

    function drawLines(lineWidth, strokeStyle) {
        let lineStart = 4;
        let lineLength = canvas.width - 5;
        CTX.lineWidth = lineWidth;
        CTX.lineCap = 'round';
        CTX.strokeStyle = strokeStyle;
        CTX.beginPath();

        for (let y = 1; y <= 2; y++) {
            CTX.moveTo(lineStart, y * SECTION_SIZE);
            CTX.lineTo(lineLength, y * SECTION_SIZE);
        }

        for (let x = 1; x <= 2; x++) {
            CTX.moveTo(x * SECTION_SIZE, lineStart);
            CTX.lineTo(x * SECTION_SIZE, lineLength);
        }

        CTX.stroke();
    }

    function drawState(board) {
        let text;
        for (let x = 0; x < board.length; x++) {
            for (let y = 0; y < board[x].length; y++) {
                text = board[y][x];
                drawText(text, resolveXDrawPosition(x), resolveYDrawPosition(y));
            }
        }
    }

    function drawText(text, posX, posY) {
        CTX.font = FONT_SIZE + "px Georgia";
        CTX.fillStyle = "gray";
        CTX.textAlign = 'left';
        CTX.fillText(text.toUpperCase(), posX, posY);
    }

    drawLines(10, LINE_COLOR);
    $.get('/board', {}, function (response) {
        boardState = response;
        drawState(boardState);
    }, 'json');

    canvas.addEventListener('click', function (e) {
        const xCord = e.pageX - canvas.offsetLeft;
        const yCord = e.pageY - canvas.offsetTop;
        let unit = 'X';

        for (let i = 0; i < boardState.length; i++) {
            for (let j = 0; j < boardState.length; j++) {
                if (
                    xCord >= i * SECTION_SIZE &&
                    xCord <= (i * SECTION_SIZE) + SECTION_SIZE &&
                    yCord >= j * SECTION_SIZE &&
                    yCord <= (j * SECTION_SIZE) + SECTION_SIZE
                ) {
                    if (boardState[i][j] === '' && !frizzMove) {
                        unit = !computerMove ? 'X' : 'O';
                        drawText(
                            unit,
                            resolveXDrawPosition(i),
                            resolveYDrawPosition(j)
                        );

                        boardState[i][j] = unit;

                        makeMove(unit).then((response) => {

                            if (response.length === 1) {
                                $MESSAGE_AREA.text(response[0]);
                            } else {
                                frizzMove = false;
                                let x = response[0],
                                    y = response[1],
                                    text = response[2];
                                drawText(text, resolveXDrawPosition(x), resolveYDrawPosition(y));
                                boardState[x][y] = text.toUpperCase();

                                if(response.length === 4) {
                                    $MESSAGE_AREA.text(response[3]);
                                }
                            }
                        });
                    }
                }
            }
        }
    });

    /**
     *
     * @param unit
     * @returns {*}
     */
    function makeMove(unit) {
        frizzMove = true;
        return $.post('/move', {
            boardState: boardState,
            unit: unit
        });
    }

    /**
     *
     * @param {number} x
     * @returns {number}
     */
    function resolveXDrawPosition(x) {
        return ((x + 1) * SECTION_SIZE / 2) - (FONT_SIZE / boardState.length) + (x * (FONT_SIZE - (FONT_SIZE / 4)))
    }

    /**
     *
     * @param {number} y
     * @returns {number}
     */
    function resolveYDrawPosition(y) {
        return ((y + 1) * SECTION_SIZE / 2) + (FONT_SIZE / boardState.length) + (y * (FONT_SIZE - (FONT_SIZE / 4)));
    }

})();