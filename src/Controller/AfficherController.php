<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AfficherController extends AbstractController
{
    #[Route('/afficher/{id}', name: 'afficher')]
    public function afficher($id, SortieRepository $sortieRepo): Response
    {
        $sortie = $sortieRepo->find($id);

        if (!$sortie) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('afficher/afficher.html.twig', ['sortie'=>$sortie]);
    }
}
