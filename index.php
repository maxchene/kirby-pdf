<?php
@include_once __DIR__ . '/vendor/autoload.php';

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\Response;
use Maxchene\KirbyPdf\Engine\WkHtmlToPdfEngine;


App::plugin('maxchene/kirby-pdf', [
    'routes' => [
        [
            'pattern' => '(:all).pdf',
            'action' => function (string $all) {
                $page = page($all);
                /**
                 * TODO
                 * Check if a specific pdf template is set in
                 * templates/pdf/template-name
                 */
                $clone = $page->clone(['template' => 'pdf']);
                $html = $clone->render();
                $pdf = new WkHtmlToPdfEngine();
                $out = $pdf->output($html);
                return new Response($out, 'application/pdf');
            }
        ]
    ],
    'pageMethods' => [
        'pdfUrl' => function () {
            return $this->url() . '.pdf';
        }
    ]

]);
