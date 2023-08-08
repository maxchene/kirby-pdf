<?php

namespace Maxchene\KirbyPdf\Engine;

use Kirby\Exception\Exception;
use Maxchene\KirbyPdf\KirbyPdf;

class WkHtmlToPdfEngine extends AsbtractPdfEngine
{

    protected string $_binary = 'wkhtmltopdf';

    public function __construct(KirbyPdf $pdf)
    {
        parent::__construct($pdf);
    }

    /**
     * @throws Exception
     */
    public function output(): string
    {
        $command = $this->getCommand();
        $content = $this->exec($command, $this->pdf->getHtml());

        if (!empty($content['stdout'])) {
            return $content['stdout'];
        }

        if (!empty($content['stderr'])) {
            throw new Exception(sprintf(
                'System error "%s" when executing command "%s". ' .
                'Try using the binary/package provided on http://wkhtmltopdf.org/downloads.html',
                $content['stderr'],
                $command
            ));
        }

        throw new Exception("WKHTMLTOPDF didn't return any data");

    }

    protected function exec(string $cmd, string $htmlContent): array
    {
        $result = ['stdout' => '', 'stderr' => '', 'return' => ''];
        $proc = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, null);
        fwrite($pipes[0], $htmlContent);
        fclose($pipes[0]);

        $result['stdout'] = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $result['stderr'] = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $result['return'] = proc_close($proc);

        return $result;
    }

    protected function getCommand(): string
    {
        $binary = $this->_binary;
        $command = $binary;
        $command .= ' - -';
        return $command;
    }

}
