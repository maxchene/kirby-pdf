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

    protected string|int|null $marginBottom = 0;

    protected string|int|null $marginLeft = 0;

    protected string|int|null $marginRight = 0;

    protected string|int|null $marginTop = 0;

    protected string $html = '';

    protected string $title = 'coucou les gens';

    protected Page $page;

    protected int|null $delay = null;

    public function __construct()
    {
        $this->buildOptions();
    }

    protected function buildOptions(): void
    {
        $this->setOrientation(option('maxchene.kirby-pdf.orientation'));
        $this->setMarginBottom(option('maxchene.kirby-pdf.margin.bottom'));
        $this->setMarginLeft(option('maxchene.kirby-pdf.margin.left'));
        $this->setMarginRight(option('maxchene.kirby-pdf.margin.right'));
        $this->setMarginTop(option('maxchene.kirby-pdf.margin.top'));


        $engineName = option('maxchene.kirby-pdf.engine');
        $engineClass = sprintf("\Maxchene\KirbyPdf\Engine\%sEngine", $engineName);
        $engine = new $engineClass($this);
        $this->setEngine($engine);
    }

    /**
     * @param string $orientation
     * @return KirbyPdf
     */
    public function setOrientation(string $orientation): KirbyPdf
    {
        $this->orientation = $orientation;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrientation(): string
    {
        return $this->orientation;
    }

    /**
     * @param string $title
     * @return KirbyPdf
     */
    public function setTitle(string $title): KirbyPdf
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $pageSize
     * @return KirbyPdf
     */
    public function setPageSize(string $pageSize): KirbyPdf
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageSize(): string
    {
        return $this->pageSize;
    }

    /**
     * @param int|string|null $marginBottom
     * @return KirbyPdf
     */
    public function setMarginBottom(int|string|null $marginBottom): KirbyPdf
    {
        $this->marginBottom = $marginBottom;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getMarginBottom(): int|string|null
    {
        return $this->marginBottom;
    }

    /**
     * @param int|string|null $marginLeft
     * @return KirbyPdf
     */
    public function setMarginLeft(int|string|null $marginLeft): KirbyPdf
    {
        $this->marginLeft = $marginLeft;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getMarginLeft(): int|string|null
    {
        return $this->marginLeft;
    }

    /**
     * @return int|string|null
     */
    public function getMarginRight(): int|string|null
    {
        return $this->marginRight;
    }

    /**
     * @param int|string|null $marginRight
     */
    public function setMarginRight(int|string|null $marginRight): KirbyPdf
    {
        $this->marginRight = $marginRight;
        return $this;
    }

    /**
     * @return int|string|null
     */
    public function getMarginTop(): int|string|null
    {
        return $this->marginTop;
    }

    /**
     * @param int|string|null $marginTop
     * @return KirbyPdf
     */
    public function setMarginTop(int|string|null $marginTop): KirbyPdf
    {
        $this->marginTop = $marginTop;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDelay(): ?int
    {
        return $this->delay;
    }

    /**
     * @param int|null $delay
     * @return KirbyPdf
     */
    public function setDelay(?int $delay): KirbyPdf
    {
        $this->delay = $delay;
        return $this;
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
        $this->setTitle($page->title()->value());
        return $this;
    }

}
