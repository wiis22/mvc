<?php

namespace App\Card;

class Card
{
    protected ?string $value;
    protected ?string $suit;

    public function __construct(?string $value = null, ?string $suit = null)
    {
        $this->value = $value;
        $this->suit = $suit;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getSuit(): ?string
    {
        return $this->suit;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function setSuit(?string $suit): void
    {
        $this->suit = $suit;
    }

    public function getAsString(): string
    {
        return "[{$this->value} of {$this->suit}]";
    }
}
