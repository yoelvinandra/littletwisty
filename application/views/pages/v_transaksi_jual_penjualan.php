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
	
	#tab_all_transaksi li.active{
	    border-top-color: #008d4c !important;
	}
	
	.circle-icon-1 {
      width: 10px;
      height: 10px;
      background-color: blue;
      border-radius: 50%;
      display: inline-block; /* Keep it inline if part of a list */
    }
    
    .circle-icon-2 {
      width: 10px;
      height: 10px;
      background-color: red;
      border-radius: 50%;
      display: inline-block; /* Keep it inline if part of a list */
    }

  </style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Transaksi Penjualan
       <?php echo form_open_multipart(base_url().'Penjualan/Transaksi/Penjualan/importExcel', ['id' => 'excelForm']); ?>     
            <button style="float:right; margin-top:-30px; margin-right:10px;" type="button" class="btn btn-primary" onclick="openFileExcel()" >Upload Excel Penjualan</button> 
            <input style="display:none;" type="file"  name="excelFile" id="excelFile" accept=".xls,.xlsx" onchange="importExcel()" required>
      <?php echo form_close(); ?>
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol> -->
</section>

<section class="content">
  <div class="nav-tabs-custom"  style="padding:0px; margin:0px;">
            <ul class="nav nav-tabs" id="tab_all_transaksi">
                <li class="active"><a href="#tab_penjualan" onclick="javascript:changeTabMarketplace(0)" data-toggle="tab"><b>Penjualan</b></a></li>
				<li id="header_shopee"><a href="#tab_shopee" data-toggle="tab" onclick="javascript:changeTabMarketplace(1)"><img alt="Shopee" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png">&nbsp;&nbsp;<span id="notifShopee-1"></span>&nbsp;&nbsp;<span id="notifShopee-2"></span></a></li>
			    <!--<li id="header_tiktok"><a href="#tab_tiktok" data-toggle="tab" onclick="javascript:changeTabMarketplace(2)"><img alt="Tiktok" style="width:60px;"src="data:image/png;base64,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"></a></li>-->
				<!--<li id="header_lazada"><a href="#tab_lazada" data-toggle="tab" onclick="javascript:changeTabMarketplace(3)"><img alt="Lazada" style="width:60px;"src="data:image/jpeg;base64,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"></a></li>-->
            </ul>
            <div class="tab-content" style="padding:0px; margin:0px;">
                    <div class="tab-pane" id="tab_shopee">
                        <?php if($_GET['i'] == 1){include("v_transaksi_jual_penjualan_shopee.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_tiktok">
                         <?php if($_GET['i'] == 2){include("v_transaksi_jual_penjualan_tiktok.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_lazada">
                        <?php if($_GET['i'] == 3){include("v_transaksi_jual_penjualan_lazada.php");}?>
                    </div>
                    <div class="tab-pane active" id="tab_penjualan">
                        <div class=" ">
                              <!-- Main row -->
                              <div class="row">
                                  <div class="col-md-12">
                                    <div class="box" style="border:0px; padding:0px; margin:0px;">
                                        <div class="box-header form-inline">
                            				<button class="btn btn-success" onclick="javascript:tambah()">Tambah/F2</button>
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
                                                <div class="box-body ">
                                                    <table id="dataGrid" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                                        <!-- class="table-hover"> -->
                                                        <thead>
                                                        <tr>
                                                            <th width="35px"></th>
                                                            <th width="35px"></th>
                                                            <th>Hari</th>
                                                            <th>Tgl</th>
                                                            <th>J Transaksi</th>
                                                            <th width="150px">No. Penjualan</th>
                                                            <th width="150px"></th>
                                                            <th>Grand Total</th>
                                                            <th width="35px"></th>
                                                            <th>Potongan</th>
                                                            <th>Grand Total %</th>
                                                            <th>Pembayaran</th>
                                                            <th width="200px">Customer</th>
                                                            <th width="200px">Catatan</th>
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
                                            <div class="tab-pane" id="tab_form">
                                                <div class="box-body"  style="padding-left:0px; padding-right:0px;">
                            					 <div class="col-md-12">
                                                    <div class="box-body"  style="padding-left:0px; padding-right:0px;">
                            							
                            							<div class="form-group col-md-8 col-sm-8 ol-xs-12">
                            								<input type="hidden" id="mode" name="mode">
                            								<input type="hidden" id="IDTRANS" name="IDTRANS">
                            								
                            								<input type="hidden" id="PEMBAYARAN" name="PEMBAYARAN">
                            								<input type="hidden" id="POTONGANPERSEN" name="POTONGANPERSEN">
                            								<input type="hidden" id="POTONGANRP" name="POTONGANRP">
                            								<input type="hidden" id="JENISTRANSAKSI" name="JENISTRANSAKSI" value="OFFLINE">
                            								
                            								<div class="col-md-2 col-sm-2 ol-xs-12" style="padding: 0px">
                            									<label >No Jual</label>
                            								</div>
                            								<div class="col-md-9 col-sm-9 col-xs-12" style="padding: 0px 0px 5px 0px">
                            									<input type="text" class="form-control" id="NOTRANS" name="NOTRANS" style="border:1px solid #B5B4B4; border-radius:1px;" placeholder="Auto Generate" readonly>
                            									<input type="hidden" class="form-control" id="IDTRANS" name="IDTRANS">
                            								</div>
                            							</div>
                            							<div class="form-group col-md-4">
                            								
                            								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px">
                            									<label>Tgl Jual</label>
                            								</div>
                            								<div class="col-md-8 col-sm-8 col-xs-12"  style="padding: 0px 0px 5px 0px">
                            									<input type="text" class="form-control" id="TGLTRANS"  name="TGLTRANS" style=" border:1px solid #B5B4B4; border-radius:1px;" placeholder="Tgl Jual">
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
                            								<div class="col-md-2 col-sm-2 col-xs-12" style="padding: 0px; margin-top:15px;">
                            									<label >Scan </label>
                            								</div>
                            								<div class="col-md-9  col-sm-9 col-xs-12"  style="padding: 0px 0px 5px 0px;  margin-top:15px;">
                            									<input type="text" class="form-control"  
                            									id="INPUTBARCODE" style="background:#E0FFFF;" placeholder="BARCODE/F8 " value="">
                            								</div>
                            								<div class="col-md-2 col-sm-2 col-xs-12 SALINDETAILBARANG" style="padding: 0px; margin-top:15px;">
                            									<label >Salin dari </label>
                            								</div>
                            								<div class="col-md-4  col-sm-4 col-xs-12 SALINDETAILBARANG"  style="padding: 0px 0px 5px 0px;  margin-top:15px;" >
                            								    <div class="input-group margin" style="padding:0; margin:0">
                                							        <input type="text"  class="form-control" id="DETAILBARANG" style="border:1px solid #B5B4B4; border-radius:1px;" placeholder="Kode Transaksi">
                                							        <div class="input-group-btn">
                                										<button type="button" id="btn_salin" class="btn btn-primary btn-flat" data-id="7">Salin</button>
                                									</div>
                            									</div>
                            								</div>
                            							</div>
                            							<div class="form-group col-md-4">
                            								<div class="col-md-4 col-sm-4 col-xs-12" style="padding: 0px">
                            									<label>Lokasi</label>
                            								</div>
                            								<div class="col-md-8 col-sm-8 col-xs-12"  style="padding: 0px 0px 5px 0px">
                            									<select class="form-control" id="LOKASI" name="LOKASI" placeholder="Lokasi..." style="width:100%;">
                                        							<?=comboGrid("model_master_lokasi")?>
                                        						</select>
                            								</div>
                            								<input type="text" style="font-size:29pt; text-align:right; height:75px;  background:#2a3f54; border-color:#2a3f54; color:white; margin-top:55px;"   id="GRANDTOTALHEADER" class="form-control" value="0" readonly></input>
                            							</div>
                            							<div class="form-group col-md-4 col-sm-4 col-xs-12" style="margin-bottom:20px; float:right;">
                            								
                            							</div>
                            							
                            						  
                            						   <!-- /top tiles -->
                            						<!-- DETAIL -->
                            								<div class="col-md-12 col-sm-12 col-xs-12">
                            									<div role="tabpanel" data-example-id="togglable-tabs" >
                            
                            										<div class="col-md-6 col-sm-6 col-xs-6">
                            											<button type="button" id="btn_tambah" class="btn btn-success" onclick="tambahDetail()" data-toggle="modal" data-target="#modal-barang"  style="margin-right:35%">Barang/F4</button>
                            										</div>
                            										<div class="col-md-6 col-sm-6 col-xs-6" align="right" style="margin-left:0px;  margin-bottom:5px;">
                            											<button id="btn_simpan" style="padding:6px 10px 6px 10px;" onclick="simpan()" >Bayar/F9</button>	
                            										</div>
                            										
                            										<div id="trans_content" class="tab-content trans-content" style="padding-left:0px;padding-right:0px;" >
                            											<div role="tabpanel" class="tab-pane fade active in" id="tab_trans0" >
                            											<div class="col-md-12 col-sm-12 col-xs-12" style="border:1px solid; background:white; border-radius:0px 0px 3px 3px;padding-top:15px; padding-left:0px; padding-right:0px; ">
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
                            													<div class="col-md-4 col-sm-4 col-xs-12" hidden>
                            														<div align="left" style="font-weight:bold">Service Charge</div>
                            														<div class="input-group form-group">	
                            															<input type="text" readonly class="form-control has-feedback-left col-md-2 col-sm-2 col-xs-2" id="SERVICECHARGE" placeholder="Service Charge" value="0">
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
                            									<div class="modal-dialog modal-lg">
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
                            														<th></th>
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
                            </div>
                            <!-- /.col -->
                          </div>
                        </div>
                    </div>
                </div>
	
</div>
</section>
<input type="hidden" id="status">
<input type="hidden" id="jenis_harga">
<script>

if('<?=$_SESSION[NAMAPROGRAM]['USERNAME'] == 'USERTES'?>')
{
    $("#header_shopee").hide();
    $("#header_tiktok").hide();
    $("#header_lazada").hide();
}

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


const params = new URLSearchParams(window.location.search);

const index = params.get('i')??0;

 if(index == 1)
 {
     $('.nav-tabs a[href="#tab_shopee"]').tab('show');
    //  changeTabShopee(2);
    //  changeTabShopee(3);
    //  changeTabShopee(4);
    //  changeTabShopee(1);
 }
 else if(index == 2)
 {
     $('.nav-tabs a[href="#tab_tiktok"]').tab('show');
    //  changeTabTiktok(1);
 }
 else if(index == 3)
 {
     $('.nav-tabs a[href="#tab_lazada"]').tab('show');
    //  changeTabLazada(1);
 }

function changeTabMarketplace(index){
    
    let url = new URL(window.location.href);
    url.searchParams.set("i", index);
    
     if(index == 0)
     {
         $("#tab_grid").hide();
     }
     else if(index == 1)
     {
         $("#tab_shopee").hide();
     }
     else if(index == 2)
     {
         $("#tab_tiktok").hide();
     }
     else if(index == 3)
     {
         $("#tab_lazada").hide();
     }
    
    // Redirect to the updated URL
    window.location.href = url.toString();
}

$(document).ready(function(){

	$('#TGLTRANS, #tgl_awal_filter, #tgl_akhir_filter').datepicker({
		format: 'yyyy-mm-dd',
		 autoclose: true, // Close the datepicker automatically after selection
        container: 'body', // Attach the datepicker to the body element
        orientation: 'bottom auto' // Show the calendar below the input
	});
	$("#tgl_awal_filter").datepicker('setDate', "<?=TGLAWALFILTER?>");
	$("#TGLTRANS , #tgl_akhir_filter").datepicker('setDate', new Date());
	$("#status").val('I,S,P,D');
	
    $("#notifShopee-1").html("");
    $("#notifShopee-2").html("");
    //ADA PESANAN BARU
    $.ajax({
		type    : 'POST',
		url     : base_url+'Shopee/dataGrid/',
		data    : {state:1,status:'UNPAID,READY_TO_SHIP',tglawal:"<?=TGLAWALFILTERMARKETPLACE?>",tglakhir:$("#TGLTRANS").val()},
		dataType: 'json',
		success : function(msg){
		    if(msg.rows.length > 0)
		    { 
		        $("#notifShopee-1").addClass("circle-icon-1");
		    }
	}});
	
	//ADA PESANAN RETUR
    $.ajax({
		type    : 'POST',
		url     : base_url+'Shopee/dataGrid/',
		data    : {state:4,status:'TO_RETURN|REQUESTED,TO_RETURN|PROCESSING,TO_RETURN|JUDGING-SELLER_DISPUTE',tglawal:"<?=TGLAWALFILTERMARKETPLACE?>",tglakhir:$("#TGLTRANS").val()},
		dataType: 'json',
		success : function(msg){
		    if(msg.rows.length > 0)
		    { 
		        $("#notifShopee-2").addClass("circle-icon-2");
		    }
	}});
	
	//$("#GRANDTOTALHEADER").css('width',(screen.width*23/100)+"px");
	//$("#CATATAN").css('width',(screen.width*23/100)+"px");
	$('body').keyup(function(e){
		hotkey(e);
	});
	
	$("#NAMACUSTOMER").attr("readonly","readonly");
	
	$('#TOTAL').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
	$('#POTONGANRPP').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
	$('#PPN').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
	$('#SERVICECHARGE').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
	$('#GRANDTOTAL').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
	$('#GRANDTOTALHEADER').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
	$('#PEMBAYARANP').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
	
    $('.select2').select2({
      templateResult: function(state) {
        if (!state.id) return state.text;
        const desc = $(state.element).data('desc') || '';
        return `<strong>${state.text}</strong><br><small>${desc}</small>`;
      },
      templateSelection: function(state) {
        if (!state.id) return state.text;
        const desc = $(state.element).data('desc') || '';
        return state.text;
      },
      escapeMarkup: function(markup) { return markup; }
    });
	
	$("#btn_salin").click(function(){
	    if($("#DETAILBARANG").val() != "")
        {
    	    $.ajax({
    			type    : 'POST',
    			url     : base_url+'Penjualan/Transaksi/Penjualan/loadDetail/',
    			data    : {kode: $("#DETAILBARANG").val(),mode:"ubah",idcustomer:$("#IDCUSTOMER").val()},
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
	//-------
	
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
	
	$("#LOKASI").change(function() {
        for(var i = 0 ; i < countdetail; i++)
		{	
			$("#detailtab"+i+"").remove();
		}
			
		countdetail= 0;
		hitung_ppn(countdetail);
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
            { data: 'JENISTRANSAKSI' ,className:"text-center"},	
            { data: 'KODEPENJUALAN' ,className:"text-center"},
            { data: 'KODETRANSREFERENSI' ,className:"text-center",visible: false},
            { data: 'GRANDTOTAL', render:format_number, className:"text-right"},
            { data: 'POTONGANPERSEN',visible: false,},
            { data: 'POTONGANRP', render:format_number, className:"text-right"},
            { data: 'GRANDTOTALDISKON', render:format_number, className:"text-right"},
            { data: 'PEMBAYARAN', render:format_number, className:"text-right"},
            { data: 'NAMACUSTOMER' ,className:"text-left"},
            { data: 'CATATAN' ,className:"text-left"},
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
                "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash'></i></button> <button id='btn_cetak' class='btn btn-warning'><i class='fa fa-print' ></i></button>&nbsp;&nbsp;<button id='btn_cetak_pajak' class='btn' style='background:white; color:black'><i class='fa fa-print' ></i></button>"	
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
		else if(mode == "btn_cetak"){ cetak(row,'NON');}
		else if(mode == "btn_cetak_pajak"){ cetak(row,'PAJAK');}

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
			{
                data: 'ALAMAT',
                render: function(data, type, row) {
                    if(data == null)
                    {
                        data = "";
                    }
                    // Substring the address to the first 30 characters
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
			{ data: 'TELP' },
			{ data: 'CATATANCUSTOMER' ,visible: false,},
        ],
		
    });
	
	//BUAT NAMBAH BARANG BIASA
	$('#table_customer tbody').on('click', 'tr', function () {
		var row = $('#table_customer').DataTable().row( this ).data();
		$("#modal-customer").modal('hide');	
		
		
		var alamat;
		$("#NAMACUSTOMER").val(row.NAMA);
		$("#IDCUSTOMER").val(row.ID);
		$("#CATATANCUSTOMER").attr('placeholder',row.CATATANCUSTOMER);
		
		if(row.KONSINYASI == 1){
		    readonlyHarga='';
		    
    		$("#jenis_harga").val("KONSINYASI_"+row.ID);
    		
		}
		else{
		    readonlyHarga='readonly';
    		$("#jenis_harga").val("2_"+row.ID);
		}
		
		if($("#JENISTRANSAKSI").val() == "ONLINE")
        {
          $('#PEMBAYARANP').attr('readonly','readonly');
          readonlyPotongan = 'readonly';
        }
        else
        {
          $('#PEMBAYARANP').removeAttr('readonly');
          readonlyPotongan = '';
		  if(row.MEMBER == 1){readonlyPotongan='readonly';$("#POTONGANPERSEN").val(row.DISKONMEMBER);}else{readonlyPotongan=''; $("#POTONGANRP").val(0); $("#POTONGANPERSEN").val("0");}
        }
		
		
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

function openFileExcel(){
     document.getElementById('excelFile').click();
}

function importExcel(){
    document.getElementById("excelForm").submit();
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function hotkey(e){
	if(e.keyCode == 113) // F2
	{
		tambah();
	}
	else if(e.keyCode == 115) // F4
	{
		$("#modal-barang").modal('show');
	}
	else if(e.keyCode == 119) // F8
	{
		$("#INPUTBARCODE").focus();
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
		data    : {barcode:"XXXXX",qty:"1",mode:"LANGSUNG",jenisharga:getJenisHarga()},
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
				$("#JUMLAH"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
				$("#HARGA"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				$("#HARGA"+countdetail+" input").val(parseInt(row.HARGA));
				$("#DISKONRP"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				$("#SUBTOTAL"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				hitung_diskon(row.DISKON,parseInt(row.HARGA),countdetail);

                $('.x_content').animate({
                  scrollTop: $('.table-responsive')[0].scrollHeight
                }, 0);  // 0ms delay, instantly scroll to the bottom

                
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
			data    : {barcode:val,qty:jml,mode:mode,jenisharga:getJenisHarga()},
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
							    $("#JUMLAH"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
							    $("#HARGA"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				                $("#HARGA"+countdetail+" input").val(parseInt(msg.rows.HARGA));
								$("#DISKONRP"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
								$("#SUBTOTAL"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
								hitung_diskon(msg.rows.DISKON,parseInt(msg.rows.HARGA),countdetail);
                                $('.x_content').animate({
                                  scrollTop: $('.table-responsive')[0].scrollHeight
                                }, 0); 
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
    $("#JENISTRANSAKSI").val("OFFLINE");
    readonlyHarga = "readonly";
    readonlyPotongan = "";
    $("#btn_simpan").text("Bayar/F9");
	$("#btn_simpan, #btn_tambah, #LOKASI, #TGLTRANS, #btn_search").css('filter', '');
	$("#btn_simpan, #btn_tambah, #LOKASI, #TGLTRANS, #btn_search").removeAttr('disabled');
					
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
	$("#INPUTBARCODE").val("");
	$("#DETAILBARANG").val("");
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
			
    			if(row.JENISTRANSAKSI == "ONLINE")
    			{
    			  	$("#btn_tambah").css('filter', 'grayscale(100%)');
    				$("#btn_tambah").attr('disabled', 'disabled');
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
		                
                        $("#JENISTRANSAKSI").val(row.JENISTRANSAKSI);
						$("#LOKASI").val(row.IDLOKASI);
						$("#NOTRANS").val(row.KODEPENJUALAN);
						$("#IDTRANS").val(row.IDPENJUALAN);
						$("#IDCUSTOMER").val(msg[0].IDCUSTOMER);
						$("#NAMACUSTOMER").val(msg[0].NAMACUSTOMER);
						$("#CATATANCUSTOMER").val(row.CATATANCUSTOMER);
						$("#TGLTRANS").val(row.TGLTRANS);
						$("#CATATAN").val(row.CATATAN);
						
						$("#CATATANCUSTOMER, #CATATAN").attr('readonly', 'readonly');
						
                        if($("#JENISTRANSAKSI").val() == "ONLINE")
                        {
                          $('#PEMBAYARANP').attr('readonly','readonly');
                		  readonlyPotongan = 'readonly';
                        }
                        else
                        {
                          $('#PEMBAYARANP').removeAttr('readonly');
                		  readonlyPotongan = '';
                          if(row.KONSINYASI == 1){readonlyHarga = "";} else{readonlyHarga = "readonly";}
                          if(row.MEMBER == 1){readonlyPotongan='readonly'; }else{readonlyPotongan='';}
                        }
                        
						var totalBarang = 0;
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
							$("#JUMLAH"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY']?>");
							$("#HARGA"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
				            $("#HARGA"+countdetail+" input").val(parseInt(msg[i].HARGA));
							$("#DISKONRP"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
							$("#SUBTOTAL"+countdetail+" input").number(true, "<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
							
							if(typeof $('#JUMLAH'+countdetail+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
		                    {
							    totalBarang+= parseInt($('#JUMLAH'+countdetail+'  input').val());
		                    }
		                    
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
								
								$('#POTONGANPERSEN').val(row.POTONGANPERSEN);
								$('#POTONGANRP').val(parseInt(row.POTONGANRP));
								//GRANDTOTAL
								$('#GRANDTOTAL').val((parseInt($('#TOTAL').val())));
		                        $('#GRANDTOTALHEADER').val((parseInt($('#GRANDTOTAL').val())));
							}
						}
						
	                    $('#TOTALBARANG').val(totalBarang);
						$('#PEMBAYARAN').val(parseInt(row.PEMBAYARAN));
						$('#GRANDTOTALP').val(parseInt(row.GRANDTOTALDISKON));
                        
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
    if($("#JENISTRANSAKSI").val() != "OFFLINE")
    {
         Swal.fire({
        		title            : 'Tidak bisa membatalkan transaksi, karena ini Penjualan Online',
        		type             : 'error',
        		showConfirmButton: false,
        		timer            : 1500
        	 });
    }
    else
    {
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
					
					$.ajax({
                        type      : 'POST',
                        url       : base_url+'Shopee/setStokBarang',
                        data      : {
                            'idtrans' : row.IDPENJUALAN, 
                            'jenistrans' : 'PENJUALAN',
                        },
                        dataType  : 'json',
                        beforeSend: function (){
                            //$.messager.progress();
                        },
                        success: function(msg){
                            if (msg.success) {
                                if(msg.msg != "")
                                {
                                    Swal.fire({
                                        title            : msg.msg,
                                        type             : 'success',
                                        showConfirmButton: false,
                                        timer            : 1500
                                    });
                                }
                            } else {
                                Swal.fire({
                                    title            : msg.msg,
                                    type             : 'error',
                                    showConfirmButton: false,
                                    timer            : 1500
                                });
                            }
                        },
                        
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

function cetak(row,type){
	get_akses_user('<?=$kodemenu?>', function(data){
		if (data.CETAK==1) {
			get_status_trans("Penjualan/Transaksi/Penjualan",row.IDPENJUALAN, function(data){
				if (data.status =='I') {
					$.ajax({
						type    : 'POST',
						dataType: 'json',
						url     : base_url+'Penjualan/Transaksi/Penjualan/ubahStatusJadiSlip',
						data    : {idtrans: row.IDPENJUALAN, kodetrans: row.KODEPENJUALAN},
						cache   : false,
						success : function(msg){
							if (msg.success) {
								$("#dataGrid").DataTable().ajax.reload();
								if(type == 'PAJAK')
								{
								    window.open(base_url+"Penjualan/Transaksi/Penjualan/cetakPajak/"+row.IDPENJUALAN, '_blank');
								}
								else
								{
								    window.open(base_url+"Penjualan/Transaksi/Penjualan/cetak/"+row.IDPENJUALAN, '_blank');
								}
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
				else if(data.status != 'D'){
					if(type == 'PAJAK')
					{
					    window.open(base_url+"Penjualan/Transaksi/Penjualan/cetakPajak/"+row.IDPENJUALAN, '_blank');
					}
					else
					{
					    window.open(base_url+"Penjualan/Transaksi/Penjualan/cetak/"+row.IDPENJUALAN, '_blank');
					}
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
        if($('#POTONGANPERSENP').val() == "")
        {
            $('#POTONGANPERSENP').val(0);
        }
        else if($('#POTONGANPERSENP').val().length > 1)
        {
            if($('#POTONGANPERSENP').val()[0] == "0")
            {
              $('#POTONGANPERSENP').val($('#POTONGANPERSENP').val()[1]);
            }
        }
        
        var String = "";
        for(var x = 0 ; x < $('#POTONGANPERSENP').val().length; x++)
        {
            if($('#POTONGANPERSENP').val()[x] == "+" || $('#POTONGANPERSENP').val()[x] == "0" || $('#POTONGANPERSENP').val()[x] == "1" || $('#POTONGANPERSENP').val()[x] == "2" || $('#POTONGANPERSENP').val()[x] == "3" || $('#POTONGANPERSENP').val()[x] == "4" || $('#POTONGANPERSENP').val()[x] == "5" || $('#POTONGANPERSENP').val()[x] == "6" || $('#POTONGANPERSENP').val()[x] == "7" || $('#POTONGANPERSENP').val()[x] == "8" || $('#POTONGANPERSENP').val()[x] == "9" )
            {
                String += $('#POTONGANPERSENP').val()[x];
            }
            
        }
        $('#POTONGANPERSENP').val(String);
        
        var totaldisc 		 = 0;
        var potonganPersen   = $('#POTONGANPERSENP').val().toString().split("+");
        var totalsemua       = parseInt($('#GRANDTOTAL').val());
        var sisa             = totalsemua;
        for(var i=0;i<potonganPersen.length;i++){
        	potonganPersen[i] = parseFloat(potonganPersen[i]);
        	disc = +((potonganPersen[i] * sisa / 100).toFixed(<?= $_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>));
        	sisa -= disc;
        	totaldisc += disc;
        }
        $('#POTONGANRPP').val(totaldisc);
        
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
    
	
	if(parseInt($('#POTONGANRPP').val()) > parseInt($('#GRANDTOTAL').val()))
	{
	     Swal.fire({
				title            : 'Potongan Harga tidak boleh lebih besar dari Pembayaran',
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
			});
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
            					<input type="text" style="font-size:25pt; text-align:right; height:55px; width:25%; float:left;"  class="form-control has-feedback-left" id="POTONGANPERSENP" pattern="[0-9+]*" placeholder="%" onkeyup="getPotongan(\'PERSEN\')" value="0" '+readonlyPotongan+'>\
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
    			      if($("#JENISTRANSAKSI").val() != "OFFLINE")
                        {
                             Swal.fire({
                            		title            : 'Tidak bisa melakukan pembayaran, karena ini Penjualan Online',
                            		type             : 'error',
                            		showConfirmButton: false,
                            		timer            : 1500
                            	 });
                        }
                        else
                        {
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
                							
                							var dataBarang = [];
                        					for(var x = 0 ; x < row.length; x++)
                        					{
                        					    dataBarang.push(row[x].idbarang);
                        					}
                        					
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
                                                    if (msg.success) {
                                                        if(msg.msg != "")
                                                        {
                                                            Swal.fire({
                                                                title            : msg.msg,
                                                                type             : 'success',
                                                                showConfirmButton: false,
                                                                timer            : 1500
                                                            });
                                                        }
                                                    } else {
                                                        Swal.fire({
                                                            title            : msg.msg,
                                                            type             : 'error',
                                                            showConfirmButton: false,
                                                            timer            : 1500
                                                        });
                                                    }
                                                },
                                                
                                            });
                                            
                							$("#dataGrid").DataTable().ajax.reload();
                							$('.nav-tabs a[href="#tab_grid"]').tab('show');
                				// 			var row ={};
                				// 			row = msg.row;
                							
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
    			  }
    			})
    			
    			$("#PEMBAYARANP").focus();
    			$('#GRANDTOTALP').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
    			$('#PEMBAYARANP').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
    			$('#POTONGANRPP').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
    	
    			//PEMBAYARAN
    			$('#POTONGANPERSENP').val($('#POTONGANPERSEN').val());
    			if($('#POTONGANPERSENP').val() != 0){
    			    getPotongan('PERSEN');
    			}
    			else
    			{ 
    			    $('#POTONGANRPP').val(parseInt($('#POTONGANRP').val()));
    			}
    			
    			$('#GRANDTOTALP').val((parseInt($('#GRANDTOTAL').val())) - (parseInt($('#POTONGANRPP').val())));
    			
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
	$("#LOKASI").val($('#LOKASI option:eq(0)').val());
	$('#LOKASI').trigger('change');	
	
					
	$("#NAMACUSTOMER").val('BIASA');
	$("#IDCUSTOMER").val(1);
	$("#INPUTBARCODE").val("");
	$("#DETAILBARANG").val("");

	$('#TOTAL').val(0);
	$('#PPN').val(0);
	$('#DISKON').val(0);
	$('#POTONGANRP').val(0);
	$('#POTONGANPERSEN').val("0");
	$('#GRANDTOTAL').val(0);
	$('#GRANDTOTALHEADER').val(0);	
	$('#SERVICECHARGE').val(0);	
	$('#PEMBAYARAN').val(0);	
	$("#CATATANCUSTOMER, #CATATAN").removeAttr('readonly');
	
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
	var total = 0;
	var totalBarang = 0;
	var ppn = 0;
	
	for(var i = 0 ; i < jmlData;i++)
	{   
	    if(typeof $('#JUMLAH'+i+'  input').val() != "undefined") // BARANG TELAH DIHAPUS
		{
		    totalBarang+= parseInt($('#JUMLAH'+i+'  input').val());
		}
	}
	
	$('#TOTALBARANG').val(totalBarang);

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
		
		$("#DISKONRP"+index+" input").val(totaldisc);
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
    $('#KEMBALI').number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
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
		    var String = "";
            for(var x = 0 ; x < $("#DISKON"+e+" input").val().length; x++)
            {
                if($("#DISKON"+e+" input").val()[x] == "+" || $("#DISKON"+e+" input").val()[x] == "0" || $("#DISKON"+e+" input").val()[x] == "1" || $("#DISKON"+e+" input").val()[x] == "2" || $("#DISKON"+e+" input").val()[x] == "3" || $("#DISKON"+e+" input").val()[x] == "4" || $("#DISKON"+e+" input").val()[x] == "5" || $("#DISKON"+e+" input").val()[x] == "6" || $("#DISKON"+e+" input").val()[x] == "7" || $("#DISKON"+e+" input").val()[x] == "8" || $("#DISKON"+e+" input").val()[x] == "9" )
                {
                    String += $("#DISKON"+e+" input").val()[x];
                }
                
            }
            $("#DISKON"+e+" input").val(String)
        
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

function currency(amount){
   return Number(amount).toLocaleString()
}

</script>

