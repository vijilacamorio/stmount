<?php
use Dompdf\Dompdf;
use Dompdf\Options;

defined('BASEPATH') OR exit('No direct script access allowed');

class Dompdf_lib
{
    private $dompdf;

    public function __construct()
    {
        require_once APPPATH . 'third_party/dompdf/autoload.inc.php';

        $this->dompdf = new Dompdf();
        $this->dompdf->setOptions($this->getOptions());
    }

    private function getOptions()
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        return $options;
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size = 'A4', $orientation = 'portrait')
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($filename = 'document.pdf', $options = array())
    {
        $this->dompdf->stream($filename, $options);
    }
}
