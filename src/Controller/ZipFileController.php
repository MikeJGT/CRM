<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
// use App\Service\ZipFile;
Use App\Entity\Incident;
use ZipArchive;

//Incorporar servicio ZipFile
class ZipFileController extends AbstractController
{
    #[Route('/zip/file', name: 'app_zip_file')]
    public function index(): Response
    {
        return $this->render('zip_file/index.html.twig', [
            'controller_name' => 'ZipFileController',
        ]);
    }


    #[Route('/zip/{id}', name: 'zip')]
    public function createZip( Incident $incidents)
    {   
        $fileName='uploads/brochures/'.$incidents->getBrochureFilename();
        $pdf='incident.pdf';

        $files=[
            $fileName,
            $pdf
        ];
        $zipPath =$this->generatezip($files);


        return new BinaryFileResponse($zipPath);
    }


    //Borrar esto
    private function generateZip($files)
    {
        $zip = new ZipArchive();
        $zipPath = sys_get_temp_dir() . '/incident.zip';

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add files to the ZIP archive

            foreach($files as $file){
                //Check filename output
                $zip->addFile($file);
                
            }

            $zip->close();

            return $zipPath;
        } else {
            return null; // Error handling
        }
    }
}
