<?php

namespace App\Card;

class CardHand
{
    /**
     * @var array<\App\Card\Card|null>
     */
    protected array $cards = [];

    public function deal(int $numberOfCards, DeckOfCardsGraphic $deck): void
    {
        for ($i = 0; $i < $numberOfCards; $i++) {
            $this->cards[] = $deck->dealCard();
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
