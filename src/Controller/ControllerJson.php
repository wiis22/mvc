<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerJson
{
    #[Route("/api")]
    public function jsonApi(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'api-quote' => "Kommer ge dig en quote",
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote")]
    public function jsonApier(): Response
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
}