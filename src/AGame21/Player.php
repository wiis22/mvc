<?php

namespace App\AGame21;

use App\Card\CardHand;
use App\Card\DeckOfCardsGraphic;


class Player extends CardHand
{
    public function getPCardsAsString(): array {
        $deck = new DeckOfCardsGraphic();
        $deck->setCards($this->getCards());
        return $deck->getCardsAsString();
    }

    public function getPPoints(): int {
        $cards = $this->getCards();
        $tot = 0;
        $aceC = 0;

        foreach ($cards as $card) {
            $value = $card->getValue();

            switch ($value) {
                case 'A':
                    $aceC++;
                    $tot += 1;
                    break;
                case 'K':
                    $tot += 13;
                    break;
                case 'Q':
                    $tot += 12;
                    break;
                case 'J':
                    $tot += 11;
                    break;

                default:
                    $tot += (int) $value;
                    break;
            }
        }

        for ($i=0; $i < $aceC; $i++) { 
            if ($tot + 13 <= 21) {
                $tot += 13;
            }
        }

        return $tot;
    }

    /**
     * @param array<\App\Card\Card> $cards an array of cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }
}