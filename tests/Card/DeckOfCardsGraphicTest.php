<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     * @return void
     */
    public function testCreateGraphicDeck()
    {
        $deck = new DeckOfCardsGraphic();
        $this->assertInstanceOf("App\Card\DeckOfCardsGraphic", $deck);

        $res = $deck->getCardsAsString();
        $this->assertNotEmpty($res);
    }


    /**
     * test to see that get graphic worsk with a suit that dose not exist
     * @return void
     */
    public function testGetCardGraphic()
    {
        $deck = new DeckOfCardsGraphic();

        $invalidSuit = "Wrong!";
        $validValue = "A";

        $res = $deck->getCardGraphic($invalidSuit, $validValue);

        $this->assertSame("", $res);

    }
}
