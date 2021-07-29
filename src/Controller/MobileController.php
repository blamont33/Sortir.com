<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MobileController extends AbstractController
{
    #[Route('/mobile/accueil', name: 'mobile_accueil')]
    public function accueilMobile(SortieRepository $sortieRepo): Response
    {

        $sorties = $sortieRepo->findAll();

        return $this->render('mobile/accueil.html.twig', ['sorties'=>$sorties]);
    }

    #[Route('/mobile/detail/{id}', name: 'mobile_detail')]
    public function detailMobile($id, SortieRepository $sortieRepo)
    {
        $sorties = $sortieRepo->find($id);

        return $this->render('mobile/detail.html.twig', ['sorties'=>$sorties]);
    }

}
