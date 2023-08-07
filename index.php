<?php
@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App;
use Kirby\Cms\Response;
use Kirby\Template\Template;
use Maxchene\KirbyPdf\Engine\WkHtmlToPdfEngine;


App::plugin('maxchene/kirby-pdf', [
    'options' => [
        'engine' => 'WkHtmlToPdf',
        'margin' => [
            'bottom' => 10,
            'left' => 10,
            'right' => 10,
            'top' => 10,
        ],
        'orientation' => 'portrait'
    ],
    'routes' => [
        [
            'pattern' => '(:all).pdf',
            'action' => function (string $all) {
                $page = page($all);
                $currentTemplate = $page->template()->name();
                $pdfTemplate = new Template('pdf/' . $currentTemplate);

                if ($pdfTemplate->exists()) {
                    $page = $page->clone(['template' => $pdfTemplate->name()]);
                }

                $html = $page->render();
                $pdf = new WkHtmlToPdfEngine();
                $output = $pdf->output($html);
                return new Response($output, 'application/pdf');
            }
        ]
    ],
    'pageMethods' => [
        'pdfUrl' => function () {
            return $this->url() . '.pdf';
        }
    ]

]);
