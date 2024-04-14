<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\Card;
use App\Card\CardHand;
use App\Card\DeckOfCards;

class CardGameController extends AbstractController
{
    #[Route("/game/card", name: "card_start")]
    public function home(SessionInterface $session): Response
    {

        if (!$session->get("deck") || !($session->get("deck") instanceof DeckOfCards)) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        return $this->render('card/home.html.twig');
    }

    #[Route("/game/deck", name: "show_deck")]
    public function showDeck(SessionInterface $session): Response
    {
        $deck = $session->get("deck");

        $cardsInDeck = $deck->getCardsAsString();

        return $this->render('card/deck.html.twig', ["cardsInDeck" => $cardsInDeck]);
    }


    #[Route("/game/deck/shuffle", name: "deck_shuffle")]
    public function shuffleDeck(SessionInterface $session): Response
    {

        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck);
        $cardsInDeckShuffled = $deck->getCardsAsString();

        return $this->render('card/shuffle.html.twig', ["cardsInDeckShuffled" => $cardsInDeckShuffled]);
    }

    #[Route("/game/deck/draw", name: "deck_draw")]
    public function drawCard(SessionInterface $session): Response
    {
        $deck = $session->get("deck", new DeckOfCards());

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

    #[Route("/game/deck/draw/{number}", name: "deck_draw_number")]
    public function drawCardNumber(int $number, SessionInterface $session): Response
    {
        $deck = $session->get("deck", new DeckOfCards());

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
