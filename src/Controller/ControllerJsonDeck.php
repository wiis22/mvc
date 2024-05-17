<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class ControllerJsonDeck extends AbstractController
{
    /**
     * Create a JSON representation of a deck of cards. And store it in session.
     *
     * @param SessionInterface $session The session interface to store deck data.
     * @return JsonResponse The JSON response containing info that the deck is shuffled and the deck..
     */
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

        $session->set("theDeck", $deck);

        $response = new JsonResponse($theDeck);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Shuffles the deck.
     *
     * @param SessionInterface $session The session interface to store deck data.
     * @return JsonResponse The JSON response containing info that the deck is shuffled and the deck..
     */
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

        $responseData = [
            'Message' => 'Leken ar blandad',
            'shuffled_deck' => $theDeck
        ];

        $response = new JsonResponse($responseData);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return  $response;
    }

    /**
     * Draw a card from the deck.
     *
     * @param SessionInterface $session The session interface to store deck data.
     * @return JsonResponse The JSON response containing info of drawn cards and remaing cards in deck.
     */
    #[Route("/api/deck/draw", methods: ["POST"])]
    public function drawCards(SessionInterface $session): Response
    {
        $deckData = $session->get("theDeck");

        //Ceck if deckData is of DeckOfCards else create a new deck and set that new deck as cards.
        $deck = ($deckData instanceof DeckOfCards) ? $deckData : new DeckOfCards();
        if (!($deckData instanceof DeckOfCards)) {
            $deck->setCards($deckData);
        }
        $card = $deck->dealCard();

        $remainingCards = count($deck->getCardsAsString());

        $session->set("theDeck", $deck);

        $theCard = [];
        if ($card instanceof Card) {
            $suit = $card->getSuit();
            $value = $card->getValue();
            $theCard[$value . $suit] = $card->getAsString();
        }
        $data = [
            "drawn_card" => $theCard,
            "remaining_cards" => $remainingCards
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return  $response;
    }

    /**
     * Draw a specific number of cards from the deck.
     *
     * @param SessionInterface $session The session interface to store deck data.
     * @param Request $request the request containing the number of cards to be drawn.
     * @return JsonResponse The JSON response containing info of drawn cards and remaing cards in deck.
     */
    #[Route("/api/deck/draw/{number}", methods: ["POST"])]
    public function drawCardsNumber(SessionInterface $session, Request $request): Response
    {
        $number = $request->request->getInt('number', 0);
        print($number);

        $deckData = $session->get("theDeck");

        //Ceck if deckData is of DeckOfCards else create a new deck and set that new deck as cards.
        $deck = ($deckData instanceof DeckOfCards) ? $deckData : new DeckOfCards();

        //set cards for the new deck if deckdata is not of DeckOfCards.
        if (!($deckData instanceof DeckOfCards)) {
            $deck->setCards($deckData);
        }

        //Draw cars from the deck
        $drawnCards = [];
        for ($i = 0; $i < (int)$number; $i++) {
            if ($deck instanceof DeckOfCards) {
                $card = $deck->dealCard();
                if ($card instanceof Card) {
                    $drawnCards[] = $card->getAsString();
                }
            }

        }

        //The remaining number of cards in deck.
        $remainingCards = count($deck->getCardsAsString());

        //Update session with the deck.
        $session->set("theDeck", $deck);

        //Response data
        $data = [
            "drawn_cards" => $drawnCards,
            "remaining_cards" => $remainingCards
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return  $response;
    }
}
