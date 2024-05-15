<?php

namespace App\Card;

/**
 * A class that represents a playing card.
 */
class Card
{
    /**
     * @var string|null The value fo the card.
     */
    protected ?string $value;

    /**
     * @var string|null The suit fo the card.
     */
    protected ?string $suit;

    /**
     * Card constructor.
     * 
     * @param string|null $value The value of the card.
     * @param string|null $suit The suit of the card.
     */
    public function __construct(?string $value = null, ?string $suit = null)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    /**
     * Get the value of the card.
     * 
     * @return string|null The value of the card.
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Get the suit of the card.
     * 
     * @return string|null The suit of the card.
     */
    public function getSuit(): ?string
    {
        return $this->suit;
    }

    /**
     * Set the value of the card.
     * 
     * @param string|null The value of the card.
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * Set the suit of the card.
     * 
     * @param string|null The suit of the card.
     */
    public function setSuit(?string $suit): void
    {
        $this->suit = $suit;
    }

    /**
     * Get a string rerpresentation of the card.
     * 
     * @return string The string rep of the card.
     */
    public function getAsString(): string
    {
        return "[{$this->value} of {$this->suit}]";
    }
}
