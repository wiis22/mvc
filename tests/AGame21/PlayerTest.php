<?php

namespace App\AGame21;

use PHPUnit\Framework\TestCase;

use App\Card\Card;
use App\Card\DeckOfCardsGraphic;

/**
 * Test cases for class DeckOfCards.
 */
class PlayerTest extends TestCase
{
    /**
     * Test get players cards as a grapic version of the cards.
     * @return void
     */
    public function testGetPCards()
    {
        $player = new Player();
        $player->setCards([new Card("A", "♠"), new Card("2", "♠"), new Card("3", "♠")]);

        $res = $player->getPCardsAsString();
        $this->assertEquals(["🂡", "🂢", "🂣"], $res);
    }

    /**
     * test get player points.
     * @return void
     */
    public function testGetPoints()
    {
        $player = new Player();

        $player->setCards([new Card("A", "♠"), new Card("K", "♠"), new Card("2", "♠")]);
        $this->assertSame(16, $player->getPPoints());

        $player->setCards([new Card("J", "♠"), new Card("Q", "♠")]);
        $this->assertSame(23, $player->getPPoints());

        $player->setCards([new Card("A", "♠"), new Card("7", "♠")]);
        $this->assertSame(21, $player->getPPoints());

    }


    /**
     * test get player points.
     * @return void
     */
    public function testGetPointsBackJack()
    {
        $player = new Player();

        $player->setCards([new Card("A", "♠"), new Card("K", "♠")]);
        $this->assertSame(21, $player->getPPointsBlackJack());

        $player->setCards([new Card("J", "♠"), new Card("Q", "♠")]);
        $this->assertSame(20, $player->getPPointsBlackJack());

        $player->setCards([new Card("A", "♠"), new Card("7", "♠"), new Card("7", "♠")]);
        $this->assertSame(15, $player->getPPointsBlackJack());

    }
}
