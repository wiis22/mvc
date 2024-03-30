<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ControllerTwig extends AbstractController
{
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/report#kmom01", name: "report_kmom01")]
    public function reportKmom01(): Response
    {
        return $this->redirectToRoute('report') . '#kmom01';
    }

    // #[Route("/api", name: "api")]
    // public function api(): Response
    // {

    //     $jsonRoutes = [
    //         "api/quote" => "Kommer ge dig en quote"
    //     ];

    //     return $this->render('apier.html.twig', $jsonRoutes);
    // }

    #[Route("/lucky", name: "lucky_number")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
}