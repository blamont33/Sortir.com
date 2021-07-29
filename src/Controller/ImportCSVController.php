<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\service\ImportCSV;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportCSVController extends AbstractController
{
    #[Route('/importCsv', name: 'importCsv')]
    public function import(ImportCSV $importCSV, EntityManagerInterface $em, Request $request): Response
    {
        $file = new Media();
        $fileForm = $this->createForm(MediaType::class, $file);
        $fileForm->handleRequest($request);

        if($fileForm->isSubmitted() && $fileForm->isValid())
        {
            $em->persist($file);
            $em->flush();

            $name = $file->getImageName();
            $importCSV->importerCSV($name);

            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('import_csv/importCsv.html.twig', ['fileForm'=>$fileForm->createView()]);
    }
}
