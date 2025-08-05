<?php
require_once(APPPATH.'third_party/fpdf/fpdf.php');

class Fpdf extends FPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}
