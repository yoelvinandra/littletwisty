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
   
   .align-middle {
    vertical-align: middle !important;
    }
    
    #modal-barang-shopee .modal-dialog {
        min-width: 1000px;
        margin: 30px auto;
    }
    
    #modal-barang-shopee .modal-content {
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    #modal-barang-shopee .modal-body {
        overflow-y: auto;
        flex: 1 1 auto;
    }
</style>

<!-- Main content -->
<section class="content">
    
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12"  style="border:0px; padding:0px 15px 0px 15px;">
            <div class="box" style="border:0px; padding:0px; margin:0px;">
            <div class="box-header form-inline" style="padding:0px;">
                <button class="btn btn-success" onclick="javascript:tambahShopee()">Tambah</button>
            	<div class="pull-right" style="width:170px; margin-right:0px;">
                	<div class="input-group " >
                	 <div class="input-group-addon">
                		 <i class="fa fa-filter"></i>
                	 </div>
                		<select id="cb_barang_status" name="cb_barang_status" class="form-control "  panelHeight="auto" required="true">
                			<option value="SEMUA">SEMUA </option>
                			<option value="NORMAL">NORMAL</option>
                			<option value="UNLIST">UNLIST</option>
                			<option value="BANNED">BANNED</option>
                			<option value="REVIEWING">REVIEW</option>
                			<option value="SELLER_DELETE">SELLER DELETE</option>
                			<option value="SHOPEE_DELETE">SHOPEE DELETE</option>
                		</select>
                	</div>
                </div>
            </div>
            <br>          			
            <table id="dataGridShopee" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                <!-- class="table-hover"> -->
                <thead>
                <tr>
                    <th width="80px"></th>
                    <th>Nama Produk</th>
                    <th width="100px">Ada Varian</th>
                    <th width="40px">Tgl. Input</th>
                    <th width="100px">Status</th>								
                </tr>
                </thead>
            </table>  
        </div>
    </div>
    <!-- /.col -->
  </div>
  
  <div class="modal fade" id="modal-barang-shopee" >
  	<div class="modal-dialog modal-lg">
  	<div class="modal-content">
  		<div class="modal-body">
              <div class="box-body">
                  <input type="hidden" id="IDBARANGSHOPEE" name="IDBARANGSHOPEE">
                  <div class="row">
                      <div class="form-group col-md-12">
                          <h3 style="font-weight:bold;" class="form-group">Tambah Produk</h3>
                          <label>Hubungkan Master Barang <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                          <select id="BARANGSHOPEE" name="BARANGSHOPEE" class="form-control select2" style="border:1px solid #B5B4B4; border-radius:1px; width:100%; height:32px; padding-left:12px; padding-right:12px;">
        
                          </select>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="form-group col-md-8">
                          <h4 style="font-weight:bold;">Informasi Produk<label class="pull-right">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="flat-blue" id="UNLISTED" name="UNLISTED" value="1">&nbsp; Unlisted</label></h4>
                          <div class="row">
                                <div class="col-md-12">
                                    <label>Kategori Shopee <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                    <select id="KATEGORISHOPEE" class="form-control select2" name="KATEGORISHOPEE" style="border:1px solid #B5B4B4; border-radius:1px; width:100%; height:32px; padding-left:12px; padding-right:12px;">
    
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <label>Nama Produk <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                    <input type="text" class="form-control" id="NAMASHOPEE" name="NAMASHOPEE" placeholder="Nama Produk" readonly>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <label>Deskripsi <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                    <textarea class="form-control" rows="9" id="DESKRIPSISHOPEE" name="DESKRIPSISHOPEE" placeholder="Deskripsi....." readonly></textarea>
                                </div>
                                 <div class ="form-group col-md-12">
                                  <br>
                                  <label style="font-weight:bold; margin-bottom:-5px;">Varian Produk<i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                  <div style="margin-top:-28px !important;" >
                                      <table id="dataGridVarianShopee" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                          <!-- class="table-hover"> -->
                                          <thead>
                                          <tr>
                                              <th></th>
                                              <th>Nama</th>
                                              <th width="100px">Harga</th>
                                              <th width="200px">SKU</th>							
                                          </tr>
                                          </thead>
                                      </table>  
                                    </div>
                              </div>
                          </div>
                      </div>
                      <div class="form-group col-md-4">
                          <h4 style="font-weight:bold;">Pengiriman</h4>
                          <div class="row" style="margin-top:10px;">
                              <div class="col-md-12">
                          	    <label>Berat (gram) <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                  <input type="text" class="form-control" id="BERATSHOPEE" name="BERATSHOPEE" placeholder="Dalam Gram" readonly>
                              </div>
                          </div>
                          <div class="row">
                              <br>
                              <div class="col-md-4">
                                  <label>Panjang</label>
                                  <input type="text" class="form-control" id="PANJANGSHOPEE" name="PANJANGSHOPEE" placeholder="Cm" readonly>
                              </div>
                              <div class="col-md-4">
                                  <label>Lebar</label>
                                  <input type="text" class="form-control" id="LEBARSHOPEE" name="LEBARSHOPEE" placeholder="Cm" readonly>
                              </div>
                              <div class="col-md-4">
                                  <label>Tinggi</label>
                                  <input type="text" class="form-control" id="TINGGISHOPEE" name="TINGGISHOPEE" placeholder="Cm" readonly>
                              </div>
                          </div>
                          <div class="row" style="height:200px;">
                               <br>
                               <div class="col-md-12">
                                   <label>Pilihan Pengiriman <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Min 1)</i></label> 
                                   <div style="margin-top:-7px;">
                                        <table id="dataGridPengirimanShopee" class="table table-bordered table-striped table-hover display nowrap" width="200px">
                                            <!-- class="table-hover"> -->
                                            <thead style="display:none;">
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table> 
                                    </div>
                                </div>
                          </div>
                          <div class="row" style="margin-top:45px;">
                              <br>
                              <div class="col-md-12">
                                   <label>Spesifikasi <i style="color:grey;"></i></label> 
                                   <div style="margin-top:6px;">
                                        <table id="dataGridAttributShopee" class="table table-bordered table-striped table-hover display nowrap" width="200px">
                                            <!-- class="table-hover"> -->
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Keterangan</th>
                                                    <th>Pilihan</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                    </div>
                                </div>
                          </div>
                      </div>
                  </div>
                 <div class="row">
                   <div class ="form-group col-md-12">
                       <h4 style="font-weight:bold; margin-bottom:-5px;">Gambar Produk<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Min 1)</i></h4>
                       <br>
                       <table id="gambarprodukshopee">
                       </table>  
                   </div>
                   <div class ="form-group col-md-12">
                       <h4 style="font-weight:bold; margin-bottom:-5px;">Gambar Varian<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Setiap Varian)</i></h4>
                       <br>
                       <table id="gambarvarianshopee">
                       </table>    
                   </div>
                 </div>
                 <div class="row" id="TABELUKURANSETTINGSHOPEE">
                   <div class ="form-group col-md-12">
                       <h4 style="font-weight:bold; margin-bottom:-5px; margin-top:10px;">Tabel Ukuran<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></h4>
                       <br>
                       
                        <select id="TEMPLATESHOPEE" class="form-control select2 pull-left" name="TEMPLATESHOPEE" style="border:1px solid #B5B4B4; border-radius:1px; width:250px; height:32px; padding-left:12px; padding-right:12px;">
    
                        </select>
                       <label>
                        &nbsp;&nbsp;&nbsp;<input type="checkbox" class="flat-blue" id="SIZETEMPLATESHOPEE" name="SIZETEMPLATESHOPEE" value="1" checked>&nbsp; Pilih Template</label>

                       <table id="gambarukuranprodukshopee" style="margin-top:10px;">
                          
                       </table>  
                   </div>
                 </div>
              </div>
              <div class="box-footer">
                  <button type="button" id="btn_simpan_detail" class="btn btn-success" onclick="javascript:simpanShopee()">Simpan</button>
                  </div>
              </div>
  		</div>
  	</div>
  </div>
  
  <div class="modal fade" id="modal-lebih-jelas-shopee" style="z-index:999999999999999999999999999;">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; <span id="titleLebihJelasShopee" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_shopee'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmShopee()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewLebihJelasShopee">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
 </div>
  
 <div class="modal fade" id="modal-detail-pengiriman-shopee">
	<div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title"  style="float:left; padding-top:4px;" id="NAMAPENGIRIMANSHOPEE"></h4>
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
                   <label>Keterangan</label>
                   <div id="KETERANGANPENGIRIMANSHOPEE">-</div>
                   <br>
                   <label>Jenis Pengiriman</label>
                   <div id="JENISPENGIRIMANSHOPEE">-</div>
                   <br>
                </div>
      	    </div>
      	  </div>
	    </div>
	</div>
  </div>
  
  <div class="modal fade" id="modal-detail-attribut-shopee">
	<div class="modal-dialog" style="max-width:400px;">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;" onclick="kembaliAttribut()">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title"  style="float:left; padding-top:4px;" id="NAMAATTRIBUTSHOPEE" ></h4>
            <button id='btn_simpan_attribut_shopee'  style="float:right;" class='btn btn-success' onclick="simpanAttribut()">Simpan</button>
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
                   <div id="JENISATTRIBUTSHOPEE">-</div>
                   <br>
                </div>
      	    </div>
      	  </div>
	    </div>
	</div>
  </div>
  <!-- /.row (main row) -->
</section>
<input type="hidden" id="statusShopee">
<!-- /.content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
var pengirimanShopee = [];
var attributShopee = [];
var attributShopeeOld = [];
var dataMasterShopee = [];
var warna = [];
var ukuran = [];
$(document).ready(function() {
    loading();
    $("#statusShopee").val('NORMAL,UNLIST,BANNED,REVIEWING,SELLER_DELETE,SHOPEE_DELETE');
    $("#TEMPLATESHOPEE").select2();
    //MENAMPILKAN TRANSAKSI
    $("#cb_barang_status").change(function(event){
        loading();
    	if($(this).val()  == 'SEMUA' )
    	{
    		$("#statusShopee").val('NORMAL,UNLIST,BANNED,REVIEWING,SELLER_DELETE,SHOPEE_DELETE');
    	}	
    	else
    	{
    		$("#statusShopee").val($(this).val());
    	}
    	$("#dataGridShopee").DataTable().ajax.reload();
    	
    });
    
    $('#SIZETEMPLATESHOPEE').on('ifChanged', function (event) {
        let isChecked = $(this).prop('checked');
    
        if (isChecked) {
            $("#TEMPLATESHOPEE").removeAttr("disabled");
            $("#gambarukuranprodukshopee").hide();
        } else {
            $("#TEMPLATESHOPEE").attr("disabled", "disabled");
            $("#gambarukuranprodukshopee").show();
        }
        
        $("#TEMPLATESHOPEE").val(0);
        $("#TEMPLATESHOPEE").trigger('change');
    });
    
    // $("#TEMPLATESHOPEE").change(function(){
        
    // })

    $('#dataGridShopee').DataTable({
        'pageLength'  : 25,
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Shopee/dataGridBarang',
			dataSrc: "rows",
			type   : "POST",
        	data   : function(e){
        	    e.status 		 = getStatusShopee(index);
        	},
		},
        columns:[
            // { data: 'IDBARANG', visible: false},
            {data: ''},
            {data: 'NAMABARANG'},
            {data: 'VARIAN', className:"text-center"},
            {data: 'TGLENTRY', className:"text-center"},
            {data: 'STATUS', className:"text-center"},
        ],
		'columnDefs': [
			{
			    "targets": 0,
                "data": null,
                "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
			}
		],
    });

    //DAPATKAN INDEX
	$('#dataGridShopee tbody').on( 'click', 'button', function () {
		var row = $('#dataGridShopee').DataTable().row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah"){ ubahHeader(row); }
		if(mode == "btn_hapus"){ hapusHeader(row); }
	});
    
    $('#dataGridShopee').DataTable().on('xhr.dt', function () {
        Swal.close();
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
    
    $('#dataGridVarianShopee').DataTable({
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
            {data: 'IDBARANG', visible:false},
            {data: 'NAMABARANG'},
            {data: 'HARGAJUAL', render:format_number, className:"text-right"},
            {data: 'SKUSHOPEE', className:"text-center"},
        ],
    	'columnDefs': [
    	    {
    		    "targets": 1,
                "render" :function (data) 
    			{
    			    var array = data.split(" | ");
    			    if(array.length == 1)
    			    {
    				    return array[0];
    			    }
    			    else
    			    {
    				    return array[1]+" | "+array[2];
    			    }
    			},		
    		},
    	]
    });
    
    $('#dataGridPengirimanShopee').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
    	"scrollX"	  : true,
    	ajax		  : {
    		url    : base_url+'Shopee/getPengiriman',
    		dataSrc: "rows",
    	},
        columns:[
            {data: 'CHOOSEPENGIRIMAN', className:"text-center"},
            {data: 'IDPENGIRIMAN', visible:false},
            {data: 'NAMAPENGIRIMAN'},
        ],
    	'columnDefs': [
    	     {
    		    "targets": 0,
                "render" :function (data) 
    			{
    			    if(data == 1)
    			    {
    				    return "<input type='checkbox' class='choose-row' checked>";
    			    }
    			    else
    			    {
    				    return "<input type='checkbox' class='choose-row'>";
    			    }
    			},		
    		},
    		{
    		    "targets": 2,
                "render" :function (data,display,row) 
    			{
    			    return data+'<span class="pull-right" onclick="checkDetailKirim('+(row.IDPENGIRIMAN)+')">Detail</span>';
    			},		
    		},
    	]
    });
    
    $('#dataGridPengirimanShopee').DataTable().on('xhr.dt', function () {
        setTimeout(() => {
            pengirimanShopee = $('#dataGridPengirimanShopee').DataTable().rows().data().toArray();
        }, "500");
    });
    
    $('#dataGridAttributShopee').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
    	"scrollX"	  : true,
    	ajax		  : {
    		url    : base_url+'Shopee/getAttribut',
    		dataSrc: "rows",
    	},
        columns:[
            {data: 'IDATTRIBUT', visible:false},
            {data: 'NAMAATTRIBUT'},
            {data: 'VALUEATTRIBUT'},
        ],
    	'columnDefs': [
    	    {
                "targets": 1, // Assuming VALUEATTRIBUT is column index 2
                "width": "90px",
                "render" :function (data,display,row) 
    			{
    	            return `<div style='width:90px; white-space: pre-wrap;       
                                                white-space: -moz-pre-wrap;  
                                                white-space: -pre-wrap;      
                                                white-space: -o-pre-wrap;     
                                                word-wrap: break-word;'>`+data+`</div>`;
    			}
            },
    		{
    		    "targets": -1,
                "render" :function (data,display,row) 
    			{
    			    
    			   var html = row.VALUEATTRIBUT;
    			   if(data.length > 18)
    			   {
    			     html = row.VALUEATTRIBUT.substr(0,18)+"...";    
    			   }
    			    
    			   if(row.REQUIRED){
    			       html += '<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i>';
    			   }
    			   
    			   html += '<span class="pull-right" onclick="pilihDataAttribut('+(row.IDATTRIBUT)+')">Pilih</span>';
    			   return html;
    			},		
    		},
    	]
    });
    
    $('#dataGridAttributShopee').DataTable().on('xhr.dt', function () {
        setTimeout(() => {
            attributShopee = $('#dataGridAttributShopee').DataTable().rows().data().toArray();
            attributShopeeOld = $('#dataGridAttributShopee').DataTable().rows().data().toArray();
        }, "500");
    });
    
    $('#dataGridVarianShopee').DataTable().on('xhr.dt', function () {
        if($("#BARANGSHOPEE").val() != 0)
        {
            setTimeout(() => {
                
                //GAMBAR PRODUK
               	var htmlGambarProduk = "<tr>";
                   var utama = "Gambar Utama";
                   
                   for(var y = 0 ; y < 9 ;y++)
                   {
                       var marginRight = "30px";
                       
                       if(y % 9 == 0 && y != 0)
                       {
                           marginRight = "";
                       }
                       
                       if(y % 5 == 0 && y != 0)
                       {
                           htmlGambarProduk +="</tr><tr>";
                       }
                       
                       htmlGambarProduk += `
                                       <td>
                                           <input type="file" id="file-input-shopee-`+y+`" accept="image/*,video/*" style="display:none;" value="">
                                           <input type="hidden"  id="format-input-shopee-`+y+`" value="">
                                           <input type="hidden"  id="index-input-shopee-`+y+`" value="`+y+`">
                                           <input type="hidden"  id="src-input-shopee-`+y+`" value="">
                                           <input type="hidden"  id="keterangan-input-shopee-`+y+`" value="Gambar Produk `+(y+1).toString()+`">
                                           <input type="hidden"  id="id-input-shopee-`+y+`" value="">
                                           
                                           <div style="margin-bottom:20px;">
                                               <img id="preview-image-shopee-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; margin-right:`+marginRight+`; cursor:pointer; border:2px solid #dddddd;'>
                                               <div style="text-align:center; margin-right:`+marginRight+`"><b>`+utama+`</b><br>
                                               <span id="ubahGambarProdukShopee-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                               &nbsp;
                                               <span id="hapusGambarProdukShopee-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                               </div>
                                           </div>
                                       </td>`;  
                                       
                       utama = "";
                   
                   }
                   htmlGambarProduk += "</tr>";
                   $("#gambarprodukshopee").html(htmlGambarProduk);
                   $("#gambarprodukshopee").css('margin-bottom','-40px');
                   
                   for(var y = 0 ; y < 9 ;y++)
                   {
                       const fileInput = document.getElementById('file-input-shopee-'+y);
                       const previewImage = document.getElementById('preview-image-shopee-'+y);
                       const title = document.getElementById('keterangan-input-shopee-'+y);
                       const format = document.getElementById('format-input-shopee-'+y);
                       const index = document.getElementById('index-input-shopee-'+y);
                       const url =  document.getElementById('src-input-shopee-'+y);
                       const id = document.getElementById('id-input-shopee-'+y);
                       
                       const ubahImage = document.getElementById('ubahGambarProdukShopee-'+y);
                       const hapusImage = document.getElementById('hapusGambarProdukShopee-'+y);
                       
                       previewImage.addEventListener('click', () => {
                         if(fileInput.value != "")
                         {
                             lihatLebihJelasShopee(format.value,title.value,url.value);
                         }
                         else
                         {
                           fileInput.click();
                         }
                       });
                       
                       ubahImage.addEventListener('click', () => {
                         fileInput.click();
                       });
                       
                       hapusImage.addEventListener('click', () => {
                         fileInput.value = '';
                         format.value = '';
                         previewImage.src = base_url+"/assets/images/addphoto.webp";
                         url.value = "";
                         id.value = "";
                         
                         ubahImage.style.display = 'none';
                         hapusImage.style.display = 'none';
                       });
                       
                       fileInput.addEventListener('change', () => {
                         const file = fileInput.files[0];
                         if (!file) return;
               
                         // Jika file adalah gambar
                         if (file.type.startsWith('image/')) {
                           const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                       
                           if (!allowedTypes.includes(file.type.toLowerCase())) {
                               fileInput.value = '';
                             Swal.fire({
                               title: 'Format gambar tidak didukung (hanya jpg/jpeg/png)',
                               icon: 'warning',
                               showConfirmButton: false,
                               timer: 2000
                             });
                             return;
                           }
                       
                           const maxSizeMB = 10;
                           if (file.size > maxSizeMB * 1024 * 1024) {
                               fileInput.value = '';
                             Swal.fire({
                               title: 'Ukuran gambar melebihi 10MB',
                               icon: 'warning',
                               showConfirmButton: false,
                               timer: 2000
                             });
                             return;
                           }
                       
                           // Upload file asli ke server
                           const formData = new FormData();
                           formData.append('index', index.value);
                           formData.append('kode', $("#BARANGSHOPEE").val()+"_"+y);
                           formData.append('file', file);
                           formData.append('tipe', 'GAMBAR');
                           formData.append('size', file.size);
                           formData.append("reason","produk");
                       
                           loading();
                           
                           $.ajax({
                             type: 'POST',
                             url: base_url + 'Shopee/uploadLocalUrl/',
                             data: formData,
                             contentType: false,
                             processData: false,
                             dataType: 'json',
                             success: function (msg) {
                               Swal.close();
                               if (msg.success) {
                                format.value = "GAMBAR";
                                previewImage.src = msg.url;
                                url.value =  msg.url;
                                id.value = msg.id;
                       
                                ubahImage.style.display = '';
                                hapusImage.style.display = '';
                               }
                               else
                               {
                                   fileInput.value = '';
                               }
                             },
                             error: function (xhr, status, error) {
                               fileInput.value = '';
                               Swal.fire({
                                 title: 'Upload gagal!',
                                 text: error,
                                 icon: 'error'
                               });
                             }
                           });
                         }
                         // Jika file adalah video
                        //   else if (file.type.startsWith('video/')) {
                        //     format.value = "VIDEO";
                        //     const video = document.createElement("video");
                        //     video.preload = "metadata";
                       
                        //     video.onloadedmetadata = function () {
                        //       window.URL.revokeObjectURL(video.src);
                       
                        //       if (parseInt(video.duration) > 60) {
                        //         Swal.fire({
                        //         	title            : 'Durasi Min 1 Menit',
                        //         	type             : 'warning',
                        //         	showConfirmButton: false,
                        //         	timer            : 2000
                        //         });
                        //         fileInput.value = ""; // Kosongkan input
                        //         format.value = "";
                        //         return;
                        //       }
                             
                        //       const maxSizeMB = 10;
                        //       if (file.size > maxSizeMB * 1024 * 1024) {
                        //           fileInput.value = '';
                        //          Swal.fire({
                        //           title: 'Ukuran video melebihi 10MB',
                        //           icon: 'warning',
                        //           showConfirmButton: false,
                        //           timer: 2000
                        //          });
                        //          return;
                        //       }
                             
                        //      // Upload file asli ke server
                        //       const formData = new FormData();
                        //       formData.append('index', index.value);
                        //       formData.append('kode', $("#BARANGSHOPEE").val()+"_"+y);
                        //       formData.append('file', file);
                        //       formData.append('tipe', 'VIDEO');
                        //       formData.append('size', file.size);
                        //       formData.append("reason","produk");
                           
                        //         loading();
                               
                        //         $.ajax({
                        //           type: 'POST',
                        //           url: base_url + 'Shopee/uploadLocalUrl/',
                        //           data: formData,
                        //           contentType: false,
                        //           processData: false,
                        //           dataType: 'json',
                        //           success: function (msg) {
                        //             Swal.close();
                        //             if (msg.success) {
                        //              format.value = "VIDEO";
                        //              previewImage.src =  base_url+"/assets/images/video.webp";
                        //              url.value =  msg.url;
                           
                        //              ubahImage.style.display = '';
                        //              hapusImage.style.display = '';
                        //             }
                        //             else
                        //             {
                        //                 fileInput.value = '';
                        //             }
                        //           },
                        //           error: function (xhr, status, error) {
                        //             fileInput.value = '';
                        //             Swal.fire({
                        //               title: 'Upload gagal!',
                        //               text: error,
                        //               icon: 'error'
                        //             });
                        //           }
                        //         });
                        //     };
                       
                        //     video.onerror = () => {
                        //       Swal.fire({
                        //         	title            : 'Gagal memuat video dari file',
                        //         	type             : 'warning',
                        //         	showConfirmButton: false,
                        //         	timer            : 2000
                        //         });
                        //       fileInput.value = "";
                        //       format.value = "";
                        //     };
                       
                        //     video.src = URL.createObjectURL(file);
                        //   }
                       
                         // Tipe file tidak valid
                         else {
                            Swal.fire({
                               	title            : 'Hanya mendukung file Gambar dan Video',
                               	type             : 'warning',
                               	showConfirmButton: false,
                               	timer            : 2000
                           });
                         }
                       });
                   }
               	
               	
               	//GAMBAR VARIAN
               	var varian = $('#dataGridVarianShopee').DataTable().rows().data().toArray();
              
               	warna = [];
               	ukuran = [];
               	for(var y = 0 ; y < varian.length; y++)
               	{
               	    var array = varian[y].NAMABARANG.split(" | ");
               	    var tempWarna = "";
               	    var tempUk = "";
               		if(array.length == 1)
               		{
               		    tempWarna = array[0];
               		}
               		else
               		{
               		    tempWarna = array[1];
               		    tempUk = array[2].split("SIZE ")[1];
               		}
               		
               		adaWarna = false;
               		for(var w = 0 ; w < warna.length; w++)
               		{
               		    if(warna[w] == tempWarna)
               		    {
               		        adaWarna = true;
               		    }
               		}
               		
               		if(!adaWarna)
               		{
               		    warna.push(tempWarna);
               		}
               		
               		adaUkuran = false;
               		for(var u = 0 ; u < ukuran.length; u++)
               		{
               		    if(ukuran[u] == tempUk)
               		    {
               		        adaUkuran = true;
               		    }
               		}
               		
               		if(!adaUkuran)
               		{
               		    ukuran.push(tempUk);
               		}
               	}
               	
               	var htmlGambarVarian = "<tr>";
                       
                   for(var y = 0 ; y < warna.length ;y++)
                   {
                        var marginRight = "30px";
                        
                        if(y % 9 == 0 && y != 0)
                        {
                            marginRight = "";
                        }
                        
                        if(y % 5 == 0 && y != 0)
                        {
                            htmlGambarVarian +="</tr><tr>";
                        }
                        
                        htmlGambarVarian += `
                                        <td>
                                            <input type="file" id="file-input-varian-shopee-`+y+`" accept="image/*,video/*" style="display:none;" value="">
                                            <input type="hidden"  id="format-input-varian-shopee-`+y+`" value="">
                                            <input type="hidden"  id="index-input-varian-shopee-`+y+`" value="`+y+`">
                                            <input type="hidden"  id="src-input-varian-shopee-`+y+`" value="">
                                            <input type="hidden"  id="keterangan-input-varian-shopee-`+y+`" value="Gambar Varian `+warna[y]+`">
                                            <input type="hidden"  id="id-input-varian-shopee-`+y+`" value="">
                                           
                                            <div style="margin-bottom:20px;">
                                                <img id="preview-image-varian-shopee-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; margin-right:`+marginRight+`; cursor:pointer; border:2px solid #dddddd;'>
                                                <div style="text-align:center; margin-right:`+marginRight+`"><b>`+warna[y]+`</b><br>
                                                <span id="ubahGambarVarianShopee-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                                &nbsp;
                                                <span id="hapusGambarVarianShopee-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                                </div>
                                            </div>
                                        </td>`;  
                                        
                        utama = "";
                    
                   }
                   
                   htmlGambarVarian += "</tr>";
                   $("#gambarvarianshopee").html(htmlGambarVarian);
                   $("#gambarvarianshopee").css('margin-bottom','-20px');
               
               	    for(var y = 0 ; y < warna.length ;y++)
                   {
                     
                       const fileInput = document.getElementById('file-input-varian-shopee-'+y);
                       const previewImage = document.getElementById('preview-image-varian-shopee-'+y);
                       const title = document.getElementById('keterangan-input-varian-shopee-'+y);
                       const format = document.getElementById('format-input-varian-shopee-'+y);
                       const index = document.getElementById('index-input-varian-shopee-'+y);
                       const url =  document.getElementById('src-input-varian-shopee-'+y);
                       const id =  document.getElementById('id-input-varian-shopee-'+y);
                       
                       const ubahImage = document.getElementById('ubahGambarVarianShopee-'+y);
                       const hapusImage = document.getElementById('hapusGambarVarianShopee-'+y);
                       
                       previewImage.addEventListener('click', () => {
                         if(fileInput.value != "")
                         {
                             lihatLebihJelasShopee(format.value,title.value,url.value);
                         }
                         else
                         {
                           fileInput.click();
                         }
                       });
                       
                       ubahImage.addEventListener('click', () => {
                         fileInput.click();
                       });
                       
                       hapusImage.addEventListener('click', () => {
                         fileInput.value = '';
                         format.value = '';
                         previewImage.src = base_url+"/assets/images/addphoto.webp";
                         url.value = "";
                         id.value = "";
                         
                         ubahImage.style.display = 'none';
                         hapusImage.style.display = 'none';
                       });
                       
                       fileInput.addEventListener('change', () => {
                         const file = fileInput.files[0];
                         if (!file) return;
               
                         // Jika file adalah gambar
                         if (file.type.startsWith('image/')) {
                           const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                       
                           if (!allowedTypes.includes(file.type.toLowerCase())) {
                               fileInput.value = '';
                             Swal.fire({
                               title: 'Format gambar tidak didukung (hanya jpg/jpeg/png)',
                               icon: 'warning',
                               showConfirmButton: false,
                               timer: 2000
                             });
                             return;
                           }
                       
                           const maxSizeMB = 10;
                           if (file.size > maxSizeMB * 1024 * 1024) {
                               fileInput.value = '';
                             Swal.fire({
                               title: 'Ukuran gambar melebihi 10MB',
                               icon: 'warning',
                               showConfirmButton: false,
                               timer: 2000
                             });
                             return;
                           }
                       
                           // Upload file asli ke server
                           const formData = new FormData();
                           formData.append('index', index.value);
                           formData.append('kode', $("#BARANGSHOPEE").val()+"_"+warna[y]);
                           formData.append('file', file);
                           formData.append('tipe', 'GAMBAR');
                           formData.append('size', file.size);
                           formData.append("reason","produk");
                       
                           loading();
                           
                           $.ajax({
                             type: 'POST',
                             url: base_url + 'Shopee/uploadLocalUrl/',
                             data: formData,
                             contentType: false,
                             processData: false,
                             dataType: 'json',
                             success: function (msg) {
                               Swal.close();
                               if (msg.success) {
                                format.value = "GAMBAR";
                                previewImage.src = msg.url;
                                url.value =  msg.url;
                                id.value = msg.id;
                       
                                ubahImage.style.display = '';
                                hapusImage.style.display = '';
                               }
                               else
                               {
                                   fileInput.value = '';
                               }
                             },
                             error: function (xhr, status, error) {
                               fileInput.value = '';
                               Swal.fire({
                                 title: 'Upload gagal!',
                                 text: error,
                                 icon: 'error'
                               });
                             }
                           });
                         }
                         // Jika file adalah video
                       //   else if (file.type.startsWith('video/')) {
                       //     format.value = "VIDEO";
                       //     const video = document.createElement("video");
                       //     video.preload = "metadata";
                       
                       //     video.onloadedmetadata = function () {
                       //       window.URL.revokeObjectURL(video.src);
                       
                       //       if (parseInt(video.duration) > 60) {
                       //         Swal.fire({
                       //         	title            : 'Durasi Min 1 Menit',
                       //         	type             : 'warning',
                       //         	showConfirmButton: false,
                       //         	timer            : 2000
                       //         });
                       //         fileInput.value = ""; // Kosongkan input
                       //         format.value = "";
                       //         return;
                       //       }
                             
                       //       const maxSizeMB = 10;
                       //       if (file.size > maxSizeMB * 1024 * 1024) {
                       //           fileInput.value = '';
                       //          Swal.fire({
                       //           title: 'Ukuran video melebihi 10MB',
                       //           icon: 'warning',
                       //           showConfirmButton: false,
                       //           timer: 2000
                       //          });
                       //          return;
                       //       }
                             
                       //       var row = JSON.parse($("#rowDataShopee").val());
                       //         // Upload file asli ke server
                       //         const formData = new FormData();
                       //         formData.append('index', index.value);
                       //         formData.append('kodepengembalian', row.KODEPENGEMBALIAN);
                       //         formData.append('file', file);
                       //         formData.append('tipe', 'VIDEO');
                       //         formData.append('size', file.size);
                           
                       //         loading();
                               
                       //         $.ajax({
                       //           type: 'POST',
                       //           url: base_url + 'Shopee/uploadLocalUrl/',
                       //           data: formData,
                       //           contentType: false,
                       //           processData: false,
                       //           dataType: 'json',
                       //           success: function (msg) {
                       //             Swal.close();
                       //             if (msg.success) {
                       //              format.value = "VIDEO";
                       //              previewImage.src =  base_url+"/assets/images/video.webp";
                       //              url.value =  msg.url;
                           
                       //              ubahImage.style.display = '';
                       //              hapusImage.style.display = '';
                       //             }
                       //             else
                       //             {
                       //                 fileInput.value = '';
                       //             }
                       //           },
                       //           error: function (xhr, status, error) {
                       //             fileInput.value = '';
                       //             Swal.fire({
                       //               title: 'Upload gagal!',
                       //               text: error,
                       //               icon: 'error'
                       //             });
                       //           }
                       //         });
                       //     };
                       
                       //     video.onerror = () => {
                       //       Swal.fire({
                       //         	title            : 'Gagal memuat video dari file',
                       //         	type             : 'warning',
                       //         	showConfirmButton: false,
                       //         	timer            : 2000
                       //         });
                       //       fileInput.value = "";
                       //       format.value = "";
                       //     };
                       
                       //     video.src = URL.createObjectURL(file);
                       //   }
                       
                         // Tipe file tidak valid
                         else {
                            Swal.fire({
                               	title            : 'Hanya mendukung file Gambar dan Video',
                               	type             : 'warning',
                               	showConfirmButton: false,
                               	timer            : 2000
                           });
                         }
                       });
                   }
                   
            }, "500");
        }
    });
    
    $.ajax({
        type      : 'POST',
        url       : base_url+'Shopee/getKategori',
        dataType  : 'json',
        beforeSend: function (){
            //$.messager.progress();
        },
        success: function(msg){
           var selectKategori = "<option value='0'>-Pilih Kategori-</option>";
           $("#KATEGORISHOPEE").html(selectKategori);
           for(var x = 0 ; x < msg.length ; x++)
           {
              selectKategori += "<option value='"+msg[x].VALUE+"'>"+msg[x].TEXT+"</option>"; 
           }
           $("#KATEGORISHOPEE").html(selectKategori);
           $("#KATEGORISHOPEE").select2();
        }
    });
    
      
    $.ajax({
        type      : 'POST',
        url       : base_url+'Master/Data/Barang/dataGrid',
        data      : {'jenis' : 'SHOPEE'},
        dataType  : 'json',
        beforeSend: function (){
            //$.messager.progress();
        },
        success: function(msg){
           var msg = msg.rows;
           var selectBarang = "<option value='0'>-Pilih Master Barang-</option>";
           dataMasterShopee = msg;
           $("#BARANGSHOPEE").html(selectBarang);
           for(var x = 0 ; x < msg.length ; x++)
           {
              selectBarang += "<option value='"+msg[x].KATEGORI+"'>"+msg[x].KATEGORI+"</option>"; 
           }
           $("#BARANGSHOPEE").html(selectBarang);
           $("#BARANGSHOPEE").select2();
        }
    });
   
});

function checkDetailKirim(id){
    for(var x = 0 ; x < pengirimanShopee.length; x++)
    {
        if(id == pengirimanShopee[x].IDPENGIRIMAN)
        {
            $("#NAMAPENGIRIMANSHOPEE").html(pengirimanShopee[x].NAMAPENGIRIMAN);
            $("#KETERANGANPENGIRIMANSHOPEE").html(pengirimanShopee[x].KETERANGANPENGIRIMAN);
            
            var tablePengiriman = "<table style='border-collapse: collapse; width:100%;'>";
            for(var y = 0 ; y < pengirimanShopee[x].JENISPENGIRIMAN.length;y++)
            {
                tablePengiriman += ("<tr><td valign='top' width='150px' style='border: 1px solid #ccc; padding: 6px 8px; text-align:center;'><b>"+pengirimanShopee[x].JENISPENGIRIMAN[y].NAMAPENGIRIMAN+"</b></td><td style='border: 1px solid #ccc; padding: 6px 8px;'>"+pengirimanShopee[x].JENISPENGIRIMAN[y].KETERANGANPENGIRIMAN+"</td></tr>");
            }
            tablePengiriman += "</table>";
            
            $("#JENISPENGIRIMANSHOPEE").html(tablePengiriman); //dataKirim[x].JENISPENGIRIMAN
            
            $("#modal-detail-pengiriman-shopee").modal('show');
        }
    }
}

function pilihDataAttribut(id){
    for(var x = 0 ; x < attributShopee.length; x++)
    {
        if(id == attributShopee[x].IDATTRIBUT)
        {
            $("#NAMAATTRIBUTSHOPEE").html(attributShopee[x].NAMAATTRIBUT);
            var maxValue = attributShopee[x].SYARATATTRIBUT['MAXVALUE'];
            var htmlAttribut = "";
            if(attributShopee[x].SYARATATTRIBUT['COMPONENT'] == "FREETEXTFILED")
            {
                var valueText = "";
                if(attributShopee[x].VALUEATTRIBUT != null)
                {
                    valueText = attributShopee[x].VALUEATTRIBUT;
                }
                htmlAttribut = "<input type='text' class='form-control' value='"+valueText+"' id='"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT' placeholder='Isi disini ...'>";
            }
            else 
            {
                htmlAttribut += `
                <label style="text-align:center;">Pilih `+maxValue.toString()+` dari daftar berikut :</label>
                <br>
                <table style='width:100%'>`;
                
                for(var y = 0 ; y < attributShopee[x].JENISATTRIBUT.length;y++)
                {
                    var checked = "";
                    if(attributShopee[x].JENISATTRIBUT[y].SELECTED == 1)
                    {
                       checked = "checked"; 
                    }
                    
                    htmlAttribut += ("<tr><td valign='top' width='50px' style='text-align:center; padding: 6px 8px;'><input "+checked+" type='checkbox' id='"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_"+attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT+"' onclick='activeCheckbox(`checkbox`,`"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_"+attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT+"`)' ></td><td valign='top' style='padding: 6px 8px;' onclick='activeCheckbox(`label`,`"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_"+attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT+"`)' >"+attributShopee[x].JENISATTRIBUT[y].NAMAATTRIBUT+"</td></tr>");
                }
                
                if(attributShopee[x].JENISATTRIBUT.length == 0)
                {
                    htmlAttribut +="<tr><td colspan='2'> Tidak Ada Pilihan Tersedia</td></tr>";     
                }
                
                htmlAttribut += "</table>"; 
                
                if(attributShopee[x].SYARATATTRIBUT['COMPONENT'].includes("COMBOBOX")){
                     var width = "235px";   
                     
                     htmlAttribut += "<br><label>Pilihan sendiri</label><br><span><input type='text' style='width:"+width+"; float:left; margin-right:10px;' class='form-control' id='"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT' placeholder='Isi disini ...'>";
                     
                     htmlAttribut += "<button id='btn_simpan_attribut_shopee'  class='btn btn-success' onclick='tambahAttribut("+x+",`"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT`)'>Tambah Pilihan</button></span>";
                }
            }
                
            $("#JENISATTRIBUTSHOPEE").html(htmlAttribut); 
            
            $("#modal-detail-attribut-shopee").modal('show');
        }
    }
}

function tambahAttribut(x,id){
   if($("#"+id).val() != "")
   {
       var oldLength = attributShopee[x].JENISATTRIBUT.length;
       attributShopee[x].JENISATTRIBUT.push({
           'IDATTRIBUT'   : "TEXT-"+(attributShopee[x].JENISATTRIBUT.length),
           'NAMAATTRIBUT' : $("#"+id).val(),
           'SELECTED'     : 0,
       });
       
       var htmlAttribut = ("<tr><td valign='top' width='50px' style='text-align:center; padding: 6px 8px;'><input type='checkbox' id='"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT-"+(attributShopee[x].JENISATTRIBUT.length-1)+"' onclick='activeCheckbox(`checkbox`,`"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT-"+(attributShopee[x].JENISATTRIBUT.length-1)+"`)' ></td><td valign='top' style='padding: 6px 8px;' onclick='activeCheckbox(`label`,`"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT-"+(attributShopee[x].JENISATTRIBUT.length-1)+"`)' >"+attributShopee[x].JENISATTRIBUT[attributShopee[x].JENISATTRIBUT.length-1].NAMAATTRIBUT+"</td></tr>");
       if(oldLength == 0)
       {
        $("#JENISATTRIBUTSHOPEE table").html(htmlAttribut);    
       }
       else
       {
        $("#JENISATTRIBUTSHOPEE table").append(htmlAttribut);  
       }
       $("#"+id).val("");
   }
}

function simpanAttribut()
{
    var table = $('#dataGridAttributShopee').DataTable();

    // Find the row with the given IDATTRIBUT
    var rowIndex = -1;
    table.rows().every(function(x, tableLoop, rowLoop) {
        var data = this.data();
        var newValue = "";
        
        if(attributShopee[x].SYARATATTRIBUT['COMPONENT'] == "FREETEXTFILED")
        {
            if($("#"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT").val() != null)
            {
                newValue = $("#"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_TEXT").val();
            }
            else
            {
                newValue = attributShopee[x].VALUEATTRIBUT;
            }
            
            if(newValue == "")
            {
                attributShopee[x].SELECTED = 0;
            }
            else
            {
                attributShopee[x].SELECTED = 1;
            }
            
        }
        else
        {
            for(var y = 0 ; y < attributShopee[x].JENISATTRIBUT.length;y++)
            {
                if(attributShopee[x].JENISATTRIBUT[y].SELECTED == 1)
                {
                    newValue += (attributShopee[x].JENISATTRIBUT[y].NAMAATTRIBUT+",");
                }
            }
            
            newValue = newValue.slice(0, -1);
        }
        
        attributShopee[x].VALUEATTRIBUT = newValue;
        data.VALUEATTRIBUT = newValue;
        table.row(x).data(data).invalidate().draw(false); // Redraw without resetting pagination
        
    });
    $("#modal-detail-attribut-shopee").modal('hide');
    attributShopeeOld = JSON.parse(JSON.stringify(attributShopee));
}

function kembaliAttribut()
{
    attributShopee = JSON.parse(JSON.stringify(attributShopeeOld));
}

function activeCheckbox(type,id)
{
    if(type == "checkbox")
    {
         $("#"+id).prop("checked",!$("#"+id).prop("checked"));
    }
    
    var checkFirst = $("#"+id).prop("checked");
    
    var checkDone = false;
    var indexX = 0;
    var indexY = 0;
    var maxValue = 0;
    for(var x = 0 ; x < attributShopee.length; x++)
    {
        if(id.split("_")[0] == attributShopee[x].IDATTRIBUT)
        {
            indexX = x;
            var maxValue = attributShopee[x].SYARATATTRIBUT['MAXVALUE'];
            if(maxValue == 1)
            {
                for(var y = 0 ; y < attributShopee[x].JENISATTRIBUT.length;y++)
                {
                    if(id.split("_")[2] ==  attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT)
                    {
                        indexY = y;
                    }
                    else
                    {
                        $("#"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_"+attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT).prop("checked",false);
                        attributShopee[x].SELECTED = 0;
                        attributShopee[x].JENISATTRIBUT[y].SELECTED = 0;
                    }
                }
                checkDone = true;
            }
            else
            {
                var jmlCheck = 0;
                //CHECK JUMLAH SAMA MAXVALUE APA PAS
                for(var y = 0 ; y < attributShopee[x].JENISATTRIBUT.length;y++)
                {
                    if($("#"+attributShopee[x].IDATTRIBUT+"_"+attributShopee[x].SYARATATTRIBUT['COMPONENT']+"_"+attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT).prop("checked"))
                    {
                        jmlCheck++;
                    }
                    
                    if(id.split("_")[2] ==  attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT)
                    {
                        indexY = y;
                    }
                }
                if(maxValue > jmlCheck)
                {
                     checkDone = true;
                }
            }
        }
    }
    
    if(checkDone || checkFirst)
    {
        $("#"+id).prop("checked",!$("#"+id).prop("checked"));
        
        if($("#"+id).prop("checked"))
        {
          attributShopee[indexX].SELECTED = 1;
          attributShopee[indexX].JENISATTRIBUT[indexY].SELECTED = 1;  
        }
        else
        {
          attributShopee[indexX].SELECTED = 0;
          attributShopee[indexX].JENISATTRIBUT[indexY].SELECTED = 0;  
        }
    }
}

function reset() {
    $("#dataGridVarianShopee").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian');
    $("#dataGridVarianShopee").DataTable().ajax.reload();
    $("#dataGridPengirimanShopee").DataTable().ajax.url(base_url+'Shopee/getPengiriman');
    $("#dataGridPengirimanShopee").DataTable().ajax.reload();
    $("#dataGridAttributShopee").DataTable().ajax.url(base_url+'Shopee/getAttribut');
    $("#dataGridAttributShopee").DataTable().ajax.reload();
    
    $("#NAMASHOPEE").val("");
    $("#DESKRIPSISHOPEE").val("");
    $("#BERATSHOPEE").val("");
    $("#PANJANGSHOPEE").val("");
    $("#LEBARSHOPEE").val("");
    $("#TINGGISHOPEE").val("");
    $("#IDBARANGSHOPEE").val(0);
    
    $("#SIZETEMPLATESHOPEE").prop('checked',true).iCheck('update');
    $("#UNLISTED").prop('checked',false).iCheck('update');
    
	$("#gambarprodukshopee").html("");
	$("#gambarprodukshopee").css('margin-bottom','0px');
	$("#gambarvarianshopee").html("");
	$("#gambarvarianshopee").css('margin-bottom','0px');
	$("#gambarukuranprodukshopee").html("");
	$("#TABELUKURANSETTINGSHOPEE").hide();
	warna = [];
    ukuran = [];
    attributShopee = [];
    attributShopeeOld = [];
}

$("#KATEGORISHOPEE").change(function(){
    if($(this).val() != 0 && $(this).val() != null)
    {
       loading();
       $("#dataGridAttributShopee").DataTable().ajax.url(base_url+'Shopee/getAttribut/'+$(this).val());
       $("#dataGridAttributShopee").DataTable().ajax.reload();
    
       $.ajax({
           type      : 'POST',
           url       : base_url+'Shopee/getSizeChart',
           data      : {kategori:$(this).val()},
           dataType  : 'json',
           beforeSend: function (){
               //$.messager.progress();
           },
           success: function(msg){
               
              $("#TABELUKURANSETTINGSHOPEE").hide();
              $("#gambarukuranprodukshopee").hide();
              $("#TEMPLATESHOPEE").hide();
              $("#SIZETEMPLATESHOPEE").hide();
                       
              if(msg.available_image_size_chart || msg.available_template_size_chart)
              {
                  $("#TABELUKURANSETTINGSHOPEE").show();
                  
                  if(msg.available_image_size_chart && msg.available_template_size_chart)
                  {
                      $("#SIZETEMPLATESHOPEE").show();
                      $("#gambarukuranprodukshopee").show();
                      $("#TEMPLATESHOPEE").show();
                  }
                  else if(msg.available_image_size_chart)
                  {
                      $("#gambarukuranprodukshopee").show();
                  }
                  else if(msg.available_template_size_chart)
                  {
                      $("#TEMPLATESHOPEE").show();
                  }
              }
              
              var selectTemplate = "<option value='0'>-Pilih Template-</option>";
              $("#TEMPLATESHOPEE").html(selectTemplate);
              for(var x = 0 ; x < msg['rows'].length ; x++)
              {
                 selectTemplate += "<option value='"+msg['rows'][x].SIZE_ID+"'>"+msg['rows'][x].SIZE_NAME+"</option>"; 
              }
              $("#TEMPLATESHOPEE").html(selectTemplate);
              $("#TEMPLATESHOPEE").trigger('change');
              
              var htmlGambarSize = `
                <tr>
                 <td>
                     <input type="file" id="file-size-shopee" accept="image/*,video/*" style="display:none;" value="">
                     <input type="hidden"  id="format-size-shopee" value="">
                     <input type="hidden"  id="index-size-shopee" value="0">
                     <input type="hidden"  id="src-size-shopee" value="">
                     <input type="hidden"  id="keterangan-size-shopee" value="Gambar Size Chart">
                     <input type="hidden"  id="id-size-shopee" value="">
                     
                     <div>
                         <img id="preview-size-shopee" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; cursor:pointer; border:2px solid #dddddd;'>
                         <div style="text-align:center;"><b>Gambar Tabel</b><br>
                         <span id="ubahSizeProdukShopee" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                         &nbsp;
                         <span id="hapusSizeProdukShopee" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                         </div>
                     </div>
                 </td>
                </tr>
               `;
               
               $("#gambarukuranprodukshopee").html(htmlGambarSize);
              
                const fileInput = document.getElementById('file-size-shopee');
                const previewImage = document.getElementById('preview-size-shopee');
                const title = document.getElementById('keterangan-size-shopee');
                const format = document.getElementById('format-size-shopee');
                const index = document.getElementById('index-size-shopee');
                const url =  document.getElementById('src-size-shopee');
                const id = document.getElementById('id-size-shopee');
                
                const ubahImage = document.getElementById('ubahSizeProdukShopee');
                const hapusImage = document.getElementById('hapusSizeProdukShopee');
                
                previewImage.addEventListener('click', () => {
                  if(fileInput.value != "")
                  {
                      lihatLebihJelasShopee(format.value,title.value,url.value);
                  }
                  else
                  {
                    fileInput.click();
                  }
                });
                
                ubahImage.addEventListener('click', () => {
                  fileInput.click();
                });
                
                hapusImage.addEventListener('click', () => {
                  fileInput.value = '';
                  format.value = '';
                  previewImage.src = base_url+"/assets/images/addphoto.webp";
                  url.value = "";
                  id.value = "";
                  
                  ubahImage.style.display = 'none';
                  hapusImage.style.display = 'none';
                });
                
                fileInput.addEventListener('change', () => {
                  const file = fileInput.files[0];
                  if (!file) return;
           
                  // Jika file adalah gambar
                  if (file.type.startsWith('image/')) {
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                
                    if (!allowedTypes.includes(file.type.toLowerCase())) {
                        fileInput.value = '';
                      Swal.fire({
                        title: 'Format gambar tidak didukung (hanya jpg/jpeg/png)',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000
                      });
                      return;
                    }
                
                    const maxSizeMB = 10;
                    if (file.size > maxSizeMB * 1024 * 1024) {
                        fileInput.value = '';
                      Swal.fire({
                        title: 'Ukuran gambar melebihi 10MB',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000
                      });
                      return;
                    }
                
                    // Upload file asli ke server
                    const formData = new FormData();
                    formData.append('index', index.value);
                    formData.append('kode', $("#BARANGSHOPEE").val()+"_SIZE");
                    formData.append('file', file);
                    formData.append('tipe', 'GAMBAR');
                    formData.append('size', file.size);
                    formData.append("reason","produk");
                
                    loading();
                    
                    $.ajax({
                      type: 'POST',
                      url: base_url + 'Shopee/uploadLocalUrl/',
                      data: formData,
                      contentType: false,
                      processData: false,
                      dataType: 'json',
                      success: function (msg) {
                        Swal.close();
                        if (msg.success) {
                         format.value = "GAMBAR";
                         previewImage.src = msg.url;
                         url.value =  msg.url;
                         id.value = msg.id;
                
                         ubahImage.style.display = '';
                         hapusImage.style.display = '';
                        }
                        else
                        {
                            fileInput.value = '';
                        }
                      },
                      error: function (xhr, status, error) {
                        fileInput.value = '';
                        Swal.fire({
                          title: 'Upload gagal!',
                          text: error,
                          icon: 'error'
                        });
                      }
                    });
                  }
                  // Jika file adalah video
                //   else if (file.type.startsWith('video/')) {
                //     format.value = "VIDEO";
                //     const video = document.createElement("video");
                //     video.preload = "metadata";
                
                //     video.onloadedmetadata = function () {
                //       window.URL.revokeObjectURL(video.src);
                
                //       if (parseInt(video.duration) > 60) {
                //         Swal.fire({
                //         	title            : 'Durasi Min 1 Menit',
                //         	type             : 'warning',
                //         	showConfirmButton: false,
                //         	timer            : 2000
                //         });
                //         fileInput.value = ""; // Kosongkan input
                //         format.value = "";
                //         return;
                //       }
                      
                //       const maxSizeMB = 10;
                //       if (file.size > maxSizeMB * 1024 * 1024) {
                //           fileInput.value = '';
                //          Swal.fire({
                //           title: 'Ukuran video melebihi 10MB',
                //           icon: 'warning',
                //           showConfirmButton: false,
                //           timer: 2000
                //          });
                //          return;
                //       }
                      
                //       var row = JSON.parse($("#rowDataShopee").val());
                //         // Upload file asli ke server
                //         const formData = new FormData();
                //         formData.append('index', index.value);
                //         formData.append('kodepengembalian', row.KODEPENGEMBALIAN);
                //         formData.append('file', file);
                //         formData.append('tipe', 'VIDEO');
                //         formData.append('size', file.size);
                    
                //         loading();
                        
                //         $.ajax({
                //           type: 'POST',
                //           url: base_url + 'Shopee/uploadLocalUrl/',
                //           data: formData,
                //           contentType: false,
                //           processData: false,
                //           dataType: 'json',
                //           success: function (msg) {
                //             Swal.close();
                //             if (msg.success) {
                //              format.value = "VIDEO";
                //              previewImage.src =  base_url+"/assets/images/video.webp";
                //              url.value =  msg.url;
                    
                //              ubahImage.style.display = '';
                //              hapusImage.style.display = '';
                //             }
                //             else
                //             {
                //                 fileInput.value = '';
                //             }
                //           },
                //           error: function (xhr, status, error) {
                //             fileInput.value = '';
                //             Swal.fire({
                //               title: 'Upload gagal!',
                //               text: error,
                //               icon: 'error'
                //             });
                //           }
                //         });
                //     };
                
                //     video.onerror = () => {
                //       Swal.fire({
                //         	title            : 'Gagal memuat video dari file',
                //         	type             : 'warning',
                //         	showConfirmButton: false,
                //         	timer            : 2000
                //         });
                //       fileInput.value = "";
                //       format.value = "";
                //     };
                
                //     video.src = URL.createObjectURL(file);
                //   }
                
                  // Tipe file tidak valid
                  else {
                     Swal.fire({
                        	title            : 'Hanya mendukung file Gambar dan Video',
                        	type             : 'warning',
                        	showConfirmButton: false,
                        	timer            : 2000
                    });
                  }
                });
              
                let isChecked = $('#SIZETEMPLATESHOPEE').prop('checked');
    
                if (isChecked) {
                    $("#TEMPLATESHOPEE").removeAttr("disabled");
                    $("#gambarukuranprodukshopee").hide();
                } else {
                    $("#TEMPLATESHOPEE").attr("disabled", "disabled");
                    $("#gambarukuranprodukshopee").show();
                }
                
                Swal.close();
           }
       });
    }
})

$("#BARANGSHOPEE").change(function(){
    if($(this).val() == 0 && $(this).val() != null)
    {
        reset();
    }
    else
    {
		for(var x = 0 ; x < dataMasterShopee.length; x++)
		{
		    if(dataMasterShopee[x].KATEGORI == $(this).val())
		    {
		        $("#NAMASHOPEE").val(dataMasterShopee[x].KATEGORI);
		        $("#DESKRIPSISHOPEE").val(dataMasterShopee[x].DESKRIPSI.replaceAll("\R\N","\r\n").replaceAll("???? ",""));
		        $("#BERATSHOPEE").val(dataMasterShopee[x].BERAT);
		        $("#PANJANGSHOPEE").val(dataMasterShopee[x].PANJANG);
		        $("#LEBARSHOPEE").val(dataMasterShopee[x].LEBAR);
		        $("#TINGGISHOPEE").val(dataMasterShopee[x].TINGGI);
		    }
		}
		
        $("#dataGridVarianShopee").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian/'+encodeURIComponent($(this).val()));
		$("#dataGridVarianShopee").DataTable().ajax.reload();
    }
})

function getStatusShopee(){
	return $("#statusShopee").val();
}

function lihatLebihJelasShopee(jenis,title,url){

    $("#modal-lebih-jelas-shopee").modal("show");
    $("#titleLebihJelasShopee").html(title);
    $("#previewLebihJelasShopee").css("color","#3296ff");
    $("#previewLebihJelasShopee").css("cursor","pointer");
    $("#previewLebihJelasShopee").css("text-align","center");
    $("#previewLebihJelasShopee").css("background","#d4d4d7");
    if(jenis == "GAMBAR")
    {
        $("#previewLebihJelasShopee").html("<img src='"+url+"' max-width=100%; height=600px;>");
    }
    else
    {
        $("#previewLebihJelasShopee").html("<iframe src='"+url+"' max-width=100%; height=600px;>");
    }
}

function tambahShopee(){
    $("#modal-barang-shopee").modal("show");
    $("#KATEGORISHOPEE").val(0);
    $("#BARANGSHOPEE").val(0);
    $(".select2").trigger('change');
}

function simpanShopee(){
    
    var arrImage = [];
    for(var y = 0 ; y < 9 ;y++)
    {
        //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
        if($("#src-input-shopee-"+y).val() != "")
        {
            arrImage.push($('#id-input-shopee-'+y).val());
        }
    }
    
    var arrImageVarian = [];
    for(var y = 0 ; y < warna.length; y++)
    {
        //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
        if($("#src-input-varian-shopee-"+y).val() != "")
        {
            arrImageVarian.push($('#id-input-varian-shopee-'+y).val());
        }
    }
    
    var arrLogistics = [];
    $("#dataGridPengirimanShopee").DataTable().rows().every(function () {
        var $rowNode = $(this.node());
        var checkbox = $rowNode.find('.choose-row');
        for(var x = 0 ; x < pengirimanShopee.length; x++)
        {
            if(pengirimanShopee[x].IDPENGIRIMAN == this.data().IDPENGIRIMAN) //&& checkbox.prop('checked')
            {
                for(var y = 0 ; y < pengirimanShopee[x].JENISPENGIRIMAN.length;y++)
                {
                    arrLogistics.push({
                     'enabled'     : checkbox.prop('checked'),
                     'logistic_id' : pengirimanShopee[x].JENISPENGIRIMAN[y].IDPENGIRIMAN 
                    });
                }
            }
        }
    });
    
    var arrAttribut = [];
    var countRequiredAttribute = 0;
    var countValueAttribute = 0;
    for(var x = 0 ; x < attributShopee.length; x++)
    {
        if(attributShopee[x].REQUIRED)
        {
            countRequiredAttribute++;
        }
        
        if(attributShopee[x].SELECTED == 1)
        {
            countValueAttribute++;
            var arrDetailAttribut = []
            for(var y = 0 ; y < attributShopee[x].JENISATTRIBUT.length;y++)
            {
                if(attributShopee[x].JENISATTRIBUT[y].SELECTED == 1)
                {
                    if(attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT.toString().includes("TEXT"))
                    {
                        arrDetailAttribut.push({
                         'value_id'            : 0,
                         'original_value_name' : attributShopee[x].JENISATTRIBUT[y].NAMAATTRIBUT 
                        });
                    }
                    else
                    {
                        arrDetailAttribut.push({
                         'value_id'            : attributShopee[x].JENISATTRIBUT[y].IDATTRIBUT,
                         'original_value_name' : "" 
                        });
                    }
                }
            }
            
            if(attributShopee[x].SYARATATTRIBUT['COMPONENT'] == "FREETEXTFILED")
            {
                arrDetailAttribut.push({
                 'value_id'            : 0,
                 'original_value_name' : attributShopee[x].VALUEATTRIBUT 
                });
            }
            
            arrAttribut.push({
                 'attribute_id'         : attributShopee[x].IDATTRIBUT,
                 'attribute_value_list' : arrDetailAttribut
            });
        }
    }
    
    if(countRequiredAttribute == 0)
    {
        countValueAttribute = 0;
    }
    
    var useSize = true;
    if($("#TABELUKURANSETTINGSHOPEE").css("display") == "none")
    {
        useSize = false;    
    }
    
    var sizeChart = "";
    var sizeChartID = "";
    if($("#TEMPLATESHOPEE").val() != 0)
    {
        sizeChartID = $("#TEMPLATESHOPEE").val();
        sizeChart = "";
    }
    else if($("#id-size-shopee").val() != "")
    {
        
        sizeChartID = 0;
        sizeChart = $("#id-size-shopee").val();
    }
    else
    {
        sizeChartID = 0;
        sizeChart = "";
    }
    
    if(($("#KATEGORISHOPEE").val() == 0 || $("#KATEGORISHOPEE").val() == null) || ($("#BARANGSHOPEE").val() == 0 || $("#BARANGSHOPEE").val() == null) || arrLogistics.length == 0 || arrImage.length == 0 || arrImageVarian.length != warna.length || (useSize && (sizeChartID == 0 && sizeChart == "")) || countValueAttribute != countRequiredAttribute) // || base64Images.length == 0
    {
        Swal.fire({ 
        	title            : "Terdapat Data Produk yang belum diisi",
        	type             : 'error',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
        
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Shopee/setBarang/',
        	data    : {
        	   "IDBARANG"       : $("#IDBARANGSHOPEE").val(),
        	   "KATEGORI"       : $("#KATEGORISHOPEE").val(), 
        	   "NAMA"           : $("#NAMASHOPEE").val(), 
        	   "DESKRIPSI"      : $("#DESKRIPSISHOPEE").val(), 
        	   "BERAT"          : $("#BERATSHOPEE").val(), 
        	   "PANJANG"        : $("#PANJANGSHOPEE").val(), 
        	   "LEBAR"          : $("#LEBARSHOPEE").val(), 
        	   "TINGGI"         : $("#TINGGISHOPEE").val(), 
        	   "UNLISTED"       : $("#UNLISTED").prop("checked")? 1 : 0,
        	   "VARIAN"         : JSON.stringify($('#dataGridVarianShopee').DataTable().rows().data().toArray()),
        	   "WARNA"          : JSON.stringify(warna),
        	   "UKURAN"         : JSON.stringify(ukuran),
        	   "ATTRIBUT"      : JSON.stringify(arrAttribut),
        	   "GAMBARPRODUK"   : JSON.stringify(arrImage),
        	   "GAMBARVARIAN"   : JSON.stringify(arrImageVarian),
        	   "SIZECHART"      : sizeChart,
        	   "SIZECHARTID"    : sizeChartID,
        	   "LOGISTICS"      : JSON.stringify(arrLogistics),
        	},
            dataType: 'json',
        	success : function(msg){
               
                Swal.close();	
                Swal.fire({
                	title            :  msg.msg,
                	type             : (msg.success?'success':'error'),
                	showConfirmButton: false,
                	timer            : 2000
                });
                if(msg.success)
                {
                    $("#modal-retur-shopee").modal("hide");
                
                    setTimeout(() => {
                      reloadShopee();
                    }, "2000");
                }
        	}
        }); 
    }
}

function loading(){
    Swal.fire({
      title: '',
      html: '<img src="'+base_url+'assets/images/loading.gif" width="80">',                // no text or HTML content
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
}

</script>
