<?php

namespace App\Services;

/**
 * Class MoveService
 * @package App\Services
 */
class MoveService implements MoveInterface
{
    const PLAYER_UNIT       = 'X';
    const BOT_UNIT          = 'O';
    const WIN_MESSAGE       = 'The winner is %s';
    const STALEMATE_MESSAGE = 'Stalemate';

    protected const STATE_WIN       = 1;
    protected const STATE_DRAW      = 2;
    protected const STATE_CONTINUE  = 3;

    protected $state        = self::STATE_CONTINUE;
    protected $winner       = false;

    /**
     * @param array $boardState
     * @param string $playerUnit
     * @return array
     */
    public function makeMove(array $boardState, string $playerUnit = self::PLAYER_UNIT): array
    {
        $length = count($boardState);
        $unit = $this->getUnit($playerUnit);

        $this->checkBoardState($boardState, $length);

        if($this->state === self::STATE_WIN) {
            return [sprintf(self::WIN_MESSAGE, $this->winner)];
        }

        if ($this->state === self::STATE_DRAW) {
            return [self::STALEMATE_MESSAGE];
        }

        [$i, $j] = $this->getNextCoordinates(new \ArrayObject($boardState), 0, $unit);

        $boardState[$i][$j] = $unit;
        $result = [$i, $j, $unit];

        $this->checkBoardState($boardState, $length);
        if ($this->state === self::STATE_WIN) {
            $result[] = sprintf(self::WIN_MESSAGE, $this->winner);
        }

        return $result;
    }

    /**
     * @param \ArrayObject $boardState
     * @param int $depth
     * @param string $unit
     * @return int
     */
    protected function getNextCoordinates(\ArrayObject $boardState, int $depth, string $unit)
    {
        $this->checkBoardState($boardState, $boardState->count());

        if($this->state === self::STATE_CONTINUE) {
            $length = $boardState->count();
            $states = [];
            for ($i = 0; $i < $length; $i++)
            {
                for ($j = 0; $j < $length; $j++)
                {
                    $stateCopy = clone $boardState;
                    if (!empty($stateCopy[$i][$j])) {
                        continue;
                    }
                    $stateCopy[$i][$j] = $unit;
                    $states[] = [
                        'value' => $this->getNextCoordinates($stateCopy, ($depth + 1), $this->getUnit($unit)),
                        'cords' => [$i, $j]
                    ];
                }
            }

            if(!empty($states))

                if ($unit === self::BOT_UNIT) {
                    $max = $this->getMaxBy($states, 'value');
                    if ($depth === 0) {
                        return $max['cords'];
                    }
                    return $max['value'];
                } else {
                    $min = $this->getMinBy($states, 'value');
                    if ($depth === 0) {
                        return $min['cords'];
                    }
                    return $min['value'];
                }

        } elseif ($this->state === self::STATE_DRAW) {
            return 0;
        } elseif ($this->state === self::STATE_WIN) {
            return $this->winner === self::PLAYER_UNIT ? $depth - 10 : 10 - $depth;
        }
    }

    /**
     * @param array|\ArrayObject $boardState
     * @param int|null $length
     */
    protected function checkBoardState($boardState, int $length = null)
    {
        if (null === $length) {
            $length = count($boardState);
        }
        $this->state = self::STATE_DRAW;

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
                    $this->state = self::STATE_CONTINUE;
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

        $this->state = self::STATE_WIN;
        $this->winner = $unit;
        return true;
    }

    /**
     * @param string $unit
     * @return string
     */
    protected function getUnit(string $unit): string
    {
        return ucfirst($unit) === self::PLAYER_UNIT ? self::BOT_UNIT : self::PLAYER_UNIT;
    }

    /**
     * @param array $data
     * @param string $key
     * @return array
     */
    protected function getMaxBy(array $data, string $key): array
    {
        $max = $data[0];
        foreach ($data as $i => $value)
        {
            if($value[$key] > $max[$key]) {
                $max = $value;
            }
        }

        return $max;
    }

    /**
     * @param array $data
     * @param string $key
     * @return array
     */
    protected function getMinBy(array $data, string $key): array
    {
        $min = $data[0];
        foreach ($data as $i => $value)
        {
            if($value[$key] < $min[$key]) {
                $min = $value;
            }
        }

        return $min;
    }
}