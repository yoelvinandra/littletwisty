<meta charset="UTF-8">
<style>
  #table_barang_naik_lazada td {
    white-space: normal !important;
    word-wrap: break-word;
  }
  
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
    
    #modal-barang-lazada .modal-dialog {
        min-width: 1000px;
        margin: 30px auto;
    }
    
    #modal-barang-lazada .modal-content {
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    #modal-barang-lazada .modal-body {
        overflow-y: auto;
        flex: 1 1 auto;
    }
    
    
    #historyPerubahanlazada {
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
    }

    #historyPerubahanlazada thead {
      background-color: #2c3e50;
      color: #ecf0f1;
    }

    #historyPerubahanlazada th, #historyPerubahanlazada td {
      padding: 16px 20px;
      text-align: left;
    }
    
</style>

<!-- Main content -->
<section class="content">
    
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12"  style="border:0px; padding:0px 15px 0px 15px;">
            <div class="box" style="border:0px; padding:0px; margin:0px;">
            <div class="box-header form-inline" style="padding:0px;">
                <button class="btn btn-success" onclick="javascript:tambahLazada()">Tambah</button>
                &nbsp;&nbsp;
                <!--<button class="btn btn-primary" onclick="javascript:naikkanLazada()">Naikkan Produk</button>-->
                <!--<button class="btn btn-primary" onclick="javascript:sinkronisasilazada()">Sinkronisasi Stok Produk</button>-->
            	<div class="pull-right" style="width:170px; margin-right:0px;">
                	<div class="input-group " >
                	 <div class="input-group-addon">
                		 <i class="fa fa-filter"></i>
                	 </div>
                		<select id="cb_barang_status_lazada" name="cb_barang_status_lazada" class="form-control "  panelHeight="auto" required="true">
                			<option value="all">Semua </option>
                			<option value="live,sold-out" selected>Aktif</option>
                			<option value="inactive">Tidak Aktif</option>
                			<option value="pending">Masa Review</option>
                			<option value="deleted">Penjual Hapus</option>
                			<option value="rejected">Lazada Tolak</option>
                		</select>
                	</div>
                </div>
            </div>
            <br>          			
            <table id="datagridLazada" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                <!-- class="table-hover"> -->
                <thead>
                <tr>
                    <th width="80px"></th>
                    <th>Nama Produk</th>
                    <th width="100px">Terhubung Master</th>
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
  
  <div class="modal fade" id="modal-barang-lazada" >
  	<div class="modal-dialog modal-lg">
  	<div class="modal-content">
  		<div class="modal-body">
              <div class="box-body">
                  <input type="hidden" id="IDBARANGLAZADA" name="IDBARANGLAZADA">
                  <div class="row">
                      <div class="form-group col-md-12">
                          <h3 style="font-weight:bold;" class="form-group" id="titleLazada">Tambah Produk</h3>
                          <span id="checklazada" style="font-size:12pt; float:right; margin-top:-40px; font-style:italic;">*Terdapat perubahan data pada master,&nbsp;&nbsp;<span style="text-decoration:underline; cursor:pointer;" onclick="checklazadaDetail()">Lihat Perubahan</span></span>
                          <label>Hubungkan Master Barang <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                          <select id="BARANGLAZADA" name="BARANGLAZADA" class="form-control select2" style="border:1px solid #B5B4B4; border-radius:1px; width:100%; height:32px; padding-left:12px; padding-right:12px;">
        
                          </select>
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="form-group col-md-8">
                          <h4 style="font-weight:bold;">Informasi Produk<label class="pull-right">&nbsp;&nbsp;&nbsp;<input type="checkbox" class="flat-blue" id="AKTIF" name="AKTIF" value="1">&nbsp; Aktif</label></h4>
                          <div class="row">
                                <div class="col-md-12">
                                    <label>Kategori Lazada <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                    <select id="KATEGORILAZADA" class="form-control select2" name="KATEGORILAZADA" style="border:1px solid #B5B4B4; border-radius:1px; width:100%; height:32px; padding-left:12px; padding-right:12px;">
    
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <label>Nama Produk <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                    <input type="text" class="form-control" id="NAMALAZADA" name="NAMALAZADA" placeholder="Nama Produk">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <label>Deskripsi <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                    <textarea class="form-control" rows="9" id="DESKRIPSILAZADA" name="DESKRIPSILAZADA" placeholder="Deskripsi....."></textarea>
                                </div>
                                <div class="form-group col-md-12" id="DIVDATAVARIAN">
                                  <br>
                                  <label style="font-weight:bold; margin-bottom:-5px;">Varian Produk<i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                  <div style="margin-top:-28px !important;" >
                                      <table id="dataGridVarianLazada" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                          <!-- class="table-hover"> -->
                                          <thead>
                                          <tr>
                                              <th></th>
                                              <th>Nama</th>
                                              <th width="80px">Harga</th>
                                              <th width="180px">SKU</th>	
                                              <th width="50px">Aktif</th>							
                                          </tr>
                                          </thead>
                                      </table>  
                                   </div>
                               </div>
                               <div id="DIVDATANONVARIAN">
                                   <div class="form-group col-md-6" >
                                      <br>
                                      <label style="font-weight:bold; margin-bottom:-5px;">Harga Jual Tampil <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                      <input type="text" class="form-control" id="HARGAJUALMASTERLAZADA" name="HARGAJUALMASTERLAZADA" placeholder="Harga Jual Tampil" readonly>
                                   </div>
                                    <div class="form-group col-md-6" >
                                      <br>
                                      <label style="font-weight:bold; margin-bottom:-5px;">SKU <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                      <input type="text" class="form-control" id="SKUMASTERLAZADA" name="SKUMASTERLAZADA" placeholder="SKU lazada" readonly>
                                   </div>
                                </div>
                          </div>
                          <div class="row">
                             <div class ="form-group col-md-12">
                                 <br>
                                 <h4 style="font-weight:bold; margin-bottom:-5px;">Gambar Produk<i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></h4>
                                 <br>
                                 <table id="gambarProdukLazada">
                                 </table>  
                             </div>
                             <div class ="form-group col-md-12" id="DIVGAMBARVARIANLAZADA">
                                 <h4 style="font-weight:bold; margin-bottom:-5px;">Gambar Varian<i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></h4>
                                 <br>
                                 <table id="gambarVarianLazada">
                                 </table>    
                             </div>
                           </div>
                      </div>
                        <div class="form-group col-md-4">
                          <h4 style="font-weight:bold;">Pengiriman</h4>
                          <div class="row" style="margin-top:10px;">
                              <div class="col-md-12">
                          	    <label>Berat (gram) <i style="color:grey;">&nbsp;&nbsp;&nbsp;mengikuti master</i></label>
                                  <input type="text" class="form-control" id="BERATMASTERLAZADA" name="BERATMASTERLAZADA" placeholder="Dalam Gram" readonly>
                              </div>
                          </div>
                          <div class="row">
                              <br>
                              <div class="col-md-4">
                                  <label>Panjang</label>
                                  <input type="text" class="form-control" id="PANJANGMASTERLAZADA" name="PANJANGMASTERLAZADA" placeholder="Cm" readonly>
                              </div>
                              <div class="col-md-4">
                                  <label>Lebar</label>
                                  <input type="text" class="form-control" id="LEBARMASTERLAZADA" name="LEBARMASTERLAZADA" placeholder="Cm" readonly>
                              </div>
                              <div class="col-md-4">
                                  <label>Tinggi</label>
                                  <input type="text" class="form-control" id="TINGGIMASTERLAZADA" name="TINGGIMASTERLAZADA" placeholder="Cm" readonly>
                              </div>
                          </div>
                          <!--<div class="row" style="height:200px;">-->
                          <!--     <br>-->
                          <!--     <div class="col-md-12">-->
                          <!--         <label>Pilihan Pengiriman <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Min 1)</i></label> -->
                          <!--         <div style="margin-top:-7px;">-->
                          <!--              <table id="dataGridPengirimanShopee" class="table table-bordered table-striped table-hover display nowrap" width="200px">-->
                                            <!-- class="table-hover"> -->
                          <!--                  <thead style="display:none;">-->
                          <!--                      <tr>-->
                          <!--                          <th></th>-->
                          <!--                          <th></th>-->
                          <!--                          <th></th>-->
                          <!--                      </tr>-->
                          <!--                  </thead>-->
                          <!--              </table> -->
                          <!--          </div>-->
                          <!--      </div>-->
                          <!--</div>-->
                          <!--<div class="row" style="margin-top:45px;">-->
                          <!--    <br>-->
                          <!--    <div class="col-md-12">-->
                          <!--         <label>Spesifikasi Produk <i style="color:grey;"></i></label> -->
                          <!--         <div style="margin-top:6px;">-->
                          <!--              <table id="dataGridAttributShopee" class="table table-bordered table-striped table-hover display nowrap" width="200px">-->
                                            <!-- class="table-hover"> -->
                          <!--                  <thead>-->
                          <!--                      <tr>-->
                          <!--                          <th></th>-->
                          <!--                          <th>Keterangan</th>-->
                          <!--                          <th>Pilihan</th>-->
                          <!--                      </tr>-->
                          <!--                  </thead>-->
                          <!--              </table> -->
                          <!--          </div>-->
                          <!--      </div>-->
                          <!--</div>-->
                      </div>
                  </div>
              </div>
              <div class="box-footer">
                  <button type="button" id="btn_simpan_detail_lazada" class="btn btn-success" onclick="javascript:simpanLazada()">Simpan</button>
                  </div>
              </div>
  		</div>
  	</div>
  </div>
  
  <div class="modal fade" id="modal-lebih-jelas-lazada" style="z-index:999999999999999999999999999;">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; <span id="titleLebihJelaslazada" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_lazada'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmlazada()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewLebihJelaslazada">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
 </div>
  
  <div class="modal fade" id="modal-check-lazada">
	<div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title"  style="float:left; padding-top:4px;">Perubahan Data Master</h4>
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px;">
                   <table id="historyPerubahanlazada" width="100%" style="border:0.5px solid #cccccc">
                      
                   </table>
                </div>
      	    </div>
      	  </div>
	    </div>
	</div>
  </div>
  
  <!-- /.row (main row) -->
</section>
<input type="hidden" id="statuslazada">
<input type="hidden" id="modeLazada">
<!-- /.content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
var pengirimanlazada = [];
var attributlazada = [];
var attributlazadaOld = [];
var dataMasterLazada = [];
var warna = [];
var ukuran = [];
var historyPerubahanlazada = [];
$(document).ready(function() {
    
    $("#HARGAJUALMASTERLAZADA").number(true, 0);
    const modal = document.getElementById('modal-detail-attribut-lazada');

    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        kembaliAttribut();
      }
    });

    loading();
    $("#statuslazada").val('live,sold-out');
    //MENAMPILKAN TRANSAKSI
    $("#cb_barang_status_lazada").change(function(event){
        loading();
        $("#statuslazada").val($(this).val());
    	$("#datagridLazada").DataTable().ajax.reload();
    	
    });

    $('#datagridLazada').DataTable({
        'pageLength'  : 25,
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Lazada/dataGridBarang',
			dataSrc: "rows",
			type   : "POST",
        	data   : function(e){
        	    e.status 		 = getStatuslazada();
        	},
		},
        columns:[
            // { data: 'IDBARANG', visible: false},
            {data: ''},
            {data: 'NAMABARANG'},
            {data: 'MASTERCONNECTED', className:"text-center"},
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
			,
			{
			    "targets": -1,
                "render" :function (data,display,row) 
    			{
    			    var status = "";
                	if(data == "Active")
                	{
                	    status = "Aktif";
                	}
                	if(data == "InActive")
                	{
                	    status = "Tidak Aktif";
                	}
                	if(data == "Pending QC")
                	{
                	  status = "Masa Review";
                	}
                	if(data == "Deleted")
                	{
                	    status = "Penjual Hapus"; 
                	}
                	if(data == "Suspended")
                	{
                	    status = "Lazada Tolak";
                	}
                			
    			    return status;
    			},		
			}
		],
    });

    //DAPATKAN INDEX
	$('#datagridLazada tbody').on( 'click', 'button', function () {
		var row = $('#datagridLazada').DataTable().row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah"){ ubahLazada(row); }
		if(mode == "btn_hapus"){ hapusLazada(row); }
	});
    
    $('#datagridLazada').DataTable().on('xhr.dt', function () {
        Swal.close();
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
    
   $('#dataGridVarianLazada').DataTable({
      paging: false,
      lengthChange: false,
      searching: true,
      ordering: false,
      info: true,
      autoWidth: false,
      scrollX: true,
      ajax: {
        url: base_url + 'Master/Data/Barang/getDataVarian',
        dataSrc: "rows",
      },
      columns: [
        { data: 'IDBARANG', visible: false },
        { data: 'NAMABARANG' },
        { data: 'HARGAJUAL', render: format_number, className: "text-right" },
        { data: 'SKULAZADA', className: "text-center" },
        { data: 'STATUS', className: "text-center" } // <-- changed from 'AKTIF'
      ],
      columnDefs: [
        {
          targets: 1,
          render: function (data) {
            var array = data.split(" | ");
            if (array.length == 1) {
              return array[0];
            } else {
              return array[1] + " | " + array[2];
            }
          },
        },
        {
          targets: -1,
          render: function (data, type, row, meta) {
            var checked = data == 1 ? "checked" : "";
            return `<input type='checkbox' class='status-checkbox' disabled data-id='${row.IDBARANG}' ${checked}>`;
          },
        },
      ],
      drawCallback: function () {
        $('.status-checkbox').on('change', function () {
          var id = $(this).data('id');
          var checked = $(this).is(':checked') ? 1 : 0;
          var table = $('#dataGridVarianLazada').DataTable();
    
          var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
            return table.cell(rowIdx, 0).data() == id;
          });
    
          if (rowIndex.length > 0) {
            table.cell(rowIndex[0], 4).data(checked).draw(false); // column 4 = STATUS
          }
        });
      }
    });
    
    $('#dataGridVarianLazada').DataTable().on('xhr.dt', function () {
        if($("#BARANGLAZADA").val() != 0)
        {
            setTimeout(() => {
               	
               	//GAMBAR VARIAN
               	var varian = $('#dataGridVarianLazada').DataTable().rows().data().toArray();
              
               	warna = [];
               	ukuran = [];
               	for(var y = 0 ; y < varian.length; y++)
               	{
               	    var tempWarna = varian[y].WARNA;
               	    var tempUk = varian[y].SIZE;
               		
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
                                            <input type="file" id="file-input-varian-lazada-`+y+`" accept="image/jpeg,image/jpg,image/png" style="display:none;" value="">
                                            <input type="hidden"  id="format-input-varian-lazada-`+y+`" value="">
                                            <input type="hidden"  id="index-input-varian-lazada-`+y+`" value="`+y+`">
                                            <input type="hidden"  id="src-input-varian-lazada-`+y+`" value="">
                                            <input type="hidden"  id="keterangan-input-varian-lazada-`+y+`" value="Gambar Varian `+warna[y]+`">
                                            <input type="hidden"  id="id-input-varian-lazada-`+y+`" value="">
                                           
                                            <div style="margin-bottom:20px;">
                                                <img id="preview-image-varian-lazada-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; margin-right:`+marginRight+`; cursor:pointer; border:2px solid #dddddd;'>
                                                <div style="text-align:center; margin-right:`+marginRight+`"><b>`+warna[y]+`</b><br>
                                                <span id="ubahGambarVarianLazada-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                                &nbsp;
                                                <span id="hapusGambarVarianLazada-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                                </div>
                                            </div>
                                        </td>`;  
                                        
                        utama = "";
                    
                   }
                   
                   htmlGambarVarian += "</tr>";
                   $("#gambarVarianLazada").html(htmlGambarVarian);
                   $("#gambarVarianLazada").css('margin-bottom','-20px');
               
               	    for(var y = 0 ; y < warna.length ;y++)
                   {
                     
                       const fileInput = document.getElementById('file-input-varian-lazada-'+y);
                       const previewImage = document.getElementById('preview-image-varian-lazada-'+y);
                       const title = document.getElementById('keterangan-input-varian-lazada-'+y);
                       const format = document.getElementById('format-input-varian-lazada-'+y);
                       const index = document.getElementById('index-input-varian-lazada-'+y);
                       const url =  document.getElementById('src-input-varian-lazada-'+y);
                       const id =  document.getElementById('id-input-varian-lazada-'+y);
                       
                       const ubahImage = document.getElementById('ubahGambarVarianLazada-'+y);
                       const hapusImage = document.getElementById('hapusGambarVarianLazada-'+y);
                       
                       previewImage.addEventListener('click', () => {
                         if(url.value != '')
                         {
                             lihatLebihJelaslazada(format.value,title.value,url.value);
                         }
                         else
                         {
                        //   fileInput.click();
                         }
                       });
                       
                       ubahImage.addEventListener('click', () => {
                        //  fileInput.click();
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
                       
                           const maxSizeMB = 1;
                           if (file.size > maxSizeMB * 1024 * 1024) {
                               fileInput.value = '';
                                 Swal.fire({
                                   title: 'Ukuran gambar melebihi 1 MB',
                                   icon: 'warning',
                                   showConfirmButton: false,
                                   timer: 2000
                                 });
                           }
                           
                            const img = new Image();
                            img.onload = function () {
                                if (img.width < 350 || img.height < 350) {
                                     Swal.fire({
                                        title: 'Panjang dan Lebar gambar minimal 350px',
                                        icon: 'warning',
                                        showConfirmButton: false,
                                        timer: 2000
                                      });
                                } 
                                else
                                {
                                    // Upload file asli ke server
                                   const formData = new FormData();
                                   formData.append('index', index.value);
                                   formData.append('kode', $("#BARANGLAZADA").val()+"_"+warna[index.value]);
                                   formData.append('file', file);
                                   formData.append('tipe', 'GAMBAR');
                                   formData.append('size', file.size);
                                   formData.append("reason","produk");
                               
                                   loading();
                                   
                                   $.ajax({
                                     type: 'POST',
                                     url: base_url + 'Lazada/uploadLocalUrl/',
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
                                URL.revokeObjectURL(img.src); // free memory
                            };
                             img.src = URL.createObjectURL(file); 
                       
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
                             
                       //       var row = JSON.parse($("#rowDatalazada").val());
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
                       //           url: base_url + 'Lazada/uploadLocalUrl/',
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
                               	title            : 'Hanya mendukung file Gambar',
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
        url       : base_url+'Lazada/getKategori',
        dataType  : 'json',
        beforeSend: function (){
            //$.messager.progress();
        },
        success: function(msg){
           var selectKategori = "<option value='0'>-Pilih Kategori-</option>";
           $("#KATEGORILAZADA").html(selectKategori);
           for(var x = 0 ; x < msg.length ; x++)
           {
              selectKategori += "<option value='"+msg[x].VALUE+"'>"+msg[x].TEXT+"</option>"; 
           }
           $("#KATEGORILAZADA").html(selectKategori);
           $("#KATEGORILAZADA").select2();
        }
    });
    getMasterBarang("");
});

function getMasterBarang(jenis){
    $.ajax({
        type      : 'POST',
        url       : base_url+'Master/Data/Barang/dataGrid',
        data      : {'jenis' : jenis},
        dataType  : 'json',
        beforeSend: function (){
            //$.messager.progress();
        },
        success: function(msg){
           var msg = msg.rows;
           var selectBarang = "<option value='0'>-Pilih Master Barang-</option>";
           dataMasterLazada = msg;
           $("#BARANGLAZADA").html(selectBarang);
           for(var x = 0 ; x < msg.length ; x++)
           {
              selectBarang += "<option value='"+msg[x].KATEGORI+"'>"+msg[x].KATEGORI+"</option>"; 
           }
           $("#BARANGLAZADA").html(selectBarang);
           $("#BARANGLAZADA").select2();
        }
    });
}

function checklazadaDetail(){
    $("#modal-check-lazada").modal('show');
    $("#historyPerubahanlazada").html("<tr><th width='6%'>No</th><th width='60%'>Keterangan</th><th width='17.5%'>Saat Ini</th><th width='17.5%'>Sebelumnya</th></tr>");
    for(var x = 0 ; x < historyPerubahanlazada.length; x++)
    {
        $("#historyPerubahanlazada").append("<tr valign='top'><td>"+(x+1)+". </td><td>"+historyPerubahanlazada[x].label+"</td><td>"+historyPerubahanlazada[x].baru+"</td><td>"+historyPerubahanlazada[x].lama+"</td></tr>");
    }
}


function ubahLazada(row){
    reset();
    historyPerubahanlazada = [];
    setGambarProduk();
    $("#checklazada").hide();
    $("#DIVDATAVARIAN").hide();
    $("#DIVDATANONVARIAN").hide();
    $("#DIVGAMBARVARIANLAZADA").hide();
    $("#titleLazada").html("Ubah Produk");
    loading();
    $("#modeLazada").val("UBAH");
    if(row.KATEGORIMASTERBARANG != "")
    {
        getMasterBarang("");
    }
    else
    {
        getMasterBarang('LAZADA');
    }
    setTimeout(() => { 
        $("#btn_simpan_detail_lazada").show();
        if(row.STATUS == "InActive")
        { 
            $("#AKTIF").prop('checked',false).iCheck('update');
        }
        else if(row.STATUS == "Active")
        {  
            $("#AKTIF").prop('checked',true).iCheck('update');
        }
        else
        {
            $("#btn_simpan_detail_lazada").hide();
        }
        
        $("#IDBARANGLAZADA").val(row.item_id);
    	
        $("#modal-barang-lazada").modal("show");
        $("#KATEGORILAZADA").val(row.primary_category);
        $("#BARANGLAZADA").val(row.KATEGORIMASTERBARANG);
        $(".select2").trigger('change');
        
        $("#KATEGORILAZADA").attr('disabled','disabled');
        if(row.KATEGORIMASTERBARANG != "")
        {
            $("#BARANGLAZADA").attr('disabled','disabled');
        }
        else
        {
            $("#BARANGLAZADA").removeAttr('disabled');
        }
    	
        var table = $('#dataGridVarianLazada').DataTable();
        table.ajax.url(base_url+'Master/Data/Barang/getDataVarian/'+encodeURIComponent(row.KATEGORIMASTERBARANG));
    	table.ajax.reload();
    	
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Lazada/getDataBarang/',
        	data    : {idindukbaranglazada: row.item_id},
        	dataType: 'json',
        	success : function(msg){
        	    if(msg.dataVarian.length == 0 )
        	    {
        	        var price = row.skus[0].price;
        	        
        	        if(price != $("#HARGAJUALMASTERLAZADA").val())
        	        {
        	            historyPerubahanlazada.push({
        	                'label' : 'Harga Produk',
        	                'lama'  : "Rp"+currency(price.toString()),
        	                'baru'  : "Rp"+currency($("#HARGAJUALMASTERLAZADA").val().toString())
        	            });
        	            $("#checklazada").show();
        	        }
        	        if(row.skus[0].SellerSku != $("#SKUMASTERLAZADA").val())
        	        {
        	            historyPerubahanlazada.push({
        	                'label' : 'SKU Produk',
        	                'lama'  : (row.skus[0].SellerSku.toString()),
        	                'baru'  : ($("#SKUMASTERLAZADA").val().toString())
        	            });
        	            $("#checklazada").show();
        	        }
        	        if((row.skus[0].package_weight * 1000) != $("#BERATMASTERLAZADA").val())
        	        {
        	            historyPerubahanlazada.push({
        	                'label' : 'Berat Produk',
        	                'lama'  : currency((row.skus[0].package_weight * 1000).toString())+" gram",
        	                'baru'  : currency($("#BERATMASTERLAZADA").val().toString())+" gram"
        	            });
        	            $("#checklazada").show();
        	        }
        	        if(row.skus[0].package_length != $("#PANJANGMASTERLAZADA").val())
        	        {
        	            historyPerubahanlazada.push({
        	                'label' : 'Panjang Produk',
        	                'lama'  : currency(row.skus[0].package_length.toString())+" cm",
        	                'baru'  : currency($("#PANJANGMASTERLAZADA").val().toString())+" cm"
        	            });
        	            $("#checklazada").show();
        	        }
        	        if(row.skus[0].package_width != $("#LEBARMASTERLAZADA").val())
        	        {
        	            historyPerubahanlazada.push({
        	                'label' : 'Lebar Produk',
        	                'lama'  : currency(row.skus[0].package_width.toString())+" cm",
        	                'baru'  : currency($("#LEBARMASTERLAZADA").val().toString())+" cm"
        	            });
        	            
        	            $("#checklazada").show();
        	        }
        	        if(row.skus[0].package_height != $("#TINGGIMASTERLAZADA").val())
        	        {
        	            historyPerubahanlazada.push({
        	                'label' : 'Lebar Produk',
        	                'lama'  : currency(row.skus[0].package_height.toString())+" cm",
        	                'baru'  : currency($("#TINGGIMASTERLAZADA").val().toString())+" cm"
        	            });
        	            $("#checklazada").show();
        	        }
        	        
        	        $("#DIVDATAVARIAN").hide();
                    $("#DIVDATANONVARIAN").show();
                    $("#DIVGAMBARVARIANLAZADA").hide();
        	    }
        	    else
        	    {
        	        $("#DIVDATAVARIAN").show();
                    $("#DIVDATANONVARIAN").hide();
                    $("#DIVGAMBARVARIANLAZADA").show();
        	    }
        	    
                loading();
        	    setTimeout(() => { 
        	            //VARIAN
        	            // CEK ADA BARANG BARU APA NDAK
                        table.rows().every(function () {
                            var rowData = this.data();
                            var ada = false;
                    	    for(var x = 0 ; x < msg.dataVarian.length; x++)
                    	    {
                                if (rowData.IDBARANGLAZADA == msg.dataVarian[x].ID) {
                                    ada = true;
                                }
                    	    }
                    	    
                    	    if(!ada)
                    	    {  
                    	        historyPerubahanlazada.push({
                	                'label' : 'Varian '+rowData.NAMABARANG,
                	                'lama'  : '-',
                	                'baru'  : 'Baru'
                	            });
                	            
                                $("#checklazada").show();
                               // Update the NAMABARANG field
                               rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:yellow; text-align:center; padding:5px; width:100px;'>Varian Baru</i>";
                               rowData.MODE = "BARU";
                               // Set the updated data back into the row
                               this.data(rowData).draw(false); // draw(false) avoids full table redraw
                    	    }
                        });
                        
                        //CEK ADA YANG BERUBAH
                        table.rows().every(function () {
                            var rowData = this.data();
                            var ada = false;
                            var perubahan = false;
                    	    for(var x = 0 ; x < msg.dataVarian.length; x++)
                    	    {
                                if (rowData.IDBARANGLAZADA == msg.dataVarian[x].ID) {
                                    rowData.MODE = "";
                                    if(rowData.HARGAJUAL != msg.dataVarian[x].HARGA)
                                    {
                                        perubahan = true;
                                        historyPerubahanlazada.push({
                        	                'label' : 'Harga Varian '+rowData.NAMABARANG,
                        	                'lama'  : "Rp"+currency(msg.dataVarian[x].HARGA.toString()),
                        	                'baru'  : "Rp"+currency(rowData.HARGAJUAL.toString())
                        	            });
                	            
                                       $("#checklazada").show();
                                    // Update the NAMABARANG field
                                       rowData.IDBARANG   = msg.dataVarian[x].ID,
                                       rowData.MODE += "UBAH HARGA";
                                       // Set the updated data back into the row
                                       this.data(rowData).draw(false); // draw(false) avoids full table redraw
                                    }
                                    if(rowData.SKULAZADA != msg.dataVarian[x].SKU)
                                    {
                                        perubahan = true;
                                        if(rowData.MODE != "")
                                        {
                                            rowData.MODE += "|";
                                        }
                                        
                                        historyPerubahanlazada.push({
                        	                'label' : 'SKU Varian '+rowData.NAMABARANG,
                        	                'lama'  : (msg.dataVarian[x].SKU.toString()),
                        	                'baru'  : (rowData.SKULAZADA.toString())
                        	            });
                        	            
                                        $("#checklazada").show();
                                    // Update the NAMABARANG field
                                       rowData.IDBARANG   = msg.dataVarian[x].ID,
                                       rowData.MODE += "UBAH SKU";
                                       // Set the updated data back into the row
                                       this.data(rowData).draw(false); // draw(false) avoids full table redraw
                                    }
                                }
                    	    }
                    	    if(perubahan)
                    	    {
                                rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:lightblue; text-align:center; padding:5px; width:100px;'>Data Diubah</i>";
                                this.data(rowData).draw(false);
                    	    }
                        });
                        
                        // CEK ADA YANG DIHAPUS APA NDAK
                        for(var x = 0 ; x < msg.dataVarian.length; x++)
                    	{
                            ada = false;
                            table.rows().every(function () {
                                var rowData = this.data();
                                if (rowData.IDBARANGLAZADA == msg.dataVarian[x].ID) {
                                    ada = true;
                                }
                            });
                            
                            if(!ada)
                            {
                                historyPerubahanlazada.push({
                        	        'label' : 'Varian '+msg.dataVarian[x].NAMA,
                        	        'lama'  : 'Dihapus',
                        	        'baru'  : '-'
                        	    });
                        	            
                               $("#checklazada").show();
                               var nama = msg.dataVarian[x].NAMA.replaceAll(' | SIZE ',' <span>|</span> SIZE ')+" <i class='pull-right' style='background:#FF5959; text-align:center; padding:5px; width:100px; color:white;'>Varian Dihapus</i>";
                 
                               // Update the NAMABARANG field
                               var newRow = {
                                  IDBARANG   : msg.dataVarian[x].ID,
                                  NAMABARANG : nama,
                                  HARGAJUAL : msg.dataVarian[x].HARGA,
                                  SIZE : msg.dataVarian[x].SIZE,
                                  WARNA : msg.dataVarian[x].WARNA,
                                  HARGAJUAL : msg.dataVarian[x].HARGA,
                                  SKULAZADA : msg.dataVarian[x].SKU,
                                  MODE : 'HAPUS'
                               };
                               
                               table.row.add(newRow).draw(false); // draw(false) avoids full table redraw
                            }
                    	}
                    	
                    	var imageProduk = row.images;
                    	//GAMBAR PRODUK
                    	for(var y = 0 ; y < imageProduk.length ; y++)
                    	{
                    	   // $("#file-input-lazada-"+y).val("-");
                    	    $("#format-input-lazada-"+y).val('GAMBAR');
                    	    $("#index-input-lazada-"+y).val(0);
                    	    $("#src-input-lazada-"+y).val(imageProduk[y]);
                    	    $("#keterangan-input-lazada-"+y).val("Gambar Produk "+(y+1).toString());
                    	    $("#id-input-lazada-"+y).val(imageProduk[y].split("/")[imageProduk[y].split("/").length-1].split(".")[0]);
                    	    $("#preview-image-lazada-"+y).attr("src",imageProduk[y]);
        
                        //     	$("#ubahGambarProdukLazada-"+y).show();
                        //     	$("#hapusGambarProdukLazada-"+y).show();
                   
                    	    
                    	}
                    	
                    	//GAMBAR VARIAN
                    	for(var y = 0 ; y < warna.length ; y++)
                    	{
                    	    for(var z = 0 ; z < msg.dataGambarVarian.length; z++)
                    	    {
                        	    if(msg.dataGambarVarian[z].WARNA == warna[y])
                        	    {
                            	   // $("#file-input-varian-lazada-"+y).val("-");
                            	    $("#format-input-varian-lazada-"+y).val('GAMBAR');
                            	    $("#index-input-varian-lazada-"+y).val(y);
                            	    $("#src-input-varian-lazada-"+y).val(msg.dataGambarVarian[z].IMAGEURL);
                            	    $("#keterangan-input-varian-lazada-"+y).val("Gambar Varian "+warna[y]);
                            	    $("#id-input-varian-lazada-"+y).val(msg.dataGambarVarian[z].IMAGEID);
                            	    $("#preview-image-varian-lazada-"+y).attr("src",msg.dataGambarVarian[z].IMAGEURL);
                  
                                // 	    $("#ubahGambarVarianLazada-"+y).show();
                        	       //     $("#hapusGambarVarianLazada-"+y).show();
                      
                        	    }
                    	    }
                    	}
                        
                        Swal.close();
        	     }, "1500");
        	    
        	}
           
        });
    	
    }, "1500");

}

function hapusLazada(row){
    Swal.fire({
        title: 'Anda Yakin Akan Menghapus Barang ini di Lazada <br>'+row.NAMABARANG+' ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
        	     loading();
        	     $.ajax({
                	type    : 'POST',
                	url     : base_url+'Lazada/removeBarang/',
                	data    : {idindukbaranglazada: row.item_id, skulistlazada : JSON.stringify(row.skus)},
                	dataType: 'json',
                	success : function(msg){
                	    Swal.close();	
                        Swal.fire({
                        	title            :  msg.msg,
                        	type             : (msg.success?'success':'error'),
                        	showConfirmButton: false,
                        	timer            : 2000
                        });
                        
                        setTimeout(() => { 
                            if(msg.success)
                            {
                                $("#datagridLazada").DataTable().ajax.reload();
                                reset();
                            }
                        }, "1000");
                	}
        	        
        	     });
            }
    });
}

function reset() {
    $("#dataGridVarianLazada").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian');
    $("#dataGridVarianLazada").DataTable().ajax.reload();
    
    $("#NAMALAZADA").val("");
    $("#DESKRIPSILAZADA").val("");
    $("#BERATMASTERLAZADA").val("");
    $("#PANJANGMASTERLAZADA").val("");
    $("#LEBARMASTERLAZADA").val("");
    $("#TINGGIMASTERLAZADA").val("");
    $("#IDBARANGLAZADA").val(0);
    $("#HARGAJUALMASTERLAZADA").val(0);
    $("#SKUMASTERLAZADA").val("");
    
    $("#AKTIF").prop('checked',true).iCheck('update');
    
    $("#KATEGORILAZADA").removeAttr('disabled');
    $("#BARANGLAZADA").removeAttr('disabled');
    
    $("#NAMALAZADA").prop('readonly',true);
    $("#DESKRIPSILAZADA").prop('readonly',true);
    
	$("#gambarProdukLazada").html("");
	$("#gambarProdukLazada").css('margin-bottom','0px');
	$("#gambarVarianLazada").html("");
	$("#gambarVarianLazada").css('margin-bottom','0px');
	warna = [];
    ukuran = [];
    attributlazada = [];
    attributlazadaOld = [];
    historyPerubahanlazada = [];
}

$("#BARANGLAZADA").change(function(){
    if($(this).val() == 0 && $(this).val() != null)
    {
        reset();
    }
    else
    {
        var idbarangdarimaster = 0;
		for(var x = 0 ; x < dataMasterLazada.length; x++)
		{
		    if(dataMasterLazada[x].KATEGORI == $(this).val())
		    {
		        $("#NAMALAZADA").val(dataMasterLazada[x].KATEGORI);
		        $("#DESKRIPSILAZADA").val(dataMasterLazada[x].DESKRIPSI.replaceAll("\\R\\N","\\r\\n").replaceAll("???? ",""));
		        $("#BERATMASTERLAZADA").val(dataMasterLazada[x].BERAT);
		        $("#PANJANGMASTERLAZADA").val(dataMasterLazada[x].PANJANG);
		        $("#LEBARMASTERLAZADA").val(dataMasterLazada[x].LEBAR);
		        $("#TINGGIMASTERLAZADA").val(dataMasterLazada[x].TINGGI);
		        
		        if(dataMasterLazada[x].JMLVARIAN > 0)
		        {
		            $("#DIVDATAVARIAN").show();
		            $("#DIVDATANONVARIAN").hide();
		            $("#DIVGAMBARVARIANLAZADA").show();
                    $("#dataGridVarianLazada").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian/'+encodeURIComponent($(this).val()));
            		$("#dataGridVarianLazada").DataTable().ajax.reload();
            		$("#HARGAJUALMASTERLAZADA").val(0);
                    $("#SKUMASTERLAZADA").val('');
		        }
		        else
		        {
		            $("#DIVDATAVARIAN").hide();
		            $("#DIVDATANONVARIAN").show();
		            $("#DIVGAMBARVARIANLAZADA").hide();
		            
		            $("#HARGAJUALMASTERLAZADA").val(dataMasterLazada[x].HARGAJUAL);
                    $("#SKUMASTERLAZADA").val(dataMasterLazada[x].SKULAZADA);
                    $("#dataGridVarianLazada").DataTable().clear().draw();
                    warna = [];
                    ukuran = [];

		        }
		        
		        setGambarProduk();
		        idbarangdarimaster = dataMasterLazada[x].IDBARANG;

    	        setTimeout(function() {
		         if(idbarangdarimaster != 0)
        	        {
        	            $.ajax({
                        	type    : 'POST',
                        	url     : base_url+'Master/Data/Barang/getGambarBarang/',
                        	data    : {idbarang:idbarangdarimaster},
                        	dataType: 'json',
                        	success : function(msg){
                        	    
                        	    var imageProduk = msg.dataInduk;
                            	//GAMBAR PRODUK
                            	for(var y = 0 ; y < imageProduk.length ; y++)
                            	{
                            	   // $("#file-input-"+y).val("-");
                            	    $("#format-input-lazada-"+y).val('GAMBAR');
                            	    $("#index-input-lazada-"+y).val(y);
                            	    $("#src-input-lazada-"+y).val(imageProduk[y].URL);
                            	    $("#keterangan-input-lazada-"+y).val("Gambar Produk "+(y+1).toString());
                            	    $("#id-input-lazada-"+y).val(imageProduk[y].ID);
                            	    $("#preview-image-lazada-"+y).attr("src",imageProduk[y].URL);
                            	    
                            	 
                                //     	$("#ubahGambarProdukLazada-"+y).show();
                                //     	$("#hapusGambarProdukLazada-"+y).show();
                             
                                	
                                	dataGambar[y] = {
                                       'ID'   : $("#id-input-lazada-"+y).val(),
                                       'NAMA' : "INDUK_"+$("#index-input-lazada-"+y).val(),
                                       'URL'  : $("#preview-image-lazada-"+y).attr("src"),
                                    };
                            	    
                            	}
                            	
                        	    var imageVarian = msg.dataGambarVarian;
                        	    for(var y = 0 ; y < imageVarian.length ; y++)
                            	{
                            	    dataGambarVarian[y] = {
                                       'ID'   : '',
                                       'NAMA' : '',
                                       'URL'  : '',
                                    };
                                                                           
                            	    for(var z = 0 ; z < imageVarian.length ; z++)
                            	    {
                            	        if("Gambar Varian "+imageVarian[z].NAMA == $("#keterangan-input-varian-lazada-"+y).val())
                            	        {
                                    	    // $("#file-input-varian-"+y).val("-");
                                            $("#format-input-varian-lazada-"+y).val('GAMBAR');
                                            $("#index-input-varian-lazada-"+y).val(y);
                                            $("#src-input-varian-lazada-"+y).val(imageVarian[z].URL);
                                            $("#id-input-varian-lazada-"+y).val(imageVarian[z].ID);
                                            $("#preview-image-varian-lazada-"+y).attr("src",imageVarian[z].URL);
                                            
                                          
                                            //     $("#ubahGambarVarianLazada-"+y).show();
                                        	   // $("#hapusGambarVarianLazada-"+y).show();
                                        
                                    	    
                                    	    dataGambarVarian[y] = {
                                               'ID'   : $("#id-input-varian-lazada-"+y).val(),
                                               'NAMA' : imageVarian[z].NAMA,
                                               'URL'  : $("#preview-image-varian-lazada-"+y).attr("src"),
                                            };
                            	        }
                            	    }
                            	}
                        	}
                        	    
                        });
        	        }
    	        }, 1000);
		    }
		}
    }
})

function getStatuslazada(){
	return $("#statuslazada").val();
}

function lihatLebihJelaslazada(jenis,title,url){

    $("#modal-lebih-jelas-lazada").modal("show");
    $("#titleLebihJelaslazada").html(title);
    $("#previewLebihJelaslazada").css("color","#3296ff");
    $("#previewLebihJelaslazada").css("cursor","pointer");
    $("#previewLebihJelaslazada").css("text-align","center");
    $("#previewLebihJelaslazada").css("background","#d4d4d7");
    if(jenis == "GAMBAR")
    {
        $("#previewLebihJelaslazada").html("<img src='"+url+"' max-width=100%; height=600px;>");
    }
    else
    {
        $("#previewLebihJelaslazada").html("<iframe src='"+url+"' max-width=100%; height=600px;>");
    }
}

function naikkanLazada(){
    $("#dataGridNaikkanProdukLazada").DataTable().ajax.reload();
    $("#modal-naik-produk-lazada").modal('show');
}

function tambahLazada(){
    $("#checklazada").hide();
    $("#DIVDATAVARIAN").hide();
    $("#DIVDATANONVARIAN").hide();
    $("#DIVGAMBARVARIANLAZADA").hide();
    $("#titleLazada").html("Tambah Produk");
    $("#modeLazada").val("TAMBAH");
    reset();
    getMasterBarang('LAZADA');
    $("#modal-barang-lazada").modal("show");
    $("#KATEGORILAZADA").val(0);
    $("#BARANGLAZADA").val(0);
    $(".select2").trigger('change');
    setGambarProduk();
}

function simpanLazada(){
    var arrImage = [];
    var arrImageID = [];
    var arrImageBukanLazada = [];
    for(var y = 0 ; y < 8 ;y++)
    {
        //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
        if($("#src-input-lazada-"+y).val() != "")
        {
            arrImageID.push($('#id-input-lazada-'+y).val());
            arrImage.push($('#src-input-lazada-'+y).val());
            if(!$("#src-input-lazada-"+y).val().includes('https://sg-test-11.slatic.net')){
                arrImageBukanLazada.push(
                    {
                        "id" : $("#id-input-lazada-"+y).val(),
                        "url" : $("#src-input-lazada-"+y).val(),
                        "url-baru" : ""
                    }
                )
            }
        }
    }
    
    var arrImageVarian = [];
    var arrImageIDVarian = [];
    for(var y = 0 ; y < warna.length; y++)
    {
        //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
        if($("#src-input-varian-lazada-"+y).val() != "")
        {
            arrImageIDVarian.push($('#id-input-varian-lazada-'+y).val());
            arrImageVarian.push($('#src-input-varian-lazada-'+y).val());
            if(!$("#src-input-lazada-"+y).val().includes('https://sg-test-11.slatic.net')){
                arrImageBukanLazada.push(
                    {
                        "id" : $('#id-input-varian-lazada-'+y).val(),
                        "url" :$("#src-input-varian-lazada-"+y).val(),
                        "url-baru" : ""
                    }
                )
            }
        }
    }
    
    if(($("#KATEGORILAZADA").val() == 0 || $("#KATEGORILAZADA").val() == null) || ($("#BARANGLAZADA").val() == 0 || $("#BARANGLAZADA").val() == null) || arrImage.length < 2 || arrImageVarian.length != warna.length) // || base64Images.length == 0
    {
        Swal.fire({ 
        	title            : "Terdapat Data Produk yang belum diisi",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
        loading();
        
        let ajax1;

        if (arrImageBukanLazada.length > 0) {
            ajax1 = $.ajax({
                type    : 'POST',
                url     : base_url+'Lazada/changeLocalUrl/',
                data    : {
                    "url" : JSON.stringify(arrImageBukanLazada),
                },
                dataType: 'json'
            });
        } else {
            // kalau kosong langsung resolve dummy deferred
            ajax1 = $.Deferred().resolve().promise();
        }

        $.when(ajax1).done(function(msg){
            var error = false;
            if(msg)
            {
                if (msg.success) {
                    for(var x = 0 ; x < arrImageID.length ; x++)
                    {
                        for(var y = 0 ; y < msg.data.length ; y++)
                        {
                            if(arrImageID[x] == msg.data[y]['id'])
                            {
                                arrImage[x] = msg.data[y]['url-baru']
                            }
                        }
                    }
                    for(var x = 0 ; x < arrImageIDVarian.length ; x++)
                    {
                        for(var y = 0 ; y < msg.data.length ; y++)
                        {
                            if(arrImageIDVarian[x] == msg.data[y]['id'])
                            {
                                arrImageVarian[x] = msg.data[y]['url-baru']
                            } 
                        }
                    }
                }
                else
                {
                    error = true;
                    Swal.close();	
                    // Swal.fire({
                    //     title            : msg.msg,
                    //     type             : (msg.success?'success':'error'),
                    //     showConfirmButton: false,
                    //     timer            : 2000
                    // });
                    simpanLazada();
                }
            }
            
            if(!error)
            {
                // hanya sekali dipanggil di sini
                $.ajax({
                    type    : 'POST',
                    url     : base_url+'Lazada/setBarang/',
                    data    : {
                        "IDBARANG"       : $("#IDBARANGLAZADA").val(),
                        "KATEGORI"       : $("#KATEGORILAZADA").val(), 
                        "NAMA"           : $("#NAMALAZADA").val(), 
                        "DESKRIPSI"      : $("#DESKRIPSILAZADA").val(), 
                        "BERAT"          : $("#BERATMASTERLAZADA").val(), 
                        "PANJANG"        : $("#PANJANGMASTERLAZADA").val(), 
                        "LEBAR"          : $("#LEBARMASTERLAZADA").val(), 
                        "TINGGI"         : $("#TINGGIMASTERLAZADA").val(), 
                        "HARGA"          : $("#HARGAJUALMASTERLAZADA").val(),      
                        "SKU"            : $("#SKUMASTERLAZADA").val(), 
                        "AKTIF"          : $("#AKTIF").prop("checked")? 1 : 0,
                        "VARIAN"         : JSON.stringify($('#dataGridVarianLazada').DataTable().rows().data().toArray()),
                        "WARNA"          : JSON.stringify(warna),
                        "UKURAN"         : JSON.stringify(ukuran),
                        "GAMBARPRODUK"   : JSON.stringify(arrImage),
                        "GAMBARVARIAN"   : JSON.stringify(arrImageVarian)
                    },
                    dataType: 'json',
                    success : function(msg){
                        Swal.close();	
                        Swal.fire({
                            title            : msg.msg,
                            type             : (msg.success?'success':'error'),
                            showConfirmButton: false,
                            timer            : 2000
                        });
                
                        setTimeout(() => { 
                            if(msg.success) {
                                $("#modal-barang-lazada").modal("hide");
                                $("#datagridLazada").DataTable().ajax.reload();
                                reset();
                            }
                        }, "1000");
                    }
                });
            }
        });
    }
}

function setGambarProduk(){
    
    //GAMBAR PRODUK
    var htmlGambarProduk = "<tr>";
    var label = "Gambar Utama";
    
    for(var y = 0 ; y < 8 ;y++)
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
        
        if(y > 0)
        {
            label = "Gambar "+(y+1);
        }
        
        htmlGambarProduk += `
                        <td>
                            <input type="file" id="file-input-lazada-`+y+`" accept="image/jpeg,image/jpg,image/png" style="display:none;" value="">
                            <input type="hidden"  id="format-input-lazada-`+y+`" value="">
                            <input type="hidden"  id="index-input-lazada-`+y+`" value="`+y+`">
                            <input type="hidden"  id="src-input-lazada-`+y+`" value="">
                            <input type="hidden"  id="keterangan-input-lazada-`+y+`" value="Gambar Produk `+(y+1).toString()+`">
                            <input type="hidden"  id="id-input-lazada-`+y+`" value="">
                            
                            <div style="margin-bottom:20px;">
                                <img id="preview-image-lazada-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; margin-right:`+marginRight+`; cursor:pointer; border:2px solid #dddddd;'>
                                <div style="text-align:center; margin-right:`+marginRight+`"><b>`+label+`</b><br>
                                <span id="ubahGambarProdukLazada-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                &nbsp;
                                <span id="hapusGambarProdukLazada-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                </div>
                            </div>
                        </td>`;  
                        
        utama = "";
    
    }
    htmlGambarProduk += "</tr>";
    $("#gambarProdukLazada").html(htmlGambarProduk);
    $("#gambarProdukLazada").css('margin-bottom','-40px');
    
    for(var y = 0 ; y < 8 ;y++)
    {
        const fileInput = document.getElementById('file-input-lazada-'+y);
        const previewImage = document.getElementById('preview-image-lazada-'+y);
        const title = document.getElementById('keterangan-input-lazada-'+y);
        const format = document.getElementById('format-input-lazada-'+y);
        const index = document.getElementById('index-input-lazada-'+y);
        const url =  document.getElementById('src-input-lazada-'+y);
        const id = document.getElementById('id-input-lazada-'+y);
        
        const ubahImage = document.getElementById('ubahGambarProdukLazada-'+y);
        const hapusImage = document.getElementById('hapusGambarProdukLazada-'+y);
        
        previewImage.addEventListener('click', () => {
          if(url.value != '')
          {
              lihatLebihJelaslazada(format.value,title.value,url.value);
          }
          else
          {
            // fileInput.click();
          }
        });
        
        ubahImage.addEventListener('click', () => {
        //   fileInput.click();
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
        
            const maxSizeMB = 1;
            if (file.size > maxSizeMB * 1024 * 1024) {
                fileInput.value = '';
              Swal.fire({
                title: 'Ukuran gambar melebihi 1 MB',
                icon: 'warning',
                showConfirmButton: false,
                timer: 2000
              });
              return;
            }
            
            const img = new Image();
            img.onload = function () {
                if (img.width < 350 || img.height < 350) {
                     Swal.fire({
                        title: 'Panjang dan Lebar gambar minimal 350px',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000
                      });
                } 
                else
                {
                      // Upload file asli ke server
                    const formData = new FormData();
                    formData.append('index', index.value);
                    formData.append('kode', $("#BARANGLAZADA").val()+"_"+index.value);
                    formData.append('file', file);
                    formData.append('tipe', 'GAMBAR');
                    formData.append('size', file.size);
                    formData.append("reason","produk");
                
                    loading();
                    
                    $.ajax({
                      type: 'POST',
                      url: base_url + 'Lazada/uploadLocalUrl/',
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
                URL.revokeObjectURL(img.src); // free memory
            };
             img.src = URL.createObjectURL(file); 
        
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
         //       formData.append('kode', $("#BARANGLAZADA").val()+"_"+y);
         //       formData.append('file', file);
         //       formData.append('tipe', 'VIDEO');
         //       formData.append('size', file.size);
         //       formData.append("reason","produk");
            
         //         loading();
                
         //         $.ajax({
         //           type: 'POST',
         //           url: base_url + 'Lazada/uploadLocalUrl/',
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
                	title            : 'Hanya mendukung file Gambar',
                	type             : 'warning',
                	showConfirmButton: false,
                	timer            : 2000
            });
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
