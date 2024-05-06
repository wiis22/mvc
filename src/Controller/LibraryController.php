<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\LibraryRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index( LibraryRepository $libraryRepository ): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/create', name: 'add_new_book_form')]
    public function create( LibraryRepository $libraryRepository ): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/add_book', name: 'add_book', methods: ['POST'])]
    public function addBook( Request $request, LibraryRepository $libraryRepository ): Response
    {
        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');
        $imgfile = $request->request->get('img');

        var_dump($title);
        var_dump($isbn);
        var_dump($author);
        var_dump($imgfile);

        $this->addFlash(
            'notice',
            'Boken finns nu i bibloteket!'
        );
        return $this->render('library/create.html.twig');
    }

    #[Route('/library', name: 'show_books')]
    public function showAll( LibraryRepository $libraryRepository ): Response
    {
        return $this->render('library/index.html.twig');
    }
}
