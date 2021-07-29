<?php


namespace App\Controller\Api;


use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api")
 */
class ApiSortieController extends AbstractController
{
    #[Route('/lieu/{id}', name: 'lieu', methods: ['GET'])]
    public function lieu($id, LieuRepository $lieuRepo, SerializerInterface $serializer)
    {
        $lieu = $lieuRepo->find($id);

        $json = $serializer->serialize($lieu, 'json', ['groups'=>'infos']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/lieux/{id}', name: 'lieux', methods: ['GET'])]
    public function lieux($id, LieuRepository $lieuRepo, SerializerInterface $serializer)
    {
        $lieux = $lieuRepo->findByVille($id);

        $json = $serializer->serialize($lieux, 'json', ['groups'=>'infos']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/ville/{id}', name: 'ville', methods: ['GET'])]
    public function ville($id, VilleRepository $villeRepo, SerializerInterface $serializer)
    {
        $ville = $villeRepo->find($id);

        $json = $serializer->serialize($ville, 'json', ['groups'=>'ville']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/nom/{nom}', name: 'villes', methods: ['GET'])]
    public function villes($nom, VilleRepository $villeRepo, SerializerInterface $serializer)
    {
        $villes = $villeRepo->findVille($nom);

        $json = $serializer->serialize($villes, 'json', ['groups'=>'ville']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


}