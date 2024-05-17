<?php

namespace App\AGame21;

use App\Card\CardHand;
use App\Card\DeckOfCardsGraphic;

class Player extends CardHand
{
    /**
     * @return array<string>
     */
    public function getPCardsAsString(): array
    {
        $deck = new DeckOfCardsGraphic();
        $cards = $this->getCards();
        $deck->setCards($cards); // @phpstan-ignore-line
        return $deck->getCardsAsString();
    }

    /**
     * Gets the total points in hand.
     *
     * @return int total value of hand
     */
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getPPoints(): int
    {
        $cards = $this->getCards();
        $tot = 0;
        $aceC = 0;

        //Check each card in hand to se tot value.
        foreach ($cards as $card) {
            if ($card !== null) {
                $value = $card->getValue();

                //Check what value a card gives.
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
        }


        //Check if we got an Ace and if we can add 13 without going bust.
        while ($aceC > 0 && $tot + 13 <= 21) {
            $tot += 13;
            $aceC--;
        }

        return $tot;
    }

    /**
     * @param array<\App\Card\Card|null> $cards an array of cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }
}
