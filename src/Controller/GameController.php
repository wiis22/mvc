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
        $session->clear();
        if (!$session->get("player_hand") || !($session->get("player_hand") instanceof DeckOfCardsGraphic)) {
            $playerHand = new Player();
            $session->set("player_hand", $playerHand);
        }

        if (!$session->get("bank_hand") || !($session->get("bank_hand") instanceof DeckOfCardsGraphic)) {
            $bankHand = new Player();
            $session->set("bank_hand", $bankHand);
        }

        if (!$session->get("deck21") || !($session->get("deck21") instanceof DeckOfCardsGraphic)) {
            $deck = new DeckOfCardsGraphic();
            $deck->shuffle();
            $session->set("deck21", $deck);
        }

        return $this->render('game/start.html.twig');
    }

    #[Route("/game/draw", name: "draw_21", methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        $deck = $session->get("deck21");
        $playerHand = $session->get("player_hand");



        if ($playerHand == null) {
            $this->addFlash(
                'warning',
                'F mågot gick fel klicka på session Delete!'
            );
            return $this->render('game/home.html.twig');
        }
        $playerHand->deal(1, $deck);
        $test = $playerHand->getPCardsAsString();
        $tot = $playerHand->getPPoints();
        if ($tot >= 21) {
            if ($tot === 21) {
                $this->addFlash(
                    'notice',
                    'Du vann Grattis! Du fick 21!!!'
                );
                return  $this->render('game/bank.html.twig', [ "p_hand" => $test, "p_tot" => $tot]);
            }
            $this->addFlash(
                'warning',
                'Du blev stor! Du förlorade!'
            );
            return  $this->render('game/bank.html.twig', [ "p_hand" => $test, "p_tot" => $tot]);
        }

        $session->set("deck21", $deck);
        $session->set("player_hand_string", $test);
        $session->set("player_tot", $tot);

        return $this->render('game/play.html.twig', [ "p_hand" => $test, "tot" => $tot ]);
    }

    #[Route("/game/stop", name: "stop_21", methods: ['POST'])]
    public function stop(SessionInterface $session): Response
    {
        //här ska det vara baken som gör sitt
        $deck = $session->get("deck21");
        $playerHand = $session->get("player_hand_string");
        $playerTot = $session->get("player_tot");
        $bankHand = $session->get("bank_hand");
        if ($playerHand == null) {
            $this->addFlash(
                'warning',
                'Player måste dra kort först!'
            );
            return $this->render('game/start.html.twig');
        }

        $tot = 0;
        while ($tot <= 17) {
            $bankHand->deal(1, $deck);
            $tot = $bankHand->getPPoints();
        }

        $test = $bankHand->getPCardsAsString();

        $session->set("bank_hand_string", $test);
        $session->set("bank_tot", $tot);


        if ($tot > 21 || $tot < $playerTot) {
            $this->addFlash(
                'notice',
                'Du vann Grattis!'
            );
            return  $this->render('game/bank.html.twig', [ "p_hand" => $playerHand, "p_tot" => $playerTot, "b_hand" => $test, "b_tot" => $tot ]);
        }
        $this->addFlash(
            'warning',
            'Baken vann! Du förlorade!'
        );
        return  $this->render('game/bank.html.twig', [ "p_hand" => $playerHand, "p_tot" => $playerTot, "b_hand" => $test, "b_tot" => $tot ]);
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}
