<?php

namespace App\Card;

class DeckOfCards
{
    protected $cards = [];

    public function __construct()
    {
        $this->InitDeck();
    }

    public function initDeck(): void{
        $suits = ["♠", "♥", "♦", "☘"];
        $values = ["2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A"];

        foreach ($suits as $suit)
        {
            foreach ($values as $value)
            {
                $this->cards[] = new Card($value, $suit);
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function dealCard(): ?Card
    {
        return array_pop($this->cards);
    }
}