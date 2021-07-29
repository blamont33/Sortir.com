<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\NewParticipantType;
use App\Form\VilleType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdministrateurController extends AbstractController
{
    #[Route('/administrateur/nouveau', name: 'administrateur_nouveau')]
    public function nouveau(EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $encoder): Response
    {

        $participant = new Participant();
        $participantForm = $this->createForm(NewParticipantType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            $hashed = $encoder->encodePassword($participant, $participant->getPassword());
            $participant->setPassword($hashed);
            $participant->setImageName('profil_vide.jpg');

            $em->persist($participant);
            $em->flush();

            $this->addFlash('success', 'Le participant a bien été ajoutée!');
            return $this->redirectToRoute('administrateur_nouveau');
        }

        return $this->render('administrateur/nouveauParticipant.html.twig', ['participantForm' => $participantForm->createView()]);
    }

    #[Route('/administrateur/listeParticipant', name: 'administrateur_listeParticipant')]
    public function listeParticipant(ParticipantRepository $participantRepo)
    {
        $participants = $participantRepo->findAll();

        return $this->render('administrateur/listeParticipant.html.twig', ['participant' => $participants]);
    }

    #[Route('/administrateur/supprimer/{id}', name: 'administrateur_supprimer')]
    public function supprimer($id, ParticipantRepository $participantRepo, EntityManagerInterface $em)
    {
        $participant = $participantRepo->find($id);

        if (!$participant) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($participant);
        $em->flush();

        $this->addFlash('success', 'Le participant a bien été supprimé!');
        return $this->redirectToRoute('administrateur_listeParticipant');

    }

    #[Route('/administrateur/inactif/{id}', name: 'administrateur_inactif')]
    public function inactif($id, ParticipantRepository $participantRepo, EntityManagerInterface $em)
    {
        $participant = $participantRepo->find($id);

        if (!$participant) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        if ($participant->getActif() == true) {
            $participant->setActif(false);
            $em->flush();
            $this->addFlash('success', 'Le participant a bien été rendu inactif!');
        } else {
            $participant->setActif(true);
            $em->flush();
            $this->addFlash('success', 'Le participant a bien été rendu actif!');
        }

        return $this->redirectToRoute('administrateur_listeParticipant');

    }

    #[Route('administrateur/ville', name: 'administrateur_ville')]
    public function ville(VilleRepository $villeRepo, Request $request, EntityManagerInterface $em)
    {
        //On récupère les filtres
        $filtre = $request->get("nomVille");

        $villes = $villeRepo->findVille($filtre);

        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid())
        {
            $em->persist($ville);
            $em->flush();

            return $this->redirectToRoute('administrateur_ville');
        }

        //On vérifie si on a une requête Ajax
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('administrateur/ville_content.html.twig', ['ville'=>$villes,'villeForm'=>$villeForm->createView() ])
            ]);
        }

        return $this->render('administrateur/ville.html.twig', ['ville'=>$villes, 'villeForm'=>$villeForm->createView()]);
    }

    #[Route('/administrateur/supprimer/ville/{id}', name: 'administrateur_supprimer_ville')]
    public function supprimerVille($id, VilleRepository $villeRepo, EntityManagerInterface $em)
    {
        $ville = $villeRepo->find($id);

        if (!$ville) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($ville);
        $em->flush();

        $this->addFlash('success', 'La ville a bien été supprimée!');
        return $this->redirectToRoute('administrateur_ville');

    }

    #[Route('/administrateur/modifier/ville/{id}', name: 'administrateur_modifier_ville')]
    public function modifierVille($id, VilleRepository $villeRepo, EntityManagerInterface $em, Request $request)
    {
        $ville = $villeRepo->find($id);

        if (!$ville) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if($villeForm->isSubmitted() && $villeForm->isValid())
        {
        $em->persist($ville);
        $em->flush();

        $this->addFlash('success', 'La ville a bien été modifiée!');
        return $this->redirectToRoute('administrateur_ville');
        }

        return $this->render('administrateur/modifier_ville.html.twig', ['form'=>$villeForm->createView()]);    }

    #[Route('administrateur/campus', name: 'administrateur_campus')]
    public function campus(CampusRepository $campusRepo, Request $request, EntityManagerInterface $em)
    {
        //On récupère les filtres
        $filtre = $request->get("nomCampus");

        $campuss = $campusRepo->findCampuss($filtre);

        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid())
        {
            $em->persist($campus);
            $em->flush();

            return $this->redirectToRoute('administrateur_campus');
        }

        //On vérifie si on a une requête Ajax
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('administrateur/campus_content.html.twig', ['campus'=>$campuss,'campusForm'=>$campusForm->createView() ])
            ]);
        }

        return $this->render('administrateur/campus.html.twig', ['campus'=>$campuss, 'campusForm'=>$campusForm->createView()]);
    }

    #[Route('/administrateur/supprimer/campus/{id}', name: 'administrateur_supprimer_campus')]
    public function supprimerCampus($id, CampusRepository $campusRepo, EntityManagerInterface $em)
    {
        $campus = $campusRepo->find($id);

        if (!$campus) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $em->remove($campus);
        $em->flush();

        $this->addFlash('success', 'Le campus a bien été supprimé!');
        return $this->redirectToRoute('administrateur_campus');

    }

    #[Route('/administrateur/modifier/campus/{id}', name: 'administrateur_modifier_campus')]
    public function modifierCampus($id, CampusRepository $campusRepo, EntityManagerInterface $em, Request $request)
    {
        $campus = $campusRepo->find($id);

        if (!$campus) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid())
        {
            $em->persist($campus);
            $em->flush();

            $this->addFlash('success', 'Le campus a bien été modifié!');
            return $this->redirectToRoute('administrateur_campus');
        }

        return $this->render('administrateur/modifier_campus.html.twig', ['form'=>$campusForm->createView()]);    }
}
