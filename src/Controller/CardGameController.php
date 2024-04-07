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
    public function home( SessionInterface $session ): Response
    {

        if (!($session->get("deck"))){
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        return $this->render('card/home.html.twig');
    }

    #[Route("/game/deck", name: "show_deck")]
    public function showDeck( SessionInterface $session ): Response
    {
        $deck = $session->get("deck");
        $cardsInDeck = $deck->getCardsAsString();

        return $this->render('card/deck.html.twig',["cardsInDeck" => $cardsInDeck]);
    }


    #[Route("/game/deck/shuffle", name: "deck_shuffle")]
    public function shuffleDeck( SessionInterface $session ): Response
    {

        $deck = new DeckOfCards();
        $deck->shuffle();
        $cardsInDeckShuffled = $deck->getCardsAsString();

        return $this->render('card/shuffle.html.twig',["cardsInDeckShuffled" => $cardsInDeckShuffled]);
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
