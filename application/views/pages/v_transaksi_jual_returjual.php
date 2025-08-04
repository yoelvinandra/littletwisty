<!DOCTYPE html>
<html lang="en">
  <style>
  
	div.scrollmenu {
	  white-space: nowrap;
	  transform  : rotateX(180deg);
	  height     : 50px;
	  overflow-x : auto;
	}

	div.scrollmenu a {
	  background-color: #f7f7f7;
	  display         : inline-block;
	  color           : grey;
	  border-top      : 1px solid;
	  border-left     : 1px solid;
	  border-right    : 1px solid;
	  border-radius   : 3px 3px 0px 0px;
	  text-align      : center;
	  padding         : 10px;
	  text-decoration : none;
	  transform       : rotateX(180deg);	
	}

	div.scrollmenu a:hover {
	  background-color: #2a3f54;
	  border-top      : 1px solid;
	  border-left     : 1px solid;
	  border-right    : 1px solid;
	  border-radius   : 3px 3px 0px 0px;
	  border-color    : #2a3f54;
	  color           : white;
	}

	#choose {
	  background-color: #2a3f54;
	  border-top      : 1px solid;
	  border-left     : 1px solid;
	  border-right    : 1px solid;
	  border-radius   : 3px 3px 0px 0px;
	  border-color    : #2a3f54;
	  color           : white;
	}
	
	#btn_simpan{
	  background-color: #2a3f54;
	  border          : 1px solid;
	  border-radius   : 3px 3px 3px 3px;
	  border-color    : #2a3f54;
	  color           : white;
	}
	
	#btn_simpan:hover{
	  background-color: white;
	  color           : black;
	}
	
	#choose_pembayaran{
	  background-color: #2a3f54;
	  border-radius   : 3px;
	  border-color    : #2a3f54;
	  color           : white;
	}
	
	.tab_bayar{
	  padding      : 10px 0px 10px 10px;
	  border       : 1px solid;
	  border-radius: 3px;
	  margin-left  : 10px;
	}
	
	#table_barang{
	  cursor:pointer;
	}

  </style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Transaksi Penjualan
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
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12" style="margin-bottom:10px;">
						<button class="btn btn-success" onclick="javascript:tambah()">Tambah/F2</button>
					</div>
					
					<div class="col-md-6 col-sm-6 col-xs-12" style="margin-bottom:10px; padding-right:30px;">
						<div class="input-group pull-right">
							<input type="text" class="form-control" id="tgl_awal_filter" style="width:90px; float:left;" name="tgl_awal_filter" readonly> 
							<span style="float:left; margin-top:5px;"> &nbsp; - &nbsp; </span>
							<input type="text" class="form-control" id="tgl_akhir_filter" style="width:90px	; float:left;" name="tgl_akhir_filter" readonly>&nbsp;
							<button class="btn btn-success" onclick="javascript:refresh()">Tampilkan</button>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-12">
					&nbsp;
				</div>
			</div>
        </div>
		<div class="nav-tabs-custom"  style="margin-top:-20px;">
            <ul class="nav nav-tabs" id="tab_transaksi">
				<li class="active"><a href="#tab_grid" data-toggle="tab">Grid</a></li>
				<li><a href="#tab_form" data-toggle="tab" onclick="tambah_ubah_mode()" >Tambah</a></li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_grid">
                    <div class="box-body ">
						<div class="input-group pull-right col-md-5 col-sm-5 col-xs-12" >
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
                                <th>Hari</th>
                                <th>Tgl</th>
                                <th>Customer</th>
                                <th>Grand Total</th>
                                <th width="35px"></th>
                                <th>Potongan</th>
                                <th>Grand Total %</th>
                                <th>Pembayaran</th>
                                <th>No</th>
                                <th>Catatan</th>
                                <th width="35px"></th>
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
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_form">
                    <div class="box-body">
					 <div class="col-md-12">
                        <div class="box-body">
							
							<div class="form-group col-md-8 col-sm-8 ol-xs-12">
								<input type="hidden" id="mode" name="mode">
								<input type="hidden" id="IDTRANS" name="IDTRANS">
								
								<input type="hidden" id="PEMBAYARAN" name="PEMBAYARAN">
								<input type="hidden" id="POTONGANPERSEN" name="POTONGANPERSEN">
								<input type="hidden" id="POTONGANRP" name="POTONGANRP">
								
								<div class="col-md-2 col-sm-2 ol-xs-12" style="padding: 0px">
									<label >No Jual</label>
								</div>
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px 0px 5px 0px">
									<input type="text" class="form-control" id="NOTRANS" name="NOTRANS" style="border:1px solid #B5B4B4; border-radius:1px;" placeholder="Auto Generate" readonly>
									<input type="hidden" class="form-control" id="IDTRANS" name="IDTRANS">
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; text-align:right;">
									<label>Lokasi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-12" style="padding: 0px 0px 5px 0px">
            						<select class="form-control" id="LOKASI" name="LOKASI" placeholder="Lokasi..." style="width:100%;">
            							<option value="0">-pilih lokasi-</option>
            							<?=comboGrid("model_master_lokasi")?>
            						</select>
								</div>
							</div>
							<div class="form-group col-md-4">
								
								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px">
									<label>Tgl Jual</label>
								</div>
								<div class="col-md-8 col-sm-8 col-xs-12"  style="padding: 0px 0px 5px 0px">
									<input type="text" class="form-control" id="TGLTRANS"  name="TGLTRANS" style=" border:1px solid #B5B4B4; border-radius:1px;" placeholder="Tgl PO">
								</div>
							</div>
							<div class="form-group col-md-8  col-sm-8 col-xs-12">
								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px">
									<label >Customer </label>
								</div>
								<div class="col-md-9  col-sm-9 col-xs-12"  style="padding: 0px 0px 5px 0px">
									<div class="input-group margin" style="padding:0; margin:0">
										<input type="text" class="form-control"  id="NAMACUSTOMER"  name="NAMACUSTOMER" style="border:1px solid #B5B4B4; border-radius:1px; float:left;" placeholder="Nama Customer">
										<input type="hidden" class="form-control" id="IDCUSTOMER" name="IDCUSTOMER">
										<div class="input-group-btn">
											<button type="button" id="btn_search" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal-customer"data-id="7">Search</button>
										</div>
									</div>	
								</div>
								<div class="col-md-2  col-sm-2 col-xs-12" style="padding: 0px">
									<label >&nbsp; </label>
								</div>
								<div class="col-md-6  col-sm-6 col-xs-12"  style="padding: 0px 0px 5px 0px">
									<textarea class="form-control" id="CATATANCUSTOMER" name="CATATANCUSTOMER" style="height:35px;" placeholder="Keterangan Customer"></textarea>
								</div>
								<div class="col-md-3  col-sm-3 col-xs-12"  style="padding: 0px 0px 5px 0px">
					                <textarea class="form-control" id="CATATAN" name="CATATAN"  style="height:35px;" placeholder="Catatan Tambahan"></textarea>
								</div>
							</div>
							<div class="form-group col-md-4 col-sm-4  col-xs-12">
								<input type="text" style="font-size:29pt; text-align:right; height:75px;  background:#2a3f54; border-color:#2a3f54; color:white;"   id="GRANDTOTALHEADER" class="form-control" value="0" readonly></input>
							</div>
							<!--
							<div class="form-group col-md-4 col-sm-4 col-xs-12" style="margin-bottom:20px; float:right;">
								
							</div>
							
							<div class="col-md-8 col-sm-8 col-xs-12" style="margin-bottom:20px;">
									<input type="text" class="form-control"  
									style="font-size:29pt;  height:60px;"  id="INPUTBARCODE" placeholder="BARCODE/` " value="">
							</div>
							-->
							
						  
						   <!-- /top tiles -->
						<!-- DETAIL -->
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div role="tabpanel" data-example-id="togglable-tabs" >

										<div class="col-md-6 col-sm-6 col-xs-6">
											<button type="button" id="btn_tambah" class="btn btn-success" onclick="tambahDetail()" data-toggle="modal" data-target="#modal-barang"  style="margin-right:35%">Barang/F8</button>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6" align="right" style="margin-left:0px;  margin-bottom:5px;">
											<button id="btn_simpan" style="padding:6px 10px 6px 10px;" onclick="simpan()" >Bayar/F9</button>	
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
																			<th style="vertical-align:middle; text-align:center;" width="100px" >Harga</th>
																			<th style="vertical-align:middle; text-align:center;" width="50px">Diskon(%)</th>
																			<th style="vertical-align:middle; text-align:center;" width="50px">Diskon(Rp)</th>
																			<th style="vertical-align:middle; text-align:center;" width="100px">Subtotal</th>
																	<th style="vertical-align:middle; text-align:center;" width="50px"> </th>
																</tr>
															</thead>
															<tbody class="table-responsive"></tbody>
														</table> 
													</div>
													</div>
												</div> 
												</div>
												
											<!-- HEADER -->
											</div>
										<!-- /footer content -->
											</div>
											<div class="x_panel" style="padding:0px; padding-top:10px; border-radius:2px; z-index;-1;">
												<div class="col-md-12 col-sm-12 col-xs-12 input-group form-group">
												<br>
													<div class="col-md-4  col-sm-4 col-xs-12">
														<div align="left" style="font-weight:bold">Pakai PPN</div>
														<select name="PAKAIPPN" class="form-control " id="PAKAIPPN" >
															<option value="TIDAK">Tidak</option>
															<option value="INCL">Include</option>
															<option value="EXCL">Exclude</option>
														</select>
															
													</div>	
													<div class="col-md-4 col-sm-4 col-xs-12">
														<div align="left" style="font-weight:bold">Total</div>
														<div class="input-group form-group">
															<input type="text" readonly class="form-control has-feedback-left col-md-2 col-sm-2 col-xs-2" id="TOTAL" placeholder="Total"  value="0">
															<div class="input-group-addon">
																	<i class="fa fa-money" style="font-size:8pt;"></i>
															</div>
														</div>
													</div>
													<div class="col-md-4 col-sm-4 col-xs-12" hidden>
														<div align="left" style="font-weight:bold">Service Charge</div>
														<div class="input-group form-group">	
															<input type="text" readonly class="form-control has-feedback-left col-md-2 col-sm-2 col-xs-2" id="SERVICECHARGE" placeholder="Service Charge" value="0">
															<div class="input-group-addon">
																	<i class="fa fa-money" style="font-size:8pt;"></i>
															</div>
														</div>
													</div>
													<div class="col-md-4 col-sm-4 col-xs-12">
														<div align="left" style="font-weight:bold">PPN</div>
														<div class="input-group form-group">
															<input type="text" readonly class="form-control has-feedback-left col-md-2 col-sm-2 col-xs-2" id="PPN" placeholder="PPN" value="0">
															<div class="input-group-addon">
																	<i class="fa fa-money" style="font-size:8pt;"></i>
															</div>
														</div>
													</div>
													<div class="col-md-2 col-sm-6 col-xs-12" hidden>
														<div align="left" style="font-weight:bold">Grand Total</div>
														<div class="input-group form-group">	
															<input type="text" readonly class="form-control has-feedback-left" id="GRANDTOTAL" placeholder="Grand Total" value="0">
															<div class="input-group-addon">
																	<i class="fa fa-money" style="font-size:8pt;"></i>
															</div>
														</div>
													</div>
												</div>
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
														<th>Harga</th>
													</tr>
												</thead>
											</table>
											
											<input type="text" class="form-control has-feedback-left" id="jml" onkeyup="return numberInput(event,'',1)" placeholder="Jml" value="1" style="position:absolute; width:24.5%; left:73%; top:13px; ">
											<div width="100%">
											<input type="text" class="form-control " id="namaservice" placeholder="Keterangan Biaya Lain" style="width:60%;">
								            <div style="width:39.5%; float:right;">   
    											<div style="width:49%;margin-top:-33.8px; float:left;"><input type="text" class="form-control " id="biaya" onkeyup="return numberInput(event,'',1)" placeholder="Biaya" style="width:97.5%;"  value="0"></div>
    											<button type="button" id="btn_biaya" class="btn btn-success" onclick="tambahBiaya()" style=" margin-top:-33.8px; float:left;">Tambah Biaya</button>
								            </div>
											</div>
										</div>
									</div>
									</div>
								</div>
							<!--MODAL CUSTOMER-->
								<div class="modal fade" id="modal-customer" >
									<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-body">
											<table id="table_customer" class="table table-bordered table-striped table-hover display nowrap">
												<thead>
													<tr>
														<th></th>
														<th></th>
														<th></th>
														<th>Nama</th>
														<th>Alamat</th>
														<th>Telp</th>
														<th>Catatan</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
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
	
	</div>
</section>
<input type="hidden" id="status">
<input type="hidden" id="jenis_harga">
<script>
var pointertrans = 0;
var pointerbayar = 0;
var counttrans = 0;
var countdetail = 0;
var qty=0;
var mode='';
var base_url = '<?=base_url()?>';
var ppnrp = [];
var readonlyHarga = 'readonly';
var readonlyPotongan = '';
$(document).ready(function(){
	//$("#GRANDTOTALHEADER").css('width',(screen.width*23/100)+"px");
	//$("#CATATAN").css('width',(screen.width*23/100)+"px");
	
	$('body').keyup(function(e){
		hotkey(e);
	});
	
	$("#NAMACUSTOMER").attr("readonly","readonly");
	
	$('#TOTAL').number(true,0);
	
	$('#POTONGANRPP').number(true,0);
	
	$('#POTONGANPERSENP').number(true,0);
	
	$('#PPN').number(true,0);
	
	$('#SERVICECHARGE').number(true,0);
	
	$('#GRANDTOTAL').number(true,0);
	
	$('#GRANDTOTALHEADER').number(true,0);
	
	$('#PEMBAYARANP').number(true,0);
	
	$('.select2').select2({
		  theme: "classic"
	});
	
	//TAMBAH
	$('#TGLTRANS, #tgl_awal_filter, #tgl_akhir_filter').datepicker({
		format: 'yyyy-mm-dd'
	});
	$("#tgl_awal_filter").datepicker('setDate', "<?=TGLAWALFILTER?>");
	$("#tgl_akhir_filter").datepicker('setDate', "<?=TGLAKHIRFILTER?>");
	$("#TGLTRANS").datepicker('setDate', new Date());
	$("#status").val('I,S,P,D');
	//-------
	
	$('#PAKAIPPN').on('change', function () {
		hitung_ppn(countdetail);
	});
	
	$("#modal-barang").on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input', $("#table_barang").DataTable().table().container()).focus();
    });
	
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
			url    : base_url+'Penjualan/Transaksi/Penjualan/dataGrid/',
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
            { data: 'IDPENJUALAN',visible: false,},	
            { data: 'HARI',className:"text-center"},	
            { data: 'TGLTRANS',className:"text-center"},	
            { data: 'NAMACUSTOMER' ,className:"text-left"},
            { data: 'GRANDTOTAL', render:format_number, className:"text-right"},
            { data: 'POTONGANPERSEN',visible: false,},
            { data: 'POTONGANRP', render:format_number, className:"text-right"},
            { data: 'GRANDTOTALDISKON', render:format_number, className:"text-right"},
            { data: 'PEMBAYARAN', render:format_number, className:"text-right"},
            { data: 'KODEPENJUALAN' ,className:"text-center"},
            { data: 'CATATAN' ,className:"text-left"},
            { data: 'STATUS', visible: false,},      
        ],
		columnDefs: [ 
			{
                "targets": 0,
                "data": null,
                "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash'></i></button> <button id='btn_cetak' class='btn btn-warning'><i class='fa fa-print' ></i></button>"	
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
	
	//TABLE CUSTOMER
	$("#table_customer").DataTable({
        'retrieve'    : true,
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"dom"		  : '<"pull-left"f><"pull-right"l>tip',
		ajax		  : {
			url    : base_url+'Master/Data/Customer/comboGridTransaksi', // Master/Data/Barang/loadData
			dataSrc: "rows",
			type   : "POST",
		},
		language: {
			search: "Cari",
			searchPlaceholder: "Nama Customer"
		},
        columns:[
            { data: 'ID' ,visible: false,},
            { data: 'KONSINYASI' ,visible: false,},
            { data: 'MEMBER' ,visible: false,},
            { data: 'NAMA' },
			{ data: 'ALAMAT'},
			{ data: 'TELP' },
			{ data: 'CATATANCUSTOMER' },
        ],
		
    });
	
	//BUAT NAMBAH BARANG BIASA
	$('#table_customer tbody').on('click', 'tr', function () {
		var row = $('#table_customer').DataTable().row( this ).data();
		$("#modal-customer").modal('hide');	
		
		
		
		if(row.KODE == "SHOPEE"){
			$("#jenis_harga").val("5");
		}
		else if(row.KODE == "GRAB"){
			$("#jenis_harga").val("3");
		}
		else if(row.KODE == "GOJEK"){
			$("#jenis_harga").val("4");
		}
		else
		{
			$("#jenis_harga").val("2");
		}
		
		var alamat;
		$("#NAMACUSTOMER").val(row.NAMA);
		$("#IDCUSTOMER").val(row.ID);
		$("#CATATANCUSTOMER").attr('placeholder',row.CATATANCUSTOMER);
		
		if(row.KONSINYASI == 1){readonlyHarga='';}else{readonlyHarga='readonly';}
		if(row.MEMBER == 1){readonlyPotongan='readonly';$("#POTONGANPERSEN").val(row.DISKONMEMBER);}else{readonlyPotongan=''; $("#POTONGANRP").val(0); $("#POTONGANPERSEN").val(0);}
		
		
// 		if(row.CATATANCUSTOMER == "" || row.CATATANCUSTOMER == null)
// 		{
// 			$("#CATATANCUSTOMER").attr("readonly","readonly");
// 		}
// 		else
// 		{
// 			$("#CATATANCUSTOMER").removeAttr("readonly");
// 		}
		
		for(var i = 0 ; i < countdetail; i++)
		{	
			$("#detailtab"+i+"").remove();
		}
			
		countdetail= 0;
		hitung_ppn(countdetail);
		
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
			url    : base_url+'Master/Data/Barang/comboGridTransaksi',   // Master/Data/Barang/loadData
			dataSrc: "rows",
			type   : "POST",
			data    : function(e){
					e.transaksi 	 = getJenisHarga();
					e.mode 			 = "BIASA";
			}
		},
		language: {
			search           : "Cari",
			searchPlaceholder: "Nama Produk"
		},
        columns:[
			{ data: 'ID', visible: false,},
            { data: 'KODE' },
            { data: 'NAMA' },
			{ data: 'HARGA', render:format_uang, className:"text-right"},
			{ data: 'STOK', visible:false},
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
	
	//BUAT NAMBAH BARANG HBS QTY WAKTU DI ENTER
	$('#jml').bind('keypress', function(e){
		if (e.keyCode == 13) {
			
			var jml = parseInt($("#jml").val());
				var value = $('#table_barang tbody tr td').html();
				
			tambah_barang_barcode(value,jml,"LANGSUNG");
			$("#modal-barang").modal('hide');
			
			$("#jml").val(1);
			var table = $('#table_barang').DataTable();
			table.search("").draw();
		}
	});
	
	//BUAT NAMBAH BARANG DARI BARCODENYA
	$('#INPUTBARCODE').bind('keypress', function(e){
		if (e.keyCode == 13) {
			var a          = e.target;
			var val        = a.value;
			var barcodeVal = val.split("*");
			
			var qty 	= 0;
			var barcode = "";
			
			if(barcodeVal.length == 1)
			{
				qty 	 = 1;
				barcode  = barcodeVal[0];
			}
			else
			{
				qty 	 = barcodeVal[0];
				barcode  = barcodeVal[1];
			}
		
			
			tambah_barang_barcode(barcode,qty,"BARCODE");
			
			$("#jml").val(1);
			var table = $('#table_barang').DataTable();
			table.search("").draw();
		}
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

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function hotkey(e){
	if(e.keyCode == 113) // F2
	{
		tambah();
	}
	else if(e.keyCode == 119) // F8
	{
		$("#modal-barang").modal('show');
	}
	else if(e.keyCode == 120) // F9
	{
		simpan();
	}
	else if(e.keyCode == 192) //`
	{
		$("#INPUTBARCODE").focus();
	}
	
}

function tambahBiaya(){
    $.ajax({
		type    : 'POST',
		url     : base_url+'Master/Data/Barang/getDataBarang',
		data    : {barcode:"XXXXX",qty:"1",mode:"LANGSUNG"},
		dataType: 'json',
		success : function(msg){
        	var row = msg.rows;
        	row.HARGA = $("#biaya").val();
	        row.NAMA  = $("#namaservice").val();
            tambah_barang(row); 
        	$("#modal-barang").modal('hide');	
        	$("#jml").val(1);
        	var table = $('#table_barang').DataTable();
        	table.search("").draw();
    	}
    });
}

function getStatus(){
	return $("#status").val();
}

function getJenisHarga(){
	return $("#jenis_harga").val();
}

function refresh(){
    $("#dataGrid").DataTable().ajax.reload();
}	
//---------	
// $("#LOKASI").change( function () {
//     $.ajax({
// 		type    : 'POST',
// 		url     : base_url+'Master/Data/Lokasi/gantiSessionLokasi',
// 		data    : {lokasi:$("#LOKASI").val()},
// 		dataType: 'json',
// 		success : function(msg){
// 			window.location.reload(msg); 
// 		}
// 	});
// });


function tambah_barang(row){
	
	var jml         = parseInt($("#jml").val());
	var ada         = false;
	var simpanIndex = -1;
	var temp_jumlah = 0;
	
	for(var i = 0; i < countdetail;i++)
	{
		if($("#KODE"+i).html() == row.KODE && row.STOK == 1)
		{		
			ada = true;
			simpanIndex = i;
		}
	}
	
	//MASUKAN DATA DALAM GRID
	if(ada)
	{
		//JUMLAH BARANG HASIL UPDATE DI GRID
		temp_jumlah = (parseInt($("#JUMLAH"+simpanIndex+" input").val())+parseInt(jml));
		if(temp_jumlah > 0)
		{			
			$("#JUMLAH"+simpanIndex+" input").val(parseInt($("#JUMLAH"+simpanIndex+" input").val())+parseInt(jml));
			row.DISKON = $("#DISKON"+simpanIndex+" input").val();
			row.DISKONRP = $("#DISKONRP"+simpanIndex+" input").val();
			hitung_diskon(row.DISKON,parseInt(row.HARGA),simpanIndex);

			if($("#DISKON"+simpanIndex+" input").val() == 0){	
				hitung_diskon_rupiah(parseInt(row.DISKONRP),parseInt(row.HARGA),countdetail);	
			}		
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
				+row.NAMA+"</td><td width='50px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+jml+"'></td><td width='50px' id='SATUAN"+countdetail+"'align='center'>"+row.SATUANKECIL+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' "+readonlyHarga+" onkeyup='return numberInput(event,"+countdetail+",2)'></td><td width='50px' id='DISKON"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",2)' value='"+row.DISKON+"'></td><td width='50px' id='DISKONRP"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",9)' value='"+row.DISKONRP+"'></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right'><input style='width:100px;'  type='text' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
				$("#JUMLAH"+countdetail+" input").number(true, 0);
				$("#HARGA"+countdetail+" input").number(true, 2);
				$("#HARGA"+countdetail+" input").val(parseInt(row.HARGA));
				$("#DISKONRP"+countdetail+" input").number(true, 2);
				$("#SUBTOTAL"+countdetail+" input").number(true, 2);
				hitung_diskon(row.DISKON,parseInt(row.HARGA),countdetail);

				if($("#DISKON"+simpanIndex+" input").val() == 0){
					hitung_diskon_rupiah(parseInt(row.DISKONRP),parseInt(row.HARGA),countdetail);	
				}
				countdetail++;
		}
		else
		{
			alert("Jumlah Barang Tidak Boleh Nol");
		}
	}
	
	if(temp_jumlah > 0)
	{		
		hitung_ppn(countdetail);
	}
}

function tambah_barang_barcode(val,jml,mode)
{	
	if(jml.length > 3){
		
		Swal.fire({
			title            : 'Jumlah Barang Tidak Boleh Lebih Dari 999',
			type             : 'warning',
			showConfirmButton: false,
			timer            : 1500
		});
	}
	else
	{
		$.ajax({
			type    : 'POST',
			url     : base_url+'Master/Data/Barang/getDataBarang',
			data    : {barcode:val,qty:jml,mode:mode},
			dataType: 'json',
			success : function(msg){
					//10*B00001
					//BARANG PERNAH DIINPUTKAN ATAU BELUM
					var ada         = false;
					var simpanIndex = -1;
					var temp_jumlah = 0;
					
					if(msg.rows == null)
					{
						Swal.fire({
							title            : 'Tidak Ada Barang Dengan Barcode Tersebut',
							type             : 'warning',
							showConfirmButton: false,
							timer            : 1500
						});
					}
					else
					{
					
						for(var i = 0; i < countdetail;i++)
						{
							if($("#NAMA"+i).html() == msg.rows.NAMA)
							{							
								ada = true;
								simpanIndex = i;
							}
						}
						
						//MASUKAN DATA DALAM GRID
						if(ada)
						{
							//JUMLAH BARANG HASIL UPDATE DIGRID
							temp_jumlah = (parseInt($("#JUMLAH"+simpanIndex+" input").val())+parseInt(msg.rows.QTY));
							if(temp_jumlah > 0)
							{
								$("#JUMLAH"+simpanIndex+" input").val(parseInt($("#JUMLAH"+simpanIndex+" input").val())+parseInt(msg.rows.QTY));
								msg.rows.DISKON = $("#DISKON"+simpanIndex+" input").val();
								msg.rows.DISKONRP = $("#DISKONRP"+simpanIndex+" input").val();
								hitung_diskon(msg.rows.DISKON,parseInt(msg.rows.HARGA),simpanIndex);

								if($("#DISKON"+simpanIndex+" input").val() == 0){
									hitung_diskon_rupiah(parseInt(msg.rows.DISKONRP),parseInt(msg.rows.HARGA),simpanIndex);	
								}
														
							}
							else
							{
								Swal.fire({
									title            : 'Jumlah Barang Tidak Boleh Nol',
									type             : 'warning',
									showConfirmButton: false,
									timer            : 1500
								});
							}						
						}
						else
						{	
							temp_jumlah = msg.rows.QTY;
							if(temp_jumlah > 0)
							{
								 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+msg.rows.ID+"</td><td id='KODE"+countdetail+"' hidden>"+msg.rows.KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
								+msg.rows.NAMA+"</td><td width='50px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+jml+"'></td><td width='50px' id='SATUAN"+countdetail+"'align='center'>"+msg.rows.SATUANKECIL+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' "+readonlyHarga+" onkeyup='return numberInput(event,"+countdetail+",2)'></td><td width='50px' id='DISKON"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",2)' value='"+msg.rows.DISKON+"'></td><td width='50px' id='DISKONRP"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",9)' value='"+msg.rows.DISKONRP+"'></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right'><input style='width:100px;'  type='text' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
							    $("#JUMLAH"+countdetail+" input").number(true, 0);
							    $("#HARGA"+countdetail+" input").number(true, 2);
				                $("#HARGA"+countdetail+" input").val(parseInt(msg.rows.HARGA));
								$("#DISKONRP"+countdetail+" input").number(true, 2);
								$("#SUBTOTAL"+countdetail+" input").number(true, 2);
								hitung_diskon(msg.rows.DISKON,parseInt(msg.rows.HARGA),countdetail);

								if($("#DISKON"+countdetail+" input").val() == 0){
									hitung_diskon_rupiah(parseInt(msg.rows.DISKONRP),parseInt(msg.rows.HARGA),countdetail);
								}
								countdetail++;
							}
							else
							{
								
								Swal.fire({
									title            : 'Jumlah Barang Tidak Boleh Nol',
									type             : 'warning',
									showConfirmButton: false,
									timer            : 1500
								});
							}
						}					
						if(temp_jumlah > 0)
						{
							hitung_ppn(countdetail);
						}
					}
			}
		});
	}
	
	$('#INPUTBARCODE').val('');
}

function tambah(){
    readonlyHarga = "readonly";
    readonlyPotongan = "";
	$("#btn_simpan, #btn_tambah, #LOKASI, #TGLTRANS, #btn_search").css('filter', '');
	$("#btn_simpan, #btn_tambah, #LOKASI, #TGLTRANS, #btn_search").removeAttr('disabled');
					
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
		    	$("#mode").val('tambah');
				$("#jenis_harga").val("2");
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
    readonlyHarga = "readonly";
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
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.UBAH==1) {
			$("#mode").val('ubah');
			
			$('.table-responsive').html("");
			countdetail = 0;
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Ubah');
			
			get_status_trans("Penjualan/Transaksi/Penjualan",row.IDPENJUALAN,function(data){
				if(data.status != "I" && data.status != "S")
				{
					$("#btn_simpan, #btn_tambah").css('filter', 'grayscale(100%)');
					$("#btn_simpan, #btn_tambah").attr('disabled', 'disabled');
				}
				else
				{
				    $("#LOKASI, #TGLTRANS, #btn_search").css('filter', 'grayscale(100%)');
					$("#LOKASI, #TGLTRANS, #btn_search").attr('disabled', 'disabled');
				    
					$("#btn_simpan, #btn_tambah").css('filter', '');
					$("#btn_simpan, #btn_tambah").removeAttr('disabled');
				}
			});
						
			$.ajax({
				type    : 'POST',
				url     : base_url+'Penjualan/Transaksi/Penjualan/loadDetail/',
				data    : {id:row.IDPENJUALAN,mode:"ubah"},
				dataType: 'json',
				success : function(msg){
						//TOTAL
						var total = 0;
						var ppn   = 0;
		                
						
						$("#LOKASI").val(row.IDLOKASI);
						$("#NOTRANS").val(row.KODEPENJUALAN);
						$("#IDTRANS").val(row.IDPENJUALAN);
						$("#IDCUSTOMER").val(msg[0].IDCUSTOMER);
						$("#NAMACUSTOMER").val(msg[0].NAMACUSTOMER);
						$("#CATATANCUSTOMER").val(row.CATATANCUSTOMER);
						$("#TGLTRANS").val(row.TGLTRANS);
						$("#CATATAN").val(row.CATATAN);
						for(var i = 0; i < msg.length;i++)
						{
							if(msg[i].PAKAIPPN == 0){
								$("#PAKAIPPN").val("TIDAK");
							}
							else if(msg[i].PAKAIPPN == 1){
								$("#PAKAIPPN").val("EXCL");
							}
							else if(msg[i].PAKAIPPN == 2){
								$("#PAKAIPPN").val("INCL");
							}
							
							var pakaippn = $("#PAKAIPPN").val();
							
							 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+msg[i].ID+"</td><td id='KODE"+countdetail+"' hidden>"+msg[i].KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
									+msg[i].NAMA+"</td><td width='50px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+parseInt(msg[i].QTY)+"'></td><td width='50px' id='SATUAN"+countdetail+"' align='center'>"
									+msg[i].SATUANKECIL+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' "+readonlyHarga+" onkeyup='return numberInput(event,"+countdetail+",2)'></td><td width='50px' id='DISKON"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",2)' value='"+msg[i].DISKON+"'></td><td width='50px' id='DISKONRP"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",9)' value='"+msg[i].DISKONRP+"'></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right'><input style='width:100px;'  type='text' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
							$("#JUMLAH"+countdetail+" input").number(true, 0);
							$("#HARGA"+countdetail+" input").number(true, 2);
				            $("#HARGA"+countdetail+" input").val(parseInt(msg[i].HARGA));
							$("#DISKONRP"+countdetail+" input").number(true, 2);
							$("#SUBTOTAL"+countdetail+" input").number(true, 2);
							hitung_diskon(msg[i].DISKON,parseInt(msg[i].HARGA),countdetail);
							if($("#DISKON"+countdetail+" input").val() == 0){
								hitung_diskon_rupiah(parseInt(msg[i].DISKONRP),parseInt(msg[i].HARGA),countdetail);
							}
							countdetail++; 
							
							if(pakaippn == "EXCL")
							{
								if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
								{				
									total+= parseInt($('#SUBTOTAL'+i+'  input').val());
									ppnrp[i] = (parseInt($('#SUBTOTAL'+i+'  input').val())*("<?=$PPNPERSEN?>"/100)).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
									ppn  += ppnrp[i];
								}
								
								if(i == msg.length-1)
								{
									//TOTAL
									$('#TOTAL').val(total);
									
									//PPN
									$('#PPN').val(ppn);
								}
								
								//GRANDTOTAL
							    $('#GRANDTOTAL').val((parseInt($('#TOTAL').val())+(parseInt($('#PPN').val()))));
		                        $('#GRANDTOTALHEADER').val((parseInt($('#GRANDTOTAL').val())));
							}
							else if(pakaippn == "INCL")
							{
								if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
								{				
									total+= parseInt($('#SUBTOTAL'+i+'  input').val());
									ppnrp[i] = (parseInt($('#SUBTOTAL'+i+'  input').val())/11).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
									ppn  += ppnrp[i];
								}
								
								if(i == msg.length-1)
								{
									//TOTAL
									$('#TOTAL').val(total);
									
									//PPN
									$('#PPN').val(ppn);
								}
								
								//GRANDTOTAL
								$('#GRANDTOTAL').val((parseInt($('#TOTAL').val())));
		                        $('#GRANDTOTALHEADER').val((parseInt($('#GRANDTOTAL').val())));
							}
							else
							{
								if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
								{				
									total+= parseInt($('#SUBTOTAL'+i+'  input').val());
									ppnrp[i] = 0;
								}
								
								if(i == msg.length-1)
								{
									//TOTAL
									$('#TOTAL').val(total);
									
									//PPN
									$('#PPN').val(0);
								}
								
								$('#POTONGANPERSEN').val(parseInt(row.POTONGANPERSEN));
								$('#POTONGANRP').val(parseInt(row.POTONGANRP));
								//GRANDTOTAL
								$('#GRANDTOTAL').val((parseInt($('#TOTAL').val())));
		                        $('#GRANDTOTALHEADER').val((parseInt($('#GRANDTOTAL').val())));
							}
						}
						
				
						$('#PEMBAYARAN').val(parseInt(row.PEMBAYARAN));
						$('#GRANDTOTALP').val(parseInt(row.GRANDTOTALDISKON));
						
                        if(row.KONSINYASI == 1){readonlyHarga = "";} else{readonlyHarga = "readonly";}
                        if(row.MEMBER == 1){readonlyPotongan='readonly'; }else{readonlyPotongan='';}
                        
						getKembali();
						
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
	get_status_trans("Penjualan/Transaksi/Penjualan",row.IDPENJUALAN, function(data){
		if (data.status=='I' || data.status=='S') {
			get_akses_user('<?=$kodemenu?>', function(data){
				if (data.HAPUS==1) {
					$('#ALASANPEMBATALAN').val("");
					$("#modal-alasan").modal('show');
					$("#btn_batal").val(JSON.stringify(row));
					$("#KETERANGAN_BATAL").html("Apa anda yakin akan membatalkan transaksi "+row.KODEPENJUALAN+" ?");
				} else {
					Swal.fire({
						title            : 'Anda Tidak Memiliki Hak Akses',
						type             : 'error',
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
			url     : base_url+"Penjualan/Transaksi/Penjualan/batalTrans",
			data    : "idtrans="+row.IDPENJUALAN + "&kodetrans="+row.KODEPENJUALAN + "&alasan="+alasan,
			cache   : false,
			success : function(msg){
				if (msg.success) {
					Swal.fire({
						title            : 'Transaksi dengan kode '+row.KODEPENJUALAN+' telah dibatalkan',
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
			get_status_trans("Penjualan/Transaksi/Penjualan",row.IDPENJUALAN, function(data){
				if (data.status!='D') {
					$.ajax({
						type    : 'POST',
						dataType: 'json',
						url     : base_url+'Penjualan/Transaksi/Penjualan/ubahStatusJadiSlip',
						data    : {idtrans: row.IDPENJUALAN, kodetrans: row.KODEPENJUALAN},
						cache   : false,
						success : function(msg){
							if (msg.success) {
								$("#dataGrid").DataTable().ajax.reload();
								window.open(base_url+"Penjualan/Transaksi/Penjualan/cetak/"+row.IDPENJUALAN, '_blank');
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
				title            : 'Anda Tidak Memiliki Hak Akses',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
		}
	});	
}

function tambahDetail(){
	$('#table_barang_filter input').focus();
	$("#table_barang").DataTable().ajax.reload();
}

function ubahDetail(e){
	alert(e);
}

function hapusDetail(e){	
	$("#detailtab"+e+"").remove();
	hitung_ppn(countdetail);
}

function getPotongan(jenis) {
    if(jenis == "PERSEN")
    {
        if(parseInt($('#POTONGANPERSENP').val()) > 100)
        {
            Swal.fire({
				title            : 'Potongan tidak boleh lebih besar dari 100',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
        }
        else if(parseInt($('#POTONGANPERSENP').val()) > 0)
        {
            $('#POTONGANRPP').val(parseInt(parseInt($('#POTONGANPERSENP').val()) / 100 * (parseInt($('#GRANDTOTAL').val()))));
        }
        else if(parseInt($('#POTONGANPERSENP').val()) < 0)
        {
            Swal.fire({
				title            : 'Potongan tidak boleh lebih kecil dari nol',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
        }
    }
    else
    {
         $('#POTONGANPERSENP').val(0);
         
        if(parseInt($('#POTONGANRPP').val()) < 0)
        {
            Swal.fire({
				title            : 'Potongan Rupiah tidak boleh lebih kecil dari nol',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
        }
    }
    
    
    var htmlAdd = "Pesanan Anda : \n";
   
	if("<?=$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN']?>" == "TUMTUMMY")
	{
    	for (var i=0;i<countdetail;i++) {
        		if(typeof $('#NAMA'+i).html() != "undefined") // BARANG TELAH DIHAPUS
        		{
        			
        			var nama         = $("#NAMA"+i).html();
        			var jml          = $("#JUMLAH"+i+" input").val();
        			var harga        = $("#HARGA"+i+" input").val();
        			var subtotal     = $("#SUBTOTAL"+i+" input").val();
        			
        			if(nama.includes("KROKET"))
        			{
        			    htmlAdd += ((parseInt(jml) / 2)+"x "+capitalizeFirstLetter(nama.toLowerCase())+" @"+(parseInt(harga) * 2)+" = "+subtotal+"\n");
        			}
        			else
        			{
        			    htmlAdd += (jml+"x "+capitalizeFirstLetter(nama.toLowerCase())+" @"+harga+" = "+subtotal+"\n");
        			}
        				
        		}
        }
	
        htmlAdd += "\nTotal Pesanan = "+$("#GRANDTOTAL").val();
        if(parseInt($('#POTONGANRPP').val()) > 0)
        {
         htmlAdd += "\nPotongan = "+$("#POTONGANRPP").val();
        }
         htmlAdd += "\nTotal Setelah Diskon = "+$("#GRANDTOTALP").val();
        htmlAdd += "\nTotal Pembayaran = "+$("#PEMBAYARANP").val()+"\n\nTf ke BCA 0881931418 a/n Irene.\nTerima Kasih";
	}
    	
    $("#ringkasan").val(htmlAdd);
    
    
    
	$('#GRANDTOTALP').val((parseInt($('#GRANDTOTAL').val())) - (parseInt($('#POTONGANRPP').val())));
	
	if(parseInt($('#POTONGANRPP').val()) > parseInt($('#GRANDTOTAL').val()))
	{
	     Swal.fire({
				title            : 'Potongan Harga tidak boleh lebih besar dari Pembayaran',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
	}
	
	getKembali();
}

function copynow() {
  // Get the text field
  var textField = document.getElementById("ringkasan");

  //textField.innerText = $("#ringkasan").val();
//   document.body.appendChild(textField);
    textField.select();
    document.execCommand('copy');

  // Alert the copied text
  alert("Salin Berhasil");
} 

function simpan(){
	
    	var jmlData = 0;
    	var row = [];
    	for (var i=0;i<countdetail;i++) {
    		if(typeof $('#NAMA'+i).html() != "undefined") // BARANG TELAH DIHAPUS
    		{
    			//KHUSUS DISKON KURS
    			var totaldisc   = 0;
    			var discPersen  = $("#DISKON"+i+" input").val();
    			var hargaDiskon = $("#HARGA"+i+" input").val();
    			
    			if (discPersen != "0") {
    				
    				discPersen = discPersen.toString().split("+");
    
    				for(var j=0;j<discPersen.length;j++){
    					if(discPersen[j]!= "" && discPersen[j] <= 100 && discPersen[j]>0){
    						discPersen[j] = parseFloat(discPersen[j]);
    						disc = +((discPersen[j] * hargaDiskon / 100).toFixed(<?= $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>));
    						totaldisc += disc;
    					}
    				}
    			}
    
    			row[jmlData] = 
    				{
    					idbarang     : $("#ID"+i).html(),
    					namabarang   : $("#NAMA"+i).html(),
    					jml          : $("#JUMLAH"+i+" input").val(),
    					harga        : $("#HARGA"+i+" input").val(),
    					hargakurs    : $("#HARGA"+i+" input").val(),
    					discpersen   : $("#DISKON"+i+" input").val(),
    					disckurs     : $("#DISKONRP"+i+" input").val(),
    					disc	     : $("#DISKONRP"+i+" input").val(),
    					subtotal     : $("#SUBTOTAL"+i+" input").val(),
    					subtotalkurs : $("#SUBTOTAL"+i+" input").val(),
    					pakaippn     : $("#PAKAIPPN").val(),
    					satuan       : $("#SATUAN"+i).html(),
    					ppnrp        : ppnrp[i],
    					IDPENJUALAN  : $("#IDTRANS").val(),
    					KODEPENJUALAN: $("#NOTRANS").val(),
    				};
    			jmlData++;
    		}
    	}
    	if($("#LOKASI").val() == 0)
    	{
    		Swal.fire({
                title            : "Lokasi harus diisi",
                type             : 'warning',
                showConfirmButton: false,
                timer            : 1500
    		});
    	}
    	else if(jmlData == 0)
    	{
    		Swal.fire({
                title            : "Tidak Ada Data Barang",
                type             : 'error',
                showConfirmButton: false,
                timer            : 1500
           });
    	}	
    	else if($("#GRANDTOTAL").val() > $("#PEMBAYARANP").val())
    	{
    		Swal.fire({
                title            : "Pembayaran Kurang",
                type             : 'warning',
                showConfirmButton: false,
                timer            : 1500
    		});
    	}		
    	else if($("#PEMBAYARANP").val() == 0)
    	{
    		Swal.fire({
                title            : "Pembayaran harus diisi",
                type             : 'warning',
                showConfirmButton: false,
                timer            : 1500
    		});
    	}	
    	else
    	{
    	        var htmlAdd = "";
    	    
    			Swal.fire({
    			  title: '<strong style="font-size:20pt;">Pembayaran</strong>',
    			  icon: 'info',
    			  width: '700px',
    			  html: '<div class="row">\
        			         <div class="col-sm-6">\
            			        <div style="text-align:center;">\
            					<div style="text-align:left; font-weight:bold; font-size:12pt;">Potongan</div>\
            					<input type="text" style="font-size:25pt; text-align:right; height:55px; width:25%; float:left;"  class="form-control has-feedback-left" id="POTONGANPERSENP" placeholder="%" onkeyup="getPotongan(\'PERSEN\')" value="0" '+readonlyPotongan+'>\
            					<div style="width:15%; float:left; text-align:left; font-weight:bold; font-size:25pt; margin-top:6px;">%&nbsp;</div>\
            					<input type="text" style="font-size:25pt; text-align:right; height:55px;  width:60%;"  class="form-control has-feedback-left" id="POTONGANRPP" placeholder="" onkeyup="getPotongan(\'RP\')" value="0" '+readonlyPotongan+'>\
            					<br>\
            			        <div style="text-align:left; font-weight:bold; font-size:12pt;">Grand Total &nbsp;&nbsp;<i>Sesudah Potongan</i></div>\
            					<input type="text" style="font-size:25pt; text-align:right; height:55px; background:#2a3f54; border-color:#2a3f54; color:white;"  class="form-control has-feedback-left" id="GRANDTOTALP" readonly>\
            					<br>\
            					<div style="text-align:left; font-weight:bold; font-size:12pt;">Pembayaran</div>\
            					<input type="text" style="font-size:25pt; text-align:right; height:55px;"  class="form-control has-feedback-left" id="PEMBAYARANP" placeholder="Pembayaran" onkeyup="return getKembali()" value="0">\
            					<br>\
        					    <div style="text-align:left; font-weight:bold; font-size:12pt;">Kembali</div>\
            					<input type="text" style="font-size:25pt; text-align:right; height:55px;"  class="form-control has-feedback-left" id="KEMBALI" placeholder="Kembali" value="0"  readonly>\
        					    </div>\
        					</div>\
        					<div class="col-sm-6">\
        					    <div style="text-align:left; font-weight:bold; font-size:12pt;">Catatan</div>\
            					<textarea id="ringkasan" style="text-align:left; width:100%; height:302px; color:black;"></textarea>\
            					<br>\
            					<div class="btn btn-success" style="margin-top:5px;" onclick="javascript:copynow()">Salin Catatan</div>\
            					</div>\
        					</div>\
    					</div>\
    					',
    			  showCloseButton: true,
    			  showCancelButton: false,
    			  focusConfirm: false,
    			  confirmButtonText:
    				'Bayar Sekarang'
    			}).then((result) => {
    			  /* Read more about isConfirmed, isDenied below */
    			  if (result.value) {
    			      
    			    var valid = true;
                    if(parseInt($('#PEMBAYARANP').val()) < parseInt($('#GRANDTOTALP').val()))
                    {
                        valid = false;
                        Swal.fire({
                    		title            : 'Pembayaran tidak boleh lebih kecil dari Grand Total sesudah potongan',
                    		type             : 'error',
                    		showConfirmButton: false,
                    		timer            : 1500
                    	 });
                    }
                    
                    if(valid)
                    {
        				$.ajax({
        					type    : 'POST',
        					url     : base_url+'Penjualan/Transaksi/Penjualan/simpan/', 
        					data    : {	
        								IDPENJUALAN  		: $("#IDTRANS").val(),
        								KODEPENJUALAN		: $("#NOTRANS").val(),
        								TGLTRANS	 		: $("#TGLTRANS").val(),
        								IDCUSTOMER   		: $("#IDCUSTOMER").val(),
        								IDLOKASI     		: $("#LOKASI").val(),
        								TOTAL        		: $("#TOTAL").val(),
        								POTONGANPERSEN   	: $("#POTONGANPERSENP").val(),
        								POTONGANRP   		: $("#POTONGANRPP").val(),
        								PPNRP        		: $("#PPN").val(),
        								GRANDTOTAL   		: $("#GRANDTOTAL").val(),
        								GRANDTOTALDISKON   		: $("#GRANDTOTALP").val(),
        								PEMBAYARAN   		: $("#PEMBAYARANP").val(),
        								CATATAN 	 		: $("#CATATAN").val(),
        								CATATANCUSTOMER 	: $("#CATATANCUSTOMER").val(),
        								
        								data_detail  		: JSON.stringify(row),
        								mode         		: $("#mode").val(),
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
        							var row ={};
        							row = msg.row;
        							
        							//cetak(row);
        							
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
    			})
    			$("#PEMBAYARANP").focus();
    			$('#GRANDTOTALP').number(true,0);
    			$('#PEMBAYARANP').number(true,0);
    			$('#POTONGANRPP').number(true,0);
    			$('#POTONGANPERSENP').number(true,0);
    	
    			//PEMBAYARAN
    			$('#GRANDTOTALP').val(parseInt($('#GRANDTOTAL').val()));
    			$('#POTONGANPERSENP').val(parseInt($('#POTONGANPERSEN').val()));
    			if(parseInt($('#POTONGANPERSENP').val()) > 0){
    			    getPotongan('PERSEN');
    			}
    			else
    			{ 
    			    $('#POTONGANRPP').val(parseInt($('#POTONGANRP').val()));
    			}
    			
    		  var htmlAdd = "Pesanan Anda : \n";
    		  if("<?=$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN']?>" == "TUMTUMMY")
    	      {
            	   for (var i=0;i<countdetail;i++) {
                   		if(typeof $('#NAMA'+i).html() != "undefined") // BARANG TELAH DIHAPUS
                   		{
                   			
                   			var nama         = $("#NAMA"+i).html();
                   			var jml          = $("#JUMLAH"+i+" input").val();
                   			var harga        = $("#HARGA"+i+" input").val();
                   			var subtotal     = $("#SUBTOTAL"+i+" input").val();
                   			
                   			if(nama.includes("KROKET"))
                   			{
                   			    htmlAdd += ((parseInt(jml) / 2)+"x "+capitalizeFirstLetter(nama.toLowerCase())+" @"+(parseInt(harga) * 2)+" = "+subtotal+"\n");
                   			}
                   			else
                   			{
                   			    htmlAdd += (jml+"x "+capitalizeFirstLetter(nama.toLowerCase())+" @"+harga+" = "+subtotal+"\n");
                   			}
                   				
                   		}
                   }
                   
                   
        	
               htmlAdd += "\nTotal Pesanan = "+$("#GRANDTOTAL").val();
               if(parseInt($('#POTONGANRPP').val()) > 0)
               {
                htmlAdd += "\nPotongan = "+$("#POTONGANRPP").val();
               }
               htmlAdd += "\nTotal Setelah Diskon = "+$("#GRANDTOTALP").val();
               htmlAdd += "\nTotal Pembayaran = "+$("#PEMBAYARANP").val()+"\n\nTf ke BCA 0881931418 a/n Irene.\nTerima Kasih";
    	      }
    	      
              $("#PEMBAYARANP").val($("#PEMBAYARAN").val());
              $("#ringkasan").val(htmlAdd);
              getKembali();
    		
    	}
}

function reset(){
	$('.table-responsive').html("");
	$("#NOTRANS").val("");
	$("#CATATAN").val("");
	$("#CATATANCUSTOMER").val("");
	$("#TGLTRANS").datepicker('setDate', new Date());
	$("#btn_simpan").css('filter', '');
	$("#btn_simpan").removeAttr('disabled');
	
					
	$("#NAMACUSTOMER").val('BIASA');
	$("#IDCUSTOMER").val(1);
	$("#INPUTBARCODE").val("");

	$('#TOTAL').val(0);
	$('#PPN').val(0);
	$('#DISKON').val(0);
	$('#POTONGANRP').val(0);
	$('#POTONGANPERSEN').val(0);
	$('#GRANDTOTAL').val(0);
	$('#GRANDTOTALHEADER').val(0);	
	$('#SERVICECHARGE').val(0);	
	$('#PEMBAYARAN').val(0);	
}

function visibleTab(e){
	pointertrans = e;
	
	$('#GRANDTOTAL').val(grandtotal[e]);
	
	//ACTIVE TAB
	$(".tab").removeAttr("id");
	$('.tabs'+e).attr('id','choose');
	
}

function jenisbayarTab(e){
	pointerbayar = e;
	//ACTIVE TAB
	$(".tab_bayar").removeAttr("id");
	$('.tab_cara_bayar'+e).attr('id','choose_pembayaran');
}

function hitung_ppn(jmlData){
	var pakaippn = $("#PAKAIPPN").val();
	var total = 0;
	var ppn = 0;

	if(pakaippn == "EXCL")
	{
		for(var i = 0 ; i < jmlData;i++)
		{
			if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
			{		
				total+= parseInt($('#SUBTOTAL'+i+'  input').val());
				ppnrp[i] = 0;
				ppnrp[i] += (parseInt($('#SUBTOTAL'+i+'  input').val())*("<?=$PPNPERSEN?>"/100)).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				ppn  += parseFloat(ppnrp[i]);
			}
		}
		
		$('#TOTAL').val(total);
	
		//PPN
		$('#PPN').val(ppn);
		
		//GRANDTOTAL
		$('#GRANDTOTAL').val((parseInt($('#TOTAL').val())+(parseInt($('#PPN').val()))));
		$('#GRANDTOTALHEADER').val((parseInt($('#GRANDTOTAL').val())));
							
	}
	else if(pakaippn == "INCL")
	{
		for(var i = 0 ; i < jmlData;i++)
		{
			if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
			{	
				total+= parseInt($('#SUBTOTAL'+i+'  input').val());
				ppnrp[i] = 0;
				ppnrp[i] += (parseFloat($('#SUBTOTAL'+i+'  input').val())/11).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				ppn  += parseFloat(ppnrp[i]);
			}
		}
		
		$('#TOTAL').val(total);
	
		//PPN
		$('#PPN').val(ppn);
		
		//GRANDTOTAL
		
		$('#GRANDTOTAL').val((parseInt($('#TOTAL').val())));
		$('#GRANDTOTALHEADER').val((parseInt($('#GRANDTOTAL').val())));	
	}
	else
	{
		for(var i = 0 ; i < jmlData;i++)
		{
			if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
			{			
				total+= parseInt($('#SUBTOTAL'+i+'  input').val());
				ppnrp[i] = 0;
			}
		}
		
		$('#TOTAL').val(total);
		
		//PPN
		$('#PPN').val(0);
		
		//GRANDTOTAL
		$('#GRANDTOTAL').val((parseInt($('#TOTAL').val())));
		$('#GRANDTOTALHEADER').val((parseInt($('#GRANDTOTAL').val())));						
	}
}

function hitung_diskon(discPersen,hargaDiskon,index){
	var totaldisc 		 = 0;
	var totalHargaDiskon = 0;
	if (discPersen != "0") {
		
		discPersen = discPersen.toString().split("+");

		var discDescription = "";
		for(var i=0;i<discPersen.length;i++){
			if(discPersen[i]!= "" && discPersen[i] <= 100 && discPersen[i]>0){
				discPersen[i] = parseFloat(discPersen[i]);
				disc = +((discPersen[i] * hargaDiskon / 100).toFixed(<?= $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>));
				totaldisc += disc;
				hargaDiskon -= disc;
				discDescription += discPersen[i]+"+";
			}
		}
		
		$("#DISKONRP"+index+" input").val(disc);
		discDescription = discDescription.slice(0,-1);

		totalHargaDiskon = parseInt($("#JUMLAH"+index+" input").val())*hargaDiskon;
	}
	else
	{
		totalHargaDiskon = (parseInt($("#JUMLAH"+index+" input").val())* parseInt($("#HARGA"+index+" input").val()));
	}

	$("#SUBTOTAL"+index+" input").val(totalHargaDiskon);
}

function hitung_diskon_rupiah(discrupiah,hargaDiskon,index){
	var totaldisc 		 = 0;
	var totalHargaDiskon = 0;
	if (discrupiah != "0") {
		
		hargaDiskon = hargaDiskon-discrupiah;
		totalHargaDiskon = parseInt($("#JUMLAH"+index+" input").val())*hargaDiskon;
	}
	else
	{
		totalHargaDiskon = (parseInt($("#JUMLAH"+index+" input").val())* parseInt($("#HARGA"+index+" input").val()));
	}

	$("#SUBTOTAL"+index+" input").val(totalHargaDiskon);
}

function getKembali(){
    var kembali = 0;
    if(parseInt($('#PEMBAYARANP').val()) >= parseInt($('#GRANDTOTALP').val()))
    {
      kembali = ((parseInt($('#PEMBAYARANP').val())) - (parseInt($('#GRANDTOTALP').val()))); 
    }
    
    $('#KEMBALI').val(kembali); 
    $('#KEMBALI').number(true,0);
}

//LIMIT ANGKA SAJA
function numberInput(evt,e,field) {
	var inputLength;
	// 0 = jumlah detail
	// 1 = jumlah ketika tambah
	// 2 = diskon detail
	// 9 = diskonrp detail
	// 3 = pembayaran
	var jmlData = 0;
	if(field == 0 || field == 2 || field == 9)
	{
		inputLength = $("#JUMLAH"+e+" input").val().length;
		jmlData =  parseInt($("#JUMLAH"+e+" input").val());
		
		if(jmlData < 1)
		{
		    $("#JUMLAH"+e+" input").val(1);
            Swal.fire({
        			title            : "Jumlah minimal 1",
        			type             : 'error',
        			showConfirmButton: false,
        			timer            : 1500
        	});
		}
	
	}
	else if(field == 1){
		inputLength = $("#jml").val().length;
		jmlData =   parseInt($("#jml").val());
		
		if(jmlData < 1)
		{
    		$("#jml").val(1);
            Swal.fire({
        			title            : "Jumlah minimal 1",
        			type             : 'error',
        			showConfirmButton: false,
        			timer            : 1500
        	});
		}
	}

	var charCode = (evt.which) ? evt.which : event.keyCode
	
	if (jmlData > 0) {
	    
	    if($("#DISKON"+e+" input").val() < 0 || $("#DISKON"+e+" input").val() > 100){
	        $("#DISKON"+e+" input").val(0);
	        $("#DISKONRP"+e+" input").val(0);
	        Swal.fire({
    				title            : "Diskon % diisi dari 0 - 100",
    				type             : 'error',
    				showConfirmButton: false,
    				timer            : 1500
    		});
	    }
	    
	    if($("#DISKONRP"+e+" input").val() < 0){
	        $("#DISKON"+e+" input").val(0);
	        $("#DISKONRP"+e+" input").val(0);
	        Swal.fire({
    				title            : "Diskon Rupiah tidak boleh kurang dari 0",
    				type             : 'error',
    				showConfirmButton: false,
    				timer            : 1500
    		});
	    }
	    
		if($("#DISKON"+e+" input").val() > 0 && $("#DISKONRP"+e+" input").val() > 0 && field == 9){
			$("#DISKON"+e+" input").val(0);
		}
		
		if(inputLength == 0) //KALAU FIELD KOSONG
		{
			$("#jml").val(1);
			$("#diskon").val(0);
			$("#JUMLAH"+e+" input").val(0);			
		}
		if(field != 1) //1 itu tambah barang pasti error, karena belum masuk dataGrid
		{
			hitung_diskon($("#DISKON"+e+" input").val(),parseInt($("#HARGA"+e+" input").val()),e);

			if($("#DISKON"+e+" input").val() == 0){
				hitung_diskon_rupiah($("#DISKONRP"+e+" input").val(),parseInt($("#HARGA"+e+" input").val()),e);
			}
			
				  $('#KEMBALI').val((parseInt($('#PEMBAYARANP').val())) - (parseInt($('#GRANDTOTALP').val())));
		}

		if(field != 3)//PEMBAYARAN LANGSUNG BAYAR
		{
			hitung_ppn(countdetail);
		}
	}
	
	if($("#SUBTOTAL"+e+" input").val() < 0){
	    $("#DISKON"+e+" input").val(0);
	    $("#DISKONRP"+e+" input").val(0);
		Swal.fire({
				title            : "Subtotal Barang tidak boleh kurang dari Nol",
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
		});
	}

	if((field == 2 || field == 9 ) && charCode == 43) //KHUSUS DISKON SOAL BISA +
	{
		return true;
	}
	else if (charCode > 31 && (charCode < 48 || charCode > 57)) //CEK ANGKA DAN DIGIT MAKS 3
	{
		return false;
	}
	else
	{
		return true;
	}
}

function get_status_trans(v_link, v_idtrans, callback) {
	$.ajax({
		dataType: "json",
		type    : 'POST',
		url     : base_url+v_link+"/getStatusTrans",
		data    : {
			idtrans: v_idtrans
		},
		cache: false,
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

