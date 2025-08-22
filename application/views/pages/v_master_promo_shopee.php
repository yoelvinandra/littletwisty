<style>
  #table_barang td {
    white-space: normal !important;
    word-wrap: break-word;
  }
  
  .scroll-wrapper {
      max-height: 93vh;
      overflow-y: auto;
      overflow-x: hidden;
      padding-right: 15px; /* optional: for scrollbar space */
    }
    
   .bootstrap-datetimepicker-widget {
    min-width: 300px !important; /* You can adjust this value */
  }
  
  .pt-15 {
        padding-top: 15px !important;
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
                		<select id="cb_barang_status_shopee" name="cb_barang_status_shopee" class="form-control "  panelHeight="auto" required="true">
                			<option value="all">Semua </option>
                			<option value="ongoing" selected>Aktif</option>
                			<option value="upcoming">Akan Datang</option>
                			<option value="expired">Telah Berakhir</option>
                		</select>
                	</div>
                </div>
            </div>
            <br>          			
            <table id="dataGridPromoShopee" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                <!-- class="table-hover"> -->
                <thead>
                <tr>
                    <th width="80px"></th>
                    <th>Nama Promosi</th>
                    <th width="100px">Tgl Mulai</th>
                    <th width="100px">Tgl Akhir</th>
                    <th width="40px">Status</th>							
                </tr>
                </thead>
            </table>  
        </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row (main row) -->
  <!--MODAL PROMO-->
  <div class="modal fade" id="modal-barang-shopee" >
  	<div class="modal-dialog modal-lg ">
  	<div class="modal-content">
  	    <div class="scroll-wrapper">
      		<div class="modal-body">
                  <div class="box-body">
                      <input type="hidden" id="IDPROMOSISHOPEE" name="IDPROMOSISHOPEE">
                      <div class="row">
                          <div class="form-group col-md-12"  style="padding-right:0px;">
                              <div class="row">
                                   <div class="col-md-12" style="padding-right:0px;">
                                        <h3 style="font-weight:bold;" class="form-group" id="titleShopee">Tambah Promo</h3>
                                        <ul style="padding-left:15px;">
                            		        <li>Promo tidak bisa diberikan kepada barang yang sudah ada promo pada periode yang sama</li>
                                            <li>Ketika Tambah Promo, Minimal Tanggal Awal adalah 1 jam setelah promo dibuat</li>
                                            <li>Ketika Tambah Promo, Minimal Tanggal Akhir adalah 1 jam setelah Tanggal Awal ditentukan</li>
                                            <li>Ketika Ubah Promo, Tanggal Awal tidak bisa lebih kecil dari sebelumnya</li>
                                            <li>Ketuka Ubah Promo, Tanggal Akhir tidak bisa lebih besar dari sebelumnya</li>
                                            <li>Harga Promo harus lebih kecil dibandingkan Harga Jual Tampil</li>
                                        </ul>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Nama Promosi <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                        <input type="text" class="form-control" id="NAMAPROMOSISHOPEE" name="NAMAPROMOSISHOPEE" placeholder="Nama Produk">
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Tgl Mulai <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                        <input type="text" style="border:1px solid #B5B4B4; border-radius:1px;"  placeholder="Min 1 Jam dari Sekarang" class="form-control datetimepicker-input" id="TGLMULAISHOPEE" name="TGLMULAISHOPEE" data-toggle="datetimepicker" data-target="#TGLMULAISHOPEE"/>
                                	</div>
                                	<div class="form-group col-md-1" style="margin-top:35px; text-align:center;">
                                	    s/d
                                	</div>
                                	<div class="form-group col-md-3">
                                        <label>Tgl Akhir <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                		<input type="text" style="border:1px solid #B5B4B4; border-radius:1px;"  placeholder="Min 1 Jam dari Tgl Mulai" class="form-control datetimepicker-input" id="TGLAKHIRSHOPEE" name="TGLAKHIRSHOPEE" data-toggle="datetimepicker" data-target="#TGLAKHIRSHOPEE"/>
                                	</div>
                                	<div class="form-group col-md-1">
                                	</div>
                                    <div class="col-md-2">
                                       <label>Pot(%) Massal</label>
                                       <input type="text" class="form-control" style="width:100%; text-align:center;" id="persenAll" value="" placeholder="0" min="0" max="100" onblur="limitPersentase(event)" onkeyup="persentase(event)" mouseup="persentase(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>
                                    </div>
                                    <div class="col-md-2">
                                       <label>Batas Massal</label>
                                       <input type="text" class="form-control" style="width:100%; text-align:center;"id="batasAll" value="" placeholder="0" min="0" max="100" onblur="limitBatas(event)" onkeyup="batas(event)" mouseup="batas(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>
                                    </div>
                              </div>
                           </div>
                           <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                      <h4 style="font-weight:bold;">Produk yang Kena Promo <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></h4>
                                      <button type="button" style="float:left; position: relative; z-index: 10;"  id="btn_tambah_produk_shopee" class="btn btn-success" onclick="javascript:tambahProduk()">Tambah</button>
                                      <div style="font-size:16px; font-weight:normal; font-style:italic; padding-top:5px;">&nbsp;&nbsp;*Harga Promo diisi dari harga coret yang diatur pada master harga</div>
                                    </div>
                                    <div class="col-md-12" style="padding-right:0px; margin-top:-35px;">
                                        <table id="dataGridVarianPromo" class="table table-bordered table-striped table-hover display nowrap" >
                                            <!-- class="table-hover"> -->
                                            <thead>
                                                <tr>
                                                    <th width="20px"><input type='checkbox' id='pilihDetailProdukSemua' style="pt-15"></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Produk</th>
                                                    <th width="120px">Harga Jual Tampil</th>
                                                    <th width="120px">Harga Promo</th>
                                                    <th width="60px">Batas Pembelian</th>
                                                </tr>
                                            </thead>
                                        </table> 
                                    </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="box-footer">
                      <button type="button" id="btn_simpan_detail_shopee" class="btn btn-success" onclick="javascript:simpanShopee()">Simpan</button>
                      </div>
                  </div>
      		</div>
      	</div>
  	</div>
  </div>
  
  <!--MODAL BARANG-->
  <div class="modal fade" id="modal-barang">
  	<div class="modal-dialog modal-lg">
  	<div class="modal-content">
  		<div class="modal-body">
  		    <button type="button" id="btn_tambah_produk_shopee" class="btn btn-success" style="position:absolute; right:15px;" onclick="javascript:simpanProduk()">Pilih Produk <span id="countProduk"></span></button>
  			<table id="table_barang" class="table table-bordered table-striped table-hover display nowrap" width="100%">
  				<thead>
  					<tr>
  					    <th><input type="checkbox" id="pilihProdukSemua"></th>
  						<th>Nama</th>
  						<th>Jml Varian</th>
  						<th>Harga</th>
  					</tr>
  				</thead>
  			</table>
  		</div>
  	</div>
  	</div>
  </div>
                            								
</section>
<input type="hidden" id="statusShopee">
<input type="hidden" id="modeShopee">
<input type="hidden" id="statusTransShopee">

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />

<!-- /.content -->
<script>
var indexRow;
$(document).ready(function() {
    $("#persenAll").number(true,0);
    $("#batasAll").number(true,0);
    $('#TGLMULAISHOPEE').datetimepicker({
      format: 'YYYY-MM-DD HH:mm', // Change to 'L LT' for date + time
      sideBySide: true,
    });
    $('#TGLAKHIRSHOPEE').datetimepicker({
      format: 'YYYY-MM-DD HH:mm',// Change to 'L LT' for date + time
      sideBySide: true,
    });
//   $('#TGLMULAISHOPEE, #TGLAKHIRSHOPEE').timepicker({
//         timeFormat: 'HH:mm:ss'
//     });
	
    $("#statusShopee").val('ongoing');
    //MENAMPILKAN TRANSAKSI
    $("#cb_barang_status_shopee").change(function(event){
        loading();
        $("#statusShopee").val($(this).val());
        $("#dataGridPromoShopee").DataTable().ajax.reload();
    });
    
    $('#dataGridPromoShopee').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Shopee/dataGridPromo',
			dataSrc: "rows",
			type   : "POST",
			data   : function(e){
        	    e.status 		 = getStatusShopee();
        	},
		},
        columns:[
            {data: ''},
            {data: 'NAMAPROMOSI'}, 
            {data: 'TGLMULAI', width:120, className:"text-center"},  
            {data: 'TGLAKHIR', width:120, className:"text-center"},   
            {data: 'STATUS', width:120, className:"text-center"},            
        ],
		columnDefs: [
		    {
			    "targets": 0,
                "data": null,
                "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
			}
			,
			{
			    "targets": 2,
                "render" :function (data,display,row) 
    			{	
    			    return data.replaceAll(" ","<br>");
    			},		
			}
			,
			{
			    "targets": 3,
                "render" :function (data,display,row) 
    			{	
    			    return data.replaceAll(" ","<br>");
    			},		
			}
			,
			{
			    "targets": -1,
                "render" :function (data,display,row) 
    			{
    			    var status = "";
                	if(data == "ongoing")
                	{
                	    status = "Aktif";
                	}
                	if(data == "upcoming")
                	{
                	    status = "Akan Datang";
                	}
                	if(data == "expired")
                	{
                	    status = "Telah Berakhir";
                	}
                			
    			    return status;
    			},		
			}
		]
    });
    
    //DAPATKAN INDEX
	$('#dataGridPromoShopee tbody').on( 'click', 'button', function () {
		var row = $('#dataGridPromoShopee').DataTable().row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah"){ ubahShopee(row); }
		if(mode == "btn_hapus"){ hapusShopee(row); }
	});
    
    $('#dataGridPromoShopee').DataTable().on('xhr.dt', function () {
        Swal.close();
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
    
    $("#TGLMULAISHOPEE").on('change.datetimepicker', function(e) {
        $('#dataGridVarianPromo').DataTable().clear().draw();
    });
    
    $("#TGLAKHIRSHOPEE").on('change.datetimepicker', function(e) {
        $('#dataGridVarianPromo').DataTable().clear().draw();
    })
    
    $('#dataGridVarianPromo').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
    	"scrollX"	  : true,
//     	ajax		  : {
//     		url    : base_url+'Shopee/dataGridPromoBarang',
//     		dataSrc: "rows",
// 			type   : "POST",
//     	},
        columns:[
            {data: '',width:20, className: 'pt-15'},
            {data: 'MODE',width:0},
            {data: 'IDINDUKBARANGSHOPEE', visible:false},
            {data: 'IDBARANGSHOPEE', visible:false},
            {data: 'NAMABARANG', className: 'pt-15'},
            {data: 'HARGAJUALTAMPIL',width:100, className:"text-right"},
            {data: 'HARGACORET',width:100, className:"text-right"},
            {data: 'BATASPEMBELIAN',width:60, className:"text-center"},
        ],
    	'columnDefs': [
    		{
    		    "targets": 0,
                "data": null,
                "render" :function (data,display,row) 
    			{
    			    return '<input type="checkbox" id="check_'+row.ID+'" class="pilihDetailProduk">';
    			},	
    		},
    		{
    		    "targets": 1,
                "data": null,
                "render" :function (data,display,row) 
    			{
    			    return '<input type="hidden" id="mode_'+row.ID+'">';
    			},	
    		},
    		{
    		    "targets": 5,
                "data": null,
                "render" :function (data,display,row) 
    			{
    			    return '<input type="text" id="harga_jual_'+row.ID+'" style="width:100px; text-align:right;" disabled class="form-control hargajual">';
    			},	
    		},
    		{
    		    "targets": 6,
                "data": null,
                "render" :function (data,display,row) 
    			{
    			    return '<input type="text" id="harga_'+row.ID+'" style="width:100px; text-align:right;" disabled class="form-control hargacoret" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>';
    			},	
    		},
    		{
    		    "targets": 7,
                "data": null,
                "render" :function (data,display,row) 
    			{
    			    return '<input type="text" id="batas_'+row.ID+'"  style="width:60px; text-align:center;" disabled class="form-control bataspembelian" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>';
    			},	
    		}
    	]
    });
    
    $('#dataGridVarianPromo').DataTable().on('xhr.dt', function () {
        $(".hargacoret").number(true,0);
        $(".bataspembelian").number(true,0);
    });
    
    $('#pilihDetailProdukSemua').change(function (event) {
        let isChecked = $(this).prop('checked');
        $(".pilihDetailProduk").prop('checked',isChecked);
        
        var table = $('#dataGridVarianPromo').DataTable();
        var allData = table.rows().data(); 
        allData.each(function (value, index) {
            $("#harga_"+value.ID).attr('disabled','disabled');
            $("#batas_"+value.ID).attr('disabled','disabled');
            
            if($("#mode_"+value.ID).val() == "UBAH")
            {
                $("#mode_"+value.ID).val('HAPUS');
            }
        });
        
        $('input.pilihDetailProduk:checked').each(function () {
            let inputValuePersen = $("#persenAll").val();
            let inputValueBatas = $("#batasAll").val();
            let id = $(this).attr('id').replaceAll("check_","");
            $("#harga_"+id).removeAttr('disabled');
            $("#batas_"+id).removeAttr('disabled');
            
            allData.each(function (value, index) {
               if(value.ID == id)
               {
                    if(inputValuePersen > 0)
                    {
                        $("#harga_"+id).val(parseInt(value.HARGAJUALTAMPIL) - parseInt(parseInt(value.HARGAJUALTAMPIL) * parseInt(inputValuePersen) / 100));
                    }
                    
                    if(inputValueBatas > 0)
                    {
                        $("#batas_"+id).val(parseInt(inputValueBatas));
                    }
                    
                    if($("#mode_"+value.ID).val() == "HAPUS")
                    {
                        $("#mode_"+value.ID).val('UBAH');
                    }
               }
            });
        });
    });
    
     $(document).on('change', '.pilihDetailProduk', function (event) {
        let isChecked = $(this).prop('checked');
        let id = $(this).attr('id').replaceAll("check_","");
        if(isChecked)
        {
            $("#harga_"+id).removeAttr('disabled');
            $("#batas_"+id).removeAttr('disabled');
        }
        else
        {
            $("#harga_"+id).attr('disabled','disabled');
            $("#batas_"+id).attr('disabled','disabled');
        }
        
        let inputValuePersen = $("#persenAll").val();
        let inputValueBatas = $("#batasAll").val();
        var table = $('#dataGridVarianPromo').DataTable();
    
        // Get all data from the table
        var allData = table.rows().data(); 
        allData.each(function (value, index) {
           if(value.ID == id)
           {
                if(inputValuePersen > 0)
                {
                    $("#harga_"+id).val(parseInt(value.HARGAJUALTAMPIL) - parseInt(parseInt(value.HARGAJUALTAMPIL) * parseInt(inputValuePersen) / 100));
                }
                if(inputValueBatas > 0)
                {
                    $("#batas_"+id).val(parseInt(inputValueBatas));
                }
                
                if(!isChecked && $("#mode_"+value.ID).val() == "UBAH")
                {
                    $("#mode_"+value.ID).val('HAPUS');
                }
                else if(isChecked && $("#mode_"+value.ID).val() == "HAPUS")
                {
                    $("#mode_"+value.ID).val('UBAH');
                }
           }
        });
        
        
        var count = 0;
        $('input.pilihDetailProduk:checked').each(function () {
            count++;
        });
        var totalCount = $('#dataGridVarianPromo').DataTable().data().count();
        
        if (count == totalCount) {
            $("#pilihDetailProdukSemua").prop('checked', true);
        } else {
            $("#pilihDetailProdukSemua").prop('checked', false);
        }
    });
    
    $("#modal-barang").on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input', $("#table_barang").DataTable().table().container()).focus();
    });
    
    //TABLE BARANG
	$("#table_barang").DataTable({
        'retrieve'    : true,
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"dom"		  : '<"pull-left"f><"pull-right"l>tip',
		ajax		  : {
			url    : base_url+'Master/Data/Barang/dataGrid',   // Master/Data/Barang/loadData
        	dataSrc: function(json) {
                // Filter rows with KATEGORI !== 0
                json.rows = json.rows.filter(function(item) {
                    return item.IDINDUKBARANGSHOPEE != 0;
                });
                return json.rows;
            },
			type   : "POST",
    		data   : function(e){
        	    e.marketplace = 'SHOPEE';
        	},
		},
		language: {
			search           : "Cari",
			searchPlaceholder: "Nama Produk"
		},
        columns:[
            { data: ''},
            { data: 'KATEGORI'},
            { data: 'JMLVARIAN', width:80,className:"text-center"},
			{ data: 'RANGEHARGAUMUM', width:150,className:"text-center"},
        ],
        'columnDefs': [
    		{
    		    "targets": 0,
                "render" :function (data,display,row) 
    			{
    			    return '<input type="checkbox" id="'+row.KATEGORI+'" class="pilihProduk"></input>';
    			},	
    		}
    	]
		
    });
    
    $('#pilihProdukSemua').change(function (event) {
        let isChecked = $(this).prop('checked');
    
        $(".pilihProduk").prop('checked',isChecked);
        var totalCount = $('#table_barang').DataTable().data().count();
        if(isChecked)
        {
            $("#countProduk").html('('+totalCount+')');
        }
        else
        {
            $("#countProduk").html('');
        }
    });
    
    $(document).on('change', '.pilihProduk', function (event) {
        let isChecked = $(this).prop('checked');
        var count = 0;
        $('input.pilihProduk:checked').each(function () {
            count++;
        });
        var totalCount = $('#table_barang').DataTable().data().count();
        
        if(count < 1)
        {
            $("#countProduk").html('');
        }
        else
        {
            $("#countProduk").html('('+count+')');
        }
        
        if (count == totalCount) {
            $("#pilihProdukSemua").prop('checked', true);
        } else {
            $("#pilihProdukSemua").prop('checked', false);
        }
    });

});

function persentase(e){
  var inputElement = event.target;
  const classList = inputElement.classList;
  const inputValue =  parseInt(inputElement.value.replaceAll(",",""));
  
  if(inputValue < 0)
  {
      inputElement.value = 0;
  }
  
  if(inputValue > 100)
  {
      inputElement.value = 100;
  }
}

function batas(e){
   var inputElement = event.target;
   const classList = inputElement.classList;
   const inputValue =  parseInt(inputElement.value.replaceAll(",",""));
   
   if(inputValue < 0)
   {
       inputElement.value = 0;
   }  
}

function limitPersentase(e){
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue =  parseInt(inputElement.value.replaceAll(",",""));
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    
    if(inputValue > 100)
    {
        inputElement.value = 100;
    }
  
    $('input.pilihDetailProduk:checked').each(function () {
        let id = $(this).attr('id').replaceAll("check_","");
        var table = $('#dataGridVarianPromo').DataTable();

        // Get all data from the table
        var allData = table.rows().data(); 
        allData.each(function (value, index) {
           if(value.ID == id)
           {
                $("#harga_"+id).val(parseInt(value.HARGAJUALTAMPIL) - (parseInt(parseInt(value.HARGAJUALTAMPIL) * parseInt(inputValue) / 100)));
           }
        });
    });
}

function limitBatas(e){
  var inputElement = event.target;
  const classList = inputElement.classList;
  const inputValue =  parseInt(inputElement.value.replaceAll(",",""));
  
  if(inputValue < 0)
  {
      inputElement.value = 0;
  } 
  
   $('input.pilihDetailProduk:checked').each(function () {
        let id = $(this).attr('id').replaceAll("check_","");
        
        $("#batas_"+id).val(inputElement.value);
   });
}

function simpanProduk(){
    $("#modal-barang").modal('hide');
    var countAdd = 0;
    //GET DATA ALL
     $.ajax({
    	type    : 'POST',
    	url     : base_url+'Master/Data/Barang/comboGridAllMarketplaceVarian',
    	data    : {marketplace: 'SHOPEE'},
    	dataType: 'json',
    	success : function(msg){
    	    var dataBarang = msg.rows;
            var table = $('#dataGridVarianPromo').DataTable();
            var allData = table.rows().data(); 
            var listIDBarang = [];
    	    $('input.pilihProduk:checked').each(function () {
               var kategori = $(this).attr('id');
               listIDBarang.push("0");
               for(var x  = 0 ; x < dataBarang.length ; x++)
               {
                   if(dataBarang[x].KATEGORI == kategori)
                   {
                        var ada = false;
                        allData.each(function (value, index) {
                           if(value.ID == dataBarang[x].ID)
                           {
                               ada = true;
                           }
                        });
                        
                        if(!ada)
                        {
                            var newRow = {
                                'MODE'                  : 'TAMBAH',
                                'ID'                    : dataBarang[x].ID,
                                'IDINDUKBARANGSHOPEE'   : dataBarang[x].IDINDUKBARANGSHOPEE,
                                'IDBARANGSHOPEE'        : dataBarang[x].IDBARANGSHOPEE,
                                'NAMABARANG'            : dataBarang[x].NAMA,
                                'HARGAJUALTAMPIL'       : dataBarang[x].HARGAJUAL,
                                'HARGACORET'            : dataBarang[x].HARGA,
                                'BATASPEMBELIAN'        : 0
                            };
                            
                            listIDBarang[listIDBarang.length-1] = dataBarang[x].IDINDUKBARANGSHOPEE;
                            
                            // Add the row
                            var table = $('#dataGridVarianPromo').DataTable();
                            table.row.add(newRow).draw();
                            $("#mode_"+dataBarang[x].ID).val("TAMBAH");
                            $("#harga_jual_"+dataBarang[x].ID).val(dataBarang[x].HARGAJUAL);
                            $("#harga_"+dataBarang[x].ID).val(dataBarang[x].HARGA);
                            countAdd++;
                        }
                   }
               }
               if(listIDBarang[listIDBarang.length-1] == 0)
               {
                   listIDBarang.splice((listIDBarang.length-1), 1); 
               }
            });
            
            $(".hargajual").number(true,0);
            $(".hargacoret").number(true,0);
            $(".bataspembelian").number(true,0);
            
            if(countAdd > 0)
            {
                $("#pilihDetailProdukSemua").prop('checked', false);
            }
            
            $.ajax({
            	type    : 'POST',
            	url     : base_url+'Shopee/getItemPromo',
            	data    : {databarang: JSON.stringify(listIDBarang), tglmulai:$("#TGLMULAISHOPEE").val(),tglakhir:$("#TGLAKHIRSHOPEE").val()},
            	dataType: 'json',
            	success : function(msg){
            	    var dataBarang = msg.rows;
                    for(var x = 0 ; x < dataBarang.length;x++)
                    {
                        if(dataBarang[x].DISABLED)
                        {
                            $("#check_"+dataBarang[x].ID).attr("disabled","disabled");
                        }
                        else
                        {
                            $("#check_"+dataBarang[x].ID).removeAttr("disabled");
                        }
                    }
            	}});
    	}
    });
}

function getStatusShopee(){
	return $("#statusShopee").val();
}

function tambahProduk(){
    if($("#TGLMULAISHOPEE").val() != "" && $("#TGLAKHIRSHOPEE").val() != "")
    {
        const tglMulai = moment($('#TGLMULAISHOPEE').val(), 'YYYY-MM-DD HH:mm');
        const tglAkhir = moment($('#TGLAKHIRSHOPEE').val(), 'YYYY-MM-DD HH:mm');

        if (tglMulai.isAfter(tglAkhir)) {
          Swal.fire({ 
            	title            : "Tgl Mulai harus lebih kecil dari Tgl Akhir!",
            	type             : 'warning',
            	showConfirmButton: false,
            	timer            : 1500
           });
        }
        else
        {
            $("#table_barang").DataTable().ajax.reload();
            $("#modal-barang").modal('show');
        }
    }
    else
    {
        Swal.fire({ 
         	title            : "Tgl Mulai dan Tgl Akhir harus diisi",
         	type             : 'warning',
         	showConfirmButton: false,
         	timer            : 1500
        });
    }
}

function tambahShopee(){
    reset();
    $("#titleShopee").html("Tambah Promo");
    $("#modeShopee").val("TAMBAH");
    $("#modal-barang-shopee").modal('show');
}

function ubahShopee(row){
    reset();
    $("#titleShopee").html("Ubah Promo");
    if(row.STATUS == "expired")
    {
        $("#btn_simpan_detail_shopee").hide();
    }
    $("#statusTransShopee").val(row.STATUS);
    loading();
    $("#IDPROMOSISHOPEE").val(row.IDPROMOSI);
    $("#NAMAPROMOSISHOPEE").val(row.NAMAPROMOSI);
    $("#TGLMULAISHOPEE").val(row.TGLMULAI);
    $("#TGLAKHIRSHOPEE").val(row.TGLAKHIR);
    
    $("#NAMAPROMOSISHOPEE").attr('disabled','disabled');
    $("#TGLMULAISHOPEE").attr('disabled','disabled');
    $("#TGLAKHIRSHOPEE").attr('disabled','disabled');
    
    $("#modeShopee").val("UBAH");
    $("#modal-barang-shopee").modal('show');
    //GET DATA ALL
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/getPromo',
    	data    : {idpromosi: row.IDPROMOSI},
    	dataType: 'json',
    	success : function(msg){
    	    
    	    for(var x = 0 ; x < msg.rows.length; x++)
    	    {
    	        var rowData = msg.rows[x];
                // Add the row
                var table = $('#dataGridVarianPromo').DataTable();
                var rows = {
                    'MODE'                  : 'UBAH',
                    'ID'                    : rowData.ID,
                    'IDINDUKBARANGSHOPEE'   : rowData.IDINDUKBARANGSHOPEE,
                    'IDBARANGSHOPEE'        : rowData.IDBARANGSHOPEE,
                    'NAMABARANG'            : rowData.NAMABARANG,
                    'HARGAJUALTAMPIL'       : rowData.HARGAJUALTAMPIL,
                    'HARGACORET'            : rowData.HARGACORET,
                    'BATASPEMBELIAN'        : rowData.BATASPEMBELIAN
                };
                
                table.row.add(rows).draw();
                $("#check_"+rowData.ID).prop('checked',true);
                $("#mode_"+rowData.ID).val('UBAH');
                $("#harga_jual_"+rowData.ID).val(rowData.HARGAJUALTAMPIL);
                $("#harga_"+rowData.ID).val(rowData.HARGACORET);
                $("#batas_"+rowData.ID).val(rowData.BATASPEMBELIAN);
                $("#harga_"+rowData.ID).removeAttr('disabled');
                $("#batas_"+rowData.ID).removeAttr('disabled');
    	    }
    	    
            $(".hargajual").number(true,0);
            $(".hargacoret").number(true,0);
            $(".bataspembelian").number(true,0);
            $("#pilihDetailProdukSemua").prop('checked', true);
            
    	    Swal.close();
    	}
    });
}

function simpanShopee(){
    var count = 0 ;
    var arrBarang = [];
    var table = $('#dataGridVarianPromo').DataTable();
    // Get all data from the table
    var allData = table.rows().data(); 
       
    $('input.pilihDetailProduk:checked').each(function () {
        count++;
        var id = $(this).attr('id').replaceAll("check_","");
        allData.each(function (value, index) {
           if(value.ID == id)
           {
                value.HARGACORET     =  $("#harga_"+value.ID).val();
                value.BATASPEMBELIAN =  $("#batas_"+value.ID).val();
                value.MODE           =  $("#mode_"+value.ID).val();
                arrBarang.push(value);
           }
        });
    });
    
    //KHUSUS YANG DIHAPUS
    allData.each(function (value, index) {
       if($("#mode_"+value.ID).val() == 'HAPUS')
       {
            value.HARGACORET     =  $("#harga_"+value.ID).val();
            value.BATASPEMBELIAN =  $("#batas_"+value.ID).val();
            value.MODE           =  $("#mode_"+value.ID).val();
            arrBarang.push(value);
       }
    });
    
    if($("#NAMAPROMOSHOPEE").val() == 0 || $("#TGLMULAISHOPEE").val() == ""  || $("#TGLAKHIRSHOPEE").val() == ""  || count == 0)
    {
        Swal.fire({ 
        	title            : "Terdapat Data Promo yang belum diisi",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
        const tglMulai = moment($('#TGLMULAISHOPEE').val(), 'YYYY-MM-DD HH:mm');
        const tglAkhir = moment($('#TGLAKHIRSHOPEE').val(), 'YYYY-MM-DD HH:mm');

        if (tglMulai.isAfter(tglAkhir)) {
          Swal.fire({ 
            	title            : "Tgl Mulai harus lebih kecil dari Tgl Akhir!",
            	type             : 'warning',
            	showConfirmButton: false,
            	timer            : 1500
           });
        } else {
            loading();
            $.ajax({
            	type    : 'POST',
            	url     : base_url+'Shopee/setPromo/',
            	data    : {
            	   "mode"            : $("#modeShopee").val(),
            	   "idpromosi"       : $("#IDPROMOSISHOPEE").val(),
            	   "namapromosi"     : $("#NAMAPROMOSISHOPEE").val(), 
            	   "tglmulai"        : $("#TGLMULAISHOPEE").val(), 
            	   "tglakhir"        : $("#TGLAKHIRSHOPEE").val(), 
            	   "status"          : $("#statusTransShopee").val(),
            	   "databarang"      : JSON.stringify(arrBarang),
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
                    
                    setTimeout(() => { 
                        if(msg.success)
                        {
                            $("#modal-barang-shopee").modal("hide");
                            $("#dataGridPromoShopee").DataTable().ajax.reload();
                            reset();
                        }
                    }, "1000");
            	}
            }); 
        }
    }
}

function hapusShopee(row){
   Swal.fire({
        title: 'Anda Yakin Akan Mengakhiri Promo ini di Shopee <br>'+row.NAMAPROMOSI+' ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
        	     loading();
        	     $.ajax({
                	type    : 'POST',
                	url     : base_url+'Shopee/removePromo/',
                	data    : {idpromosi: row.IDPROMOSI, statuspromosi : row.STATUS},
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
                                $("#dataGridPromoShopee").DataTable().ajax.reload();
                                reset();
                            }
                        }, "1000");
                	}
        	        
        	     });
            }
    });  
}

function reset(){
    $("#btn_simpan_detail_shopee").show();
    $('#dataGridVarianPromo').DataTable().clear().draw();
    $("#NAMAPROMOSISHOPEE").val("");
    $("#IDPROMOSISHOPEE").val("");
    $("#TGLMULAISHOPEE").val("");
    $("#TGLAKHIRSHOPEE").val("");
    $("#NAMAPROMOSISHOPEE").removeAttr("disabled");
    $("#TGLMULAISHOPEE").removeAttr("disabled");
    $("#TGLAKHIRSHOPEE").removeAttr("disabled");
    $("#persenAll").val("");
    $("#batasAll").val("");
    $("#pilihDetailProdukSemua").prop('checked', false);
    $("#statusTransShopee").val("upcoming");
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
