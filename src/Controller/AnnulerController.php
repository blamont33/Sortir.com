<?php

namespace App\Controller;

use App\Form\AnnulerType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnulerController extends AbstractController
{
    #[Route('/annuler/{id}', name: 'annuler')]
    public function annuler($id, SortieRepository $sortieRepo, EntityManagerInterface $em, EtatRepository $etatRepo, Request $request): Response
    {
        $sortie = $sortieRepo->find($id);

        if (!$sortie) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $annulerForm = $this->createForm(AnnulerType::class, $sortie);
        $annulerForm->handleRequest($request);


        if ($annulerForm->isSubmitted() && $annulerForm->isValid()) {

            $etat = $etatRepo->find(6);

            $sortie->setEtat($etat);
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('main_accueil');

        }

        return $this->render('modifier/annuler.html.twig', ['sortie'=>$sortie, 'annulerForm'=>$annulerForm->createView()]);
    }
}
