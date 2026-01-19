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
    Transaksi Penyesuaian Stok
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
							<li class="pull-right" style="width:250px">
                            	<div class="input-group " >
                            	 <div class="input-group-addon">
                            		 <i class="fa fa-filter"></i>
                            	 </div>
                            		<select id="cb_trans_status" name="cb_trans_status" class="form-control "  panelHeight="auto" required="true">
                            			<option value="SEMUA">Semua Transaksi </option>
                            			<option value="AKTIF">Transaksi Aktif</option>
                            			<option value="HAPUS">Transaksi Hapus</option>
                            		</select>
                            	</div>
                            </li>
				
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="tab_grid">
								<div class="box-body">
									<table id="dataGrid" class="table table-bordered table-striped table-hover display nowrap" width="100%">
										<!-- class="table-hover"> -->
										<thead>
										<tr>
											<th width="35px"></th>
											<th width="35px"></th>
											<th>Tgl Trans</th>
											<th>No. Penyesuaian</th>
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
    							     <div class="box-body"  style="padding-left:0px; padding-right:0px;">
                    					 <div class="col-md-12">
                                            <div class="box-body"  style="padding-left:0px; padding-right:0px;">
                    							<div class="form-group col-md-4">
                									<input type="hidden" id="mode" name="mode">
                									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
                										<label >No Peny.. </label>
                									</div>
                									<div class="col-md-9 col-sm-9 col-xs-9" style="padding: 0px 0px 5px 0px">
        												<input type="text" class="form-control" id="NOTRANS" name="NOTRANS" style="width:100%; border:1px solid #B5B4B4; border-radius:1px;" placeholder="Auto Generate" readonly>
    													<input type="hidden" class="form-control" id="IDTRANS" name="IDTRANS">	
                									</div>
                									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
                										<label>Tgl Peny..  </label>
                									</div>
                									<div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">	 
        											    <input type="text" class="form-control" id="TGLTRANS"  name="TGLTRANS" style="width:100%; border:1px solid #B5B4B4; border-radius:1px;" placeholder="Tgl Penyesuaian">
                									</div>
                									<div class="col-md-3 col-sm-3 col-xs-3 SALINDETAILBARANG" style="padding: 0px;">
                        								<label >Salin dari </label>
                        							</div>
                        							<div class="col-md-9  col-sm-9 col-xs-9 SALINDETAILBARANG"  style="padding: 0px 0px 5px 0px;" >
                        							    <div class="input-group margin" style="padding:0; margin:0">
                            						        <input type="text"  class="form-control" id="DETAILBARANG" style="border:1px solid #B5B4B4; border-radius:1px;" placeholder="Kode Transaksi">
                            						        <div class="input-group-btn">
                            									<button type="button" id="btn_salin" class="btn btn-primary btn-flat" data-id="7">Salin</button>
                            								</div>
                        								</div>
                        							</div>
            									</div>
                    							<div class="form-group col-md-5">
                									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
                										<label >Lokasi  </label>
                									</div>
                									<div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">
                									   <select  class="form-control"  id="LOKASI" name="LOKASI" style="width:100%;border:1px solid #B5B4B4; border-radius:1px;"><?=comboGrid("model_master_lokasi")?></select>		
                									</div>
                									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px" hidden>
                										<label >No Opname  </label>
                									</div>
                									<div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px" hidden>	
                									    <select  class="form-control select2"  id="OPNAME" name="OPNAME" style=" width:100% !important;">
    															<option value="0 | -"> - Pilih - </option>
    															<?=comboGridTransaksi("Model_inventori_opname",["S"])?>
    													</select>		
                									</div>
                									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
                										<label >J. Massal  </label>
                									</div>
                									<div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">
                									   	<div class="input-group margin" style="padding:0; margin:0">
                									   	    <input type="text" class="form-control"  id="JMLMASSAL">
                    										<div class="input-group-btn">
                    											<button type="button" id="btn_tambah_all"  class="btn btn-primary btn-flat">Terapkan Semua</button>
                    										</div>
                    									</div>	
                									</div>
            								</div>
            								<div class="form-group col-md-3">
            									 <textarea class="form-control" id="CATATAN" name="CATATAN" placeholder="Catatan" style="height:75px;"></textarea> 		
            								</div>
                    						<div class="col-md-12 col-sm-12 col-xs-12">
                    							<div role="tabpanel" data-example-id="togglable-tabs" >
                    								 <div class="form-group col-md-4" style="margin-left:-15px; margin-bottom:-5px;">
                    									<input type="hidden" id="mode" name="mode">
                    									<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
                    										<label >Cari</label>
                    									</div>
                    									<div class="col-md-9 col-sm-9 col-xs-9" style="padding: 0px 0px 5px 0px">
                    										<input type="text" id="searchInput"  class="form-control" style="border:1px solid #B5B4B4; width:103%; border-radius:1px;" placeholder="..." onkeyup="searchText()">
                    									</div>
                    									<br>
                    									<button type="button" id="btn_tambah" class="btn btn-success" style="margin-left:15px; margin-bottom:10px;" onclick="tambahDetail()" data-toggle="modal" data-target="#modal-barang" ><i class="fa fa-plus"></i></button>		
                    								</div>										
                    								<div class="col-md-8 col-sm-8 col-xs-6" align="right" style="margin-bottom:5px;">	
                    								    <br>
                    								    <br>
                    									<button type="button" id="btn_simpan" class="btn btn-primary" onclick="simpan()">Simpan &nbsp;  <i class="fa fa-save"></i></button>
                    								</div>
                    								
                    								<div id="trans_content" class="tab-content trans-content" style="padding-left:0px;padding-right:0px;">
                    									<div role="tabpanel" class="tab-pane fade active in" id="tab_trans0" >
                    									<div class="col-md-12 col-sm-12 col-xs-12" style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; padding-top:15px; padding-left:0px; padding-right:0px; ">
                    										<!--SATU TABEL-->
    															<div class="col-md-12 col-sm-12 col-xs-12 ">
    															   <div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
    																<div class="row"> 
    																  <div class=" col-sm-12">
    																	   <table id="dataGridDetail" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
    																		<thead>
    																			<tr>
    																				<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
    																				<th style="vertical-align:middle; text-align:center;" width="50px">Jml Opname</th>
    																				<th style="vertical-align:middle; text-align:center;" width="50px">Stok</th>
    																				<th style="vertical-align:middle; text-align:center;" width="50px">Selisih</th>
    																				<th style="vertical-align:middle; text-align:center;" width="50px">Satuan</th>
    																				<th style="vertical-align:middle; text-align:center;" width="100px" >Harga</th>
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
    													
                    									    </div>
                    									     <div class="x_panel" style="padding:0px; padding-top:10px; border-radius:2px; z-index;-1;">
                												<div class="col-md-12 col-sm-12 col-xs-12 input-group form-group">
                												<br>
                													<div class="col-md-3 col-sm-3 col-xs-12">
                														<div align="left" style="font-weight:bold">Total</div>
                														<div class="input-group form-group">
                															<input type="text" readonly class="form-control has-feedback-left col-md-2 col-sm-2 col-xs-2" id="TOTAL" placeholder="Total"  value="0">
                															<div class="input-group-addon">
                																	<i class="fa fa-money" style="font-size:8pt;"></i>
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
    											<div class="modal-dialog" style="width:700px;">
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
    														<input type="text" class="form-control has-feedback-left" id="jml" onkeyup="return numberInput(event,'',1)" placeholder="Jml" style="position:absolute; left:88%;top:10px; width:60px;" value="0">
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
var fromOpname = '';

$(document).ready(function(){
    $("#btn_salin").click(function(){
        if($("#DETAILBARANG").val() != "")
        {
    	    $.ajax({
    			type    : 'POST',
    			url     : base_url+'Inventori/Transaksi/PenyesuaianStok/loadDetail/',
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
        }
	});
	
	$("#TOTAL").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	$("#JMLMASSAL").number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
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
        
        for(var i = 0 ; i < countdetail; i++)
		{	
			$("#detailtab"+i+"").remove();
		}
			
		countdetail= 0;
		$('#TOTAL').val(0);
        if($("#mode").val() == "tambah")
		{
           getAllBarang();
		}
    });
	$("#status").val('I,S,P,D');
	//-------

	  $('.select2').select2({
		 dropdownAutoWidth: true, 
	 });
	
	$("#btn_tambah_all").click(function() {
	    var total = 0;
	    for(var i = 0; i < countdetail;i++)
    	{
        	$("#SELISIH"+i+" input").val(parseFloat($("#JMLMASSAL").val()));
    		$("#JUMLAH"+i+ " input").val(parseFloat($("#SALDO"+i+ " input").val()) + parseFloat($("#SELISIH"+i+ " input").val()));
    		$("#SUBTOTAL"+i+" input").val(parseFloat($("#JUMLAH"+i+" input").val())* parseFloat($("#HARGA"+i+ " input").val()));
    	    total+= parseFloat($("#SUBTOTAL"+i+ " input").val());
        }
        						
        $('#TOTAL').val(total);
	});
	
	$("#LOKASI").change(function() {
        for(var i = 0 ; i < countdetail; i++)
		{	
			$("#detailtab"+i+"").remove();
		}
			
		countdetail= 0;
		$('#TOTAL').val(0);
		if($("#mode").val() == "tambah")
		{
		    getAllBarang();
		}
      })
	
	//barang Opname
	$('#OPNAME').change(function() {
		$("#btn_tambah").prop('disabled',false);
		$('.table-responsive').html('');
		
		if($('#OPNAME').val() != "0 | -" && $("#mode").val() == "tambah")
		{
		    fromOpname = "readonly";
		//HEADER
			 $.ajax({
				type    : 'POST',
				url     : base_url+'Inventori/Transaksi/OpnameStok/loadHeader/', 
				data    : {id:$('#OPNAME').val().split(" | ")[0],mode:$("#mode").val()},
				dataType: 'json',
				success: function(msg){
					$("#TGLTRANS").datepicker('setDate',msg.TGLTRANS);
					$("#LOKASI").val(msg.IDLOKASI);
					$("#LOKASI").trigger('change');
					$("#LOKASI").attr('disabled','disabled');
					
        			$.ajax({
        				type    : 'POST',
        				url     : base_url+'Inventori/Transaksi/OpnameStok/loadDataOpnamePenyesuaian/', 
        				data    : {id:$('#OPNAME').val().split(" | ")[0],mode:$("#mode").val()},
        				dataType: 'json',
        				success: function(msg){
        					
        						$("#btn_tambah").prop('disabled',true);
        						var total = 0;
        						
        						//BARANG PERNAH DIINPUTKAN ATAU BELUM
        						for(var i = 0; i < msg.length;i++)
        						{
        							 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+msg[i].ID+"</td><td id='KODE"+countdetail+"' hidden>"+msg[i].KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
        							+msg[i].NAMA+"</td><td width='60px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+parseFloat(msg[i].QTY)+"' "+fromOpname+"></td><td width='60px' id='SALDO"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+parseFloat(msg[i].SALDO)+"' readonly></td><td width='60px' id='SELISIH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+parseFloat(msg[i].SELISIH)+"'></td><td id='SATUAN"+countdetail+"'align='center'>"
        							+msg[i].SATUANKECIL+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' value="+msg[i].HARGA+" readonly></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right'><input style='width:100px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+parseFloat(msg[i].HARGA * msg[i].QTY) +"' align='right' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
        							$("#JUMLAH"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
        							$("#SALDO"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
        							$("#SELISIH"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
        							$("#SUBTOTAL"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
        							$("#HARGA"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
        				
        							countdetail++;
        							
        							total+= parseFloat($("#SUBTOTAL"+i+ " input").val());
        						}
        						
        						$('#TOTAL').val(total);
        						
        				}
        			});
			 }
			});
		}
		else
		{
		    fromOpname = "";
		    $("#LOKASI").removeAttr('disabled');
		}
	
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
			url    : base_url+'Inventori/Transaksi/PenyesuaianStok/dataGrid',
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
            { data: 'IDPENYESUAIANSTOK',visible: false,},	
            { data: 'TGLTRANS',className:"text-center"},	
            { data: 'KODEPENYESUAIANSTOK' ,className:"text-center"},
            { data: 'KODEOPNAMESTOK' ,className:"text-center",visible: false},
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
			url    : base_url+'Master/Data/Barang/comboGridTransaksi/1', // Master/Data/Barang/loadData
			dataSrc: "rows",
			type   : "POST",
			data    : {mode:"BIASA"},
		},
		language: {
			search: "Cari",
			searchPlaceholder: "Nama Produk"
		},
        columns:[
			{ data: 'ID', visible: false,},
            { data: 'KODE'},
            { data: 'NAMA'},
			{ data: 'HARGA', render:format_uang, className:"text-right"},
        ],
		
    });
	
	
	//BUAT NAMBAH BARANG BIASA
	$('#table_barang tbody').on('click', 'tr', function () {
		var row = $('#table_barang').DataTable().row( this ).data();
        tambah_barang(row,"SATU"); 
		$("#modal-barang").modal('hide');	
		
		$("#jml").val(0);
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

function getAllBarang(){
    $("#jml").val(0);
    $.ajax({
		type    : 'POST',
		url     : base_url+'Inventori/Transaksi/PenyesuaianStok/loadDataBarang/',
		data    : {idlokasi:$("#LOKASI").val(),tgltrans:$("#TGLTRANS").val()},
		dataType: 'json',
		success: function(msg){
		    for(var x = 0 ; x < msg.rows.length ; x++)
		    {
		        tambah_barang(msg.rows[x],"ALL")
		    }
		}
	});
	$("#searchInput").val("");
	$("#searchInput").focus();
}

function refresh(){
    $("#dataGrid").DataTable().ajax.reload();
}	
//---------	
function tambah_barang(row,mode){
	
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
    	$("#SELISIH"+simpanIndex+" input").val(parseFloat($("#SELISIH"+simpanIndex+" input").val())+parseFloat(jml));
		$("#JUMLAH"+simpanIndex+ " input").val(parseFloat($("#SALDO"+simpanIndex+ " input").val()) + parseFloat($("#SELISIH"+simpanIndex+ " input").val()));
		$("#SUBTOTAL"+simpanIndex+" input").val(parseFloat($("#JUMLAH"+simpanIndex+" input").val())* parseFloat(row.HARGA));
	}
	else
	{	
		temp_jumlah = row.QTY;
		 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+row.ID+"</td><td id='KODE"+countdetail+"' hidden>"+row.KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
				+row.NAMA+"</td><td width='60px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+row.QTY+"' "+fromOpname+"></td><td width='60px' id='SALDO"+countdetail+"'align='center' readonly><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+row.SALDO+"' readonly></td><td width='60px' id='SELISIH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+jml+"'></td><td id='SATUAN"+countdetail+"'align='center'>"
				+row.SATUANKECIL+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' value='"+parseFloat(row.HARGA)+"' readonly></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right' readonly><input style='width:100px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+parseFloat(row.HARGA * jml) +"' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
				$("#SALDO"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
				$("#JUMLAH"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
				$("#SELISIH"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
				$("#SUBTOTAL"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				$("#HARGA"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				
				countdetail++;
				
				if(mode == 'SATU')
				{
				    $('.x_content').animate({
                      scrollTop: $('.table-responsive')[0].scrollHeight
                    }, 0);  
                
    				$.ajax({
    				    cache   : false,
                		type    : 'POST',
                		url     : base_url+'Master/Data/Barang/getStok', 
                		data    : {barang:row.ID,lokasi:$("#LOKASI").val(),tgl:$("#TGLTRANS").val()},
                		dataType: 'json',
                		success: function(msg){
                			$("#SALDO"+(countdetail-1)+ " input").val(msg.QTY);
                			$("#JUMLAH"+(countdetail-1)+ " input").val(parseFloat($("#SALDO"+(countdetail-1)+ " input").val()) + parseFloat($("#SELISIH"+(countdetail-1)+ " input").val()));
                			
                		}
                	});
				}
// 		if(temp_jumlah > 0)
// 		{
// 			 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+row.ID+"</td><td id='KODE"+countdetail+"' hidden>"+row.KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
// 				+row.NAMA+"</td><td width='60px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='0' "+fromOpname+"></td><td width='60px' id='SALDO"+countdetail+"'align='center' readonly><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='0' readonly></td><td width='60px' id='SELISIH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+jml+"'></td><td id='SATUAN"+countdetail+"'align='center'>"
// 				+row.SATUANKECIL+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' value='0' readonly></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right' readonly><input style='width:100px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+parseFloat(row.HARGA * jml) +"' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
// 				$("#SALDO"+countdetail+ " input").number(true, 0);
// 				$("#JUMLAH"+countdetail+ " input").number(true, 0);
// 				$("#SELISIH"+countdetail+ " input").number(true, 0);
// 				$("#SUBTOTAL"+countdetail+ " input").number(true, 2);
// 				$("#HARGA"+countdetail+ " input").number(true, 2);
				
// 				$("#HARGA"+countdetail+" input").val(parseFloat($("#SUBTOTAL"+countdetail+" input").val())/ parseFloat($("#SELISIH"+countdetail+" input").val()));
// 				countdetail++;
				
// 				// $.ajax({
// 				//     cache   : false,
//     //         		type    : 'POST',
//     //         		url     : base_url+'Master/Data/Barang/getStok', 
//     //         		data    : {barang:row.ID,lokasi:$("#LOKASI").val(),tgl:$("#TGLTRANS").val()},
//     //         		dataType: 'json',
//     //         		success: function(msg){
//     //         			$("#SALDO"+(countdetail-1)+ " input").val(msg.QTY);
//     //         			$("#JUMLAH"+(countdetail-1)+ " input").val(parseFloat($("#SALDO"+(countdetail-1)+ " input").val()) + parseFloat($("#SELISIH"+(countdetail-1)+ " input").val()));
            			
//     //         		}
//     //         	});
// 		}
// 		else
// 		{
// 			alert("Jumlah Barang Tidak Boleh Nol");
// 		}
	}
	
	if(temp_jumlah > 0)
	{

		var total = 0;
		
		for(var i = 0 ; i < countdetail;i++)
		{
			if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
			{				
				total+= parseFloat($('#SUBTOTAL'+i+' input').val());
			}
		}

		$('#TOTAL').val(total);

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
    $("#JMLMASSAL").val("");
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.UBAH==1) {
			$("#mode").val('ubah');
			
			
			$('.table-responsive').html("");
			countdetail = 0;
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Ubah');
			
			get_status_trans("Inventori/Transaksi/PenyesuaianStok",row.IDPENYESUAIANSTOK,function(data){
				if(data.status == "I")
				{
					reset("NOOPNAME");
					//MENAMPILKAN DATA YANG TELAH STATUS S
					$("#OPNAME").html('<option value="0 | -"> - Pilih - </option>'+"<?=comboGridTransaksi("Model_inventori_opname",["S"])?>");
					$("#btn_simpan, #btn_tambah").css('filter', '');
					$("#btn_simpan, #btn_tambah").removeAttr('disabled');
				}
				else if(data.status == "S")
				{
					//MENAMPILKAN DATA YANG TELAH STATUS P
					$("#OPNAME").html('<option value="0 | -"> - Pilih - </option>'+"<?=comboGridTransaksi("Model_inventori_opname",["P"])?>");

					$("#btn_simpan, #btn_tambah").css('filter', 'grayscale(100%)');
					$("#btn_simpan, #btn_tambah").attr('disabled', 'disabled');
				}
				else
				{
				    $("#OPNAME").html('<option value="0 | -"> - Pilih - </option>'+"<?=comboGridTransaksi("Model_inventori_opname",["I","S","P","D"])?>");
				}
			});
            setTimeout(function() {
                //HEADER
    			 $.ajax({
    				type    : 'POST',
    				url     : base_url+'Inventori/Transaksi/PenyesuaianStok/loadHeader/', 
    				data    : {id:row.IDPENYESUAIANSTOK,mode:"ubah"},
    				dataType: 'json',
    				success: function(msg){
    					$("#IDTRANS").val(msg.IDTRANS);
    					$("#NOTRANS").val(msg.KODETRANS);
    					$("#TGLTRANS").datepicker('setDate',msg.TGLTRANS);
    					$("#LOKASI").val(msg.IDLOKASI);
    					$("#LOKASI").trigger('change');
    									 
    					if(msg.OPNAMESTOK.split(" | ")[0] == 0)
    					{
    						$("#OPNAME").val("0 | -");
    					}
    					else
    					{
    						$("#OPNAME").val(msg.OPNAMESTOK);
    					}
    					$("#OPNAME").trigger('change');
    					
    					$("#TGLTRANS").attr('disabled','disabled');
    					$("#LOKASI").attr('disabled','disabled');
    					$("#OPNAME").attr('disabled','disabled');
    			        $("#CATATAN").val(msg.CATATAN);
    			        $("#CATATAN").attr("readonly","readonly");
    			        
    			        
    
            			$.ajax({
            				type    : 'POST',
            				url     : base_url+'Inventori/Transaksi/PenyesuaianStok/loadDetail/', 
            				data    : {id:row.IDPENYESUAIANSTOK,mode:"ubah"},
            				dataType: 'json',
            				success: function(msg){
            					
            						//TOTAL
            						var total = 0;
            						
            						//BARANG PERNAH DIINPUTKAN ATAU BELUM
            						for(var i = 0; i < msg.length;i++)
            						{
            							 $('.table-responsive').append("<tr id='detailtab"+countdetail+"'><td id='ID"+countdetail+"' hidden>"+msg[i].ID+"</td><td id='KODE"+countdetail+"' hidden>"+msg[i].KODE+"</td><td width='400px' id='NAMA"+countdetail+"'>"
            							+msg[i].NAMA+"</td><td width='60px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+parseFloat(msg[i].QTY)+"' "+fromOpname+"></td><td width='60px' id='SALDO"+countdetail+"'align='center'><input style='width:40px; text-align:center;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='NO' readonly ></td><td width='60px' id='SELISIH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+parseFloat(msg[i].SELISIH)+"'></td><td id='SATUAN"+countdetail+"'align='center'>"
            							+msg[i].SATUANKECIL+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' value='"+parseFloat(msg[i].HARGA)+"' readonly></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right' readonly><input style='width:100px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+parseFloat(msg[i].HARGA * msg[i].SELISIH) +"' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> </td></tr>");
            							$("#JUMLAH"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
            							$("#SELISIH"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
            							$("#HARGA"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
            							$("#SUBTOTAL"+countdetail+ " input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
            							countdetail++;
            							
            							if(typeof $('#SUBTOTAL'+i+" input").val() != "undefined") // BARANG TELAH DIHAPUS
            							{				
            								total+= parseFloat($('#SUBTOTAL'+i+" input").val());
            							}
            							
            							if(i == msg.length-1)
            							{
            								//TOTAL
            								$('#TOTAL').val(total);
            								
            							}
            						}
            				}
            			});
    			    }
			    });
            }, 100);
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

	get_status_trans("Inventori/Transaksi/PenyesuaianStok",row.IDPENYESUAIANSTOK, function(data){
		if (data.status=='I' || data.status=='S') {
			get_akses_user('<?=$kodemenu?>', function(data){
				if (data.HAPUS==1) {
					$('#ALASANPEMBATALAN').val("");
					$("#modal-alasan").modal('show');
					$("#btn_batal").val(JSON.stringify(row));
					$("#KETERANGAN_BATAL").html("Apa anda yakin akan membatalkan transaksi "+row.KODEPENYESUAIANSTOK+" ?");
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
			url     : base_url+"Inventori/Transaksi/PenyesuaianStok/batalTrans",
			data    : "idtrans="+row.IDPENYESUAIANSTOK + "&kodetrans="+row.KODEPENYESUAIANSTOK + "&alasan="+alasan,
			cache   : false,
			success: function(msg){
				if (msg.success) {
					Swal.fire({
						title            : 'Transaksi dengan kode '+row.KODEPENYESUAIANSTOK+' telah dibatalkan',
						type             : 'success',
						showConfirmButton: false,
						timer            : 1500
					});
					
					var doneStok = [true,true,true];     
					if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'YES'?>')
                    {
                         doneStok[0] = false;
                    }
                    if('<?=$_SESSION[NAMAPROGRAM]['TIKTOK_ACTIVE'] == 'YES'?>')
                    {
                         doneStok[1] = false;
                    }
                    if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'YES'?>')
                    {
                         doneStok[2] = false;
                    }
                    
                    if(doneStok.length > 0)
                    {
                        var loadingStok = false
                        for(var d = 0 ; d < doneStok.length;d++)
                        {
                          if(!doneStok[d])
                          {
                              loadingStok = true;
                          }
                        }
                        if(loadingStok)
                        {
                         loadingMaster();
                        }
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'YES'?>')
                    {
    					$.ajax({
                            type      : 'POST',
                            url       : base_url+'Shopee/setStokBarang',
                            data      : {
                                'idtrans' : row.IDPENYESUAIANSTOK, 
                                'jenistrans' : 'PENYESUAIANSTOK',
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                                doneStok[0] = true;
                                cekDone = true;
                                for(var d = 0 ; d < doneStok.length;d++)
                                {
                                    if(!doneStok[d])
                                    {
                                        cekDone = false
                                    }
                                }
                                
                                if(cekDone)
                                {
                                    Swal.close();    
                                }
                                
                                if (msg.success) {
                                    if(msg.msg != "")
                                    {
                                        showAlertMarketplace('SUCCESS', msg.msg, "SHOPEE");
                                    }
                                } else {
                                    showAlertMarketplace('FAILED', msg.msg, "SHOPEE");
                                }
                            },
                            
                        });
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['TIKTOK_ACTIVE'] == 'YES'?>')
                    {
                        $.ajax({
                            type      : 'POST',
                            url       : base_url+'Tiktok/setStokBarang',
                            data      : {
                                'idtrans' : row.IDPENYESUAIANSTOK, 
                                'jenistrans' : 'PENYESUAIANSTOK',
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                                doneStok[1] = true;
                                cekDone = true;
                                for(var d = 0 ; d < doneStok.length;d++)
                                {
                                    if(!doneStok[d])
                                    {
                                        cekDone = false
                                    }
                                }
                                
                                if(cekDone)
                                {
                                    Swal.close();    
                                }
                                
                                if (msg.success) {
                                    if(msg.msg != "")
                                    {
                                        showAlertMarketplace('SUCCESS', msg.msg, "TIKTOK");
                                    }
                                } else {
                                    showAlertMarketplace('FAILED', msg.msg, "TIKTOK");
                                }
                            },
                            
                        });
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'YES'?>')
                    {
                        $.ajax({
                            type      : 'POST',
                            url       : base_url+'Lazada/setStokBarang',
                            data      : {
                                'idtrans' : row.IDPENYESUAIANSTOK, 
                                'jenistrans' : 'PENYESUAIANSTOK',
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                                doneStok[2] = true;
                                cekDone = true;
                                for(var d = 0 ; d < doneStok.length;d++)
                                {
                                    if(!doneStok[d])
                                    {
                                        cekDone = false
                                    }
                                }
                                
                                if(cekDone)
                                {
                                    Swal.close();    
                                }
                                
                                if (msg.success) {
                                    if(msg.msg != "")
                                    {
                                        showAlertMarketplace('SUCCESS', msg.msg, "LAZADA");
                                    }
                                } else {
                                    showAlertMarketplace('FAILED', msg.msg, "LAZADA");
                                }
                            },
                            
                        });
                    }
                    
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
			get_status_trans("Inventori/Transaksi/PenyesuaianStok",row.IDPENYESUAIANSTOK, function(data){
				if (data.status!='D') {
					$.ajax({
						type    : 'POST',
						dataType: 'json',
						url     : base_url+'Inventori/Transaksi/PenyesuaianStok/ubahStatusJadiSlip',
						data    : {idtrans: row.IDPENYESUAIANSTOK, kodetrans: row.KODEPENYESUAIANSTOK},
						cache   : false,
						success: function(msg){
							if (msg.success) {
								$("#dataGrid").DataTable().ajax.reload();
								window.open(base_url+"Inventori/Transaksi/PenyesuaianStok/cetak/"+row.IDPENYESUAIANSTOK, '_blank');
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
				title            : 'Alasan Harus Diisi',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
		}
	});
	
}

function tambahDetail(){
    $("#jml").val(1);
	$('#table_barang_filter input').focus();
}

function ubahDetail(e){
	alert(e);
}

function hapusDetail(e){
	
		
	//HAPUS
	$("#detailtab"+e+"").remove();
	
	//TOTAL
	var total = 0;

	for(var i = 0 ; i < countdetail;i++)
	{
		if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
		{				
			total+= parseFloat($('#SUBTOTAL'+i+' input').val());
		}
	}
	
	$('#TOTAL').val(total);

}


function simpan(){
	var jmlData = 0;
	var row = [];
	for (var i=0;i<countdetail;i++) {
		if(typeof $('#NAMA'+i).html() != "undefined") // BARANG TELAH DIHAPUS
		{	
		    if($("#SELISIH"+i+" input").val() != 0)
		    {
    			row[jmlData] = 
    				{
    					idbarang:$("#ID"+i).html(),
    					jml:$("#JUMLAH"+i+" input").val(),
    					selisih:$("#SELISIH"+i+" input").val(),
    					harga:$("#HARGA"+i+" input").val(),
    					satuan:$("#SATUAN"+i).html(),
    					subtotal:$("#SUBTOTAL"+i+" input").val(),
    					
    				};
    			jmlData++;
		    }
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
			url     : base_url+'Inventori/Transaksi/PenyesuaianStok/simpan/', 
			data    : {	
						IDPENYESUAIAN  : $("#IDTRANS").val(),
						KODEPENYESUAIAN: $("#NOTRANS").val(),
						IDLOKASI       : $("#LOKASI").val(),
						TGLTRANS       : $("#TGLTRANS").val(),
						IDOPNAME       : $("#OPNAME").val().split(" | ")[0],
						data_detail    : JSON.stringify(row),
						mode           : $("#mode").val(),
						CATATAN        : $("#CATATAN").val(),
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
					
					var dataBarang = [];
					for(var x = 0 ; x < row.length; x++)
					{
					    dataBarang.push(row[x].idbarang);
					}
					
					
				    var doneStok = [true,true,true];     
					if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'YES'?>')
                    {
                       doneStok[0] = false;
                    }
                     if('<?=$_SESSION[NAMAPROGRAM]['TIKTOK_ACTIVE'] == 'YES'?>')
                    {
                         doneStok[1] = false;
                    }
                    if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'YES'?>')
                    {
                         doneStok[2] = false;
                    }
                    
                    if(doneStok.length > 0)
                    {
                        var loadingStok = false
                        for(var d = 0 ; d < doneStok.length;d++)
                        {
                          if(!doneStok[d])
                          {
                              loadingStok = true;
                          }
                        }
                        if(loadingStok)
                        {
                         loadingMaster();
                        }
                    }
					
					if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'YES'?>')
                    {
    					$.ajax({
                            type      : 'POST',
                            url       : base_url+'Shopee/setStokBarang',
                            data      : {
                                'idlokasi' : $("#LOKASI").val(), 
                                'databarang' : JSON.stringify(dataBarang),
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                                doneStok[0] = true;
                                cekDone = true;
                                for(var d = 0 ; d < doneStok.length;d++)
                                {
                                    if(!doneStok[d])
                                    {
                                        cekDone = false
                                    }
                                }
                                
                                if(cekDone)
                                {
                                    Swal.close();    
                                }
                                
                                if (msg.success) {
                                    if(msg.msg != "")
                                    {
                                        showAlertMarketplace('SUCCESS', msg.msg, "SHOPEE");
                                    }
                                } else {
                                    showAlertMarketplace('FAILED', msg.msg, "SHOPEE");
                                }
                            },
                            
                        });
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['TIKTOK_ACTIVE'] == 'YES'?>')
                    {
                        $.ajax({
                            type      : 'POST',
                            url       : base_url+'Tiktok/setStokBarang',
                            data      : {
                                'idlokasi' : $("#LOKASI").val(), 
                                'databarang' : JSON.stringify(dataBarang),
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                                doneStok[1] = true;
                                cekDone = true;
                                for(var d = 0 ; d < doneStok.length;d++)
                                {
                                    if(!doneStok[d])
                                    {
                                        cekDone = false
                                    }
                                }
                                
                                if(cekDone)
                                {
                                    Swal.close();    
                                }
                                
                                if (msg.success) {
                                    if(msg.msg != "")
                                    {
                                        showAlertMarketplace('SUCCESS', msg.msg, "TIKTOK");
                                    }
                                } else {
                                    showAlertMarketplace('FAILED', msg.msg, "TIKTOK");
                                }
                            },
                            
                        });
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'YES'?>')
                    {
                        $.ajax({
                            type      : 'POST',
                            url       : base_url+'Lazada/setStokBarang',
                            data      : {
                                'idlokasi' : $("#LOKASI").val(), 
                                'databarang' : JSON.stringify(dataBarang),
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                                doneStok[2] = true;
                                cekDone = true;
                                for(var d = 0 ; d < doneStok.length;d++)
                                {
                                    if(!doneStok[d])
                                    {
                                        cekDone = false
                                    }
                                }
                                
                                if(cekDone)
                                {
                                    Swal.close();    
                                }
                                
                                if (msg.success) {
                                    if(msg.msg != "")
                                    {
                                        showAlertMarketplace('SUCCESS', msg.msg, "LAZADA");
                                    }
                                } else {
                                    showAlertMarketplace('FAILED', msg.msg, "LAZADA");
                                }
                            },
                            
                        });
                    }
                    
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


function reset(status_reset=""){
    $("#DETAILBARANG").val("");
	$('.table-responsive').html("");
	$("#NOTRANS").val("");
	$("#IDTRANS").val("");
	$("#TGLTRANS").datepicker('setDate', new Date());
	$("#LOKASI").val($('#LOKASI option:eq(0)').val());
	$('#LOKASI').trigger('change');	
	if(status_reset != "NOOPNAME")
	{
		$("#OPNAME").html('<option value="0 | -"> - Pilih - </option>'+"<?=comboGridTransaksi("Model_inventori_opname",["S"])?>");
		$("#OPNAME").val($('#OPNAME option:eq(0)').val());
		$('#OPNAME').trigger('change');	
	}
	else
	{
	}
	fromOpname = "";
	countdetail = 0;
	$("#CATATAN").val("");
	
	$("#TGLTRANS").removeAttr('disabled');
	$("#LOKASI").removeAttr('disabled');
	$("#OPNAME").removeAttr('disabled');
	$("#CATATAN").removeAttr('readonly');
	
	$("#btn_simpan, #btn_tambah").css('filter', '');
	$("#btn_simpan, #btn_tambah").removeAttr('disabled');	
	$("#JMLMASSAL").val("");
}

function diskonField(evt){
	var format_diskon = Number.isInteger(parseFloat($('#DISKON').val()));
	
	var charCode = (evt.which) ? evt.which : event.keyCode

	if (charCode == 13) {
		if($('#DISKON').val() != "" && format_diskon == true && $('#TOTAL').val() != "")
		{
			//TOTAL
			var total = 0;
			for(var i = 0 ; i < countdetail;i++)
			{
				if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
				{				
					total+= parseFloat($('#SUBTOTAL'+i+' input').val());
				}
			}
			
			$('#TOTAL').val(total);

			
		}
		else
		{
			$('#DISKON').val(0);
		}
	}
	
	if (charCode > 31 && (charCode < 48 || charCode > 57) || inputLength > 2) //CEK ANGKA DAN DIGIT MAKS 3
	return false;
	return true;
	
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

function totalbayar(){

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
		$("#SELISIH"+e+ " input").val(parseInt($("#JUMLAH"+e+ " input").val()) - parseInt($("#SALDO"+e+ " input").val()));
	}
	else if(field == 1){
		inputLength = $("#jml").val().length;
	}
	else if(field == 2)
	{
		inputLength = $("#DISKON"+e+" input").val().length;
	}
	else if(field == 3)
	{
		inputLength = $("#SELISIH"+e+" input").val().length;
		$("#JUMLAH"+e+ " input").val(parseInt($("#SALDO"+e+ " input").val()) + parseInt($("#SELISIH"+e+ " input").val()));
	}


	var charCode = (evt.which) ? evt.which : event.keyCode

// 	if (charCode == 13) {
		
		if(inputLength == 0) //KALAU FIELD KOSONG
		{
			$("#jml").val(0);
			$("#JUMLAH"+e+" input").val(0);
		}
			
			
			//HARGA
			$("#SUBTOTAL"+e+" input").val(parseFloat($("#HARGA"+e+" input").val()) * parseFloat($("#JUMLAH"+e+" input").val()));
			
	
			//TOTAL
			var total = 0;
			for(var i = 0 ; i < countdetail;i++)
			{
				if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
				{				
					total+= parseFloat($('#SUBTOTAL'+i+' input').val());
				}
			}
			
			$('#TOTAL').val(total);

		
// 	}

	if (charCode > 31 && (charCode < 48 || charCode > 57) || inputLength > 4) //CEK ANGKA DAN DIGIT MAKS 3
	return false;
	return true;
}

function get_status_trans(v_link, v_idtrans, callback) {
	$.ajax({
		dataType: "json",
		type: 'POST',
		url: base_url+v_link+"/getStatusTrans",
		data: {
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

 function searchText() {
    var input = document.getElementById('searchInput').value.toLowerCase(); // Get search input
      var table = document.getElementById('dataGridDetail'); // Get the table
      var rows = table.getElementsByTagName('tr'); // Get all rows

      // Loop through the rows and hide those that don't match the search term
      for (var i = 1; i < rows.length; i++) { // Start at 1 to skip the header row
        var cells = rows[i].getElementsByTagName('td');
        var rowText = ''; // Variable to store row text

        // Loop through each cell in the row to create the text content
        for (var j = 0; j < cells.length; j++) {
          rowText += cells[j].textContent.toLowerCase(); // Concatenate text from each cell
        }

        // If the row text contains the search term, show it, otherwise hide it
        if (rowText.indexOf(input) > -1) {
          rows[i].style.display = ''; // Show the row
        } else {
          rows[i].style.display = 'none'; // Hide the row
        }
      }
 }
 
 function loadingMaster(){
    Swal.fire({
      title: '',
      html: '<div style="font-size:20pt; font-weight:600;">Menghubungkan Master Barang dengan Marketplace... <div>',                // no text or HTML content
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
}
</script>

