<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     * @return void
     */
    public function testCreateCard()
    {
        $cardH = new CardHand();
        $this->assertInstanceOf("App\Card\CardHand", $cardH);

        $res = $cardH->getCards();
        $this->assertTrue($res == []);
        ;
    }

    /**
     * Test so deal sets the numberOfCards from DeckOFCardsGraphic
     */
    public function testDeal(): void
    {
        $mockDeck = $this->createMock(DeckOfCardsGraphic::class);

        $numberOfCards = 3;

        $mockDeck->expects($this->exactly($numberOfCards))
                ->method('dealCard')
                ->willReturn($this->createMock(Card::class));

        $cardH = new CardHand();

        $cardH->deal($numberOfCards, $mockDeck);

        $cards = $cardH->getCards();

        $this->assertCount($numberOfCards, $cards, "wrong number of cards dealt");

        foreach ($cards as $card) {
            $this->assertInstanceOf("App\Card\Card", $card);
        }

    }
}
