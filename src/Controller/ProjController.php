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

class ProjController extends AbstractController
{
    /**
     * Render the homepage for the game.
     *
     * @return Response
     */
    #[Route("/proj", name: "info_proj")]
    public function home(): Response
    {
        return $this->render('proj/home.html.twig');
    }

    /**
     * Initialize the game
     *
     * @return Response
     */
    #[Route("/proj/init", methods: ["POST"])]
    public function init(SessionInterface $session, Request $request): Response
    {
        $user = $request->request->get('user');

        $number = $request->request->getInt('number', 1);
        $this->addFlash(
            'notice',
            "number: $number, user: $user"
        );

        //om user redan finns i sesson så gör inget annars får user ett value på 100
        if (!($session->get("$user"))) {
            $value = 100;
            $session->set($user, $value);
        }
        $session->set("numberOfHands", $number);

        return $this->redirectToRoute('play_black', ["user" => $user]);
        // return $this->redirectToRoute('info_proj');
    }

    /**
     * Render the start page for the game.
     *
     * @return Response
     */
    #[Route("/proj/start", name: "play_black")]
    public function start(): Response
    {
        $data = [
            "Value" => $sessino->get($user),
            "Hands" =>$sessino->get("numberOfHands")
        ];

        //skickar med hur många händer som ska med och visas up samt hur högt value user har.
        return $this->render('proj/start.html.twig', $data);
    }

}
