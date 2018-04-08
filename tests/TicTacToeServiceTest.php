<?php

use PHPUnit\Framework\TestCase;
use App\Services\{
    MoveService, MoveInterface
};

/**
 * Class TicTacToeServiceTest
 */
class TicTacToeServiceTest extends TestCase
{
    /**
     * @var MoveInterface
     */
    protected $moveService;

    /**
     * TicTacToeServiceTest constructor.
     * @param null|string $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->moveService = new MoveService();
    }

    public function testGetCorrectResponseForNextMove()
    {
        $boardSate = EMPTY_BOARD;
        $array = $this->moveService->makeMove($boardSate, MoveService::PLAYER_UNIT);

        $this->assertCount(3, $array);
        $this->assertArrayHasKey(0, $array);
        $this->assertArrayHasKey(1, $array);
        $this->assertEquals($array[2], MoveService::BOT_UNIT);
    }

    public function testStalemate()
    {
        $boardState = STALEMATE_BOARD;
        $response = $this->moveService->makeMove($boardState);

        $this->assertCount(1, $response);
        $this->assertEquals($response[0], MoveService::STALEMATE_MESSAGE);
    }

    public function testHorizontalWin()
    {
        $boardState = HORIZONTAL_WIN_BARD;
        $response = $this->moveService->makeMove($boardState);

        $this->assertCount(1, $response);
        $this->assertEquals($response[0], 'The winner is ' . MoveService::PLAYER_UNIT);
    }

    public function testVerticalWin()
    {
        $boardState = VERTICAL_WIN_BARD;
        $response = $this->moveService->makeMove($boardState);

        $this->assertCount(1, $response);
        $this->assertEquals($response[0], 'The winner is ' . MoveService::PLAYER_UNIT);
    }

    public function testDiagonalWin()
    {
        $boardState = DIAGONAL_WIN_BOARD;
        $response = $this->moveService->makeMove($boardState);

        $this->assertCount(1, $response);
        $this->assertEquals($response[0], 'The winner is ' . MoveService::PLAYER_UNIT);
    }

    public function testInverseDiagonalWin()
    {
        $boardState = DIAGONAL_INVERSE_WIN_BOARD;
        $response = $this->moveService->makeMove($boardState);

        $this->assertCount(1, $response);
        $this->assertEquals($response[0], 'The winner is ' . MoveService::BOT_UNIT);
    }

    public function testBestMove()
    {
        $state = EMPTY_BOARD;

        $state[1][2] = MoveService::PLAYER_UNIT;
        $res = $this->moveService->makeMove($state);
        $state[$res[0]][$res[1]] = $res[2];
        $this->assertEquals([0, 2, MoveService::BOT_UNIT], $res);

        $state[2][0] = MoveService::PLAYER_UNIT;
        $res = $this->moveService->makeMove($state);
        $state[$res[0]][$res[1]] = $res[2];
        $this->assertEquals([0, 0, MoveService::BOT_UNIT], $res);

        $state[0][1] = MoveService::PLAYER_UNIT;
        $res = $this->moveService->makeMove($state);
        $state[$res[0]][$res[1]] = $res[2];
        $this->assertEquals([1, 1, MoveService::BOT_UNIT], $res);

        $state[2][2] = MoveService::PLAYER_UNIT;
        $res = $this->moveService->makeMove($state);
        $state[$res[0]][$res[1]] = $res[2];
        $this->assertEquals([2, 1, MoveService::BOT_UNIT], $res);

        $state[1][0] = MoveService::PLAYER_UNIT;
        $this->assertEquals($this->moveService->makeMove($state), [MoveService::STALEMATE_MESSAGE]);
    }
}