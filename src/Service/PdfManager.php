<?php

// src/Service/FileUploader.php
namespace App\Service;


use App\Entity\Incident;
use Pontedilana\PhpWeasyPrint\Pdf;
use Pontedilana\WeasyprintBundle\WeasyPrint\Response\PdfResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Twig\Environment;


class PdfManager{
    public function __construct(
        private readonly Environment $twig,
        private readonly Pdf $weasyPrint,
    ) {
    }

    public function renderPDFLive(Incident $incident, string $templateName, array $templateParams){
        
        $html= $this->getHtmlTemlpateForPDF($templateName,$templateParams);
        $pdfContent =$this->weasyPrint->getOutputFromHtml($html);

        //Renderizar pdf online
        return new PdfResponse(
            content: $pdfContent,
            fileName: 'Incident_' . $incident->getAssigned() . '.pdf',
            contentType: 'application/pdf',
            contentDisposition: ResponseHeaderBag::DISPOSITION_INLINE,
            // or download the file instead of displaying it in the browser with
            // contentDisposition: ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            status: 200,
            headers: []
        );
    }

    public function savePDFLocal(string $templateName, array $templateParams, string $localPath){

        $html = $this->getHtmlTemlpateForPDF($templateName, $templateParams);
        $this->weasyPrint->generateFromHtml($html, $localPath, [], true);
    }

    private function getHtmlTemlpateForPDF( string $templateName, array $templateParams){

        return $this->twig->render($templateName,$templateParams);
    }
}