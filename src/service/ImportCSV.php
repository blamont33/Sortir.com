<?php


namespace App\service;


use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ImportCSV extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private string $dataDirectory;
    private ParticipantRepository $participantRepository;
    private CampusRepository $campusRepository;
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory, ParticipantRepository $participantRepository, CampusRepository $campusRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->dataDirectory = $dataDirectory;
        $this->entityManager = $entityManager;
        $this->participantRepository = $participantRepository;
        $this->campusRepository = $campusRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function importerCSV($name)
    {
        $this->getDataFromFile($name);
        $this->createUsers($name);
    }

    private function getDataFromFile($name): array
    {
        $file = $this->dataDirectory . $name;

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $normalizers = [new ObjectNormalizer()];

        $encoders = [
            new CsvEncoder()
        ];

        $serializer = new Serializer($normalizers, $encoders);

        /** @var string $fileString */
        $fileString = file_get_contents($file);

        $data = $serializer->decode($fileString, $fileExtension);

        if (array_key_exists('results', $data)) {
            return $data['results'];
        }

        return $data;

    }

    private function createUsers($name): void
    {
        $usersCreated = 0;

        foreach ($this->getDataFromFile($name) as $row) {
            if (array_key_exists('email', $row) && !empty($row['email'])) {
                $user = $this->participantRepository->findOneBy([
                    'email' => $row['email']
                ]);

                $campus = $this->campusRepository->find($row['campus']);

                if (!$user) {
                    $participant = new Participant();
                    $participant->setEmail($row['email'])
                        ->setPassword($this->passwordEncoder->encodePassword($participant,$row['password']))
                        ->setPseudo($row['pseudo'])
                        ->setNom($row['nom'])
                        ->setPrenom($row['prenom'])
                        ->setActif($row['actif'])
                        ->setAdministrateur($row['administrateur'])
                        ->setCampus($campus)
                        ->setTelephone($row['telephone'])
                        ->setImageName($row['imageName']);

                    $this->entityManager->persist($participant);
                    $usersCreated++;

                }
            }
        }

        $this->entityManager->flush();

        if ($usersCreated > 1) {
            $string = "{$usersCreated} UTILISATEURS CREES EN BASE DE DONNEE.";
        } elseif ($usersCreated === 1) {
            $string = "1 UTILISATEUR A ETE CREE EN BASE DE DONNEES.";
        } else {
            $string = "AUCUN UTILISATEUR N\'A ETE CREE EN BASE DE DONNEE.";
        }

        $this->addFlash('success', $string);
    }

}