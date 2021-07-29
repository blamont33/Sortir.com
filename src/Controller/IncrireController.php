<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class IncrireController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/incrire/{id}', name: 'incrire')]
    public function inscrire($id, ParticipantRepository $participantRepo, Request $request, SortieRepository $sortieRepo, EntityManagerInterface $em): Response
    {
        $user = $this->security->getUser();

        $participant = $participantRepo->find($user);

        $sortie = $sortieRepo->find($id);
        $sortie->addParticipant($participant);

        $em->persist($sortie);
        $em->flush();

        return $this->redirectToRoute('main_accueil');
    }
}
