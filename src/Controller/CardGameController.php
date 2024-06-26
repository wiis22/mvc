<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCardsGraphic;
use App\Card\DeckOfCards;

class CardGameController extends AbstractController
{
    /**
     * Display the homepage for the card game.
     *
     * @param SessionInterface $session The session interface to store game data.
     * @return Response
     */
    #[Route("/game/card", name: "card_start")]
    public function home(SessionInterface $session): Response
    {

        if (!$session->get("deck") || !($session->get("deck") instanceof DeckOfCardsGraphic)) {
            $deck = new DeckOfCardsGraphic();
            $session->set("deck", $deck);
        }
        return $this->render('card/home.html.twig');
    }

    /**
     * Show the curresnt deck of cards.
     *
     * @param SessionInterface $session The session interface to get game data.
     * @return Response
     */
    #[Route("/game/deck", name: "show_deck")]
    public function showDeck(SessionInterface $session): Response
    {
        $deck = $session->get("deck");

        $cardsInDeck = $deck->getCardsAsString();

        return $this->render('card/deck.html.twig', ["cardsInDeck" => $cardsInDeck]);
    }

    /**
     * Shuffle the deck of cards.
     *
     * @param SessionInterface $session The session interface to get and update game data.
     * @return Response
     */
    #[Route("/game/deck/shuffle", name: "deck_shuffle")]
    public function shuffleDeck(SessionInterface $session): Response
    {

        $deck = new DeckOfCardsGraphic();
        $deck->shuffle();
        $session->set("deck", $deck);
        $cardsInDeckShuffled = $deck->getCardsAsString();

        return $this->render('card/shuffle.html.twig', ["cardsInDeckShuffled" => $cardsInDeckShuffled]);
    }

    /**
     * Draw a card from the deck.
     *
     * @param SessionInterface $session The session interface to get and update game data.
     * @return Response
     */
    #[Route("/game/deck/draw", name: "deck_draw")]
    public function drawCard(SessionInterface $session): Response
    {
        $deck = $session->get("deck", new DeckOfCardsGraphic());

        $drawnCard = $deck->dealCard();
        if ($drawnCard == null) {
            $this->addFlash(
                'warning',
                'Finns inga mer kort att dra, shuffla leken!'
            );
            return $this->render('card/home.html.twig');
        }
        $drawnCard = $drawnCard->getAsString();
        $remainingCards = count($deck->getCardsAsString());

        $session->set("deck", $deck);
        return $this->render('card/draw.html.twig', [
            "drawnCard" => $drawnCard,
            "remainingCards" => $remainingCards
        ]);
    }

    /**
     * Draw a specific numer of cards from the deck.
     *
     * @param int $number The nomber of cards to ne drawn.
     * @param SessionInterface $session The session interface to get and update game data.
     * @return Response
     */
    #[Route("/game/deck/draw/{number}", name: "deck_draw_number")]
    public function drawCardNumber(int $number, SessionInterface $session): Response
    {
        $deck = $session->get("deck", new DeckOfCardsGraphic());

        $remainingCards = count($deck->getCardsAsString());
        if ($number > $remainingCards) {
            $this->addFlash(
                'warning',
                'Finns inga tillräkligt med kort att dra, shuffla leken eller testa en midre nummer!'
            );
            return $this->render('card/home.html.twig');
        }

        $drawnCards = [];

        for ($i = 0; $i < $number; $i++) {
            $drawnCard = $deck->dealCard();
            $drawnCards[] = $drawnCard->getAsString();
        }

        $remainingCards = count($deck->getCardsAsString());

        $session->set("deck", $deck);

        return $this->render('card/draw_number.html.twig', [
                "drawnCards" => $drawnCards,
                "remainingCards" => $remainingCards
        ]);
    }

    /**
     * View the current session data.
     *
     * @param SessionInterface $session The session interface to view the data in it.
     * @return Response
     */
    #[Route("/session", name: "session_view")]
    public function viewSession(SessionInterface $session): Response
    {
        $sessionData = $session->all();
        $sessionKeys = array_keys($sessionData);
        // var_dump($sessionData);
        return $this->render('session/view.html.twig', [
            'sessionKeys' => $sessionKeys,
        ]);
    }

    /**
     * Delete the data in session
     *
     * @param SessionInterface $session The session interface to delete the data in session.
     * @return Response
     */
    #[Route("/session/delete", name: "session_delete")]
    public function deleteSession(SessionInterface $session): Response
    {
        $session -> clear();
        $this->addFlash(
            'warning',
            'nu är sessionen raderad!'
        );
        return $this->redirectToRoute('session_view');
    }
}
