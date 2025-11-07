
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Master Customer
    <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt;"  onclick="exportTableToExcel()">Excel</button>
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol> -->
</section>

<!-- Main content -->
<section class="content">
  
 <div class="nav-tabs-custom"  style="padding:0px; margin:0px;">
            <ul class="nav nav-tabs" id="tab_all_transaksi">
                <li class="active"><a href="#tab_master" data-toggle="tab"><b>Master</b></a></li>
				<li id="header_shopee"><a href="#tab_shopee" data-toggle="tab"><img alt="Shopee" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png"></a></li>
			    <!--<li id="header_tiktok"><a href="#tab_tiktok" data-toggle="tab"><img alt="Tiktok" style="width:60px;"src="data:image/png;base64,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"></a></li>-->
				<li id="header_lazada"><a href="#tab_lazada" data-toggle="tab"><img alt="Lazada" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Lazada_%282019%29.svg/960px-Lazada_%282019%29.svg.png"></a></li>
			</ul>
            <div class="tab-content" style="padding:0px; margin:0px;">
                    <div class="tab-pane" id="tab_shopee">
                         <div class="row">
                           <div class="col-md-12">
                                <div class="box" style="border:0px; padding:0px; margin:0px;">
                                   <div class="col-md-12" style="margin:10px;">
                                       <br>
                                        <div style="font-size:12pt; font-weight:bold;">*Data tidak termasuk pesanan yang dibatalkan.
                                        </div>
                                        <br>
                                        <table id="dataGridShopeeAPI" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                               <!-- class="table-hover"> -->
                                               <thead>
                                                   <tr>
                                                       <th width="10px">No</th>
                                                       <th width="150px">Nama</th>
                                                       <th width="100px">Telp</th>
                                                       <th >Alamat</th>
                                                       <th width="100px" >Kota</th>
                                                       <th width="80px">Total Pesanan</th>
                                                       <th width="80px">Total Pesanan Selesai</th>
                                                       <th width="80px">Total Pesanan Retur</th>
                                                       <th width="80px">Total Barang</th>
                                                       <th width="80px">Total Bayar</th>
                                                   </tr>
                                               </thead>
                                         </table>
                                        <br>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_tiktok">
                    </div>
                    <div class="tab-pane" id="tab_lazada">
                        <div class="row">
                           <div class="col-md-12">
                                <div class="box" style="border:0px; padding:0px; margin:0px;">
                                   <div class="col-md-12" style="margin:10px;">
                                       <br>
                                        <div style="font-size:12pt; font-weight:bold;">*Data tidak termasuk pesanan yang dibatalkan.
                                        </div>
                                        <br>
                                        <table id="dataGridLazadaAPI" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                               <!-- class="table-hover"> -->
                                               <thead>
                                                   <tr>
                                                       <th width="10px">No</th>
                                                       <th width="150px">Nama</th>
                                                       <th width="100px">Telp</th>
                                                       <th >Alamat</th>
                                                       <th width="100px" >Kota</th>
                                                       <th width="80px">Total Pesanan</th>
                                                       <th width="80px">Total Pesanan Selesai</th>
                                                       <th width="80px">Total Pesanan Retur</th>
                                                       <th width="80px">Total Barang</th>
                                                       <th width="80px">Total Bayar</th>
                                                   </tr>
                                               </thead>
                                         </table>
                                        <br>
                                   </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane active" id="tab_master">
                        <div class=" ">    
                            <!-- Main row -->
                            <div class="row">
                                <div class="col-md-12">
                                <div class="box" style="border:0px; padding:0px; margin:0px;">
                                <div class="box-header form-inline">
                                <button class="btn btn-success" onclick="javascript:tambah()">Tambah</button>
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
                                                <tr>
                                                    <th width="35px"></th>
                                                    <th>ID</th>
                                                    <th>Kode</th>
                                                    <th>Nama</th>
                                                    <th>Nama Faktur Pajak</th>
                                                    <th width="25px">Member</th>          
                                                    <th width="25px">Konsinyasi</th>          
                                                    <th>Alamat</th>
                                                    <th>Kota</th>
                                                    <th>Provinsi</th>
                                                    <th>Negara</th>
                                                    <th>NPWP</th>
                                                    <th>Telp</th>
                                                    <th>Email</th>
                                                    <th>Bank</th>
                                                    <th>Rekening</th>
                                                    <th>Catatan</th>
                                                    <th width="40px">User Input</th>
                                                    <th width="40px">Tgl. Input</th>
                                                    <th width="25px">Aktif</th>                                
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div id="tableExcel" style="display:none;" ></div>
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="tab_form">
                                        <div class="box-body">
                    					 <div class="col-md-12">
                    						<!-- form start -->
                    						<form role="form" id="form_input">
                                                <input type="hidden" id="mode" name="mode">
                                                <input type="hidden" id="IDCUSTOMER" name="IDCUSTOMER">
                                                <div class="box-body">
                                                    <div class="form-group col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label for="KODECUSTOMER">Kode Customer &nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="MEMBER" name="MEMBER" value="1">&nbsp; Member (Komisi / Potongan)</label><label>&nbsp;&nbsp;<input type="checkbox" class="flat-blue" id="KONSINYASI" name="KONSINYASI" value="1">&nbsp; Konsinyasi </label> <label>&nbsp;&nbsp;<input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Aktif </label>
                                                                <input type="text" class="form-control" id="KODECUSTOMER" name="KODECUSTOMER" placeholder="AUTO" readonly>
                                                                <br>
                                                                <label>Nama Customer <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                <input type="text" class="form-control" id="NAMACUSTOMER" name="NAMACUSTOMER" placeholder="Nama Customer">
                                                                <br>
                                                                <label>Nama Faktur Pajak <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                <input type="text" class="form-control" id="NAMAFAKTURPAJAK" name="NAMAFAKTURPAJAK" placeholder="Nama Faktur Pajak">
                            									<div id="diskon_member">
                            									<br>
                            									<label>Potongan Member(%) <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                <input type="number" class="form-control" id="DISKONMEMBER" name="DISKONMEMBER" placeholder="Potongan Member(%)" min="0" max="100" onkeydown="return ( event.ctrlKey || event.altKey 
                                                                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                                                                    || (95<event.keyCode && event.keyCode<106)
                                                                    || (event.keyCode==8) || (event.keyCode==9) 
                                                                    || (event.keyCode>34 && event.keyCode<40) 
                                                                    || (event.keyCode==46) )">
                                                                </div>
                            									<br>
                                                                <label>Alamat</label>
                                                                <input type="text" class="form-control" id="ALAMAT" name="ALAMAT" placeholder="Alamat">
                                                             </div>
                                                         </div>
                                                         <br>
                                                         <div class="row">
                                                             <div class="col-md-4">
                                                                <label>Kota</label>
                                                                <input type="text" class="form-control" id="KOTA" name="KOTA" placeholder="Kota">
                                                             </div>
                                                             <div class="col-md-4">
                                                                <label>Provinsi</label>
                                                                <input type="text" class="form-control" id="PROVINSI" name="PROVINSI" placeholder="Provinsi">
                                                             </div>
                                                             <div class="col-md-4">
                                                                <label>Negara</label>
                                                                <input type="text" class="form-control" id="NEGARA" name="NEGARA" placeholder="Negara">
                                                             </div>
                                                         </div>
                                                         <br>
                                                         <div class="row">
                                                            <div class="col-md-6">
                                                                <label>NPWP</label>
                                                                <input type="text" class="form-control" id="NPWP" name="NPWP" placeholder="NPWP">
                                                             </div>
                                                         </div>
                                                         <br>
                                                         <div class="row">
                                                             <div class="col-md-6">
                                                                <label>Telp</label>
                                                                <input type="text" class="form-control" id="TELP" name="TELP" placeholder="Telepon">
                                                             </div>
                                                             <div class="col-md-6">
                                                                <label>Email</label>
                                                                <input type="text" class="form-control" id="EMAIL" name="EMAIL" placeholder="Email">
                                                             </div>
                                                         </div>
                                                         <br>
                                                         <div class="row">
                                                             <div class="col-md-6">
                                                                <label>Bank</label>
                                                                <input type="text" class="form-control" id="NAMABANK" name="NAMABANK" placeholder="Bank">
                                                             </div>
                                                             <div class="col-md-6">
                                                                <label>Rekening</label>
                                                                <input type="text" class="form-control" id="NOREKENING" name="NOREKENING" placeholder="Rekening">
                                                             </div>
                                                         </div>
                                                         <br>
                                                            <label>Catatan</label>
                                                            <textarea class="form-control" rows="3" id="CATATAN" name="CATATAN" placeholder="Catatan....."></textarea>
                                                        </div>
                                                    </div>
                                                <!-- /.box-body -->
                    
                                                <div class="box-footer">&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <button type="button" id="btn_simpan" class="btn btn-primary" onclick="javascript:simpan()">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
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
                </div>
            </div>
        </div>
    </div>
  <!-- /.row (main row) -->

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script>
var indexRow;

if('<?=$_SESSION[NAMAPROGRAM]['USERNAME'] == 'USERTES'?>')
{
    $("#header_shopee").hide();
    $("#header_tiktok").hide();
    $("#header_lazada").hide();
}

if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'NO'?>')
{
    $("#header_shopee").hide();
}
if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'NO'?>')
{
    $("#header_lazada").hide();
}


$(document).ready(function() {
    $("#diskon_member").hide();
    $("#mode").val('tambah');
    $("#STATUS").prop('checked',true).iCheck('update');
    $("#MEMBER").prop('checked',false).iCheck('update').on('ifChanged', function(event) {
        $("#DISKONMEMBER").val(0);
        if ($(this).prop('checked')) {
            $("#diskon_member").show();
        } else {
            $("#diskon_member").hide();
        }
    });
	$("#KONSINYASI").prop('checked',false).iCheck('update');
    

    $('#dataGrid').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		
		ajax		  : {
			url    : base_url+'Master/Data/Customer/dataGrid',
			dataSrc: "rows",
			dataFilter: function (data) {
                // Refresh the new table whenever DataTable reloads
                var allData = JSON.parse(data).rows; // Get all rows' data

                // Create the HTML structure for the new table
                var newTable = $('<table id="newTable" class="table table-bordered">');
                var thead = $('<thead>').append('<tr><th>Kode Customer</th><th>Nama Customer</th><th>Member</th><th>Konsinyasi</th><th>Alamat</th><th>Kota</th><th>Provinsi</th><th>Negara</th><th>Telp</th><th>Email</th><th>Nama Bank</th><th>No Rekening</th><th>Catatan</th><th>User Buat</th><th>Tgl Entry</th><th>Status</th></tr>');
                var tbody = $('<tbody>');
                 // Loop through the DataTable data and create rows for the new table
         
                allData.forEach(function (row, index) {
                    var tr = $('<tr>');
            
                    tr.append('<td>' + row.KODECUSTOMER + '</td>');
                    tr.append('<td>' + row.NAMACUSTOMER + '</td>');
                    tr.append('<td>' + row.NAMAFAKTURPAJAK + '</td>');
                    tr.append('<td class="text-center">' + (row.MEMBER == 1 ? 'YA' : 'TIDAK') + '</td>');
                    tr.append('<td class="text-center">' + (row.KONSINYASI == 1 ? 'YA' : 'TIDAK') + '</td>');
                    tr.append('<td>' + (row.ALAMAT== null?"":row.ALAMAT) + '</td>');
                    tr.append('<td>' + (row.KOTA== null?"":row.KOTA) + '</td>');
                    tr.append('<td>' + (row.PROVINSI== null?"": row.PROVINSI) + '</td>');
                    tr.append('<td>' + (row.NEGARA== null?"":row.NEGARA) + '</td>');
                    tr.append('<td>' + (row.TELP== null?"":row.TELP) + '</td>');
                    tr.append('<td>' + (row.NPWP== null?"":row.NPWP) + '</td>');
                    tr.append('<td>' + (row.EMAIL== null?"":row.EMAIL) + '</td>');
                    tr.append('<td>' + (row.NAMABANK== null?"":row.NAMABANK) + '</td>');
                    tr.append('<td>' + (row.NOREKENING== null?"":row.NOREKENING) + '</td>');
                    tr.append('<td>' + (row.CATATAN== null?"":row.CATATAN) + '</td>');
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
                
                return data;
            }
		},
        columns:[
            {data: ''},
            {data: 'IDCUSTOMER', visible:false},
            {data: 'KODECUSTOMER'},
            {data: 'NAMACUSTOMER'},
            {data: 'NAMAFAKTURPAJAK'},
            {data: 'MEMBER', className:"text-center"},    
            {data: 'KONSINYASI', className:"text-center"},    
            {data: 'ALAMAT'},
            {data: 'KOTA'},
            {data: 'PROVINSI'},
            {data: 'NEGARA'},
            {data: 'NPWP'},
            {data: 'TELP'},
            {data: 'EMAIL'},
            {data: 'NAMABANK'},
            {data: 'NOREKENING'},
            {data: 'CATATAN'},
            {data: 'USERBUAT'},
            {data: 'TGLENTRY', className:"text-center"},
            {data: 'STATUS', className:"text-center"},            
        ],
		columnDefs: [ 
			{
                "targets": 0,
                "data": null,
                "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
			},
			{
                "targets": 5,
                "render" :function (data) 
                            {
                                if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                                else return '<input type="checkbox" class="flat-blue" disabled></input>';
                            },	
			},
			{
                "targets": 6,
                "render" :function (data) 
                            {
                                if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                                else return '<input type="checkbox" class="flat-blue" disabled></input>';
                            },	
			},
			{
                "targets": -1,
                "render" :function (data) 
                            {
                                if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                                else return '<input type="checkbox" class="flat-blue" disabled></input>';
                            },	
			},
		]
    });

    //DAPATKAN INDEX
    $('#dataGrid tbody').on( 'click', 'button', function () {
        var table = $('#dataGrid').DataTable();
    	var row = table.row( $(this).parents('tr') ).data();
    	var mode = $(this).attr("id");
    	
    	if(mode == "btn_ubah"){ ubah(row); }
    	else if(mode == "btn_hapus"){ hapus(row); }
    
    } );

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
    
    //SHOPEE
	// Step 1: Initialize the table and assign to a variable
    var tableShopee = $('#dataGridShopeeAPI').DataTable({
        paging      : true,
        lengthChange: true,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : false,
        scrollX     : true,
        ajax        : {
            url    : base_url + 'Shopee/getCustomer',
            dataSrc: "rows",
        },
        columns: [
            { data: 'NO', className: "text-center" },
            { data: 'NAMA' },
            { data: 'TELP' , className:"text-center" },
            { data: 'ALAMAT' },
            { data: 'KOTA' , className:"text-center" },
            { data: 'TOTALPESANAN' , render:format_number , className:"text-center" },
            { data: 'TOTALPESANANSUKSES' , render:format_number , className:"text-center" },
            { data: 'TOTALPESANANRETUR' , render:format_number , className:"text-center" },
            { data: 'TOTALBARANG' ,  render:format_number , className:"text-center"},
            { data: 'TOTALBAYAR',  render:format_number, className:"text-right" },
        ],
    });
	
// 	//TIKTOK
// 	$('#dataGridTiktokAPI').DataTable({
//         'paging'      : true,
//         'lengthChange': true,
//         'searching'   : true,
//         'ordering'    : true,
//         'info'        : true,
//         'autoWidth'   : false,
// 		"scrollX"	  : true,
// 		ajax		  : {
// 			url    : base_url+'Tiktok/getStokLokasi',
// 			dataSrc: "rows",
// 		},
//         columns:[
// 			{data: 'NO'},
//             {data: 'ADDRESSAPI', visible:false},
//             {data: 'ADDRESS', visible:false},
//         ],
// 		columnDefs: [ 
// 			{
//                 "targets": 1,
//                 "render" :function (data) 
//                  {
//                      if (data == 0) return '<button class="btn" id="btn_ubah">Pilih Lokasi Master</button>';
//                      else return data+' <button class="btn" id="btn_ubah">Ubah</button> <button class="btn" id="btn_hapus">Hapus</button>';
//                  },		
// 			}
			
// 		],
//     });  

//     //DAPATKAN INDEX
// 	$('#dataGridTiktokAPI tbody').on( 'click', 'button', function () {
	    
// 	    var table = $('#dataGridTiktokAPI').DataTable();
// 		var row = table.row( $(this).parents('tr') ).data();
// 		var mode = $(this).attr("id");
		
// 		if(mode == "btn_ubah"){ ubahTiktok(row); }
// 		else if(mode == "btn_hapus"){ hapusTiktok(row); }
// 	});
	
    //LAZADA
	// Step 1: Initialize the table and assign to a variable
    var tableLazada = $('#dataGridLazadaAPI').DataTable({
        paging      : true,
        lengthChange: true,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : false,
        scrollX     : true,
        ajax        : {
            url    : base_url + 'Lazada/getCustomer',
            dataSrc: "rows",
        },
        columns: [
            { data: 'NO', className: "text-center" },
            { data: 'NAMA' },
            { data: 'TELP' , className:"text-center" },
            { data: 'ALAMAT' },
            { data: 'KOTA' , className:"text-center" },
            { data: 'TOTALPESANAN' , render:format_number , className:"text-center" },
            { data: 'TOTALPESANANSUKSES' , render:format_number , className:"text-center" },
            { data: 'TOTALPESANANRETUR' , render:format_number , className:"text-center" },
            { data: 'TOTALBARANG' ,  render:format_number , className:"text-center"},
            { data: 'TOTALBAYAR',  render:format_number, className:"text-right" },
        ],
    });
});

function exportTableToExcel() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcel'), {sheet:"Sheet 1"});
  const ws = wb.Sheets[wb.SheetNames[0]];
                    
  ws['!cols'] = [
    { wpx: 70 }, // Column A width in pixels
    { wpx: 200 }, // Column B width in pixels
    { wpx: 200 }, // Column B width in pixels
    { wpx: 60 },  // Column C width in pixels
    { wpx: 60 },  // Column C width in pixels
    { wpx: 300 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 120 },  // Column C width in pixels
    { wpx: 60 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 100 }, // Column A width in pixels
    { wpx: 70 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'CUSTOMER_'+dateNowFormatExcel()+'.xlsx');
}
    
$("#DISKONMEMBER").bind('keyup mouseup', function () {
    if($(this).val() < 0)
   {
       $(this).val(0);
   }
    if($(this).val() > 100)
   {
       $(this).val(100);
   }
});

function tambah(){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
				$("#mode").val('tambah');

				//pindah tab & ganti judul tab
				$('.nav-tabs a[href="#tab_form"]').tab('show');
				$('.nav-tabs a[href="#tab_form"]').html('Tambah');
				$("#NAMACUSTOMER").removeAttr('readonly');
				$("#NAMAFAKTURPAJAK").removeAttr('readonly');
				//clear form input
				$("#STATUS").prop('checked',true).iCheck('update');
				$("#MEMBER").prop('checked',false).iCheck('update');
				$("#KONSINYASI").prop('checked',false).iCheck('update');
				$("#DISKONMEMBER").val(0);
				$("#IDCUSTOMER").val("");
				$("#KODECUSTOMER").val("");
				$("#NAMACUSTOMER").val("");
				$("#NAMAFAKTURPAJAK").val("");
				$("#NPWP").val("");
				$("#ALAMAT").val("");
				$("#KOTA").val("");
				$("#PROVINSI").val("");
				$("#NEGARA").val("");
				$("#TELP").val("");
				$("#EMAIL").val("");
				$("#WEBSITE").val("");
				$("#NAMABANK").val("");
				$("#NOREKENING").val("");
				$("#CATATAN").val("");
				
                if ($("#MEMBER").prop('checked')) {
                    $("#diskon_member").show();
                } else {
                    $("#diskon_member").hide();
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

function ubah(row){
	
		get_akses_user('<?=$_GET['kode']?>', function(data){
			if (data.UBAH==1) {
				$("#mode").val('ubah');
				
				//pindah tab & ganti judul tab
				$('.nav-tabs a[href="#tab_form"]').tab('show');
				$('.nav-tabs a[href="#tab_form"]').html('Ubah');

				//load row data to form
				if(row.STATUS == 0) $("#STATUS").prop('checked',false).iCheck('update');
				else if(row.STATUS == 1) $("#STATUS").prop('checked',true).iCheck('update');
				
				
				if(row.MEMBER == 0) $("#MEMBER").prop('checked',false).iCheck('update');
				else if(row.MEMBER == 1) $("#MEMBER").prop('checked',true).iCheck('update');
				
				if(row.KONSINYASI == 0) $("#KONSINYASI").prop('checked',false).iCheck('update');
				else if(row.KONSINYASI == 1) $("#KONSINYASI").prop('checked',true).iCheck('update');
				
				$("#IDCUSTOMER").val(row.IDCUSTOMER);
				$("#KODECUSTOMER").val(row.KODECUSTOMER);
				$("#NAMACUSTOMER").val(row.NAMACUSTOMER);
				$("#NAMAFAKTURPAJAK").val(row.NAMAFAKTURPAJAK);
				$("#NPWP").val(row.NPWP);
				$("#ALAMAT").val(row.ALAMAT);
				$("#KOTA").val(row.KOTA);
				$("#PROVINSI").val(row.PROVINSI);
				$("#NEGARA").val(row.NEGARA);
				$("#TELP").val(row.TELP);
				$("#EMAIL").val(row.EMAIL);
				$("#NAMABANK").val(row.NAMABANK);
				$("#NOREKENING").val(row.NOREKENING);
				$("#DISKONMEMBER").val(row.DISKONMEMBER);
				$("#CATATAN").val(row.CATATAN);
				
                if ($("#MEMBER").prop('checked')) {
                    $("#diskon_member").show();
                } else {
                    $("#diskon_member").hide();
                }
				
				if(row.KODECUSTOMER == "XUMUM" || row.KODECUSTOMER == "XGOJEK" || row.KODECUSTOMER == "XGRAB" || row.KODECUSTOMER == "XSHOPEE" || row.KODECUSTOMER == "XTIKTOK" || row.KODECUSTOMER == "XTOKPED")
				{
					$("#NAMACUSTOMER").attr('readonly','readonly');
					$("#NAMAFAKTURPAJAK").attr('readonly','readonly');
				}
				else
				{
					$("#NAMACUSTOMER").removeAttr('readonly');
					$("#NAMAFAKTURPAJAK").removeAttr('readonly');
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

function simpan() {
	// var isValid = $('#form_input').form('validate');
	if (1) {
		mode = $('[name=mode]').val();
        
        $.ajax({
            type      : 'POST',
            url       : base_url+'Master/Data/Customer/simpan',
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
                    tambah();
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

function hapus(row){
	if(row.KODECUSTOMER == "XUMUM" || row.KODECUSTOMER == "XSHOPEE" || row.KODECUSTOMER == "XGOJEK" || row.KODECUSTOMER == "XGRAB" || row.KODECUSTOMER == "XTIKTOK" || row.KODECUSTOMER == "XTOKPED")
	{
		Swal.fire({
			title            : 'Khusus Umum / Gojek / Grab / Shopee / Tiktok / Tokped Tidak Dapat Dihapus',
			type             : 'warning',
			showConfirmButton: false,
			timer            : 1500
		});
	}
	else
	{
		get_akses_user('<?=$_GET['kode']?>', function(data){
			if (data.HAPUS==1) {
			    
			  if (row) {
    		     Swal.fire({
                		title: 'Anda Yakin Akan Menghapus Customer '+row.NAMACUSTOMER+' ?',
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
                						url     : base_url+"Master/Data/Customer/hapus",
                						data    : "id="+row.IDCUSTOMER + "&kode="+row.KODECUSTOMER,
                						cache   : false,
                						success : function(msg){
                							if (msg.success) {
                								Swal.fire({
                									title            : 'Customer dengan nama '+row.NAMACUSTOMER+' telah dihapus',
                									type             : 'success',
                									showConfirmButton: false,
                									timer            : 1500
                								});
                								$("#dataGrid").DataTable().ajax.reload();
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
</script>
