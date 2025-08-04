<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
    Master Perkiraan
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
                                    <th width="40px">Kode</th>
                                    <th>Nama</th>
                                    <th>Kelompok</th>
                                    <th width="30px">Saldo</th>
                                    <th width="40px">Induk</th>
                                    <th width="30px">Tipe</th>
                                    <th width="40px">Kas/Bank</th>
                                    <th width="40px">Kd.Kas/Bank</th>
                                    <!-- <th>Currency</th> -->
                                    <th width="40px">Akun Piutang</th>
                                    <th width="40px">Akun Hutang</th>
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
                            <input type="hidden" id="IDPERKIRAAN" name="IDPERKIRAAN">
                            <div class="box-body">
                                <div class="form-group col-md-6">
                                    <label for="KODEPERKIRAAN">Kode Perkiraan &nbsp;&nbsp;&nbsp; <input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Aktif </label>
                                    <input type="text" class="form-control" id="KODEPERKIRAAN" name="KODEPERKIRAAN" placeholder="AUTO">
                                    <br>
                                    <label>Nama Perkiraan</label>
                                    <input type="text" class="form-control" id="NAMAPERKIRAAN" name="NAMAPERKIRAAN" placeholder="Nama Perkiraan">
                                    <br>
                                    <label>Kelompok</label><br>
                                    <select id="KELOMPOK" name="KELOMPOK" class="form-control select2" style="width:100%;" panelHeight="auto" required="true">
                                        <option value="NERACA-AKTIVA">Neraca-Aktiva</option>
                                        <option value="NERACA-PASIVA">Neraca-Pasiva</option>
                                        <option value="LABA/RUGI-PENAMBAH">Laba/Rugi-Penambah</option>
                                        <option value="LABA/RUGI-PENGURANG">Laba/Rugi-Pengurang</option>
                                    </select>
                                    <br>
                                    <label>Saldo</label><br>
                                    <select id="SALDO" name="SALDO" class="form-control select2" style="width:100%;" panelHeight="auto" required="true">
                                        <option value=""> - </option>
                                        <option value="DEBET">Debet</option>
                                        <option value="KREDIT">Kredit</option>
                                    </select>
                                    <br>
                                    <label for="INDUK">Akun Perkiraan Induk</label>
                                    <div class="input-group margin" style="padding:0; margin:0">
                                        <input type="text" id="INDUK" name="INDUK" hidden>
                                        <input type="text" class="form-control" id="NAMAINDUK" name="NAMAINDUK" placeholder="Akun Perkiraan Induk" readonly>
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal-akuninduk">Search</button>
                                        </div>
                                    </div>
                                    <br>
                                    <label>Tipe</label><br>
                                    <select id="TIPE" name="TIPE" class="form-control select2" style="width:100%;" panelHeight="auto" required="true">
                                        <option value="HEADER">Header</option>
                                        <option value="DETAIL">Detail</option>
                                    </select>
                                    <br>
                                    <label>Jenis Kas-Bank</label><br>
                                    <select id="KASBANK" name="KASBANK" class="form-control select2" style="width:100%;" panelHeight="auto" required="true">
                                        <option value="0"> - </option>
                                        <option value="1">Kas</option>
                                        <option value="2">Bank</option>
                                    </select>
                                    <br>
                                    <label>Kode Kas-Bank</label>
                                    <input type="text" class="form-control" id="KODEKASBANK" name="KODEKASBANK" placeholder="Kode Kas Bank">
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

    <div class="modal fade" id="modal-akuninduk">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <table id="table_akuninduk" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        </div>
    </div>
</section>
<!-- /.content -->

<script>
$(document).ready(function() {
    $("#mode").val('tambah');
    $("#STATUS").prop('checked',true).iCheck('update');
    
    // $('.select2').select2({
    //     selectOnClose: true,
    //     minimumResultsForSearch: Infinity
    // });
		
    $('#dataGrid').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/Perkiraan/dataGrid',
			dataSrc: "rows",
		},
        columns:[
            {data: ''},
            {data: 'IDPERKIRAAN', visible:false},
            {data: 'KODEPERKIRAAN', className:"text-left"},
            {data: 'NAMAPERKIRAAN'},
            {data: 'KELOMPOK'},
            {data: 'SALDO'},
            {data: 'INDUK'},
            {data: 'TIPE'},
            {data: 'KASBANK'},
            {data: 'KODEKASBANK'},
            {data: 'AKUNPIUTANG', className:"text-center"},
            {data: 'AKUNHUTANG', className:"text-center"},
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
                "targets": 8,
                "render" :function (data) 
                        {
                            if (data == 1) return 'KAS';
                            else if (data == 2) return 'BANK';
                            else return '';
                        },	
            },
            {
                "targets": 10,
                "render" :function (data) 
                        {
                            if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                            else return '<input type="checkbox" class="flat-blue" disabled></input>';
                        },	
            },
            {
                "targets": 11,
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
		],
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
    
    $("#table_akuninduk").DataTable({
        'retrieve'    : true,
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		ajax		  : {
			url    : base_url+'Master/Data/Perkiraan/dataGrid',
			dataSrc: "rows",
		},
        columns:[
            {data: 'KODEPERKIRAAN'},
			{data: 'NAMAPERKIRAAN'},
        ],
    });

    $('#table_akuninduk tbody').on('click', 'tr', function () {
        var data = $('#table_akuninduk').DataTable().row( this ).data();
        $("#INDUK").val(data.KODEPERKIRAAN);
        $("#NAMAINDUK").val(data.KODEPERKIRAAN + " ~ " + data.NAMAPERKIRAAN);
        $("#modal-akuninduk").modal('hide');
    } );
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
			$("#IDPERKIRAAN").val("");
			$("#KODEPERKIRAAN").val("");
			$("#NAMAPERKIRAAN").val("");
			$('#KELOMPOK').val("NERACA-AKTIVA");
			$('#SALDO').val("");
			$('#TIPE').val("HEADER");
			$('#KASBANK').val(0);
			$('#KODEKASBANK').val("");
			$('#KELOMPOK, #SALDO, #TIPE, #KASBANK').select2().trigger('change');
			$("#INDUK").val("");
			$("#NAMAINDUK").val("");
			$("#KASBANK").val("");
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
				$("#IDPERKIRAAN").val(row.IDPERKIRAAN);
				$("#KODEPERKIRAAN").val(row.KODEPERKIRAAN);
				$("#NAMAPERKIRAAN").val(row.NAMAPERKIRAAN);
				$('#KELOMPOK').val(row.KELOMPOK);
				$('#SALDO').val(row.SALDO);
				$('#TIPE').val(row.TIPE);
				$('#KASBANK').val(row.KASBANK);
				$('#KODEKASBANK').val(row.KODEKASBANK);
				$('#KELOMPOK, #SALDO, #TIPE, #KASBANK').select2().trigger('change');
				$("#INDUK").val(row.INDUK);
				$("#NAMAINDUK").val(row.NAMAINDUK);
				$("#KASBANK").val(row.KASBANK);
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
            url       : base_url+'Master/Data/Perkiraan/simpan',
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
					url     : base_url+"Master/Data/Perkiraan/hapus",
					data    : "id="+row.IDPERKIRAAN + "&kode="+row.KODEPERKIRAAN,
					cache   : false,
					success : function(msg){
						if (msg.success) {
							Swal.fire({
								title            : 'Perkiraan dengan nama '+row.NAMAPERKIRAAN+' telah dihapus',
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