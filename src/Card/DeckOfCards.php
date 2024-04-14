<?php

namespace App\Card;

class DeckOfCards
{
    /**
     * @var array<\App\Card\Card>
     */
    protected $cards = [];

    public function __construct()
    {
        $this->InitDeck();
    }

    public function initDeck(): void
    {
        $suits = ["♠", "♥", "♦", "♣"];
        $values = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];

        foreach ($suits as $suit) {
            foreach ($values as $value) {
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

    /**
     * @return array<string>
     */
    public function getCardsAsString(): array
    {
        $cardsAsString = [];
        foreach ($this->cards as $card) {
            $cardsAsString[] = $card->getValue() . $card->getSuit();
        }

        return $cardsAsString;
    }

    /**
     * @return array<\App\Card\Card>
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @param array<\App\Card\Card> $cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }
}
