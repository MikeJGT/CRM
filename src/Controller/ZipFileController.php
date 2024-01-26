<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ZipFile;
Use App\Entity\Incident;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Pontedilana\PhpWeasyPrint\Pdf;

//Incorporar servicio ZipFile
class ZipFileController extends AbstractController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly Pdf $weasyPrint,
    ) {
    }

    #[Route('/zip/{id}', name: 'zip')]
    public function createZip( Incident $incidents)
    {   
        $fileSystem = new Filesystem();
        $zipAchive=new ZipFile($this->twig, $this->weasyPrint);
        $fileName='uploads/brochures/'.$incidents->getBrochureFilename();

        $brochures=$incidents->getBrochures();
        $files=[];
        foreach($brochures as $brochure){
            $name='uploads/brochures/'.$brochure->getFileName();
            array_push($files, $name);
        }

        //Descarga del pdf del propio incidente en local
        $this->forward('App\Controller\PDFController::savePdfLocal', [
            'id' => $incidents->getId(),
        ]);
        
        $namepdf='incident-'.$incidents->getAssigned().'.pdf';
        array_push($files, $namepdf);

        $response =$zipAchive->generatezip($incidents,$files);
        
        //Elimina el archivo despues de descargar el pdf. 
        $fileSystem->remove('incident-'.$incidents->getAssigned().'.pdf');

        return $response;
    }

}
