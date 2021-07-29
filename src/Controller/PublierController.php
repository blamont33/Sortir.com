<?php

namespace App\Controller;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublierController extends AbstractController
{
    #[Route('/publier/{id}', name: 'publier')]
    public function publier($id, SortieRepository $sortieRepo, EntityManagerInterface $em, EtatRepository $etatRepo): Response
    {
        $sortie = $sortieRepo->find($id);

        if (!$sortie) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $etat = $etatRepo->find(2);

        $sortie->setEtat($etat);
        $em->flush();

        return $this->redirectToRoute('main_accueil');
    }
}
