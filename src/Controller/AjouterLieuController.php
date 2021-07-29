<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjouterLieuController extends AbstractController
{
    #[Route('/ajouterLieu', name: 'ajouter_lieu')]
    public function ajouterLieu(EntityManagerInterface $em, Request $request): Response
    {
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if ($lieuForm->isSubmitted() && $lieuForm->isValid())
        {
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', 'Un nouveau lieu a été ajouté!');
            return $this->redirectToRoute('create_sortie');
        }


        return $this->render('ajouter_lieu/ajouterLieu.html.twig', ['lieuForm'=>$lieuForm->createView()]);
    }
}
