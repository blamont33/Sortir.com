<?php

namespace App\Command;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CreateUsersFromCsvCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private string $dataDirectory;
    private SymfonyStyle $io;
    private ParticipantRepository $participantRepository;
    private CampusRepository $campusRepository;

    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory, ParticipantRepository $participantRepository, CampusRepository $campusRepository)
    {
        parent::__construct();
        $this->dataDirectory = $dataDirectory;
        $this->entityManager = $entityManager;
        $this->participantRepository = $participantRepository;
        $this->campusRepository = $campusRepository;
    }

    protected static $defaultName = 'app:create-users-from-file';
    protected static $defaultDescription = 'Add a short description for your command';

    protected function configure()
    {
        $this->setDescription('importer des données en provenance d\'un fichier CSV');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createUsers();
        return Command::SUCCESS;
    }

    private function getDataFromFile(): array
    {
        $file = $this->dataDirectory . 'download.csv';

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

    private function createUsers(): void
    {
        $this->io->section('CREATION DES UTILISATEURS A PARTIR DU FICHIER');

        $usersCreated = 0;

        foreach ($this->getDataFromFile() as $row) {
            if (array_key_exists('email', $row) && !empty($row['email'])) {
                $user = $this->participantRepository->findOneBy([
                    'email' => $row['email']
                ]);

                $campus = $this->campusRepository->find($row['campus']);

                if (!$user) {
                    $participant = new Participant();
                    $participant->setEmail($row['email'])
                        ->setPassword($row['password'])
                        ->setPseudo($row['pseudo'])
                        ->setNom($row['nom'])
                        ->setPrenom($row['prenom'])
                        ->setActif($row['actif'])
                        ->setAdministrateur($row['administrateur'])
                        ->setCampus($campus)
                        ->setTelephone($row['telephone']);


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

        $this->io->success($string);
    }
}
