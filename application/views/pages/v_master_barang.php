<meta charset="UTF-8">
<style>
    /* Custom CSS */
   .image-upload-section {
       margin-top: 20px;
   }

   .image-upload-box {
       padding: 20px;
       border: 2px solid #ccc;
       border-radius: 8px;
       background-color: #f9f9f9;
       box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
   }

   .image-upload-header {
       text-align: center;
       font-size: 24px;
       font-weight: bold;
       margin-bottom: 15px;
   }

   .file-input-container {
       text-align: center;
       margin-bottom: 20px;
   }

   .file-input-container input[type="file"] {
       display: none;
   }

   .file-input-container label {
       font-size: 18px;
       color: #007bff;
       cursor: pointer;
       padding: 10px 25px;
       border: 2px solid #007bff;
       border-radius: 5px;
       transition: all 0.3s ease;
   }

   .file-input-container label:hover {
       background-color: #007bff;
       color: white;
       border-color: #0056b3;
   }

   .image-preview {
       display: flex;
       flex-wrap: wrap;
       justify-content: center;
       margin-top: 20px;
   }

   .image-preview .image-item {
       position: relative;
       width: 120px;
       height: 120px;
       margin: 10px;
       border-radius: 8px;
       box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   }

   .image-preview img {
       width: 100%;
       height: 100%;
       border-radius: 8px;
       object-fit: cover;
   }

   .image-preview .image-item .remove-btn,
   .image-preview .image-item .change-btn {
       position: absolute;
       top: 5px;
       right: 5px;
       background-color: rgba(0, 0, 0, 0.6);
       color: white;
       padding: 5px;
       font-size:10px;
       border-radius: 100%;
       cursor: pointer;
   }

   .alert-message {
       color: red;
       text-align: center;
       font-size: 18px;
       margin-top: 10px;
   }

   /* Responsive Design */
   @media (max-width: 768px) {
       .image-preview img {
           width: 90px;
           height: 90px;
       }

       .file-input-container label {
           font-size: 16px;
           padding: 8px 20px;
       }
   }
   
   td{
       cursor:pointer;
   }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Master Produk
    <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt;"  onclick="exportTableToExcel()">Excel</button>
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol> -->
</section>

<!-- Main content -->
<section class="content">
    
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
        <div class="box">
        <div class="box-header">
            <button class="btn btn-success" onclick="javascript:tambahHeader()">Tambah</button>
            <!--<?php echo form_open_multipart(base_url().'Master/Data/Barang/importExcelUrutan', ['id' => 'excelFormUrutan']); ?>     -->
            <!--    <button style="margin-top:-30px; margin-left:10px;" type="button" class="btn pull-right btn-primary" onclick="openFileExcelUrutan()" >Upload Format Urutan</button> -->
            <!--    <input style="display:none;" type="file"  name="excelFileUrutan" id="excelFileUrutan" accept=".xls,.xlsx" onchange="importExcelUrutan()" required>-->
            <!--    <button type="button" class="btn pull-right btn-success" style="margin-top:-30px; font-size:10pt; "  onclick="exportTableToExcelUrutan()">Download Format Urutan</button>-->
            <!--<?php echo form_close(); ?>-->
        </div>
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom" >
            <ul class="nav nav-tabs" id="tab_transaksi">
                <li class="active"><a href="#tab_grid" data-toggle="tab">Grid</a></li>
                <li><a href="#tab_form" data-toggle="tab">Tambah</a></li>
            </ul>		
			
            <div class="tab-content">
                <div class="tab-pane active" id="tab_grid">
                    <div class="box-body">
                        <table id="dataGrid" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                            <!-- class="table-hover"> -->
                            <thead>
                            <span style="font-style:italic">*Geser keatas atau kebawah untuk merubah urutan produk</span>
                            <tr>
                                <th width="80px"></th>
                                <th width="80px"></th>
                                <th width="80px"></th>
                                <th width="80px"></th>
                                <th width="80px"></th>
                                <th width="80px"></th>
                                <th>Nama Produk</th>
                                <th width="100px">Jml Varian</th>
                                <th width="200px">Harga Produk</th>
                                <th width="40px">Tgl. Input</th>
                                <th width="25px">Aktif</th>								
                            </tr>
                            </thead>
                        </table>                        
                    </div>
                    <div id="tableExcel" style="display:none;" ></div>
                    <div id="tableExcelUrutan" style="display:none;" ></div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_form">
                    <div class="box-body">
                        <div class="col-md-12">
                        <!-- form start -->
                        <form role="form" id="form_input">
                            <input type="hidden" id="mode" name="mode">
                            <input type="hidden" id="datavarian" name="datavarian">
                            <input type="hidden" id="BASE64IMAGES" name="BASE64IMAGES">
                                <div class="box-body">
                                    <div class="form-group col-md-8">
                                        <h3 style="font-weight:bold;">Informasi Produk</h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Kategori <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                <select id="KATEGORIONLINE" name="KATEGORIONLINE" style="border:1px solid #B5B4B4; border-radius:1px; width:100%; height:32px; padding-left:12px; padding-right:12px;">
                                                    <option value="SEPATU BAYI" selected>SEPATU BAYI</option>
                                                    <option value="SEPATU ANAK LAKI-LAKI">SEPATU ANAK LAKI-LAKI</option>
                                                    <option value="SEPATU ANAK PEREMPUAN">SEPATU ANAK PEREMPUAN</option>
                                                    <option value="PEMBUNGKUS KADO DAN KEMASAN">PEMBUNGKUS KADO DAN KEMASAN</option>
                                                    <option value="SET DAN PAKET HADIAH">SET DAN PAKET HADIAH</option>
                                                </select>
                                                <!--<input type="text" class="form-control" id="KATEGORIONLINE" name="KATEGORIONLINE" placeholder="KATEGORI">-->
                                            </div>
                                            <div class="col-md-8">
                                                <label>Nama Produk <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                <input type="text" class="form-control" id="KATEGORI" name="KATEGORI" placeholder="Nama Produk">
                                            </div>
                                            <div class="col-md-12">
                                                <br>
                                                <label>Deskripsi <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                <textarea class="form-control" rows="5" id="DESKRIPSI" name="DESKRIPSI" placeholder="Deskripsi....."></textarea>
                                            </div>
                                        </div>
                                    </div>			
                                    <div class="form-group col-md-4">
                                        <h3 style="font-weight:bold;">Pengiriman</h3>
                                            <div class="row">
                                                <div class="col-md-12">
                								    <label>Berat (gram) <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="BERAT" name="BERAT" placeholder="Dalam Gram">
                                                </div>
                                            </div>
                                            <br>
                                            <label>Ukuran Barang (cm)</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Panjang</label>
                                                    <input type="text" class="form-control" id="PANJANG" name="PANJANG" placeholder="Dalam Centimeter">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Lebar</label>
                                                    <input type="text" class="form-control" id="LEBAR" name="LEBAR" placeholder="Dalam Centimeter">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Tinggi</label>
                                                    <input type="text" class="form-control" id="TINGGI" name="TINGGI" placeholder="Dalam Centimeter">
                                                </div>
                                            </div>
                                        <!--<br><br><br>-->
                                        <!--<br><br>-->
                                        <!--<h3 style="font-weight:bold;">Gambar Produk <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></h3>-->
                                        <!--<br>-->
                                        <!--    <div class="row">-->
                                                <!-- Image Upload Box -->
                                        <!--        <div class="col-md-12">-->
                                        <!--            <div class="box box-primary image-upload-box">-->
                                        <!--                <div class="box-body">-->
                                        <!--                    <div class="file-input-container">-->
                                        <!--                        <input type="file" id="imageUpload" accept="image/*" multiple>-->
                                        <!--                        <label for="imageUpload"><i class="fa fa-upload"></i> Pilih Gambar</label>-->
                                        <!--                    </div>-->
                                        <!--                    <div id="message" class="alert-message"></div>-->
                                        <!--                    <div id="imageContainer" class="image-preview"></div>-->
                                        <!--                </div>-->
                                        <!--            </div>-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                    </div>
                                    <div class ="form-group col-md-12">
                                        <br>
                                        <h3 style="font-weight:bold; margin-bottom:-5px;">Varian Produk<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Min 1)</i></h3>
                                        
                                        <!--<button type="button" style="margin-bottom:-50px;" id="btn_tambah_varian" onclick="tambah()" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modal-varian"data-id="7">Tambah Varian</button> &nbsp;&nbsp;&nbsp;&nbsp;-->
                                        <button type="button" style="margin-bottom:-50px;" id="btn_set_varian" onclick="tambahMassal()" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modal-set-varian"data-id="7">Tambah Varian</button>
                                        
                                        <table id="dataGridVarian" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                            <!-- class="table-hover"> -->
                                            <thead>
                                            <div style="font-style:italic; margin-left:130px; margin-top:7px;">*Geser keatas atau kebawah, dan simpan untuk merubah urutan varian</div>
                                            <tr>
                                                <th width="100px"></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Nama</th>
                                                <th width="50px">Sat</th>
                                                <th width="100px">Harga Jual Tampil</th>
                                                <th width="100px">Harga Beli</th>
                                                <th width="200px">Barcode</th>
                                                <th width="200px">SKU Shopee</th>
                                                <th width="200px">SKU Tiktok</th>
                                                <th width="40px">User Input</th>
                                                <th width="40px">Tgl. Input</th>
                                                <th width="25px">Aktif</th>								
                                            </tr>
                                            </thead>
                                        </table>  
                                    </div>
                                </div>
                            <div class="box-footer">&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" id="btn_simpan" class="btn btn-primary" onclick="javascript:simpanHeader()">Simpan</button>
                                </div>
                            </div>
						</form>
						<div class="modal fade" id="modal-varian" >
							<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-body">
                                    <div class="box-body">
                                        <input type="hidden" id="IDBARANG" name="IDBARANG">
                                        <div class="form-group col-md-12">
                                            
                                            <h3 style="font-weight:bold;">Varian Produk<label>&nbsp;&nbsp;&nbsp;<input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Aktif &nbsp;&nbsp;&nbsp;</label></h3>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="KODEBARANG">Kode Varian&nbsp;&nbsp;&nbsp;</label>
                                                   <input type="text" class="form-control" id="KODEBARANG" name="KODEBARANG" placeholder="AUTO" readonly>
                                                </div>
                                                
                                                <div class="col-md-9">
                                                    <label>Nama Varian  <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <div>
                                                        <span class="pull-left" style="padding-top:7px; font-style:italic;" >LITTLE TWISTY -&nbsp;</span>
                                                        <input type="text" class="form-control" id="NAMABARANG" name="NAMABARANG" placeholder="Contoh : LEON ZIPPER SHOES" style="width:80%;">
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Harga Jual Tampil<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="HARGAJUAL" name="HARGAJUAL" placeholder="Harga Jual Tampil">
                                                </div>
                                                 <div class="col-md-3">
                                                    <label>Harga Beli <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="HARGABELI" name="HARGABELI" placeholder="Harga Beli">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Warna</label>
                                                    <input type="text" class="form-control" id="WARNABARANG"   name="WARNABARANG" placeholder="Cth : BLUE">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Ukuran</label>
                                                    <input type="number" class="form-control" id="UKURANBARANG"  name="UKURANBARANG" placeholder="Cth : 0" onkeyup="checkValid(event)" mouseup="checkValid(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Satuan<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="SATUAN2" name="SATUAN2" placeholder="Cth : PAIR">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-4">
                							        <label>SKU Shopee <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="SKUSHOPEE" name="SKUSHOPEE" placeholder="SKU Shopee">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>SKU Tiktok <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="SKUTIKTOK" name="SKUTIKTOK" placeholder="SKU Tiktok">
                                                </div>
                                                <div class="col-md-4">
                    							    <label>Barcode</label>
                                                    <input type="text" class="form-control" id="BARCODE" name="BARCODE" placeholder="Barcode">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row" hidden>
                                                <div class="col-md-6">
                                                    <label>SKU Grab</label>
                                                    <input type="text" class="form-control" id="SKUGRAB" name="SKUGRAB" placeholder="SKU Grab">
                                                </div>
                                                 <div class="col-md-6">
                							        <label>SKU Gojek</label>
                                                    <input type="text" class="form-control" id="SKUGOJEK" name="SKUGOJEK" placeholder="SKU Gojek">
                                                </div>
                                            <br>
                                            </div>
                                            <div class="row" hidden>
                                                <div class="col-md-6">
                							        <label>SKU Gojek</label>
                                                    <input type="text" class="form-control" id="SKUGOJEK" name="SKUGOJEK" placeholder="SKU Gojek">
                                                </div>
                                                <div class="col-md-6">
                							        <label>SKU Tokped</label>
                                                    <input type="text" class="form-control" id="SKUTOKPED" name="SKUTOKPED" placeholder="SKU Tokped">
                                                </div>
                                            <br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button type="button" id="btn_simpan_detail" class="btn btn-success" onclick="javascript:simpan()">Tambah</button>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
						
						<div class="modal fade" id="modal-set-varian" >
							<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-body">
                                    <div class="box-body">
                                        
                                        <div class="form-group col-md-12">
                                            
                                            <h3 style="font-weight:bold;">Tambah Varian Produk Massal</h3>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Nama Varian  <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <div>
                                                    <span class="pull-left" style="padding-top:7px; font-style:italic;" >LITTLE TWISTY -&nbsp;</span>
                                                    <input type="text" class="form-control" id="NAMABARANGMASSAL" name="NAMABARANGMASSAL" placeholder="Contoh : LEON ZIPPER SHOES" style="width:88%;">
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Harga Jual Tampil<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="HARGAJUALMASSAL" name="HARGAJUALMASSAL" placeholder="Harga Jual Tampil">
                                                </div>
                                                 <div class="col-md-3">
                                                    <label>Harga Beli <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="HARGABELIMASSAL" name="HARGABELIMASSAL" placeholder="Harga Beli">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Satuan<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                    <input type="text" class="form-control" id="SATUANMASSAL" name="SATUANMASSAL" placeholder="Cth : PAIR">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Warna <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label><br>
                                                    <select class="form-control select2" multiple="multiple" id="PILIHWARNAMASSAL" name="PILIHWARNAMASSAL" placeholder="Warna..." style="width:100%; float:left;"  onchange="">
                                              	    </select>
                                              	    <i>*ketik warna, lalu enter untuk menambah Warna Baru</i>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Ukuran <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label><br>
                                                    <select class="form-control select2" multiple="multiple" id="PILIHUKURANMASSAL" name="PILIHUKURANMASSAL" placeholder="Ukuran..." style="width:100%; float:left;"  onchange="">
                                                    </select>
                                              	    <i>*ketik ukuran, lalu enter untuk menambah Ukuran Baru</i>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label style="color:red; font-size:16pt;">*SKU dan Barcode akan digenerate otomatis melalui system, pastikan dahulu sebelum menyimpan barang.</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <button type="button" id="btn_simpan_massal_detail" class="btn btn-success" onclick="javascript:simpanMassal()">Tambah Varian</button>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					  </div>
                            <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
        </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row (main row) -->
</section>
<!-- /.content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    
const maxImages = 10;
let base64Images = [];  // Array to store base64 encoded images
var dataVarianLama = [];
var dataLama = {};
$(document).ready(function() {
    
	var counter = 0;
    $("#mode").val('tambah');
    $("#btn_simpan_detail").html('Tambah');
    $("#STATUS").prop('checked',true).iCheck('update');
    $("#STOK").prop('checked',true).iCheck('update');
    
    	
    $('#KATEGORI, #NAMABARANG, #NAMABARANGMASSAL').on('keypress', function(event) {
        // List of characters to block
        const blockedChars = ['/', '\\', '.', ',','|'];

        // Check if the pressed key is in the blocked characters array
        if (blockedChars.includes(String.fromCharCode(event.which))) {
            event.preventDefault(); // Prevents the character from being typed
        }
    });
    
    $('.select2').select2();
    
    $('#PILIHWARNAMASSAL').select2({
        tags: true,  // Allows custom tags (user input)
        placeholder: "",
        allowClear: true,
        width: '100%',
    });
    
    $('#PILIHUKURANMASSAL').select2({
        tags: true,  // Allows custom tags (user input)
        placeholder: "",
        allowClear: true,
        width: '100%',
    });

    // When the Select2 dropdown is opened, add the button above the options
    $('#PILIHUKURANMASSAL').on('select2:close', function() {
        $('#PILIHUKURANMASSAL option').each(function() {
            var optionValue = $(this).val();
    
            // Check if the option value is a valid number
            if (isNaN(optionValue) || optionValue === '') {
                // Remove the option if it's not a valid number
                $(this).remove();
                Swal.fire({
    				title            : 'Ukuran harus berupa angka',
    				type             : 'warning',
    				showConfirmButton: false,
    				timer            : 1500
    			});
            }
        });
    
        // Trigger select2 change event to update the dropdown
        $('#PILIHUKURANMASSAL').trigger('change');
    });
        
    $("#HARGABELI, #HARGAJUAL, #HARGABELIMASSAL, #HARGAJUALMASSAL, #BERAT, #PANJANG, #LEBAR, #TINGGI").number(true, 0);
	
    $('#dataGrid').DataTable({
        'pageLength'  : 25,
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/Barang/dataGrid',
			dataSrc: "rows",
			dataFilter: function (data) {
			    
			     $.ajax({
                    type      : 'POST',
                    url       : base_url+'Master/Data/Barang/dataGridVarian',
                    dataType  : 'json',
                    beforeSend: function (){
                        //$.messager.progress();
                    },
                    success: function(dataVarian){
                         // Refresh the new table whenever DataTable reloads
                        var allData = dataVarian.rows; // Get all rows' data
        
                        // Create the HTML structure for the new table
                        var newTable = $('<table id="newTable" class="table table-bordered">');
                        var thead = $('<thead>').append('<tr><th>Kategori</th><th>Nama Produk</th><th>Kode Varian</th><th>Nama Varian</th><th>Warna</th><th>Ukuran</th><th>Satuan</th><th>Harga Jual Tampil</th><th>Harga Beli</th><th>Barcode</th><th>SKU Shopee</th><th>SKU Tiktok</th><th>Berat</th><th>Panjang</th><th>Lebar</th><th>Tinggi</th><th>User Buat</th><th>Tgl Entry</th><th>Status</th></tr>');
                        var tbody = $('<tbody>');
                         // Loop through the DataTable data and create rows for the new table
                 
                        allData.forEach(function (row, index) {
                            var tr = $('<tr>');
                            tr.append('<td>' + (row.KATEGORIONLINE) + '</td>');
                            tr.append('<td>' + (row.KATEGORI) + '</td>');
                            tr.append('<td>' + (row.KODEBARANG) + '</td>');
                            tr.append('<td>' + (row.NAMABARANG.split(" | ")[0]) + '</td>');
                            tr.append('<td>' + (row.WARNA == null?"":row.WARNA) + '</td>');
                            tr.append('<td>' + (row.UKURAN == null?"":row.UKURAN) + '</td>');
                            tr.append('<td>' + (row.SATUAN == null?"":row.SATUAN) + '</td>');
                            tr.append('<td>' + (row.HARGAJUAL == null?"":row.HARGAJUAL) + '</td>');
                            tr.append('<td>' + (row.HARGABELI == null?"":row.HARGABELI) + '</td>');
                            tr.append('<td>' + (row.BARCODE == null?"":row.BARCODE) + '</td>');
                            tr.append('<td>' + (row.SKUSHOPEE == null?"":row.SKUSHOPEE) + '</td>');
                            tr.append('<td>' + (row.SKUTIKTOK == null?"":row.SKUTIKTOK) + '</td>');
                            tr.append('<td>' + (row.BERAT == null?"":row.BERAT) + '</td>');
                            tr.append('<td>' + (row.PANJANG == null?"":row.PANJANG) + '</td>');
                            tr.append('<td>' + (row.LEBAR == null?"":row.LEBAR) + '</td>');
                            tr.append('<td>' + (row.TINGGI == null?"":row.TINGGI) + '</td>');
                            tr.append('<td>' + row.USERBUAT + '</td>');
                            tr.append('<td class="text-center">' + row.TGLENTRY + '</td>');
                            tr.append('<td class="text-center">' + (row.STATUS == 1 ? 'AKTIF' : 'NON AKTIF') + '</td>');
                    
                            // Append the row to the tbody
                            tbody.append(tr);
                        });
                
                        // Append the thead and tbody to the new table
                        newTable.append(thead).append(tbody);
                        // Append the new table to the DOM (you can specify where you want the new table to appear)
                        $('#tableExcel').html(newTable); 
                        
                        //UNTUK URUTAN
                        // Create the HTML structure for the new table
                        var newTableUrutan = $('<table id="newTableUrutan" class="table table-bordered">');
                        var theadUrutan = $('<thead>').append('<tr><th>Kode Varian</th><th>SKU Varian</th><th>Nama Varian</th></tr>');
                        var tbodyUrutan = $('<tbody>');
                         // Loop through the DataTable data and create rows for the new table
                 
                        allData.forEach(function (row, index) {
                            trUrutan = $('<tr>');
                            trUrutan.append('<td>' + (row.KODEBARANG) + '</td>');
                            trUrutan.append('<td>' + (row.SKUSHOPEE == null?"":row.SKUSHOPEE) + '</td>');
                            trUrutan.append('<td>' + (row.NAMABARANG) + '</td>');
                            // Append the row to the tbody
                            tbodyUrutan.append(trUrutan);
                        });
                
                        // Append the thead and tbody to the new table
                        newTableUrutan.append(theadUrutan).append(tbodyUrutan);
                        $('#tableExcelUrutan').html(newTableUrutan);
                    }
                });
               
                
                return data;
            }
		},
        columns:[
            // { data: 'IDBARANG', visible: false},
            {data: ''},
            {data: 'DESKRIPSI',visible:false},
            {data: 'PANJANG',visible:false},
            {data: 'LEBAR',visible:false},
            {data: 'TINGGI',visible:false},
            {data: 'BERAT',visible:false},
            {data: 'KATEGORI'},
            {data: 'JMLVARIAN', className:"text-center"},
            {data: 'RANGEHARGAUMUM', className:"text-center"},
            {data: 'TGLENTRY', className:"text-center"},
            {data: 'STATUS', className:"text-center"},
        ],
		'columnDefs': [
			{
			    "targets": 0,
                "data": null,
                "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
			},
			{
                "targets": -1,
                "render" :function (data) 
					{
						if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                        else return '<input type="checkbox" class="flat-blue" disabled></input>';
					},	
			}
		],
    });

    //DAPATKAN INDEX
	$('#dataGrid tbody').on( 'click', 'button', function () {
		var row = $('#dataGrid').DataTable().row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah"){ ubahHeader(row); }
		if(mode == "btn_hapus"){ hapusHeader(row); }
	});
    
    const tbody = $('#dataGrid tbody')[0];
    const sortable = new Sortable(tbody, {
        animation: 150,
        ghostClass: 'dragging',
        handle: 'tr',
        onEnd: function(evt) {
            if(evt.oldIndex != evt.newIndex)
            {
                let movedData = $('#dataGrid').DataTable().row(evt.oldIndex).data();
                let temp;
                
                var dataList = $('#dataGrid').DataTable().rows().data();
                $('#dataGrid').DataTable().clear();
                
                for(var x = 0 ; x < dataList.length; x++)
                {
                    if(evt.newIndex <= evt.oldIndex)
                    {
                        
                       if(x == evt.newIndex)
                       {
                           temp = dataList[x];
                           dataList[x] = movedData;
                           movedData = temp;
                       }
                       else if(x <= evt.oldIndex && x > evt.newIndex)
                       {
                           temp = dataList[x];
                           dataList[x] = movedData;
                           movedData = temp;
                       }
                         $('#dataGrid').DataTable().row.add(dataList[x]).draw();
                    }
                    else
                    {
                       if(x >= evt.oldIndex && x < evt.newIndex)
                       {
                           dataList[x] = dataList[x+1];
                       }
                       else if(x == evt.newIndex)
                       {
                           dataList[x] = movedData;
                       }
                     $('#dataGrid').DataTable().row.add(dataList[x]).draw();
                    }
                }
                
                 var table = $('#dataGrid').DataTable();
    
                // Get all data from the table
                var data = table.rows().data();
                
                 // Prepare an array with the order and other necessary data
                var tableData = [];
                data.each(function(value, index) {
                    // For example, push only the data you need (row ID, order, etc.)
                    tableData.push({
                        KATEGORI: value.KATEGORI,  // Assuming the ID is in the first column
                        KATEGORIONLINE: value.KATEGORIONLINE, // Assuming the name is in the second column
                    });
                });
                
                
                 $.ajax({
                    type      : 'POST',
                    url       : base_url+'Master/Data/Barang/ubahUrutanTampil',
                    data      : {dataKategori:JSON.stringify(tableData)},
                    dataType  : 'json',
                    beforeSend: function (){
                        //$.messager.progress();
                    },
                    success: function(msg){
                        if (msg.success) {
                            Swal.fire({
                                title            : 'Ubah Urutan Sukses',
                                type             : 'success',
                                showConfirmButton: false,
                                timer            : 1500
                            });
                            $("#dataGrid").DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title            : msg.errorMsg,
                                type             : 'error',
                                showConfirmButton: false,
                                timer            : 1500
                            });
                        }
                    }
                });
            }
        }
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
    
    
    $('#dataGridVarian').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/Barang/getDataVarian',
			dataSrc: "rows",
		},
        columns:[
            {data: ''},
            {data: 'IDBARANG', visible:false},
            {data: 'WARNA', visible:false},
            {data: 'SIZE', visible:false},
            {data: 'MODE', visible:false},
            {data: 'IDBARANG', visible:false},
            {data: 'KODEBARANG', visible:false},
            {data: 'NAMABARANG'},
            {data: 'SATUAN', visible:false},
            {data: 'HARGAJUAL', render:format_number, className:"text-right"},
            {data: 'HARGABELI', render:format_number, className:"text-right"},
            {data: 'BARCODE', className:"text-center"},
            {data: 'SKUSHOPEE', className:"text-center"},
            {data: 'SKUTIKTOK', className:"text-center"},
            {data: 'USERBUAT' },
            {data: 'TGLENTRY', className:"text-center"},
            {data: 'STATUS', className:"text-center"},
        ],
		'columnDefs': [
			{
			    "targets": 0,
                "data": null,
                "defaultContent": "<button id='btn_ubah_varian' type='button' data-toggle='modal' data-target='#modal-varian' data-id='7' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus_varian' type='button'  class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
			},
			{
                "targets": -1,
                "render" :function (data) 
					{
						if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                        else return '<input type="checkbox" class="flat-blue" disabled></input>';
					},	
			}
		]
    });
    
     //DAPATKAN INDEX
	$('#dataGridVarian tbody').on( 'click', 'button', function () {
		var row = $('#dataGridVarian').DataTable().row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah_varian"){ ubah(row); }
		if(mode == "btn_hapus_varian"){ hapus(row); }
	});
	
	const tbodyvarian = $('#dataGridVarian tbody')[0];
    const sortablevarian = new Sortable(tbodyvarian, {
        animation: 150,
        ghostClass: 'dragging',
        handle: 'tr',
        onEnd: function(evt) {
            
            let movedData = $('#dataGridVarian').DataTable().row(evt.oldIndex).data();
            let temp;
            
            var dataList = $('#dataGridVarian').DataTable().rows().data();
            $('#dataGridVarian').DataTable().clear();
            
            for(var x = 0 ; x < dataList.length; x++)
            {
                if(evt.newIndex <= evt.oldIndex)
                {
                    
                   if(x == evt.newIndex)
                   {
                       temp = dataList[x];
                       dataList[x] = movedData;
                       movedData = temp;
                   }
                   else if(x <= evt.oldIndex && x > evt.newIndex)
                   {
                       temp = dataList[x];
                       dataList[x] = movedData;
                       movedData = temp;
                   }
                     $('#dataGridVarian').DataTable().row.add(dataList[x]).draw();
                }
                else
                {
                   if(x >= evt.oldIndex && x < evt.newIndex)
                   {
                       dataList[x] = dataList[x+1];
                   }
                   else if(x == evt.newIndex)
                   {
                       dataList[x] = movedData;
                   }
                 $('#dataGridVarian').DataTable().row.add(dataList[x]).draw();
                }
            }
            
        }
    });
            
});

function exportTableToExcel() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcel'), {sheet:"Sheet 1"});
 const ws = wb.Sheets[wb.SheetNames[0]];
  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 150 }, // Column A width in pixels
    { wpx: 300 }, // Column A width in pixels
    { wpx: 70 }, // Column A width in pixels
    { wpx: 250 }, // Column B width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 },  // Column C width in pixels
    { wpx: 70 },  // Column C width in pixels
    { wpx: 70 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 }, // Column B width in pixels
    { wpx: 100 }, // Column A width in pixels
    { wpx: 70 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'BARANG_'+dateNowFormatExcel()+'.xlsx');
}

function exportTableToExcelUrutan() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcelUrutan'), {sheet:"Sheet 1"});
 const ws = wb.Sheets[wb.SheetNames[0]];
  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 70 }, // Column A width in pixels
    { wpx: 150 }, // Column A width in pixels
    { wpx: 300 }, // Column A width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'URUTAN_BARANG_'+dateNowFormatExcel()+'.xlsx');
}

function checkValid(data) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    if(inputValue > 35)
    {
        inputElement.value = 35;
    }
}
    
// Handle file input change (when user selects files)
$('#imageUpload').on('change', function(e) {
    let files = e.target.files;
    const messageElement = $('#message');
    messageElement.text('');  // Clear the message

    // Check if the number of files exceeds the limit
    if (files.length + base64Images.length > maxImages) {
        messageElement.text('Maksimal 10 Gambar');
        return;
    }

    // Loop through all selected files
    for (let i = 0; i < files.length; i++) {
        if (base64Images.length >= maxImages) break; // Stop if 10 images are already uploaded

        let file = files[i];

        // Validate that the file is an image
        if (!file.type.startsWith('image/')) {
            messageElement.text('Hanya Boleh File Gambar');
            continue;
        }

        // Create a FileReader to read the image
        let reader = new FileReader();
        reader.onload = function(event) {
            let base64String = event.target.result;  // Get the base64 string
            base64Images.push(base64String);  // Store base64 string
            displayImage(base64String);  // Display the image
        };

        // Convert the file to base64
        reader.readAsDataURL(file);
    }
});

// Function to display the base64 image with remove and change buttons
function displayImage(base64String) {
    const imageContainer = $('#imageContainer');
    let imageItem = $('<div class="image-item"></div>');

    // Add image element
    let imgElement = $('<img>').attr('src', base64String);
    imageItem.append(imgElement);

    // Add remove button
    let removeBtn = $('<span class="remove-btn" style="margin-top:7px;"><i class="fa fa-times"></i></span>');
    removeBtn.on('click', function() {
        removeImage(base64String);
    });

    // Add change button
    let changeBtn = $('<span class="change-btn" style="margin-top:85px;"><i class="fa fa-pencil"></i></span>');
    changeBtn.on('click', function() {
        changeImage(base64String);
    });

    // Append buttons
    imageItem.append(removeBtn);
    imageItem.append(changeBtn);

    // Append image item to container
    imageContainer.append(imageItem);
}

// Function to remove image
function removeImage(base64String) {
    // Remove image from the display
    const imageItems = $('#imageContainer .image-item');
    imageItems.each(function() {
        let imgSrc = $(this).find('img').attr('src');
        if (imgSrc === base64String) {
            $(this).remove();
        }
    });

    // Remove image from the base64 array
    base64Images = base64Images.filter(img => img !== base64String);
}

// Function to change an image
function changeImage(base64String) {
    // Find the index of the image
    const index = base64Images.indexOf(base64String);

    if (index !== -1) {
        // Prompt for a new image file
        let fileInput = $('<input type="file" accept="image/*">');
        fileInput.click();
        fileInput.on('change', function(e) {
            let file = e.target.files[0];
            if (file) {
                // Read new file and convert to Base64
                let reader = new FileReader();
                reader.onload = function(event) {
                    let newBase64String = event.target.result;
                    // Replace the image in the array
                    base64Images[index] = newBase64String;
                    // Update the image in the display
                    $(imageContainer).find('img').eq(index).attr('src', newBase64String);

                    $('#imageContainer').html("");
                	for(var x = 0 ; x < base64Images.length ; x++)
                    {
                       displayImage(base64Images[x]);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

function tambahHeader(){
    get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
			$("#mode").val('tambah');

			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Tambah');	
			
			$("#dataGridVarian").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian/');
		    $("#dataGridVarian").DataTable().ajax.reload();
			
        	dataVarianLama = [];
        	base64Images = [];
        	$("#BASE64IMAGES").val(JSON.stringify(base64Images));
            $('#KATEGORIONLINE option:first').prop('selected', true);
        	$('#imageContainer').html("");
        	$("#BERAT").val("");
        	$("#PANJANG").val("");
        	$("#LEBAR").val("");
        	$("#TINGGI").val("");
        	$("#DESKRIPSI").val("");
        	$("#KATEGORI").val("");
		} else {
			Swal.fire({
				title            : 'Anda Tidak Memiliki Hak Akses',
				type             : 'warning',
				showConfirmButton: false,
				timer            : 1500
			});
		}
	});
    
}

function ubahHeader(row){
     get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.UBAH==1) {
			$("#mode").val('ubah');
			
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Ubah');
			
			$("#dataGridVarian").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian/'+encodeURIComponent(row.KATEGORI));
		    $("#dataGridVarian").DataTable().ajax.reload();
        	$("#BERAT").val(row.BERAT);
        	$("#PANJANG").val(row.PANJANG);
        	$("#LEBAR").val(row.LEBAR);
        	$("#TINGGI").val(row.TINGGI);
        	$("#DESKRIPSI").val(row.DESKRIPSI.replaceAll("\R\N","\r\n").replaceAll("???? ",""));
        	$("#KATEGORIONLINE").val(row.KATEGORIONLINE);
        	$("#KATEGORI").val(row.KATEGORI);
        	dataLama = row;
        	
        	dataVarianLama = [];
		    base64Images = [];
    		$("#BASE64IMAGES").val(JSON.stringify(base64Images));
    		$('#imageContainer').html("");
    		setTimeout(function() {
                
            $('#dataGridVarian').DataTable().rows().every(function () {
             
                var rowData = this.data();
                dataVarianLama.push(rowData);
                // $.ajax({
                //      type      : 'POST',
                //      url       : base_url+'Master/Data/Barang/getGambarBarang',
                //      data      : {"idbarang" : rowData.IDBARANG},
                //      dataType  : 'json',
                //      beforeSend: function (){
                //          //$.messager.progress();
                //      },
                //      success: function(gambar){
                //         for(var x = 0 ; x < gambar.length ; x++)
                //         {
                //           base64Images.push(gambar[x]);
                //           displayImage(gambar[x]);
                //         }
                //      }
                //  });
            })
            
        }, 1000);
    	
			
	    } else {
			Swal.fire({
				title            : 'Anda Tidak Memiliki Hak Akses',
				type             : 'warning',
				showConfirmButton: false,
				timer            : 1500
			});
		}
	});
    
}



function simpanHeader() {
    var tableVarian = $('#dataGridVarian').DataTable();
    tableVarian.rows().eq(0).each(function (index) {
         var row = tableVarian.row(index).data();
         
         if(dataLama.KATEGORIONLINE != $("#KATEGORIONLINE").val() || dataLama.KATEGORI != $("#KATEGORI").val() || dataLama.DESKRIPSI != $("#DESKRIPSI").val() || dataLama.BERAT != $("#BERAT").val() || dataLama.PANJANG != $("#PANJANG").val() || dataLama.LEBAR != $("#LEBAR").val() || dataLama.TINGGI != $("#TINGGI").val())
         {
             if(row.MODE == "")
             {
                 row.MODE = "ubah";
             }
         }
    });
	
	if(tableVarian.rows().data().length == 0 || $("#KATEGORIONLINE").val() == "" || $("#KATEGORI").val() == "" || $("#DESKRIPSI").val() == "" || $("#BERAT").val() == "" || $("#BERAT").val() == "0"){ // || base64Images.length == 0
        Swal.fire({ 
        	title            : "Terdapat Data Produk yang belum diisi",
        	type             : 'error',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
        $("#BASE64IMAGES").val(JSON.stringify(base64Images));
	
    	var dataVarian = [];
    	tableVarian.rows().eq(0).each(function (index) {
    	    if(tableVarian.row(index).data().MODE == "")
    	    {
    	        tableVarian.row(index).data().MODE = "ubah";
    	    }
    	    
            if (tableVarian.row(index).data().MODE != "") {
                var row = tableVarian.row(index).data();
                dataVarian.push(row);
            }
        });
		$("#datavarian").val(JSON.stringify(dataVarian));
        
        $.ajax({
            type      : 'POST',
            url       : base_url+'Master/Data/Barang/simpanAll',
            data      : $('#form_input :input').serialize(),
            dataType  : 'json',
            beforeSend: function (){
                //$.messager.progress();
            },
            success: function(msg){
                if (msg.success) {
                    Swal.fire({
                        title            : 'Simpan Data Sukses',
                        type             : 'success',
                        showConfirmButton: false,
                        timer            : 1500
                    });
                    $("#dataGrid").DataTable().ajax.reload();
                    tambahHeader();
                    $('.nav-tabs a[href="#tab_grid"]').tab('show');
                } else {
                    Swal.fire({
                        title            : msg.errorMsg,
                        type             : 'error',
                        showConfirmButton: false,
                        timer            : 1500
                    });
                }
            }
        });
	}
}

function hapusHeader(row){
    get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.HAPUS==1) {
		     if (row) {
    		     Swal.fire({
                		title: 'Anda Yakin Akan Menghapus Barang '+row.KATEGORI+' ?',
                		showCancelButton: true,
                		confirmButtonText: 'Yakin',
                		cancelButtonText: 'Tidak',
                		}).then((result) => {
                		/* Read more about isConfirmed, isDenied below */
                			if (result.value) {
                               
                    				$("#mode").val('hapus');
                    		
                                    $.ajax({
                    					type    : 'POST',
                    					dataType: 'json',
                    					url     : base_url+"Master/Data/Barang/hapusAll",
                    					data    : "kategori="+row.KATEGORI,
                    					cache   : false,
                    					success : function(msg){
                    						if (msg.success) {
                    							Swal.fire({
                    								title            : 'Barang dengan nama '+row.KATEGORI+' telah dihapus',
                    								type             : 'success',
                    								showConfirmButton: false,
                    								timer            : 1500
                    							});
                    							$("#dataGrid").DataTable().ajax.reload();
                    						
                    						} else {
                    								Swal.fire({
                    									title            : msg.errorMsg,
                    									type             : 'error',
                    									showConfirmButton: false,
                    									timer            : 1500
                    								});
                    						}
                                          }
                                       });
            	}
                		});
		     }
		
			
		} else {
			Swal.fire({
				title            : 'Anda Tidak Memiliki Hak Akses',
				type             : 'warning',
				showConfirmButton: false,
				timer            : 1500
			});
		}
	});
}

function tambah(){
	//clear form input
    $("#btn_simpan_detail").html('Tambah');
	$("#STATUS").prop('checked',true).iCheck('update');
	$("#IDBARANG").val("");
    $("#KODEBARANG").val("");            
    $("#NAMABARANG").val("");       
    $("#UKURANBARANG").val("");       
    $("#WARNABARANG").val("");   
    $("#BARCODE").val("");
	$("#SATUAN2").val("");
	$("#HARGABELI").val("");
	$("#HARGAJUAL").val("");
	$("#SKUSHOPEE").val("");
	$("#SKUTIKTOK").val("");
	
}

function tambahMassal(){
    $("#NAMABARANGMASSAL").val("");
    $("#SATUANMASSAL").val("");
    $("#HARGAJUALMASSAL").val("");
    $("#HARGABELIMASSAL").val("");
    $("#PILIHWARNAMASSAL").html("");
    $("#PILIHUKURANMASSAL").html("");
    
    var tableVarian = $('#dataGridVarian').DataTable();
    tableVarian.rows().eq(0).each(function (index) {
         var row = tableVarian.row(index).data();
         $("#NAMABARANGMASSAL").val(row.NAMABARANG.split("LITTLE TWISTY - ")[1].split(" | ")[0]);
         $("#SATUANMASSAL").val(row.SATUAN);
         $("#HARGAJUALMASSAL").val(row.HARGAJUAL);
         $("#HARGABELIMASSAL").val(row.HARGABELI);
        //  $("#PILIHWARNAMASSAL").val(row.NAMABARANG);
        //  $("#PILIHUKURANMASSAL").val(row.NAMABARANG);
    });
}

function simpanMassal(){
    if(!$("#NAMABARANGMASSAL").val().includes("LITTLE TWISTY - "))
    {
        $("#NAMABARANGMASSAL").val("LITTLE TWISTY - "+$("#NAMABARANGMASSAL").val());
    }
    var arrayWarna = [];
    var arrayUkuran = [];
    $('#PILIHWARNAMASSAL option:selected').each(function() {
        arrayWarna.push($(this).text());
    });
    
    $('#PILIHUKURANMASSAL option:selected').each(function() {
        arrayUkuran.push($(this).text());
    });
    
    if($("#NAMABARANGMASSAL").val() == "" || $("#SATUANMASSAL").val() == "" || $("#HARGAJUALMASSAL").val() == "" || $("#HARGAJUALMASSAL").val() == "0" || $("#HARGABELIMASSAL").val() == "" || $("#HARGABELIMASSAL").val() == "0"){
        Swal.fire({
        	title            : "Terdapat Data Massal yang belum diisi",
        	type             : 'error',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if(arrayWarna.length == 0 || arrayUkuran.length == 0)
    {   
        Swal.fire({
        	title            : "Isi Warna dan Ukuran minimal 1",
        	type             : 'error',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
        
        var table = $('#dataGridVarian').DataTable(); // Get DataTable instance
         
        var status = 0;
        if($("#STATUS").prop('checked'))
        {
            status = 1;
        }
        
        arrayWarna.forEach(function(warna, index) {
                
            arrayUkuran.forEach(function(ukuran, index) {
                 
                var namabarang = $("#NAMABARANGMASSAL").val()+" | "+warna+" | SIZE "+ukuran;
                var barangMassal = $("#NAMABARANGMASSAL").val().split(" ");
                var SKUBARCODE = ""; 
               
                if(barangMassal.length < 2)
                {
                    SKUBARCODE = "LTW / "+barangMassal[barangMassal.length-1]+" "+warna.substring(0,4)+ukuran; 
                }
                else
                {
                    SKUBARCODE = "LTW / "+barangMassal[barangMassal.length-2]+" "+warna.substring(0,4)+ukuran; 
                }
                
                let randomDecimal = Math.random();
                // Define new row data
                var newRowData = {
                    SKUSHOPEE: SKUBARCODE,
                    SKUTIKTOK: SKUBARCODE,
                    IDBARANG: "X"+warna+"X"+$("#UKURANBARANG").val()+"X"+randomDecimal.toString(),
                    WARNA: warna,
                    SIZE: ukuran,
                    BARCODE:SKUBARCODE,
                    KODEBARANG: "",
                    NAMABARANG: namabarang,
                    SATUAN: $("#SATUANMASSAL").val(),
                    HARGAJUAL: $("#HARGAJUALMASSAL").val(),
                    HARGABELI: $("#HARGABELIMASSAL").val(),
                    USERBUAT: 'AUTOGEN',
                    TGLENTRY: 'AUTOGEN',
                    STATUS: status,
                    MODE:"tambah"
                };
            
                table.row.add(newRowData).draw();
            });
        });
    	$("#modal-set-varian").modal('hide');	
    }
}

function simpan(){
    if(!$("#NAMABARANG").val().includes("LITTLE TWISTY - "))
    {
        $("#NAMABARANG").val("LITTLE TWISTY - "+$("#NAMABARANG").val());
    }
    
    if($("#NAMABARANG").val() == "" || $("#SKUSHOPEE").val() == "" || $("#SKUTIKTOK").val() == "" || $("#SATUAN2").val() == "" || $("#HARGAJUAL").val() == "" || $("#HARGAJUAL").val() == "0" || $("#HARGABELI").val() == "" || $("#HARGABELI").val() == "0"){
        Swal.fire({
        	title            : "Terdapat Data Varian yang belum diisi",
        	type             : 'error',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
    
        var table = $('#dataGridVarian').DataTable(); // Get DataTable instance
         
        var status = 0;
        if($("#STATUS").prop('checked'))
        {
            status = 1;
        }
        var warna = "";
        if($("#WARNABARANG").val() != "")
        {
            warna = " | "+$("#WARNABARANG").val();
        }
        var ukuran = "";
        if($("#UKURANBARANG").val() != "0" && $("#UKURANBARANG").val() != "")
        {
            ukuran = " | SIZE "+$("#UKURANBARANG").val();
        }
        var namabarang = $("#NAMABARANG").val()+warna+ukuran;
        
        //CEK IDBARANG SEBELUMNYA ADA ATAU TIDAK
        var adaBarang = false;
        for(var x = 0 ; x < dataVarianLama.length;x++)
        {
           if(dataVarianLama[x].IDBARANG == $("#IDBARANG").val())
           {
               adaBarang = true;
           }
        }
        
        if(!adaBarang)
        {
            //CEK SUDAH ADA BARANG NYA DI GRID ATAU TIDAK
            var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
                return table.cell(rowIdx, 1).data() == $("#IDBARANG").val(); // Match by IDBARANG column
            })[0];
            
            if (rowIndex !== undefined) {
            }
            else
            {
                let randomDecimal = Math.random();
                $("#IDBARANG").val("X"+$("#WARNABARANG").val()+"X"+$("#UKURANBARANG").val()+"X"+randomDecimal.toString());
            }
        }
        
        // Define new row data
        var newRowData = {
            SKUSHOPEE: $("#SKUSHOPEE").val(),
            SKUTIKTOK: $("#SKUTIKTOK").val(),
            IDBARANG: $("#IDBARANG").val(),
            WARNA: $("#WARNABARANG").val(),
            SIZE: $("#UKURANBARANG").val(),
            BARCODE: $("#BARCODE").val(),
            KODEBARANG: $("#KODEBARANG").val(),
            NAMABARANG: namabarang,
            SATUAN: $("#SATUAN2").val(),
            HARGAJUAL: $("#HARGAJUAL").val(),
            HARGABELI: $("#HARGABELI").val(),
            USERBUAT: 'AUTOGEN',
            TGLENTRY: 'AUTOGEN',
            STATUS: status,
            MODE:(adaBarang?"ubah":"tambah")
        };
    
        // Add the row to the table
        if($("#btn_simpan_detail").html() == "Tambah")
        {
            table.row.add(newRowData).draw();
        }
        else
        {
            // Find the row based on ID
            var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
                return table.cell(rowIdx, 1).data() == $("#IDBARANG").val(); // Match by IDBARANG column
            })[0];
            
            if (rowIndex !== undefined) {
                var row = table.row(rowIndex); // This returns a row object
                // Set the updated data back to the row
                row.data(newRowData);
            
                // Redraw the table to reflect the updated data
                row.invalidate().draw();
            }
        }
        
    	$("#modal-varian").modal('hide');	
    }
}

function ubah(row){
	//load row data to form
	if(row.STATUS == 0) $("#STATUS").prop('checked',false).iCheck('update');
	else if(row.STATUS == 1) $("#STATUS").prop('checked',true).iCheck('update');
	
	if(row.STOK == 'TIDAK') $("#STOK").prop('checked',false).iCheck('update');
	else if(row.STOK == 'YA') $("#STOK").prop('checked',true).iCheck('update');
	
	var barang = row.NAMABARANG.split("LITTLE TWISTY - ")[1].split(" | ");

	$("#IDBARANG").val(row.IDBARANG);
	$("#KODEBARANG").val(row.KODEBARANG);
    $("#NAMABARANG").val(barang[0]);
    $("#WARNABARANG").val(row.WARNA);
    $("#UKURANBARANG").val(row.SIZE);
	$("#SATUAN2").val(row.SATUAN);
	$("#HARGABELI").val(row.HARGABELI);
	$("#HARGAJUAL").val(row.HARGAJUAL);
	$("#SKUSHOPEE").val(row.SKUSHOPEE);
	$("#SKUTIKTOK").val(row.SKUTIKTOK);
	$("#BARCODE").val(row.BARCODE);
    $("#btn_simpan_detail").html('Ubah');

}

function hapus(row){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.HAPUS==1) {
		     if (row) {
    		     Swal.fire({
                		title: 'Anda Yakin Akan Menghapus Barang '+row.NAMABARANG+' ?',
                		showCancelButton: true,
                		confirmButtonText: 'Yakin',
                		cancelButtonText: 'Tidak',
                		}).then((result) => {
                		/* Read more about isConfirmed, isDenied below */
                			if (result.value) {
                			    
                			       //CEK IDBARANG SEBELUMNYA ADA ATAU TIDAK
                                var adaBarang = false;
                                for(var x = 0 ; x < dataVarianLama.length;x++)
                                {
                                   if(dataVarianLama[x].IDBARANG == row.IDBARANG)
                                   {
                                       adaBarang = true;
                                   }
                                }
                                
                                if(!adaBarang)
                                {
                    			    var table = $('#dataGridVarian').DataTable(); // Get DataTable instance
                                    var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
                                        return table.cell(rowIdx, 1).data() == $("#IDBARANG").val(); // Match by IDBARANG column
                                    })[0];
                                    
                                    if (rowIndex !== undefined) {
                                        table.row(rowIndex).remove().draw(); 
                                        Swal.fire({
                    						title            : 'Barang dengan nama '+row.NAMABARANG+' telah dihapus',
                    						type             : 'success',
                    						showConfirmButton: false,
                    						timer            : 1500
                    					});
                                    }
                                }
                                else
                                {
                               
                    				$("#mode").val('hapus');
                    		
                                    $.ajax({
                    					type    : 'POST',
                    					dataType: 'json',
                    					url     : base_url+"Master/Data/Barang/hapus",
                    					data    : "id="+row.IDBARANG + "&kode="+row.KODEBARANG,
                    					cache   : false,
                    					success : function(msg){
                    						if (msg.success) {
                    							Swal.fire({
                    								title            : 'Barang dengan nama '+row.NAMABARANG+' telah dihapus',
                    								type             : 'success',
                    								showConfirmButton: false,
                    								timer            : 1500
                    							});
                    							$("#dataGridVarian").DataTable().ajax.reload();
                    								dataVarianLama = [];
                                        		    base64Images = [];
                                            		$("#BASE64IMAGES").val(JSON.stringify(base64Images));
                                            		$('#imageContainer').html("");
                                            		setTimeout(function() {
                                                        
                                                    $('#dataGridVarian').DataTable().rows().every(function () {
                                                     
                                                        var rowData = this.data();
                                                        dataVarianLama.push(rowData);
                                                            $.ajax({
                                                                 type      : 'POST',
                                                                 url       : base_url+'Master/Data/Barang/getGambarBarang',
                                                                 data      : {"idbarang" : rowData.IDBARANG},
                                                                 dataType  : 'json',
                                                                 beforeSend: function (){
                                                                     //$.messager.progress();
                                                                 },
                                                                 success: function(gambar){
                                                                    for(var x = 0 ; x < gambar.length ; x++)
                                                                    {
                                                                       base64Images.push(gambar[x]);
                                                                       displayImage(gambar[x]);
                                                                    }
                                                                 }
                                                             });
                                                        });
                                                    
                                                    }, 1000);
                    						} else {
                    								Swal.fire({
                    									title            : msg.errorMsg,
                    									type             : 'error',
                    									showConfirmButton: false,
                    									timer            : 1500
                    								});
                    						}
                    					}
                    				});
                                }
                			} 
                         });
            	}
			
		} else {
			Swal.fire({
				title            : 'Anda Tidak Memiliki Hak Akses',
				type             : 'warning',
				showConfirmButton: false,
				timer            : 1500
			});
		}
	});
}

function get_akses_user(kodemenu, callback) {
	$.ajax({
		dataType: "json",
		type: 'POST',
		url: base_url+"Master/Data/User/getUserAkses",
		data: "kodemenu=" + kodemenu+ " &iduser=<?= $_SESSION[NAMAPROGRAM]['IDUSER']?>",
		cache: false,
		success: function (msg) {
			if (msg.success) {
				callback(msg.data);
			} else {
				$.messager.alert('Error', msg.errorMsg, 'error');
			}
		}
	});
}

function openFileExcelUrutan(){
     document.getElementById('excelFileUrutan').click();
}

function importExcelUrutan(){
    document.getElementById("excelFormUrutan").submit();
}

</script>
