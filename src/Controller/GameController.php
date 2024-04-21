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
            $player_hand = new Player();
            $session->set("player_hand", $player_hand);
        }

        if (!$session->get("bank_hand") || !($session->get("bank_hand") instanceof DeckOfCardsGraphic)) {
            $Bank_hand = new Player();
            $session->set("bank_hand", $Bank_hand);
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
    public function stop(SessionInterface $session): Response {
        //här ska det vara baken som gör sitt
        $deck = $session->get("deck21");
        $player_hand = $session->get("player_hand_string");
        $player_tot = $session->get("player_tot");
        $bank_hand = $session->get("bank_hand");
        if ($player_hand == null) {
            $this->addFlash(
                'warning',
                'Player måste dra kort först!'
            );
            return $this->render('game/start.html.twig');
        }

        $tot = 0;
        while ($tot <= 17) {
            $bank_hand->deal(1, $deck);
            $tot = $bank_hand->getPPoints();
        }

        $test = $bank_hand->getPCardsAsString();

        if ($tot > 21 || $tot < $player_tot) {
            $this->addFlash(
                'notice',
                'Du vann Grattis!'
            );
            return  $this->render('game/bank.html.twig', [ "p_hand" => $player_hand, "p_tot" => $player_tot, "b_hand" => $test, "b_tot" => $tot ]);
        }
        $this->addFlash(
            'warning',
            'Baken vann! Du förlorade!'
        );
        return  $this->render('game/bank.html.twig', [ "p_hand" => $player_hand, "p_tot" => $player_tot, "b_hand" => $test, "b_tot" => $tot ]);
    }

    #[Route("/game/doc", name: "doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
}