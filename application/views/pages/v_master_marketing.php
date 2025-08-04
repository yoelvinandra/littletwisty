
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Master Marketing
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
                                <th>HP</th>
                                <th>Email</th>
                                <th>Catatan</th>
                                <th width="40px">User Input</th>
                                <th width="40px">Tgl. Input</th>
                                <th width="25px">Aktif</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_form">
                    <div class="box-body">
					 <div class="col-md-12">
						<!-- form start -->
						<form role="form" id="form_input">
                            <input type="hidden" id="mode" name="mode">
                            <input type="hidden" id="IDMARKETING" name="IDMARKETING">
                            <div class="box-body">
                                <div class="form-group col-md-6">
                                    <label for="KODEMARKETING">Kode Marketing &nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Aktif </label>
                                    <input type="text" class="form-control" id="KODEMARKETING" name="KODEMARKETING" placeholder="AUTO" readonly>
                                    <br>
                                    <label>Nama Marketing</label>
                                    <input type="text" class="form-control" id="NAMAMARKETING" name="NAMAMARKETING" placeholder="Nama Marketing">
                                    <br>
                                    <label>Alamat</label>
                                    <input type="text" class="form-control" id="ALAMAT" name="ALAMAT" placeholder="Alamat">
                                    <br>
                                    <label>Telp</label>
                                    <input type="text" class="form-control" id="TELP" name="TELP" placeholder="Telepon">
                                    <br>
                                    <label>HP</label>
                                    <input type="text" class="form-control" id="HP" name="HP" placeholder="Handphone">
                                    <br>
                                    <label>Email</label>
                                    <input type="text" class="form-control" id="EMAIL" name="EMAIL" placeholder="Email">
                                    <br>
                                    <label>Catatan</label>
                                    <textarea class="form-control" rows="3" id="CATATAN" name="CATATAN" placeholder="Catatan....."></textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
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
			url    : base_url+'Master/Data/Marketing/dataGrid',
			dataSrc: "rows",
		},
        columns:[
            {data: ''},
            {data: 'IDMARKETING', visible:false},
            {data: 'KODEMARKETING'},
            {data: 'NAMAMARKETING'},
            {data: 'ALAMAT'},
            {data: 'TELP'},
			{data: 'HP'},
			{data: 'EMAIL'},
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
	});
    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});

function tambah(){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
			$("#mode").val('tambah');

			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Tambah');

			//clear form input
			$("#STATUS").prop('checked',true).iCheck('update');
			$("#IDMARKETING").val("");
			$("#KODEMARKETING").val("");
			$("#NAMAMARKETING").val("");
			$("#ALAMAT").val("");
			$("#TELP").val("");
			$("#HP").val("");
			$("#EMAIL").val("");
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
			$("#IDMARKETING").val(row.IDMARKETING);
			$("#KODEMARKETING").val(row.KODEMARKETING);
			$("#NAMAMARKETING").val(row.NAMAMARKETING);
			$("#ALAMAT").val(row.ALAMAT);
			$("#TELP").val(row.TELP);
			$("#HP").val(row.HP);
			$("#EMAIL").val(row.EMAIL);
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
            url       : base_url+'Master/Data/Marketing/simpan',
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
			$("#mode").val('hapus');
			if (row) {
				$.ajax({
					type    : 'POST',
					dataType: 'json',
					url     : base_url+"Master/Data/Marketing/hapus",
					data    : "id="+row.IDMARKETING + "&kode="+row.KODEMARKETING,
					cache   : false,
					success : function(msg){
						if (msg.success) {
							Swal.fire({
								title            : 'Marketing dengan nama '+row.NAMAMARKETING+' telah dihapus',
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
