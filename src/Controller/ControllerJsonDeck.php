<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class ControllerJsonDeck
{
    #[Route("/api/deck", methods: ["GET"])]
    public function jsonApi(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $cards = $deck->getCards();
        $theDeck = [];
        foreach ($cards as $card) {
            $suit = $card->getSuit();
            $value = $card->getValue();
            $theDeck[$suit][$value] = $card->getAsString();
        }

        $session->set("theDeck",  $deck);

        $response = new JsonResponse($theDeck);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", methods: ["POST"])]
    public function shuffleDeck(SessionInterface $session): JsonResponse
    {
        $deck = $session->get("theDeck", new DeckOfCards());
        $deck->shuffle();

        $cards = $deck->getCards();
        $theDeck = [];
        foreach ($cards as $card) {
            $suit = $card->getSuit();
            $value = $card->getValue();
            $theDeck[$value . $suit] = $card->getAsString();
        }


        $session->set("theDeck", $deck);

        $response = new JsonResponse($theDeck);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", methods: ["POST"])]
    public function drawCards(SessionInterface $session): Response
    {
        $deckData = $session->get("theDeck");

        if ($deckData instanceof DeckOfCards){
            $deck = $deckData;
        } else {
            $deck = new DeckOfCards();
            $deck->setCards($deckData);
        }

        $card = $deck->dealCard();

        $remainingNumberOfCardsInDeck = count($deck->getCardsAsString());

        $session->set("theDeck", $deck);

        $theCard = [];
        $suit = $card->getSuit();
        $value = $card->getValue();
        $theCard[$value . $suit] = $card->getAsString();
        $data = [
            "drawn_card" => $theCard,
            "remaining_cards" => $remainingNumberOfCardsInDeck
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw/{number}", methods: ["POST"])]
    public function drawCardsNumber(SessionInterface $session, $number): Response
    {
        $deckData = $session->get("theDeck");

        if ($deckData instanceof DeckOfCards){
            $deck = $deckData;
        } else {
            $deck = new DeckOfCards();
            $deck->setCards($deckData);
        }

        for ($i = 0; $i < $number; $i++) {
            $card = $deck->dealCard();
            $drawnCards[] = $card->getAsString();
        }
        $remainingNumberOfCardsInDeck = count($deck->getCardsAsString());

        $session->set("theDeck", $deck);
        $remainingCards = count($deck->getCardsAsString());
        $data = [
            "drawn_cards" => $drawnCards,
            "remaining_cards" => $remainingNumberOfCardsInDeck
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}