# Elo

A PHP implementation of [Elo rating system](http://en.wikipedia.org/wiki/Elo_rating_system)

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require zelenin/elo "dev-master"
```

or add

```
"zelenin/elo": "dev-master"
```

to the require section of your ```composer.json```

## Usage

Create two players with current ratings:

```php
use Zelenin\Elo\Player;

$player1 = new Player(1200);
$player2 = new Player(800);
```

Create match:

```php
use Zelenin\Elo\Match;

$match = new Match($player1, $player2);
$match->setScore(1, 0)
    ->setK(32)
    ->count();
```

Get players:

```php
$player1 = $match->getPlayer1();
$player2 = $match->getPlayer2();
```

Get new ratings:

```php
$newRating1 = $player1->getRating();
$newRating2 = $player2->getRating();
```

### Advanced usage

If you want use this library for not-traditional for Elo sports like football, ice hockey, basketball, you may set additional handlers for setting goal index and home correction.

```php
use Zelenin\Elo\Match;
use Zelenin\Elo\Player;

$player1 = new Player(1200);
$player2 = new Player(800);

$goalIndexHandler = function ($score1, $score2) {
    $diff = abs($score1 - $score2);
    if ($diff > 0) {
        return sqrt($diff);
    }
    return 1;
};

$homeCorrectionHandler = function ($home, $diff) {
    $coefficient = 100;
    if ($home == 1) {
        return $diff - $coefficient;
    }
    if ($home == 2) {
        return $diff + $coefficient;
    }
    return $diff;
};

$match = new Match($player1, $player2);
$match->setScore(1, 0)
    ->setK(32)
    ->setGoalIndexHandler($goalIndexHandler)
    ->setHome(2)
    ->setHomeCorrectionHandler($homeCorrectionHandler)
    ->count();

$newRating1 = $player1->getRating();
$newRating2 = $player2->getRating();
```

## Info

See Wiki about [Elo rating system](http://en.wikipedia.org/wiki/Elo_rating_system)

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
