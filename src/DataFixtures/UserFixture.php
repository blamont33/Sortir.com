<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Repository\CampusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
     private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }
    public function load(ObjectManager $manager)
    {
        $tableau = array('Nantes', 'Niort', 'Rennes', 'Quimper');
        foreach ($tableau as $ville) {
            $campus = new Campus();
            $campus->setNomCampus($ville);
            $manager->persist($campus);
        }


        for ($i = 0; $i < 20; $i++) {
            $participant = new Participant();
            $participant->setEmail('user'.$i.'@mail.fr');
            $participant->setPassword($this->passwordEncoder->encodePassword($participant,'password'));
            $participant->setPseudo('pseudo'.$i);
            $participant->setNom('nom'.$i);
            $participant->setPrenom('prenom'.$i);
            $participant->setTelephone(mt_rand(1000000000,9999999999));
            $participant->setActif(1);
            $participant->setAdministrateur(0);
            $participant->setImageName('profil_vide.jpg');

            $manager->persist($participant);
        }




        $manager->flush();
    }
}
