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
     * Render the start of the game.
     *
     * @return Response
     */
    #[Route("/proj", name: "proj_spel")]
    public function home(): Response
    {
        return $this->render('proj/spel.html.twig');
    }

    /**
     * Render the info / sbout page for the project.
     *
     * @return Response
     */
    #[Route("/proj/about", name: "proj_info")]
    public function about(): Response
    {
        return $this->render('proj/home.html.twig');
    }

    /**
     * Initialize the game
     *
     * @param SessionInterface $session The session interface to store data.
     * @return Response
     */
    #[Route("/proj/init", name: "proj_init", methods: ["POST"])]
    public function init(SessionInterface $session, Request $request): Response
    {
        $user = $request->request->get('user');

        $number = $request->request->getInt('number', 1);


        //ta och rensa nuvarande session från en lista av saker. listan:
        $sessionVar = [
        "theDeck",
        "stoped",
        "numberOfHands",
        "insatser",
        "players",
        "playersHandsString",
        "playersHandsValues",
        "bankHandString",
        "totBank",
        "messages",
        'username'
        ];

        foreach ($sessionVar as $var) {
            if ($session->has($var)) {
                $session->remove($var);
            }
        }

        //Deck setup
        $deck = $session->get("theDeck", new DeckOfCardsGraphic());
        $deck->shuffle();

        $session->set("theDeck", $deck);

        $stoped = [];
        $session->set("stoped", $stoped);


        //om user redan finns i sesson så gör inget annars får user ett value på 100
        if (!($session->get("$user"))) {
            $value = 100;
            $session->set("$user", $value);
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
            "Hands" => $session->get("numberOfHands"),
            "user" => $user
        ];

        //skickar med hur många händer som ska med och visas up samt hur högt value user har.
        return $this->render('proj/start.html.twig', $data);
    }

    /**
     * Deal cards to the player
     *
     * @return Response
     */
    #[Route("/proj/insats", name: "proj_insats", methods: ["POST"])]
    public function insats(SessionInterface $session, Request $request): Response
    {
        $user = $request->request->get('user');
        $nrHands = $session->get("numberOfHands");
        $value = $session->get("$user");
        $stoped = $session->get("stoped");


        //Set the bet ot hand and remove the bet from the value of the user.
        $insatser = [];
        for ($i = 1; $i <= $nrHands; $i++) {
            $handValue = $request->request->getInt('hand' . $i);
            $insatser[$i] = $handValue;
            if (!($session->get("playersHandsString"))) {
                $value -= $handValue;
            }
        }
        $session->set("insatser", $insatser);
        $session->set("$user", $value);

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
        $players = [];

        if ($session->get("playersHandsString")) {
            $playersHandsString = $session->get("playersHandsString");
            $playersHandsValues = $session->get("playersHandsValues");
        }

        if (!($session->get("playersHandsString"))) {
            for ($i = 0; $i < $nrHands; $i++) {
                $playerHand = new Player();
                $playerHand->deal(2, $deck);
                $players[$i] = $playerHand;
                $playersHandsString[$i] = $playerHand->getPCardsAsString();
                $playersHandsValues[$i] = $playerHand->getPPointsBlackJack();
                if ($playerHand->getPPointsBlackJack() === 21) {
                    array_push($stoped, $i);
                }
            }
            $session->set("stoped", $stoped);
            $session->set("players", $players);
            $session->set("playersHandsString", $playersHandsString);
            $session->set("playersHandsValues", $playersHandsValues);
            $session->set("theDeck", $deck);
        }


        //prep data
        $data = [
            "Value" => $session->get("$user"),
            "HandsNr" => $session->get("numberOfHands"),
            "playersHandsString" => $playersHandsString,
            "playersHandsValues" => $playersHandsValues,
            "insatser" => $insatser,
            "user" => $user,
            "stoped" => $stoped
        ];

        return $this->render('proj/deal_base.html.twig', $data); // denna ska leda till att man händerna får två kort och
        //sedan få man bestämma per hand om man vill ha ett till i en loop till man blir för stor eller har 21.
    }


    /**
     * Draw a card to a specific hand
     *
     * @return Response
     */
    #[Route("/proj/draw", name: "proj_draw", methods: ["POST"])]
    public function draw(SessionInterface $session, Request $request): Response
    {
        $user = $request->request->get('user');
        $drawToHand = $request->request->getInt('hand');

        $deck = $session->get("theDeck");
        $insatser = $session->get("insatser");
        $players = $session->get("players");
        $stoped = $session->get("stoped");

        //Setup look to se that the players[$drawToHand] got something in it then draw a card,
        //then after check what value the hand got if the value is above 21 then remove the insats.
        $playersHandsValues = [];
        $playersHandsString = [];

        if ($session->get("playersHandsString")) {

            $playersHandsString = $session->get("playersHandsString");
            $playersHandsValues = $session->get("playersHandsValues");

            $playerHand = $players[$drawToHand - 1];
            $playerHand->deal(1, $deck);
            $playersHandsString[$drawToHand - 1] = $playerHand->getPCardsAsString();
            $playersHandsValues[$drawToHand - 1] = $playerHand->getPPointsBlackJack();

            //om en hand blir tjock så tas dens insats bort från insatser. Och $stoped[] = $drawToHand

            if($playersHandsValues[$drawToHand - 1] > 21) {
                // $insatser[$drawToHand] = 0;
                array_push($stoped, $drawToHand);
                $session->set("username", $user);

                // $stoped[] = $drawToHand;
            }


            $session->set("playersHandsString", $playersHandsString);
            $session->set("playersHandsValues", $playersHandsValues);
            $session->set("insatser", $insatser);
            $session->set("stoped", $stoped);
            // error_log(print_r($playersHandsValues, true));
        }


        //prep data
        $data = [
            "Value" => $session->get("$user"),
            "HandsNr" => $session->get("numberOfHands"),
            "playersHandsString" => $playersHandsString,
            "playersHandsValues" => $playersHandsValues,
            "insatser" => $insatser,
            "user" => $user,
            "stoped" => $stoped
        ];

        //om stoped.length === $session->get("numberOfHands") så ska dealern få dra kort.
        if (count($stoped) === $session->get("numberOfHands")) {
            return $this->redirectToRoute('Dealer', $data);
        }

        return $this->render('proj/deal_base.html.twig', $data);
    }

    /**
     * Stop for a specific hand.
     *
     * @return Response
     */
    #[Route("/proj/stop", name: "proj_stop", methods: ["POST"])]
    public function stop(SessionInterface $session, Request $request): Response
    {
        $user = $request->request->get('user');
        $drawToHand = $request->request->getInt('hand');

        $insatser = $session->get("insatser");
        $stoped = $session->get("stoped");

        $playersHandsValues = [];
        $playersHandsString = [];

        if (!(count($stoped) === $session->get("stoped"))) {

            $playersHandsString = $session->get("playersHandsString");
            $playersHandsValues = $session->get("playersHandsValues");


            array_push($stoped, $drawToHand);

            $session->set("stoped", $stoped);

        }

        $session->set("username", $user);


        //prep data
        $data = [
            "Value" => $session->get("$user"),
            "HandsNr" => $session->get("numberOfHands"),
            "playersHandsString" => $playersHandsString,
            "playersHandsValues" => $playersHandsValues,
            "insatser" => $insatser,
            "user" => $user,
            "stoped" => $stoped
        ];

        //om stoped.length === $session->get("numberOfHands") så ska dealern få dra kort.
        if (count($stoped) === $session->get("numberOfHands")) {
            return $this->redirectToRoute('Dealer', $data);
        }

        return $this->render('proj/deal_base.html.twig', $data);
    }

    /**
     * The dealers time to draw
     *
     * @return Response
     */
    #[Route("/proj/dealer", name: "Dealer")]
    public function dealer(SessionInterface $session): Response
    {

        $deck = $session->get("theDeck");
        $insatser = $session->get("insatser");
        $stoped = $session->get("stoped");
        $playersHandsString = $session->get("playersHandsString");
        $playersHandsValues = $session->get("playersHandsValues");

        $bankHandString = $session->get("bankHandString");
        $totBank = $session->get("totBank");


        if (!$bankHandString) {
            list($bankHandString, $totBank) = $this->drawToDealer($deck);
        }

        $session->set("bankHandString", $bankHandString);
        $session->set("totBank", $totBank);

        //prep for check of hands.

        $messages = $session->get("messages");
        $user = $session->get("username");
        $value = $session->get("$user");

        //chek result of hands
        if (!$messages) {
            list($messages, $value, $insatser) = $this->checkHands($playersHandsValues, $totBank, $insatser, $value);
            $session->set("messages", $messages);
            $session->set("$user", $value);
        }

        //prep data
        $data = [
            "Value" => $value,
            "HandsNr" => $session->get("numberOfHands"),
            "playersHandsString" => $playersHandsString,
            "playersHandsValues" => $playersHandsValues,
            "insatser" => $insatser,
            "user" => $user,
            "stoped" => $stoped,
            "bankHandString" => $bankHandString,
            "totBank" => $totBank,
            "messages" => $messages
        ];

        return $this->render('proj/bank.html.twig', $data);
    }

    // /**
    //  * @param DeckOfCardsGraphic $deck
    //  * @return array{array<string>, int}
    //  */
    // @phpstan-ignore-next-line
    private function drawToDealer(DeckOfCardsGraphic $deck): array
    {
        $bankHand = new Player();
        $totBank = 0;
        while ($totBank < 17) {
            $bankHand->deal(1, $deck);
            $totBank = $bankHand->getPPointsBlackJack();
        }
        return [$bankHand->getPCardsAsString(), $totBank];
    }

    // @phpstan-ignore-next-line
    private function checkHands($playersHandsValues, $totBank, $insatser, int $value): array
    {
        $messages = [];
        foreach ($playersHandsValues as $i => $pHandValue) {

            //kolla om någon har blivit tjock
            if ($pHandValue > 21) {
                //spelaren förlorar oavsätt 0 * insats
                $insatser[$i + 1] = 0;
                $messages[$i] = "Handen är tjock.";
                continue;
            }

            if ($totBank > 21) {
                //spelaren får 2 * insats
                $insatser[$i + 1] *= 2;
                $messages[$i] = "Dealern är tjock.";
                $value += $insatser[$i + 1];
                continue;
            }

            if ($pHandValue === $totBank) {
                //om seplaren och banken har samma får handen tillbaka insatsen. 1 * insats
                $insatser[$i + 1] *= 1;
                $messages[$i] = "Handen och dealern har samma värde.";
                $value += $insatser[$i + 1];
                continue;
            }

            if ($pHandValue < $totBank) {
                //spelaren har mindre än dealer och förlorar sin insats. 0 * insats
                $insatser[$i + 1] = 0;
                $messages[$i] = "Handen är under dealerns värde.";
                continue;
            }

            if ($pHandValue > $totBank) {
                //spelaren har mer än baken och vinner. 2 * insats
                $insatser[$i + 1] *= 2;
                $messages[$i] = "Handen's värde är större.";
                $value += $insatser[$i + 1];
                continue;
            }

            //Blackjack
            if ($pHandValue === 21) {
                //om baken också får black jack
                if ($totBank === 21) {
                    //spelaren får tillbaka sin insats
                    $insatser[$i + 1] *= 1;
                    $messages[$i] = "Handen och Dealern har båda två Black Jack.";
                    $value += $insatser[$i + 1];
                    continue;
                }
                //spelaren får 1,5 * insats
                $insatser[$i + 1] *= 1.5;
                $messages[$i] = "Handen är Black Jack!";
                $value += $insatser[$i + 1];
                continue;
            }
        }
        return [$messages, $value, $insatser];
    }


    /**
     * Delete the data in session
     *
     * @param SessionInterface $session The session interface to delete the data in session.
     * @return Response
     */
    #[Route("/session/delete/list", name: "session_delete_list")]
    public function deleteSession(SessionInterface $session): Response
    {
        //ta och rensa nuvarande session från en lista av saker. listan:
        $sessionVar = [
            'theDeck',
            'stoped',
            'numberOfHands',
            'insatser',
            'players',
            'playersHandsString',
            'playersHandsValues',
            'bankHandString',
            'totBank',
            'messages',
            'username'
            ];

        foreach ($sessionVar as $var) {
            if ($session->has($var)) {
                $session->remove($var);
            }
        }

        return $this->redirectToRoute('session_view');
    }

}
