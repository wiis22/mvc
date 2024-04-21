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
use App\AGame21\Player;
use App\AGame21\Bank;

class GameController extends AbstractController
{
    #[Route("/game", name: "info_game")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/start", name: "start")]
    public function start(SessionInterface $session): Response
    {
        if (!$session->get("player_hand") || !($session->get("player_hand") instanceof DeckOfCardsGraphic)) {
            $player_hand = new Player();
            $session->set("player_hand", $player_hand);
        }

        if (!$session->get("Bank_hand") || !($session->get("Bank_hand") instanceof DeckOfCardsGraphic)) {
            $Bank_hand = new Player();
            $session->set("Bank_hand", $Bank_hand);
        }

        if (!$session->get("deck21") || !($session->get("deck21") instanceof DeckOfCardsGraphic)) {
            $deck = new DeckOfCardsGraphic();
            $deck->shuffle();
            $session->set("deck21", $deck);
        }

        return $this->render('game/start.html.twig');
    }

    #[Route("/game/draw", name: "draw_21", methods: ['POST'])]
    public function draw( SessionInterface $session ): Response
    {
        $deck = $session->get("deck21");
        $player_hand = $session->get("player_hand");



        if ($player_hand == null) {
            $this->addFlash(
                'warning',
                'F mågot gick fel klicka på session Delete!'
            );
            return $this->render('game/home.html.twig');
        }
        $player_hand->deal(1, $deck);
        $test = $player_hand->getPCardsAsString();
        $tot = $player_hand->getPPoints();
        if ($tot >= 21) {
            if ($tot === 21) {
                $this->addFlash(
                    'notice',
                    'Du vann Grattis!'
                );
                //rensa session
                $session->clear();
                return $this->render('game/home.html.twig');
            }
            $this->addFlash(
                'warning',
                'Du blev stor! Du förlorade!'
            );
            //rensa session
            $session->clear();
            return $this->render('game/home.html.twig');
        }

        $session->set("deck21", $deck);
        $session->set("player_hand", $player_hand);

        return $this->render('game/play.html.twig', [ "p_hand" => $test, "tot" => $tot ]);
    }

    #[Route("/game/stop", name: "stop_21", methods: ['POST'])]
    public function stop(SessionInterface $session): Response {
        //här ska det vara baken som gör sitt
        $deck = $session->get("deck21");
        $player_hand = $session->get("player_hand");
        $bank_hand = $session->get("bank_hand");
        if ($player_hand == null) {
            $this->addFlash(
                'warning',
                'Player måste dra kort först!'
            );
            return $this->render('game/start.html.twig');
        }

        $bank_hand->deal(1, $deck);
        $test = $bank_hand->getPCardsAsString();
        $tot = $bank_hand->getPPoints();

        return $this->redirectToRoute('start');
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}