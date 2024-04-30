<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     * @return void
     */
    public function testCreateCard()
    {
        $card = new Card();
        $this->assertInstanceOf("App\Card\Card", $card);

        $res = $card->getAsString();
        $this->assertNotEmpty($res);
    }

    /**
     * test get value.
     * @return void
     */
    public function testCreateCardWithValue()
    {
        $card = new Card("6");
        $this->assertInstanceOf("App\Card\Card", $card);

        $res = $card->getValue();
        $this->assertTrue($res == "6");
    }

    /**
     * test get Suit.
     * @return void
     */
    public function testCreateCardWithSuit()
    {
        $card = new Card("1", "aSuit");
        $this->assertInstanceOf("App\Card\Card", $card);

        $res = $card->getValue();
        $this->assertTrue($res == "1");
        $res2 = $card->getSuit();
        $this->assertTrue($res2 == "aSuit");
    }

    /**
     * test setSuit.
     * @return void
     */
    public function testCardSetSuit()
    {
        $card = new Card();
        $this->assertInstanceOf("App\Card\Card", $card);

        $card->setSuit("aSuit");
        $res2 = $card->getSuit();
        $this->assertTrue($res2 == "aSuit");
    }

    /**
     * test setValue.
     * @return void
     */
    public function testCardSetValue()
    {
        $card = new Card();
        $this->assertInstanceOf("App\Card\Card", $card);

        $card->setValue("6");
        $res2 = $card->getValue();
        $this->assertTrue($res2 == "6");
    }

}
