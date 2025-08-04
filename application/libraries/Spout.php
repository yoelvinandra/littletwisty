<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Memuat autoloader Spout
require_once FCPATH . 'application/third_party/spout/src/Box/Spout/Autoloader/autoload.php';

class Spout
{
    public function __construct()
    {
        // Menginisialisasi autoloader Spout
        // \Box\Spout\Autoloader\autoload::register();
    }

    // Fungsi untuk membaca file Excel XLSX
    public function readXlsxPenjualan($filePath)
    {
        
        // KODEPENJUALAN = No Penjualan
        // KODEPENJUALANDESTI = No Penjualan Desti
        // KODEPENJUALANMARKETPLACE = No Pesanan (Market Place)
        // KODEPAKET = No Paket
        // METODEBAYAR = Metode Bayar
        // POTONGAN HARGA = Diskon Penjual + Biaya Pengiriman Final + Biaya Layanan + Pajak
        // GRANDTOTAL = Subtotal
        
        // INPUT MASTER CUSTOMER
        // NAMA CUSTOMER = Nama Pembeli
        // TELP CUSTOMER = Nomor Telepon Pembeli
        // EMAIL CUSTOMER = Email Pembeli
        // ALAMAT CUSTOMER = Alamat Pembeli
        
        // //HATI2x, SATU NOMOR PESANAN BISA LEBIH DARI 1 ITEM
        
        // SKUBARANG = SKU Master (Buat dapeting ID Barang)
        // HARGAKURS = Harga Produk Setelah Diskon
        // JUMLAHKURS = Jumlah
        // SUBTOTALKURS = HARGAKURS * JUMLAHKURS

        $reader = Box\Spout\Reader\Common\Creator\ReaderEntityFactory::createXLSXReader();
        $reader->open($filePath);
        $reader->setShouldPreserveEmptyRows(true);
        $reader->setShouldFormatDates(true);
        $index = 1;
        $baris = 1;
        $dataValues = [];
        $arrayDetail = [];
        $oldID = 9999999;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // do stuff with the row
                $protectedCells = $row->getCells();
                $cells = [];
                //GET DATA OF PROTECTED OBJECT
                for($x = 0 ; $x < count($protectedCells) ; $x++)
                {
                    array_push($cells,$protectedCells[$x]->getValue());
                }
                if($baris > 1)
                {
                    if($oldID != $cells[4])
                    {
                        $oldID = $cells[4];
                        $arrayDetail = [];
                        $arrAlamat = explode(", ",$cells[15]);
                        
                        $status = "S";
                        if($cells[6] == "unpaid")
                        {
                            $status = "I";
                        }
                        else if($cells[6] == "Cancellations")
                        {
                            $status = "D";
                        }
                        
                        array_push($dataValues,
                        [
                            'IDPERUSAHAAN'      => $_SESSION[NAMAPROGRAM]['IDPERUSAHAAN'],
                            'JENISTRANSAKSI'    => 'ONLINE',
                            'KODETRANSREFERENSI'=> $cells[3].' / '.$cells[4],
                            'NAMACUSTOMER'      => $cells[12],
                            'TELPCUSTOMER'      => $cells[13],
                            'EMAILCUSTOMER'     => $cells[14],
                            'ALAMATCUSTOMER'    => $arrAlamat[0]??"",
                            'KOTACUSTOMER'      => str_replace("KAB. ","",str_replace("KOTA " ,"",$arrAlamat[count($arrAlamat)-5]))??"",
                            'PROVINSICUSTOMER'  => $arrAlamat[count($arrAlamat)-3]??"",
                            'NEGARACUSTOMER'    => str_replace("ID","INDONESIA",$arrAlamat[count($arrAlamat)-2])??"",
                			'TGLTRANS'          => explode(" ",$cells[1])[0],
                			'TGLENTRY'          => explode(" ",$cells[1])[0],
                			'JAMENTRY'          => explode(" ",$cells[1])[1],
                			'USERENTRY'         => $_SESSION[NAMAPROGRAM]['USERID'],
                			'TOTAL'             => $cells[27],
                			'PPNRP'             => 0,
                			'GRANDTOTAL'        => $cells[27],
                			'POTONGANRP'        => (abs($cells[28])+abs($cells[30])+abs($cells[31])),
                			'POTONGANPERSEN'    => 0,
                			'GRANDTOTALDISKON'  => ($cells[27]-(abs($cells[28])+abs($cells[30])+abs($cells[31]))),
                			'PEMBAYARAN'        => $cells[33],
                			'STATUS'            => $status,
                			'DATADETAIL'        => $arrayDetail
                        ]);
                    }
                    
                    $sku = "CARD-BOX-OTHER";
                    if($cells[19] != "")
                    {
                        $sku = $cells[19];
                    }
                    
                    array_push($arrayDetail,
                    [
                        'sku' 	        => $sku,
                        'jml'           => $cells[24],
                        'satuan'        => 'PAIR',
            			'idcurrency'    => 1,
                        'harga'         => $cells[22],
                        'hargakurs'     => $cells[22],
                        'discpersen'    => 0,
                        'disc'          => $cells[22] - $cells[23],
                        'disckurs'      => $cells[22] - $cells[23],
                        'subtotal'      => ($cells[24] * $cells[23]),
                        'subtotalkurs'  => ($cells[24] * $cells[23]),
                        'pakaippn'      => 0,
                        'ppnpersen'     => 0,
                        'ppnrp'         => 0,
                    ]);
                    
                    $dataValues[count($dataValues)-1]['DATADETAIL'] = $arrayDetail;
                    
                }
                $baris++;
            }
        }

        $reader->close();
        return $dataValues;
    }
    
     // Fungsi untuk membaca file Excel XLSX
    public function readXlsxUrutanBarang($filePath)
    {
        $reader = Box\Spout\Reader\Common\Creator\ReaderEntityFactory::createXLSXReader();
        $reader->open($filePath);
        $reader->setShouldPreserveEmptyRows(true);
        $reader->setShouldFormatDates(true);
        $urutan = 1;
        $baris = 1;
        $dataValues = [];
        $arrayDetail = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // do stuff with the row
                $protectedCells = $row->getCells();
                $cells = [];
                //GET DATA OF PROTECTED OBJECT
                for($x = 0 ; $x < count($protectedCells) ; $x++)
                {
                    array_push($cells,$protectedCells[$x]->getValue());
                }
                if($baris > 1)
                {
                    array_push($dataValues,
                    [
                        'KODEBARANG'      => $cells[0],
                        'SKU'             => $cells[1],
                        'URUTANTAMPIL'    => $urutan,
                    ]);
                    $urutan++;
                }
                $baris++;
            }
        }

        $reader->close();
        return $dataValues;
    }

    // Fungsi untuk menulis file Excel XLSX
    public function writeXlsx($filePath, $data)
    {
        $writer = \Box\Spout\Writer\XLSX\Writer::createFromFile($filePath);
        $writer->openToFile($filePath);

        foreach ($data as $row) {
            $writer->addRow($row); // Menambahkan baris ke file
        }

        $writer->close();
    }
}
