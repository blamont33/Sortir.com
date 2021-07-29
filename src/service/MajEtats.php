<?php


namespace App\service;


use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MajEtats implements MajEtatsInterface
{
    private EntityManagerInterface $em;
    private EtatRepository $etatRepo;

    public function __construct(EntityManagerInterface $em, EtatRepository $etatRepo)
    {
        $this->em = $em;
        $this->etatRepo = $etatRepo;
    }

    public function MajEtats($sortie){
        $now = date("Y-m-d");
        $etatAnnulee = $this->etatRepo->findOneBy(array('libelle'=>'Annulée'));
        $etatEnCreation = $this->etatRepo->findOneBy(array('libelle'=>'En création'));
        $etatEnCours = $this->etatRepo->findOneBy(array('libelle'=>'En cours'));
        $etatPassee = $this->etatRepo->findOneBy(array('libelle'=>'Passée'));
        $etatCloturee = $this->etatRepo->findOneBy(array('libelle'=>'Clôturée'));
        $etatOuverte = $this->etatRepo->findOneBy(array('libelle'=>'Ouverte'));
        foreach ($sortie as $value) {
            if ($value->getDateDebut()->format('Y-m-d') == $now && $value->getEtat() != $etatAnnulee) {
                $etat = $etatEnCours;
                $value->setEtat($etat);
                $this->em->persist($value);

            } elseif ($value->getDateDebut()->format('Y-m-d') < $now && $value->getEtat() != $etatAnnulee) {
                $etat = $etatPassee;
                $value->setEtat($etat);
                $this->em->persist($value);

            } elseif ($value->getDateCloture()->format('Y-m-d') < $now && $value->getDateDebut()->format('Y-m-d') > $now && $value->getEtat() != $etatAnnulee ||
                $value->getNbInscriptionMax() == count($value->getParticipants()) && $value->getEtat() != "Annulée") {
                $etat = $etatCloturee;
                $value->setEtat($etat);
                $this->em->persist($value);

            } elseif ($value->getNbInscriptionMax() > count($value->getParticipants()) && $value->getDateCloture()->format('Y-m-d') > $now
                && $value->getEtat() != $etatEnCreation && $value->getEtat() != $etatAnnulee) {
                $etat = $etatOuverte;
                $value->setEtat($etat);
                $this->em->persist($value);
            }
        }
        $this->em->flush();
    }
}