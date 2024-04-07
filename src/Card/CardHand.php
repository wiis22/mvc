<?php

namespace App\Card;

class CardHand
{
    protected $cards = [];
    protected $deck;

    public function __construct(int $numberOfCards, DeckOfCards $deck)
    {
        $this->deal($numberOfCards);
        $this->deck = $deck;
    }

    public function deal(int $numberOfCards): void
    {
        for ($i=0; $i < $numberOfCards; $i++) 
        {
            $this->cards[] = $this->deck->dealCard();
        }
    }

    public function getCards(): array
    {
        return $this->cards;
    }
}