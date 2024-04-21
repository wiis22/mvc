<?php

namespace App\Card;

class DeckOfCardsGraphic extends DeckOfCards
{
    // /**
    //  * @var array<\App\Card\Card>
    //  */
    // protected array $cards = [];

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
     * get the grapic of the card
     *
     * @param string $suit The suit of the card.
     * @param string $value The value of the card.
     * @return string $suit The graphic representation of card.
     */
    public function getCardGraphic(string $suit, string $value): string
    {
        /**
         * @var array<string>
         */
        $diffrentValues = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];

        switch ($suit) {
            case "♠":
                return $this->spades[array_search($value, $diffrentValues)];
            case "♥":
                return $this->hearts[array_search($value, $diffrentValues)];
            case "♦":
                return $this->diamonds[array_search($value, $diffrentValues)];
            case "♣":
                return $this->clubs[array_search($value, $diffrentValues)];
            default:
                return "";
        }
    }

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @return array<string> Graphic version.
     */
    public function getCardsAsString(): array
    {
        $cardsAsString = [];
        foreach ($this->cards as $card) {
            $suit = $card->getSuit();
            $value = $card->getValue();
            $cardsAsString[] = $this->getCardGraphic((string) $suit, (string) $value);
        }
        return $cardsAsString;
    }
}
