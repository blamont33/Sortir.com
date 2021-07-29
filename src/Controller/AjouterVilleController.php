<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjouterVilleController extends AbstractController
{
    #[Route('/ajouterVille', name: 'ajouter_ville')]
    public function ajouterVille(EntityManagerInterface $em, Request $request): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid())
        {
            $em->persist($ville);
            $em->flush();

            $this->addFlash('success', 'Une nouvelle ville a été ajoutée!');
            return $this->redirectToRoute('create_sortie');
        }


        return $this->render('ajouter_ville/ajouterVille.html.twig', ['villeForm'=>$villeForm->createView()]);
    }
}
