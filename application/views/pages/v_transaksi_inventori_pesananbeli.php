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
	
	.bayar{
	  background-color: #2a3f54;
	  border          : 1px solid;
	  border-radius   : 3px 3px 3px 3px;
	  border-color    : #2a3f54;
	  color           : white;
	}
	
	.bayar:hover{
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
	
	label{
		white-space: nowrap;
	}
  </style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Transaksi Pesanan Pembelian
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
				<li><a href="#tab_tutup" data-toggle="tab" >Tutup PO</a></li>
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
                                <th>No. PO</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>PPN</th>
                                <th>Grand Total</th>
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
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_form"  >
                    <div class="box-body" style="padding-left:0px; padding-right:0px;">
					 <div class="col-md-12"  >
                        <div class="box-body" style="padding-left:0px; padding-right:0px;">
						<div class="form-group col-md-4" >
							<input type="hidden" id="mode" name="mode">
                            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
								<label >No PO </label>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9" style="padding: 0px 0px 5px 0px">
								<input type="text" class="form-control" id="NOTRANS" name="NOTRANS" style="border:1px solid #B5B4B4; border-radius:1px;" placeholder="Auto Generate" readonly>
								<input type="hidden" class="form-control" id="IDTRANS" name="IDTRANS">
                            </div>
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
								<label>Tgl PO  </label>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">
								<input type="text" class="form-control" id="TGLTRANS"  name="TGLTRANS" style=" border:1px solid #B5B4B4; border-radius:1px;" placeholder="Tgl PO">
                            </div>
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
								<label >Lokasi  </label>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">
								<select  class="form-control"  id="LOKASI" name="LOKASI" style="border:1px solid #B5B4B4; border-radius:1px; width:100%;"><?=comboGrid("model_master_lokasi")?></select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px;">
                    			<label >Scan</label>
                    		</div>
                    		<div class="col-md-9  col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px; ">
                    			<input type="text" class="form-control"  
                    			id="INPUTBARCODE" style="background:#E0FFFF;" placeholder="BARCODE/F8" value="">
                    		</div>
                            <div class="col-md-3 col-sm-3 col-xs-3 SALINDETAILBARANG" style="padding: 0px;">
                				<label >Salin dari </label>
                			</div>
                			<div class="col-md-9  col-sm-9 col-xs-9 SALINDETAILBARANG"  style="padding: 0px 0px 5px 0px; " >
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
								<label>S. Bayar </label>
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-5" style="padding: 0px 0px 5px 0px">
								<select  class="form-control"  id="SYARATBAYAR" name="SYARATBAYAR" style="border:1px solid #B5B4B4; border-radius:1px; width:90%;"><?=comboGrid("model_master_syaratbayar")?></select>	
                            </div>
							<div class="col-md-4 col-sm-4 col-xs-4" style="padding: 0px 0px 5px 0px">
								<input type="text" class="form-control" id="TGLJTHTEMPO"  name="TGLJTHTEMPO" placeholder="Tgl Jth Tempo" style="border:1px solid #B5B4B4; border-radius:1px; ">	
                            </div>
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
								<label >Supplier </label>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">
								<div class="input-group margin" style="padding:0; margin:0">
									<input type="text" class="form-control"  id="NAMASUPPLIER"  name="NAMASUPPLIER" style="border:1px solid #B5B4B4; border-radius:1px; float:left;" placeholder="Nama Supplier">
									<input type="hidden" class="form-control" id="IDSUPPLIER" name="IDSUPPLIER">
									<div class="input-group-btn">
										<button type="button" id="btn_search" class="btn btn-primary btn-flat" data-toggle="modal" data-target="#modal-supplier"data-id="7">Search</button>
									</div>
								</div>		
                            </div>
							<div class="col-md-3 col-sm-3 col-xs-3" style="padding: 0px">
								<label >&nbsp; </label>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9"  style="padding: 0px 0px 5px 0px">
								<input type="text" class="form-control"  id="INFOSUPPLIER"  name="INFOSUPPLIER" style="border:1px solid #B5B4B4; border-radius:1px;" placeholder="Alamat, Telp">
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
							 <textarea class="form-control" id="CATATAN" name="CATATAN" placeholder="Catatan"  style="height:75px;"></textarea> 
						</div>
						
						<!-- DETAIL -->
								<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:0px;padding-right:0px;">
									<div role="tabpanel" data-example-id="togglable-tabs" >
										<div class="col-md-8 col-sm-8 col-xs-6" style="margin-bottom:5px;">
											<button type="button" id="btn_tambah" class="btn btn-success" onclick="tambahDetail()" data-toggle="modal" data-target="#modal-barang" ><i class="fa fa-plus"></i></button>		
										</div>										
										<div class="col-md-4 col-sm-4 col-xs-6" align="right" style="margin-bottom:5px;">											
											<button type="button" id="btn_simpan" class="btn btn-primary" onclick="simpan()">Simpan &nbsp;  <i class="fa fa-save"></i></button>
										</div>										
										<div id="trans_content" class="tab-content trans-content" >
											<div role="tabpanel" class="tab-pane fade active in" id="tab_trans0"  >
											<div class="col-md-12 col-sm-12 col-xs-12" style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; padding-top:15px; padding-left:0px; padding-right:0px;">
												<!--SATU TABEL-->
												<div class="col-md-12 col-sm-12 col-xs-12 ">
												<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
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
													<div class="col-md-2  col-sm-3 col-xs-12">
														<div align="left" style="font-weight:bold">Total Barang</div>
														<div class="col-md-12 col-sm-12 col-xs-12 input-group form-group">	
															
															<input type="text" readonly class="form-control has-feedback-left" id="TOTALBARANG" placeholder="Total Barang"  value="0">
															<div class="input-group-addon">
																<i class="fa fa-shopping-bag" style="font-size:8pt;"></i>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-12">
														<div align="left" style="font-weight:bold">Total</div>
														<div class="input-group form-group">
															<input type="text" readonly class="form-control has-feedback-left col-md-2 col-sm-2 col-xs-2" id="TOTAL" placeholder="Total"  value="0">
															<div class="input-group-addon">
																	<i class="fa fa-money" style="font-size:8pt;"></i>
															</div>
														</div>
													</div>
													<div class="col-md-2  col-sm-2 col-xs-3">
														<div align="left" style="font-weight:bold">Pakai PPN</div>
														<select name="PAKAIPPN" class="form-control " id="PAKAIPPN" >
															<option value="TIDAK">Tidak</option>
															<option value="INCL">Include</option>
															<option value="EXCL">Exclude</option>
														</select>
															
													</div>	
													<div class="col-md-2 col-sm-2 col-xs-9">
														<div align="left" style="font-weight:bold">PPN</div>
														<div class="input-group form-group">
															<input type="text" readonly class="form-control has-feedback-left col-md-2 col-sm-2 col-xs-2" id="PPN" placeholder="PPN" value="0">
															<div class="input-group-addon">
																	<i class="fa fa-money" style="font-size:8pt;"></i>
															</div>
														</div>
													</div>
													<div class="col-md-3 col-sm-6 col-xs-12">
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
    											<input type="text" class="form-control has-feedback-left" id="jml" onkeyup="return numberInput(event,'',1)" placeholder="Jml" style="position:absolute; left:88%;top:10px; width:50px;" value="1">
    										</div>
    									</div>
									</div>
								</div>
							<!--MODAL SUPPLIER-->
								<div class="modal fade" id="modal-supplier">
									<div class="modal-dialog" style="width:70%;">
									<div class="modal-content">
										<div class="modal-body">
											<table id="table_supplier" class="table table-bordered table-striped table-hover display nowrap" width="100%">
												<thead>
													<tr>
														<th ></th>
														<th width="50px">Kode</th>
														<th>Nama</th>
														<th>Alamat</th>
														<th>Telp</th>
													</tr>
												</thead>
											</table>
										</div>
									</div>
									</div>
								</div>
								<!--CEK SISA-->
								<div class="modal fade" id="modal-sisa">
									<div class="modal-dialog" style="width:30%;">
									<div class="modal-content">
										<div class="modal-body">
										    <div id="NAMABARANGSISA" style="font-weight:bold; font-size:16pt;"></div><br>
											<table id="table_sisa" class="table table-bordered table-striped table-hover display nowrap" width="100%">
												<thead>
													<tr>
														<th >Transaksi Pembelian</th>
														<th style="width:40px;">Jml</th>
													</tr>
												</thead>
												<tbody>
												    
												</tbody>
												<tfoot>
												    <tr>
												        <th style="">Terpenuhi</th>
												        <th style="text-align:center;" id="TERPENUHI">0</th>
												    </tr>
												    <tr>
												        <th style="color:green">PO</th>
												        <th style="color:green; text-align:center;" id="JMLPO">0</th>
												    </tr>
												    <tr>
												        <th style="color:red;">Sisa</th>
												        <th style="color:red; text-align:center;" id="SISA">0</th>
												    </tr>
												    <tr>
												        <th style="color:black;" colspan="2"  id="ALASAN"></th>
												    </tr>
												</tfoot>
											</table>
										</div>
									</div>
									</div>
								</div>
							
						</div><!-- /.box-body -->
                    </div>
                </div>
                <!-- /.tab-pane -->
            </div>
            <div class="tab-pane" id="tab_tutup">
                    <div class="box-body row" >
                        <div class="col-md-6">
                            <h3 style="font-weight:bold;">Pilih Barang</h3>
                            <table id="dataGridBarangTutup" class="table table-bordered table-striped table-hover display nowrap" >
                                <!-- class="table-hover"> -->
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Kode</th>
                                    <th>Barang</th>
                                    <th width="18px"></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h3 style="font-weight:bold;">PO yang masih berjalan 
                            <button type="button" onclick="before_tutup_po()"  class="btn btn-primary btn-flat" style="float:right;">Tutup PO</button>
                            </h3>
                            <table id="dataGridPOTutup" class="table table-bordered table-striped table-hover display nowrap" >
                                <!-- class="table-hover"> -->
                                <thead>
                                <tr>
                                    <th  width="18px"></th>
                                    <th></th>
                                    <th></th>
                                    <th width="100px">Kode PO</th>
                                    <th width="100px">Tgl Trans</th>
                                    <th width="50px">Sisa</th>
                                    <th>Catatan</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
					<!--MODAL BATAL-->
					<div class="modal fade" id="modal-alasan-tutup">
						<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<label id="KETERANGAN_TUTUP"></label>
								<textarea class="form-control" id="ALASANTUTUP" name="ALASANTUTUP" placeholder="Alasan Tutup PO"></textarea> 
								<br>
								<button class="btn btn-danger pull-right" id="btn_tutup" onclick="tutup_po()">Tutup PO</button>
								<br>
								<br>
							</div>
						</div>
						</div>
					</div>
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

<script>
var pointertrans = 0;
var pointerbayar = 0;
var counttrans = 0;
var countdetail = 0;
var qty=0;
var mode='';
var base_url = '<?=base_url()?>';
var ppnrp = [];
$(document).ready(function(){
    
    $("#btn_salin").click(function(){
        if($("#DETAILBARANG").val() != "")
        {
    	    $.ajax({
    			type    : 'POST',
    			url     : base_url+'Inventori/Transaksi/PesananPembelian/loadDetail/',
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
	
	$('body').keyup(function(e){
		hotkey(e);
	});
	$("#JMLMASSAL").number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
    $("#btn_tambah_all").click(function() {
		var totalBarang = 0;
		var ppn = 0;
		var total = 0;
		var pakaippn = $("#PAKAIPPN").val();
		
	    for(var i = 0; i < countdetail;i++)
    	{
    	    if(typeof $('#JUMLAH'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
		    {
            	$("#JUMLAH"+i+" input").val(parseFloat($("#JMLMASSAL").val()));
            	totalBarang+= parseInt($('#JUMLAH'+i+" input").val());
		    }
		    
		    hitung_diskon(parseFloat($("#DISKON"+i+" input").val()),parseFloat($("#HARGA"+i+" input").val()),i);
            
            if(pakaippn == "EXCL")
            {
            	if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
            	{				
            		total+= parseFloat($('#SUBTOTAL'+i+'  input').val());
            		ppnrp[i] = (parseFloat($('#SUBTOTAL'+i+'  input').val())*("<?=$PPNPERSEN?>"/100)).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
            		ppn  += parseFloat(ppnrp[i]);
            	}
            }
            else if(pakaippn == "INCL")
            {
            	if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
            	{				
            		total+= parseFloat($('#SUBTOTAL'+i+'  input').val());
            		ppnrp[i] = (parseFloat($('#SUBTOTAL'+i+'  input').val())/11).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
            		ppn  += parseFloat(ppnrp[i]);
            	}
            }
            else
            {
            	if(typeof $('#SUBTOTAL'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
            	{				
            		total+= parseFloat($('#SUBTOTAL'+i+'  input').val());
            		ppnrp[i] = 0;
            	}
            	
            }
		}
		$('#TOTALBARANG').val(totalBarang);
        //TOTAL
        $('#TOTAL').val(total);
        	
        //PPN
        $('#PPN').val(ppn);
        
        //GRANDTOTAL
        $('#GRANDTOTAL').val((parseFloat($('#TOTAL').val())+(parseFloat($('#PPN').val()))));
	});
	
	$("#NAMASUPPLIER").attr("readonly","readonly");
	$("#INFOSUPPLIER").attr("readonly","readonly");
	
	//TAMBAH
	$('#TGLTRANS, #TGLJTHTEMPO, #tgl_awal_filter, #tgl_akhir_filter').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true, // Close the datepicker automatically after selection
        container: 'body', // Attach the datepicker to the body element
        orientation: 'bottom auto' // Show the calendar below the input
	});
	$("#tgl_awal_filter").datepicker('setDate', "<?=TGLAWALFILTER?>");
	$("#TGLTRANS, #TGLJTHTEMPO,#tgl_akhir_filter").datepicker('setDate', new Date());
	
	$("#TGLTRANS").change(function() {
        var value = $('#SYARATBAYAR').val().split(" | ");
		var selisih = value[2];
		
		var date = $('#TGLTRANS').datepicker('getDate'); 
		date.setDate(date.getDate()+parseFloat(selisih)); 
		$('#TGLJTHTEMPO').datepicker('setDate', date);
    });
    
	$("#status").val('I,S,C,P,D');
	//-------
	$('#TOTAL').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	$('#PPN').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	$('#GRANDTOTAL').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
	$('.select2').select2({
		  theme: "classic"
	});
	
		
	$("#LOKASI").change(function() {
        for(var i = 0 ; i < countdetail; i++)
		{	
			$("#detailtab"+i+"").remove();
		}
			
		countdetail= 0;
		hitung_ppn(countdetail);
      })
	
	$('#SYARATBAYAR').on('change', function () {
		var value = $('#SYARATBAYAR').val().split(" | ");
		var selisih = value[2];
		
		var date = $('#TGLTRANS').datepicker('getDate'); 
		date.setDate(date.getDate()+parseFloat(selisih)); 
		$('#TGLJTHTEMPO').datepicker('setDate', date);
	});
	
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
            else if (data.STATUS == "C") {
                $(row).addClass('status-cicil');
            }
            else if (data.STATUS == "P") {
                $(row).addClass('status-lanjut');
            }
            else if (data.STATUS == "D") {
                $(row).addClass('status-batal');
            }
        },
		ajax		  : {
			url    : base_url+'Inventori/Transaksi/PesananPembelian/dataGrid',
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
            { data: 'IDPO',visible: false,},	
            { data: 'TGLTRANS', className:"text-center"},	
            { data: 'KODEPO', className:"text-center"},
            { data: 'NAMASUPPLIER' ,className:"text-center"},
            { data: 'TOTAL', render:format_uang, className:"text-right"},
            { data: 'PPN', render:format_uang, className:"text-right"},
            { data: 'GRANDTOTAL', render:format_uang, className:"text-right"},
            { data: 'CATATAN'},
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
	
	//TABLE SUPPLIER
	$("#table_supplier").DataTable({
        'retrieve'    : true,
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"dom"		  : '<"pull-left"f><"pull-right"l>tip',
		ajax		  : {
			url    : base_url+'Master/Data/Supplier/comboGridTransaksi', // Master/Data/Barang/loadData
			dataSrc: "rows",
			type   : "POST",
		},
		language: {
			search: "Cari",
			searchPlaceholder: "Nama Supplier"
		},
        columns:[
            { data: 'ID', visible: false,},
            { data: 'KODE'},
            { data: 'NAMA'},
			{ data: 'ALAMAT'},
			{ data: 'TELP'},
        ],
		
    });
	
	//BUAT NAMBAH BARANG BIASA
	$('#table_supplier tbody').on('click', 'tr', function () {
		var row = $('#table_supplier').DataTable().row( this ).data();
		$("#modal-supplier").modal('hide');	
		
		$("#NAMASUPPLIER").val(row.NAMA);
		$("#IDSUPPLIER").val(row.ID);
		if(row.ALAMAT == null)row.ALAMAT = "";
        if(row.TELP == null)row.TELP = "";
        var pemisah = "";
        if(row.ALAMAT != "" && row.TELP != "")pemisah=", ";
        
		$("#INFOSUPPLIER").val(row.ALAMAT+pemisah+row.TELP);
		
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
			url    : base_url+'Master/Data/Barang/comboGridTransaksi', // Master/Data/Barang/loadData
			dataSrc: "rows",
			type   : "POST",
			data    : {mode:"BIASA", transaksi:"1"},
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
        tambah_barang(row); 
		$("#modal-barang").modal('hide');	
		
		$("#jml").val(1);
		var table = $('#table_barang').DataTable();
		table.search("").draw();
	});
	
			//BUAT NAMBAH BARANG DARI BARCODENYA
	var ready = true;
	$('#INPUTBARCODE').bind('keypress', function(e){
	    ready = true;
		if (e.keyCode == 13 && ready) {
		  //   setTimeout(function() {
    //             ready = true;
    //         }, 500);
    	    ready = false;
			var qty 	= 1;
			var barcode = $(this).val();
		
			if(barcode != "")
			{
    			tambah_barang_barcode(barcode,qty,"BARCODE");
    			
    			$("#jml").val(1);
    			var table = $('#table_barang').DataTable();
    			table.search("").draw();
			}
			else
			{ 
			    $('#INPUTBARCODE').val('');
			    Swal.fire({
					title            : 'Barcode belum diisi',
					type             : 'warning',
					showConfirmButton: false,
					timer            : 1500
				});
			}
		}
		else if (e.keyCode == 13 && !ready)
    	{
        	$('#INPUTBARCODE').val('');
    	}
	});
	
	countdetail = 0;
	
	
	//GRID BARANG
	$('#dataGridBarangTutup').DataTable({
       'pageLength'  : 25,
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
    	"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/Barang/getAll',
			dataSrc: "rows",
			type   : "POST",
		},
        columns:[
            { data: 'IDBARANG',visible: false,},	
            { data: 'KODEBARANG', className:"text-center"},	
            { data: 'NAMABARANG'},
            { data: '' },    
        ],
		columnDefs: [ 
			{
                "targets": -1,
                "data": null,
                "defaultContent": "<button id='btn_lihat' class='btn btn-warning'><i class='fa fa-arrow-right'></i></button>"	
			},
		]
    });
	
	//DAPATKAN INDEX
	var tableTutup = $('#dataGridBarangTutup').DataTable();
	$('#dataGridBarangTutup tbody').on( 'click', 'button', function () {
		var row = tableTutup.row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_lihat"){ lihat_po(row);}

	});
	
	//GRID PO
	$('#dataGridPOTutup').DataTable({
       'pageLength'  : 25,
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
    	"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Inventori/Transaksi/PesananPembelian/checkPOBelumTutup/0',
			dataSrc: "rows",
			type   : "POST",
		},
        columns:[
            { data: '' },    
            { data: 'IDPO',visible: false,},
            { data: 'IDBARANG',visible: false,},	
            { data: 'KODEPO', className:"text-center"},	
            { data: 'TGLTRANS', className:"text-center"},
            { data: 'SISA', className:"text-center"},
            { data: 'CATATAN'},
        ],
		columnDefs: [ 
			{
                "targets": 0,
                "data": null,
                "defaultContent": "<input class='pilihPO' type='checkbox'>"	
			},
		]
    });
});


function hotkey(e){
	if(e.keyCode == 119) // F8
	{
		$("#INPUTBARCODE").focus();
	}
}

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status").change(function(event){

	if($(this).val()  == 'SEMUA' )
	{
		$("#status").val('I,S,C,P,D');
	}	
	else if($(this).val()  == 'AKTIF' )
	{
		$("#status").val('I,S,C,P');
	}
	else if($(this).val()  == 'HAPUS' )
	{
		$("#status").val('D');
	}
	$("#dataGrid").DataTable().ajax.reload();
	
});

function lihat_po(row){
    var table = $('#dataGridPOTutup').DataTable();
    table.ajax.url(base_url+'Inventori/Transaksi/PesananPembelian/checkPOBelumTutup/'+row.IDBARANG);
    table.ajax.reload();
}

function before_tutup_po(){
	$('#ALASANTUTUP').val("");
	$("#modal-alasan-tutup").modal('show');
	$("#KETERANGAN_TUTUP").html("Apa anda yakin akan menutup PO atas barang ini ?");
}

function tutup_po(){
	$("#modal-alasan-tutup").modal('hide');
	alasan = $('#ALASANTUTUP').val();
	var dataTutupPO = [];
    $("#dataGridPOTutup").DataTable().rows().every(function () {
        var $rowNode = $(this.node());
        var checkbox = $rowNode.find('.pilihPO');
        if(checkbox.prop('checked')){
            dataTutupPO.push(
                {
                    'IDPO' : this.data().IDPO,
                    'IDBARANG' : this.data().IDBARANG,
                    'ALASAN' : alasan
                }
            )
        }
    });
	
	if (dataTutupPO.length > 0  && alasan != "") {
		$.ajax({
			type    : 'POST',
			dataType: 'json',
			url     : base_url+"Inventori/Transaksi/PesananPembelian/tutupPO",
			data    : "dataTutupPO="+JSON.stringify(dataTutupPO),
			cache   : false,
			success : function(msg){
				if (msg.success) {
					Swal.fire({
						title            : 'PO Ditutup',
						type             : 'success',
						showConfirmButton: false,
						timer            : 1500
					});
					$("#dataGridBarangTutup").DataTable().ajax.reload();
					$("#dataGridPOTutup").DataTable().ajax.reload();
					
				} else {
						Swal.fire({
							title            : 'Error',
							text             : msg.errorMsg,
							type             : 'error',
							showConfirmButton: false,
							timer            : 1500
						});
				}
			}
		});
	} else if(alasan == ""){
		Swal.fire({
			title            : 'Alasan Harus Diisi',
			type             : 'error',
			showConfirmButton: false,
			timer            : 1500
		});
	}
	else {
	   	Swal.fire({
			title            : 'Tidak ada data PO Harus yang dipilih',
			showConfirmButton: false,
			timer            : 1500
		}); 
	}
}

function getStatus(){
	return $("#status").val();
}

function cekSisa(index){
    $("#NAMABARANGSISA").html($("#NAMA"+index).html());
    $("#table_sisa tbody").html("");
    $.ajax({
		type    : 'POST',
		url     : base_url+'Inventori/Transaksi/PesananPembelian/checkSisa',
		data    : {idpo:$("#IDTRANS").val(),idbarang:$("#ID"+index).html()},
		dataType: 'json',
		success : function(msg){;
		    var terpenuhi = 0;
		    var sisa = 0;
		    var po = $("#JUMLAH"+index+" input").val();
		    for(var x = 0 ; x < msg.length ; x++)
		    {
    		    $("#table_sisa tbody").append(`
    		        <tr>
    		        <td>`+msg[x].KODE+`</td>
    		        <td style="text-align:center;">`+parseFloat(msg[x].JML).toFixed(<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>)+`</td>
    		        </tr>
    		    `);
    		    
    		    terpenuhi = (parseFloat(terpenuhi) + parseFloat(parseFloat(msg[x].JML).toFixed(<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>)));
		    }
    		    
    		$("#TERPENUHI").html(terpenuhi);
    		$("#JMLPO").html(po);
    		$("#SISA").html(parseFloat(po) - parseFloat(terpenuhi));
    		
    		 $.ajax({
        		type    : 'POST',
        		url     : base_url+'Inventori/Transaksi/PesananPembelian/checkTutupPO',
        		data    : {idpo:$("#IDTRANS").val(),idbarang:$("#ID"+index).html()},
        		dataType: 'json',
        		success : function(msg){
        		    $("#ALASAN").html('');
        		    if(msg.TUTUP == 1 )
        		    {
        		        $("#ALASAN").html("PO DITUTUP : "+msg.ALASANTUTUP);
        		    }
        		    
        	    }
        	});
		    
	    }
	});
    $("#modal-sisa").modal('show');
}


function refresh(){
    $("#dataGrid").DataTable().ajax.reload();
}	
//---------	
function tambah_barang(row){	
	var jml         = parseFloat($("#jml").val());
	var ada         = false;
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
			row.DISKON = $("#DISKON"+simpanIndex+" input").val();
				
			hitung_diskon(row.DISKON,parseFloat(row.HARGA),simpanIndex);	
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
				+row.NAMA+"</td><td width='50px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+jml+"'></td><td id='SATUAN"+countdetail+"' align='center'>"+row.SATUANBESAR+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+row.HARGA+"'></td><td width='50px' id='DISKON"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",2)' value='"+row.DISKON+"'></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right'><input style='width:100px;'  type='text' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button></td></tr>");
				
				$("#JUMLAH"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
				hitung_diskon(row.DISKON,parseFloat(row.HARGA),countdetail);
				$("#HARGA"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				$("#SUBTOTAL"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
			
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
			data    : {barcode:val,qty:jml,mode:mode,jenisharga:"1"},
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
							title            : 'Tidak Ada Produk Dengan Barcode Tersebut',
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
								
								$("#JUMLAH"+simpanIndex+" input").val(parseFloat($("#JUMLAH"+simpanIndex+" input").val())+parseFloat(msg.rows.QTY));
			                    msg.rows.DISKON = $("#DISKON"+simpanIndex+" input").val();
			                    hitung_diskon(msg.rows.DISKON,parseFloat(msg.rows.HARGA),simpanIndex);			
														
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
                				+msg.rows.NAMA+"</td><td width='50px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+jml+"'></td><td id='SATUAN"+countdetail+"' align='center'>"+msg.rows.SATUANBESAR+"</td><td width='50px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+msg.rows.HARGA+"'></td><td width='50px' id='DISKON"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",2)' value='"+msg.rows.DISKON+"'></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right'><input style='width:100px;'  type='text' readonly></td><td width='50px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button></td></tr>");
                				$("#JUMLAH"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
                				$("#HARGA"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                				$("#SUBTOTAL"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                			
                						
                				hitung_diskon(msg.rows.DISKON,parseFloat(msg.rows.HARGA),countdetail);
                				
                				countdetail++;
                				$('.x_content').animate({
                                  scrollTop: $('.table-responsive')[0].scrollHeight
                                }, 0);  
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
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
			$("#mode").val('tambah');
			
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Tambah');
			$("#INPUTBARCODE").focus();
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
	$("#JMLMASSAL").val("");
    $("#DETAILBARANG").val("");
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.UBAH==1) {
			$("#mode").val('ubah');

			$('.table-responsive').html("");
			countdetail = 0;
			
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Ubah');
			
			get_status_trans("Inventori/Transaksi/PesananPembelian",row.IDPO,function(data){
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
				url     : base_url+'Inventori/Transaksi/PesananPembelian/loadHeader/',
				data    : {id:row.IDPO,mode:"ubah"},
				dataType: 'json',
				success : function(msg){
					$("#IDTRANS").val(msg.IDTRANS);
					$("#NOTRANS").val(msg.KODETRANS);
					$("#TGLTRANS").datepicker('setDate',msg.TGLTRANS);
					$("#SYARATBAYAR").val(msg.SYARATBAYAR);
					$('#SYARATBAYAR').trigger('change');
					$("#IDSUPPLIER").val(msg.IDSUPPLIER);
					$("#LOKASI").val(msg.IDLOKASI);
					$('#LOKASI').trigger('change');
					$("#NAMASUPPLIER").val(msg.NAMASUPPLIER);	
					if(msg.ALAMATSUPPLIER == null)msg.ALAMATSUPPLIER = "";
                    if(msg.TELPSUPPLIER == null)msg.TELPSUPPLIER = "";
                    var pemisah = "";
                    if(msg.ALAMATSUPPLIER != "" && msg.TELPSUPPLIER != "")pemisah=", ";
    				$("#INFOSUPPLIER").val(msg.ALAMATSUPPLIER+pemisah+msg.TELPSUPPLIER);
					$("#CATATAN").val(msg.CATATAN);
					
					$("#TGLTRANS").attr('disabled','disabled');
	                $("#TGLJTHTEMPO").attr('disabled','disabled');
					$("#SYARATBAYAR").attr('disabled','disabled');
					$("#LOKASI").attr('disabled','disabled');
					$("#NAMASUPPLIER").attr('readonly','readonly');
					$("#INFOSUPPLIER").attr('readonly','readonly');
					$("#CATATAN").attr('readonly','readonly');
					$("#btn_search").attr('disabled','disabled');
					
					//DETAIL
        			$.ajax({
        				type    : 'POST',
        				url     : base_url+'Inventori/Transaksi/PesananPembelian/loadDetail/', 
        				data    : {id:row.IDPO,mode:"ubah"},
        				dataType: 'json',
        				success: function(msg){
        					
        						//TOTAL
        						var total = 0;
        						var totalBarang = 0;
        						var ppn = 0;
        
        						//$("#kode-tgl").html(row.KODEPO+" &nbsp; ~ &nbsp; "+row.TGLTRANS+"<br>"+row.NAMASUPPLIER);
        									
        						//BARANG PERNAH DIINPUTKAN ATAU BELUM
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
        							+msg[i].NAMA+"</td><td width='50px' id='JUMLAH"+countdetail+"'align='center'><input style='width:40px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",0)' value='"+parseFloat(msg[i].QTY)+"'></td><td id='SATUAN"+countdetail+"' align='center'>"+msg[i].SATUANBESAR+"</td><td width='100px' id='HARGA"+countdetail+"'align='right'><input style='width:100px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",3)' value='"+msg[i].HARGA+"'></td><td width='50px' id='DISKON"+countdetail+"'align='center'><input style='width:50px;'  type='text' onkeyup='return numberInput(event,"+countdetail+",2)' value='"+msg[i].DISKON+"'></td><td width='100px' id='SUBTOTAL"+countdetail+"' align='right'><input style='width:100px;'  type='text' readonly></td><td width='120px' align='center'><button id='btn_hapus' onclick='hapusDetail("+countdetail+")' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></i></button> <button id='btn_sisa' style='margin-left:5px;' onclick='cekSisa("+countdetail+")' class='btn btn-warning'><i class='fa fa-info-circle' aria-hidden='true' ></i></button> </td></tr>");
        						    $("#JUMLAH"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
        							$("#HARGA"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
        							$("#SUBTOTAL"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
        							
        							if(typeof $('#JUMLAH'+countdetail+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
            						{				
            							totalBarang+= parseFloat($('#JUMLAH'+countdetail+'  input').val());
            						}
            						
		                             $('#TOTALBARANG').val(totalBarang);
		                             
        							hitung_diskon(msg[i].DISKON,parseFloat(msg[i].HARGA),countdetail);
        							
        							countdetail++;
        						
        							if(pakaippn == "EXCL")
        							{
        								if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
        								{				
        									total+= parseFloat($('#SUBTOTAL'+i+' input').val());
        									ppnrp[i] = (parseFloat($('#SUBTOTAL'+i+' input').val())*("<?=$PPNPERSEN?>"/100)).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
        									ppn  += parseFloat(ppnrp[i]);
        								}
        								
        								if(i == msg.length-1)
        								{
        									//TOTAL
        									$('#TOTAL').val(total);
        									
        									//PPN
        									$('#PPN').val(ppn);
        								}
        								
        								//GRANDTOTAL
        								$('#GRANDTOTAL').val((parseFloat($('#TOTAL').val())+(parseFloat($('#PPN').val()))));
        							}
        							else if(pakaippn == "INCL")
        							{
        								if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
        								{				
        									total+= parseFloat($('#SUBTOTAL'+i+' input').val());
        									ppnrp[i] = (parseFloat($('#SUBTOTAL'+i+' input').val())/11).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
        									ppn  += parseFloat(ppnrp[i]);
        								}
        								
        								if(i == msg.length-1)
        								{
        									//TOTAL
        									$('#TOTAL').val(total);
        									
        									//PPN
        									$('#PPN').val(ppn);
        								}						
        								//GRANDTOTAL
        								$('#GRANDTOTAL').val((parseFloat($('#TOTAL').val())));
        							}
        							else
        							{
        								if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
        								{				
        									total+= parseFloat($('#SUBTOTAL'+i+' input').val());
        									ppnrp[i] = 0;
        								}
        								
        								if(i == msg.length-1)
        								{
        									//TOTAL
        									$('#TOTAL').val(total);
        									
        									//PPN
        									$('#PPN').val(0);
        								}
        								
        								//GRANDTOTAL
        								$('#GRANDTOTAL').val((parseFloat($('#TOTAL').val())+(parseFloat($('#PPN').val()))));
        							}
        							
        							
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
	get_status_trans("Inventori/Transaksi/PesananPembelian",row.IDPO, function(data){
		if (data.status=='I' || data.status=='S') {
			get_akses_user('<?=$kodemenu?>', function(data){
				if (data.HAPUS==1) {
					$('#ALASANPEMBATALAN').val("");
					$("#modal-alasan").modal('show');
					$("#btn_batal").val(JSON.stringify(row));
					$("#KETERANGAN_BATAL").html("Apa anda yakin akan membatalkan transaksi "+row.KODEPO+" ?");
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
	var row    = JSON.parse($("#btn_batal").val());
	    alasan = $('#ALASANPEMBATALAN').val();
	
	if (row  && alasan != "") {
		$.ajax({
			type    : 'POST',
			dataType: 'json',
			url     : base_url+"Inventori/Transaksi/PesananPembelian/batalTrans",
			data    : "idtrans="+row.IDPO + "&kodetrans="+row.KODEPO + "&alasan="+alasan,
			cache   : false,
			success : function(msg){
				if (msg.success) {
					Swal.fire({
						title            : 'Transaksi Dibatalkan',
						text             : 'Transaksi dengan kode '+row.KODEPO+' telah dibatalkan',
						type             : 'success',
						showConfirmButton: false,
						timer            : 1500
					});
					$("#dataGrid").DataTable().ajax.reload();
					$('.nav-tabs a[href="#tab_grid"]').tab('show');
				} else {
						Swal.fire({
							title            : 'Error',
							text             : msg.errorMsg,
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
			get_status_trans("Inventori/Transaksi/PesananPembelian",row.IDPO, function(data){
				if (data.status =='I') {
					$.ajax({
						type    : 'POST',
						dataType: 'json',
						url     : base_url+'Inventori/Transaksi/PesananPembelian/ubahStatusJadiSlip',
						data    : {idtrans: row.IDPO, kodetrans: row.KODEPO},
						cache   : false,
						success: function(msg){
							if (msg.success) {
								$("#dataGrid").DataTable().ajax.reload();
								window.open(base_url+"Inventori/Transaksi/PesananPembelian/cetak/"+row.IDPO, '_blank');
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
				else if (data.status != 'D') {
					window.open(base_url+"Inventori/Transaksi/PesananPembelian/cetak/"+row.IDPO, '_blank');
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
}

function ubahDetail(e){
	alert(e);
}

function hapusDetail(e){	
	$("#detailtab"+e+"").remove();
	hitung_ppn(countdetail);
}

$("#SUPPLIER").on("select2:select", function () {
    var value = $('#SUPPLIER').val().split(" | ");
	$('#INFOSUPPLIER').val(value[2]+", "+value[3]);
});

function simpan(){
	var jmlData = 0;
	var row     = [];
	for (var i=0;i<countdetail;i++) {
		if(typeof $('#NAMA'+i).html() != "undefined") // BARANG TELAH DIHAPUS
		{

			//KHUSUS DISKON KURS
			var totaldisc   = 0;
			var discpersen  = $("#DISKON"+i+" input").val();
			var hargaDiskon = $("#HARGA"+i+" input").val();
			
			if (discpersen != "0") {
				discpersen = discpersen.toString().split("+");
				for(var j=0;j<discpersen.length;j++){
					if(discpersen[j]!= "" && discpersen[j] <= 100 && discpersen[j]>0){
						discpersen[j] = parseFloat(discpersen[j]);
						disc = +((discpersen[j] * hargaDiskon / 100).toFixed(<?= $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>));
						totaldisc += disc;
					}
				}
			}
			row[jmlData] = 
				{
					idbarang    : $("#ID"+i).html(),
					jml         : $("#JUMLAH"+i+" input").val(),
					harga       : $("#HARGA"+i+" input").val(),
					hargakurs   : $("#HARGA"+i+" input").val(),
					satuan      : $("#SATUAN"+i).html(),
					discpersen  : $("#DISKON"+i+" input").val(),
					disckurs    : totaldisc,
					subtotal    : $("#SUBTOTAL"+i+' input').val(),
					subtotalkurs: $("#SUBTOTAL"+i+' input').val(),
					pakaippn    : $("#PAKAIPPN").val(),
					ppnrp       : ppnrp[i]
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
	else if($('#IDSUPPLIER').val() == "")
	{
		Swal.fire({
            title            : "Supplier harus diisi",
            type             : 'error',
            showConfirmButton: false,
            timer            : 1500
       });
	}	
	else
	{
		$.ajax({
			type    : 'POST',
			url     : base_url+'Inventori/Transaksi/PesananPembelian/simpan/', 
			data    : {	IDPO		 : $("#IDTRANS").val(),
						KODEPO       : $("#NOTRANS").val(),
						IDLOKASI     : $("#LOKASI").val(),
						IDSYARATBAYAR: $('#SYARATBAYAR').val().split(" | ")[3],
						IDSUPPLIER   : $("#IDSUPPLIER").val(),
						TGLTRANS     : $("#TGLTRANS").val(),
						TGLJATUHTEMPO: $("#TGLJTHTEMPO").val(),
						CATATAN      : $("#CATATAN").val(),
						TOTAL        : $("#TOTAL").val(),
						PPNRP        : $("#PPN").val(),
						GRANDTOTAL   : $("#GRANDTOTAL").val(),
						data_detail  : JSON.stringify(row),
						mode         : $("#mode").val(),
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
    $("#JMLMASSAL").val("");
    $("#DETAILBARANG").val("");
	$('.table-responsive').html("");
	$("#NOTRANS").val("");
	$("#TGLTRANS").datepicker('setDate', new Date());
	$("#SYARATBAYAR").val($('#SYARATBAYAR option:eq(0)').val()); //DEFAULT
	$('#SYARATBAYAR').trigger('change');
	$("#LOKASI").val($('#LOKASI option:eq(0)').val()); //DEFAULT
	$('#LOKASI').trigger('change');
	
	$("#IDSUPPLIER").val("");
	$("#NAMASUPPLIER").val("");
	$("#INFOSUPPLIER").val("");
	$("#PAKAIPPN").val("TIDAK");
	countdetail = 0;
	$('#TOTAL').val(0);
	$('#PPN').val(0);
	$('#GRANDTOTAL').val(0);
	$("#CATATAN").val("");	
	$("#TGLJTHTEMPO").attr('disabled','disabled');
	
	$("#TGLTRANS").removeAttr('disabled');
	$("#SYARATBAYAR").removeAttr('disabled');
	$("#LOKASI").removeAttr('disabled');
	$("#NAMASUPPLIER").removeAttr('disabled');
	$("#INFOSUPPLIER").removeAttr('disabled');
	$("#CATATAN").removeAttr('disabled');
	$("#btn_search").removeAttr('disabled');
	
	$("#btn_simpan, #btn_tambah").css('filter', '');
	$("#btn_simpan, #btn_tambah").removeAttr('disabled');
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
	var total    = 0;
	var totalBarang= 0;
	var ppn      = 0;
	
	for(var i = 0 ; i < jmlData;i++)
	{
		if(typeof $('#JUMLAH'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
		{	
		    totalBarang+= parseFloat($('#JUMLAH'+i+'  input').val());
		}
	}
	
	$('#TOTALBARANG').val(totalBarang);

	if(pakaippn == "EXCL")
		{
			for(var i = 0 ; i < jmlData;i++)
			{
				if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
				{				
					total+= parseFloat($('#SUBTOTAL'+i+' input').val());
					ppnrp[i] = (parseFloat($('#SUBTOTAL'+i+'  input').val())*("<?=$PPNPERSEN?>"/100)).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
					ppn  += parseFloat(ppnrp[i]);
				}
			}
			
			$('#TOTAL').val(total);
		
			//PPN
			$('#PPN').val(ppn);
			
			//GRANDTOTAL
			$('#GRANDTOTAL').val((parseFloat($('#TOTAL').val())+(parseFloat($('#PPN').val()))));
		}
		else if(pakaippn == "INCL")
		{
			for(var i = 0 ; i < jmlData;i++)
			{
				if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
				{				
					total+= parseFloat($('#SUBTOTAL'+i+' input').val());
					ppnrp[i] = (parseFloat($('#SUBTOTAL'+i+'  input').val())/11).toFixed("<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
					ppn  += parseFloat(ppnrp[i]);
				}
			}
			
			$('#TOTAL').val(total);

			//PPN
			$('#PPN').val(ppn);			
			
			//GRANDTOTAL
			$('#GRANDTOTAL').val((parseFloat($('#TOTAL').val())));			
		}
		else
		{
			for(var i = 0 ; i < jmlData;i++)
			{
				if(typeof $('#SUBTOTAL'+i+' input').val() != "undefined") // BARANG TELAH DIHAPUS
				{				
					total+= parseFloat($('#SUBTOTAL'+i+' input').val());
					ppnrp[i] = 0;
				}
			}
			
			$('#TOTAL').val(total);
			
			//PPN
			$('#PPN').val(0);
		
			//GRANDTOTAL
			$('#GRANDTOTAL').val((parseFloat($('#TOTAL').val())));
		}
}

function hitung_diskon(discpersen,hargaDiskon,index){
	var totaldisc 		 = 0;
	var totalHargaDiskon = 0;
	if (discpersen != "0") {
		
		discpersen = discpersen.toString().split("+");

		var discDescription = "";
		for(var i=0;i<discpersen.length;i++){
			if(discpersen[i]!= "" && discpersen[i] <= 100 && discpersen[i]>0){
				discpersen[i] = parseFloat(discpersen[i]);
				disc = +((discpersen[i] * hargaDiskon / 100).toFixed(<?= $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>));
				totaldisc += disc;
				hargaDiskon -= disc;
				discDescription += discpersen[i]+"+";
			}
		}
		discDescription = discDescription.slice(0,-1);
		totalHargaDiskon = parseFloat($("#JUMLAH"+index+" input").val())*hargaDiskon;
	}
	else
	{
		totalHargaDiskon = (parseFloat($("#JUMLAH"+index+" input").val())* parseFloat($("#HARGA"+index+" input").val()));
	}

	$("#SUBTOTAL"+index+" input").val(Math.round(totalHargaDiskon* 100)/100 );
}
//LIMIT ANGKA SAJA
function numberInput(evt,e,field) {
	var inputLength;
	// 0 = jumlah detail
	// 1 = jumlah ketika tambah
	// 2 = diskon detail
	// 3 = harga
	
	if(field == 0)
	{
		inputLength = $("#JUMLAH"+e+" input").val().length;
	}
	else if(field == 1){
		inputLength = $("#jml").val().length;
	}
	
	var charCode = (evt.which) ? evt.which : event.keyCode;

// 	if (charCode == 13) {	
		if(inputLength == 0) //KALAU FIELD KOSONG
		{
			$("#jml").val(1);
			$("#diskon").val(0);
			$("#JUMLAH"+e+" input").val(0);
		}
		
		if(field != 1){
		    var String = "";
            for(var x = 0 ; x < $("#DISKON"+e+" input").val().length; x++)
            {
                if($("#DISKON"+e+" input").val()[x] == "+" || $("#DISKON"+e+" input").val()[x] == "0" || $("#DISKON"+e+" input").val()[x] == "1" || $("#DISKON"+e+" input").val()[x] == "2" || $("#DISKON"+e+" input").val()[x] == "3" || $("#DISKON"+e+" input").val()[x] == "4" || $("#DISKON"+e+" input").val()[x] == "5" || $("#DISKON"+e+" input").val()[x] == "6" || $("#DISKON"+e+" input").val()[x] == "7" || $("#DISKON"+e+" input").val()[x] == "8" || $("#DISKON"+e+" input").val()[x] == "9" )
                {
                    String += $("#DISKON"+e+" input").val()[x];
                }
                
            }
            $("#DISKON"+e+" input").val(String)
            
			hitung_diskon($("#DISKON"+e+" input").val(),parseFloat($("#HARGA"+e+" input").val()),e);
		}
        hitung_ppn(countdetail);

// 	}
	if(field == 2 && charCode == 43  || charCode == 46) //KHUSUS DISKON SOAL BISA +
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

