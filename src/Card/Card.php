<?php

namespace App\Card;

class Card
{
    protected $value;
    protected $suit;

    public function __construct($value = null, $suit = null)
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

    public function setValue(): void
    {
        $this->value = $value;
    }

    public function setSuit($suit): void
    {
        $this->suit = $suit;
    }

    public function getAsString(): string
    {
        return "[{$this->value} of {$this->suit}]";
    }
}
