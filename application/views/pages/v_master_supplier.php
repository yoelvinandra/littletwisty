
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Master Supplier
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
                                <th>Alamat</th>
                                <th>Telp</th>
                                <th>Fax</th>
                                <th>Email</th>
                                <th>Website</th>
                                <th>Bank</th>
                                <th>Rekening</th>
                                <th>Contact Person</th>
                                <th>CP. Telp</th>
                                <th>CP. Email</th>
                                <th>NPWP</th>
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
                            <input type="hidden" id="IDSUPPLIER" name="IDSUPPLIER">
                            <div class="box-body">
                                <div class="form-group col-md-6">
                                    <label for="KODESUPPLIER">Kode Supplier &nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Aktif </label>
                                    <input type="text" class="form-control" id="KODESUPPLIER" name="KODESUPPLIER" placeholder="AUTO" readonly>
                                    <br>
                                    <label>Nama Supplier <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                    <input type="text" class="form-control" id="NAMASUPPLIER" name="NAMASUPPLIER" placeholder="Nama Supplier">
                                    <br>
                                    <label>Alamat</label>
                                    <input type="text" class="form-control" id="ALAMAT" name="ALAMAT" placeholder="Alamat">
                                    <br>
                                    <label>Telp</label>
                                    <input type="text" class="form-control" id="TELP" name="TELP" placeholder="Telepon">
                                    <br>
                                    <label>Fax</label>
                                    <input type="text" class="form-control" id="FAX" name="FAX" placeholder="Fax">
                                    <br>
                                    <label>Email</label>
                                    <input type="text" class="form-control" id="EMAIL" name="EMAIL" placeholder="Email">
                                    <br>
                                    <label>Website</label>
                                    <input type="text" class="form-control" id="WEBSITE" name="WEBSITE" placeholder="Website">
                                    <br>
                                    <label>Bank</label>
                                    <input type="text" class="form-control" id="NAMABANK" name="NAMABANK" placeholder="Bank">
                                    <br>
                                    <label>Rekening</label>
                                    <input type="text" class="form-control" id="NOREKENING" name="NOREKENING" placeholder="Rekening">
                                    <br>
                                    <label>Contact Person</label>
                                    <input type="text" class="form-control" id="CONTACTPERSON" name="CONTACTPERSON" placeholder="Contact Person">
                                    <br>
                                    <label>Telp. CP</label>
                                    <input type="text" class="form-control" id="TELPCP" name="TELPCP" placeholder="Telp. CP">
                                    <br>
                                    <label>Email CP</label>
                                    <input type="text" class="form-control" id="EMAILCP" name="EMAILCP" placeholder="Email CP">
                                    <br>
                                    <label>NPWP</label>
                                    <input type="text" class="form-control" id="NPWP" name="NPWP" placeholder="NPWP">
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
<!-- /.content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script>
var indexRow;
$(document).ready(function() {
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
			url    : base_url+'Master/Data/Supplier/dataGrid',
			dataSrc: "rows",
			dataFilter: function (data) {
                // Refresh the new table whenever DataTable reloads
                var allData = JSON.parse(data).rows; // Get all rows' data

                // Create the HTML structure for the new table
                var newTable = $('<table id="newTable" class="table table-bordered">');
                var thead = $('<thead>').append('<tr><th>Kode Supplier</th><th>Nama Supplier</th><th>Alamat</th><th>Telp</th><th>Fax</th><th>Email</th><th>Website</th><th>Nama Bank</th><th>No Rekening</th><th>Contact Person</th><th>Telp CP</th><th>Email CP</th><th>NPWP</th><th>Catatan</th><th>User Buat</th><th>Tgl Entry</th><th>Status</th></tr>');
                var tbody = $('<tbody>');
                 // Loop through the DataTable data and create rows for the new table
         
                allData.forEach(function (row, index) {
                    var tr = $('<tr>');
                    tr.append('<td>' + (row.KODESUPPLIER) + '</td>');
                    tr.append('<td>' + (row.NAMASUPPLIER) + '</td>');
                    tr.append('<td>' + (row.ALAMAT == null?"":row.ALAMAT) + '</td>');
                    tr.append('<td>' + (row.TELP == null?"":row.TELP) + '</td>');
                    tr.append('<td>' + (row.FAX == null?"":row.FAX) + '</td>');
                    tr.append('<td>' + (row.EMAIL == null?"":row.EMAIL) + '</td>');
                    tr.append('<td>' + (row.WEBSITE == null?"":row.WEBSITE) + '</td>');
                    tr.append('<td>' + (row.NAMABANK == null?"":row.NAMABANK) + '</td>');
                    tr.append('<td>' + (row.NOREKENING == null?"":row.NOREKENING) + '</td>');
                    tr.append('<td>' + (row.CONTACTPERSON == null?"":row.CONTACTPERSON) + '</td>');
                    tr.append('<td>' + (row.TELPCP == null?"":row.TELPCP) + '</td>');
                    tr.append('<td>' + (row.EMAILCP == null?"":row.EMAILCP) + '</td>');
                    tr.append('<td>' + (row.NPWP == null?"":row.NPWP) + '</td>');
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
            {data: 'IDSUPPLIER', visible:false},
            {data: 'KODESUPPLIER'},
            {data: 'NAMASUPPLIER'},
            {data: 'ALAMAT'},
            {data: 'TELP'},
			{data: 'FAX'},
            {data: 'EMAIL'},
            {data: 'WEBSITE'},
            {data: 'NAMABANK'},
            {data: 'NOREKENING'},
            {data: 'CONTACTPERSON'},
            {data: 'TELPCP'},
            {data: 'EMAILCP'},
            {data: 'NPWP'},
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
    { wpx: 300 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 120 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 60 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 120 },  // Column C width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 100 }, // Column A width in pixels
    { wpx: 70 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'SUPPLIER_'+dateNowFormatExcel()+'.xlsx');
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
				$("#IDSUPPLIER").val("");
				$("#KODESUPPLIER").val("");
				$("#NAMASUPPLIER").val("");
				$("#ALAMAT").val("");
				$("#TELP").val("");
				$("#FAX").val("");
				$("#EMAIL").val("");
				$("#WEBSITE").val("");
				$("#NAMABANK").val("");
				$("#NOREKENING").val("");
				$("#CONTACTPERSON").val("");
				$("#TELPCP").val("");
				$("#EMAILCP").val("");
				$("#NPWP").val("");
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
			$("#IDSUPPLIER").val(row.IDSUPPLIER);
			$("#KODESUPPLIER").val(row.KODESUPPLIER);
			$("#NAMASUPPLIER").val(row.NAMASUPPLIER);
			$("#ALAMAT").val(row.ALAMAT);
			$("#TELP").val(row.TELP);
			$("#FAX").val(row.FAX);
			$("#EMAIL").val(row.EMAIL);
			$("#WEBSITE").val(row.WEBSITE);
			$("#NAMABANK").val(row.NAMABANK);
			$("#NOREKENING").val(row.NOREKENING);
			$("#CONTACTPERSON").val(row.CONTACTPERSON);
			$("#TELPCP").val(row.TELPCP);
			$("#EMAILCP").val(row.EMAILCP);
			$("#NPWP").val(row.NPWP);
			$("#CATATAN").val(row.CATATAN);
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
            url       : base_url+'Master/Data/Supplier/simpan',
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
                		title: 'Anda Yakin Akan Menghapus Supplier '+row.NAMASUPPLIER+' ?',
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
                    		    	url     : base_url+"Master/Data/Supplier/hapus",
                    		    	data    : "id="+row.IDSUPPLIER + "&kode="+row.KODESUPPLIER,
                    		    	cache   : false,
                    		    	success : function(msg){
                    		    		if (msg.success) {
                    		    			Swal.fire({
                    		    				title            : 'Supplier dengan nama '+row.NAMASUPPLIER+' telah dihapus',
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
