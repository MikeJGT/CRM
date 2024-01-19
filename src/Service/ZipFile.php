<?php

namespace App\Service;

use Symfony\Component\Routing\Annotation\Route;
use ZipArchive;

class ZipFile
{
    /**
     * @Route("/create-zip", name="create_zip")
     */
    // public function createZip()
    // {
    //     $zipFileName = 'example.zip';
    //     $zipPath = $this->generateZip($zipFileName);

    //     return new BinaryFileResponse($zipPath);
    // }

    public function generateZip($files)
    {
        $zip = new ZipArchive();
        $zipPath = sys_get_temp_dir() . '/incident.zip';

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add files to the ZIP archive
            foreach($files as $file){
                $zip->addFile($file);
                
            }
            $file1 = '/path/to/file1.txt';
            $file2 = '/path/to/file2.txt';

            $zip->close();

            return $zipPath;
        } else {
            return null; // Error handling
        }
    }
}
