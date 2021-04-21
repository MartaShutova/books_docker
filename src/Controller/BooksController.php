<?php

namespace App\Controller;

use App\Entity\Books;
use App\Form\BooksFormType;
use App\Repository\BooksRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;


class BooksController extends AbstractController
{

    private $booksRepository;

    public function __construct(BooksRepository $booksRepository)
    {
        $this->booksRepository = $booksRepository;
    }

    /**
      * @Route("/", name="homepage")
      */
    public function index(): Response
    {
        return $this->render('books/index.html.twig', [
            'books' => $this->booksRepository->findAll(),
        ]);
    }
}