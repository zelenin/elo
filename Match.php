<?php

namespace Zelenin\Elo;

use Closure;

class Match
{
    /** @var Player */
    private $player1;
    /** @var Player */
    private $player2;

    /** @var float */
    private $score1;
    /** @var float */
    private $score2;

    /** @var float */
    private $k;
    /** @var Closure */
    private $goalIndexHandler;
    /** @var Closure */
    private $homeCorrectionHandler;
    /** @var int */
    private $home;

    const WIN = 1;
    const DRAW = 0.5;
    const LOSS = 0;

    /**
     * @param Player $player1
     * @param Player $player2
     */
    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    /**
     * @param float $score1
     * @param float $score2
     * @return $this
     */
    public function setScore($score1, $score2)
    {
        $this->score1 = $score1;
        $this->score2 = $score2;
        return $this;
    }

    /**
     * @param float $k
     * @return $this
     */
    public function setK($k)
    {
        $this->k = $k;
        return $this;
    }

    public function count()
    {
        $rating1 = $this->player1->getRating() + $this->k * $this->getGoalIndex() * ($this->getMatchScore() - $this->getExpectedScore());
        $rating2 = $this->player1->getRating() + $this->player2->getRating() - $rating1;
        $this->player1->setRating($rating1);
        $this->player2->setRating($rating2);
    }

    /**
     * @return Player
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * @return Player
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * @return float
     */
    private function getMatchScore()
    {
        $diff = $this->score1 - $this->score2;
        if ($diff < 0) {
            return static::LOSS;
        } elseif ($diff > 0) {
            return static::WIN;
        }
        return static::DRAW;
    }

    /**
     * @return float
     */
    private function getExpectedScore()
    {
        $this->getHomeCorrection();
        $diff = $this->player2->getRating() - $this->player1->getRating();
        return 1 / (1 + pow(10, ($diff / 400)));
    }

    /**
     * @param callable $handler
     * @return $this
     */
    public function setGoalIndexHandler(Closure $handler)
    {
        $this->goalIndexHandler = $handler;
        return $this;
    }

    /**
     * @return float
     */
    private function getGoalIndex()
    {
        if (is_callable($this->goalIndexHandler)) {
            return call_user_func($this->goalIndexHandler, $this->score1, $this->score2);
        }
        return 1;
    }

    /**
     * @param callable $handler
     * @return $this
     */
    public function setHomeCorrectionHandler(Closure $handler)
    {
        $this->homeCorrectionHandler = $handler;
        return $this;
    }

    /**
     * @return float
     */
    private function getHomeCorrection()
    {
        if (is_callable($this->homeCorrectionHandler)) {
            return call_user_func($this->homeCorrectionHandler, $this->home, $this->player1, $this->player2);
        }
        return 0;
    }

    /**
     * @param int $player
     * @return $this
     */
    public function setHome($player)
    {
        $this->home = $player;
        return $this;
    }
}
