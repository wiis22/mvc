<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     * @return void
     */
    public function testCreateDeck()
    {
        $deck = new DeckOfCards();
        $this->assertInstanceOf("App\Card\DeckOfCards", $deck);

        $res = $deck->getCardsAsString();
        $this->assertNotEmpty($res);
    }

    /**
     * Test to shuffle deck.
     * @return void
     */
    public function testshuffleDeck()
    {
        $deck1 = new DeckOfCards();
        $deck2 = new DeckOfCards();

        $this->assertTrue($deck1 == $deck2);
        $deck1->shuffle();
        $this->assertFalse($deck1 == $deck2);
    }

    /**
     * Test to dealCard.
     * @return void
     */
    public function testDealACard()
    {
        $deck = new DeckOfCards();

        $res = $deck->dealCard();
        $this->assertInstanceOf("App\Card\Card", $res);
    }


    /**
     * Test to setCards.
     * @return void
     */
    public function testSetCards()
    {
        $deck = new DeckOfCards();


        $card = [new Card()];
        $this->assertNotEmpty($deck);
        $deck->setCards($card);
        $this->assertEquals($card, $deck->getCards());
    }

    /**
     * Test to getCards.
     * @return void
     */
    public function testGetCards()
    {
        $deck = new DeckOfCards();

        $cards = $deck->getCards();
        $this->assertIsArray($cards);
        foreach ($cards as $card) {
            $this->assertInstanceOf("App\Card\Card", $card);
        }
    }
}
