<?php

namespace App\Services;

/**
 * Class MoveService
 * @package App\Services
 */
class MoveService implements MoveInterface
{
    const PLAYER_UNIT = 'X';
    const BOT_UNIT = 'O';
    const WIN_MESSAGE = 'The winner is %s';
    const STALEMATE_MESSAGE = 'Stalemate';

    protected $winner = false;
    protected $bestMove = [];
    protected $stalemate = false;

    /**
     * @param array $boardState
     * @param string $playerUnit
     * @return array
     */
    public function makeMove(array $boardState, string $playerUnit = self::PLAYER_UNIT): array
    {
        $length = count($boardState);
        $this->checkBoardState($boardState, $length);
        $unit = ucfirst($playerUnit) === self::PLAYER_UNIT ? self::BOT_UNIT : self::PLAYER_UNIT;

        if ($this->winner !== false) {
            return [sprintf(self::WIN_MESSAGE, $this->winner)];
        }

        if ($this->stalemate) {
            return [self::STALEMATE_MESSAGE];
        }

        [$i, $j] = $this->getNextCoordinates($length - 1);
        while (isset($boardState[$i][$j]) && !empty($boardState[$i][$j])) {
            [$i, $j] = $this->getNextCoordinates($length - 1);
        }

        $boardState[$i][$j] = $unit;
        $result = [$i, $j, $unit];

        $this->checkBoardState($boardState, $length);
        if ($this->winner !== false) {
            $result[] = sprintf(self::WIN_MESSAGE, $this->winner);
        }

        return $result;
    }

    /**
     * @param int $max
     * @return array
     */
    protected function getNextCoordinates(int $max): array
    {
        return [mt_rand(0, $max), mt_rand(0, $max)];
    }

    /**
     * @param array $boardState
     * @param int|null $length
     */
    protected function checkBoardState(array $boardState, int $length = null)
    {
        if (null === $length) {
            $length = count($boardState);
        }
        $this->stalemate = true;

        $diagonal = [];
        $inverseDiagonal = [];
        for ($i = 0; $i < $length; $i++) {
            if ($this->checkLine($boardState[$i])) {
                return;
            }

            $line = [];
            for ($j = 0; $j < $length; $j++) {
                $line[] = $boardState[$j][$i];

                if ($i == $j) {
                    $diagonal[] = $boardState[$j][$i];
                }

                if (($i + $j) == ($length - 1)) {
                    $inverseDiagonal[] = $boardState[$i][$j];
                }

                if (empty($boardState[$i][$j])) {
                    $this->stalemate = false;
                }
            }
            if ($this->checkLine($line)) {
                return;
            }
        }

        $this->checkLine($inverseDiagonal);
        $this->checkLine($diagonal);
    }

    /**
     * @param array $line
     * @return bool
     */
    protected function checkLine(array $line): bool
    {
        $length = count($line);
        $unit = $line[0];

        if (empty($unit)) {
            return false;
        }

        for ($i = 1; $i < $length; $i++) {
            if ($unit !== $line[$i]) {
                return false;
            }
        }

        $this->winner = $unit;
        return true;
    }
}