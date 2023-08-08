<?php

namespace Maxchene\KirbyPdf;

use Kirby\Cms\Page;
use Kirby\Template\Template;
use Maxchene\KirbyPdf\Engine\AsbtractPdfEngine;
use Maxchene\KirbyPdf\Engine\WkHtmlToPdfEngine;

class KirbyPdf
{

    protected string $engineClass = '\Maxchene\KirbyPdf\Engine\WkHtmlToPdfEngine';

    protected AsbtractPdfEngine $engine;

    protected string $pageSize = 'A4';

    protected string $orientation = 'portrait';

    protected string|int|null $marginBottom = null;

    protected string|int|null $marginLeft = null;

    protected string|int|null $marginRight = null;

    protected string|int|null $marginTop = null;

    protected string $html = '';

    protected Page $page;

    public function __construct()
    {
        $engine = option('maxchene.kirbypdf.engine');
        if ($engine !== null) {


        }
        $this->setEngine(new WkHtmlToPdfEngine($this));

    }

    public function output(): string
    {
        return $this->getEngine()->output();
    }

    /**
     * @param string $html
     * @return KirbyPdf
     */
    public function setHtml(string $html): KirbyPdf
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @param AsbtractPdfEngine $engine
     * @return KirbyPdf
     */
    public function setEngine(AsbtractPdfEngine $engine): KirbyPdf
    {
        $this->engine = $engine;
        return $this;
    }

    /**
     * @return AsbtractPdfEngine
     */
    public function getEngine(): AsbtractPdfEngine
    {
        return $this->engine;
    }

    /**
     * @param Page $page
     * @return KirbyPdf
     */
    public function setPage(Page $page): KirbyPdf
    {
        $this->page = $page;
        $currentTemplate = $page->template()->name();
        $pdfTemplate = new Template('pdf/' . $currentTemplate);

        if ($pdfTemplate->exists()) {
            $page = $page->clone(['template' => $pdfTemplate->name()]);
        }

        $this->setHtml($page->render());
        return $this;
    }

}
