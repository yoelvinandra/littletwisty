<!DOCTYPE html>
<html lang="en">
  <style>
	div.scrollmenu {
	  white-space: nowrap;
	  transform:rotateX(180deg);
	  height:50px;
	  overflow-x:auto;
	}

	div.scrollmenu a {
	  background-color: #f7f7f7;
	  display: inline-block;
	  color: grey;
	  border-top:1px solid;
	  border-left:1px solid;
	  border-right:1px solid;
	  border-radius:3px 3px 0px 0px;
	  text-align: center;
	  padding: 10px;
	  text-decoration: none;
	  transform:rotateX(180deg);
	
	}

	div.scrollmenu a:hover {
	  background-color: #2a3f54;
	  border-top:1px solid;
	  border-left:1px solid;
	  border-right:1px solid;
	  border-radius:3px 3px 0px 0px;
	  border-color:#2a3f54;	  
	  color: white;
	}

	#choose {
	  background-color: #2a3f54;
	  border-top:1px solid;
	  border-left:1px solid;
	  border-right:1px solid;
	  border-radius:3px 3px 0px 0px;
	  border-color:#2a3f54;	  
	  color: white;
	}
	
	.simpan{
	  background-color: #2a3f54;
	  border:1px solid;
	  border-radius:3px 3px 3px 3px;
	  border-color:#2a3f54;	  
	  color: white;
	}
	
	.simpan:hover{
	  background-color: white;
	  color:black;
	}
	
	#choose_pembayaran{
	  background-color: #2a3f54;
	  border-radius:3px;
	  border-color:#2a3f54;	  
	  color: white;
	}
	
	.tab_bayar{
	  padding:10px 0px 10px 10px;
	  border:1px solid;
	  border-radius:3px;
	  margin-left:10px;
	}
	
	#table_barang{
	  cursor:pointer;
	}
	
  </style>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Transaksi Opname Stok
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol> -->
</section>

<section class="content">  
  <!-- Main row -->
  <div class="row">
      <div class="col-md-12">
        <div class="box">
			<div class="box-header form-inline">
				<button class="btn btn-success" onclick="javascript:tambah()">Tambah</button>
				<div style="display: inline" class="pull-right">
					<input type="text" class="form-control" id="tgl_awal_filter" style="width:100px;" name="tgl_awal_filter" readonly> - 
					<input type="text" class="form-control" id="tgl_akhir_filter" style="width:100px;" name="tgl_akhir_filter" readonly>&nbsp;
					<button class="btn btn-success" onclick="javascript:refresh()">Tampilkan</button>
				</div>
			</div>
					<div class="nav-tabs-custom" >
						<ul class="nav nav-tabs" id="tab_transaksi">
							<li class="active"><a href="#tab_grid" data-toggle="tab">Grid</a></li>
							<li><a href="#tab_form" data-toggle="tab" onclick="tambah_ubah_mode()" >Tambah</a></li>
				
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_grid">
								<div class="box-body">
									<div class="input-group pull-right" style="width:24%;">
									 <div class="input-group-addon">
										 <i class="fa fa-filter"></i>
									 </div>
										<select id="cb_trans_status" name="cb_trans_status" class="form-control "  panelHeight="auto" required="true">
											<option value="SEMUA">Tampilkan Semua Transaksi </option>
											<option value="AKTIF">Tampilkan Transaksi Aktif</option>
											<option value="HAPUS">Tampilkan Transaksi Hapus</option>
										</select>
									</div>
									<br><br>
									<table id="dataGrid" class="table table-bordered table-striped table-hover display nowrap" width="100%">
										<!-- class="table-hover"> -->
										<thead>
										<tr>
											<th width="35px"></th>
											<th width="35px"></th>
											<th>Tgl Trans</th>
											<th>No. Opname</th>
											<th>Catatan</th>
											<th width="40px">User Input</th>
											<th width="40px">Tgl. Input</th>
											<th width="40px">User Batal</th>
											<th width="40px">Tgl. Batal</th>
											<th width="100px">Alasan Pembatalan</th>
											<th width="25px">Status</th>
										</tr>
										</thead>
									</table>
								</div>
								<!--MODAL BATAL-->
								<div class="modal fade" id="modal-alasan">
									<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<label id="KETERANGAN_BATAL"></label>
											<textarea class="form-control" id="ALASANPEMBATALAN" name="ALASANPEMBATALAN" placeholder="Alasan pembatalan"></textarea> 
											<br>
											<button class="btn btn-danger pull-right" id="btn_batal" onclick="batal()">Batal</button>
											<br>
											<br>
										</div>
									</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab_form">
							    <div class="box-body">
                					 <div class="col-md-12">
                                        <div class="box-body">
                							<div class="form-group col-md-4">
        									<input type="hidden" id="mode" name="mode">
        									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
        										<label >No Opname </label>
        									</div>
        									<div class="col-md-9 col-sm-9 col-xs-9" style="padding: 0px 0px 5px 0px">
												<input type="text" class="form-control" id="NOTRANS" name="NOTRANS" style="width:100%; border:1px solid #B5B4B4; border-radius:1px;" placeholder="Auto Generate" readonly>
												<input type="hidden" class="form-control" id="IDTRANS" name="IDTRANS">	
        									</div>
        									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
        										<label>Tgl Opname  </label>
        									</div>
        									<div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">	 
											     <input type="text" class="form-control" id="TGLTRANS"  name="TGLTRANS" style="width:100%; border:1px solid #B5B4B4; border-radius:1px;" placeholder="Tgl Opname">
        									</div>
        									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
        										<label >Lokasi  </label>
        									</div>
        									<div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">
        									    <select  class="form-control"  id="LOKASI" name="LOKASI" style="width:100%;border:1px solid #B5B4B4; border-radius:1px;"><?=comboGrid("model_master_lokasi")?></select>			
        									</div>
        									<div class="col-md-3 col-sm-3 col-xs-3 SALINDETAILBARANG" style="padding: 0px; margin-top:15px;">
                        						<label >Salin barang dari </label>
                        					</div>
                        					<div class="col-md-9  col-sm-9 col-xs-9 SALINDETAILBARANG"  style="padding: 0px 0px 5px 0px;  margin-top:15px;" >
                        					    <div class="input-group margin" style="padding:0; margin:0">
                            				        <input type="text"  class="form-control" id="DETAILBARANG" style="border:1px solid #B5B4B4; border-radius:1px;" placeholder="Masukkan Kode Transaksi">
                            				        <div class="input-group-btn">
                            							<button type="button" id="btn_salin" class="btn btn-primary btn-flat" data-id="7">Salin</button>
                            						</div>
                        						</div>
                        					</div>
        								</div>
        								<div class="form-group col-md-4">
        									 <textarea class="form-control" id="CATATAN" name="CATATAN" placeholder="Catatan"  style="height:75px;"></textarea> 		
        								</div>
                						<div class="col-md-12 col-sm-12 col-xs-12">
                							<div role="tabpanel" data-example-id="togglable-tabs" >
                								<div class="col-md-8 col-sm-8 col-xs-6" style="margin-bottom:5px;">
                									<button type="button" id="btn_tambah" class="btn btn-success" onclick="tambahDetail()" data-toggle="modal" data-target="#modal-barang" ><i class="fa fa-plus"></i></button>		
                								</div>										
                								<div class="col-md-4 col-sm-4 col-xs-6" align="right" style="margin-bottom:5px;">											
                									<button type="button" id="btn_simpan" class="btn btn-primary" onclick="simpan()">Simpan &nbsp;  <i class="fa fa-save"></i></button>
                								</div>
                								
                								<div id="trans_content" class="tab-content trans-content" >
                									<div role="tabpanel" class="tab-pane fade active in" id="tab_trans0" >
                									<div class="col-md-12 col-sm-12 col-xs-12" style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; padding:15px; ">
                										<!--SATU TABEL-->
															<div class="col-md-12 col-sm-12 col-xs-12 ">
															   <div class="x_content" style="height:350px; overflow-y:auto; overflow-x:hidden;">
																<div class="row"> 
																  <div class=" col-sm-12">
																	  <table id="dataGridDetail" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
																		<thead>
																			<tr>
																				<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
																				<th style="vertical-align:middle; text-align:center;" width="50px">Jml</th>
																				<th style="vertical-align:middle; text-align:center;" width="50px">Satuan</th>
																				<th style="vertical-align:middle; text-align:center;" width="50px"> </th>
																			</tr>
																		</thead>
																		<tbody class="table-responsive"></tbody>
																	 </table> 
																  </div>
																</div>
															  </div> 
															</div>
													
                									    </div>
                								<!-- /footer content -->
                									</div>
                								</div>
                							</div>
                						</div>
                					<!--MODAL BARANG-->
                						 <div class="modal fade" id="modal-barang">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-body">	
														<table id="table_barang" class="table table-bordered table-striped table-hover display nowrap" width="100%">
															<thead>
																<tr>
																	<th hidden>ID</th>	
																	<th width="50px">Kode</th>
																	<th>Nama</th>	
																</tr>
															</thead>
														</table>
														<input type="text" class="form-control has-feedback-left" id="jml" onkeyup="return numberInput(event,'',1)" placeholder="Jml" style="position:absolute; left:88%;top:10px; width:60px;" value="1">
													</div>
												</div>
											</div>
										 </div>
                					</div>
                					<!-- /.box-body -->
                                </div>
						</div>
					</div>

				</div>
			</div>
    <!-- /.col -->
		</div>
	</div>
</section>
<input type="hidden" id="status">
<script>
var pointertrans = 0;
var pointerbayar = 0;
var counttrans = 0;
var countdetail = 0;
var qty=0;
var mode='';
var base_url = '<?=base_url()?>';

$(document).ready(function(){
    
    $("#btn_salin").click(function(){
	    $.ajax({
			type    : 'POST',
			url     : base_url+'Inventori/Transaksi/OpnameStok/loadDetail/',
			data    : {kode: $("#DETAILBARANG").val(),mode:"ubah"},
			dataType: 'json',
			success : function(msg){
			       
					for(var i = 0; i < msg.length;i++)
					{
					     msg[i].STOK = 1;
			             $("#jml").val(parseInt(msg[i].QTY));
					     tambah_barang(msg[i]);  
					}
					
			}
		});
	});
	
	//TAMBAH
	$('#TGLTRANS, #tgl_awal_filter, #tgl_akhir_filter').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true, // Close the datepicker automatically after selection
        container: 'body', // Attach the datepicker to the body element
        orientation: 'bottom auto' // Show the calendar below the input
	});
	$("#tgl_awal_filter").datepicker('setDate', "<?=TGLAWALFILTER?>");
	$("#TGLTRANS,#tgl_akhir_filter").datepicker('setDate', new Date());
	$("#TGLTRANS").change(function() {
        var dateTGLTRANS = $('#TGLTRANS').datepicker('getDate'); // Get the date from #TGLTRANS
        var dateNow = new Date(); // Get the date from #tgl_awal_filter

        // If both dates are valid, compare them
        if (dateTGLTRANS && dateNow) {
            if (dateTGLTRANS > dateNow) {
               	Swal.fire({
        			title            : 'Tanggal Transaksi Maksimal Hari Ini',
        			type             : 'warning',
        			showConfirmButton: false,
        			timer            : 1500
        		});
		
               $("#TGLTRANS").datepicker('setDate', new Date());
            }
        } else {
            alert("Please select valid dates.");
        }
    });
	$("#status").val('I,S,P,D');

	$('.select2').select2({
		  theme: "classic"
	});
	$("#modal-barang").on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input', $("#table_barang").DataTable().table().container()).focus();
    });
    
    $("#LOKASI").change(function() {
        for(var i = 0 ; i < countdetail; i++)
		{	
			$("#detailtab"+i+"").remove();
		}
			
		countdetail= 0;
      })
	
	//GRID BARANG
	$('#dataGrid').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		"createdRow"  : function( row, data, dataIndex ) {
             if (data.STATUS == "S") {
                $(row).addClass('status-cetak');
            }
            else if (data.STATUS == "P") {
                $(row).addClass('status-lanjut');
            }
            else if (data.STATUS == "D") {
                $(row).addClass('status-batal');
            }
        },
		ajax		  : {
			url    : base_url+'Inventori/Transaksi/OpnameStok/dataGrid',
			dataSrc: "rows",
			type   : "POST",
			data   : function(e){
					e.status 		 = getStatus();
					e.tglawal        = $('#tgl_awal_filter').val();
					e.tglakhir       = $('#tgl_akhir_filter').val();
				  }
		},
        columns:[
            { data: '' },    
            { data: 'IDOPNAMESTOK',visible: false,},	
            { data: 'TGLTRANS',className:"text-center"},	
            { data: 'KODEOPNAMESTOK' ,className:"text-center"},
            { data: 'CATATAN' },
			{ data: 'USERINPUT' ,className:"text-center"},
            { data: 'TGLINPUT' ,className:"text-center"},
            { data: 'USERBATAL',className:"text-center" },
            { data: 'TGLBATAL' ,className:"text-center"},
            { data: 'ALASANBATAL'},
            { data: 'STATUS' ,className:"text-center"},            
        ],
		columnDefs: [ 
			{
                "targets": 0,
                "data": null,
                "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> <button id='btn_cetak' class='btn btn-warning'><i class='fa fa-print' ></i></button>"	
			},
		]
    });
	
	//DAPATKAN INDEX
	var table = $('#dataGrid').DataTable();
	$('#dataGrid tbody').on( 'click', 'button', function () {
		var row = table.row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah"){ ubah(row);}
		else if(mode == "btn_hapus"){ before_batal(row);}
		else if(mode == "btn_cetak"){ cetak(row);}

	} );
	
	
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
			url    : base_url+'Master/Data/Barang/comboGridTransaksi',   // Master/Data/Barang/loadData
			dataSrc: "rows",
			type   : "POST",
			data   : {mode:"BIASA"},
		},
		language: {
			search: "Cari",
			searchPlaceholder: "Nama Produk"
		},
        columns:[
            { data: 'ID', visible: false,},
            { data: 'KODE'},
            { data: 'NAMA'},
        ],
		
    });
		
	//BUAT NAMBAH BARANG BIASA
	$('#table_barang tbody').on('click', 'tr', function () {
		var row = $('#table_barang').DataTable().row( this ).data();
        tambah_barang(row); 
		$("#modal-barang").modal('hide');	
		
		$("#jml").val(1);
		var table = $('#table_barang').DataTable();
		table.search("").draw();
	});
	countdetail = 0;
});
//MENAMPILKAN TRANSAKSI
$("#cb_trans_status").change(function(event){

	if($(this).val()  == 'SEMUA' )
	{
		$("#status").val('I,S,P,D');
	}	
	else if($(this).val()  == 'AKTIF' )
	{
		$("#status").val('I,S,P');
	}
	else if($(this).val()  == 'HAPUS' )
	{
		$("#status").val('D');
	}
	$("#dataGrid").DataTable().ajax.reload();
	
});

function getStatus(){
	return $("#status").val();
}


function refresh(){
    $("#dataGrid").DataTable().ajax.reload();
}	
//---------	
function tambah_barang(row){
	var jml = parseFloat($("#jml").val());
	var ada = false;
	var simpanIndex = -1;
	var temp_jumlah = 0;
	
	for(var i = 0; i < countdetail;i++)
	{
		if($("#KODE"+i).html() == row.KODE)
		{		
			ada = true;
			simpanIndex = i;
		}
	}
	
	//MASUKAN DATA DALAM GRID
	if(ada)
	{
		//JUMLAH BARANG HASIL UPDATE DI GRID
		temp_jumlah = (parseFloat($("#JUMLAH"+simpanIndex+" input").val())+parseFloat(jml));
		if(temp_jumlah > 0)
		{
			$("#JUMLAH"+simpanIndex+" input").val(parseFloat($("#JUMLAH"+simpanIndex+" input").val())+parseFloat(jml));		
		}
		else
		{
			alert("Jumlah Barang Tidak Boleh Kurang Dari 1");
		}
	}
	else
	{	
		temp_jumlah = row.QTY;
		if(temp_jumlah > 0)
		{
			 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+row.ID+"</td><td id='KODE"+countdetail+"' hidden>"+row.KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
				+row.NAMA+"</td><td width='60px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+jml+"'></td><td id='SATUAN"+countdetail+"' align='center'>"+row.SATUANKECIL+"</td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
				$("#JUMLAH"+countdetail+ " input").number(true, 0);
				countdetail++;
				$('.x_content').animate({
                  scrollTop: $('.table-responsive')[0].scrollHeight
                }, 0);  
		}
		else
		{
			alert("Jumlah Barang Tidak Boleh Nol");
		}
	}
}


function tambah(){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
			$("#mode").val('tambah');
			$('.table-responsive').html("");
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Tambah');
			reset();
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
function tambah_ubah_mode(){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if($('.nav-tabs a[href="#tab_form"]').html() == 'Tambah' && data.TAMBAH==1)
		{
			$("#mode").val('tambah');
			reset();
		}
		else if(data.UBAH==1)
		{
			$("#mode").val('ubah');
		}
		else
		{
			Swal.fire({
				title            : 'Anda Tidak Memiliki Hak Akses',
				type             : 'warning',
				showConfirmButton: false,
				timer            : 1500
			});
			$('.nav-tabs a[href="#tab_grid"]').tab('show');
		}
	});
}
function ubah(row){
    $("#DETAILBARANG").val("");
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.UBAH==1) {
			$("#mode").val('ubah');
			
			$("#TGLTRANS").attr('disabled','disabled');
			$("#LOKASI").attr('disabled','disabled');
			
			$('.table-responsive').html("");
			countdetail = 0;
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Ubah');
			
			get_status_trans("Inventori/Transaksi/OpnameStok",row.IDOPNAMESTOK,function(data){
				if(data.status != "I")
				{
					$("#btn_simpan, #btn_tambah").css('filter', 'grayscale(100%)');
					$("#btn_simpan, #btn_tambah").attr('disabled', 'disabled');
				}
				else
				{
					$("#btn_simpan, #btn_tambah").css('filter', '');
					$("#btn_simpan, #btn_tambah").removeAttr('disabled');
				}
			});
			
			//HEADER
			 $.ajax({
				type    : 'POST',
				url     : base_url+'Inventori/Transaksi/OpnameStok/loadHeader/', 
				data    : {id:row.IDOPNAMESTOK,mode:"ubah"},
				dataType: 'json',
				success: function(msg){
					$("#IDTRANS").val(msg.IDTRANS);
					$("#NOTRANS").val(msg.KODETRANS);
					$("#TGLTRANS").datepicker('setDate',msg.TGLTRANS);
					$("#LOKASI").val(msg.IDLOKASI);
					$('#LOKASI').trigger('change');
					$("#CATATAN").val(msg.CATATAN);
					$("#CATATAN").attr("readonly","readonly");
					
        			$.ajax({
        				type    : 'POST',
        				url     : base_url+'Inventori/Transaksi/OpnameStok/loadDetail/', 
        				data    : {id:row.IDOPNAMESTOK,mode:"ubah"},
        				dataType: 'json',
        				success: function(msg){
        						//10*B00001
        						//BARANG PERNAH DIINPUTKAN ATAU BELUM
        						for(var i = 0; i < msg.length;i++)
        						{
        							 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+msg[i].ID+"</td><td id='KODE"+countdetail+"' hidden>"+msg[i].KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
        							+msg[i].NAMA+"</td><td width='60px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+parseFloat(msg[i].QTY)+"'></td><td id='SATUAN"+countdetail+"' align='center'>"+msg[i].SATUANKECIL+"</td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
        							$("#JUMLAH"+countdetail+ " input").number(true, 0);
        							countdetail++;
        						}
        				}
        			});
			 }
			});
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

function before_batal(row){
	get_status_trans("Inventori/Transaksi/OpnameStok",row.IDOPNAMESTOK, function(data){
		if (data.status=='I' || data.status=='S') {
			get_akses_user('<?=$kodemenu?>', function(data){
				if (data.HAPUS==1) {
					$('#ALASANPEMBATALAN').val("");
					$("#modal-alasan").modal('show');
					$("#btn_batal").val(JSON.stringify(row));
					$("#KETERANGAN_BATAL").html("Apa anda yakin akan membatalkan transaksi "+row.KODEOPNAMESTOK+" ?");
				} else {
					Swal.fire({
						title            : 'Anda Tidak Memiliki Hak Akses',
						type             : 'warning',
						showConfirmButton: false,
						timer            : 1500
					});
				
				}
			});
		}else{
				Swal.fire({
					title            : 'Transaksi Tidak Dapat Dibatalkan',
					type             : 'error',
					showConfirmButton: false,
					timer            : 1500
				});
		}
	});
}

function batal(){
	$("#modal-alasan").modal('hide');
	var row = JSON.parse($("#btn_batal").val());
	alasan = $('#ALASANPEMBATALAN').val();
	
	if (row  && alasan != "") {
		$.ajax({
			type    : 'POST',
			dataType: 'json',
			url     : base_url+"Inventori/Transaksi/OpnameStok/batalTrans",
			data    : "idtrans="+row.IDOPNAMESTOK + "&kodetrans="+row.KODEOPNAMESTOK + "&alasan="+alasan,
			cache   : false,
			success: function(msg){
				if (msg.success) {
					Swal.fire({
						title            : 'Transaksi dengan kode '+row.KODEOPNAMESTOK+' telah dibatalkan',
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
	}else{
		Swal.fire({
			title            : 'Alasan Harus Diisi',
			type             : 'error',
			showConfirmButton: false,
			timer            : 1500
		});
	}
}

function cetak(row){
	get_akses_user('<?=$kodemenu?>', function(data){
		if (data.CETAK==1) {	
			get_status_trans("Inventori/Transaksi/OpnameStok",row.IDOPNAMESTOK, function(data){
				if (data.status == "I") {
					$.ajax({
						type    : 'POST',
						dataType: 'json',
						url     : base_url+'Inventori/Transaksi/OpnameStok/ubahStatusJadiSlip',
						data    : {idtrans: row.IDOPNAMESTOK, kodetrans: row.KODEOPNAMESTOK},
						cache   : false,
						success: function(msg){
							if (msg.success) {
								$("#dataGrid").DataTable().ajax.reload();
								window.open(base_url+"Inventori/Transaksi/OpnameStok/cetak/"+row.IDOPNAMESTOK, '_blank');
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
				else if(data.status != "D"){
					window.open(base_url+"Inventori/Transaksi/OpnameStok/cetak/"+row.IDOPNAMESTOK, '_blank');
				}
				else
				{
					Swal.fire({
						title            : 'Transaksi Tidak Dapat Dicetak',
						type             : 'error',
						showConfirmButton: false,
						timer            : 1500
					});
				}
			});
		}else{
			Swal.fire({
				title            : 'Alasan Harus Diisi',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
		}
	});
	
}


function tambahDetail(){
	$('#table_barang_filter input').focus();
}


function ubahDetail(e){
	alert(e);
}

function hapusDetail(e){	
	//HAPUS
	$("#detailtab"+e+"").remove();

}


function simpan(){
	var row = [];
	var jmlData = 0;
	
	for (var i=0;i<countdetail;i++) {
		if(typeof $('#NAMA'+i).html() != "undefined") // BARANG TELAH DIHAPUS
		{		
			row[jmlData] = 
				{
					idbarang: $("#ID"+i).html(),
					jml     : $("#JUMLAH"+i+" input").val(),
					satuan  : $("#SATUAN"+i).html(),
				};
			jmlData++;
		}
	}

	if(jmlData == 0)
	{
		Swal.fire({
            title            : "Tidak Ada Data Barang",
            type             : 'error',
            showConfirmButton: false,
            timer            : 1500
       });
	}
	else
	{
		$.ajax({
			type    : 'POST',
			url     : base_url+'Inventori/Transaksi/OpnameStok/simpan/', 
			data    : {	
						IDOPNAME   : $("#IDTRANS").val(),
						KODEOPNAME : $("#NOTRANS").val(),
						IDLOKASI   : $("#LOKASI").val(),
						TGLTRANS   : $("#TGLTRANS").val(),
						data_detail: JSON.stringify(row),
						mode       : $("#mode").val(),
						CATATAN    : $("#CATATAN").val(),
					  },
			dataType: 'json',
			success: function(msg){
				if (msg.success) {
					Swal.fire({
						title            : 'Simpan Data Sukses',
						type             : 'success',
						showConfirmButton: false,
						timer            : 1500
					});
					$("#dataGrid").DataTable().ajax.reload();
					$('.nav-tabs a[href="#tab_grid"]').tab('show');
					reset();
				}else {
					Swal.fire({
							title            : msg.errorMsg,
							type             : 'error',
							showConfirmButton: false,
							timer            : 1500
					});
				}
			}}
		);
	}
	
}


function reset(){
    $("#DETAILBARANG").val("");
	$('.table-responsive').html("");
	$("#NOTRANS").val("");
	$("#IDTRANS").val("");
	$("#TGLTRANS").datepicker('setDate', new Date());
	$("#LOKASI").val($('#LOKASI option:eq(0)').val());
	$('#LOKASI').trigger('change');
	countdetail = 0;
	
	$("#TGLTRANS").removeAttr('disabled');
	$("#LOKASI").removeAttr('disabled');
	$("#CATATAN").removeAttr('readonly');
	
	$("#btn_simpan, #btn_tambah").css('filter', '');
	$("#btn_simpan, #btn_tambah").removeAttr('disabled');
}

//LIMIT ANGKA SAJA
function numberInput(evt,e,field) {
	var inputLength;
	// 0 = jumlah detail
	// 1 = jumlah ketika tambah
	// 2 = diskon detail
	
	if(field == 0)
	{
		inputLength = $("#JUMLAH"+e+" input").val().length;
	}
	else if(field == 1){
		inputLength = $("#jml").val().length;
	}
	else if(field == 2)
	{
		inputLength = $("#DISKON"+e+" input").val().length;
	}

	var charCode = (evt.which) ? evt.which : event.keyCode

// 	if (charCode == 13) {		
		if(inputLength == 0) //KALAU FIELD KOSONG
		{
			$("#jml").val(1);
			$("#JUMLAH"+e+" input").val(0);
		}
		
// 	}

	if (charCode > 31 && (charCode < 48 || charCode > 57) || inputLength > 4) //CEK ANGKA DAN DIGIT MAKS 3
	return false;
	return true;
}

function get_status_trans(v_link, v_idtrans, callback) {
	$.ajax({
		dataType: "json",
		type    : 'POST',
		url     : base_url+v_link+"/getStatusTrans",
		data    : {
			idtrans: v_idtrans
		},
		cache  : false,
		success: function (msg) {
			callback(msg);
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

