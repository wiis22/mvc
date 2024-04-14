<?php

namespace App\Card;

class CardHand
{
    /**
     * @var array<\App\Card\Card|null>
     */
    protected array $cards = [];
    protected DeckOfCards $deck;

    public function __construct(int $numberOfCards, DeckOfCards $deck)
    {
        $this->deal($numberOfCards);
        $this->deck = $deck;
    }

    public function deal(int $numberOfCards): void
    {
        for ($i = 0; $i < $numberOfCards; $i++) {
            $this->cards[] = $this->deck->dealCard();
        }
    }

    /**
     * @return array<\App\Card\Card|null>
     */
    public function getCards(): array
    {
        return $this->cards;
    }
}
