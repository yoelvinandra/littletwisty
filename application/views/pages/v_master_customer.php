
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
  
  <!-- Main row -->
  <div class="row">
      <div class="col-md-12">
        <div class="box">
        <div class="box-header">
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
                                <th width="25px">Member</th>          
                                <th width="25px">Konsinyasi</th>          
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>Provinsi</th>
                                <th>Negara</th>
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
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
        </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row (main row) -->

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script>
var indexRow;
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
                    tr.append('<td class="text-center">' + (row.MEMBER == 1 ? 'YA' : 'TIDAK') + '</td>');
                    tr.append('<td class="text-center">' + (row.KONSINYASI == 1 ? 'YA' : 'TIDAK') + '</td>');
                    tr.append('<td>' + (row.ALAMAT== null?"":row.ALAMAT) + '</td>');
                    tr.append('<td>' + (row.KOTA== null?"":row.KOTA) + '</td>');
                    tr.append('<td>' + (row.PROVINSI== null?"": row.PROVINSI) + '</td>');
                    tr.append('<td>' + (row.NEGARA== null?"":row.NEGARA) + '</td>');
                    tr.append('<td>' + (row.TELP== null?"":row.TELP) + '</td>');
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
            {data: 'MEMBER', className:"text-center"},    
            {data: 'KONSINYASI', className:"text-center"},    
            {data: 'ALAMAT'},
            {data: 'KOTA'},
            {data: 'PROVINSI'},
            {data: 'NEGARA'},
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
                "targets": 4,
                "render" :function (data) 
                            {
                                if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                                else return '<input type="checkbox" class="flat-blue" disabled></input>';
                            },	
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
var table = $('#dataGrid').DataTable();
$('#dataGrid tbody').on( 'click', 'button', function () {
		var row = table.row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah"){ ubah(row); }
		else if(mode == "btn_hapus"){ hapus(row); }

	} );

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});

function exportTableToExcel() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcel'), {sheet:"Sheet 1"});
  const ws = wb.Sheets[wb.SheetNames[0]];
                    
  ws['!cols'] = [
    { wpx: 70 }, // Column A width in pixels
    { wpx: 200 }, // Column B width in pixels
    { wpx: 60 },  // Column C width in pixels
    { wpx: 60 },  // Column C width in pixels
    { wpx: 300 },  // Column C width in pixels
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
				//clear form input
				$("#STATUS").prop('checked',true).iCheck('update');
				$("#MEMBER").prop('checked',false).iCheck('update');
				$("#KONSINYASI").prop('checked',false).iCheck('update');
				$("#DISKONMEMBER").val(0);
				$("#IDCUSTOMER").val("");
				$("#KODECUSTOMER").val("");
				$("#NAMACUSTOMER").val("");
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
				}
				else
				{
					$("#NAMACUSTOMER").removeAttr('readonly');
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
