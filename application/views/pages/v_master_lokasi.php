<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Master Lokasi
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
				<li id="header_shopee"><a href="#tab_shopee" data-toggle="tab" onclick="javascript:changeTabMarketplace(1)"><img alt="Shopee" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png"></a></li>
			    <li id="header_tiktok"><a href="#tab_tiktok" data-toggle="tab"><img alt="Tiktok" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/en/thumb/a/a9/TikTok_logo.svg/250px-TikTok_logo.svg.png"></a></li>
				<li id="header_lazada"><a href="#tab_lazada" data-toggle="tab"><img alt="Lazada" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Lazada_%282019%29.svg/960px-Lazada_%282019%29.svg.png"></a></li>
			</ul>
            <div class="tab-content" style="padding:0px; margin:0px;">
                    <div class="tab-pane" id="tab_shopee">
                         <div class="row">
                           <div class="col-md-12">
                                <div class="box" style="border:0px; padding:0px; margin:0px;">
                                   <div class="col-md-7" style="margin:10px;">
                                       <br>
                                       <div style="font-size:12pt; font-weight:bold;">*Untuk menambahkan Lokasi yang ada di marketplace, hanya bisa dilakukan pada aplikasi marketplace. 
                                       <br>Menu ini hanya menghubungkan stok dari lokasi yang ada pada aplikasi marketplace, dan master lokasi.
                                       </div>
                                       <br>
                                        <table id="dataGridShopeeAPI" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                               <!-- class="table-hover"> -->
                                               <thead>
                                                   <tr>
                                                       <th width="10px">No</th>
                                                       <th >Address</th>
                                                       <th width="180px">Lokasi di Master</th>
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
                                   <div class="col-md-7" style="margin:10px;">
                                       <br>
                                       <div style="font-size:12pt; font-weight:bold;">*Untuk menambahkan Lokasi yang ada di marketplace, hanya bisa dilakukan pada aplikasi marketplace. 
                                       <br>Menu ini hanya menghubungkan stok dari lokasi yang ada pada aplikasi marketplace, dan master lokasi.
                                       </div>
                                       <br>
                                        <table id="dataGridLazadaAPI" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                               <!-- class="table-hover"> -->
                                               <thead>
                                                   <tr>
                                                       <th width="10px">No</th>
                                                       <th >Address</th>
                                                       <th width="10px">Pickup & Return</th>
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
                                                            <th width="30px">Kode</th>
                                                            <th>Nama</th>
                                                            <th>Group</th>
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
                                                    <input type="hidden" id="IDLOKASI" name="IDLOKASI">
                                                    <div class="box-body">
                                                        <div class="form-group col-md-5">
                                                            <label for="KODELOKASI">Kode Lokasi &nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Aktif </label>
                                                            <input type="text" class="form-control" id="KODELOKASI" name="KODELOKASI" placeholder="AUTO" readonly>
                                                            <br>
                                                            <label>Nama Lokasi <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                            <input type="text" class="form-control" id="NAMALOKASI" name="NAMALOKASI" placeholder="Nama Lokasi">
                                                            <br>
                                                            <label>Catatan</label>
                                                            <textarea class="form-control" rows="3" id="CATATAN" name="CATATAN" placeholder="Catatan....."></textarea>
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <label for="KODELOKASI">Group Lokasi</label>
                                                            <br>
                                                             <label>&nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="ALL" name="ALL" value="1">&nbsp;&nbsp; All </label>
                                                            <br>
                                                             <label>&nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="MARKETPLACE" name="MARKETPLACE" value="1">&nbsp;&nbsp; Marketplace </label>
                                                            <br>
                                                             <label>&nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="KONSINYASI" name="KONSINYASI" value="1">&nbsp;&nbsp; Konsinyasi </label>
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
                                    <!-- /.tab-content -->
                                </div>
                                <!-- nav-tabs-custom -->
                                </div>
                            </div>
                            <!-- /.col -->
                            </div>
    <!-- /.row (main row) -->
</section>

<div class="modal fade" id="modal-shopee">
	<div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Ubah Label Lokasi</h4>
                <button id='btn_ubah_label_lokasi_shopee'  style="float:right;" class='btn btn-success' onclick="ubahLabelLokasiShopee()">Ubah</button>
            </div>
            <div class="modal-body"  style="height:200px;">	
                <div class="form-group col-md-12">
                    <label for="LOKASI"  style="font-size:12pt;">Lokasi</label>
                    <br>
                    <div id="LOKASISHOPEE" style="font-size:12pt;"></div>
			        <br>
                     <label>&nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="DEFAULTSHOPEE" name="DEFAULTSHOPEE" value="1">&nbsp;&nbsp; DEFAULT_ADDRESS </label>
                     &nbsp;&nbsp;
                     <label>&nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="PICKUPSHOPEE" name="PICKUPSHOPEE" value="1">&nbsp;&nbsp; PICKUP_ADDRESS </label>
                     &nbsp;&nbsp;
                     <label>&nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="RETURNSHOPEE" name="RETURNSHOPEE" value="1">&nbsp;&nbsp; RETURN_ADDRESS </label>
                </div>
			</div>
			<input type="hidden" id="ADDRESSIDSHOPEE">
		</div>
	</div>
</div>
										 
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<!-- /.content -->

<script>

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
if('<?=$_SESSION[NAMAPROGRAM]['TIKTOK_ACTIVE'] == 'NO'?>')
{
    $("#header_tiktok").hide();
}
if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'NO'?>')
{
    $("#header_lazada").hide();
}


$(document).ready(function() {
	var counter = 0;
    $("#mode").val('tambah');
    $("#STATUS").prop('checked',true).iCheck('update');
		
    $('#dataGrid').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/Lokasi/dataGrid',
			dataSrc: "rows",
			dataFilter: function (data) {
                // Refresh the new table whenever DataTable reloads
                var allData = JSON.parse(data).rows; // Get all rows' data

                // Create the HTML structure for the new table
                var newTable = $('<table id="newTable" class="table table-bordered">');
                var thead = $('<thead>').append('<tr><th>Kode Lokasi</th><th>Nama Lokasi</th><th>Catatan</th><th>User Buat</th><th>Tgl Entry</th><th>Status</th></tr>');
                var tbody = $('<tbody>');
                 // Loop through the DataTable data and create rows for the new table
         
                allData.forEach(function (row, index) {
                    var tr = $('<tr>');
                    var arrLokasi = row.GROUPLOKASI.split(",");
                    tr.append('<td>' + row.KODELOKASI + '</td>');
                    tr.append('<td>' + row.NAMALOKASI + '</td>');
                    tr.append('<td>' + row.GROUPLOKASI + '</td>');
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
            {data: 'IDLOKASI', visible:false},
            {data: 'KODELOKASI', className:"text-center"},
            {data: 'NAMALOKASI'},
            {data: 'GROUPLOKASI'},
            {data: 'CATATAN'},
            {data: 'USERBUAT'},
            {data: 'TGLENTRY', className:"text-center"},
            {data: 'STATUS', className:"text-center" },
        ],
		columnDefs: [ 
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
			},
			
		],
    });  

    //DAPATKAN INDEX
	$('#dataGrid tbody').on( 'click', 'button', function () {
	    var table = $('#dataGrid').DataTable();
		var row = table.row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		if(mode == "btn_ubah"){ ubah(row); }
		else if(mode == "btn_hapus"){ hapus(row); }
	});
	
	

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
            url    : base_url + 'Shopee/getStokLokasi',
            dataSrc: "rows",
        },
        columns: [
            { data: 'NO', className: "text-center" },
            { data: 'ADDRESSAPI' },
            { data: 'ADDRESS' },
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, row, meta) {
                    return `
                        <input type="hidden" id="IDADDRESSAPI_${row.NO}" value="`+row.IDADDRESSAPI+`">
                        <select class="form-control" id="LOKASISHOPEE_${row.NO}" placeholder="Lokasi..." style="width:100%;">
                            <option value="0">- Pilih Lokasi -</option>
                            <?=comboGridMarketplace("model_master_lokasi")?>
                        </select>
                    `;
                },
            }
        ],
    });
    
    // Step 2: Wait until the AJAX request finishes
    $('#dataGridShopeeAPI').on('xhr.dt', function () {
        // Delay execution to ensure rendering completes
        setTimeout(function () {
            var allDataShopee = tableShopee.rows().data().toArray();
            for (var x = 0; x < allDataShopee.length; x++) {
                let no = allDataShopee[x].NO;
                
                $(`#LOKASISHOPEE_${no}`).val(allDataShopee[x].ADDRESS);
    
                // Delegate safely only once per element (avoid duplicate bindings)
                $(document).off('change', `#LOKASISHOPEE_${no}`).on('change', `#LOKASISHOPEE_${no}`, function () {
                    $.ajax({
                		type    : 'POST',
                		dataType: 'json',
                		url     : base_url+"Shopee/setStokLokasi",
                		data    : "id="+$(this).val() + "&idAPI="+$(`#IDADDRESSAPI_${no}`).val(),
                		cache   : false,
                		success : function(msg){
                			if (msg.success) {
                				Swal.fire({
                					title            : 'Lokasi Berhasil Dihubungkan',
                					type             : 'success',
                					showConfirmButton: false,
                					timer            : 1500
                				});
                				$("#dataGridShopeeAPI").DataTable().ajax.reload();
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
                });
            }
        }, 100); // Small delay to ensure rendering is complete
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
            url    : base_url + 'Lazada/getStokLokasi',
            dataSrc: "rows",
        },
        columns: [
            { data: 'NO', className: "text-center" },
            { data: 'ADDRESSAPI' },
            { data: 'IDLOKASILAZADA' , className: "text-center" },
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, row, meta) {

                    if(row.LABELDEFAULT)
                    { 
                        return `
                            <input type="checkbox" id="IDADDRESSAPI_${row.NO}" value="`+row.IDADDRESSAPI+`" checked  onclick="setLokasiLazada(`+row.IDADDRESSAPI+`,`+row.IDADDRESSAPI+`,true)">
                        `;
                    }
                    else
                    {
                         return `
                            <input type="checkbox" id="IDADDRESSAPI_${row.NO}" value="`+row.IDADDRESSAPI+`" onclick="setLokasiLazada(`+row.IDADDRESSAPI+`,`+row.IDADDRESSAPI+`,false)">
                        `;
                    }
                },
            }
        ],
    });
});

function exportTableToExcel() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcel'), {sheet:"Sheet 1"});
  // Access the worksheet (first sheet)
  const ws = wb.Sheets[wb.SheetNames[0]];

  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 70 }, // Column A width in pixels
    { wpx: 200 }, // Column B width in pixels
    { wpx: 150 }, // Column B width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 100 }, // Column A width in pixels
    { wpx: 70 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];

  // Trigger download
  XLSX.writeFile(wb, 'LOKASI_'+dateNowFormatExcel()+'.xlsx');
}

function tambah(){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
			$("#mode").val('tambah');

			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Tambah');

			//clear form input
			$("#STATUS").prop('checked',true).iCheck('update');
        	$("#ALL").prop('checked',false).iCheck('update');
        	$("#MARKETPLACE").prop('checked',false).iCheck('update');
        	$("#KONSINYASI").prop('checked',false).iCheck('update');
			$("#IDLOKASI").val("");
			$("#KODELOKASI").val("");
			$("#NAMALOKASI").val("");

			$("#CATATAN").val("");
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
				$("#IDLOKASI").val(row.IDLOKASI);
				$("#KODELOKASI").val(row.KODELOKASI);
				$("#NAMALOKASI").val(row.NAMALOKASI);
				$("#CATATAN").val(row.CATATAN);
				
            	$("#ALL").prop('checked',false).iCheck('update');
            	$("#MARKETPLACE").prop('checked',false).iCheck('update');
            	$("#KONSINYASI").prop('checked',false).iCheck('update');
				
				if(row.GROUPLOKASI != "")
				{
				    if(row.GROUPLOKASI.includes("ALL"))
				    {
				        $("#ALL").prop('checked',true).iCheck('update');
				    }
				    if(row.GROUPLOKASI.includes("MARKETPLACE"))
				    {
				        $("#MARKETPLACE").prop('checked',true).iCheck('update');
				    }
				    if(row.GROUPLOKASI.includes("KONSINYASI"))
				    {
				        $("#KONSINYASI").prop('checked',true).iCheck('update');
				    }
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
            url       : base_url+'Master/Data/Lokasi/simpan',
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
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.HAPUS==1) {
		    
            if (row) {
    		       Swal.fire({
                		title: 'Anda Yakin Akan Menghapus Lokasi '+row.NAMALOKASI+' ?',
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
                						url     : base_url+"Master/Data/Lokasi/hapus",
                						data    : "id="+row.IDLOKASI + "&kode="+row.KODELOKASI,
                						cache   : false,
                						success : function(msg){
                							if (msg.success) {
                								Swal.fire({
                									title            : 'Lokasi dengan nama '+row.NAMALOKASI+' telah dihapus',
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

function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
         .removeAttr('checked').removeAttr('selected');
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

function changeLabelShopee(index){
    var allDataShopee = $('#dataGridShopeeAPI').DataTable().rows().data().toArray();
    $("#modal-shopee").modal("show");
    $("#LOKASISHOPEE").html(allDataShopee[index].LABELADDRESS+"<br>"+allDataShopee[index].ADDRESSAPIRAW);
    $("#ADDRESSIDSHOPEE").val(allDataShopee[index].IDADDRESSAPI);
    
    $("#DEFAULTSHOPEE").prop('checked',allDataShopee[index].LABELDEFAULT).iCheck('update');
    $("#PICKUPSHOPEE").prop('checked',allDataShopee[index].LABELPICKUP).iCheck('update');
    $("#RETURNSHOPEE").prop('checked',allDataShopee[index].LABELRETURN).iCheck('update');
}

function ubahLabelLokasiShopee(){
    $.ajax({
    	type    : 'POST',
    	dataType: 'json',
    	url     : base_url+"Shopee/setLabelLokasi",
    	data    : "id="+ $("#ADDRESSIDSHOPEE").val() + "&default="+$("#DEFAULTSHOPEE").prop('checked')+ "&pickup="+$("#PICKUPSHOPEE").prop('checked')+ "&return="+$("#RETURNSHOPEE").prop('checked'),
    	cache   : false,
    	success : function(msg){
    		if (msg.success) {
    			$("#modal-shopee").modal("hide");
    			Swal.fire({
    				title            : 'Label Berhasil Diubah',
    				type             : 'success',
    				showConfirmButton: false,
    				timer            : 1500
    			});
    			$("#dataGridShopeeAPI").DataTable().ajax.reload();
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

function setLokasiLazada(no,id,checked){
    $.ajax({
    	type    : 'POST',
    	dataType: 'json',
    	url     : base_url+"Lazada/setStokLokasi",
    	data    : "id="+ id+"&value="+ !checked,
    	cache   : false,
    	success : function(msg){
    		if (msg.success) {
            	Swal.fire({
            		title            : 'Lokasi Berhasil Dihubungkan',
            		type             : 'success',
            		showConfirmButton: false,
            		timer            : 1500
            	});
            	$("#dataGridLazadaAPI").DataTable().ajax.reload();
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

</script>