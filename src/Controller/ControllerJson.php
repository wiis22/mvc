<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

class ControllerJson extends AbstractController
{
    #[Route("/api", name: "apier")]
    public function apier(): Response
    {
        // $number = random_int(0, 100);

        $data = [
            'api/quote' => ['description' => "Kommer ge dig en quote", 'method' => "GET"],
            'api/deck' => ['description' => "Kommer ge dig en kortlek", 'method' => "GET"],
            'api/game' => ['description' => "Kommer ge dig ställningen för tillfället i game 21", 'method' => "GET"],
            'api/deck/shuffle' => ['description' => "Kommer att shuffla kortleken", 'method' => "POST"],
            'api/deck/draw' => ['description' => "drar 1 kort", 'method' => "POST"] ,
            'api/deck/draw/{number}' => ['description' => "drar 1 eller :number antal kort", 'method' => "POST"],
            'api/library/books' => ['description' => "Kommer visa vilka böcker som finns i bibloteket", 'method' => "GET"],
            'api/library/book/{isbn}' => ['description' => "Kommer visa boken som tillhör det ISBN nummret", 'method' => "POST"],
        ];

        // return new JsonResponse($data);

        // $response = new JsonResponse($data);
        // $response->setEncodingOptions(
        //     $response->getEncodingOptions() | JSON_PRETTY_PRINT
        // );
        return $this->render('api.html.twig', ["data" => $data]);
    }

    #[Route("/api/quote", name: "quote")]
    public function quote(): Response
    {
        $quotes = [
            "Frankly, my dear, I don't give a damn",
            "Three can keep a secret, if two of them are dead.",
            "He travels the fastest who travels alone."
        ];

        $randomI = array_rand($quotes);
        $randomQuote = $quotes[$randomI];
        $date = date("Y-m-d");
        $timestamp = time();

        $data = [
            'quote' => $randomQuote,
            'date' => $date,
            'timestamp' => $timestamp
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/game", name: "game")]
    public function game(SessionInterface $session): Response
    {

        $bankTot = $session->get("bank_tot");
        $playerTot = $session->get("player_tot");

        $data = [
            'Player_total_points' => $playerTot,
            'Bank_total_points' => $bankTot
        ];


        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/books", name: "all_books")]
    public function allBooks(LibraryRepository $libraryRepository): Response
    {

        $books = $libraryRepository
            ->findAll();

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/library/book/{isbn}", methods: ["POST"])]
    public function findBookByIsbn(LibraryRepository $libraryRepository, Request $request): Response
    {
        $isbn = $request->request->getInt('isbn', 0);

        $book = $libraryRepository
            ->findByIsbn($isbn);

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
