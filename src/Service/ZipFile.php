<?php

namespace App\Service;

use App\Entity\Incident;
use Twig\Environment;
use Pontedilana\PhpWeasyPrint\Pdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use ZipArchive;

class ZipFile
{
    public function __construct(
        private readonly Environment $twig,
        private readonly Pdf $weasyPrint,
    ) {
    }

       public function generateZip(Incident $incidents, $files)
    {
        $zip = new ZipArchive();
        $zipPath = sys_get_temp_dir() . '/incident.zip';

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add files to the ZIP archive

            foreach($files as $file){
                //Check filename output
                if($file){
                    $zip->addFile($file, basename($file));
                }
            }

            $zip->close();
            $response=new BinaryFileResponse($zipPath);
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_INLINE,
                $incidents->getAssigned() . '.zip'
            );
            return $response;
        } else {
            return null; // Error handling
        }
    }
    
}
