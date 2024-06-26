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
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/create', name: 'add_new_book_form')]
    public function create(): Response
    {
        return $this->render('library/create.html.twig');
    }

    #[Route('/library/add_book', name: 'add_book', methods: ['POST'])]
    public function addBook(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {

        $entityManager = $doctrine->getManager();

        $bookPaths = array("img/bokbild1.png", "img/bokbild2.png", "img/bokbild3.png");

        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');
        $imgpath = $bookPaths[rand(0, 2)];

        $book = new Library();
        $book->setTitel("$title");
        $book->setIsbn("$isbn");
        $book->setAuthor("$author");
        $book->setPicture($imgpath);

        $entityManager->persist($book);

        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Boken finns nu i bibloteket!'
        );
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/books', name: 'show_books')]
    public function showAll(LibraryRepository $libraryRepository): Response
    {
        $books = $libraryRepository
            ->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('library/view_all.html.twig', $data);
    }

    #[Route('/library/book/{id}', name: 'book')]
    public function showId(LibraryRepository $libraryRepository, int $id): Response
    {
        $book = $libraryRepository
            ->find($id);

        $data = [
            'book' => $book
        ];

        return $this->render('library/view_book.html.twig', $data);
    }

    #[Route('/library/update/{id}', name: 'update_book_form')]
    public function update(LibraryRepository $libraryRepository, int $id): Response
    {
        $book = $libraryRepository
        ->find($id);

        $data = [
            'book' => $book
        ];
        return $this->render('library/update.html.twig', $data);
    }

    #[Route('/library/update_book/{id}', name: 'update_book', methods: ['POST'])]
    public function updateBook(
        Request $request,
        ManagerRegistry $doctrine,
        int $id
    ): Response {

        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $title = $request->request->get('title2');
        $isbn = $request->request->get('isbn2');
        $author = $request->request->get('author2');

        $book->setTitel("$title");
        $book->setIsbn("$isbn");
        $book->setAuthor("$author");


        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Boken är nu uppdaterad!'
        );
        return $this->redirectToRoute('show_books');
    }

    #[Route('/library/delete/{id}', name: 'book_delete_by_id')]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Library::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();
        $this->addFlash(
            'warning',
            'Boken är nu borttagen!'
        );
        return $this->redirectToRoute('show_books');
    }

}
