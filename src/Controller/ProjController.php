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
     * @param SessionInterface $session The session interface to store data.
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

        //Deck setup
        $deck = $session->get("theDeck", new DeckOfCardsGraphic());
        $deck->shuffle();

        $session->set("theDeck", $deck);




        //om user redan finns i sesson så gör inget annars får user ett value på 100
        if (!($session->get("$user"))) {
            $value = 100;
            $session->set($user, $value);
        }
        $session->set("numberOfHands", $number);

        return $this->redirectToRoute('play_black', ['user' => $user]);
        // return $this->redirectToRoute('info_proj');
    }

    /**
     * Render the start page for the game. In page meking the bet for every hand
     *
     * @return Response
     */
    #[Route("/proj/start/{user}", name: "play_black")]
    public function start(SessionInterface $session, string $user): Response
    {
        $data = [
            "Value" => $session->get($user),
            "Hands" =>$session->get("numberOfHands")
        ];

        //skickar med hur många händer som ska med och visas up samt hur högt value user har.
        return $this->render('proj/start.html.twig', $data);
    }

    /**
     * Deal cards to the player
     *
     * @return Response
     */
    #[Route("/proj/insats", methods: ["POST"])]
    public function insats(SessionInterface $session, Request $request): Response
    {
        $user = $request->request->get('user');

        $nrHands = $session->get("numberOfHands");


        //Set the bet ot hand
        $insatser = [];
        for ($i=1; $i <= $nrHands; $i++) { 
            $handValue = $request->request->getInt('hand' . $i);
            $insatser[$i] = $handValue;
            $session->set('hand' . $i, $handValue);
        }

        //prep the cards to go to the diffrent hands
        $deckData = $session->get("theDeck");

        //for number of hands make New Player(); så att man kan använda sig utan getPcardsAsString().
        //Ceck if deckData is of DeckOfCards else create a new deck and set that new deck as cards.
        $deck = ($deckData instanceof DeckOfCardsGraphic) ? $deckData : new DeckOfCardsGraphic();
        if (!($deckData instanceof DeckOfCardsGraphic)) {
            $deck->setCards($deckData);
            $deck->shuffle();
        }


        //Ska inte ta och ge ut nya kort om playersHandsString finns i session.
        //setup Hands ie players to get cards and values
        $playersHandsValues = [];
        $playersHandsString = [];
        if ($session->get("playersHandsString")) {
            $playersHandsString = $session->get("playersHandsString");
            $playersHandsValues = $session->get("playersHandsValues");
        }
        if (!($session->get("playersHandsString"))) {
            for ($i=0; $i < $nrHands; $i++) { 
                $playerHand = new Player();
                $playerHand->deal(2, $deck);
                $playersHandsString[$i] = $playerHand->getPCardsAsString();
                $playersHandsValues[$i] = $playerHand->getPPointsBlackJack();
                var_dump($playerHand->getPPointsBlackJack());
            }
            $session->set("playersHandsString", $playersHandsString);
            $session->set("playersHandsValues", $playersHandsValues);
        }


        //prep data
        $data = [
            "HandsNr" => $session->get("numberOfHands"),
            "playersHandsString" => $playersHandsString,
            "playersHandsValues" => $playersHandsValues,
            "insatser" => $insatser
        ];

        return $this->render('proj/deal_base.html.twig', $data); // denna ska leda till att man händerna får två kort och 
        //sedan få man bestämma per hand om man vill ha ett till i en loop till man blir för stor eller har 21.
    }



}
