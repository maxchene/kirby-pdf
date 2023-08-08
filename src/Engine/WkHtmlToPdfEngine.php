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

        $options = [
            'quiet' => true,
            'print-media-type' => true,
            'orientation' => $this->pdf->getOrientation(),
            'page-size' => $this->pdf->getPageSize(),
            'encoding' => 'UTF-8',
            'title' => $this->pdf->getTitle(),
            'javascript-delay' => $this->pdf->getDelay(),
            'margin-bottom' => $this->pdf->getMarginBottom(),
            'margin-left' => $this->pdf->getMarginLeft(),
            'margin-right' => $this->pdf->getMarginRight(),
            'margin-top' => $this->pdf->getMarginTop(),
        ];

        $command = $binary;

        foreach ($options as $key => $value) {
            if (empty($value) && $value !== 0) {
                continue;
            }
            $command .= $this->parseOptions($key, $value);
        }

        $command .= ' - -';
        return $command;
    }

    /**
     * @param string $key
     * @param $value
     * @return string
     * @throws Exception
     */
    protected function parseOptions(string $key, $value): string
    {
        $command = '';
        if (is_array($value)) {
            if ($key === 'toc') {
                $command .= ' toc';
                foreach ($value as $k => $v) {
                    $command .= $this->parseOptions($k, $v);
                }
            } elseif ($key === 'cover') {
                if (!isset($value['url'])) {
                    throw new Exception('The url for the cover is missing. Use the "url" index.');
                }
                $command .= ' cover ' . escapeshellarg((string)$value['url']);
                unset($value['url']);
                foreach ($value as $k => $v) {
                    $command .= $this->parseOptions($k, $v);
                }
            } else {
                foreach ($value as $k => $v) {
                    $command .= sprintf(' --%s %s %s', $key, escapeshellarg($k), escapeshellarg((string)$v));
                }
            }
        } elseif ($value === true) {
            if ($key === 'toc') {
                $command .= ' toc';
            } else {
                $command .= ' --' . $key;
            }
        } else {
            if ($key === 'cover') {
                $command .= ' cover ' . escapeshellarg((string)$value);
            } else {
                $command .= sprintf(' --%s %s', $key, escapeshellarg((string)$value));
            }
        }

        return $command;
    }

}
