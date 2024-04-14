<?php

namespace App\DeckOfCards;

class CardGraphic extends DeckOfCards
{
    /**
     * @var array<string>
     */
    private array $spades = ["🂡", "🂢", "🂣", "🂤", "🂥", "🂦", "🂧", "🂨", "🂩", "🂪", "🂫", "🂭", "🂮"];


    /**
     * @var array<string>
     */
    private array $hearts = ["🂱", "🂲", "🂳", "🂴", "🂵", "🂶", "🂷", "🂸", "🂹", "🂺", "🂻", "🂽", "🂾"];

    /**
     * @var array<string>
     */
    private array $diamonds = ["🃁", "🃂", "🃃", "🃄", "🃅", "🃆", "🃇", "🃈", "🃉", "🃊", "🃋", "🃍", "🃎"];

    /**
     * @var array<string>
     */
    private array $clubs = ["🃑", "🃒", "🃓", "🃔", "🃕", "🃖", "🃗", "🃘", "🃙", "🃚", "🃛", "🃝", "🃞"];

    /**
     * @var array<string>
     */
    private array $diffrentValues = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];


    /**
     * get the grapic of the card
     *
     * @param string $suit The suit of the card.
     * @param string $value The value of the card.
     * @return string $suit The graphic representation of card.
     */
    public function getCardGraphic(string $suit, string $value): string
    {
        switch ($suit) {
            case "♠":
                return $this->$spades[array_search($value, $diffrentValues)];
            case "♥":
                return $this->$hearts[array_search($value, $diffrentValues)];
            case "♦":
                return $this->$diamonds[array_search($value, $diffrentValues)];
            case "♣":
                return $this->$clubs[array_search($value, $diffrentValues)];
            default:
                return "";
        }
    }

    /**
     * @var array<string>
     */
    public function getCardsAsString(): array
    {
        // return continue;// a map that has the key [suit;
    }
}
