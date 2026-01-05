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
	
	//LACAK

    .step-tracker {
      position: relative;
      margin: 50px auto;
      max-width: 300px; /* Adjust as needed */
      padding-left: 40px;
      border-left: 4px solid #ccc;
    }
    
    .step {
      position: relative;
      padding-bottom: 50px;
    }
    
    .step::before {
      content: '';
      position: absolute;
      left: 10px;
      top: 20px;
      width: 4px;
      height: calc(100% - 20px);
      background-color: #ccc;
      z-index: 0;
    }
    
    .step:last-child::before {
      display: none; /* remove line after last step */
    }
    
    .step .circle {
      width: 24px;
      height: 24px;
      background-color: #ccc;
      border-radius: 50%;
      position: absolute;
      line-height: 24px;
      color: white;
      font-weight: bold;
      font-size: 12px;
      text-align: center;
      z-index: 1;
      transition: 0.3s ease;
    }
    
    .step .label-step {
      font-size: 14px;
      color: black;
      margin-right:20px;
      margin-left: 40px;
      height:50px;
      white-space: pre-wrap;      /* CSS3 */   
      white-space: -moz-pre-wrap; /* Firefox */    
      white-space: -pre-wrap;     /* Opera <7 */   
      white-space: -o-pre-wrap;   /* Opera 7 */    
      word-wrap: break-word;      /* IE */
    }
    
    .step.active .circle,
    .step.completed .circle {
      background-color: #3296ff;
      
    }
    
    .step.completed .label-step {
      color: #3296ff;
    }
    
    #tab_kirim_tiktok .active a {
         color:black;
         font-weight:bold;
    }
    
    #tab_kirim_tiktok li a {
        color:#949494;
        font-weight:normal;
    }
    
    #tab_retur_tiktok .active a {
         color:black;
         font-weight:bold;
    }
    
    #tab_retur_tiktok li a {
        color:#949494;
        font-weight:normal;
    }
    
    #modal-retur-tiktok .modal-dialog {
        max-width: 700px;
        margin: 30px auto;
    }
    
    #modal-retur-tiktok .modal-content {
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    #modal-retur-tiktok .modal-body {
        overflow-y: auto;
        flex: 1 1 auto;
    }
    
    .bootstrap-datetimepicker-widget {
       position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%);
        z-index: 1060; /* Bootstrap modal = 1050 */
      min-width: 300px !important; /* You can adjust this value */
    }

  </style>
  
     <div class=" ">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
              <div class="box" style="border:0px; padding:0px; margin:0px;">
                  <div class="box-header form-inline">
                    <button class="btn" style="background:#000; color:white;" onclick="javascript:sinkronTiktokNow()">Sinkronisasi Hari Ini</button>&nbsp;
      				<button class="btn" style="background:white; color:#000; border:1px solid #000;" onclick="javascript:sinkronTiktok()">Sinkronisasi 15 Hari Terakhir</button>
      				<div id="filter_tgl_tiktok_1" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_tiktok_1" style="width:100px;" name="tgl_awal_filter_tiktok_1" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_tiktok_1" style="width:100px;" name="tgl_akhir_filter_tiktok_1" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshTiktok(1)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_tiktok_2" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_tiktok_2" style="width:100px;" name="tgl_awal_filter_tiktok_2" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_tiktok_2" style="width:100px;" name="tgl_akhir_filter_tiktok_2" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshTiktok(2)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_tiktok_3" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_tiktok_3" style="width:100px;" name="tgl_awal_filter_tiktok_3" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_tiktok_3" style="width:100px;" name="tgl_akhir_filter_tiktok_3" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshTiktok(3)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_tiktok_4" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_tiktok_4" style="width:100px;" name="tgl_awal_filter_tiktok_4" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_tiktok_4" style="width:100px;" name="tgl_akhir_filter_tiktok_4" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshTiktok(4)">Tampilkan</button>
      				</div>
      			</div>
      		    <div class="nav-tabs-custom" >
                  <ul class="nav nav-tabs" id="tab_transaksi_tiktok">
      				<li class="active" onclick="javascript:changeTabTiktok(1)" ><a href="#tab_1_tiktok" data-toggle="tab">Persiapan Pesanan &nbsp;<span id="totalTiktok1" style=" display:none; color:white; background:red; border-radius:100px; padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabTiktok(2)"><a href="#tab_2_tiktok" data-toggle="tab">Proses Pengiriman &nbsp;<span id="totalTiktok2" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabTiktok(3)"><a href="#tab_3_tiktok" data-toggle="tab">Selesai Pesanan &nbsp;<span id="totalTiktok3" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabTiktok(4)"><a href="#tab_4_tiktok" data-toggle="tab">Pengembalian Pesanan &nbsp;<span id="totalTiktok4" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li class="pull-right" style="width:250px">
      					<div class="input-group " id="filter_status_tiktok_1">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_tiktok_1" name="cb_trans_status_tiktok_1" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="UNPAID">Belum Bayar</option>
      					  	<option value="ON_HOLD">Menunggu</option>
      					  	<option value="AWAITING_SHIPMENT">Siap Dikirim</option>
      					  	<option value="AWAITING_COLLECTION">Diproses</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_tiktok_2">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_tiktok_2" name="cb_trans_status_tiktok_2" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="IN_TRANSIT">Dalam Pengiriman</option>
      					  	<option value="DELIVERED">Telah Dikirim</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_tiktok_3">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_tiktok_3" name="cb_trans_status_tiktok_3" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="COMPLETED">Selesai</option>
      					  	<option value="CANCELLED">Pembatalan</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_tiktok_4">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_tiktok_4" name="cb_trans_status_tiktok_4" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="RETURNED|RETURN_OR_REFUND_REQUEST_PENDING|AWAITING_BUYER_SHIP">Pengembalian Diajukan</option>
      					  	<option value="RETURNED|AWAITING_BUYER_RESPONSE|BUYER_SHIPPED_ITEM|REFUND_PENDING|RETURN_OR_REFUND_REQUEST_SUCCESS">Pengembalian Diproses</option>
      					  	<!--<option value="RETURNED|DISPUTE">Pengembalian dalam Sengketa</option>-->
      					  </select>
      					</div>
      				</li>
                  </ul>
                  <div class="tab-content">
                      <div class="tab-pane active" id="tab_1_tiktok">
                          <div class="box-body ">
                      		  <button class="btn btn-warning" id="cetakLangsungSemua" style="margin-bottom:10px; display:none;" onclick="javascript:cetakTiktokSemua(1)" >Cetak Semua Pesanan</button>
                      		  <button class="btn btn-success" id="kirimLangsungSemua" style="margin-bottom:10px; display:none;" onclick="javascript:kirimTiktokSemua()" >Atur Semua Pengiriman</button>
                              <table id="dataGridTiktok1" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                  <!-- class="table-hover"> -->
                                  <thead>
                                  <tr>
                                      <th width="35px"></th>
                                      <th width="150px">Status</th>
                                      <th width="150px">No. Pesanan</th>
                                      <th>Tgl Pesanan</th>
                                      <th width="300px">Barang</th>
                                      <th>T. Qty</th>
                                      <th>T. Bayar</th>
                                      <th width="150px">Metode Bayar</th>
                                      <th width="300px">Alamat Pembeli</th>
                                      <th>Kurir</th>
                                      <th width="150px">No Resi</th>
                                      <th width="200px">Catatan Pembeli</th>
                                      <th width="200px">Catatan Penjual</th>
                                  </tr>
                                  </thead>
                              </table>
                          </div>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab_2_tiktok">
                          <div class="box-body ">
                              <table id="dataGridTiktok2" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                  <!-- class="table-hover"> -->
                                  <thead>
                                  <tr>
                                      <th width="35px"></th>
                                      <th width="150px">Status</th>
                                      <th width="150px">No. Pesanan</th>
                                      <th>Tgl Pesanan</th>
                                      <th width="300px">Barang</th>
                                      <th>T. Qty</th>
                                      <th>T. Bayar</th>
                                      <th width="150px">Metode Bayar</th>
                                      <th width="300px">Alamat Pembeli</th>
                                      <th>Kurir</th>
                                      <th width="150px">No Resi</th>
                                      <th width="200px">Catatan Pembeli</th>
                                      <th width="200px">Catatan Penjual</th>
                                  </tr>
                                  </thead>
                              </table>
                          </div>
                      </div>
                       <div class="tab-pane" id="tab_3_tiktok">
                          <div class="box-body ">
                              <table id="dataGridTiktok3" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                  <!-- class="table-hover"> -->
                                  <thead>
                                  <tr>
                                      <th width="35px"></th>
                                      <th width="150px">Status</th>
                                      <th width="150px">No. Pesanan</th>
                                      <th>Tgl Pesanan</th>
                                      <th width="300px">Barang</th>
                                      <th>T. Qty</th>
                                      <th>T. Bayar</th>
                                      <th width="150px">Metode Bayar</th>
                                      <th width="300px">Alamat Pembeli</th>
                                      <th>Kurir</th>
                                      <th width="150px">No Resi</th>
                                      <th width="200px">Catatan Pembeli</th>
                                      <th width="200px">Catatan Penjual</th>
                                  </tr>
                                  </thead>
                              </table>
                          </div>
                      </div>
                       <div class="tab-pane" id="tab_4_tiktok">
                          <div class="box-body ">
                              <table id="dataGridTiktok4" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                  <!-- class="table-hover"> -->
                                  <thead>
                                  <tr>
                                      <th width="35px"></th>
                                      <th width="150px">Status</th>
                                      <th width="150px">No. Pengembalian</th>
                                      <th>Tgl Pengembalian</th>
                                      <th width="150px">No. Pesanan</th>
                                      <th width="300px">Barang</th>
                                      <th>T. Qty</th>
                                      <th>Dana Kembali</th>
                                      <th width="300px">Alamat Pembeli</th>
                                      <th width="150px">No Resi Pengembalian</th>
                                      <th width="200px">Alasan Pengembalian Pembeli</th>
                                      <th width="200px">Catatan Penjual</th>
                                  </tr>
                                  </thead>
                              </table>
                          </div>
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
<input type="hidden" id="STATUSTIKTOK1">
<input type="hidden" id="STATUSTIKTOK2">
<input type="hidden" id="STATUSTIKTOK3">
<input type="hidden" id="STATUSTIKTOK4">
<!--MODAL BATAL-->

<div class="modal fade" id="modal-form-tiktok">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Detail Pesanan&nbsp;&nbsp;<b id="NOTIKTOK" style="font-size:14pt;"></b>&nbsp;&nbsp;&nbsp;-&nbsp;<i id="STATUSTIKTOK"  style="font-size:12pt;"></i></h4>
            <!--<button onclick="ubahTiktok()" id="ubahTiktokDetail" style="margin-left:15px;" class='btn btn-primary'>Ubah</button> -->
            <button onclick="hapusTiktok()" id="hapusTiktokDetail" style="margin-left:5px;" class='btn btn-danger'>Batal</button>
            <button onclick="cetakTiktok()" id="cetakTiktokDetail" style="margin-left:5px;" class='btn btn-warning'>Cetak</button>
            <button onclick="kirimTiktok()" id='kirimTiktokDetail' class='btn btn-success' style='float:right;'>Atur Pengiriman</button>
            <button onclick="lacakTiktok()" id='lacakTiktokDetail' class='btn btn-success' style='float:right;'>Lacak Pesanan</button>
            <button onclick="returBarangTiktok()" id='returBarangTiktokDetail' class='btn btn-danger' style='float:right;'>Retur B. Manual</button>
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-4 col-sm-4 col-xs-4" style="padding:0px;">
                   <label>Tgl Pesanan</label>
                   <div id="TGLPESANANTIKTOK">-</div>
                   <br>
                   <label>Min Tgl Kirim</label>
                   <div id="TGLKIRIMTIKTOK">-</div>
                   <br>
                   <label>Metode Bayar</label>
                   <div id="PEMBAYARANTIKTOK">-</div>
                   <br>
                   <label>Kurir / No. Resi</label>
                   <div id="KURIRTIKTOK">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                   <label>Pembeli </label>
                   <div id="NAMAPEMBELITIKTOK">-</div>
                   <br>
                   <label>Telp </label>
                   <div id="TELPPEMBELITIKTOK">-</div>
                   <br>
                   <label>Alamat </label>
                   <div id="ALAMATPEMBELITIKTOK">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4"style="padding:0px;">
                   <label>Catatan Pembeli</label>
                   <div id="CATATANPEMBELITIKTOK">-</div>
                   <br>
                   <label class="noKembaliTiktok">No. Pengembalian</label>
                   <div class="noKembaliTiktok" id="NOPENGEMBALIANTIKTOK">-</div>
                   <br>
                   <label class="alasanKembaliTiktok">Alasan Batal / Kembali</label>
                   <div class="alasanKembaliTiktok" id="ALASANPENGEMBALIANTIKTOK">-</div>
                </div>
      	    	<!--SATU TABEL-->
      	    	<div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:15px; padding:0px;" >
          	    	<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          	    		<div class="row"> 
              				<div class=" col-sm-12">
              					<table id="dataGridDetailTiktok" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<thead>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Jml</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Sat</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px" >H. Tampil</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px">H. Coret</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px">Subtotal</th>
              							</tr>
              						</thead>
              						<tbody class="table-responsive-tiktok">
              						</tbody>
              					</table> 
              				</div>
          	    		</div>
          	    	</div> 
          	    	<div class="row" style="margin:0px;padding:0px;"> 
              				<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
              					<table id="footerTiktok" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<tfoot>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
              								<th style="vertical-align:middle; text-align:center;" id="TOTALQTYTIKTOK" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:right;" id="SUBTOTALTIKTOK" width="100px"></th>
              							</tr>
              						</tfoot>
              					</table> 
              				</div>
              			</div>
      	    	</div>
      	    	
      	    <div style="padding:0px; border-radius:2px; z-index;-1;">
      	    	<div class="col-md-6 col-sm-6 col-xs-6  " style="padding:0px 15px 0px 0px;  border-right:1px solid #cecece;">
      	    	    <div style="font-weight:bold; margin:auto; " ><i style="font-size:14pt;">Informasi Pembeli</i></div>
      	    	    <div class="row">
          	    		<div class="col-md-12">
              	    	    <div class="col-md-9" align="right" style="font-weight:bold">Total</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="TOTALPEMBELITIKTOK">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Diskon Pesanan</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="DISKONPEMBELITIKTOK">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Biaya Pengiriman</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYAKIRIMPEMBELITIKTOK">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Biaya Lain</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYALAINPEMBELITIKTOK">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold; padding-top:15px;">Pembayaran Pembeli</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-top:15px; padding-bottom:15px; padding-right:10px; border-top:1px solid #cecece; font-weight:bold" id="PEMBAYARANPEMBELITIKTOK">	
                  	    	</div>
              	    	</div>
              	    </div>
      	    	</div>
      	    	<div class="col-md-6 col-sm-6 col-xs-6  "style="padding:0px 0px 0px 15px;">
          	    	    <div style="font-weight:bold; margin:auto;" ><i style="font-size:14pt;">Informasi Penjual</i></div>
          	    	    <div class="row" id="DETAILINFORMASIPENJUALTIKTOK">
          	    			<div class="col-md-12">
                  	    	    <div class="col-md-9" align="right" style="font-weight:bold">Total</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="TOTALPENJUALTIKTOK">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Refund </div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="REFUNDPENJUALTIKTOK">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Diskon Penjual</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="DISKONPENJUALTIKTOK">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Biaya Pengiriman Final</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYAKIRIMPENJUALTIKTOK">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Biaya Layanan</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px; " id="BIAYALAYANANPENJUALTIKTOK">
              	    			</div>
              	    			<hr></hr>
              	    			<div class="col-md-9" align="right" style="font-weight:bold; padding-top:15px;">Total Penjualan</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-top:15px; padding-bottom:15px; padding-right:10px; border-top:1px solid #cecece;  font-weight:bold" id="GRANDTOTALPENJUALTIKTOK">
              	    		    </div>
              	    			<div class="col-md-9 penyelesaianTiktok"  align="right" style="font-weight:bold;">Penyelesaian Pembayaran</div>
              	    			<div class="col-md-3 penyelesaianTiktok"  style="text-align:right; padding-bottom:15px; padding-right:10px;font-weight:bold " id="PENYELESAIANPENJUALTIKTOK">		
              	    			</div>
          	    		    </div>
          	    	</div>
      	    	</div>
      	     </div>
      	    <!-- HEADER -->
      	    </div>
      	  </div>
	    </div>
	</div>
</div>

<div class="modal fade" id="modal-ubah-tiktok">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	     <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Ubah Pesanan&nbsp;&nbsp;<b id="NOTIKTOKUBAH" style="font-size:14pt;"></b></h4>
            <button id='btn_ubah_konfirm_tiktok'  style="float:right;" class='btn btn-primary' onclick="ubahKonfirmTiktok()">Ubah</button>
        </div>
		<div class="modal-body" style="height:395px;">
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailTiktokUbah" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<thead>
            						<tr>
            						    <th width="103px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
            							<th style="vertical-align:middle; text-align:center;" width="50px">Jml</th>
            							<th style="vertical-align:middle; text-align:center;" width="50px">Sat</th>
            							<th style="vertical-align:middle; text-align:center;" width="100px" >H. Tampil</th>
            							<th style="vertical-align:middle; text-align:center;" width="100px">H. Coret</th>
            							<th style="vertical-align:middle; text-align:center;" width="100px">Subtotal</th>
            						</tr>
            					</thead>
            					<tbody class="table-responsive-tiktok-ubah">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
          		<div class="row" style="margin:0px;padding:0px;"> 
            			<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
            				<table id="footerTiktokUbah" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<tfoot>
            						<tr>
            						    <th width="103px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
            							<th style="vertical-align:middle; text-align:center;" id="TOTALQTYTIKTOKUBAH" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:right;" id="SUBTOTALTIKTOKUBAH" width="100px"></th>
            						</tr>
            					</tfoot>
            				</table> 
            			</div>
            		</div>
      	    </div>
            <input type="hidden" id="itemUbahTiktok">
			<br>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="modal-alasan-tiktok">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	     <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Pembatalan Pesanan&nbsp;&nbsp;<b id="NOTIKTOKBATAL" style="font-size:14pt;"></b></h4>
            <button id='btn_hapus_konfirm_tiktok'  style="float:right;" class='btn btn-danger' onclick="hapusKonfirmTiktok()">Batal</button>
        </div>
		<div class="modal-body" style="height:480px;">
		    <div>
      	    <label>Alasan Pembatalan</label>
			<select id="cb_alasan_pembatalan_tiktok" name="cb_alasan_pembatalan_tiktok" class="form-control "  panelHeight="auto" required="true">
      		</select>
      		</div>
      		<br>
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailTiktokBatal" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<thead>
            						<tr>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
            							<th style="vertical-align:middle; text-align:center;" width="50px">Jml</th>
            							<th style="vertical-align:middle; text-align:center;" width="50px">Sat</th>
            							<th style="vertical-align:middle; text-align:center;" width="100px" >H. Tampil</th>
            							<th style="vertical-align:middle; text-align:center;" width="100px">H. Coret</th>
            							<th style="vertical-align:middle; text-align:center;" width="100px">Subtotal</th>
            						</tr>
            					</thead>
            					<tbody class="table-responsive-tiktok-batal">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
          		<div class="row" style="margin:0px;padding:0px;"> 
            			<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
            				<table id="footerTiktokBatal" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<tfoot>
            						<tr>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
            							<th style="vertical-align:middle; text-align:center;" id="TOTALQTYTIKTOKBATAL" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:right;" id="SUBTOTALTIKTOKBATAL" width="100px"></th>
            						</tr>
            					</tfoot>
            				</table> 
            			</div>
            		</div>
      	    </div>
      		<input type="hidden" id="itemBatalTiktok">
			<br>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" id="modal-kirim-tiktok">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Atur Pengiriman&nbsp;&nbsp;<span id="countAturPengiriman" style="font-size:14pt;"></span></h4>
                <button onclick="kirimKonfirmTiktok()" id='kirim_konfirm_tiktok' class='btn btn-success' style='float:right;'>Kirim</button>
        </div>
		<div class="modal-body" style="height:395px;">
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:363px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailTiktokKirim" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<thead>
            						<tr>
            							<th style="vertical-align:middle; text-align:center;" width="150px">Kurir</th>
            							<th style="vertical-align:middle; text-align:center;" width="150px" >No. Pesanan</th>
            							<th style="vertical-align:middle; text-align:center;" width="80px">T. Qty</th>
            							<th style="vertical-align:middle; text-align:center;" width="332px" >Tanggal & Waktu Jemput</th>
            							<th style="vertical-align:middle; text-align:center;" >Catatan Penjual</th>
            						</tr>
            					</thead>
            					<tbody class="table-responsive-tiktok-kirim">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
      	    </div>
		</div>
		<input type="hidden" id="rowDataPengirimanTiktok">
		<input type="hidden" id="pickupKirimTiktok">
	</div>
	</div>
</div>

<div class="modal fade" id="modal-kirim-all-tiktok">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Atur Semua Pengiriman&nbsp;&nbsp;<span id="countAturSemuaPengirimanTiktok" style="font-size:14pt;"></span></h4>
                <button onclick="kirimKonfirmAllTiktok()" id='kirim_konfirm_all_tiktok' class='btn btn-success' style='float:right;'>Kirim</button>
        </div>
		<div class="modal-body" style="height:655px;">
		        <div style="margin-left:25px; margin-bottom:25px;">
		            <label><input type="checkbox" id="pilihKirimanAllKurirTiktok" checked> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pilih Semua Kurir</label>
		            <span style="float:right; margin-top:3px; margin-right:30px;" id="keteranganKurirTiktok">Terdapat &nbsp;<span id="countAllPesananTiktok" style="font-weight:bold; font-size:14pt; "></span>&nbsp; Pesanan dari &nbsp;<span id="countAllKurirTiktok" style="font-weight:bold; font-size:14pt; "></span>&nbsp; Kurir</span>
		        </div>
		        <ul class="nav nav-tabs" id="tab_kirim_tiktok">
      				<li class="active" ><a href="#tab_regular_tiktok" data-toggle="tab">Regular <span id="countRegular"></span></a></li>
      			    <li ><a href="#tab_instant_tiktok" data-toggle="tab">Instant & Sameday <span id="countInstant"></span></a></li>
                  </ul>
                  <div class="tab-content">
                      <div class="tab-pane active" id="tab_regular_tiktok">
                        <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:20px; padding:0px;" >
                              <div class="box-body ">
                              		<div class="x_content" style="height:508px; overflow-y:auto; overflow-x:hidden;">
                              			<div class="row"> 
                                			<div class=" col-sm-12" id="dataGridDetailAllRegularTiktok">
                                			</div>
                              			</div>
                              		</div> 
                              </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab_instant_tiktok">
                            <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:20px; padding:0px;" >
                              <div class="box-body ">
                              		<div class="x_content" style="height:508px; overflow-y:auto; overflow-x:hidden;">
                              			<div class="row"> 
                                			<div class=" col-sm-12" id="dataGridDetailAllInstantTiktok">
                                			</div>
                              			</div>
                              		</div> 
                              </div>
                            </div>
                      </div>
                </div>
      	    </div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-lacak-tiktok">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Lacak Pesanan&nbsp;&nbsp;<b id="NOTIKTOKLACAK" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body" style="height:425px;">
      	    <div class="row"> 
            	<div class="col-sm-5" style="padding:0px 20px 0px 20px; border-right:1px solid #cecece;">
            	   <label>Metode Bayar</label>
                   <div id="METODEBAYARLACAKTIKTOK">-</div>
                   <br>
            	   <label>Kurir</label>
                   <div id="KURIRLACAKTIKTOK">-</div>
                   <br>
                   <label>Resi</label>
                   <div id="RESILACAKTIKTOK">-</div>
                   <br>
                   <label>Tgl Kirim</label>
                   <div id="TGLKIRIMLACAKTIKTOK">-</div>
                   <br>
                   <label>Alamat</label>
                   <div id="ALAMATLACAKTIKTOK">-</div>
            	</div>
            	<div class="col-sm-7"  >
            	    <div class="step-tracker" style="height:375px; overflow-y:scroll;">
            	    </div>
            	</div>
          	</div>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" id="modal-barang-tiktok">
	<div class="modal-dialog">
	<div class="modal-content">
    	 <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Ganti Produk Asal&nbsp;&nbsp;<b id="warnaOldTiktok" style="font-size:14pt;"></b><b> / </b><b id="sizeOldTiktok" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body">
			<table id="table_barang_tiktok" class="table table-bordered table-striped table-hover display nowrap" width="100%">
				<thead>
					<tr>
						<th hidden>ID</th>	
						<th>Nama</th>
						<th hidden>SKU</th>	
					</tr>
				</thead>
			</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-note-tiktok">
	<div class="modal-dialog ">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Catatan Pesanan&nbsp;&nbsp;<b id="NOTIKTOKCATATAN" style="font-size:14pt;"></b></h4>
                    <button id='btn_note_konfirm_tiktok'  style="float:right;" class='btn btn-success' onclick="noteKonfirmTiktok()">Simpan</button>
            </div>
    		<div class="modal-body">
    		    <textarea id="note_tiktok" maxlines="5" style="width:100%; height:200px; border:0.5px solid #cecece; padding:10px;" placeholder="Masukkan Catatan.....">
    		    </textarea>
    		    <input type="hidden" id="fromNoteTiktok">
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-cetak-tiktok">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Cetak Pesanan&nbsp;&nbsp;<span id="countCetak" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_tiktok'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmTiktok()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewCetakTiktok">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-cetak-all-tiktok">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Cetak Semua Pesanan&nbsp;&nbsp;<span id="countCetakSemua" style="font-size:14pt;"></span></h4>
                    <button id='btn_cetak_all_konfirm_tiktok'  style="float:right;" class='btn btn-warning' onclick="cetakAllKonfirmTiktok()">Cetak</button>
            </div>
    		<div class="modal-body" style="height:600px;">
          	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
              		<div class="x_content" style="height:568px; overflow-y:auto; overflow-x:hidden;">
              			<div class="row"> 
                			<div class=" col-sm-12">
                				<table id="dataGridDetailTiktokAllCetak" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
                					<thead>
                						<tr>
                						    <th style="vertical-align:middle; text-align:center;" width="30px"><input type="checkbox" id="pilihCetakAllTiktok" checked width="30px"></th>
                							<th style="vertical-align:middle; text-align:center;" width="150px" >No. Pesanan</th>
                							<th style="vertical-align:middle; text-align:center;" width="150px">Metode Bayar</th>
                							<th style="vertical-align:middle; text-align:center;" width="100px">Kurir</th>
                							<th style="vertical-align:middle; text-align:center;" width="150px">Resi</th>
                						</tr>
                					</thead>
                					<tbody class="table-responsive-tiktok-all-cetak">
                					</tbody>
                				</table> 
                			</div>
              			</div>
              		</div> 
          	    </div>
		    </div>
		    <input type="hidden" id="dataCetakSemuaTiktok">
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-pengembalian-tiktok">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Detail Pengembalian&nbsp;&nbsp;<b id="NOTIKTOKPENGEMBALIAN" style="font-size:14pt;"></b>&nbsp;&nbsp;&nbsp;-&nbsp;<i id="STATUSTIKTOKPENGEMBALIAN"  style="font-size:12pt;"></i></h4>
            <button onclick="returTiktok()" id='returTiktokDetail' class='btn btn-success' style='float:right;'>Jawab</button>
            <button id='returTiktokWait' class='btn' style='float:right; background:#888888; color:white;'></button>
            
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-4 col-sm-4 col-xs-4" style="padding:0px;">
                   <label>Tgl Pengembalian</label>
                   <div id="TGLTIKTOKPENGEMBALIAN">-</div>
                   <br>
                   <label>Tenggat Waktu</label>
                   <div id="MINTGLTIKTOKPENGEMBALIAN">-</div>
                   <br>
                   <label>No. Resi Pengembalian</label>
                   <div id="RESITIKTOKPENGEMBALIAN">-</div>
                   <br>
                   <label>No. Pesanan</label>
                   <div id="NOTIKTOKPESANANPENGEMBALIAN">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                   <label>Pembeli </label>
                   <div id="NAMAPEMBELITIKTOKPENGEMBALIAN">-</div>
                   <br>
                   <label>Telp </label>
                   <div id="TELPPEMBELITIKTOKPENGEMBALIAN">-</div>
                   <br>
                   <label>Alamat </label>
                   <div id="ALAMATPEMBELITIKTOKPENGEMBALIAN">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4"style="padding:0px;"> 
                   <label>Alasan Pilihan Pembeli</label>
                   <div id="ALASANTIKTOKPILIHAN">-</div>
                   <br>
                   <label>Alasan Pengembalian Pembeli</label>
                   <div id="ALASANTIKTOKPENGEMBALIAN" style="max-height:70px; overflow-x:hidden;">-</div>
                   <br>
                   <label>Bukti Pengembalian Pembeli</label>
          	    	<div id="GAMBARPENGEMBALIANTIKTOK" style="max-height:70px; overflow-x:hidden; width:50%; float:left;"></div>
          	    	<div id="VIDEOPENGEMBALIANTIKTOK" style="max-height:70px; overflow-x:hidden; width:50%;"></div>
                </div>
      	    	<!--SATU TABEL-->
      	    	<div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:6px; padding:0px;" >
          	    	<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          	    		<div class="row"> 
              				<div class=" col-sm-12">
              					<table id="dataGridDetailPengembalianTiktok" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<thead>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Jml</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Sat</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px" >Harga</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px">Dana Kembali</th>
              							</tr>
              						</thead>
              						<tbody class="table-responsive-tiktok-pengembalian">
              						</tbody>
              					</table> 
              				</div>
          	    		</div>
          	    	</div> 
          	    	<div class="row" style="margin:0px;padding:0px;"> 
              				<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
              					<table id="footerTiktok" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<tfoot>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
              								<th style="vertical-align:middle; text-align:center;" id="TOTALQTYPENGEMBALIANTiktok" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:right;" id="SUBTOTALPENGEMBALIANTiktok" width="100px"></th>
              							</tr>
              						</tfoot>
              					</table> 
              				</div>
              			</div>
      	    	</div>
      	     </div>
      	    <!-- HEADER -->
      	    </div>
      	  </div>
	    </div>
</div>

<div class="modal fade" id="modal-lebih-jelas-tiktok" style="z-index:999999999999999999999999999;">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; <span id="titleLebihJelasTiktok" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_tiktok'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmTiktok()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewLebihJelasTiktok">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-retur-tiktok">
	<div class="modal-dialog" style="width:700px;">
	    <div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Proses Pengembalian&nbsp;&nbsp;<b id="NOTIKTOKRETUR" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body">
		    <div id="HEADERRETURTIKTOK"></div>
		    Jadi penjual memilih :
		    <br><br>
          	<ul class="nav nav-tabs" id="tab_retur_tiktok">
          		<li id="tab_retur_header_tiktok_0"><a href="#tab_retur_detail_tiktok_0" data-toggle="tab">Kembalikan Dana ke Pembeli</a></li>
          	    <li id="tab_retur_header_tiktok_1" onclick="focusOnRefundTiktok()"><a href="#tab_retur_detail_tiktok_1" data-toggle="tab">Pengembalian Barang dan Dana</a></li>
          	    <li id="tab_retur_header_tiktok_2"><a href="#tab_retur_detail_tiktok_2" data-toggle="tab">Ajukan Banding</a></li>
            </ul>
            <div class="tab-content" style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; padding: 10px 10px 10px 10px;">
                <div class="tab-pane " id="tab_retur_detail_tiktok_0" style="padding:5px 0px 5px 0px;">
                    <div id="DETAILRETURTIKTOK_0">Dengan ini menyatakan bahwa : <br>Penjual telah setuju untuk melakukan <b>Pengembalian Dana Penuh</b>, dan pembeli &nbsp;<i>tidak perlu mengembalikan produk</i>.&nbsp; Setelah klik tombol "Setujui dan Kembalikan Dana". untuk melanjutkan proses pengembalian.</div>
                    <br><br>
                    <label style="width:100%; text-align:center; font-size:18pt;">Total Dana Kembali</label>
                    <div style="width:100%; text-align:center;"><input type="text" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANTIKTOK_0" onkeyup="return numberInputTrans(event,0)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                    <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANTIKTOK_0">
                    <br><br><br>
                     <button onclick="refundTiktok(0)" id='returRefundTiktok' class='btn btn-success' style='width:100%; font-weight:bold;'>Setuju&nbsp;&nbsp;dan&nbsp;&nbsp;Kembalikan&nbsp;&nbsp;Dana</button>
                </div>
                <div class="tab-pane" id="tab_retur_detail_tiktok_1" style="padding:5px 0px 5px 0px;">
                    <div id="DETAILRETURTIKTOK_1"></div>
                    <br><br>
                    <label style="width:100%; text-align:center; font-size:18pt;">Total Dana Pengembalian yang Diajukan</label>
                    <div style="width:100%; text-align:center;"><input type="text" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANTIKTOK_1" onkeyup="return numberInputTrans(event,1)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                    <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANTIKTOK_1">
                     <!--<div style="width:100%; margin-top:10px; text-align:center;"><button id='btn_max_kembali_tiktok' onclick="setMaksRefundTiktok()" style='border:1px solid #CECECE; margin:auto;' class='btn' >Maks Pengembalian</button></div><br><br>-->
                     <br><br><br>
                     <button onclick="refundTiktok(1)" id='returNegotiationTiktok' class='btn btn-warning' style='width:100%; font-weight:bold;'>Pengembalian&nbsp;&nbsp;Barang&nbsp;&nbsp;dan&nbsp;&nbsp;Dana</button>
                     <button id='returTiktokWaitResponse' class='btn' style='width:100%; background:#888888; color:white; font-weight:bold;'>Menunggu&nbsp;&nbsp;Respon&nbsp;&nbsp;Pembeli</button>
                </div>
                <div class="tab-pane" id="tab_retur_detail_tiktok_2" style="padding:5px 0px 5px 0px;">
                    <div id="DISPUTESEBELUMBARANGDATANG">
                        <div id="DETAILRETURTIKTOK_2">Dengan ini menyatakan bahwa : <br>Penjual mengajukan banding terhadap barang yang telah dikirimkan oleh Pembeli (Terkait kerusakan, barang yang dikembalikan berbeda, dll).</div>
            		    <div id="ALASANBANDING">
            		        <br>
                      	    <label>Alasan Banding</label>
                			<select id="cb_alasan_sengketa_tiktok" name="cb_alasan_sengketa_tiktok" class="form-control "  panelHeight="auto" required="true">
                      		
                      		</select>
                      	</div>
            		    <br>
                		<div>
                      	    <label>Penjelasan Banding</label>
                		    <textarea id="deskripsi_sengketa_tiktok" maxlines="2" style="width:100%; height:80px; border:0.5px solid #cecece; padding:10px;" placeholder="Masukkan Penjelasan....."></textarea>
                		</div>
                		<br>
                      	<div id="uploadBuktiTiktok">
                      	    <label>Upload Bukti</label>
                      	    <div id="penjelasan_bukti_tiktok"></div>
                			<div id="proof_sengketa_tiktok" style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:15px; padding:10px;">
                			    
                			</div>
                		 </div>
                         <div style="width:100%; text-align:center;"><input type="hidden" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANTIKTOK_2" onkeyup="return numberInputTrans(event,3)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                         <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANTIKTOK_2">
                         <input type="hidden" id="dataDisputeTiktok">
                         <input type="hidden" id="pilihanDisputeTiktok">
                         <input type="hidden" id="pilihDisputeTiktok">
                         <br>
                         <button onclick="refundTiktok(2)" id='returDisputeTiktok' class='btn btn-danger' style='width:100%; font-weight:bold;'>Ajukan&nbsp;&nbsp;Banding</button>
                    </div>
                    <div id="DISPUTESESUDAHBARANGDATANG">Dengan ini menyatakan bahwa : <br>Penjual mengajukan banding terhadap barang yang telah dikirimkan oleh Pembeli (Terkait kerusakan, barang yang dikembalikan berbeda, dll).<br><br>Untuk transaksi hanya dapat dilakukan pada aplikasi tiktok. Status transaksi dan stok akan terupdate, melalui sinkronisasi otomatis maupun sinkronisasi manual.</div>
                </div>
            </div>  
        </div>
      </div>
	</div>
    <input type="hidden" id="dataReturTiktok">
</div>

<input type="hidden" id="kategori_item_tiktok" value="">
<input type="hidden" id="rowDataTiktok">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />

<script>

var firsTimeTiktok = ["",true,true,true,true];
var sinkronTiktokState = false;
var doneSinkronTiktok =  ["",true,true,true,true];
var totalPesananTiktokAll = 0;

setTimeout(() => {
    changeTabTiktok(1);
    changeTabTiktok(2);
    changeTabTiktok(3);
    changeTabTiktok(4);
	
	$("#filter_status_tiktok_"+1+", #filter_tgl_tiktok_"+1).show();
    
    for(var x = 1; x <= 4 ; x++)
    {
       if(1 != x)
       {
            $("#filter_status_tiktok_"+x+", #filter_tgl_tiktok_"+x).hide();
       }
    }
}, "100");

$(document).ready(function(){
	
    //TAMBAH
	$('#tgl_awal_filter_tiktok_1, #tgl_akhir_filter_tiktok_1, #tgl_awal_filter_tiktok_2, #tgl_akhir_filter_tiktok_2, #tgl_awal_filter_tiktok_3, #tgl_akhir_filter_tiktok_3, #tgl_awal_filter_tiktok_4, #tgl_akhir_filter_tiktok_4').datepicker({
		format: 'yyyy-mm-dd',
		 autoclose: true, // Close the datepicker automatically after selection
        container: 'body', // Attach the datepicker to the body element
        orientation: 'bottom auto' // Show the calendar below the input
	});
	$("#tgl_awal_filter_tiktok_1, #tgl_awal_filter_tiktok_2, #tgl_awal_filter_tiktok_3, #tgl_awal_filter_tiktok_4").datepicker('setDate', "<?=TGLAWALFILTERMARKETPLACE?>");
	$("#tgl_akhir_filter_tiktok_1, #tgl_akhir_filter_tiktok_2, #tgl_akhir_filter_tiktok_3, #tgl_akhir_filter_tiktok_4").datepicker('setDate', new Date());
	
	$("#STATUSTIKTOK1").val('UNPAID,ON_HOLD,AWAITING_SHIPMENT,AWAITING_COLLECTION');
	$("#STATUSTIKTOK2").val('IN_TRANSIT,DELIVERED');
	$("#STATUSTIKTOK3").val('COMPLETED,CANCELLED');
	$("#STATUSTIKTOK4").val('RETURNED|RETURN_OR_REFUND_REQUEST_PENDING|AWAITING_BUYER_SHIP,RETURNED|AWAITING_BUYER_RESPONSE|BUYER_SHIPPED_ITEM|REFUND_PENDING|RETURN_OR_REFUND_REQUEST_SUCCESS');
	
	$('body').keyup(function(e){
		hotkey(e);
	});
	
	$("#modal-barang").on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input', $("#table_barang").DataTable().table().container()).focus();
    });
    
    //TABLE BARANG
	$("#table_barang_tiktok").DataTable({
        'retrieve'    : true,
        'paging'      : false,
        'lengthChange': false,
        'searching'   : true,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"dom"		  : '<"pull-left"f><"pull-right"l>tip',
		ajax		  : {
			url    : base_url+'Master/Data/Barang/comboGridMarketplace',   // Master/Data/Barang/loadData
			dataSrc: "rows",
			type   : "POST",
			data    : function(e){
					e.kategori 	    = getKategoriTiktok();
					e.marketplace 	= "Tiktok";
			}
		},
		language: {
			search           : "Cari",
			searchPlaceholder: "Nama Produk"
		},
        columns:[
			{ data: 'ID', visible: false},
            { data: 'NAMA' },
            { data: 'SKU', visible: false }
        ],
		
    });
	
	//BUAT NAMBAH BARANG BIASA
	$('#table_barang_tiktok tbody').on('click', 'tr', function () {
		var row = $('#table_barang_tiktok').DataTable().row( this ).data();
		$("#modal-barang-tiktok").modal('hide');
		
		 $(".table-responsive-tiktok-ubah").html('');
         var itemDetail = JSON.parse($("#itemUbahTiktok").val()); 
         for(var x = 0 ; x < itemDetail.length ; x++)
         {
             if(itemDetail[x]['WARNAOLD'] == $("#warnaOldTiktok").html() && itemDetail[x]['SIZEOLD'] && $("#sizeOldTiktok").html())
             {
                 itemDetail[x]['WARNA'] = row.WARNA;
                 itemDetail[x]['SIZE']  = row.SIZE;
                 itemDetail[x]['NAMA']  = row.NAMALABEL;
                 itemDetail[x]['SKU']   = row.SKU;
             }
             
              var namaBarang = itemDetail[x].NAMA;
              if(itemDetail[x].SIZE != itemDetail[x].SIZEOLD || itemDetail[x].WARNA != itemDetail[x].WARNAOLD)
              {
                  namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+itemDetail[x].WARNAOLD+" / "+itemDetail[x].SIZEOLD+"</span>");
              }
             
             $(".table-responsive-tiktok-ubah").append(setDetail(itemDetail,x,namaBarang,true));
         }
         
         
        $("#itemUbahTiktok").val(JSON.stringify(itemDetail));
            
		var table = $('#table_barang_tiktok').DataTable();
		table.search("").draw();
	});
});

function getKategoriTiktok(){
	return $("#kategori_item_tiktok").val();
}

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_tiktok_1").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#STATUSTIKTOK1").val('UNPAID,ON_HOLD,AWAITING_SHIPMENT,AWAITING_COLLECTION');
	}	
	else
	{
		$("#STATUSTIKTOK1").val($(this).val());
	}
	$("#dataGridTiktok1").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_tiktok_2").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{	
		$("#STATUSTIKTOK2").val('IN_TRANSIT,DELIVERED');
	}	
	else
	{
		$("#STATUSTIKTOK2").val($(this).val());
	}
	$("#dataGridTiktok2").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_tiktok_3").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#STATUSTIKTOK3").val('COMPLETED,CANCELLED');
	}	
	else
	{
		$("#STATUSTIKTOK3").val($(this).val());
	}
	$("#dataGridTiktok3").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_tiktok_4").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#STATUSTIKTOK4").val('RETURNED|RETURN_OR_REFUND_REQUEST_PENDING|AWAITING_BUYER_SHIP,RETURNED|AWAITING_BUYER_RESPONSE|BUYER_SHIPPED_ITEM|REFUND_PENDING|RETURN_OR_REFUND_REQUEST_SUCCESS');
	}	
	else
	{
		$("#STATUSTIKTOK4").val($(this).val());
	}
	$("#dataGridTiktok4").DataTable().ajax.reload();
	
});

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function hotkey(e){
// 	if(e.keyCode == 113) // F2
// 	{
// 		tambah();
// 	}
// 	else if(e.keyCode == 115) // F4
// 	{
// 		$("#modal-barang").modal('show');
// 	}
// 	else if(e.keyCode == 119) // F8
// 	{
// 		$("#INPUTBARCODE").focus();
// 	}
// 	else if(e.keyCode == 120) // F9
// 	{
// 		simpan();
// 	}
// 	else if(e.keyCode == 192) //`
// 	{
// 		$("#INPUTBARCODE").focus();
// 	}
	
}

function getStatusTiktok(index){
	return $("#STATUSTIKTOK"+index).val();
}

function refreshTiktok(index){
    loading();
    $("#dataGridTiktok"+index).DataTable().ajax.reload();
}

function changeTabTiktok(index){
    
    if(!sinkronTiktokState)
    {
        loading();
    }
    
    
    // if(firsTimeTiktok[1])
    // {
    //      $.ajax({
    //     	type    : 'POST',
    //     	url     : base_url+'Tiktok/init/<?=date('Y-m-d')?>/<?=date('Y-m-d')?>/update_time',
    //     	dataType: 'json',
    //     	success : function(msg){
        	    
    //     	}
	   //  });
    // }
    // else if(!firsTimeTiktok[index])
    // {
    //      $.ajax({
    //     	type    : 'POST',
    //     	url     : base_url+'Tiktok/init/<?=date('Y-m-d')?>/<?=date('Y-m-d')?>/update_time',
    //     	dataType: 'json',
    //     	success : function(msg){
        	    
    //     	}
	   //  });
    // }
    
    $("#filter_status_tiktok_"+index+", #filter_tgl_tiktok_"+index).show();
    
    for(var x = 1; x <= 4 ; x++)
    {
       if(index != x)
       {
            $("#filter_status_tiktok_"+x+", #filter_tgl_tiktok_"+x+"").hide();
       }
    }
    
    if(firsTimeTiktok[index])
    {
        firsTimeTiktok[index] = false;
    	//GRID BARANG
    	if(index != 4)
    	{
        	$('#dataGridTiktok'+index).DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true, // Enable ordering
                'order'       : [[3, 'desc']], 
                'info'        : true,
                'autoWidth'   : false,
        		"scrollX"	  : true,
        		"createdRow"  : function( row, data, dataIndex ) {
        		    if (data.STATUS.toUpperCase() == "BELUM BAYAR") {
                        $(row).addClass('status-belum-bayar');
                    }
                    else if (data.STATUS == "P") {
                        $(row).addClass('status-lanjut');
                    }
                    else if (data.STATUS == "D") {
                        $(row).addClass('status-batal');
                    }
                },
        		ajax		  : {
        			url    : base_url+'Tiktok/dataGrid/',
        			dataSrc: "rows",
        			type   : "POST",
        			data   : function(e){
        			        e.state          = index;
        					e.status 		 = getStatusTiktok(index);
        					e.tglawal        = $('#tgl_awal_filter_tiktok_'+index).val();
        					e.tglakhir       = $('#tgl_akhir_filter_tiktok_'+index).val();
        				  }
        		},
                columns:[
                    { data: '' },    	
                    { data: 'STATUS',className:"text-center"},	
                    { data: 'KODEPESANAN' ,className:"text-center"},
                    { data: 'TGLPESANAN' ,className:"text-center"},
                    { data: 'BARANG'},
                    { data: 'TOTALBARANG', render:format_number, className:"text-center"},
                    { data: 'TOTALBAYAR', render:format_number, className:"text-right"},
                    { data: 'METODEBAYAR' ,className:"text-center"},
                    { data: 'ALAMAT' ,className:"text-left"},
                    { data: 'KURIR' ,className:"text-center"},
                    { data: 'RESI' ,className:"text-center"},
                    { data: 'CATATANBELI' },
                    { data: 'CATATANJUAL' },    
                ],
        		columnDefs: [ 
        			{
                        "targets": 0,
                        "data": null,
                        render: function (data, type, row) {
                            let html = "<div style='height:150px; display: flex; flex-direction: column; justify-content: space-between;'>";
                            if (row.STATUS.toUpperCase() == "DIPROSES" ||  row.STATUS.toUpperCase() == "SIAP DIKIRIM" || row.STATUS.toUpperCase() == "DALAM PENGIRIMAN" || row.STATUS.toUpperCase() == "TELAH DIKIRIM") {
                                html += "<button id='btn_lihat_tiktok' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                if(row.STATUS.toUpperCase() == "DIPROSES")
                                {
                                    // html += "<button  style='margin-top:5px;' id='btn_hapus_tiktok' class='btn btn-danger'  style='width:122px;' >Batal</button>"; 
                                    // html += "<button style='margin-top:5px;' id='btn_edit_tiktok' class='btn btn-primary' style='width:122px;' >Ubah</button>";
                                    html+= "<button  style='margin-top:auto;' id='btn_cetak_tiktok' class='btn btn-warning'  style='width:122px;'>Cetak</button>";
                                }
                                if(row.STATUS.toUpperCase() == "SIAP DIKIRIM") // row.STATUS.toUpperCase() == "SIAP DIKIRIM"
                                {
                                    html += "<button  style='margin-top:5px;' id='btn_hapus_tiktok' class='btn btn-danger'  style='width:122px;' >Batal</button>"; 
                                    // html += "<button style='margin-top:5px;' id='btn_edit_tiktok' class='btn btn-primary' style='width:122px;' >Ubah</button>
                                    html += "<div style='margin-top:auto;'><button id='btn_kirim_tiktok' class='btn btn-success' style='width:122px;'>Atur Pengiriman</button></div>";
                                }
                                if (row.STATUS.toUpperCase() == "DALAM PENGIRIMAN" || row.STATUS.toUpperCase() == "TELAH DIKIRIM") 
                                {
                                    html += "<div style='margin-top:auto;'><button id='btn_lacak_tiktok' class='btn btn-success' style='width:122px;'>Lacak Pesanan</button></div>";
                                }
                                
                            }  else if(row.STATUS.toUpperCase() == "SELESAI" && row.KODEPENGEMBALIAN != "" && row.BARANGSAMPAIMANUAL == 0){
                                html += "<button id='btn_lihat_lazada' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<button  style='width:122px; margin-top:5px;' id='btn_retur_manual_lazada' class='btn btn-danger'  style='width:122px;' >Retur B. Manual</button>";
                            }   else {
                                html += "<button id='btn_lihat_tiktok' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                            }
                            html += "</div>";
                            return html;
                        }
                    }
                    ,
                    {
                        "targets": 1,
                        render: function (data, type, row) {
                            let html = row.STATUS;
                            if(row.STATUS.toUpperCase() == "SELESAI"  && (row.BARANGSAMPAI == 1 || row.BARANGSAMPAIMANUAL == 1)){
                                html += "<div style='width:122px; white-space: pre-wrap; white-space: -moz-pre-wrap;  white-space: -pre-wrap;  white-space: -o-pre-wrap;word-wrap: break-word; text-align:center; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>;'>Retur Barang</div><div style='margin:auto;'></div>";
                            } else if(row.STATUS.toUpperCase() == "SELESAI" && row.KODEPENGEMBALIAN != ""){
                                html += "<div style='width:122px; white-space: pre-wrap; white-space: -moz-pre-wrap;  white-space: -pre-wrap;  white-space: -o-pre-wrap;word-wrap: break-word; text-align:center; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>;'>Retur Dana</div><div style='margin:auto;'></div>";
                            }
                            return html;
                        }
                    }
                    ,
                    {
                        "targets": 2,
                        render: function (data, type, row) {
                            let html = row.KODEPESANAN;
                            if(row.STATUS.toUpperCase() == "SELESAI" && row.KODEPENGEMBALIAN != ""){
                                html += "<div style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>;width:122px; white-space: pre-wrap; white-space: -moz-pre-wrap;  white-space: -pre-wrap;  white-space: -o-pre-wrap;word-wrap: break-word; text-align:center;'>"+row.KODEPENGEMBALIAN+"</div>";
                            } 
                            return html;
                        }
                    }
        		]
            });
    	}
    	else
    	{
    	    //RETUR
    	    $('#dataGridTiktok'+index).DataTable({
                'paging'      : true,
                'lengthChange': true,
                'searching'   : true,
                'ordering'    : true, // Enable ordering
                'order'       : [[3, 'desc']], 
                'info'        : true,
                'autoWidth'   : false,
        		"scrollX"	  : true,
        		"createdRow"  : function( row, data, dataIndex ) {
        		    if (data.STATUS.toUpperCase() == "BELUM BAYAR") {
                        $(row).addClass('status-belum-bayar');
                    }
                    else if (data.STATUS == "P") {
                        $(row).addClass('status-lanjut');
                    }
                    else if (data.STATUS == "D") {
                        $(row).addClass('status-batal');
                    }
                },
        		ajax		  : {
        			url    : base_url+'Tiktok/dataGrid/',
        			dataSrc: "rows",
        			type   : "POST",
        			data   : function(e){
        			        e.state          = index;
        					e.status 		 = getStatusTiktok(index);
        					e.tglawal        = $('#tgl_awal_filter_tiktok_'+index).val();
        					e.tglakhir       = $('#tgl_akhir_filter_tiktok_'+index).val();
        				  }
        		},
                columns:[
                    { data: '' },    	
                    { data: 'STATUS',className:"text-center"},	
                    { data: 'KODEPENGEMBALIAN' ,className:"text-center"},
                    { data: 'TGLPENGEMBALIAN' ,className:"text-center"},
                    { data: 'KODEPESANAN' ,className:"text-center"},
                    { data: 'BARANGPENGEMBALIAN'},
                    { data: 'TOTALBARANGPENGEMBALIAN', render:format_number, className:"text-center"},
                    { data: 'TOTALPENGEMBALIANDANA', render:format_number, className:"text-right"},
                    { data: 'ALAMAT' ,className:"text-left"},
                    { data: 'RESIPENGEMBALIAN' ,className:"text-center"},
                    { data: 'CATATANPENGEMBALIAN' },
                    { data: 'CATATANJUAL' },    
                ],
        		columnDefs: [ 
        			{
                        "targets": 0,
                        "data": null,
                        render: function (data, type, row) {
                            let html = "<div style='height:150px; display: flex; flex-direction: column; justify-content: space-between;'>";
                                html += "<button id='btn_kembali_tiktok' style='border:1px solid #CECECE;' class='btn' >Detail Pengembalian</button>";
                                if(row.TIPEPENGEMBALIAN == "RETURN_DELIVERED")
                                {
                                    html += "<i style='color:red;'>Barang Telah Diterima Penjual</i>";
                                }
                                html += "<div style='margin-top:5px; width:122px; white-space: pre-wrap; white-space: -moz-pre-wrap;  white-space: -pre-wrap;  white-space: -o-pre-wrap;word-wrap: break-word;'>*Cek Status / Jawab bisa melalui<br>Detail Pengembalian</div><div style='margin:auto;'></div>";
                            html += "</div>";
                            return html;
                        }
                    },
        		]
            });
    	}
        
        
    	//DAPATKAN INDEX
    	var table = $('#dataGridTiktok'+index).DataTable();
    	$('#dataGridTiktok'+index+' tbody').on( 'click', 'button', function () {
    		var row = table.row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		$("#rowDataTiktok").val(JSON.stringify(row));
    		
    		if(mode == "btn_lihat_tiktok"){ lihatTiktok();}
    // 		else if(mode == "btn_edit_tiktok"){ubahTiktok();}
    		else if(mode == "btn_cetak_tiktok"){cetakTiktok();}
    		else if(mode == "btn_hapus_tiktok"){hapusTiktok();}
    		else if(mode == "btn_kirim_tiktok"){kirimTiktok();}
    		else if(mode == "btn_lacak_tiktok"){lacakTiktok();}
    		else if(mode == "btn_kembali_tiktok"){kembaliTiktok();}
    		else if(mode == "btn_retur_tiktok"){returTiktok();}
    	    else if(mode == "btn_retur_manual_tiktok"){returBarangLazada();}
    	} );
    	
    	$('#dataGridTiktok'+index+' tbody').on( 'click', 'i', function () {
    		var row = table.row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		$("#rowDataTiktok").val(JSON.stringify(row));
    		$("#fromNoteTiktok").val("GRID_X");
    		if(mode == "editNoteTiktok"){catatanPenjualTiktok();}
    	} );
    }
    else
    {
        $("#dataGridTiktok"+index).DataTable().ajax.reload();
    }
    
    // Close SweetAlert after data is loaded
    $('#dataGridTiktok'+index).DataTable().on('xhr.dt', function () {
        if(!sinkronTiktokState)
        {
           setTimeout(() => {
               if($('#dataGridTiktok'+index).DataTable().data().count() == 0)
               {
                   $("#totalTiktok"+index).hide();
               }
               else
               {
                    $("#totalTiktok"+index).show();
                    $("#totalTiktok"+index).html($('#dataGridTiktok'+index).DataTable().data().count());
               }
               recountCetakdanKirim(); 
               Swal.close();
           }, "500");
        }
        else
        {
            //JIKA SUDAH DONE SEMUA MAKA SINKRON STATE FALSE, JIKA TIDAK DIKEMBALIKAN TRUE
            sinkronTiktokState = false;
            doneSinkronTiktok[index] = true;
            for(var x = 1; x <= 4 ; x++)
            {
                if(!doneSinkronTiktok[x]){
                    sinkronTiktokState = true;
                }
            }
            
            if(!sinkronTiktokState)
            {
                Swal.close();
                
                setTimeout(() => {
                    var caption = "Tidak Ada Pesanan Baru";
                    if(totalPesananTiktokAll > 0)
                    {
                        caption = 'Terdapat '+totalPesananTiktokAll+' Pesanan Baru'
                    }
                    
                    for(var x = 1; x <= 4 ; x++)
                    {
                       if($('#dataGridTiktok'+x).DataTable().data().count() == 0)
                       {
                           $("#totalTiktok"+x).hide();
                       }
                       else
                       {
                            $("#totalTiktok"+x).show();
                            $("#totalTiktok"+x).html($('#dataGridTiktok'+x).DataTable().data().count());
                            recountCetakdanKirim();
                       }
                    }
                    Swal.fire({
    					title            :  caption,
    					type             : 'success',
    					showConfirmButton: false,
    					timer            : 2000
    				});
                }, "1000");

            }
        }
    });
}

function recountCetakdanKirim(){
    var data = $("#dataGridTiktok1").DataTable().rows().data();
    var countCetak = 0;
    var countKirim = 0;
    for(var x = 0; x < data.length; x++)
    {
        if((data[x]['STATUS'].toUpperCase() == "DIPROSES"))
        {
            countCetak++;
        }
        
        if(data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM")
        {
            countKirim++;
        }
    }
    
    $("#cetakLangsungSemua").html("Cetak Semua Pesanan ("+countCetak.toString()+")");
    $("#kirimLangsungSemua").html("Atur Semua Pengiriman ("+countKirim.toString()+")");
    
    if(countCetak == 0)
    {
        $("#cetakLangsungSemua").hide();
    }
    else
    {
        $("#cetakLangsungSemua").show();
    }
    
    if(countKirim == 0)
    {
        $("#kirimLangsungSemua").hide();
    }
    else
    {
        $("#kirimLangsungSemua").show();
    }
    
    if(countCetak != 0 && countKirim != 0)
    {
        $("#kirimLangsungSemua").css("margin-left","10px");
    }
}

function lihatTiktok(){
    var row = JSON.parse($("#rowDataTiktok").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Tiktok/loadDetail/',
    	data    : {kode: row.KODEPESANAN,metodebayar : row.METODEBAYAR},
    	dataType: 'json',
    	success : function(msg){
    	    $("#cetakTiktokDetail").hide();
    	    $("#hapusTiktokDetail").hide();
    	    $("#ubahTiktokDetail").hide();
    	    $("#kirimTiktokDetail").hide();
    	    $("#lacakTiktokDetail").hide();
    	    $("#returBarangTiktokDetail").hide();
            
    	    if(row.STATUS.toUpperCase() == "SIAP DIKIRIM")
    	    {
    	        $("#hapusTiktokDetail").show();
    	        $("#ubahTiktokDetail").show();
    	    }             
    	    if(row.STATUS.toUpperCase() == "SIAP DIKIRIM")
    	    {
    	        $("#kirimTiktokDetail").show(); 
    	    }
    	    if(row.STATUS.toUpperCase() == "DIPROSES")
            {
                 $("#cetakTiktokDetail").show();
            }
    	    if(row.STATUS.toUpperCase() == "DALAM PENGIRIMAN" || row.STATUS.toUpperCase() == "GAGAL PENGIRIMAN")
    	    {
    	        $("#lacakTiktokDetail").show();
    	    }
    	    
            $("#NOTIKTOK").html("#"+row.KODEPESANAN);
            $("#STATUSTIKTOK").html(row.STATUS);
            $("#TGLPESANANTIKTOK").html(row.TGLPESANAN.replaceAll("<br>"," "));
            $("#TGLKIRIMTIKTOK").html(row.KURIR==""?"-":row.MINTGLKIRIM);
            $("#PEMBAYARANTIKTOK").html(row.METODEBAYAR);
            $("#KURIRTIKTOK").html((row.KURIR==""?"-":row.KURIR)+" / "+(row.RESI==""?"-":row.RESI));
            $("#NAMAPEMBELITIKTOK").html(row.BUYERNAME+" ("+row.USERNAME+")");
            $("#TELPPEMBELITIKTOK").html(row.BUYERPHONE);
            $("#ALAMATPEMBELITIKTOK").html(row.BUYERALAMAT);
            $("#CATATANPEMBELITIKTOK").html(row.CATATANBELI);
            $("#CATATANPEMBELITIKTOK").html($("#CATATANPEMBELITIKTOK div").html()==""?"<div>-</div>":row.CATATANBELI);
            $("#ALASANPENGEMBALIANTIKTOK").html(row.CATATANPENGEMBALIAN);
            
            if($("#ALASANPENGEMBALIANTIKTOK div").html() != "")
            {
                $(".alasanKembaliTiktok").show();
                $("#ALASANPENGEMBALIANTIKTOK").html(row.CATATANPENGEMBALIAN);
            }
            else
            {
                $(".alasanKembaliTiktok").hide();
                $("#ALASANPENGEMBALIANTIKTOK").html("<div>-</div>");
            }
            
            $(".noKembaliTiktok").show();
            if(row.KODEPENGEMBALIAN != null)
            {
                $(".noKembaliTiktok").show();
                $("#NOPENGEMBALIANTIKTOK").html(row.KODEPENGEMBALIAN);
            }
            
            if(row.STATUS.toUpperCase() == "SELESAI" && row.BARANGSAMPAIMANUAL == 0)
            {
               $("#returBarangTiktokDetail").show();
            }
            
            $(".table-responsive-tiktok").html('');
            
            var totalCurr = 0;
            var totalCurrKembali = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
                var namaBarang = msg.DETAILBARANG[x].NAMA;
                if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
                {
                    namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
                }
                
                if((msg.DETAILBARANG[x].SIZEKEMBALI != "" || msg.DETAILBARANG[x].WARNAKEMBALI != "") && (row.BARANGSAMPAI == 1 || row.BARANGSAMPAIMANUAL == 1))
                {
                    namaBarang += ("<br><span  style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; font-style:italic;'>Retur : "+msg.DETAILBARANG[x].WARNA+" / "+msg.DETAILBARANG[x].SIZE+"</span>");
                }
                
                $(".table-responsive-tiktok").append(setDetail(msg.DETAILBARANG,x,namaBarang,false));
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
                totalCurrKembali += parseInt(msg.DETAILBARANG[x].JUMLAHKEMBALI);
            }
            var totalKembali = "";
            if(totalCurrKembali > 0 && (row.BARANGSAMPAI == 1 || row.BARANGSAMPAIMANUAL == 1))
            {
                totalKembali = "<span style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>' > (-"+currency(totalCurrKembali.toString())+")</span>";
            }
            $("#TOTALQTYTIKTOK").html(currency(totalCurr)+totalKembali);
            $("#SUBTOTALTIKTOK").html(currency(msg.SUBTOTALBELI));
            
            $("#TOTALPENJUALTIKTOK").html(currency(msg.SUBTOTALJUAL));
            $("#DISKONPENJUALTIKTOK").html(currency(msg.DISKONJUAL));
            $("#BIAYAKIRIMPENJUALTIKTOK").html(currency(msg.BIAYAKIRIMJUAL));
            $("#BIAYALAYANANPENJUALTIKTOK").html(currency(msg.BIAYALAYANANJUAL));
            $("#GRANDTOTALPENJUALTIKTOK").html(currency(msg.PENERIMAANJUAL));
            $("#REFUNDPENJUALTIKTOK").html(currency(msg.REFUNDJUAL));
            $("#PENYELESAIANPENJUALTIKTOK").html(currency(msg.PENYELESAIANPENJUAL));
           
           
           $("#TOTALPEMBELITIKTOK").html(currency(msg.SUBTOTALBELI));
           $("#DISKONPEMBELITIKTOK").html(currency(msg.DISKONBELI));
           $("#BIAYAKIRIMPEMBELITIKTOK").html(currency(msg.BIAYAKIRIMBELI));
           $("#BIAYALAINPEMBELITIKTOK").html(currency(msg.BIAYALAINBELI));
           $("#PEMBAYARANPEMBELITIKTOK").html(currency(msg.PEMBAYARANBELI));
           if(row.STATUS == "Selesai" || row.STATUS == "Pembatalan")
           {
               $(".penyelesaianTiktok").show();
           }
           else
           {
               $(".penyelesaianTiktok").hide();
           }
            Swal.close();
            $("#modal-form-tiktok").modal('show');
    			
    	}
    });
}

function kembaliTiktok(){
    var row = JSON.parse($("#rowDataTiktok").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Tiktok/loadDetailPengembalian/',
    	data    : {kode: row.KODEPENGEMBALIAN},
    	dataType: 'json',
    	success : function(msg){
    	    
    		$("#dataReturTiktok").val(JSON.stringify(msg));	
    		
            $("#NOTIKTOKPENGEMBALIAN").html("#"+row.KODEPENGEMBALIAN);
            $("#NOTIKTOKPESANANPENGEMBALIAN").html(row.KODEPESANAN);
            $("#STATUSTIKTOKPENGEMBALIAN").html(row.STATUS.replaceAll("<br>"," "));
            $("#TGLTIKTOKPENGEMBALIAN").html(row.TGLPENGEMBALIAN.replaceAll("<br>"," "));
            if( row.MINTGLPENGEMBALIAN == "0000-00-00 00:00:00")
            {
                $("#MINTGLTIKTOKPENGEMBALIAN").html("-");
            }
            else
            {
                $("#MINTGLTIKTOKPENGEMBALIAN").html(row.MINTGLPENGEMBALIAN.replaceAll("<br>"," "));
            }
            $("#RESITIKTOKPENGEMBALIAN").html((row.RESIPENGEMBALIAN==""?"-":row.RESIPENGEMBALIAN));
            $("#NAMAPEMBELITIKTOKPENGEMBALIAN").html(row.BUYERNAME+" ("+row.USERNAME+")");
            $("#TELPPEMBELITIKTOKPENGEMBALIAN").html(row.BUYERPHONE);
            $("#ALAMATPEMBELITIKTOKPENGEMBALIAN").html(row.BUYERALAMAT);
            $("#ALASANTIKTOKPILIHAN").html(row.CATATANPENGEMBALIAN);
            $("#ALASANTIKTOKPENGEMBALIAN").html(msg.ALASANPENGEMBALIAN);
            $("#ALASANTIKTOKPENGEMBALIAN").html($("#ALASANTIKTOKPENGEMBALIAN div").html()==""?"<div>-</div>":msg.ALASANPENGEMBALIAN);
            
            $(".table-responsive-tiktok-pengembalian").html('');
            
            $("#returTiktokDetail").hide();
            $("#returTiktokWait").show();
            
            // if(row.TIPEPENGEMBALIAN.toUpperCase() == "RETURN_DELIVERED")
            // {
            //     $("#STATUSTIKTOKPENGEMBALIAN").html($("#STATUSTIKTOKPENGEMBALIAN").html()+"<br>&nbsp;<i style='color:red;'>(Barang Telah Diterima Penjual)</i>");
            // }
            
            // if (row.STATUS.toUpperCase() == "PENGEMBALIAN<BR>DIAJUKAN" || row.TIPEPENGEMBALIAN.toUpperCase() == "RETURN_DELIVERED") {
            //     $("#returTiktokDetail").show();
            //     $("#returTiktokWait").hide();
            // }
            
            // if (row.STATUS.toUpperCase() == "PENGEMBALIAN<BR>DIPROSES" &&  row.TIPEPENGEMBALIAN.toUpperCase() != "RETURN_DELIVERED") {
            //     $("#returTiktokWait").html("Menunggu Barang Tiba");
            // }
            
            // if (row.STATUS.toUpperCase() == "PENGEMBALIAN<BR>DALAM SENGKETA" || row.STATUSPENGEMBALIAN.toUpperCase() == "REFUND_PENDING") {
            //     $("#returTiktokDetail").hide();
            //     $("#returTiktokWait").show();
            //     $("#returTiktokWait").html("Menunggu Respon Tiktok");
            // }
            
            var totalCurr = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
                var namaBarang = msg.DETAILBARANG[x].NAMA;
                if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
                {
                    namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
                }
                
                $(".table-responsive-tiktok-pengembalian").append(`<tr>
                	<td style="vertical-align:middle; text-align:left;" width="400px" >`+namaBarang+`</td>
                  	<td style="vertical-align:middle; text-align:center;" width="50px">`+currency(msg.DETAILBARANG[x].JUMLAH.toString())+`</td>
                  	<td style="vertical-align:middle; text-align:center;" width="50px">`+msg.DETAILBARANG[x].SATUAN.toString()+`</td>
                  	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(msg.DETAILBARANG[x].HARGA.toString())+`</td>
                  	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(msg.DETAILBARANG[x].SUBTOTAL.toString())+`</td>
                </tr>`);
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
            }
            
            $("#TOTALQTYPENGEMBALIANTiktok").html(currency(totalCurr));
            $("#SUBTOTALPENGEMBALIANTiktok").html(currency(msg.TOTALREFUND));
            
            var buktiGambar = "";
            for(var x = 0 ; x < msg.GAMBAR.length;x++)
            {
                buktiGambar += "<span style='color : blue; cursor:pointer; text-align:center;' onclick='lihatLebihJelasTiktok(`GAMBAR`,`Gambar "+(x+1)+"`,`"+msg.GAMBAR[x].url+"`)' >Gambar "+(x+1)+"</span><br>";
            }
            $("#GAMBARPENGEMBALIANTIKTOK").html(buktiGambar);
            
            var buktiVideo = "";
            for(var x = 0 ; x < msg.VIDEO.length;x++)
            {
                buktiVideo += "<span style='color : blue; cursor:pointer; text-align:center;' onclick='lihatLebihJelasTiktok(`VIDEO`,`Video "+(x+1)+"`,`"+msg.VIDEO[x].url+"`)' >Video "+(x+1)+"</span><br>";
            }
            $("#VIDEOPENGEMBALIANTIKTOK").html(buktiVideo);
            
            Swal.close();
            $("#modal-pengembalian-tiktok").modal('show');
    	}
    });
}

function lihatLebihJelasTiktok(jenis,title,url){

    $("#modal-lebih-jelas-tiktok").modal("show");
    $("#titleLebihJelasTiktok").html(title);
    $("#previewLebihJelasTiktok").css("color","#3296ff");
    $("#previewLebihJelasTiktok").css("cursor","pointer");
    $("#previewLebihJelasTiktok").css("text-align","center");
    $("#previewLebihJelasTiktok").css("background","#d4d4d7");
    if(jenis == "GAMBAR")
    {
        $("#previewLebihJelasTiktok").html("<img src='"+url+"' max-width=100%; height=600px;>");
    }
    else
    {
        $("#previewLebihJelasTiktok").html("<iframe src='"+url+"' max-width=100%; height=600px;>");
    }
}

// function ubahTiktok(){
//     $("#modal-form-tiktok").modal('hide');
//     var row = JSON.parse($("#rowDataTiktok").val());
//     loading();
//     $.ajax({
//     	type    : 'POST',
//     	url     : base_url+'Tiktok/loadDetail/',
//     	data    : {kode: row.KODEPESANAN},
//     	dataType: 'json',
//     	success : function(msg){
//             $("#NOTIKTOKUBAH").html("#"+row.KODEPESANAN);
//             $(".table-responsive-tiktok-ubah").html('');
            
//             var totalCurr = 0;
//             for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
//             {
//                 var namaBarang = msg.DETAILBARANG[x].NAMA;
//                 if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
//                 {
//                     namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
//                 }
                
//                 $(".table-responsive-tiktok-ubah").append(setDetail(msg.DETAILBARANG,x,namaBarang,true));
//                 totalCurr += msg.DETAILBARANG[x].JUMLAH;
//             }
//             $("#TOTALQTYTIKTOKUBAH").html(currency(totalCurr));
//             $("#SUBTOTALTIKTOKUBAH").html(currency(msg.SUBTOTALBELI));
//             Swal.close();
//             $("#itemUbahTiktok").val(JSON.stringify(msg.DETAILBARANG));
//             $("#modal-ubah-tiktok").modal('show');
//     	}
//     });
// }

function openItemTiktok(indexItem){
    var itemDetail = JSON.parse($("#itemUbahTiktok").val());
    $("#kategori_item_tiktok").val(itemDetail[indexItem].KATEGORI);
    $("#warnaOldTiktok").html(itemDetail[indexItem].WARNAOLD);
    $("#sizeOldTiktok").html(itemDetail[indexItem].SIZEOLD);
    $("#table_barang_tiktok").DataTable().ajax.reload();
    $("#modal-barang-tiktok").modal('show');
}

function resetItemTiktok(indexItem){
    var itemDetail = JSON.parse($("#itemUbahTiktok").val());
    
    itemDetail[indexItem]['NAMA']   = itemDetail[indexItem]['NAMAOLD'];
    itemDetail[indexItem]['WARNA']  = itemDetail[indexItem]['WARNAOLD'];
    itemDetail[indexItem]['SIZE']   = itemDetail[indexItem]['SIZEOLD'];
    itemDetail[indexItem]['SKU']    = itemDetail[indexItem]['SKUOLD'];

    $(".table-responsive-tiktok-ubah").html('');
    
    for(var x = 0 ; x < itemDetail.length ; x++)
    {
        var namaBarang = itemDetail[x].NAMA;
        if(itemDetail[x].SIZE != itemDetail[x].SIZEOLD || itemDetail[x].WARNA != itemDetail[x].WARNAOLD)
        {
            namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span style='color:#949494; font-style:italic;'>Marketplace : "+itemDetail[x].WARNAOLD+" / "+itemDetail[x].SIZEOLD+"</span>");
        }
            	 
       $(".table-responsive-tiktok-ubah").append(setDetail(itemDetail,x,namaBarang,true));        
    }
     
     
    $("#itemUbahTiktok").val(JSON.stringify(itemDetail));
}

function ubahKonfirmTiktok(){
     Swal.fire({
        title: 'Anda Yakin Mengubah Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var row = JSON.parse($("#rowDataTiktok").val());
                loading();
                $.ajax({
                	type    : 'POST',
                	url     : base_url+'Tiktok/ubah/',
                	data    : {kode: row.KODEPESANAN, dataItem: $("#itemUbahTiktok").val()},
                	dataType: 'json',
                	success : function(msg){
                	    
                        if(msg.success)
                        {
                            Swal.close();
                            $("#modal-ubah-tiktok").modal('hide');
                        }
                        
                	    Swal.fire({
                        	title            :  msg.msg,
                        	type             : (msg.success?'success':'error'),
                        	showConfirmButton: false,
                        	timer            : 2000
                        });
                        
                        setTimeout(() => {
                          reloadTiktok();
                        }, "2000");
                	}
                });
        	}
        });
}

function setDetail(itemDetail,x,namaBarang,action=false)
{
    var row = JSON.parse($("#rowDataTiktok").val());
    var actButton = '';
    var jmlKembali = '';
    if(action)
    {
       actButton = `<td style="vertical-align:middle; text-align:center;" width="103px" ><button id="btn_edit_detail_tiktok" class="btn btn-primary" onclick="openItemTiktok(`+x+`)"><i class="fa fa-edit"></i></button> <button id="btn_back_detail_tiktok" class="btn btn-danger" onclick="resetItemTiktok(`+x+`)"><i class="fa fa-refresh"></i></button></td>`;
    }
    
    if(itemDetail[x].JUMLAHKEMBALI != 0 && (row.BARANGSAMPAI == 1 || row.BARANGSAMPAIMANUAL == 1))
    {
        jmlKembali = "<span style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>' > (-"+currency(itemDetail[x].JUMLAHKEMBALI.toString())+")</span>";
    }
    
    return ` <tr>
                `+actButton+`
            	<td style="vertical-align:middle; text-align:left;" width="400px" >`+namaBarang+`</td>
              	<td style="vertical-align:middle; text-align:center;" width="50px">`+currency(itemDetail[x].JUMLAH.toString())+jmlKembali+`</td>
              	<td style="vertical-align:middle; text-align:center;" width="50px">`+itemDetail[x].SATUAN.toString()+`</td>
              	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(itemDetail[x].HARGATAMPIL.toString())+`</td>
              	<td style="vertical-align:middle; text-align:right;" width="100px" >`+currency(itemDetail[x].HARGACORET.toString())+`</td>
              	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(itemDetail[x].SUBTOTAL.toString())+`</td>
            </tr>`;
}

function cetakTiktok(){
    $("#modal-form-tiktok").modal('hide');
    var row = JSON.parse($("#rowDataTiktok").val());
    var rows = [row];
    loading();
     $.ajax({
     	type    : 'POST',
     	url     : base_url+'Tiktok/print/',
     	data    : {dataNoPesanan: JSON.stringify(rows)},
     	dataType: 'json',
     	success : function(msg){
     	        
     	        if(msg.success)
                {
                    Swal.close();
                    $("#modal-note-tiktok").modal('hide');
                    
                 	setTimeout(() => {
                      reloadTiktok();
                      $("#countCetak").html("("+rows.length+")");
                      $("#previewCetakTiktok").html("<iframe src='"+msg.merge_url+"' width=100%; height=600px;>");
                      $("#modal-cetak-tiktok").modal('show');
                    }, "2000");
                }
            
     		    Swal.fire({
             		title            :  msg.msg,
             		type             : (msg.success?'success':'error'),
             		showConfirmButton: false,
             		timer            : 2000
             	});
     	}
     });
}

function cetakTiktokSemua(index){
    $("#modal-cetak-all-tiktok").modal('show');
    $("#pilihCetakAllTiktok").prop("checked",true);
    var data = $("#dataGridTiktok"+index).DataTable().rows().data();
    var detailData = "";
    var dataSimpan = [];
    for(var x = 0; x < data.length; x++)
    {
        if(data[x]['STATUS'].toUpperCase() == "DIPROSES")
        {
            dataSimpan.push(data[x]);
        }
    }
    
    for(var x = 0; x < dataSimpan.length; x++)
    {
       detailData += ` 
           <tr>
           <td style="vertical-align:middle; text-align:center;" width="30px" "><input type="checkbox" id="cetak`+x+`" width="30px" checked></td>
           <td style="vertical-align:middle; text-align:center;" width="150px" >`+dataSimpan[x]['KODEPESANAN']+`</td>
           <td style="vertical-align:middle; text-align:center;" width="150px" >`+dataSimpan[x]['METODEBAYAR']+`</td>
           <td style="vertical-align:middle; text-align:center;" width="100px">`+dataSimpan[x]['KURIR']+`</td>
           <td style="vertical-align:middle; text-align:center;" width="150px">`+dataSimpan[x]['RESI']+`</td>
           </tr>
       `;
    }
  
    if(dataSimpan.length != 0)
    {
        $("#countCetakSemua").html("("+dataSimpan.length+")");
    }
    else
    {
         $("#countCetakSemua").html("");
    }
    $("#dataCetakSemuaTiktok").val(JSON.stringify(dataSimpan));
    $(".table-responsive-tiktok-all-cetak").html(detailData);
    
    for(var x = 0; x < dataSimpan.length; x++)
    {
     $('#cetak'+x).change(function () {
         var count = 0;
         for(var x = 0; x < dataSimpan.length; x++)
         {
             if($(".table-responsive-tiktok-all-cetak").find("#cetak"+x).is(':checked'))
             {
                count++;
             }
         }
         
         if(count != 0)
         {
             $("#countCetakSemua").html("("+count+")");
         }
         else
         {
              $("#countCetakSemua").html("");
         }
         
         if(count == dataSimpan.length)
         {
             $("#pilihCetakAllTiktok").prop("checked",true);
         }
         else
         {
             $("#pilihCetakAllTiktok").prop("checked",false);
         }
      });
    }
    
    $("#pilihCetakAllTiktok").change(function(){
        for(var x = 0; x < dataSimpan.length; x++)
         {
            $(".table-responsive-tiktok-all-cetak").find("#cetak"+x).prop("checked",$(this).prop("checked"));
         }
         
         if($(this).prop("checked"))
         {
             $("#countCetakSemua").html("("+dataSimpan.length+")");
         }
         else
         {
              $("#countCetakSemua").html("");
         }
    });

}

function cetakAllKonfirmTiktok(){
    var dataSimpan = JSON.parse($("#dataCetakSemuaTiktok").val());
    var rows = [];
    for(var x = 0; x < dataSimpan.length; x++)
    {
        if($(".table-responsive-tiktok-all-cetak").find("#cetak"+x).is(':checked'))
        {
           rows.push(dataSimpan[x]);
        }
    }
    if(rows.length > 0)
    {
        loading();
        $.ajax({
         	type    : 'POST',
         	url     : base_url+'Tiktok/print/',
         	data    : {dataNoPesanan: JSON.stringify(rows)},
         	dataType: 'json',
         	success : function(msg){
         	        
         	        if(msg.success)
                    {
                        Swal.close();
                        $("#modal-note-tiktok").modal('hide');
                        $("#modal-cetak-all-tiktok").modal('hide');
                        
                     	setTimeout(() => {
                          reloadTiktok();
                          $("#countCetak").html("("+rows.length+")");
                          var iframe = "";
                          iframe += "<iframe id='TiktokCETAK"+x+"' src='"+msg.merge_url+"' width=100%; height=600px;/><br><br>";
                          $("#previewCetakTiktok").html(iframe);
                          $("#modal-cetak-tiktok").modal('show');
                        }, "2000");
                    }
                
         		    Swal.fire({
                 		title            :  msg.msg,
                 		type             : (msg.success?'success':'error'),
                 		showConfirmButton: false,
                 		timer            : 2000
                 	});
         	}
         });
    }
    else
    {
      Swal.fire({
     		title            : "Tidak ada data yang dicentang",
     		type             : 'error',
     		showConfirmButton: false,
     		timer            : 2000
     });
    }
}


function kirimTiktok(){
    $("#modal-form-tiktok").modal('hide');
    var row = JSON.parse($("#rowDataTiktok").val());
    var rows = [row];
    loading();
    
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Tiktok/cekStokLokasi/',
    	dataType: 'json',
    	success : function(msg){
            if(!msg.success)
            {
                Swal.fire({
                		title            :  msg.msg,
                		type             : (msg.success?'success':'error'),
                		showConfirmButton: false,
                		timer            : 2000
                });
            }
            else
            {
                $.ajax({
                     	type    : 'POST',
                     	url     : base_url+'Tiktok/setKirim/',
                     	data    : {dataNoPackaging: JSON.stringify(rows),index:0},
                     	dataType: 'json',
                     	success : function(msg){
                     	    Swal.close();
                            $("#modal-kirim-tiktok").modal('show');
                            
                            var cbJemputTiktok = "";
                            if(rows[0].KODEPENGAMBILAN != "")
                            {
                                cbJemputTiktok += ` <option value="PICKUP" selected>Request Pickup</option>`;
                            }
                            else
                            {
                                cbJemputTiktok += `	<option value="DROP_OFF" selected>Drop Off</option>
                                          			<option value="PICKUP">Request Pickup</option>`;
                            }
                            
                            var countPengiriman = 1;
                            $("#countAturPengiriman").html("("+countPengiriman.toString()+")");
                            $(".table-responsive-tiktok-kirim").html('');
                            var indexKirim = 0;
                            
                            var cb_pickup = "";
                            for(var x = 0 ; x < msg.time_pickup.length ; x++)
                            {
                                var selected = "";
                                if(x == 0)
                                {
                                    selected = "selected";
                                    $("#pickupKirimTiktok").val(msg.time_pickup[x].id_pickup);
                                }
                                cb_pickup += ("<option value='"+msg.time_pickup[x].id_pickup+"' "+selected+">"+msg.time_pickup[x].start_pickup+" - "+msg.time_pickup[x].end_pickup+"</option>");
                            }
                            
                            var dataKirim = ` <tr>
                                    	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px" >`+rows[indexKirim].KURIR+`</td>
                                      	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px">`+rows[indexKirim].KODEPESANAN+`</td>
                                      	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="80px">`+currency(rows[indexKirim].TOTALBARANG)+`</td>
                                      	<td style="vertical-align:top; text-align:left;" width="280px">	
                                            <select id="cb_penjemputan_tiktok_`+indexKirim+`" class="form-control "  style="width:280px; " panelHeight="auto" required="true">
                                          		`+cbJemputTiktok+`
                                          	</select>
                                          	<select id="cb_pickup_tiktok_`+indexKirim+`" class="form-control "  style="width:280px; " panelHeight="auto" required="true">
                                              	 `+cb_pickup+`
                                            </select>
                                        </td>
                                      	<td style="vertical-align:top; text-align:left; padding-top:17px;" id="editNoteTiktokDiv`+indexKirim+`">`+rows[indexKirim].CATATANJUAL+`</td>
                                    </tr>`;
                            
                            $(".table-responsive-tiktok-kirim").append(dataKirim);
                            $("#cb_pickup_tiktok_"+indexKirim).hide();
                            
                            $("#cb_penjemputan_tiktok_"+indexKirim).change(function(){
                                if($(this).val() == "DROP_OFF")
                                {
                                    $("#cb_pickup_tiktok_"+indexKirim).hide();
                                    $('#txt_pickup_tiktok_aw_'+indexKirim).val("");
                                    $('#txt_pickup_tiktok_ak_'+indexKirim).val("");
                                }
                                else if($(this).val() == "PICKUP")
                                {
                                    $("#cb_pickup_tiktok_"+indexKirim).show();
                                }
                            })
                            
                            $("#rowDataPengirimanTiktok").val(JSON.stringify(rows));
                            $('#editNoteTiktokDiv'+indexKirim).find('#editNoteTiktok').click(function(){
                               $("#fromNoteTiktok").val("KIRIMTIKTOK_"+indexKirim);
                                catatanPenjualTiktok();
                            });
                            
                            $("#cb_pickup_tiktok_"+indexKirim).change(function(){
                                $("#pickupKirimTiktok").val($(this).val());
                            })
                    }
                });
        }
    }});
 
}

function kirimKonfirmTiktok(){
    
    Swal.fire({
        title: 'Anda Yakin Mengirim Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var row = JSON.parse($("#rowDataTiktok").val());
                row.METHOD = $("#cb_penjemputan_tiktok_0").val();
                row.JAMAWAL = $("#pickupKirimTiktok").val().split("|")[0];
                row.JAMAKHIR = $("#pickupKirimTiktok").val().split("|")[1];
                var rows = [row];
                
                loading();
                $.ajax({
                 	type    : 'POST',
                 	url     : base_url+'Tiktok/kirim/',
                 	data    : {dataAll:JSON.stringify(rows)},
                 	dataType: 'json',
                 	success : function(msg){
                 	        Swal.close();
                 	        Swal.fire({
                            		title            :  msg.msg,
                            		type             : (msg.success?'success':'error'),
                            		showConfirmButton: false,
                            		timer            : 2000
                            });
                            
                            if(msg.success)
                            {
                                 $("#modal-kirim-tiktok").modal('hide');
                                	
                              	setTimeout(() => {
                                reloadTiktok();
                              }, "2000");
                            }
                 	}
                 });
                }
        });
}

function kirimTiktokSemua() {
    loading();
    $.ajax({
        type: 'POST',
        url: base_url + 'Tiktok/cekStokLokasi/',
        dataType: 'json',
        success: function (msg) {
            if (!msg.success) {
                Swal.fire({
                    title: msg.msg,
                    icon: (msg.success ? 'success' : 'error'),
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.close();
                $("#pilihKirimanAllKurirTiktok").prop('checked',true);
                $("#modal-kirim-all-tiktok").modal('show');
                $('#tab_kirim_tiktok a:first').tab('show');
                var data = $("#dataGridTiktok1").DataTable().rows().data();
                var detailData = "";
                var dataSimpan = [];
                var dataPerKurir = [];
                for(var x = 0; x < data.length; x++)
                {
                    if(data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM")
                    {
                        dataSimpan.push(data[x]);
                    }
                }
                
                if(dataSimpan.length > 0)
                {
                    $("#keteranganKurirTiktok").show();
                    $("#countAturSemuaPengirimanTiktok").html("("+dataSimpan.length.toString()+")");
                }
                else
                {
                    $("#keteranganKurirTiktok").hide();
                    $("#countAturSemuaPengirimanTiktok").html("");
                }
                
                for(var x = 0; x < dataSimpan.length; x++)
                {
                    var ada = false;
                    for(var y = 0 ; y < dataPerKurir.length;y++)
                    {
                        if(dataSimpan[x]['KURIR'] == dataPerKurir[y]["name"])
                        {
                            ada = true;
                        }
                    }
                    
                    if(!ada)
                    {
                        dataPerKurir.push({
                            name: dataSimpan[x]['KURIR'],
                            order: [],
                            loading_done : false
                        });
                    }
                }
                
                
                $("#countAllPesananTiktok").html(dataSimpan.length.toString());
                $("#countAllKurirTiktok").html(dataPerKurir.length.toString());
                
                    //BUAT GRID PER KATEGORI
                var detailPesananRegular = "";
                var detailPesananInstant = "";
                var countRegular = 0;
                var countInstant = 0;
                var detailPesanan = "";
                for(var y = 0 ; y < dataPerKurir.length;y++)
                {  
                    var index = 0;
                    
                    for(var x = 0 ; x < dataSimpan.length;x++)
                    {
                        if(dataSimpan[x]['KURIR'] == dataPerKurir[y]["name"])
                        {
                            if(index == 0)
                            {
                                detailPesanan = `
                                <table class="table table-bordered table-striped table-hover display nowrap" width="100%" style="background:#CFECF7; border-color:#CFECF7; margin:0px; border-collapse: collapse;" >
                        			    <tr style="border-color:#CFECF7;">
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; padding-top:15px; text-align:center;" width="45px"><input type="checkbox" id="pilihKirimanAllTiktok_`+y+`" checked></td>
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; padding-top:12px; text-align:left; font-weight:bold; font-size:14pt;" width="230px" colspan='2' >`+dataPerKurir[y]["name"]+`</td>
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; text-align:center;" width="300px"  id="aturKurirAllTiktok_`+y+`" ></td>
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; text-align:center;" ><div style="width:250px; margin-top:2px;">Terdapat &nbsp;<span id="countKurirAllTiktok_`+y+`" style="font-weight:bold; font-size:14pt; ">...</span>&nbsp; Pesanan</div></td>
                        			    </tr>
                        		    </table>
                                	<table id="dataGridDetailAllTiktokKirim`+y+`" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
                        				<thead>
                        					<tr>
                        						<th style="vertical-align:middle; text-align:center;" width="70px"></th>
                        						<th style="vertical-align:middle; text-align:center;" width="150" >No. Pesanan</th>
                        						<th style="vertical-align:middle; text-align:center;" width="80px">T. Qty</th>
                        						<th style="vertical-align:middle; text-align:center;" width="300px" >Tanggal & Waktu Jemput</th>
                        						<th style="vertical-align:middle; text-align:center;" >Catatan Penjual</th>
                        					</tr>
                        				</thead>
                        				<tbody class="table-responsive-tiktok-all-kirim_`+y+`">
                        				</tbody>
                        		    </table>
                                    <br>
                                `;
                            
                                if(dataSimpan[x]['KODEPENGAMBILAN'] == "")
                                {
                                    detailPesananRegular += detailPesanan;
                                }
                                else
                                {
                                    detailPesananInstant += detailPesanan;
                                }
                            }
                            
                            dataPerKurir[y]["order"].push(dataSimpan[x]);
                            if(dataSimpan[x]['KODEPENGAMBILAN'] == "")
                            {
                                countRegular++;
                                $("#countRegular").html("("+countRegular+")");
                            }
                            else
                            {
                                countInstant++;
                                $("#countInstant").html("("+countInstant+")");
                            }
                           
                            index++;
                        }
                    }
                }
                
                $("#dataGridDetailAllRegularTiktok").html(detailPesananRegular);
                $("#dataGridDetailAllInstantTiktok").html(detailPesananInstant);
                
                $("#pilihKirimanAllKurirTiktok").change(function(){
                    for(var index = 0 ; index < dataPerKurir.length ; index++)
                    {
                        $("#pilihKirimanAllTiktok_"+index).prop("checked",$(this).prop("checked"));
                         
                        for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                        {
                            $(".table-responsive-tiktok-all-kirim_"+index+" #pilihKirimanTiktok_"+c).prop("checked",$(this).prop("checked"));
                        }
                    }
                    
                    recountPengiriman();
                });
            
               for(var i = 0 ; i < dataPerKurir.length;i++)
               {
                    var rows = dataPerKurir[i]['order'];
                    if(i == 0)
                    {
                        loading();
                    }
                    $.ajax({
                     	type    : 'POST',
                     	url     : base_url+'Tiktok/setKirim/',
                     	data    : {dataNoPackaging: JSON.stringify(rows),index:i},
                     	dataType: 'json',
                     	success : function(msg){
                     	    
                     	    dataPerKurir[msg.index].loading_done = true;
                     	    var countDone = 0;
                     	    
                     	    for(var i = 0 ; i < dataPerKurir.length ; i++)
                     	    {
                     	        if(dataPerKurir[i].loading_done)
                     	        {
                     	            countDone++;
                     	        }
                     	    }
                     	    
                     	    if(dataPerKurir.length == countDone)
                     	    {
                     	        Swal.close();
                     	    }
                     	    
                             var detailKirim = "";
                             var items = dataPerKurir[msg.index]['order'];
                             var cb_pickup = "";
                             $("#countKurirAllTiktok_"+msg.index).html(items.length.toString());
                             for(var indexKirim = 0; indexKirim < items.length;indexKirim++)
                             {
                                var dataKirim = msg;
                                       
                                $(".table-responsive-tiktok-all-kirim_"+msg.index).html('');
                                 
                                var cb_pickup = "";
                                var cb_pickup_all = `<option value="" selected>-Pilih Waktu Pickup-</option>`;
                                for(var x = 0 ; x < dataKirim.time_pickup.length ; x++)
                                {
                                    var selected = "";
                                    if(x == 0)
                                    {
                                        selected = "selected";
                                    }
                                    cb_pickup_all += ("<option value='"+dataKirim.time_pickup[x].id_pickup+"' "+selected+">"+(dataKirim.time_pickup[x].start_pickup+" - "+dataKirim.time_pickup[x].end_pickup)+"</option>");
                                    cb_pickup += ("<option value='"+dataKirim.time_pickup[x].id_pickup+"' "+selected+">"+(dataKirim.time_pickup[x].start_pickup+" - "+dataKirim.time_pickup[x].end_pickup)+"</option>");
                                }
                                
                                 var cbJemputTiktok = "";
                                 var cbJemputTiktokAll = "";
                                 if(items[0].KODEPENGAMBILAN != "")
                                 {
                                     cbJemputTiktokAll += `<option value="PICKUP" selected>Request Pickup</option>`;
                                     
                                     cbJemputTiktok += `<option value="PICKUP" selected>Request Pickup</option>`;
                                 }
                                 else
                                 {
                                     cbJemputTiktokAll += `<option value="">-Pilih Metode-</option>
                                                          <option value="DROP_OFF" selected>Drop Off</option>
                                     	                 <option value="PICKUP">Request Pickup</option>`;
                                     	                 
                                     cbJemputTiktok += `	<option value="DROP_OFF" selected>Drop Off</option>
                                               			<option value="PICKUP">Request Pickup</option>`;
                                 }
                                 
                                 detailKirim += ` <tr>
                                         	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="70px" ><input type="checkbox" id="pilihKirimanTiktok_`+indexKirim+`" width="30px" checked></td>
                                           	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px">`+items[indexKirim].KODEPESANAN+`</td>
                                           	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="80px">`+currency(items[indexKirim].TOTALBARANG)+`</td>
                                           	<td style="vertical-align:top; text-align:left;" width="300px">	
                                           	    <select id="cb_penjemputan_tiktok_`+indexKirim+`"class="form-control "  style="width:300px" panelHeight="auto" required="true">
                                               		`+cbJemputTiktok+`
                                               	</select>
                                           		<select id="cb_pickup_tiktok_`+indexKirim+`" class="form-control "  panelHeight="auto" required="true">
                                              	      `+cb_pickup+`
                                              	</select>
                                             </td>
                                     		<input type="hidden" id="jadwalKirimTiktok_`+indexKirim+`">
                                     		<input type="hidden" id="pickupKirimTiktok_`+indexKirim+`">
                                           	<td style="vertical-align:top; text-align:left; padding-top:17px;" id="editNoteTiktokDiv_`+indexKirim+`">`+items[indexKirim].CATATANJUAL+`</td>
                                         </tr>`;
                             }
                             
                             $("#aturKurirAllTiktok_"+msg.index).html(`
                                 <select id="cb_penjemputan_all_tiktok_`+msg.index+`"class="form-control "  style="width:300px" panelHeight="auto" required="true">
                                 	`+cbJemputTiktokAll+`
                                 </select>
                                <select id="cb_pickup_all_tiktok_`+msg.index+`" class="form-control "  panelHeight="auto" required="true">
                                      `+cb_pickup_all+`
                                </select>
                                <input type="hidden" id="pickupKirimTiktokAll_`+indexKirim+`">
                                
                             `);
                             
                             $(".table-responsive-tiktok-all-kirim_"+msg.index).html(detailKirim);
                             
                             $("#cb_pickup_all_tiktok_"+msg.index).hide();
                             
                             $("#pilihKirimanAllTiktok_"+msg.index).change(function(){
                                var index = msg.index; 
                                for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                {
                                    $(".table-responsive-tiktok-all-kirim_"+index+" #pilihKirimanTiktok_"+c).prop("checked",$(this).prop("checked"));
                                }
                                
                                var count = 0;
                                for(var index = 0 ; index < dataPerKurir.length ; index++)
                                {
                                    if($("#pilihKirimanAllTiktok_"+index).prop("checked"))
                                    {
                                        count++;
                                    }
                                }
                                
                                if(count == dataPerKurir.length)
                                {
                                     $("#pilihKirimanAllKurirTiktok").prop("checked",true);
                                }
                                else
                                {
                                     $("#pilihKirimanAllKurirTiktok").prop("checked",false);
                                }
                                
                                recountPengiriman();
                            });
                             
                            $(document).on('change', '[id^=cb_penjemputan_all_tiktok_]', function () {
                                var index = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                if($(this).val() == "DROP_OFF" || $(this).val() == "")
                                {
                                    $("#cb_pickup_all_tiktok_"+index).hide();
                                    $("#cb_pickup_all_tiktok_"+index).val("");
                                        
                                    $("#pickupKirimTiktokAll_"+index).val("");
                                    
                                    for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                    {
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #cb_pickup_tiktok_"+c).hide();
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #pickupKirimTiktok_"+c).val("");
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #cb_penjemputan_tiktok_"+c).val($(this).val());
                                    }
                                }
                                else if($(this).val() == "PICKUP")
                                {
                                    $("#cb_pickup_all_tiktok_"+index).show();
                                    
                                    for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                    {
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #cb_pickup_tiktok_"+c).show();
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #cb_penjemputan_tiktok_"+c).val($(this).val());
                                    }
                                }
                                 
                                 recountPengiriman();
                             })
                             
                            $("#cb_pickup_all_tiktok_"+msg.index).change(function(){
                                if($(this).val() != "")
                                {
                                    var index = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    $(".table-responsive-tiktok-all-kirim_"+index+" #pickupKirimTiktok_"+c).val($(this).val());
                                    for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                    {
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #cb_pickup_tiktok_"+c).val($(this).val());
                                    }
                                }
                                
                                recountPengiriman();
                            })
                             //CHILD
                             
                             for(var indexKirim = 0; indexKirim < items.length;indexKirim++)
                             {    
                                 
                             
                                 for(var x = 0 ; x < dataKirim.time_pickup.length ; x++)
                                 {
                                     if(x == 0)
                                     {
                                        $(".table-responsive-tiktok-all-kirim_"+msg.index+" #pickupKirimTiktok_"+indexKirim).val(dataKirim.time_pickup[x].id_pickup);
                                        $('.table-responsive-tiktok-all-kirim_'+msg.index+' #cb_pickup_tiktok_'+indexKirim).hide();
                                     }
                                 }
                                 
                                 $(".table-responsive-tiktok-all-kirim_"+i+" #editNoteTiktokDiv_"+indexKirim).find('#editNoteTiktok').click(function(){
                                   var indexKirim = this.parentNode.id.split("_")[this.parentNode.id.split("_").length-1];
                                   
                                   $("#rowDataPengirimanTiktok").val(JSON.stringify(dataPerKurir[$(this).closest('tbody').attr("class").split("_")[1]]['order']));
                                   $("#fromNoteTiktok").val("KIRIMTIKTOK_"+indexKirim+"_"+$(this).closest('tbody').attr("class").split("_")[1]);
                                     catatanPenjualTiktok();
                                 });
                                 
                                 $(".table-responsive-tiktok-all-kirim_"+i+" #jadwalKirimTiktok_"+indexKirim).val(JSON.stringify(msg));
                                 
                                 
                                $(document).on('change', '[id^=cb_penjemputan_tiktok_]', function () {
                                        // ambil i dari parent class
                                     let parentClass = $(this).closest('[class^=table-responsive-tiktok-all-kirim_]')
                                         .attr('class')
                                         .match(/table-responsive-tiktok-all-kirim_(\d+)/)[1];
                                    
                                     let i = parentClass;
                                     var indexKirim = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    
                                     if($(this).val() == "DROP_OFF")
                                     {
                                         $(".table-responsive-tiktok-all-kirim_"+i+" #cb_pickup_tiktok_"+indexKirim).hide();
                                         $(".table-responsive-tiktok-all-kirim_"+i+" #pickupKirimTiktok_"+indexKirim).val("");
                                         $(".table-responsive-tiktok-all-kirim_"+i+" #cb_penjemputan_tiktok_"+indexKirim).val($(this).val());
                                     }
                                     else if($(this).val() == "PICKUP")
                                     {
                                         $(".table-responsive-tiktok-all-kirim_"+i+" #cb_pickup_tiktok_"+indexKirim).show();
                                         $(".table-responsive-tiktok-all-kirim_"+i+" #cb_penjemputan_tiktok_"+indexKirim).val($(this).val());
                                     }
                                     
                                     if(dataPerKurir[i]['order'][0].KODEPENGAMBILAN == "")
                                     {
                                         $("#cb_penjemputan_all_tiktok_"+i).val("");
                                     }
                                     
                                     
                                     recountPengiriman();
                                 })
                                 
                                  $(".table-responsive-tiktok-all-kirim_"+msg.index+" #cb_pickup_tiktok_"+indexKirim).change(function(){
                                    var indexKirim = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];

                                    $(".table-responsive-tiktok-all-kirim_"+msg.index+" #pickupKirimTiktok_"+indexKirim).val($(this).val());
                                        
                                    if(dataPerKurir[msg.index]['order'][0].KODEPENGAMBILAN == "")
                                    {
                                        $("#cb_penjemputan_all_tiktok_"+msg.index).val("");
                                        $("#cb_pickup_all_tiktok_"+msg.index).hide();
                                    }
                                    
                                    $("#cb_pickup_all_tiktok_"+msg.index).val("");
                                    
                                    
                                    recountPengiriman();
                                })
                                
                                 $(".table-responsive-tiktok-all-kirim_"+msg.index+" #pilihKirimanTiktok_"+indexKirim).change(function(){
                                    var index = msg.index; 
                                    var count = 0;
                                    for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                    {
                                        if($(".table-responsive-tiktok-all-kirim_"+index+" #pilihKirimanTiktok_"+c).prop("checked"))
                                        {
                                            count++;
                                        }
                                    }
                                    
                                    if(count == dataPerKurir[index]['order'].length)
                                    {
                                         $("#pilihKirimanAllTiktok_"+index).prop("checked",true);
                                    }
                                    else
                                    {
                                         $("#pilihKirimanAllTiktok_"+index).prop("checked",false);
                                    }
                                    
                                    var count = 0;
                                    for(var index = 0 ; index < dataPerKurir.length ; index++)
                                    {
                                        if($("#pilihKirimanAllTiktok_"+index).prop("checked"))
                                        {
                                            count++;
                                        }
                                    }
                                    
                                    if(count == dataPerKurir.length)
                                    {
                                         $("#pilihKirimanAllKurirTiktok").prop("checked",true);
                                    }
                                    else
                                    {
                                         $("#pilihKirimanAllKurirTiktok").prop("checked",false);
                                    }
                                    
                                    recountPengiriman();
                                });
                             }
                    }});
               }
            }
        }
    });
}
function recountPengiriman() {
    var data = $("#dataGridTiktok1").DataTable().rows().data();
    var dataSimpan = [];
    var dataPerKurir = [];
    
    for(var x = 0; x < data.length; x++)
    {
        if(data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM")
        {
            dataSimpan.push(data[x]);
        }
    }
    
    for(var x = 0; x < dataSimpan.length; x++)
    {
        var ada = false;
        for(var y = 0 ; y < dataPerKurir.length;y++)
        {
            if(dataSimpan[x]['KURIR'] == dataPerKurir[y])
            {
                ada = true;
            }
        }
        
        if(!ada)
        {
            dataPerKurir.push(dataSimpan[x]['KURIR']);
        }
    }
    var countSemuaPengiriman = 0;
    
    for(var y = 0 ; y < dataPerKurir.length;y++)
    {  
        var index = 0;
        
        for(var x = 0 ; x < dataSimpan.length;x++)
        {
            if(dataSimpan[x]['KURIR'] == dataPerKurir[y])
            {
                if($(".table-responsive-tiktok-all-kirim_"+y+" #pilihKirimanTiktok_"+index).prop('checked'))
                {
                    countSemuaPengiriman++;
                }
            index++;
            }
        }
    }
    if(countSemuaPengiriman > 0)
    {
        $("#countAturSemuaPengirimanTiktok").html("("+countSemuaPengiriman.toString()+")");
    }
    else
    {
        $("#countAturSemuaPengirimanTiktok").html("");
    }
}

function kirimKonfirmAllTiktok(){
    Swal.fire({
        title: 'Anda Yakin Mengirim Semua Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var data = $("#dataGridTiktok1").DataTable().rows().data();
                var rows = [];
                for(var x = 0; x < data.length; x++)
                {
                    if(data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM")
                    {
                        rows.push(data[x]);
                    }
                }
                
                var data = $("#dataGridTiktok1").DataTable().rows().data();
                var detailData = "";
                var dataSimpan = [];
                var dataPerKurir = [];
                var rows = [];
                for(var x = 0; x < data.length; x++)
                {
                    if(data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM")
                    {
                        dataSimpan.push(data[x]);
                    }
                }
                
                for(var x = 0; x < dataSimpan.length; x++)
                {
                    var ada = false;
                    for(var y = 0 ; y < dataPerKurir.length;y++)
                    {
                        if(dataSimpan[x]['KURIR'] == dataPerKurir[y])
                        {
                            ada = true;
                        }
                    }
                    
                    if(!ada)
                    {
                        dataPerKurir.push(dataSimpan[x]['KURIR']);
                    }
                }
                
                for(var y = 0 ; y < dataPerKurir.length;y++)
                {  
                    var index = 0;
                    
                    for(var x = 0 ; x < dataSimpan.length;x++)
                    {
                        if(dataSimpan[x]['KURIR'] == dataPerKurir[y])
                        {
                            if($(".table-responsive-tiktok-all-kirim_"+y+" #pilihKirimanTiktok_"+index).prop('checked'))
                            {
                                dataSimpan[x].METHOD = $(".table-responsive-tiktok-all-kirim_"+y+" #cb_penjemputan_tiktok_"+index).val();
                                dataSimpan[x].JAMAWAL = $(".table-responsive-tiktok-all-kirim_"+y+" #pickupKirimTiktok_"+index).val().split("|")[0];
                                dataSimpan[x].JAMAKHIR = $(".table-responsive-tiktok-all-kirim_"+y+" #pickupKirimTiktok_"+index).val().split("|")[1];
                
                                rows.push(dataSimpan[x]);
                            }
                            index++;
                        }
                    }
                }
                
                loading();
                $.ajax({
                 	type    : 'POST',
                 	url     : base_url+'Tiktok/kirim/',
                 	data    : {dataAll:JSON.stringify(rows)},
                 	dataType: 'json',
                 	success : function(msg){
                 	        Swal.close();
                 	        Swal.fire({
                            		title            :  msg.msg,
                            		type             : (msg.success?'success':'error'),
                            		showConfirmButton: false,
                            		timer            : 2000
                            });
                            
                            if(msg.success)
                            {
                                $("#modal-kirim-all-tiktok").modal('hide');
                                	
                              	setTimeout(() => {
                                reloadTiktok();
                              }, "2000");
                            }
                 	}
                 });
        	}
    });
}

function lacakTiktok(){
    $("#modal-form-tiktok").modal('hide');
    var row = JSON.parse($("#rowDataTiktok").val());
    
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Tiktok/setLacak/',
    	data    : {kode: row.KODEPESANAN},
    	dataType: 'json',
    	success : function(msg){
    	        Swal.close();
    	      
            
                $("#modal-lacak-tiktok").modal('show');
                
                $("#NOTIKTOKLACAK").html("#"+row.KODEPESANAN);
                $("#KURIRLACAKTIKTOK").html(row.KURIR);
                $("#METODEBAYARLACAKTIKTOK").html(row.METODEBAYAR);
                $("#RESILACAKTIKTOK").html(row.RESI);
                $("#ALAMATLACAKTIKTOK").html(row.BUYERALAMAT);
                $("#TGLKIRIMLACAKTIKTOK").html("-"); 
                
                var stepTracker = "";
                for(var x = 0 ; x < msg.length;x++)
                {
                    if(x==0)
                    {
                        stepTracker += `<div class="step active"><div class="circle">&nbsp</div><div class="label-step" style="font-weight:bold;">`+msg[x]['description']+`<br><span style="color:#949494; font-style:italic;">`+msg[x]['update_time']+`</span></div></div>`;
                    }
                    else
                    {
                        stepTracker += `<div class="step"><div class="circle">&nbsp</div><div class="label-step">`+msg[x]['description']+`<br><span style="color:#949494; font-style:italic;">`+msg[x]['update_time']+`</span></div></div>`;
                    }
                    
                    if(msg.length > 1)
                    {
                        if(x == msg.length-2)
                        {
                        //PASTI YANG INDEX TERAKHIR
                        $("#TGLKIRIMLACAKTIKTOK").html(msg[x]['update_time']); 
                        }
                    }
                }
                
                
                $(".step-tracker").html(stepTracker);
            
    	}
    });
}

function hapusTiktok(){
    $("#modal-form-tiktok").modal('hide');
    var row = JSON.parse($("#rowDataTiktok").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Tiktok/loadDetail/',
    	data    : {kode: row.KODEPESANAN,metodebayar : row.METODEBAYAR},
    	dataType: 'json',
    	success : function(msg){
    	    $('#cb_alasan_pembatalan_tiktok').val('');
            $("#NOTIKTOKBATAL").html("#"+row.KODEPESANAN);
            $(".table-responsive-tiktok-batal").html('');
            
            var totalCurr = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
              var namaBarang = msg.DETAILBARANG[x].NAMA;
              if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
              {
                  namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
              }
          
                $(".table-responsive-tiktok-batal").append(setDetail(msg.DETAILBARANG,x,namaBarang,false));
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
            }
            $("#TOTALQTYTIKTOKBATAL").html(currency(totalCurr));
            $("#SUBTOTALTIKTOKBATAL").html(currency(msg.SUBTOTALBELI));
            $("#itemBatalTiktok").val(JSON.stringify(msg.DETAILBARANG));
             $.ajax({
            	type    : 'POST',
            	url     : base_url+'Tiktok/getAlasanPembatalan/',
            	data    : {status: row.STATUS},
            	dataType: 'json',
            	success : function(msg){
                    Swal.close();
                    var option = '<option value="">- Pilih Alasan -</option>';
          			for(var x = 0 ; x < msg.data.length ; x++)
          			{
          			    option += '<option value="'+msg.data[x].reason_id+'">'+msg.data[x].reason_name+'</option>';
          			}
      			
                    $("#cb_alasan_pembatalan_tiktok").html(option);
                    $("#itemBatalTiktok").val(JSON.stringify(msg.DETAILBARANG));
                    $("#modal-alasan-tiktok").modal('show');
                }
                 
             });
    	}
    });
}

function hapusKonfirmTiktok(){
    Swal.fire({
        title: 'Anda Yakin Membatalkan Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                if($('#cb_alasan_pembatalan_tiktok').val() == "")
                {
                    Swal.fire({
                		title            :  "Alasan Pembatalan Belum Dipilih",
                		type             : 'warning',
                		showConfirmButton: false,
                		timer            : 2000
                	});
                }
                else
                {
                    $("#modal-alasan-tiktok").modal('hide');
                    loading()
                     $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Tiktok/hapus/',
                    	data    : {kode: $("#NOTIKTOKBATAL").text().split("#")[1], alasan:$('#cb_alasan_pembatalan_tiktok').val()},
                    	dataType: 'json',
                    	success : function(msg){
                    	       Swal.close();
                    		    Swal.fire({
                            		title            :  msg.msg,
                            		type             : (msg.success?'success':'error'),
                            		showConfirmButton: false,
                            		timer            : 2000
                            	});
                            	
                            	setTimeout(() => {
                                 reloadTiktok();
                               }, "2000");
                    	}
                    });
                
                }
        	}
        });
}

function sinkronTiktokNow(){
    Swal.fire({
        title: 'Anda Yakin Melakukan Sinkronisasi ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                loading();
                $.ajax({
                	type    : 'POST',
                	url     : base_url+'Tiktok/cekStokLokasi/',
                	dataType: 'json',
                	success : function(msg){
                        if(!msg.success)
                        {
                            Swal.fire({
                            		title            :  msg.msg,
                            		type             : (msg.success?'success':'error'),
                            		showConfirmButton: false,
                            		timer            : 2000
                            });
                        }
                        else
                        {
                            totalPesananTiktokAll = 0;
                            sinkronTiktokState = true;
                            var dateNow = "<?=date('Y-m-d')?>";
                             $.ajax({
                            	type    : 'GET',
                            	url     : base_url+'Tiktok/init/'+dateNow+'/'+dateNow+'/update',
                            	dataType: 'json',
                            	success : function(msg){
                            	    totalPesananTiktokAll = msg.total;
                            	    
                            	    var indexTab = 0;
                                    var tabs = document.querySelectorAll('#tab_transaksi_tiktok li');

                                    tabs.forEach(function(tab, index) {
                                        if (tab.classList.contains('active')) {
                                            indexTab = (index+1);
                                        }
                                    });
                                    
                                    for(var x = 1; x <= 4 ; x++)
                                    {
                                        if(x != indexTab)
                                        {
                                            doneSinkronTiktok[x] = false;
                                            changeTabTiktok(x);
                                        }
                                    }
                                    
                                    doneSinkronTiktok[indexTab] = false;
                                    changeTabTiktok(indexTab);

                            }});
                        }
                	}
                });
        	}
        });
}

function sinkronTiktok(){
    Swal.fire({
        title: 'Anda Yakin Melakukan Sinkronisasi ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                loading();
                $.ajax({
                	type    : 'POST',
                	url     : base_url+'Tiktok/cekStokLokasi/',
                	dataType: 'json',
                	success : function(msg){
                        if(!msg.success)
                        {
                            Swal.fire({
                            		title            :  msg.msg,
                            		type             : (msg.success?'success':'error'),
                            		showConfirmButton: false,
                            		timer            : 2000
                            });
                        }
                        else
                        {
                            totalPesananTiktokAll = 0;
                            sinkronTiktokState = true;
                            const date = new Date();
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                            const day = String(date.getDate()).padStart(2, '0');
                            
                            const formattedDate = `${year}-${month}-${day}`;
                            
                             $.ajax({
                            	type    : 'GET',
                            	url     : base_url+'Tiktok/init/'+ "<?=TGLAWALFILTERMARKETPLACE?>"+"/"+formattedDate,
                            	dataType: 'json',
                            	success : function(msg){
                            	    totalPesananTiktokAll = msg.total;
                            	    
                                    var indexTab = 0;
                                    var tabs = document.querySelectorAll('#tab_transaksi_tiktok li');

                                    tabs.forEach(function(tab, index) {
                                        if (tab.classList.contains('active')) {
                                            indexTab = (index+1);
                                        }
                                    });
                                    
                                    for(var x = 1; x <= 4 ; x++)
                                    {
                                        if(x != indexTab)
                                        {
                                            doneSinkronTiktok[x] = false;
                                            changeTabTiktok(x);
                                        }
                                    }
                                    
                                    doneSinkronTiktok[indexTab] = false;
                                    changeTabTiktok(indexTab);

                            }});
                        }
                	}
                });
        	}
        });
}

function catatanPenjualTiktok(){
    var row;
    if($("#fromNoteTiktok").val().split("_")[0] == "GRID")
    {
        row = JSON.parse($("#rowDataTiktok").val());
    }
    else
    {
        var rows = JSON.parse($("#rowDataPengirimanTiktok").val());
        row = rows[$("#fromNoteTiktok").val().split("_")[1]];
    }

    
    $("#NOTIKTOKCATATAN").html("#"+row.KODEPESANAN);
    $("#note_tiktok").val(row.CATATANJUALRAW);
    $("#modal-note-tiktok").modal("show");
}

function noteKonfirmTiktok(){
    Swal.fire({
        title: 'Anda Yakin Menyimpan Catatan Penjualan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                loading();
                 $.ajax({
                 	type    : 'POST',
                 	url     : base_url+'Tiktok/catatanPenjual/',
                 	data    : {kode: $("#NOTIKTOKCATATAN").text().split("#")[1], note: $("#note_tiktok").val()},
                 	dataType: 'json',
                 	success : function(msg){
                 	        
                            Swal.close();
                            
                     		Swal.fire({
                            	title            :  msg.msg,
                            	type             : (msg.success?'success':'error'),
                            	showConfirmButton: false,
                            	timer            : 2000
                            });
                             	
                 	        if(msg.success)
                            {
                                $("#modal-note-tiktok").modal('hide');
                                if($("#fromNoteTiktok").val().split("_")[0] == "KIRIMTIKTOK")
                                {
                                    var indexKirim = $("#fromNoteTiktok").val().split("_")[1];
                                    
                                    var rows = JSON.parse($("#rowDataPengirimanTiktok").val());
                                    rows[indexKirim]['CATATANJUAL'] = `<i class='fa fa-edit' id='editNoteTiktok' style='cursor:pointer;'></i>
                                          <div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                        white-space: -moz-pre-wrap; /* Firefox */    
                                                        white-space: -pre-wrap;     /* Opera <7 */   
                                                        white-space: -o-pre-wrap;   /* Opera 7 */    
                                                        word-wrap: break-word;      /* IE */'>`+$("#note_tiktok").val()+`</div>`;
                                    rows[indexKirim]['CATATANJUALRAW'] = $("#note_tiktok").val();
                                    
                                    
                                    if($("#fromNoteTiktok").val().split("_").length == 3)
                                    {
                                        var index = $("#fromNoteTiktok").val().split("_")[2];
                                        
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #editNoteTiktokDiv_"+indexKirim).html(rows[indexKirim]['CATATANJUAL']);
                                        
                                        $("#rowDataPengirimanTiktok").val(JSON.stringify(rows));
                                        $(".table-responsive-tiktok-all-kirim_"+index+" #editNoteTiktokDiv_"+indexKirim).find('#editNoteTiktok').click(function(){
                                           $("#fromNoteTiktok").val("KIRIMTIKTOK_"+indexKirim+"_"+index);
                                           catatanPenjualTiktok();
                                        });
                                    }
                                    else
                                    {
                                        $('#editNoteTiktokDiv'+indexKirim).html(rows[indexKirim]['CATATANJUAL']);
                                        
                                        $("#rowDataPengirimanTiktok").val(JSON.stringify(rows));
                                        $('#editNoteTiktokDiv'+indexKirim).find('#editNoteTiktok').click(function(){
                                           $("#fromNoteTiktok").val("KIRIMTIKTOK_"+indexKirim);
                                           catatanPenjualTiktok();
                                        });
                                    }
                                }
                             	
                             	setTimeout(() => {
                                  reloadTiktok();
                                }, "2000");
                            }
                 	}
                 });
        	}
        });
}

function returBarangTiktok(){
    $("#modal-form-Tiktok").modal("hide");
    var row = JSON.parse($("#rowDataTiktok").val());
    loading();
    Swal.fire({
        title: 'Anda Yakin Merubah Pengembalian Dana menjadi Barang ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
            Swal.close();
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                $.ajax({
                	type    : 'POST',
                	url     : base_url+'Tiktok/setReturBarang/',
                	data    : {kodepengembalian: row.KODEPENGEMBALIAN,kodepesanan: row.KODEPESANAN},
                	dataType: 'json',
                	success : function(msg){
                        Swal.fire({
                        		title            :  msg.msg,
                        		type             : (msg.success?'success':'error'),
                        		showConfirmButton: false,
                        		timer            : 2000
                        });
                        
                        if(msg.success)
                        {
                            setTimeout(() => {
                                reloadTiktok();
                            }, "2000");
                        }
                	}
                });
        	}
    });
}

function returTiktok(){
    $("#modal-pengembalian-tiktok").modal("hide");
    var row = JSON.parse($("#rowDataTiktok").val());
    var rowDetail = JSON.parse($("#dataReturTiktok").val());
    loading();
    
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Tiktok/cekStokLokasi/',
    	dataType: 'json',
    	success : function(msg){
            if(!msg.success)
            {
                Swal.fire({
                		title            :  msg.msg,
                		type             : (msg.success?'success':'error'),
                		showConfirmButton: false,
                		timer            : 2000
                });
            }
            else
            {
                $("#tab_retur_header_tiktok_0").attr("class","active");
                $("#tab_retur_detail_tiktok_0").attr("class","tab-pane active");
                
                $("#tab_retur_header_tiktok_1").attr("class","");
                $("#tab_retur_detail_tiktok_1").attr("class","tab-pane");
                
                $("#tab_retur_header_tiktok_2").attr("class","");
                $("#tab_retur_detail_tiktok_2").attr("class","tab-pane");
                
                $("#deskripsi_sengketa_tiktok").val("");
                $("#returTiktokWaitResponse").hide();
                $("#returNegotiationTiktok").css("width","100%");
                $("#btn_max_kembali_tiktok").show();
                // $("#DANADIKEMBALIKANTIKTOK_1").removeAttr("readonly");
                $("#returNegotiationTiktok").show();
                $("#HEADERRETURTIKTOK").show();
                $("#DETAILRETURTIKTOK_1").html('Dengan ini menyatakan bahwa : <br>Penjual ingin melakukan <b>Pengembalian Barang dan Dana</b> kepada pembeli, dengan catatan :<ol><li>Item harus dikirim oleh pelanggan dan diverifikasi kualitasnya sebelum menyetujui pengembalian dana.</li><li>Dengan memilih "Pengembalian Barang dan Dana", Pembeli meminta pelanggan untuk mengirimkan kembali barang yang sudah diterima. Setelah pembeli menerima barang yang dikembalikan pelanggan, harap konfirmasikan pengiriman barang yang dikembalikan dan selesaikan pemeriksaan kualitas dalam batas waktu (SLA).</li><li>Penjual dapat memilih untuk mengembalikan dana sepenuhnya atau sebagian berdasarkan kesepakatan penjual dengan pembeli atau menolak pengembalian dan mengajukan banding. Agen CS Tiktok akan menghubungi penjual, jika membutuhkan bantuan untuk memproses banding.</li></ol>');
                $("#NOTIKTOKRETUR").html("#"+row.KODEPENGEMBALIAN);
                $("#HEADERRETURTIKTOK").html('Pembeli akan mengirimkan barang paling lambat pada <span style="font-weight:bold;">'+row.MINTGLPENGEMBALIAN+'</span>. Anda dapat mengajukan banding setelah menerima barang dari Pembeli atau menawarkan pengembalian Dana sebagian kepada Pembeli.<br><br>');
                $("#modal-retur-tiktok").modal("show");
                
                for(var x = 0 ; x < 3 ; x++)
                {
                    $("#DANADIKEMBALIKANTIKTOK_"+x).number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                    $("#MAXDANADIKEMBALIKANTIKTOK_"+x).number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                    
                    $("#DANADIKEMBALIKANTIKTOK_"+x).val(rowDetail.TOTALREFUND);
                    $("#MAXDANADIKEMBALIKANTIKTOK_"+x).val(rowDetail.TOTALREFUND);
                }
                
                
                if(rowDetail.REFUNDTYPE == 'ONLY_REFUND' || row.TIPEPENGEMBALIAN == 'RETURN_DELIVERED' )
                {
                    $("#tab_retur_header_tiktok_1").hide();
                    $("#tab_retur_detail_tiktok_1").hide();
                }
                
                $("#ALASANBANDING").hide();
                var select = '<option value="-">-Pilih Alasan-</option>';
                $("#DISPUTESEBELUMBARANGDATANG").show();
                $("#DISPUTESESUDAHBARANGDATANG").hide();
                
                if(rowDetail.REFUNDTYPE == 'RETURN' &&  row.TIPEPENGEMBALIAN == 'RETURN_DELIVERED' ){
                    $("#DISPUTESEBELUMBARANGDATANG").hide();
                    $("#DISPUTESESUDAHBARANGDATANG").show();
                    // $("#ALASANBANDING").show();
                    // $.ajax({
                    // 	type    : 'POST',
                    // 	url     : base_url+'Tiktok/getDispute/',
                    // 	data    : {kodepengembalian: row.KODEPENGEMBALIAN},
                    // 	dataType: 'json',
                    // 	success : function(msg){
                    // 	    var dataDispute = msg;
                    // 	    for(var x = 0 ; x < dataDispute.length;x++)
                    // 	    {
                    // 	        select +=  '<option value="'+dataDispute[x].reason_id+'">'+dataDispute[x].muti_language_text+'</option>';
                    // 	    }
                    // 	    $("#cb_alasan_sengketa_tiktok").html(select);
                    // 	}
                    // });
                }
                
                $("#penjelasan_bukti_tiktok").html("Kamu dapat menambahkan 8 Foto, ukuran file tidak bisa lebih dari 10MB.");
                
                var htmlProof = "<table><tr>";
                for(var y = 0 ; y < 8 ;y++)
                {
                    if(y % 4 == 0)
                    {
                        htmlProof += "</tr><tr>";
                    }
                    
                    htmlProof += `<td>
                                        <input type="file" id="file-input-tiktok-`+y+`" accept="image/*,video/*" style="display:none;" value="">
                                        <input type="hidden"  id="keterangan-input-tiktok-`+y+`" value="">
                                        <input type="hidden"  id="format-input-tiktok-`+y+`" value="">
                                        <input type="hidden"  id="index-input-tiktok-`+y+`" value="`+y+`">
                                        <input type="hidden"  id="src-input-tiktok-`+y+`" value="">
                                        <input type="hidden"  id="id-input-tiktok-`+y+`" value="">
                                        <div style="margin-bottom:20px; margin-right:10px;">
                                            <img id="preview-image-tiktok-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; height:100px; cursor:pointer; border:2px solid #dddddd;'>
                                            <br>
                                            <div style="text-align:center;">
                                                <span id="ubahProofTiktok-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                                &nbsp;
                                                <span id="hapusProofTiktok-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                            </div>
                                        </div>
                                    </td>`;  
                
                }
                htmlProof += "</table>";
                $("#proof_sengketa_tiktok").html(htmlProof);
            
                for(var y = 0 ; y < 8 ; y++)
                {
                        const fileInput = document.getElementById('file-input-tiktok-'+y);
                        const previewImage = document.getElementById('preview-image-tiktok-'+y);
                        const title = document.getElementById('keterangan-input-tiktok-'+y);
                        const format = document.getElementById('format-input-tiktok-'+y);
                        const index = document.getElementById('index-input-tiktok-'+y);
                        const url =  document.getElementById('src-input-tiktok-'+y);
                        const id =  document.getElementById('id-input-tiktok-'+y);
                        
                        const ubahImage = document.getElementById('ubahProofTiktok-'+y);
                        const hapusImage = document.getElementById('hapusProofTiktok-'+y);
                        
                        previewImage.addEventListener('click', () => {
                          if(url.value != '')
                          {
                              lihatLebihJelasTiktok(format.value,title.value,url.value);
                          }
                          else
                          {
                            fileInput.click();
                          }
                        });
                        
                        ubahImage.addEventListener('click', () => {
                          fileInput.click();
                        });
                        
                        hapusImage.addEventListener('click', () => {
                          fileInput.value = '';
                          format.value = '';
                          previewImage.src = base_url+"/assets/images/addphoto.webp";
                          url.value = "";
                          id.value = "";
                          
                          ubahImage.style.display = 'none';
                          hapusImage.style.display = 'none';
                        });
                        
                        fileInput.addEventListener('change', () => {
                          const file = fileInput.files[0];
                          if (!file) return;
        
                          // Jika file adalah gambar
                          if (file.type.startsWith('image/')) {
                            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        
                            if (!allowedTypes.includes(file.type.toLowerCase())) {
                                fileInput.value = '';
                              Swal.fire({
                                title: 'Format gambar tidak didukung (hanya jpg/jpeg/png)',
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 2000
                              });
                              return;
                            }
                        
                            const maxSizeMB = 10;
                            if (file.size > maxSizeMB * 1024 * 1024) {
                                fileInput.value = '';
                              Swal.fire({
                                title: 'Ukuran gambar melebihi 10 MB',
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 2000
                              });
                              return;
                            }
                        
                            var row = JSON.parse($("#rowDataTiktok").val());
                            // Upload file asli ke server
                            const formData = new FormData();
                            formData.append('index', index.value);
                            formData.append('kode', row.KODEPENGEMBALIAN);
                            formData.append('file', file);
                            formData.append('tipe', 'GAMBAR');
                            formData.append('size', file.size);
                            formData.append("reason","proof/TIKTOK");
                        
                            loading();
                            
                            $.ajax({
                              type: 'POST',
                              url: base_url + 'Tiktok/uploadLocalUrlProof/',
                              data: formData,
                              contentType: false,
                              processData: false,
                              dataType: 'json',
                              success: function (msg) {
                                Swal.close();
                                if (msg.success) {
                                 format.value = "GAMBAR";
                                 previewImage.src = msg.url;
                                 url.value =  msg.url;
                                 id.value = msg.id;
                        
                                 ubahImage.style.display = '';
                                 hapusImage.style.display = '';
                                }
                                else
                                {
                                    fileInput.value = '';
                                }
                              },
                              error: function (xhr, status, error) {
                                fileInput.value = '';
                                Swal.fire({
                                  title: 'Upload gagal!',
                                  text: error,
                                  icon: 'error'
                                });
                              }
                            });
                          }
                          // Jika file adalah video
                          else if (file.type.startsWith('video/')) {
                            format.value = "VIDEO";
                            const video = document.createElement("video");
                            video.preload = "metadata";
                        
                            video.onloadedmetadata = function () {
                              window.URL.revokeObjectURL(video.src);
                        
                              if (parseInt(video.duration) > 60) {
                                Swal.fire({
                                	title            : 'Durasi Min 1 Menit',
                                	type             : 'warning',
                                	showConfirmButton: false,
                                	timer            : 2000
                                });
                                fileInput.value = ""; // Kosongkan input
                                format.value = "";
                                return;
                              }
                              
                               const maxSizeMB = 10;
                               if (file.size > maxSizeMB * 1024 * 1024) {
                                   fileInput.value = '';
                                 Swal.fire({
                                   title: 'Ukuran video melebihi 10MB',
                                   icon: 'warning',
                                   showConfirmButton: false,
                                   timer: 2000
                                 });
                                 return;
                               }
                              
                               var row = JSON.parse($("#rowDataTiktok").val());
                                // Upload file asli ke server
                                const formData = new FormData();
                                formData.append('index', index.value);
                                formData.append('kode', row.KODEPENGEMBALIAN);
                                formData.append('file', file);
                                formData.append('tipe', 'VIDEO');
                                formData.append('size', file.size);
                                formData.append("reason","proof/TIKTOK");
                            
                                loading();
                                
                                $.ajax({
                                  type: 'POST',
                                  url: base_url + 'Tiktok/uploadLocalUrlProof/',
                                  data: formData,
                                  contentType: false,
                                  processData: false,
                                  dataType: 'json',
                                  success: function (msg) {
                                    Swal.close();
                                    if (msg.success) {
                                     format.value = "VIDEO";
                                     previewImage.src =  base_url+"/assets/images/video.webp";
                                     url.value =  msg.url;
                            
                                     ubahImage.style.display = '';
                                     hapusImage.style.display = '';
                                    }
                                    else
                                    {
                                        fileInput.value = '';
                                    }
                                  },
                                  error: function (xhr, status, error) {
                                    fileInput.value = '';
                                    Swal.fire({
                                      title: 'Upload gagal!',
                                      text: error,
                                      icon: 'error'
                                    });
                                  }
                                });
                            };
                        
                            video.onerror = () => {
                               Swal.fire({
                                	title            : 'Gagal memuat video dari file',
                                	type             : 'warning',
                                	showConfirmButton: false,
                                	timer            : 2000
                                });
                              fileInput.value = "";
                              format.value = "";
                            };
                        
                            video.src = URL.createObjectURL(file);
                          }
                        
                          // Tipe file tidak valid
                          else {
                             Swal.fire({
                                	title            : 'Hanya mendukung file Gambar dan Video',
                                	type             : 'warning',
                                	showConfirmButton: false,
                                	timer            : 2000
                            });
                          }
                        });
                    }
                
                Swal.close();
            }
    	}
    });
}

function setMaksRefundTiktok(){
  $("#DANADIKEMBALIKANTIKTOK_1").val($("#MAXDANADIKEMBALIKANTIKTOK_1").val());
}

function refundTiktok(x){
        
   var gambarada = false;
   for(var y = 0 ; y < 8;y++)
   {
       //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
       if($("#src-input-tiktok-"+y).val() != "")
       {
          gambarada = true;
       }
   
   }
        
    if(x == 2 && ($("#deskripsi_sengketa_tiktok").val() == "" || !gambarada || $("#cb_alasan_sengketa_tiktok").val() == "-"))
    {
        if($("#cb_alasan_sengketa_tiktok").val() == "-")
        {
             Swal.fire({
                 	title            : 'Alasan Banding harus dipilih',
                 	type             : 'warning',
                 	showConfirmButton: false,
                 	timer            : 2000
             });
        }
        else if($("#deskripsi_sengketa_tiktok").val() == "")
        {
             Swal.fire({
                 	title            : 'Penjelasan Banding wajib diisi',
                 	type             : 'warning',
                 	showConfirmButton: false,
                 	timer            : 2000
             });
        }
        else if(!gambarada)
        {
             Swal.fire({
                 	title            : 'Gambar wajib diisi min 1',
                 	type             : 'warning',
                 	showConfirmButton: false,
                 	timer            : 2000
             });
        }
    }
    else
    {
    Swal.fire({
        title: 'Anda Yakin Melanjutkan Pengembalian Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                setRefundTiktok(x);
        	}
        });
    }
}

function setRefundTiktok(x)
{
    var row = JSON.parse($("#rowDataTiktok").val());
    var rowDetail = JSON.parse($("#dataReturTiktok").val());
    loading();
    if(x == 0)
    {
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Tiktok/refund/',
        	data    : {kodepengembalian: row.KODEPENGEMBALIAN,kodepesanan: row.KODEPESANAN},
        	dataType: 'json',
        	success : function(msg){
               
                Swal.close();	
                Swal.fire({
                	title            :  msg.msg,
                	type             : (msg.success?'success':'error'),
                	showConfirmButton: false,
                	timer            : 2000
                });
                if(msg.success)
                {
                    $("#modal-retur-tiktok").modal("hide");
                
                    setTimeout(() => {
                      reloadTiktok();
                    }, "2000");
                }
        	}
        });
    }
    else if(x == 1)
    {
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Tiktok/returnRefund/',
        	data    : {kodepengembalian: row.KODEPENGEMBALIAN,kodepesanan: row.KODEPESANAN},
        	dataType: 'json',
        	success : function(msg){
               
                Swal.close();	
                Swal.fire({
                	title            :  msg.msg,
                	type             : (msg.success?'success':'error'),
                	showConfirmButton: false,
                	timer            : 2000
                });
                if(msg.success)
                {
                    $("#modal-retur-tiktok").modal("hide");
                
                    setTimeout(() => {
                      reloadTiktok();
                    }, "2000");
                }
        	}
        });
    }
    if(x == -1){
        
    }
    else if(x == 2)
    {
        var dataDisputeProof = [];
        for(var y = 0 ; y < 8;y++)
        {
            //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
            if($("#src-input-tiktok-"+y).val() != "")
            {
                dataDisputeProof.push({
                    "id" : (y+1),
                    "requirement" : $("#keterangan-input-tiktok-"+y).val(),
                    "thumbnail" : $("#src-input-tiktok-"+y).val(),
                    "url" : $("#src-input-tiktok-"+y).val(),
                    "url-baru" : ""
                });
            }
        
        }
        
       $.ajax({
            type    : 'POST',
            url     : base_url+'Tiktok/changeLocalUrl/',
            data    : {
                "url" : JSON.stringify(dataDisputeProof),
            },
            dataType: 'json',
            success : function(msg){
                if (msg.success) {
                    for(var x = 0 ; x < dataDisputeProof.length ; x++)
                    {
                       dataDisputeProof[x]['url-baru'] = msg.data[x]['url-baru'];
                    }
                    
                    $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Tiktok/dispute/',
                    	data    : {kodepengembalian: row.KODEPENGEMBALIAN,kodepesanan: row.KODEPESANAN,pilihandispute:$("#cb_alasan_sengketa_tiktok").val(),alasandispute:$("#deskripsi_sengketa_tiktok").val(),disputeproof:JSON.stringify(dataDisputeProof)},
                    	dataType: 'json',
                    	success : function(msg){
                           
                            Swal.close();	
                            Swal.fire({
                            	title            :  msg.msg,
                            	type             : (msg.success?'success':'error'),
                            	showConfirmButton: false,
                            	timer            : 2000
                            });
                            if(msg.success)
                            {
                                $("#modal-retur-tiktok").modal("hide");
                            
                                setTimeout(() => {
                                  reloadTiktok();
                                }, "2000");
                            }
                    	}
                    });
                }
                else
                {
                    Swal.close();	
                    setRefundTiktok(2);
                    // error = true;
                    // Swal.close();	
                    // Swal.fire({
                    //     title            : msg.msg,
                    //     type             : (msg.success?'success':'error'),
                    //     showConfirmButton: false,
                    //     timer            : 2000
                    // });
                }
                
            }
        });
    }
}

function reloadTiktok(){
    for(var x = 1 ; x <= 4 ; x++ )
    {
        $("#dataGridTiktok"+x).DataTable().ajax.reload();
    }
}

function loading(){
    Swal.fire({
      title: '',
      html: '<img src="'+base_url+'assets/images/loading.gif" width="80">',                // no text or HTML content
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
}

function focusOnRefundTiktok(){
    setTimeout(() => {
    //  $("#DANADIKEMBALIKANTIKTOK_1").focus();
    }, "500");
}

//LIMIT ANGKA SAJA
function numberInputTrans(evt,index) {
	
	if(parseInt($("#DANADIKEMBALIKANTIKTOK_"+index).val()) < 0){
	    $("#DANADIKEMBALIKANTIKTOK_"+index).val(0);
		Swal.fire({
				title            : "Dana yang dikembalikan tidak boleh kurang dari Nol",
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
		});
	}
	else if(parseInt($("#DANADIKEMBALIKANTIKTOK_"+index).val()) > parseInt($("#MAXDANADIKEMBALIKANTIKTOK_"+index).val())){
	    $("#DANADIKEMBALIKANTIKTOK_"+index).val($("#MAXDANADIKEMBALIKANTIKTOK_"+index).val())
		Swal.fire({
				title            : "Dana yang dikembalikan tidak boleh lebih dari "+currency($("#MAXDANADIKEMBALIKANTIKTOK_"+index).val()),
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
		});
	}
	
    var charCode = (evt.which) ? evt.which : event.keyCode;
	
	if (charCode > 31 && (charCode < 48 || charCode > 57)) //CEK ANGKA DAN DIGIT MAKS 3
	{
		return false;
	}
	else
	{
		return true;
	}
}

</script>

