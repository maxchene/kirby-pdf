<?php

namespace Maxchene\KirbyPdf\Engine;

use Maxchene\KirbyPdf\KirbyPdf;

abstract class AsbtractPdfEngine
{

    protected KirbyPdf $pdf;

    public function __construct(KirbyPdf $pdf)
    {
        $this->pdf = $pdf;
    }

    abstract public function output(): string;

}
