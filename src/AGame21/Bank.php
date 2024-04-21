<?php

namespace App\AGame21;

use App\Card\CardHand;
use App\Card\DeckOfCardsGraphic;


class Bank extends CardHand
{
    public function getBCardsAsString(): array {
        $deck = new DeckOfCardsGraphic();
        $deck->setCards($this->getCards());
        return $deck->getCardsAsString();
    }

}