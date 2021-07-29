<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\MonProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class ProfilController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/monProfil', name: 'profil_monProfil')]
    public function update(EntityManagerInterface $em, Request $request, ParticipantRepository $participantRepo,
                           UserPasswordEncoderInterface $encoder): Response
    {
        $idParticipant = $this->security->getUser()->getUsername();

        $participant = $participantRepo->find($idParticipant);

        $userForm = $this->createForm(MonProfilType::class, $participant);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $hashed = $encoder->encodePassword($participant, $participant->getPassword());
            $participant->setPassword($hashed);

            $em->persist($participant);
            $em->flush();

            return $this->redirectToRoute('main_accueil');


        }
        $this->getDoctrine()->getManager()->refresh($participant);


        return $this->render('profil/monProfil.html.twig', ['userForm' => $userForm->createView(), 'idParticipant' => $idParticipant, 'participant' => $participant]);
    }

    #[Route('/autreProfil/{id}', name: 'profil_autreProfil')]
    public function profil($id, EntityManagerInterface $em, Request $request, ParticipantRepository $participantRepo,
                           UserPasswordEncoderInterface $encoder): Response
    {

        $participant = $participantRepo->find($id);

        return $this->render('profil/autreProfil.html.twig', ['participant' => $participant]);
    }
}
