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
     * Gets the total points in hand for black jack.
     *
     * @return int total value of hand
     */
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getPPointsBlackJack(): int
    {
        $cards = $this->getCards();
        $tot = 0;
        $aceC = 0;

        //Mapping the card values.
        $valueMap = [
            'A' => 11,
            'K' => 10,
            'Q' => 10,
            'J' => 10
        ];

        //Check each card in hand to se tot value.
        foreach ($cards as $card) {
            if ($card !== null) {
                $value = $card->getValue();
                //Using the map to get the card values.
                $cardValue = $valueMap[$value] ?? (int)$value;

                if($value === 'A') {
                    $aceC++;
                }

                $tot += $cardValue;
            }
        }

        //Check if we got an Ace and if tot is over 21.
        while ($aceC > 0 && $tot > 21) {
            $tot -= 10;
            $aceC--;
        }

        return $tot;
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

        //Mapping the card values.
        $valueMap = [
            'A' => 1,
            'K' => 13,
            'Q' => 12,
            'J' => 11
        ];

        //Check each card in hand to se tot value.
        foreach ($cards as $card) {
            if ($card !== null) {
                $value = $card->getValue();

                //Using the map to get the card values.
                $cardValue = $valueMap[$value] ?? (int)$value;

                if($value === 'A') {
                    $aceC++;
                }

                $tot += $cardValue;
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
