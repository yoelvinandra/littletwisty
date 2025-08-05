<?php
require_once(APPPATH.'third_party/fpdf/fpdf.php');
require_once(APPPATH.'third_party/fpdi/autoload.php');

use setasign\Fpdi\Fpdi;

class Pdf_merger extends Fpdi
{
    public function __construct()
    {
        parent::__construct();
    }
}
