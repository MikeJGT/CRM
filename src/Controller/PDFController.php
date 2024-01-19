<?php

namespace App\Controller;

use App\Entity\Incident;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pontedilana\PhpWeasyPrint\Pdf;
use Pontedilana\WeasyprintBundle\WeasyPrint\Response\PdfResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Twig\Environment;

//Dividir funcionalidad en servicio PDF
class PDFController extends AbstractController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly Pdf $weasyPrint,
    ) {
    }

    #[Route('/pdf/{id}', name: 'pdf')]
    public function pdf(Incident $incident): Response
    {
        $html = $this->twig->render('pdf/index.html.twig',[
            'incident' => $incident
        ]);
        $pdfContent =$this->weasyPrint->getOutputFromHtml($html);
        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/incident.pdf';

        //Guardar en /public
        $this->weasyPrint->generateFromHtml($html, $pdfPath, [], true);

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
}