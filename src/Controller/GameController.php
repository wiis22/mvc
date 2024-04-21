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

class GameController extends AbstractController
{
    #[Route("/game", name: "info_game")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/start", name: "start")]
    public function start(): Response
    {
        if (!$session->get("deck21") || !($session->get("deck21") instanceof DeckOfCardsGraphic)) {
            $deck = new DeckOfCardsGraphic();
            $deck->shuffle();
            $session->set("deck21", $deck);
        }
        return $this->render('game/start.html.twig');
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}