<?php

namespace Zelenin\Elo;

class Player
{
    /** @var float */
    private $rating;

    /**
     * @param float $rating
     */
    public function __construct($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @param float $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }
}
