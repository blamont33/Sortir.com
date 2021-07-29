<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\ModifSortieType;
use App\Form\SortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ManageSortieController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/create/sortie', name: 'create_sortie')]
    public function create_sortie(Request $request, EntityManagerInterface $em,
                                  ParticipantRepository $participantRepo, CampusRepository $campusRepo,
                                  EtatRepository $etatRepo): Response
    {
        $user = $this->security->getUser()->getUsername();
        $client = HttpClient::create();
        $url = "http://localhost/sortir.com/public/api/lieu/2";
        $response = $client->request('GET', $url);
        $lieu = $response->toArray();

        $url = "http://localhost/sortir.com/public/api/lieux/2";
        $response = $client->request('GET', $url);
        $lieux = $response->toArray();

        $campus = $campusRepo->FindCampus($user);

        $sortie = new Sortie;
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $organisateur = $participantRepo->find($user);
            $sortie->setOrganisateur($organisateur);
            $sortie->setCampus($campus[0]);

            if ($form->get('enregistrer')->isClicked()) {
                $etat = $etatRepo->find(1);
                $sortie->setEtat($etat);
            } elseif ($form->get('publier')->isClicked()) {
                $etat = $etatRepo->find(2);
                $sortie->setEtat($etat);
            }
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('main_accueil');
        }
        return $this->render('create_sortie/creerSortie.html.twig', ['form' => $form->createView(), 'campus' => $campus, 'sortie'=>$sortie, 'lieu'=>$lieu, "lieux"=>$lieux]);
    }

    #[Route('/update/sortie/{id}', name: 'update_sortie')]
    function update($id, Request $request, EntityManagerInterface $em,SortieRepository $sortieRepo,
                    ParticipantRepository $participantRepo, CampusRepository $campusRepo, EtatRepository $etatRepo): Response
    {
        $user = $this->security->getUser()->getUsername();
        $client = HttpClient::create();
        $url = "http://localhost/sortir.com/public/api/lieu/2";
        $response = $client->request('GET', $url);
        $lieu = $response->toArray();

        $url = "http://localhost/sortir.com/public/api/lieux/2";
        $response = $client->request('GET', $url);
        $lieux = $response->toArray();

        $campus = $campusRepo->FindCampus($user);

        $sortie = $sortieRepo->find($id);
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $organisateur = $participantRepo->find($user);
            $sortie->setOrganisateur($organisateur);
            $sortie->setCampus($campus[0]);

            if ($form->get('enregistrer')->isClicked()) {
                $etat = $etatRepo->find(1);
                $sortie->setEtat($etat);
            } elseif ($form->get('publier')->isClicked()) {
                $etat = $etatRepo->find(2);
                $sortie->setEtat($etat);
            }
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('main_accueil');
        }
        return $this->render('create_sortie/modifierSortie.html.twig', ['form' => $form->createView(), 'campus' => $campus, 'sortie'=>$sortie, 'lieu'=>$lieu, "lieux"=>$lieux]);
    }

    #[Route('/supprimer/sortie/{id}', name: 'supprimer_sortie')]
    function supprimer(SortieRepository $sortieRepo, $id, EntityManagerInterface $em)
    {
        $sortie = $sortieRepo->find($id);
        if (!$sortie) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        $em->remove($sortie);
        $em->flush();

        return $this->redirectToRoute('main_accueil');
    }
}
