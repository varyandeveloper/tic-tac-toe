<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('EMPTY_BOARD', [
    ['','',''],
    ['','',''],
    ['','','']
]);

define('HORIZONTAL_WIN_BARD', [
    ['X','X','X'],
    ['','0',''],
    ['O','O','X']
]);

define('VERTICAL_WIN_BARD', [
    ['X','X','X'],
    ['O','X','O'],
    ['O','X','O']
]);

define('DIAGONAL_WIN_BOARD', [
    ['X','','O'],
    ['','X',''],
    ['','O','X']
]);

define('DIAGONAL_INVERSE_WIN_BOARD', [
    ['X','','O'],
    ['X','O',''],
    ['O','O','X']
]);

define('STALEMATE_BOARD', [
    ['O','X','X'],
    ['X','X','O'],
    ['O','O','X']
]);

define('RAND_STATE_1', [
    ['O','X','X'],
    ['X','',''],
    ['X','O','O']
]);
