<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Sortie;

use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\service\MajEtats;
use App\service\MajEtatsInterface;
use Doctrine\ORM\EntityManagerInterface;
use Mobile_Detect;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'main_accueil')]
    public function index(Request $request, CampusRepository $campusRepository, SortieRepository $sortieRepository,
                          EntityManagerInterface $em, EtatRepository $etatRepo, MajEtatsInterface $majEtats): Response
    {
        $detect = new Mobile_Detect;
        if($detect->isMobile() && !$detect->isTablet())
        {
            return $this->redirectToRoute('mobile_accueil');
        }

        $this->denyAccessUnlessGranted("ROLE_USER");

        //On récupère les filtres
        $filtre = $request->get("campus");
        $contient = $request->get("contient");
        $debut = $request->get("debut");
        $fin = $request->get("fin");
        $organisateur = $request->get("organisateur");
        $inscrit = $request->get("inscrit");
        $pas_inscrit = $request->get("pas_inscrit");
        $passe = $request->get("passe");

        $sortie = $sortieRepository->findSortie($filtre, $contient, $debut, $fin, $organisateur, $inscrit, $pas_inscrit, $passe);

        $campus = $campusRepository->findAll();

        $majEtats->MajEtats($sortie);

        //On vérifie si on a une requête Ajax
        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('main/content.html.twig', ['sortie' => $sortie])
            ]);
        }

        return $this->render('main/accueil.html.twig', ['campus' => $campus, 'sortie' => $sortie]);
    }

}
