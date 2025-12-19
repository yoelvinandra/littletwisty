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
    
    #tab_kirim_shopee .active a {
         color:black;
         font-weight:bold;
    }
    
    #tab_kirim_shopee li a {
        color:#949494;
        font-weight:normal;
    }
    
    #tab_retur_shopee .active a {
         color:black;
         font-weight:bold;
    }
    
    #tab_retur_shopee li a {
        color:#949494;
        font-weight:normal;
    }
    
    #modal-retur-shopee .modal-dialog {
        max-width: 700px;
        margin: 30px auto;
    }
    
    #modal-retur-shopee .modal-content {
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    #modal-retur-shopee .modal-body {
        overflow-y: auto;
        flex: 1 1 auto;
    }

  </style>
  
     <div class=" ">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
              <div class="box" style="border:0px; padding:0px; margin:0px;">
                  <div class="box-header form-inline">
                    <button class="btn" style="background:#EE4D2D; color:white;" onclick="javascript:sinkronShopeeNow()">Sinkronisasi Hari Ini</button>&nbsp;
      				<button class="btn" style="background:white; color:#EE4D2D; border:1px solid #EE4D2D;" onclick="javascript:sinkronShopee()">Sinkronisasi 15 Hari Terakhir</button>
      				<div id="filter_tgl_shopee_1" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_shopee_1" style="width:100px;" name="tgl_awal_filter_shopee_1" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_shopee_1" style="width:100px;" name="tgl_akhir_filter_shopee_1" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshShopee(1)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_shopee_2" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_shopee_2" style="width:100px;" name="tgl_awal_filter_shopee_2" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_shopee_2" style="width:100px;" name="tgl_akhir_filter_shopee_2" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshShopee(2)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_shopee_3" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_shopee_3" style="width:100px;" name="tgl_awal_filter_shopee_3" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_shopee_3" style="width:100px;" name="tgl_akhir_filter_shopee_3" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshShopee(3)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_shopee_4" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_shopee_4" style="width:100px;" name="tgl_awal_filter_shopee_4" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_shopee_4" style="width:100px;" name="tgl_akhir_filter_shopee_4" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshShopee(4)">Tampilkan</button>
      				</div>
      			</div>
      		    <div class="nav-tabs-custom" >
                  <ul class="nav nav-tabs" id="tab_transaksi_shopee">
      				<li class="active" onclick="javascript:changeTabShopee(1)" ><a href="#tab_1_shopee" data-toggle="tab">Persiapan Pesanan &nbsp;<span id="totalShopee1" style=" display:none; color:white; background:red; border-radius:100px; padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabShopee(2)"><a href="#tab_2_shopee" data-toggle="tab">Proses Pengiriman &nbsp;<span id="totalShopee2" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabShopee(3)"><a href="#tab_3_shopee" data-toggle="tab">Selesai Pesanan &nbsp;<span id="totalShopee3" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabShopee(4)"><a href="#tab_4_shopee" data-toggle="tab">Pengembalian Pesanan &nbsp;<span id="totalShopee4" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li class="pull-right" style="width:250px">
      					<div class="input-group " id="filter_status_shopee_1">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_shopee_1" name="cb_trans_status_shopee_1" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="UNPAID">Belum Bayar</option>
      					  	<option value="READY_TO_SHIP">Siap Dikirim</option>
      					  	<option value="PROCESSED">Diproses</option>
      					  	<option value="IN_CANCEL">Dibatalkan Penjual</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_shopee_2">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_shopee_2" name="cb_trans_status_shopee_2" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="SHIPPED">Dalam Pengiriman</option>
      					  	<option value="TO_CONFIRM_RECEIVE">Telah Dikirim</option>
      					  	<option value="RETRY_SHIP">Dikirim Ulang</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_shopee_3">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_shopee_3" name="cb_trans_status_shopee_3" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="COMPLETED">Selesai</option>
      					  	<option value="CANCELLED">Pembatalan</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_shopee_4">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_shopee_4" name="cb_trans_status_shopee_4" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="TO_RETURN|REQUESTED">Pengembalian Diajukan</option>
      					  	<option value="TO_RETURN|PROCESSING">Pengembalian Diproses</option>
      					  	<option value="TO_RETURN|SELLER_DISPUTE-JUDGING">Pengembalian Dalam Sengketa</option>
      					  </select>
      					</div>
      				</li>
                  </ul>
                  <div class="tab-content">
                      <div class="tab-pane active" id="tab_1_shopee">
                          <div class="box-body ">
                      		  <button class="btn btn-warning" id="cetakLangsungSemua" style="margin-bottom:10px; display:none;" onclick="javascript:cetakShopeeSemua(1)" >Cetak Semua Pesanan</button>
                      		  <button class="btn btn-success" id="kirimLangsungSemua" style="margin-bottom:10px; display:none;" onclick="javascript:kirimShopeeSemua()" >Atur Semua Pengiriman</button>
                              <table id="dataGridShopee1" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
                      <div class="tab-pane" id="tab_2_shopee">
                          <div class="box-body ">
                              <table id="dataGridShopee2" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
                       <div class="tab-pane" id="tab_3_shopee">
                          <div class="box-body ">
                              <table id="dataGridShopee3" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
                       <div class="tab-pane" id="tab_4_shopee">
                          <div class="box-body ">
                              <table id="dataGridShopee4" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
<input type="hidden" id="statusShopee1">
<input type="hidden" id="statusShopee2">
<input type="hidden" id="statusShopee3">
<input type="hidden" id="statusShopee4">
<!--MODAL BATAL-->

<div class="modal fade" id="modal-form-shopee">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Detail Pesanan&nbsp;&nbsp;<b id="NOSHOPEE" style="font-size:14pt;"></b>&nbsp;&nbsp;&nbsp;-&nbsp;<i id="STATUSSHOPEE"  style="font-size:12pt;"></i></h4>
            <button onclick="ubahShopee()" id="ubahShopeeDetail" style="margin-left:15px;" class='btn btn-primary'>Ubah</button> 
            <button onclick="hapusShopee()" id="hapusShopeeDetail" style="margin-left:5px;" class='btn btn-danger'>Batal</button>
            <button onclick="cetakShopee()" id="cetakShopeeDetail" style="margin-left:5px;" class='btn btn-warning'>Cetak</button>
            <button onclick="kirimShopee()" id='kirimShopeeDetail' class='btn btn-success' style='float:right;'>Atur Pengiriman</button>
            <button onclick="lacakShopee()" id='lacakShopeeDetail' class='btn btn-success' style='float:right;'>Lacak Pesanan</button>
            <button onclick="returBarangShopee()" id='returBarangShopeeDetail' class='btn btn-danger' style='float:right;'>Retur B. Manual</button>
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-4 col-sm-4 col-xs-4" style="padding:0px;">
                   <label>Tgl Pesanan</label>
                   <div id="TGLPESANANSHOPEE">-</div>
                   <br>
                   <label>Min Tgl Kirim</label>
                   <div id="TGLKIRIMSHOPEE">-</div>
                   <br>
                   <label>Metode Bayar</label>
                   <div id="PEMBAYARANSHOPEE">-</div>
                   <br>
                   <label>Kurir / No. Resi</label>
                   <div id="KURIRSHOPEE">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                   <label>Pembeli </label>
                   <div id="NAMAPEMBELISHOPEE">-</div>
                   <br>
                   <label>Telp </label>
                   <div id="TELPPEMBELISHOPEE">-</div>
                   <br>
                   <label>Alamat </label>
                   <div id="ALAMATPEMBELISHOPEE">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4"style="padding:0px;">
                   <label>Catatan Pembeli</label>
                   <div id="CATATANPEMBELISHOPEE">-</div>
                   <br>
                   <label class="noKembaliShopee">No. Pengembalian</label>
                   <div class="noKembaliShopee" id="NOPENGEMBALIANSHOPEE">-</div>
                   <br>
                   <label class="alasanKembaliShopee">Alasan Batal / Kembali</label>
                   <div class="alasanKembaliShopee" id="ALASANPENGEMBALIANSHOPEE">-</div>
                </div>
      	    	<!--SATU TABEL-->
      	    	<div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:15px; padding:0px;" >
          	    	<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          	    		<div class="row"> 
              				<div class=" col-sm-12">
              					<table id="dataGridDetailShopee" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
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
              						<tbody class="table-responsive-shopee">
              						</tbody>
              					</table> 
              				</div>
          	    		</div>
          	    	</div> 
          	    	<div class="row" style="margin:0px;padding:0px;"> 
              				<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
              					<table id="footerShopee" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<tfoot>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
              								<th style="vertical-align:middle; text-align:center;" id="TOTALQTYSHOPEE" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:right;" id="SUBTOTALSHOPEE" width="100px"></th>
              							</tr>
              						</tfoot>
              					</table> 
              				</div>
              			</div>
      	    	</div>
      	    	
      	    <div style="padding:0px; border-radius:2px; z-index;-1;">
      	    	<div class="col-md-6 col-sm-6 col-xs-6  " style="padding:0px 15px 0px 0px;">
      	    	    <div style="font-weight:bold; margin:auto; " ><i style="font-size:14pt;">Informasi Pembeli</i></div>
      	    	    <div class="row">
          	    		<div class="col-md-12">
              	    	    <div class="col-md-9" align="right" style="font-weight:bold">Total</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="TOTALPEMBELISHOPEE">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Diskon Pesanan</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="DISKONPEMBELISHOPEE">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Biaya Pengiriman</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYAKIRIMPEMBELISHOPEE">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Biaya Lain</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYALAINPEMBELISHOPEE">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold; padding-top:15px;">Pembayaran Pembeli</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-top:15px; padding-bottom:15px; padding-right:10px; border-top:1px solid #cecece; font-weight:bold" id="PEMBAYARANPEMBELISHOPEE">	
                  	    	</div>
              	    	</div>
              	    </div>
      	    	</div>
      	    	<div class="col-md-6 col-sm-6 col-xs-6  "style="padding:0px 0px 0px 15px; border-left:1px solid #cecece;">
          	    	    <div style="font-weight:bold; margin:auto;" ><i style="font-size:14pt;">Informasi Penjual</i></div>
          	    	    <div class="row">
          	    			<div class="col-md-12">
                  	    	    <div class="col-md-9" align="right" style="font-weight:bold">Total</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="TOTALPENJUALSHOPEE">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Refund </div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="REFUNDPENJUALSHOPEE">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Diskon Penjual</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="DISKONPENJUALSHOPEE">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Biaya Pengiriman Final</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYAKIRIMPENJUALSHOPEE">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Biaya Layanan</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px; " id="BIAYALAYANANPENJUALSHOPEE">
              	    			</div>
              	    			<hr></hr>
              	    			<div class="col-md-9" align="right" style="font-weight:bold; padding-top:15px;">Total Penjualan</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-top:15px; padding-bottom:15px; padding-right:10px; border-top:1px solid #cecece;  font-weight:bold" id="GRANDTOTALPENJUALSHOPEE">
              	    		    </div>
              	    			<div class="col-md-9 penyelesaianShopee"  align="right" style="font-weight:bold;">Penyelesaian Pembayaran</div>
              	    			<div class="col-md-3 penyelesaianShopee"  style="text-align:right; padding-bottom:15px; padding-right:10px;font-weight:bold " id="PENYELESAIANPENJUALSHOPEE">		
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

<div class="modal fade" id="modal-ubah-shopee">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	     <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Ubah Pesanan&nbsp;&nbsp;<b id="NOSHOPEEUBAH" style="font-size:14pt;"></b></h4>
            <button id='btn_ubah_konfirm_shopee'  style="float:right;" class='btn btn-primary' onclick="ubahKonfirmShopee()">Ubah</button>
        </div>
		<div class="modal-body" style="height:395px;">
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailShopeeUbah" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
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
            					<tbody class="table-responsive-shopee-ubah">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
          		<div class="row" style="margin:0px;padding:0px;"> 
            			<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
            				<table id="footerShopeeUbah" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<tfoot>
            						<tr>
            						    <th width="103px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
            							<th style="vertical-align:middle; text-align:center;" id="TOTALQTYSHOPEEUBAH" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:right;" id="SUBTOTALSHOPEEUBAH" width="100px"></th>
            						</tr>
            					</tfoot>
            				</table> 
            			</div>
            		</div>
      	    </div>
            <input type="hidden" id="itemUbahShopee">
			<br>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="modal-alasan-shopee">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	     <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Pembatalan Pesanan&nbsp;&nbsp;<b id="NOSHOPEEBATAL" style="font-size:14pt;"></b></h4>
            <button id='btn_hapus_konfirm_shopee'  style="float:right;" class='btn btn-danger' onclick="hapusKonfirmShopee()">Batal</button>
        </div>
		<div class="modal-body" style="height:480px;">
		    <div>
      	    <label>Alasan Pembatalan</label>
			<select id="cb_alasan_pembatalan_shopee" name="cb_alasan_pembatalan_shopee" class="form-control "  panelHeight="auto" required="true">
      			<option value="">- Pilih Alasan -</option>
      			<option value="OUT_OF_STOCK">Stok Habis</option>
      			<!--<option value="CUSTOMER_REQUEST">Permintaan Pelanggan</option>-->
      			<!--<option value="UNDELIVERABLE_AREA">Area Tidak Terkirim</option>-->
      		</select>
      		</div>
      		<br>
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailShopeeBatal" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
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
            					<tbody class="table-responsive-shopee-batal">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
          		<div class="row" style="margin:0px;padding:0px;"> 
            			<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
            				<table id="footerShopeeBatal" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<tfoot>
            						<tr>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
            							<th style="vertical-align:middle; text-align:center;" id="TOTALQTYSHOPEEBATAL" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:right;" id="SUBTOTALSHOPEEBATAL" width="100px"></th>
            						</tr>
            					</tfoot>
            				</table> 
            			</div>
            		</div>
      	    </div>
      		<input type="hidden" id="itemBatalShopee">
			<br>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" id="modal-kirim-shopee">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Atur Pengiriman&nbsp;&nbsp;<span id="countAturPengiriman" style="font-size:14pt;"></span></h4>
                <button onclick="kirimKonfirmShopee()" id='kirim_konfirm_shopee' class='btn btn-success' style='float:right;'>Kirim</button>
        </div>
		<div class="modal-body" style="height:395px;">
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:363px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailShopeeKirim" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<thead>
            						<tr>
            							<th style="vertical-align:middle; text-align:center;" width="150px">Kurir</th>
            							<th style="vertical-align:middle; text-align:center;" width="150" >No. Pesanan</th>
            							<th style="vertical-align:middle; text-align:center;" width="80px">T. Qty</th>
            							<th style="vertical-align:middle; text-align:center;" width="332px" >Atur Lokasi, Tanggal, & Waktu Jemput</th>
            							<th style="vertical-align:middle; text-align:center;" >Catatan Penjual</th>
            						</tr>
            					</thead>
            					<tbody class="table-responsive-shopee-kirim">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
      	    </div>
		</div>
		<input type="hidden" id="jadwalKirimShopee">
		<input type="hidden" id="rowDataPengirimanShopee">
		<input type="hidden" id="pickupKirimShopee">
		<input type="hidden" id="addressKirimShopee">
	</div>
	</div>
</div>

<div class="modal fade" id="modal-kirim-all-shopee">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Atur Semua Pengiriman&nbsp;&nbsp;<span id="countAturSemuaPengirimanShopee" style="font-size:14pt;"></span></h4>
                <button onclick="kirimKonfirmAllShopee()" id='kirim_konfirm_all_shopee' class='btn btn-success' style='float:right;'>Kirim</button>
        </div>
		<div class="modal-body" style="height:655px;">
		        <div style="margin-left:25px; margin-bottom:25px;">
		            <label><input type="checkbox" id="pilihKirimanAllKurirShopee" checked> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pilih Semua Kurir</label>
		            <span style="float:right; margin-top:3px; margin-right:30px;" id="keteranganKurirShopee">Terdapat &nbsp;<span id="countAllPesananShopee" style="font-weight:bold; font-size:14pt; "></span>&nbsp; Pesanan dari &nbsp;<span id="countAllKurirShopee" style="font-weight:bold; font-size:14pt; "></span>&nbsp; Kurir</span>
		        </div>
		        <ul class="nav nav-tabs" id="tab_kirim_shopee">
      				<li class="active" ><a href="#tab_regular_shopee" data-toggle="tab">Regular <span id="countRegular"></span></a></li>
      			    <li ><a href="#tab_instant_shopee" data-toggle="tab">Instant & Sameday <span id="countInstant"></span></a></li>
                  </ul>
                  <div class="tab-content">
                      <div class="tab-pane active" id="tab_regular_shopee">
                        <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:20px; padding:0px;" >
                              <div class="box-body ">
                              		<div class="x_content" style="height:508px; overflow-y:auto; overflow-x:hidden;">
                              			<div class="row"> 
                                			<div class=" col-sm-12" id="dataGridDetailAllRegularShopee">
                                			</div>
                              			</div>
                              		</div> 
                              </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="tab_instant_shopee">
                            <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:20px; padding:0px;" >
                              <div class="box-body ">
                              		<div class="x_content" style="height:508px; overflow-y:auto; overflow-x:hidden;">
                              			<div class="row"> 
                                			<div class=" col-sm-12" id="dataGridDetailAllInstantShopee">
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

<div class="modal fade" id="modal-lacak-shopee">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Lacak Pesanan&nbsp;&nbsp;<b id="NOSHOPEELACAK" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body" style="height:425px;">
      	    <div class="row"> 
            	<div class="col-sm-5" style="padding:0px 20px 0px 20px; border-right:1px solid #cecece;">
            	   <label>Metode Bayar</label>
                   <div id="METODEBAYARLACAKSHOPEE">-</div>
                   <br>
            	   <label>Kurir</label>
                   <div id="KURIRLACAKSHOPEE">-</div>
                   <br>
                   <label>Resi</label>
                   <div id="RESILACAKSHOPEE">-</div>
                   <br>
                   <label>Tgl Kirim</label>
                   <div id="TGLKIRIMLACAKSHOPEE">-</div>
                   <br>
                   <label>Alamat</label>
                   <div id="ALAMATLACAKSHOPEE">-</div>
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

<div class="modal fade" id="modal-barang-shopee">
	<div class="modal-dialog">
	<div class="modal-content">
    	 <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Ganti Produk Asal&nbsp;&nbsp;<b id="warnaOldShopee" style="font-size:14pt;"></b><b> / </b><b id="sizeOldShopee" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body">
			<table id="table_barang_shopee" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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

<div class="modal fade" id="modal-note-shopee">
	<div class="modal-dialog ">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Catatan Pesanan&nbsp;&nbsp;<b id="NOSHOPEECATATAN" style="font-size:14pt;"></b></h4>
                    <button id='btn_note_konfirm_shopee'  style="float:right;" class='btn btn-success' onclick="noteKonfirmShopee()">Simpan</button>
            </div>
    		<div class="modal-body">
    		    <textarea id="note_shopee" maxlines="5" style="width:100%; height:200px; border:0.5px solid #cecece; padding:10px;" placeholder="Masukkan Catatan.....">
    		    </textarea>
    		    <input type="hidden" id="fromNoteShopee">
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-cetak-shopee">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Cetak Pesanan&nbsp;&nbsp;<span id="countCetak" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_shopee'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmShopee()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewCetakShopee">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-cetak-all-shopee">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Cetak Semua Pesanan&nbsp;&nbsp;<span id="countCetakSemua" style="font-size:14pt;"></span></h4>
                    <button id='btn_cetak_all_konfirm_shopee'  style="float:right;" class='btn btn-warning' onclick="cetakAllKonfirmShopee()">Cetak</button>
            </div>
    		<div class="modal-body" style="height:600px;">
          	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
              		<div class="x_content" style="height:568px; overflow-y:auto; overflow-x:hidden;">
              			<div class="row"> 
                			<div class=" col-sm-12">
                				<table id="dataGridDetailShopeeAllCetak" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
                					<thead>
                						<tr>
                						    <th style="vertical-align:middle; text-align:center;" width="30px"><input type="checkbox" id="pilihCetakAllShopee" checked width="30px"></th>
                							<th style="vertical-align:middle; text-align:center;" width="150px" >No. Pesanan</th>
                							<th style="vertical-align:middle; text-align:center;" width="150px">Metode Bayar</th>
                							<th style="vertical-align:middle; text-align:center;" width="100px">Kurir</th>
                							<th style="vertical-align:middle; text-align:center;" width="150px">Resi</th>
                						</tr>
                					</thead>
                					<tbody class="table-responsive-shopee-all-cetak">
                					</tbody>
                				</table> 
                			</div>
              			</div>
              		</div> 
          	    </div>
		    </div>
		    <input type="hidden" id="dataCetakSemuaShopee">
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-pengembalian-shopee">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Detail Pengembalian&nbsp;&nbsp;<b id="NOSHOPEEPENGEMBALIAN" style="font-size:14pt;"></b>&nbsp;&nbsp;&nbsp;-&nbsp;<i id="STATUSSHOPEEPENGEMBALIAN"  style="font-size:12pt;"></i></h4>
            <button onclick="returShopee()" id='returShopeeDetail' class='btn btn-success' style='float:right;'>Jawab</button>
            <button id='returShopeeWait' class='btn' style='float:right; background:#888888; color:white;'>Menunggu Barang Tiba</button>
            
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-4 col-sm-4 col-xs-4" style="padding:0px;">
                   <label>Tgl Pengembalian</label>
                   <div id="TGLSHOPEEPENGEMBALIAN">-</div>
                   <br>
                   <label>Tenggat Waktu</label>
                   <div id="MINTGLSHOPEEPENGEMBALIAN">-</div>
                   <br>
                   <label>No. Resi Pengembalian</label>
                   <div id="RESISHOPEEPENGEMBALIAN">-</div>
                   <br>
                   <label>No. Pesanan</label>
                   <div id="NOSHOPEEPESANANPENGEMBALIAN">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                   <label>Pembeli </label>
                   <div id="NAMAPEMBELISHOPEEPENGEMBALIAN">-</div>
                   <br>
                   <label>Telp </label>
                   <div id="TELPPEMBELISHOPEEPENGEMBALIAN">-</div>
                   <br>
                   <label>Alamat </label>
                   <div id="ALAMATPEMBELISHOPEEPENGEMBALIAN">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4"style="padding:0px;"> 
                   <label>Alasan Pilihan Pembeli</label>
                   <div id="ALASANSHOPEEPILIHAN">-</div>
                   <br>
                   <label>Alasan Pengembalian Pembeli</label>
                   <div id="ALASANSHOPEEPENGEMBALIAN" style="max-height:70px; overflow-x:hidden;">-</div>
                   <br>
                   <label>Bukti Pengembalian Pembeli</label>
          	    	<div id="GAMBARPENGEMBALIANSHOPEE" style="max-height:70px; overflow-x:hidden; width:50%; float:left;"></div>
          	    	<div id="VIDEOPENGEMBALIANSHOPEE" style="max-height:70px; overflow-x:hidden; width:50%;"></div>
                </div>
      	    	<!--SATU TABEL-->
      	    	<div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:6px; padding:0px;" >
          	    	<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          	    		<div class="row"> 
              				<div class=" col-sm-12">
              					<table id="dataGridDetailPengembalianShopee" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<thead>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Jml</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Sat</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px" >Harga</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px">Dana Kembali</th>
              							</tr>
              						</thead>
              						<tbody class="table-responsive-shopee-pengembalian">
              						</tbody>
              					</table> 
              				</div>
          	    		</div>
          	    	</div> 
          	    	<div class="row" style="margin:0px;padding:0px;"> 
              				<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
              					<table id="footerShopee" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<tfoot>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
              								<th style="vertical-align:middle; text-align:center;" id="TOTALQTYPENGEMBALIANSHOPEE" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:right;" id="SUBTOTALPENGEMBALIANSHOPEE" width="100px"></th>
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

<div class="modal fade" id="modal-lebih-jelas-shopee" style="z-index:999999999999999999999999999;">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; <span id="titleLebihJelasShopee" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_shopee'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmShopee()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewLebihJelasShopee">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-retur-shopee">
	<div class="modal-dialog" style="width:700px;">
	    <div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Proses Pengembalian&nbsp;&nbsp;<b id="NOSHOPEERETUR" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body">
		    <div id="HEADERRETURSHOPEE"></div>
		    Jadi penjual memilih :
		    <br><br>
          	<ul class="nav nav-tabs" id="tab_retur_shopee">
          		<li id="tab_retur_header_shopee_0"><a href="#tab_retur_detail_shopee_0" data-toggle="tab">Kembalikan Dana ke Pembeli</a></li>
          	    <li id="tab_retur_header_shopee_1" onclick="focusOnRefundShopee()"><a href="#tab_retur_detail_shopee_1" data-toggle="tab">Negosiasi Pengembalian</a></li>
          	    <li id="tab_retur_header_shopee_2"><a href="#tab_retur_detail_shopee_2" data-toggle="tab">Menunggu Pengembalian Barang</a></li>
          	    <li id="tab_retur_header_shopee_3"><a href="#tab_retur_detail_shopee_3" data-toggle="tab">Ajukan Banding</a></li>
            </ul>
            <div class="tab-content" style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; padding: 10px 10px 10px 10px;">
                <div class="tab-pane " id="tab_retur_detail_shopee_0" style="padding:5px 0px 5px 0px;">
                    <div id="DETAILRETURSHOPEE_0">Dengan ini menyatakan bahwa : <br>Penjual telah setuju untuk melakukan <b>Pengembalian Dana Penuh</b>, dan pembeli &nbsp;<i>tidak perlu mengembalikan produk</i>.&nbsp; Setelah klik tombol "Setujui dan Kembalikan Dana". untuk melanjutkan proses pengembalian.</div>
                    <br><br>
                    <label style="width:100%; text-align:center; font-size:18pt;">Total Dana Kembali</label>
                    <div style="width:100%; text-align:center;"><input type="text" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANSHOPEE_0" onkeyup="return numberInputTrans(event,0)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                    <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANSHOPEE_0">
                    <br><br><br>
                     <button onclick="refundShopee(0)" id='returRefundShopee' class='btn btn-success' style='width:100%; font-weight:bold;'>Setuju&nbsp;&nbsp;dan&nbsp;&nbsp;Kembalikan&nbsp;&nbsp;Dana</button>
                </div>
                <div class="tab-pane" id="tab_retur_detail_shopee_1" style="padding:5px 0px 5px 0px;">
                    <div id="DETAILRETURSHOPEE_1"></div>
                    <br><br>
                    <label style="width:100%; text-align:center; font-size:18pt;">Total Dana Pengembalian yang Diajukan</label>
                    <div style="width:100%; text-align:center;"><input type="text" class="form-control has-feedback-left" id="DANADIKEMBALIKANSHOPEE_1" onkeyup="return numberInputTrans(event,1)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                    <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANSHOPEE_1">
                     <div style="width:100%; margin-top:10px; text-align:center;"><button id='btn_max_kembali_shopee' onclick="setMaksRefundShopee()" style='border:1px solid #CECECE; margin:auto;' class='btn' >Maks Pengembalian</button></div><br><br>
                     <button onclick="refundShopee(11)" id='returAcceptNegotiationShopee' class='btn btn-success' style='width:100%; font-weight:bold;'>Setuju&nbsp;&nbsp;dan&nbsp;&nbsp;Kembalikan&nbsp;&nbsp;Dana</button>
                     <button onclick="refundShopee(1)" id='returNegotiationShopee' class='btn btn-danger' style='width:100%; font-weight:bold;'>Negosiasi&nbsp;&nbsp;Pengembalian&nbsp;&nbsp;Dana</button>
                     <button id='returShopeeWaitResponse' class='btn' style='width:100%; background:#888888; color:white; font-weight:bold;'>Menunggu&nbsp;&nbsp;Respon&nbsp;&nbsp;Pembeli</button>
                </div>
                <div class="tab-pane" id="tab_retur_detail_shopee_2" style="padding:5px 0px 5px 0px;">
                     <div id="DETAILRETURSHOPEE_2">Dengan ini menyatakan bahwa : <br>Penjual menunggu <b>Barang Sampai</b>, untuk melanjutkan proses pengembalian.&nbsp; Setelah klik tombol "Menunggu Pengembalian Barang". untuk menunggu barang tiba.</div>
                     <div style="width:100%; text-align:center;"><input type="hidden" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANSHOPEE_2" onkeyup="return numberInputTrans(event,2)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                     <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANSHOPEE_2">
                     <br><br>
                     <button onclick="refundShopee(2)" id='returWaitShopee' class='btn btn-warning' style='width:100%; font-weight:bold;'>Menunggu&nbsp;&nbsp;Pengembalian&nbsp;&nbsp;Barang</button>
                </div>
                <div class="tab-pane" id="tab_retur_detail_shopee_3" style="padding:5px 0px 5px 0px;">
                    <div id="DETAILRETURSHOPEE_3">Dengan ini menyatakan bahwa : <br>Penjual mengajukan banding terhadap barang yang telah dikirimkan oleh Pembeli (Terkait kerusakan, barang yang dikembalikan berbeda, dll).<br><br>Untuk transaksi hanya dapat dilakukan pada aplikasi shopee. Status transaksi dan stok akan terupdate, melalui sinkronisasi otomatis maupun sinkronisasi manual.</div>
        		    <!--<br>-->
        		    <!--<div>-->
              <!--    	    <label>Alasan Banding</label>-->
            		<!--	<select id="cb_alasan_sengketa_shopee" name="cb_alasan_sengketa_shopee" class="form-control "  panelHeight="auto" required="true">-->
                  		
              <!--    		</select>-->
              <!--    	</div>-->
            		<!--<br>-->
            		<!--<div>-->
              <!--    	    <label>Penjelasan Banding</label>-->
            		<!--    <textarea id="deskripsi_sengketa_shopee" maxlines="2" style="width:100%; height:80px; border:0.5px solid #cecece; padding:10px;" placeholder="Masukkan Penjelasan....."></textarea>-->
            		<!--</div>-->
            		<!--<br>-->
            		<!-- <div>-->
              <!--    	    <label>Email Penanggung Jawab</label>-->
            		<!--	<input type="email" class="form-control" id="email_sengketa_shopee" style="width:100%;" name="email_sengketa_shopee" placeholder="Cth : shopee@gmail.com">-->
              <!--		</div>-->
              <!--    	<br>-->
              <!--    	<div id="uploadBuktiShopee">-->
              <!--    	    <label>Upload Bukti</label>-->
              <!--    	    <div id="penjelasan_bukti_shopee"></div>-->
            		<!--	<div id="proof_sengketa_shopee" style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:15px; padding:10px;">-->
            			    
            		<!--	</div>-->
            		<!-- </div>-->
              <!--       <div style="width:100%; text-align:center;"><input type="hidden" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANSHOPEE_3" onkeyup="return numberInputTrans(event,3)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>-->
              <!--       <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANSHOPEE_3">-->
              <!--       <input type="hidden" id="dataDisputeShopee">-->
              <!--       <input type="hidden" id="pilihanDisputeShopee">-->
              <!--       <input type="hidden" id="pilihDisputeShopee">-->
              <!--       <br>-->
                     <!--<button onclick="refundShopee(3)" id='returDisputeShopee' class='btn btn-danger' style='width:100%; font-weight:bold;'>Ajukan&nbsp;&nbsp;Banding</button>-->
                </div>
            </div>  
        </div>
      </div>
	</div>
    <input type="hidden" id="dataReturShopee">
</div>

<input type="hidden" id="kategori_item_shopee" value="">
<input type="hidden" id="rowDataShopee">

<script>

var firsTimeShopee = ["",true,true,true,true];
var sinkronShopeeState = false;
var doneSinkronShopee =  ["",true,true,true,true];
var totalPesananShopeeAll = 0;

setTimeout(() => {
    changeTabShopee(1);
    changeTabShopee(2);
    changeTabShopee(3);
    changeTabShopee(4);
	
	$("#filter_status_shopee_"+1+", #filter_tgl_shopee_"+1).show();
    
    for(var x = 1; x <= 4 ; x++)
    {
       if(1 != x)
       {
            $("#filter_status_shopee_"+x+", #filter_tgl_shopee_"+x).hide();
       }
    }
}, "100");

$(document).ready(function(){
	
    //TAMBAH
	$('#tgl_awal_filter_shopee_1, #tgl_akhir_filter_shopee_1, #tgl_awal_filter_shopee_2, #tgl_akhir_filter_shopee_2, #tgl_awal_filter_shopee_3, #tgl_akhir_filter_shopee_3, #tgl_awal_filter_shopee_4, #tgl_akhir_filter_shopee_4').datepicker({
		format: 'yyyy-mm-dd',
		 autoclose: true, // Close the datepicker automatically after selection
        container: 'body', // Attach the datepicker to the body element
        orientation: 'bottom auto' // Show the calendar below the input
	});
	$("#tgl_awal_filter_shopee_1, #tgl_awal_filter_shopee_2, #tgl_awal_filter_shopee_3, #tgl_awal_filter_shopee_4").datepicker('setDate', "<?=TGLAWALFILTERMARKETPLACE?>");
	$("#tgl_akhir_filter_shopee_1, #tgl_akhir_filter_shopee_2, #tgl_akhir_filter_shopee_3, #tgl_akhir_filter_shopee_4").datepicker('setDate', new Date());
	
	$("#statusShopee1").val('UNPAID,READY_TO_SHIP,PROCESSED,IN_CANCEL');
	$("#statusShopee2").val('SHIPPED,TO_CONFIRM_RECEIVE,RETRY_SHIP');
	$("#statusShopee3").val('COMPLETED,CANCELLED');
	$("#statusShopee4").val('TO_RETURN|REQUESTED,TO_RETURN|PROCESSING,TO_RETURN|JUDGING-SELLER_DISPUTE');
	
	$('body').keyup(function(e){
		hotkey(e);
	});
	
	$("#modal-barang").on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input', $("#table_barang").DataTable().table().container()).focus();
    });
    
    //TABLE BARANG
	$("#table_barang_shopee").DataTable({
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
					e.kategori 	    = getKategoriShopee();
					e.marketplace 	= "SHOPEE";
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
	$('#table_barang_shopee tbody').on('click', 'tr', function () {
		var row = $('#table_barang_shopee').DataTable().row( this ).data();
		$("#modal-barang-shopee").modal('hide');
		
		 $(".table-responsive-shopee-ubah").html('');
         var itemDetail = JSON.parse($("#itemUbahShopee").val()); 
         for(var x = 0 ; x < itemDetail.length ; x++)
         {
             if(itemDetail[x]['WARNAOLD'] == $("#warnaOldShopee").html() && itemDetail[x]['SIZEOLD'] && $("#sizeOldShopee").html())
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
             
             $(".table-responsive-shopee-ubah").append(setDetail(itemDetail,x,namaBarang,true));
         }
         
         
        $("#itemUbahShopee").val(JSON.stringify(itemDetail));
            
		var table = $('#table_barang_shopee').DataTable();
		table.search("").draw();
	});
});

function getKategoriShopee(){
	return $("#kategori_item_shopee").val();
}

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_shopee_1").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#statusShopee1").val('UNPAID,READY_TO_SHIP,PROCESSED,IN_CANCEL');
	}	
	else
	{
		$("#statusShopee1").val($(this).val());
	}
	$("#dataGridShopee1").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_shopee_2").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#statusShopee2").val('SHIPPED,TO_CONFIRM_RECEIVE,RETRY_SHIP');
	}	
	else
	{
		$("#statusShopee2").val($(this).val());
	}
	$("#dataGridShopee2").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_shopee_3").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#statusShopee3").val('COMPLETED,CANCELLED');
	}	
	else
	{
		$("#statusShopee3").val($(this).val());
	}
	$("#dataGridShopee3").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_shopee_4").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#statusShopee4").val('TO_RETURN|REQUESTED,TO_RETURN|PROCESSING,TO_RETURN|JUDGING-SELLER_DISPUTE');
	}	
	else
	{
		$("#statusShopee4").val($(this).val());
	}
	$("#dataGridShopee4").DataTable().ajax.reload();
	
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

function getStatusShopee(index){
	return $("#statusShopee"+index).val();
}

function refreshShopee(index){
    loading();
    $("#dataGridShopee"+index).DataTable().ajax.reload();
}

function changeTabShopee(index){
    
    if(!sinkronShopeeState)
    {
        loading();
    }
    
    
    // if(firsTimeShopee[1])
    // {
    //      $.ajax({
    //     	type    : 'POST',
    //     	url     : base_url+'Shopee/init/<?=date('Y-m-d')?>/<?=date('Y-m-d')?>/update_time',
    //     	dataType: 'json',
    //     	success : function(msg){
        	    
    //     	}
	   //  });
    // }
    // else if(!firsTimeShopee[index])
    // {
    //      $.ajax({
    //     	type    : 'POST',
    //     	url     : base_url+'Shopee/init/<?=date('Y-m-d')?>/<?=date('Y-m-d')?>/update_time',
    //     	dataType: 'json',
    //     	success : function(msg){
        	    
    //     	}
	   //  });
    // }
    
    $("#filter_status_shopee_"+index+", #filter_tgl_shopee_"+index).show();
    
    for(var x = 1; x <= 4 ; x++)
    {
       if(index != x)
       {
            $("#filter_status_shopee_"+x+", #filter_tgl_shopee_"+x+"").hide();
       }
    }
    
    if(firsTimeShopee[index])
    {
        firsTimeShopee[index] = false;
    	//GRID BARANG
    	if(index != 4)
    	{
        	$('#dataGridShopee'+index).DataTable({
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
        			url    : base_url+'Shopee/dataGrid/',
        			dataSrc: "rows",
        			type   : "POST",
        			data   : function(e){
        			        e.state          = index;
        					e.status 		 = getStatusShopee(index);
        					e.tglawal        = $('#tgl_awal_filter_shopee_'+index).val();
        					e.tglakhir       = $('#tgl_akhir_filter_shopee_'+index).val();
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
                            if (row.STATUS.toUpperCase() == "SIAP DIKIRIM") {
                                html += "<button id='btn_lihat_shopee' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<div style='margin-top:5px;'><button id='btn_edit_shopee' class='btn btn-primary' style='width:59.5px;' >Ubah</button> <button id='btn_hapus_shopee' class='btn btn-danger'  style='width:59.5px;' >Batal</button></div>";
                                if(row.KODEPENGAMBILAN != "")
                                {
                                    html+= "<button  style='margin-top:5px;' id='btn_cetak_shopee' class='btn btn-warning'  style='width:122px;'>Cetak</button>";
                                }
                                html += "<div style='margin-top:auto;'><button id='btn_kirim_shopee' class='btn btn-success' style='width:122px;'>Atur Pengiriman</button></div>";
                            } else if (row.STATUS.toUpperCase() == "DALAM PENGIRIMAN" || row.STATUS.toUpperCase() == "TELAH DIKIRIM") {
                                html += "<button id='btn_lihat_shopee' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<div style='margin-top:auto;'><button id='btn_lacak_shopee' class='btn btn-success' style='width:122px;'>Lacak Pesanan</button></div>";
                            } else if (row.STATUS.toUpperCase() == "DIPROSES" ) {
                                html += "<button id='btn_lihat_shopee' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<div style='margin-top:auto;'><button id='btn_cetak_shopee' class='btn btn-warning'  style='width:122px;'>Cetak</button></div>";
                            } else if(row.STATUS.toUpperCase() == "SELESAI" && row.KODEPENGEMBALIAN != "" && (row.BARANGSAMPAI == 0 && row.BARANGSAMPAIMANUAL == 0)){
                                html += "<button id='btn_lihat_shopee' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<button  style='width:122px; margin-top:5px;' id='btn_retur_manual_shopee' class='btn btn-danger'  style='width:122px;' >Retur B. Manual</button>";
                            }  else {
                                html += "<button id='btn_lihat_shopee' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
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
                            if(row.STATUS.toUpperCase() == "SELESAI" && (row.BARANGSAMPAI == 1 || row.BARANGSAMPAIMANUAL == 1)){
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
                                html += "<div style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>;'>"+row.KODEPENGEMBALIAN+"</div>";
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
    	    $('#dataGridShopee'+index).DataTable({
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
        			url    : base_url+'Shopee/dataGrid/',
        			dataSrc: "rows",
        			type   : "POST",
        			data   : function(e){
        			        e.state          = index;
        					e.status 		 = getStatusShopee(index);
        					e.tglawal        = $('#tgl_awal_filter_shopee_'+index).val();
        					e.tglakhir       = $('#tgl_akhir_filter_shopee_'+index).val();
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
                                html += "<button id='btn_kembali_shopee' style='border:1px solid #CECECE;' class='btn' >Detail Pengembalian</button>";
                                html += "<div style='margin-top:5px; width:122px; white-space: pre-wrap; white-space: -moz-pre-wrap;  white-space: -pre-wrap;  white-space: -o-pre-wrap;word-wrap: break-word;'>*Cek Status / Jawab bisa melalui<br>Detail Pengembalian</div><div style='margin:auto;'></div>";
                          
                            html += "</div>";
                            return html;
                        }
                    },
        		]
            });
    	}
        
        
    	//DAPATKAN INDEX
    	var table = $('#dataGridShopee'+index).DataTable();
    	$('#dataGridShopee'+index+' tbody').on( 'click', 'button', function () {
    		var row = table.row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		$("#rowDataShopee").val(JSON.stringify(row));
    		
    		if(mode == "btn_lihat_shopee"){ lihatShopee();}
    		else if(mode == "btn_edit_shopee"){ubahShopee();}
    		else if(mode == "btn_cetak_shopee"){cetakShopee();}
    		else if(mode == "btn_hapus_shopee"){hapusShopee();}
    		else if(mode == "btn_kirim_shopee"){kirimShopee();}
    		else if(mode == "btn_lacak_shopee"){lacakShopee();}
    		else if(mode == "btn_kembali_shopee"){kembaliShopee();}
    		else if(mode == "btn_retur_shopee"){returShopee();}
    		else if(mode == "btn_retur_manual_shopee"){returBarangShopee();}
    		
    	} );
    	
    	$('#dataGridShopee'+index+' tbody').on( 'click', 'i', function () {
    		var row = table.row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		$("#rowDataShopee").val(JSON.stringify(row));
    		$("#fromNoteShopee").val("GRID_X");
    		if(mode == "editNoteShopee"){catatanPenjualShopee()}
    	} );
    }
    else
    {
        $("#dataGridShopee"+index).DataTable().ajax.reload();
    }
    
    // Close SweetAlert after data is loaded
    $('#dataGridShopee'+index).DataTable().on('xhr.dt', function () {
        if(!sinkronShopeeState)
        {
           setTimeout(() => {
               if($('#dataGridShopee'+index).DataTable().data().count() == 0)
               {
                   $("#totalShopee"+index).hide();
               }
               else
               {
                    $("#totalShopee"+index).show();
                    $("#totalShopee"+index).html($('#dataGridShopee'+index).DataTable().data().count());
               }
               recountCetakdanKirim(); 
               Swal.close();
           }, "500");
        }
        else
        {
            //JIKA SUDAH DONE SEMUA MAKA SINKRON STATE FALSE, JIKA TIDAK DIKEMBALIKAN TRUE
            sinkronShopeeState = false;
            doneSinkronShopee[index] = true;
            for(var x = 1; x <= 4 ; x++)
            {
                if(!doneSinkronShopee[x]){
                    sinkronShopeeState = true;
                }
            }
            
            if(!sinkronShopeeState)
            {
                Swal.close();
                
                setTimeout(() => {
                    var caption = "Tidak Ada Pesanan Baru";
                    if(totalPesananShopeeAll > 0)
                    {
                        caption = 'Terdapat '+totalPesananShopeeAll+' Pesanan Baru'
                    }
                    
                    for(var x = 1; x <= 4 ; x++)
                    {
                       if($('#dataGridShopee'+x).DataTable().data().count() == 0)
                       {
                           $("#totalShopee"+x).hide();
                       }
                       else
                       {
                            $("#totalShopee"+x).show();
                            $("#totalShopee"+x).html($('#dataGridShopee'+x).DataTable().data().count());
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
    var data = $("#dataGridShopee1").DataTable().rows().data();
    var countCetak = 0;
    var countKirim = 0;
    for(var x = 0; x < data.length; x++)
    {
        if(data[x]['STATUS'].toUpperCase() == "DIPROSES" || (data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM" && data[x]['KODEPENGAMBILAN'] != ""))
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

function lihatShopee(){
    var row = JSON.parse($("#rowDataShopee").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/loadDetail/',
    	data    : {kode: row.KODEPESANAN},
    	dataType: 'json',
    	success : function(msg){
    	    $("#cetakShopeeDetail").hide();
    	    $("#hapusShopeeDetail").hide();
    	    $("#ubahShopeeDetail").hide();
    	    $("#kirimShopeeDetail").hide();
    	    $("#lacakShopeeDetail").hide();
    	    if(row.STATUS.toUpperCase() == "SIAP DIKIRIM")
    	    {
    	        $("#hapusShopeeDetail").show();
    	        $("#ubahShopeeDetail").show();
    	        $("#kirimShopeeDetail").show();
    	    }
    	    else if(row.STATUS.toUpperCase() == "DALAM PENGIRIMAN" || row.STATUS.toUpperCase() == "TELAH DIKIRIM")
    	    {
    	        $("#lacakShopeeDetail").show();
    	    }
    	    else if(row.STATUS.toUpperCase() == "DIPROSES")
    	    {
    	        $("#cetakShopeeDetail").show();
    	    }
    	    
            $("#NOSHOPEE").html("#"+row.KODEPESANAN);
            $("#STATUSSHOPEE").html(row.STATUS);
            $("#TGLPESANANSHOPEE").html(row.TGLPESANAN.replaceAll("<br>"," "));
            $("#TGLKIRIMSHOPEE").html(row.KURIR==""?"-":row.MINTGLKIRIM);
            $("#PEMBAYARANSHOPEE").html(row.METODEBAYAR);
            $("#KURIRSHOPEE").html((row.KURIR==""?"-":row.KURIR)+" / "+(row.RESI==""?"-":row.RESI));
            $("#NAMAPEMBELISHOPEE").html(row.BUYERNAME+" ("+row.USERNAME+")");
            $("#TELPPEMBELISHOPEE").html(row.BUYERPHONE);
            $("#ALAMATPEMBELISHOPEE").html(row.BUYERALAMAT);
            $("#CATATANPEMBELISHOPEE").html(row.CATATANBELI);
            $("#CATATANPEMBELISHOPEE").html($("#CATATANPEMBELISHOPEE div").html()==""?"<div>-</div>":row.CATATANBELI);
            $("#ALASANPENGEMBALIANSHOPEE").html(row.CATATANPENGEMBALIAN);
            
            if($("#ALASANPENGEMBALIANSHOPEE div").html() != "")
            {
                $(".alasanKembaliShopee").show();
                $("#ALASANPENGEMBALIANSHOPEE").html(row.CATATANPENGEMBALIAN);
            }
            else
            {
                $(".alasanKembaliShopee").hide();
                $("#ALASANPENGEMBALIANSHOPEE").html("<div>-</div>");
            }
            
            $(".noKembaliShopee").show();
            if(row.KODEPENGEMBALIAN != null)
            {
                $(".noKembaliShopee").show();
                $("#NOPENGEMBALIANSHOPEE").html(row.KODEPENGEMBALIAN);
            }
            
            $(".table-responsive-shopee").html('');
            
            var totalCurr = 0;
            var totalCurrKembali = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
                var namaBarang = msg.DETAILBARANG[x].NAMA;
                if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
                {
                    namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
                }
                
                if((msg.DETAILBARANG[x].SIZEKEMBALI != "" || msg.DETAILBARANG[x].WARNAKEMBALI != "") && row.BARANGSAMPAI == 1)
                {
                    namaBarang += ("<br><span  style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; font-style:italic;'>Retur : "+msg.DETAILBARANG[x].WARNA+" / "+msg.DETAILBARANG[x].SIZE+"</span>");
                }
                
                $(".table-responsive-shopee").append(setDetail(msg.DETAILBARANG,x,namaBarang,false));
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
                totalCurrKembali += parseInt(msg.DETAILBARANG[x].JUMLAHKEMBALI);
            }
            var totalKembali = "";
            if(totalCurrKembali > 0 && row.BARANGSAMPAI == 1)
            {
                totalKembali = "<span style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>' > (-"+currency(totalCurrKembali.toString())+")</span>";
            }
            $("#TOTALQTYSHOPEE").html(currency(totalCurr)+totalKembali);
            $("#SUBTOTALSHOPEE").html(currency(msg.SUBTOTALBELI));
            
           $("#TOTALPENJUALSHOPEE").html(currency(msg.SUBTOTALBELI));
           $("#DISKONPENJUALSHOPEE").html(currency(msg.DISKONJUAL));
           $("#BIAYAKIRIMPENJUALSHOPEE").html(currency(msg.BIAYAKIRIMJUAL));
           $("#BIAYALAYANANPENJUALSHOPEE").html(currency(msg.BIAYALAYANANJUAL));
           $("#GRANDTOTALPENJUALSHOPEE").html(currency(msg.PENERIMAANJUAL));
           $("#REFUNDPENJUALSHOPEE").html(currency(msg.REFUNDJUAL));
           $("#PENYELESAIANPENJUALSHOPEE").html(currency(msg.PENYELESAIANPENJUAL));
           
           $("#TOTALPEMBELISHOPEE").html(currency(msg.SUBTOTALBELI));
           $("#DISKONPEMBELISHOPEE").html(currency(msg.DISKONBELI));
           $("#BIAYAKIRIMPEMBELISHOPEE").html(currency(msg.BIAYAKIRIMBELI));
           $("#BIAYALAINPEMBELISHOPEE").html(currency(msg.BIAYALAINBELI));
           $("#PEMBAYARANPEMBELISHOPEE").html(currency(msg.PEMBAYARANBELI));
           if(row.STATUS == "Selesai" || row.STATUS == "Pembatalan")
           {
               $(".penyelesaianShopee").show();
           }
           else
           {
               $(".penyelesaianShopee").hide();
           }
            Swal.close();
            $("#modal-form-shopee").modal('show');
    			
    	}
    });
}

function kembaliShopee(){
    var row = JSON.parse($("#rowDataShopee").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/loadDetailPengembalian/',
    	data    : {kode: row.KODEPENGEMBALIAN},
    	dataType: 'json',
    	success : function(msg){
    	    
    		$("#dataReturShopee").val(JSON.stringify(msg));	
    		
            $("#NOSHOPEEPENGEMBALIAN").html("#"+row.KODEPENGEMBALIAN);
            $("#NOSHOPEEPESANANPENGEMBALIAN").html(row.KODEPESANAN);
            $("#STATUSSHOPEEPENGEMBALIAN").html(row.STATUS.replaceAll("<br>"," "));
            $("#TGLSHOPEEPENGEMBALIAN").html(row.TGLPENGEMBALIAN.replaceAll("<br>"," "));
            $("#MINTGLSHOPEEPENGEMBALIAN").html(row.MINTGLPENGEMBALIAN);
            $("#RESISHOPEEPENGEMBALIAN").html((row.RESIPENGEMBALIAN==""?"-":row.RESIPENGEMBALIAN));
            $("#NAMAPEMBELISHOPEEPENGEMBALIAN").html(row.BUYERNAME+" ("+row.USERNAME+")");
            $("#TELPPEMBELISHOPEEPENGEMBALIAN").html(row.BUYERPHONE);
            $("#ALAMATPEMBELISHOPEEPENGEMBALIAN").html(row.BUYERALAMAT);
            $("#ALASANSHOPEEPILIHAN").html(msg.ALASANPILIHPENGEMBALIAN);
            $("#ALASANSHOPEEPENGEMBALIAN").html(row.CATATANPENGEMBALIAN);
            $("#ALASANSHOPEEPENGEMBALIAN").html($("#ALASANSHOPEEPENGEMBALIAN div").html()==""?"<div>-</div>":row.CATATANPENGEMBALIAN);
            
            $(".table-responsive-shopee-pengembalian").html('');
            
            $("#returShopeeDetail").hide();
            $("#returShopeeWait").show();
            
            if (msg.LOGISTICSTATUS == "LOGISTICS_DELIVERY_DONE") {
                $("#returShopeeDetail").show();
                $("#returShopeeWait").hide();
            }
            else if (msg.REFUNDTYPE == "RRBOC" && row.SELLERMENUNGGUBARANGDATANG == 0 ) {
                $("#returShopeeDetail").show();
                $("#returShopeeWait").hide();
            }
            
            var totalCurr = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
                var namaBarang = msg.DETAILBARANG[x].NAMA;
                if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
                {
                    namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
                }
                
                $(".table-responsive-shopee-pengembalian").append(`<tr>
                	<td style="vertical-align:middle; text-align:left;" width="400px" >`+namaBarang+`</td>
                  	<td style="vertical-align:middle; text-align:center;" width="50px">`+currency(msg.DETAILBARANG[x].JUMLAH.toString())+`</td>
                  	<td style="vertical-align:middle; text-align:center;" width="50px">`+msg.DETAILBARANG[x].SATUAN.toString()+`</td>
                  	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(msg.DETAILBARANG[x].HARGA.toString())+`</td>
                  	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(msg.DETAILBARANG[x].SUBTOTAL.toString())+`</td>
                </tr>`);
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
            }
            
            $("#TOTALQTYPENGEMBALIANSHOPEE").html(currency(totalCurr));
            $("#SUBTOTALPENGEMBALIANSHOPEE").html(currency(msg.TOTALREFUND));
            
            var buktiGambar = "";
            for(var x = 0 ; x < msg.GAMBAR.length;x++)
            {
                buktiGambar += "<span style='color : blue; cursor:pointer; text-align:center;' onclick='lihatLebihJelasShopee(`GAMBAR`,`Gambar "+(x+1)+"`,`"+msg.GAMBAR[x]+"`)' >Gambar "+(x+1)+"</span><br>";
            }
            $("#GAMBARPENGEMBALIANSHOPEE").html(buktiGambar);
            
            var buktiVideo = "";
            for(var x = 0 ; x < msg.VIDEO.length;x++)
            {
                buktiVideo += "<span style='color : blue; cursor:pointer; text-align:center;' onclick='lihatLebihJelasShopee(`VIDEO`,`Video "+(x+1)+"`,`"+msg.VIDEO[x]['video_url']+"`)' >Video "+(x+1)+"</span><br>";
            }
            $("#VIDEOPENGEMBALIANSHOPEE").html(buktiVideo);
            
            Swal.close();
            $("#modal-pengembalian-shopee").modal('show');
    	}
    });
}

function lihatLebihJelasShopee(jenis,title,url){

    $("#modal-lebih-jelas-shopee").modal("show");
    $("#titleLebihJelasShopee").html(title);
    $("#previewLebihJelasShopee").css("color","#3296ff");
    $("#previewLebihJelasShopee").css("cursor","pointer");
    $("#previewLebihJelasShopee").css("text-align","center");
    $("#previewLebihJelasShopee").css("background","#d4d4d7");
    if(jenis == "GAMBAR")
    {
        $("#previewLebihJelasShopee").html("<img src='"+url+"' max-width=100%; height=600px;>");
    }
    else
    {
        $("#previewLebihJelasShopee").html("<iframe src='"+url+"' max-width=100%; height=600px;>");
    }
}

$("#cb_alasan_sengketa_shopee").change(function(){
    var dataSengketa = JSON.parse($("#dataDisputeShopee").val());
    var chooseSengketa = $(this).val();
    $("#pilihDisputeShopee").val(chooseSengketa);
    $("#proof_sengketa_shopee").html("");
    $("#uploadBuktiShopee").hide();
    
    for(var x = 0 ; x < dataSengketa.length;x++)
    {
      if(chooseSengketa == dataSengketa[x].dispute_reason) 
      {
        $("#penjelasan_bukti_shopee").html("");
        var exampleEvidence = dataSengketa[x].sample_evidence;
        
        if(exampleEvidence == null)
        {
            $("#uploadBuktiShopee").hide();
        }
        else
        {
             $("#uploadBuktiShopee").show();
        }
    
        var htmlExample = dataSengketa[x].dispute_requirement+"<br>";
        for(var y = 0 ; y < exampleEvidence.length;y++)
        {
          htmlExample += "<img onclick='lihatLebihJelasShopee(`GAMBAR`,`Gambar Contoh "+(y+1)+"`,`"+exampleEvidence[y].url+"`)'; src='"+exampleEvidence[y].thumbnail+"' style='width:100px; margin-top:10px; margin-left:10px; cursor:pointer;'>";  
        }
        $("#penjelasan_bukti_shopee").html(htmlExample);
        
        var modulEvidence = dataSengketa[x].evidence_module_list;
        $("#pilihanDisputeShopee").val(JSON.stringify(modulEvidence));
        var htmlProof = "<table>";
        for(var y = 0 ; y < modulEvidence.length;y++)
        {
            var buktiAda = false;
            var onclick = "";
            if(buktiAda)
            {
                onclick="lihatLebihJelasShopee(`GAMBAR`,"+modulEvidence[y].requirement+",`"+modulEvidence[y].url+"`)";
            }
            
            var required = "";
            if(modulEvidence[y].is_required)
            {
                required = "(Harus Ada)";
            }
            
            htmlProof += `<tr>
                            <td>
                                <input type="file" id="file-input-shopee-`+y+`" accept="image/*,video/*" style="display:none;" value="">
                                <input type="hidden"  id="keterangan-input-shopee-`+y+`" value="`+modulEvidence[y].requirement+`">
                                <input type="hidden"  id="format-input-shopee-`+y+`" value="">
                                <input type="hidden"  id="index-input-shopee-`+y+`" value="`+y+`">
                                <input type="hidden"  id="src-input-shopee-`+y+`" value="">
                                <input type="hidden"  id="id-input-shopee-`+y+`" value="">
                                <div>
                                    <img id="preview-image-shopee-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; margin-top:10px; margin-right:10px; cursor:pointer; border:2px solid #dddddd;'></td><td><div><b>`+required+`</b> `+modulEvidence[y].requirement+`
                                    <br>
                                    <span id="ubahProofShopee-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                    &nbsp;
                                    <span id="hapusProofShopee-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                    </div>
                                </div>
                            </td>
                           </tr>`;  
        
        }
        htmlProof += "</table>";
        $("#proof_sengketa_shopee").html(htmlProof);
    
        for(var y = 0 ; y < modulEvidence.length;y++)
        {
            // if(!modulEvidence[y].requirement.toUpperCase().includes("VIDEO"))
            // {
                const fileInput = document.getElementById('file-input-shopee-'+y);
                const previewImage = document.getElementById('preview-image-shopee-'+y);
                const title = document.getElementById('keterangan-input-shopee-'+y);
                const format = document.getElementById('format-input-shopee-'+y);
                const index = document.getElementById('index-input-shopee-'+y);
                const url =  document.getElementById('src-input-shopee-'+y);
                const id =  document.getElementById('id-input-shopee-'+y);
                
                const ubahImage = document.getElementById('ubahProofShopee-'+y);
                const hapusImage = document.getElementById('hapusProofShopee-'+y);
                
                previewImage.addEventListener('click', () => {
                  if(url.value != '')
                  {
                      lihatLebihJelasShopee(format.value,title.value,url.value);
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
                
                    var row = JSON.parse($("#rowDataShopee").val());
                    // Upload file asli ke server
                    const formData = new FormData();
                    formData.append('index', index.value);
                    formData.append('kode', row.KODEPENGEMBALIAN);
                    formData.append('file', file);
                    formData.append('tipe', 'GAMBAR');
                    formData.append('size', file.size);
                    formData.append("reason","proof/SHOPEE");
                
                    loading();
                    
                    $.ajax({
                      type: 'POST',
                      url: base_url + 'Shopee/uploadLocalUrlProof/',
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
                      
                       var row = JSON.parse($("#rowDataShopee").val());
                        // Upload file asli ke server
                        const formData = new FormData();
                        formData.append('index', index.value);
                        formData.append('kode', row.KODEPENGEMBALIAN);
                        formData.append('file', file);
                        formData.append('tipe', 'VIDEO');
                        formData.append('size', file.size);
                        formData.append("reason","proof/SHOPEE");
                    
                        loading();
                        
                        $.ajax({
                          type: 'POST',
                          url: base_url + 'Shopee/uploadLocalUrlProof/',
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
        // }
        
      }
    }
})

function ubahShopee(){
    $("#modal-form-shopee").modal('hide');
    var row = JSON.parse($("#rowDataShopee").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/loadDetail/',
    	data    : {kode: row.KODEPESANAN},
    	dataType: 'json',
    	success : function(msg){
            $("#NOSHOPEEUBAH").html("#"+row.KODEPESANAN);
            $(".table-responsive-shopee-ubah").html('');
            
            var totalCurr = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
                var namaBarang = msg.DETAILBARANG[x].NAMA;
                if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
                {
                    namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
                }
                
                $(".table-responsive-shopee-ubah").append(setDetail(msg.DETAILBARANG,x,namaBarang,true));
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
            }
            $("#TOTALQTYSHOPEEUBAH").html(currency(totalCurr));
            $("#SUBTOTALSHOPEEUBAH").html(currency(msg.SUBTOTALBELI));
            Swal.close();
            $("#itemUbahShopee").val(JSON.stringify(msg.DETAILBARANG));
            $("#modal-ubah-shopee").modal('show');
    	}
    });
}

function openItemShopee(indexItem){
    var itemDetail = JSON.parse($("#itemUbahShopee").val());
    $("#kategori_item_shopee").val(itemDetail[indexItem].KATEGORI);
    $("#warnaOldShopee").html(itemDetail[indexItem].WARNAOLD);
    $("#sizeOldShopee").html(itemDetail[indexItem].SIZEOLD);
    $("#table_barang_shopee").DataTable().ajax.reload();
    $("#modal-barang-shopee").modal('show');
}

function resetItemShopee(indexItem){
    var itemDetail = JSON.parse($("#itemUbahShopee").val());
    
    itemDetail[indexItem]['NAMA']   = itemDetail[indexItem]['NAMAOLD'];
    itemDetail[indexItem]['WARNA']  = itemDetail[indexItem]['WARNAOLD'];
    itemDetail[indexItem]['SIZE']   = itemDetail[indexItem]['SIZEOLD'];
    itemDetail[indexItem]['SKU']    = itemDetail[indexItem]['SKUOLD'];

    $(".table-responsive-shopee-ubah").html('');
    
    for(var x = 0 ; x < itemDetail.length ; x++)
    {
        var namaBarang = itemDetail[x].NAMA;
        if(itemDetail[x].SIZE != itemDetail[x].SIZEOLD || itemDetail[x].WARNA != itemDetail[x].WARNAOLD)
        {
            namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span style='color:#949494; font-style:italic;'>Marketplace : "+itemDetail[x].WARNAOLD+" / "+itemDetail[x].SIZEOLD+"</span>");
        }
            	 
       $(".table-responsive-shopee-ubah").append(setDetail(itemDetail,x,namaBarang,true));        
    }
     
     
    $("#itemUbahShopee").val(JSON.stringify(itemDetail));
}

function ubahKonfirmShopee(){
     Swal.fire({
        title: 'Anda Yakin Mengubah Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var row = JSON.parse($("#rowDataShopee").val());
                loading();
                $.ajax({
                	type    : 'POST',
                	url     : base_url+'Shopee/ubah/',
                	data    : {kode: row.KODEPESANAN, dataItem: $("#itemUbahShopee").val()},
                	dataType: 'json',
                	success : function(msg){
                	    
                        if(msg.success)
                        {
                            Swal.close();
                            $("#modal-ubah-shopee").modal('hide');
                        }
                        
                	    Swal.fire({
                        	title            :  msg.msg,
                        	type             : (msg.success?'success':'error'),
                        	showConfirmButton: false,
                        	timer            : 2000
                        });
                        
                        setTimeout(() => {
                          reloadShopee();
                        }, "2000");
                	}
                });
        	}
        });
}

function setDetail(itemDetail,x,namaBarang,action=false)
{
    var row = JSON.parse($("#rowDataShopee").val());
    var actButton = '';
    var jmlKembali = '';
    if(action)
    {
       actButton = `<td style="vertical-align:middle; text-align:center;" width="103px" ><button id="btn_edit_detail_shopee" class="btn btn-primary" onclick="openItemShopee(`+x+`)"><i class="fa fa-edit"></i></button> <button id="btn_back_detail_shopee" class="btn btn-danger" onclick="resetItemShopee(`+x+`)"><i class="fa fa-refresh"></i></button></td>`;
    }
    
    if(itemDetail[x].JUMLAHKEMBALI != 0 && row.BARANGSAMPAI == 1)
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

function cetakShopee(){
    $("#modal-form-shopee").modal('hide');
    var row = JSON.parse($("#rowDataShopee").val());
    var rows = [row];
    loading();
     $.ajax({
     	type    : 'POST',
     	url     : base_url+'Shopee/print/',
     	data    : {dataNoPesanan: JSON.stringify(rows)},
     	dataType: 'json',
     	success : function(msg){
     	        
     	        if(msg.success)
                {
                    Swal.close();
                    $("#modal-note-shopee").modal('hide');
                    
                 	setTimeout(() => {
                      reloadShopee();
                      $("#countCetak").html("("+rows.length+")");
                      $("#previewCetakShopee").html("<iframe src='"+msg.merge_url+"' width=100%; height=600px;>");
                      $("#modal-cetak-shopee").modal('show');
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

function cetakShopeeSemua(index){
    $("#modal-cetak-all-shopee").modal('show');
    $("#pilihCetakAllShopee").prop("checked",true);
    var data = $("#dataGridShopee"+index).DataTable().rows().data();
    var detailData = "";
    var dataSimpan = [];
    for(var x = 0; x < data.length; x++)
    {
        if(data[x]['STATUS'].toUpperCase() == "DIPROSES" || (data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM" && data[x]['KODEPENGAMBILAN'] != ""))
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
    $("#dataCetakSemuaShopee").val(JSON.stringify(dataSimpan));
    $(".table-responsive-shopee-all-cetak").html(detailData);
    
    for(var x = 0; x < dataSimpan.length; x++)
    {
     $('#cetak'+x).change(function () {
         var count = 0;
         for(var x = 0; x < dataSimpan.length; x++)
         {
             if($(".table-responsive-shopee-all-cetak").find("#cetak"+x).is(':checked'))
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
             $("#pilihCetakAllShopee").prop("checked",true);
         }
         else
         {
             $("#pilihCetakAllShopee").prop("checked",false);
         }
      });
    }
    
    $("#pilihCetakAllShopee").change(function(){
        for(var x = 0; x < dataSimpan.length; x++)
         {
            $(".table-responsive-shopee-all-cetak").find("#cetak"+x).prop("checked",$(this).prop("checked"));
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

function cetakAllKonfirmShopee(){
    var dataSimpan = JSON.parse($("#dataCetakSemuaShopee").val());
    var rows = [];
    for(var x = 0; x < dataSimpan.length; x++)
    {
        if($(".table-responsive-shopee-all-cetak").find("#cetak"+x).is(':checked'))
        {
           rows.push(dataSimpan[x]);
        }
    }
    if(rows.length > 0)
    {
        loading();
        $.ajax({
         	type    : 'POST',
         	url     : base_url+'Shopee/print/',
         	data    : {dataNoPesanan: JSON.stringify(rows)},
         	dataType: 'json',
         	success : function(msg){
         	        
         	        if(msg.success)
                    {
                        Swal.close();
                        $("#modal-note-shopee").modal('hide');
                        $("#modal-cetak-all-shopee").modal('hide');
                        
                     	setTimeout(() => {
                          reloadShopee();
                          $("#countCetak").html("("+rows.length+")");
                          var iframe = "";
                          iframe += "<iframe id='SHOPEECETAK"+x+"' src='"+msg.merge_url+"' width=100%; height=600px;/><br><br>";
                          $("#previewCetakShopee").html(iframe);
                          $("#modal-cetak-shopee").modal('show');
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


function kirimShopee(){
    $("#modal-form-shopee").modal('hide');
    var row = JSON.parse($("#rowDataShopee").val());
    var rows = [row];
    loading();
    
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/cekStokLokasi/',
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
                 	url     : base_url+'Shopee/setKirim/',
                 	data    : {dataNoPackaging: JSON.stringify(rows),index:0},
                 	dataType: 'json',
                 	success : function(msg){
                 	        Swal.close();
                 	        $("#modal-kirim-shopee").modal('show');
                            $("#jadwalKirimShopee").val(JSON.stringify(msg));
                            
                            var dataKirim = JSON.parse($("#jadwalKirimShopee").val());
                            
                            var countPengiriman = 1;
                            $("#countAturPengiriman").html("("+countPengiriman.toString()+")");
                            $(".table-responsive-shopee-kirim").html('');
                            var indexKirim = 0;
                            var cb_pickup = "";
                            for(var x = 0 ; x < dataKirim.pickup[0].time_slot_list.length ; x++)
                            {
                                var selected = "";
                                if(x == 0)
                                {
                                    selected = "selected";
                                    $("#pickupKirimShopee").val(dataKirim.pickup[0].time_slot_list[x].pickup_time_id);
                                }
                                cb_pickup += ("<option value='"+dataKirim.pickup[0].time_slot_list[x].pickup_time_id+"' "+selected+">"+(dataKirim.pickup[0].time_slot_list[x].time_text == null?"":(dataKirim.pickup[0].time_slot_list[x].time_text+", "))+dataKirim.pickup[0].time_slot_list[x].date+"</option>");
                            }
                             
                            var cb_pickup_area = "";
                            for(var x = 0 ; x < dataKirim.pickup.length ; x++)
                            {
                                var selected = "";
                                if(x == 0)
                                {
                                    selected = "selected";
                                    $("#addressKirimShopee").val(dataKirim.pickup[x].address_id);
                                }
                                
                                var desc = "";
                                for(var y = 0 ; y < dataKirim.pickup[x].address_flag.length; y++)
                                {
                                    if(y != 0)
                                    {
                                        desc += "&nbsp;,&nbsp;&nbsp;&nbsp;";
                                    }
                                    desc += dataKirim.pickup[x].address_flag[y].toUpperCase();
                                }
                                
                                cb_pickup_area += (`<option data-desc='`+desc+`' value='`+x+`|`+dataKirim.pickup[x].address_id+`' `+selected+`>`+dataKirim.pickup[x].address+`</option>`);
                            }
                            
                            var cbJemputShopee = "";
                            if(rows[0].KODEPENGAMBILAN != "")
                            {
                                cbJemputShopee += ` <option value="PICK_UP" selected>Request Pickup</option>`;
                            }
                            else
                            {
                                cbJemputShopee += `	<option value="DROP_OFF" selected>Drop Off</option>
                                          			<option value="PICK_UP">Request Pickup</option>`;
                            }
                            
                            var dataKirim = ` <tr>
                                    	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px" >`+rows[indexKirim].KURIR+`</td>
                                      	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px">`+rows[indexKirim].KODEPESANAN+`</td>
                                      	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="80px">`+currency(rows[indexKirim].TOTALBARANG)+`</td>
                                      	<td style="vertical-align:top; text-align:left;" width="280px">	
                                      	    <select id="cb_penjemputan_shopee`+indexKirim+`"class="form-control "  style="width:280px; " panelHeight="auto" required="true">
                                          		`+cbJemputShopee+`
                                          	</select>
                                      		<select id="cb_pickup_area_shopee`+indexKirim+`" class="form-control select2" style="margin-top:10px; "  panelHeight="auto" required="true">
                                          		 `+cb_pickup_area+`
                                          	</select>
                                          	<select id="cb_pickup_shopee`+indexKirim+`" class="form-control "  style="width:280px; " panelHeight="auto" required="true">
                                          	      `+cb_pickup+`
                                          	</select>
                                        </td>
                                      	<td style="vertical-align:top; text-align:left; padding-top:17px;" id="editNoteShopeeDiv`+indexKirim+`">`+rows[indexKirim].CATATANJUAL+`</td>
                                    </tr>`;
                            
                            $(".table-responsive-shopee-kirim").append(dataKirim);
                            
                       
                            $('.select2').select2({
                              width: '280px',
                              templateResult: select2TemplateResult,
                              templateSelection: select2TemplateSelection,
                              escapeMarkup: function(markup) { return markup; }
                            });
                            
                            $("#rowDataPengirimanShopee").val(JSON.stringify(rows));
                            $('#editNoteShopeeDiv'+indexKirim).find('#editNoteShopee').click(function(){
                               $("#fromNoteShopee").val("KIRIMSHOPEE_"+indexKirim);
                                catatanPenjualShopee();
                            });
                            
                            if(row.KODEPENGAMBILAN != "")
                            {
                                $("#cb_pickup_shopee"+indexKirim).show();
                                $("#cb_pickup_area_shopee"+indexKirim).next('.select2-container').show();
                            }
                            else
                            {
                                
                                $("#cb_pickup_shopee"+indexKirim).hide();
                                $("#cb_pickup_area_shopee"+indexKirim).next('.select2-container').hide();
                            }
                            
                            
                            $("#cb_penjemputan_shopee"+indexKirim).change(function(){
                                if($(this).val() == "DROP_OFF")
                                {
                                    $("#cb_pickup_shopee"+indexKirim).hide();
                                    $("#cb_pickup_area_shopee"+indexKirim).next('.select2-container').hide();
                                    $("#addressKirimShopee").val("");
                                    $("#pickupKirimShopee").val("");
                                }
                                else if($(this).val() == "PICK_UP")
                                {
                                    $("#cb_pickup_shopee"+indexKirim).show();
                                    $("#cb_pickup_area_shopee"+indexKirim).next('.select2-container').show();
                                }
                            })
                            
                            $("#cb_pickup_area_shopee"+indexKirim).change(function(){
                                var indexPickupTime = $(this).val().split("|")[0];
                                $("#addressKirimShopee").val($(this).val().split("|")[1]);
                                var dataKirim = JSON.parse($("#jadwalKirimShopee").val());
                                var cb_pickup = "";
                                for(var x = 0 ; x < dataKirim.pickup[indexPickupTime].time_slot_list.length ; x++)
                                {
                                    var selected = "";
                                    if(x == 0)
                                    {
                                        selected = "selected";
                                        $("#pickupKirimShopee").val(dataKirim.pickup[indexPickupTime].time_slot_list[x].pickup_time_id);
                                    }
                                    cb_pickup += ("<option value='"+dataKirim.pickup[indexPickupTime].time_slot_list[x].pickup_time_id+"' "+selected+">"+(dataKirim.pickup[indexPickupTime].time_slot_list[x].time_text == null?"":(dataKirim.pickup[indexPickupTime].time_slot_list[x].time_text+", "))+dataKirim.pickup[indexPickupTime].time_slot_list[x].date+"</option>");
                                }
                                
                                $("#cb_pickup_shopee"+indexKirim).html(cb_pickup);
                            })
                            
                            $("#cb_pickup_shopee"+indexKirim).change(function(){
                                $("#pickupKirimShopee").val($(this).val());
                            })
                 	}
                 });
        }
    }});
 
}

function kirimKonfirmShopee(){
    
    Swal.fire({
        title: 'Anda Yakin Mengirim Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var row = JSON.parse($("#rowDataShopee").val());
                var rows = [row];
                
                rows[0].METHOD = $("#cb_penjemputan_shopee0").val();
                if(rows[0].METHOD == "DROP_OFF")
                {
                    rows[0].ADDRESS = "";
                    rows[0].PICKUP = "";
                }
                else
                {
                    rows[0].ADDRESS = $("#cb_pickup_area_shopee0").val().split("|")[1];
                    rows[0].PICKUP = $("#cb_pickup_shopee0").val();
                }
                
                loading();
                $.ajax({
                 	type    : 'POST',
                 	url     : base_url+'Shopee/kirim/',
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
                             $("#modal-kirim-shopee").modal('hide');
                            	
                          	setTimeout(() => {
                            reloadShopee();
                          }, "2000");
                 	}
                 });
        	}
        });
}

function kirimShopeeSemua(){
    loading();
     $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/cekStokLokasi/',
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
                $("#pilihKirimanAllKurirShopee").prop('checked',true);
                $("#modal-kirim-all-shopee").modal('show');
                $('#tab_kirim_shopee a:first').tab('show');
                var data = $("#dataGridShopee1").DataTable().rows().data();
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
                    $("#keteranganKurirShopee").show();
                    $("#countAturSemuaPengirimanShopee").html("("+dataSimpan.length.toString()+")");
                }
                else
                {
                    $("#keteranganKurirShopee").hide();
                    $("#countAturSemuaPengirimanShopee").html("");
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
                
                
                $("#countAllPesananShopee").html(dataSimpan.length.toString());
                $("#countAllKurirShopee").html(dataPerKurir.length.toString());
                
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
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; padding-top:15px; text-align:center;" width="45px"><input type="checkbox" id="pilihKirimanAllShopee_`+y+`" checked></td>
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; padding-top:12px; text-align:left; font-weight:bold; font-size:14pt;" width="230px" colspan='2' >`+dataPerKurir[y]["name"]+`</td>
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; text-align:center;" width="350px"  id="aturKurirAllShopee_`+y+`" ></td>
                        			    	<td style="background:#CFECF7; border-color:#CFECF7; vertical-align:top; text-align:center;" ><div style="width:250px; margin-top:2px;">Terdapat &nbsp;<span id="countKurirAllShopee_`+y+`" style="font-weight:bold; font-size:14pt; ">...</span>&nbsp; Pesanan</div></td>
                        			    </tr>
                        		    </table>
                                	<table id="dataGridDetailAllShopeeKirim`+y+`" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
                        				<thead>
                        					<tr>
                        						<th style="vertical-align:middle; text-align:center;" width="70px"></th>
                        						<th style="vertical-align:middle; text-align:center;" width="150" >No. Pesanan</th>
                        						<th style="vertical-align:middle; text-align:center;" width="80px">T. Qty</th>
                        						<th style="vertical-align:middle; text-align:center;" width="350px" >Atur Lokasi, Tanggal, & Waktu Jemput</th>
                        						<th style="vertical-align:middle; text-align:center;" >Catatan Penjual</th>
                        					</tr>
                        				</thead>
                        				<tbody class="table-responsive-shopee-all-kirim_`+y+`">
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
                
                $("#dataGridDetailAllRegularShopee").html(detailPesananRegular);
                $("#dataGridDetailAllInstantShopee").html(detailPesananInstant);
                
                $("#pilihKirimanAllKurirShopee").change(function(){
                    for(var index = 0 ; index < dataPerKurir.length ; index++)
                    {
                        $("#pilihKirimanAllShopee_"+index).prop("checked",$(this).prop("checked"));
                         
                        for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                        {
                            $(".table-responsive-shopee-all-kirim_"+index+" #pilihKirimanShopee_"+c).prop("checked",$(this).prop("checked"));
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
                     	url     : base_url+'Shopee/setKirim/',
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
                     	    $("#countKurirAllShopee_"+msg.index).html(items.length.toString());
                     	    for(var indexKirim = 0; indexKirim < items.length;indexKirim++)
                     	    {
                            
                                var dataKirim = msg;
                          
                           
                                $(".table-responsive-shopee-kirim").html('');
                                var cb_pickup = "";
                                var cb_pickup_all = `<option value="" selected>-Pilih Waktu Pickup-</option>`;
                                for(var x = 0 ; x < dataKirim.pickup[0].time_slot_list.length ; x++)
                                {
                                    var selected = "";
                                    if(x == 0)
                                    {
                                        selected = "selected";
                                    }
                                    cb_pickup_all += ("<option value='"+dataKirim.pickup[0].time_slot_list[x].pickup_time_id+"' "+selected+">"+(dataKirim.pickup[0].time_slot_list[x].time_text == null?"":(dataKirim.pickup[0].time_slot_list[x].time_text+", "))+dataKirim.pickup[0].time_slot_list[x].date+"</option>");
                                    cb_pickup += ("<option value='"+dataKirim.pickup[0].time_slot_list[x].pickup_time_id+"' "+selected+">"+(dataKirim.pickup[0].time_slot_list[x].time_text == null?"":(dataKirim.pickup[0].time_slot_list[x].time_text+", "))+dataKirim.pickup[0].time_slot_list[x].date+"</option>");
                                }
                                 
                                var cb_pickup_area = "";
                                var cb_pickup_area_all = `<option data-desc="" value="" selected>-Pilih Lokasi Pickup-</option>`;
                                for(var x = 0 ; x < dataKirim.pickup.length ; x++)
                                {
                                    var selected = "";
                                    if(x == 0)
                                    {
                                        selected = "selected";
                                    }
                                    
                                    var desc = "";
                                    for(var y = 0 ; y < dataKirim.pickup[x].address_flag.length; y++)
                                    {
                                        if(y != 0)
                                        {
                                            desc += "&nbsp;,&nbsp;&nbsp;&nbsp;";
                                        }
                                        desc += dataKirim.pickup[x].address_flag[y].toUpperCase();
                                    }
                                    
                                    cb_pickup_area_all += (`<option data-desc='`+desc+`' value='`+x+`|`+dataKirim.pickup[x].address_id+`' `+selected+`>`+dataKirim.pickup[x].address+`</option>`);
                                    cb_pickup_area += (`<option data-desc='`+desc+`' value='`+x+`|`+dataKirim.pickup[x].address_id+`' `+selected+`>`+dataKirim.pickup[x].address+`</option>`);
                                }
                                
                                var cbJemputShopee = "";
                                var cbJemputShopeeAll = "";
                                if(dataPerKurir[msg.index]['order'][0].KODEPENGAMBILAN != "")
                                {
                                    cbJemputShopeeAll += `<option value="PICK_UP" selected>Request Pickup</option>`;
                                    
                                    cbJemputShopee += `<option value="PICK_UP" selected>Request Pickup</option>`;
                                }
                                else
                                {
                                    cbJemputShopeeAll += `<option value="">-Pilih Metode-</option>
                                                         <option value="DROP_OFF" selected>Drop Off</option>
                                    	                 <option value="PICK_UP">Request Pickup</option>`;
                                    	                 
                                    cbJemputShopee += `	<option value="DROP_OFF" selected>Drop Off</option>
                                              			<option value="PICK_UP">Request Pickup</option>`;
                                }
                                
                                detailKirim += ` <tr>
                                        	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="70px" ><input type="checkbox" id="pilihKirimanShopee_`+indexKirim+`" width="30px" checked></td>
                                          	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px">`+items[indexKirim].KODEPESANAN+`</td>
                                          	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="80px">`+currency(items[indexKirim].TOTALBARANG)+`</td>
                                          	<td style="vertical-align:top; text-align:left;" width="350px">	
                                          	    <select id="cb_penjemputan_shopee_`+indexKirim+`"class="form-control "  style="width:332px" panelHeight="auto" required="true">
                                              		`+cbJemputShopee+`
                                              	</select>
                                          		<select id="cb_pickup_area_shopee_`+indexKirim+`" class="form-control select2" style="margin-top:10px;"  panelHeight="auto" required="true">
                                              		 `+cb_pickup_area+`
                                              	</select>
                                              	<select id="cb_pickup_shopee_`+indexKirim+`" class="form-control "  panelHeight="auto" required="true">
                                              	      `+cb_pickup+`
                                              	</select>
                                            </td>
                                    		<input type="hidden" id="jadwalKirimShopee_`+indexKirim+`">
                                    		<input type="hidden" id="pickupKirimShopee_`+indexKirim+`">
                                    		<input type="hidden" id="addressKirimShopee_`+indexKirim+`">
                                          	<td style="vertical-align:top; text-align:left; padding-top:17px;" id="editNoteShopeeDiv_`+indexKirim+`">`+items[indexKirim].CATATANJUAL+`</td>
                                        </tr>`;
                     	    }
                     	    
                     	    $("#aturKurirAllShopee_"+msg.index).html(`
                     	        <select id="cb_penjemputan_all_shopee_`+msg.index+`"class="form-control "  style="width:332px" panelHeight="auto" required="true">
                                	`+cbJemputShopeeAll+`
                                </select>
                                <select id="cb_pickup_area_all_shopee_`+msg.index+`" class="form-control select2 " style="margin-top:10px;"  panelHeight="auto" required="true">
                                	 `+cb_pickup_area_all+`
                                </select>
                                <select id="cb_pickup_all_shopee_`+msg.index+`" class="form-control "  panelHeight="auto" required="true">
                                      `+cb_pickup_all+`
                                </select>
                     	    `);
                     	    
                            $(".table-responsive-shopee-all-kirim_"+msg.index).html(detailKirim);
            
                            $("#cb_pickup_area_all_shopee_"+msg.index).select2({
                              width: '332px',
                              templateResult:select2TemplateResult,
                              templateSelection: select2TemplateSelection,
                              escapeMarkup: function(markup) { return markup; }
                            });
                            
                            $("#pilihKirimanAllShopee_"+msg.index).change(function(){
                                var index = msg.index; 
                                for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                {
                                    $(".table-responsive-shopee-all-kirim_"+index+" #pilihKirimanShopee_"+c).prop("checked",$(this).prop("checked"));
                                }
                                
                                var count = 0;
                                for(var index = 0 ; index < dataPerKurir.length ; index++)
                                {
                                    if($("#pilihKirimanAllShopee_"+index).prop("checked"))
                                    {
                                        count++;
                                    }
                                }
                                
                                if(count == dataPerKurir.length)
                                {
                                     $("#pilihKirimanAllKurirShopee").prop("checked",true);
                                }
                                else
                                {
                                     $("#pilihKirimanAllKurirShopee").prop("checked",false);
                                }
                                
                                recountPengiriman();
                            });
                            
                            $("#cb_penjemputan_all_shopee_"+msg.index).change(function(){
                                if($(this).val() != "")
                                {
                                    var index = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    if($(this).val() == "DROP_OFF")
                                    {
                                        $("#cb_pickup_all_shopee_"+index).hide();
                                        $("#cb_pickup_area_all_shopee_"+index).next('.select2-container').hide();
                                        $("#cb_pickup_all_shopee_"+index).val("");
                                        $("#cb_pickup_area_all_shopee_"+index).val("");
                                        $("#cb_pickup_area_all_shopee_"+index).trigger('change');
                                        
                                        for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                        {
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_shopee_"+c).hide();
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_area_shopee_"+c).next('.select2-container').hide();
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_area_shopee_"+c).trigger('change');
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_penjemputan_shopee_"+c).val($(this).val());
                                        }
                                    }
                                    else if($(this).val() == "PICK_UP")
                                    {
                                        $("#cb_pickup_all_shopee_"+index).show();
                                        $("#cb_pickup_area_all_shopee_"+index).next('.select2-container').show();
                                        
                                        for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                        {
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_shopee_"+c).show();
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_area_shopee_"+c).next('.select2-container').show();
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_area_shopee_"+c).trigger('change');
                                            $(".table-responsive-shopee-all-kirim_"+index+" #cb_penjemputan_shopee_"+c).val($(this).val());
                                        }
                                    }
                                }
                                
                                recountPengiriman();
                            })
                            
                            $("#cb_pickup_area_all_shopee_"+msg.index).change(function(){
                                if($(this).val() != "")
                                {
                                    var indexPickupTime = $(this).val().split("|")[0];
                                    var index = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                    {
                                        $(".table-responsive-shopee-all-kirim_"+index+" #addressKirimShopee_"+c).val($(this).val().split("|")[1]);
                                        var dataKirim = JSON.parse($(".table-responsive-shopee-all-kirim_"+index+" #jadwalKirimShopee_"+c).val());
                                        var cb_pickup = "";
                                        for(var x = 0 ; x < dataKirim.pickup[indexPickupTime].time_slot_list.length ; x++)
                                        {
                                            var selected = "";
                                            if(x == 0)
                                            {
                                                selected = "selected";
                                                $(".table-responsive-shopee-all-kirim_"+index+" #pickupKirimShopee_"+c).val(dataKirim.pickup[indexPickupTime].time_slot_list[x].pickup_time_id);
                                            }
                                            cb_pickup += ("<option value='"+dataKirim.pickup[indexPickupTime].time_slot_list[x].pickup_time_id+"' "+selected+">"+(dataKirim.pickup[indexPickupTime].time_slot_list[x].time_text == null?"":(dataKirim.pickup[indexPickupTime].time_slot_list[x].time_text+", "))+dataKirim.pickup[indexPickupTime].time_slot_list[x].date+"</option>");
                                        }
                                        
                                        $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_shopee_"+c).html(cb_pickup);
                                        $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_area_shopee_"+c).val($(this).val());
                                        $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_area_shopee_"+c).trigger('change');
                                    }
                                }
                                
                                recountPengiriman();
                            })
                            
                            $("#cb_pickup_all_shopee_"+msg.index).change(function(){
                                if($(this).val() != "")
                                {
                                    var index = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    $(".table-responsive-shopee-all-kirim_"+index+" #pickupKirimShopee_"+c).val($(this).val());
                                    for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                    {
                                        $(".table-responsive-shopee-all-kirim_"+index+" #cb_pickup_shopee_"+c).val($(this).val());
                                    }
                                }
                                
                                recountPengiriman();
                            })
                            
                            //CHILD
                            
                            for(var indexKirim = 0; indexKirim < items.length;indexKirim++)
                     	    {    
                     	        
                     	    $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_area_shopee_"+indexKirim).select2({
                              width: '332px',
                              templateResult:select2TemplateResult,
                              templateSelection: select2TemplateSelection,
                              escapeMarkup: function(markup) { return markup; }
                            });
                            
                                for(var x = 0 ; x < dataKirim.pickup[0].time_slot_list.length ; x++)
                                {
                                    if(x == 0)
                                    {
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #pickupKirimShopee_"+indexKirim).val(dataKirim.pickup[0].time_slot_list[x].pickup_time_id);
                                    }
                                }
                                for(var x = 0 ; x < dataKirim.pickup.length ; x++)
                                {
                                    if(x == 0)
                                    {
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #addressKirimShopee_"+indexKirim).val(dataKirim.pickup[x].address_id);
                                    }
                                }
                                
                                $(".table-responsive-shopee-all-kirim_"+msg.index+" #editNoteShopeeDiv_"+indexKirim).find('#editNoteShopee').click(function(){
                                  var indexKirim = this.parentNode.id.split("_")[this.parentNode.id.split("_").length-1];
                                  
                                  $("#rowDataPengirimanShopee").val(JSON.stringify(dataPerKurir[$(this).closest('tbody').attr("class").split("_")[1]]['order']));
                                  $("#fromNoteShopee").val("KIRIMSHOPEE_"+indexKirim+"_"+$(this).closest('tbody').attr("class").split("_")[1]);
                                    catatanPenjualShopee();
                                });
                                
                     	        $(".table-responsive-shopee-all-kirim_"+msg.index+" #jadwalKirimShopee_"+indexKirim).val(JSON.stringify(msg));
                                
                                if(dataPerKurir[msg.index]['order'][0].KODEPENGAMBILAN != "")
                                {
                                    $("#cb_pickup_all_shopee_"+msg.index).show();
                                    $("#cb_pickup_area_all_shopee_"+msg.index).next('.select2-container').show();
                                    
                                    $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_shopee_"+indexKirim).show();
                                    $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_area_shopee_"+indexKirim).next('.select2-container').show();
                                }
                                else
                                {
                                    $("#cb_pickup_all_shopee_"+msg.index).hide();
                                    $("#cb_pickup_area_all_shopee_"+msg.index).next('.select2-container').hide();
                                    
                                    $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_shopee_"+indexKirim).hide();
                                    $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_area_shopee_"+indexKirim).next('.select2-container').hide();
                                }
                                
                                
                                $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_penjemputan_shopee_"+indexKirim).change(function(){
                                    var indexKirim = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    if($(this).val() == "DROP_OFF")
                                    {
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_shopee_"+indexKirim).hide();
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_area_shopee_"+indexKirim).next('.select2-container').hide();
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #addressKirimShopee_"+indexKirim).val("");
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #pickupKirimShopee_"+indexKirim).val("");
                                    }
                                    else if($(this).val() == "PICK_UP")
                                    {
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_shopee_"+indexKirim).show();
                                        $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_area_shopee_"+indexKirim).next('.select2-container').show();
                                    }
                                  
                                        
                                    if(dataPerKurir[msg.index]['order'][0].KODEPENGAMBILAN == "")
                                    {
                                        $("#cb_penjemputan_all_shopee_"+msg.index).val("");
                                        $("#cb_pickup_area_all_shopee_"+msg.index).next('.select2-container').hide();
                                        $("#cb_pickup_area_all_shopee_"+msg.index).trigger('change');
                                        $("#cb_pickup_all_shopee_"+msg.index).hide();
                                    }
                                    
                                    
                                    $("#cb_pickup_area_all_shopee_"+msg.index).val("");
                                    $("#cb_pickup_area_all_shopee_"+msg.index).trigger('change');
                                    $("#cb_pickup_all_shopee_"+msg.index).val("");
                                    
                                    
                                    recountPengiriman();
                                })
                                
                                $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_area_shopee_"+indexKirim).change(function(){
                                   
                                    var indexPickupTime = $(this).val().split("|")[0];
                                    var indexKirim = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    $(".table-responsive-shopee-all-kirim_"+msg.index+" #addressKirimShopee_"+indexKirim).val($(this).val().split("|")[1]);
                                    var dataKirim = JSON.parse($(".table-responsive-shopee-all-kirim_"+msg.index+" #jadwalKirimShopee_"+indexKirim).val());
                                    var cb_pickup = "";
                                    for(var x = 0 ; x < dataKirim.pickup[indexPickupTime].time_slot_list.length ; x++)
                                    {
                                        var selected = "";
                                        if(x == 0)
                                        {
                                            selected = "selected";
                                            $(".table-responsive-shopee-all-kirim_"+msg.index+" #pickupKirimShopee_"+indexKirim).val(dataKirim.pickup[indexPickupTime].time_slot_list[x].pickup_time_id);
                                        }
                                        cb_pickup += ("<option value='"+dataKirim.pickup[indexPickupTime].time_slot_list[x].pickup_time_id+"' "+selected+">"+(dataKirim.pickup[indexPickupTime].time_slot_list[x].time_text == null?"":(dataKirim.pickup[indexPickupTime].time_slot_list[x].time_text+", "))+dataKirim.pickup[indexPickupTime].time_slot_list[x].date+"</option>");
                                    }
                                    
                                    $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_shopee_"+indexKirim).html(cb_pickup);
                                    
                                    if( $("#cb_pickup_area_all_shopee_"+msg.index).val() != $(this).val() && $("#cb_pickup_area_all_shopee_"+msg.index).val() != "")
                                    {    
                                        if(dataPerKurir[msg.index]['order'][0].KODEPENGAMBILAN == "")
                                        {
                                            $("#cb_penjemputan_all_shopee_"+msg.index).val("");
                                            $("#cb_pickup_area_all_shopee_"+msg.index).next('.select2-container').hide();
                                            $("#cb_pickup_all_shopee_"+msg.index).hide();
                                        }
                                    
                                       
                                        $("#cb_pickup_area_all_shopee_"+msg.index).val("");
                                        $("#cb_pickup_area_all_shopee_"+msg.index).trigger('change');
                                        $("#cb_pickup_all_shopee_"+msg.index).val("");
                                    }
                                    
                                    recountPengiriman();
                                })
                                
                                $(".table-responsive-shopee-all-kirim_"+msg.index+" #cb_pickup_shopee_"+indexKirim).change(function(){
                                    var indexKirim = $(this).attr("id").split("_")[$(this).attr("id").split("_").length-1];
                                    $(".table-responsive-shopee-all-kirim_"+msg.index+" #pickupKirimShopee_"+indexKirim).val($(this).val());
                                        
                                    if(dataPerKurir[msg.index]['order'][0].KODEPENGAMBILAN == "")
                                    {
                                        $("#cb_penjemputan_all_shopee_"+msg.index).val("");
                                        $("#cb_pickup_area_all_shopee_"+msg.index).next('.select2-container').hide();
                                        $("#cb_pickup_all_shopee_"+msg.index).hide();
                                    }
                                    
                                    $("#cb_pickup_area_all_shopee_"+msg.index).val("");
                                    $("#cb_pickup_area_all_shopee_"+msg.index).trigger('change');
                                    $("#cb_pickup_all_shopee_"+msg.index).val("");
                                    
                                    
                                    recountPengiriman();
                                })
                                
                                 $(".table-responsive-shopee-all-kirim_"+msg.index+" #pilihKirimanShopee_"+indexKirim).change(function(){
                                    var index = msg.index; 
                                    var count = 0;
                                    for(var c = 0 ; c < dataPerKurir[index]['order'].length; c++)
                                    {
                                        if($(".table-responsive-shopee-all-kirim_"+index+" #pilihKirimanShopee_"+c).prop("checked"))
                                        {
                                            count++;
                                        }
                                    }
                                    
                                    if(count == dataPerKurir[index]['order'].length)
                                    {
                                         $("#pilihKirimanAllShopee_"+index).prop("checked",true);
                                    }
                                    else
                                    {
                                         $("#pilihKirimanAllShopee_"+index).prop("checked",false);
                                    }
                                    
                                    var count = 0;
                                    for(var index = 0 ; index < dataPerKurir.length ; index++)
                                    {
                                        if($("#pilihKirimanAllShopee_"+index).prop("checked"))
                                        {
                                            count++;
                                        }
                                    }
                                    
                                    if(count == dataPerKurir.length)
                                    {
                                         $("#pilihKirimanAllKurirShopee").prop("checked",true);
                                    }
                                    else
                                    {
                                         $("#pilihKirimanAllKurirShopee").prop("checked",false);
                                    }
                                    
                                    recountPengiriman();
                                });
                     	    }
                     	}
                     });
               }
            }
        }
     });
}

function recountPengiriman() {
    var data = $("#dataGridShopee1").DataTable().rows().data();
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
                if($(".table-responsive-shopee-all-kirim_"+y+" #pilihKirimanShopee_"+index).prop('checked'))
                {
                    countSemuaPengiriman++;
                }
            index++;
            }
        }
    }
    if(countSemuaPengiriman > 0)
    {
        $("#countAturSemuaPengirimanShopee").html("("+countSemuaPengiriman.toString()+")");
    }
    else
    {
        $("#countAturSemuaPengirimanShopee").html("");
    }
}

function kirimKonfirmAllShopee(){
    Swal.fire({
        title: 'Anda Yakin Mengirim Semua Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var data = $("#dataGridShopee1").DataTable().rows().data();
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
                            if($(".table-responsive-shopee-all-kirim_"+y+" #pilihKirimanShopee_"+index).prop('checked'))
                            {
                                
                                dataSimpan[x]['METHOD'] = $(".table-responsive-shopee-all-kirim_"+y+" #cb_penjemputan_shopee_"+index).val();
                                if(dataSimpan[x]['METHOD'] == "DROP_OFF")
                                {
                                    dataSimpan[x].ADDRESS = "";
                                    dataSimpan[x].PICKUP = "";
                                }
                                else
                                {
                                    dataSimpan[x]['ADDRESS'] =  $(".table-responsive-shopee-all-kirim_"+y+" #addressKirimShopee_"+index).val();
                                    dataSimpan[x]['PICKUP'] = $(".table-responsive-shopee-all-kirim_"+y+" #pickupKirimShopee_"+index).val();
                                }
                                
                                rows.push(dataSimpan[x]);
                                
                            }
                        index++;
                        }
                    }
                }
                
                loading();
                $.ajax({
                 	type    : 'POST',
                 	url     : base_url+'Shopee/kirim/',
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
                             $("#modal-kirim-all-shopee").modal('hide');
                            	
                          	setTimeout(() => {
                            reloadShopee();
                          }, "2000");
                 	}
                 });
        	}
    });
}

function lacakShopee(){
    $("#modal-form-shopee").modal('hide');
    var row = JSON.parse($("#rowDataShopee").val());
    
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/setLacak/',
    	data    : {kode: row.KODEPESANAN, kodepackaging:row.KODEPACKAGING},
    	dataType: 'json',
    	success : function(msg){
    	        Swal.close();
    	      
            
                $("#modal-lacak-shopee").modal('show');
                
                $("#NOSHOPEELACAK").html("#"+row.KODEPESANAN);
                $("#KURIRLACAKSHOPEE").html(row.KURIR);
                $("#METODEBAYARLACAKSHOPEE").html(row.METODEBAYAR);
                $("#RESILACAKSHOPEE").html(row.RESI);
                $("#ALAMATLACAKSHOPEE").html(row.BUYERALAMAT);
                
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
                    
                    if(msg[x]['logistics_status'] == "PICKED_UP")
                    {
                       //PASTI YANG INDEX TERAKHIR
                        $("#TGLKIRIMLACAKSHOPEE").html(msg[x]['update_time']); 
                    }
                }
                
                
                $(".step-tracker").html(stepTracker);
            
    	}
    });
}

function hapusShopee(){
    $("#modal-form-shopee").modal('hide');
    var row = JSON.parse($("#rowDataShopee").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/loadDetail/',
    	data    : {kode: row.KODEPESANAN},
    	dataType: 'json',
    	success : function(msg){
    	    $('#cb_alasan_pembatalan_shopee').val('');
            $("#NOSHOPEEBATAL").html("#"+row.KODEPESANAN);
            $(".table-responsive-shopee-batal").html('');
            
            var totalCurr = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
              var namaBarang = msg.DETAILBARANG[x].NAMA;
              if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
              {
                  namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
              }
          
                $(".table-responsive-shopee-batal").append(setDetail(msg.DETAILBARANG,x,namaBarang,false));
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
            }
            $("#TOTALQTYSHOPEEBATAL").html(currency(totalCurr));
            $("#SUBTOTALSHOPEEBATAL").html(currency(msg.SUBTOTALBELI));
            Swal.close();
            $("#itemBatalShopee").val(JSON.stringify(msg.DETAILBARANG));
            $("#modal-alasan-shopee").modal('show');
    	}
    });
}

function hapusKonfirmShopee(){
    Swal.fire({
        title: 'Anda Yakin Membatalkan Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                if($('#cb_alasan_pembatalan_shopee').val() == "")
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
                    $("#modal-alasan-shopee").modal('hide');
                    loading()
                     $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Shopee/hapus/',
                    	data    : {kode: $("#NOSHOPEEBATAL").text().split("#")[1], dataItem: $("#itemBatalShopee").val(),alasan:$('#cb_alasan_pembatalan_shopee').val()},
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
                                 reloadShopee();
                               }, "2000");
                    	}
                    });
                
                }
        	}
        });
}

function sinkronShopeeNow(){
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
                	url     : base_url+'Shopee/cekStokLokasi/',
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
                            totalPesananShopeeAll = 0;
                            sinkronShopeeState = true;
                            var dateNow = "<?=date('Y-m-d')?>";
                             $.ajax({
                            	type    : 'GET',
                            	url     : base_url+'Shopee/init/'+dateNow+'/'+dateNow+'/update',
                            	dataType: 'json',
                            	success : function(msg){
                            	    totalPesananShopeeAll = msg.total;
                            	    
                                    var indexTab = 0;
                                    var tabs = document.querySelectorAll('#tab_transaksi_shopee li');

                                    tabs.forEach(function(tab, index) {
                                        if (tab.classList.contains('active')) {
                                            indexTab = (index+1);
                                        }
                                    });
                                    
                                    for(var x = 1; x <= 4 ; x++)
                                    {
                                        if(x != indexTab)
                                        {
                                            doneSinkronShopee[x] = false;
                                            changeTabShopee(x);
                                        }
                                    }
                                    
                                    doneSinkronShopee[indexTab] = false;
                                    changeTabShopee(indexTab);
                            }});
                        }
                	}
                });
        	}
        });
}

function sinkronShopee(){
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
                	url     : base_url+'Shopee/cekStokLokasi/',
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
                            totalPesananShopeeAll = 0;
                            sinkronShopeeState = true;
                            const date = new Date();
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                            const day = String(date.getDate()).padStart(2, '0');
                            
                            const formattedDate = `${year}-${month}-${day}`;
                            
                             $.ajax({
                            	type    : 'GET',
                            	url     : base_url+'Shopee/init/'+ "<?=TGLAWALFILTERMARKETPLACE?>"+"/"+formattedDate,
                            	dataType: 'json',
                            	success : function(msg){
                            	    totalPesananShopeeAll = msg.total;
                            	    
                                    var indexTab = 0;
                                    var tabs = document.querySelectorAll('#tab_transaksi_shopee li');

                                    tabs.forEach(function(tab, index) {
                                        if (tab.classList.contains('active')) {
                                            indexTab = (index+1);
                                        }
                                    });
                                    
                                    for(var x = 1; x <= 4 ; x++)
                                    {
                                        if(x != indexTab)
                                        {
                                            doneSinkronShopee[x] = false;
                                            changeTabShopee(x);
                                        }
                                    }
                                    
                                    doneSinkronShopee[indexTab] = false;
                                    changeTabShopee(indexTab);
                            }});
                        }
                	}
                });
        	}
        });
}

function catatanPenjualShopee(){
    var row;
    if($("#fromNoteShopee").val().split("_")[0] == "GRID")
    {
        row = JSON.parse($("#rowDataShopee").val());
    }
    else
    {
        var rows = JSON.parse($("#rowDataPengirimanShopee").val());
        row = rows[$("#fromNoteShopee").val().split("_")[1]];
    }

    
    $("#NOSHOPEECATATAN").html("#"+row.KODEPESANAN);
    $("#note_shopee").val(row.CATATANJUALRAW);
    $("#modal-note-shopee").modal("show");
}

function noteKonfirmShopee(){
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
                 	url     : base_url+'Shopee/catatanPenjual/',
                 	data    : {kode: $("#NOSHOPEECATATAN").text().split("#")[1], note: $("#note_shopee").val()},
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
                                $("#modal-note-shopee").modal('hide');
                                if($("#fromNoteShopee").val().split("_")[0] == "KIRIMSHOPEE")
                                {
                                    var indexKirim = $("#fromNoteShopee").val().split("_")[1];
                                    
                                    var rows = JSON.parse($("#rowDataPengirimanShopee").val());
                                    rows[indexKirim]['CATATANJUAL'] = `<i class='fa fa-edit' id='editNoteShopee' style='cursor:pointer;'></i>
                                          <div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                        white-space: -moz-pre-wrap; /* Firefox */    
                                                        white-space: -pre-wrap;     /* Opera <7 */   
                                                        white-space: -o-pre-wrap;   /* Opera 7 */    
                                                        word-wrap: break-word;      /* IE */'>`+$("#note_shopee").val()+`</div>`;
                                    rows[indexKirim]['CATATANJUALRAW'] = $("#note_shopee").val();
                                    
                                    
                                    if($("#fromNoteShopee").val().split("_").length == 3)
                                    {
                                        var index = $("#fromNoteShopee").val().split("_")[2];
                                        
                                        $(".table-responsive-shopee-all-kirim_"+index+" #editNoteShopeeDiv_"+indexKirim).html(rows[indexKirim]['CATATANJUAL']);
                                        
                                        $("#rowDataPengirimanShopee").val(JSON.stringify(rows));
                                        $(".table-responsive-shopee-all-kirim_"+index+" #editNoteShopeeDiv_"+indexKirim).find('#editNoteShopee').click(function(){
                                           $("#fromNoteShopee").val("KIRIMSHOPEE_"+indexKirim+"_"+index);
                                           catatanPenjualShopee();
                                        });
                                    }
                                    else
                                    {
                                        $('#editNoteShopeeDiv'+indexKirim).html(rows[indexKirim]['CATATANJUAL']);
                                        
                                        $("#rowDataPengirimanShopee").val(JSON.stringify(rows));
                                        $('#editNoteShopeeDiv'+indexKirim).find('#editNoteShopee').click(function(){
                                           $("#fromNoteShopee").val("KIRIMSHOPEE_"+indexKirim);
                                           catatanPenjualShopee();
                                        });
                                    }
                                }
                             	
                             	setTimeout(() => {
                                  reloadShopee();
                                }, "2000");
                            }
                 	}
                 });
        	}
        });
}

function returBarangShopee(){
    $("#modal-pengembalian-shopee").modal("hide");
    var row = JSON.parse($("#rowDataShopee").val());
    
    Swal.fire({
        title: 'Anda Yakin Merubah Pengembalian Dana menjadi Barang ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                $.ajax({
                	type    : 'POST',
                	url     : base_url+'Shopee/setReturBarang/',
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
                                reloadShopee();
                            }, "2000");
                        }
                	}
                });
        	}
        });
    }
}

function returShopee(){
    $("#modal-pengembalian-shopee").modal("hide");
    var row = JSON.parse($("#rowDataShopee").val());
    var rowDetail = JSON.parse($("#dataReturShopee").val());
    loading();
    
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/cekStokLokasi/',
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
                	url     : base_url+'Shopee/getSolution/',
                	data    : {kode: row.KODEPENGEMBALIAN, detailRetur : $("#dataReturShopee").val()},
                	dataType: 'json',
                	success : function(msg){
                        $("#returShopeeWaitResponse").hide();
                        $("#returAcceptNegotiationShopee").hide();
                        $("#returAcceptNegotiationShopee").css("width","100%");
                        $("#returNegotiationShopee").css("width","100%");
                        $("#btn_max_kembali_shopee").show();
                        $("#DANADIKEMBALIKANSHOPEE_1").removeAttr("readonly");
                        $("#returNegotiationShopee").show();
                        $("#HEADERRETURSHOPEE").show();
                        $("#DETAILRETURSHOPEE_1").html('Dengan ini menyatakan bahwa : <br>Penjual ingin melakukan <b>Negosiasi</b>, kepada pembeli terkait dengan nominal dana yang akan dikembalikan.&nbsp; Setelah klik tombol "Negosiasi Pengembalian Dana". untuk melanjutkan proses pengembalian.');
                        $("#NOSHOPEERETUR").html("#"+row.KODEPENGEMBALIAN);
                        $("#HEADERRETURSHOPEE").html('Pembeli akan mengirimkan barang paling lambat pada <span style="font-weight:bold;">'+row.MINTGLKIRIMPENGEMBALIAN+'</span>. Anda dapat mengajukan banding setelah menerima barang dari Pembeli atau menawarkan pengembalian Dana sebagian kepada Pembeli.<br><br>');
                        $("#modal-retur-shopee").modal("show");
                        
                        
                        if (rowDetail.LOGISTICSTATUS == "LOGISTICS_PICKUP_DONE") {
                            $("#HEADERRETURSHOPEE").html('Pembeli telah mengirimkan barang ke alamat penjual. Penjual dapat mengajukan banding setelah menerima barang dari pembeli atau setelah tanggal <span style="font-weight:bold;">'+row.MINTGLKIRIMPENGEMBALIAN+'</span><br><br>');
                        }
                        
                        var active = true;
                        for(var x = 0 ; x < msg.length; x++)
                        {
                            $("#DANADIKEMBALIKANSHOPEE_"+x).number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                            $("#MAXDANADIKEMBALIKANSHOPEE_"+x).number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                            $("#tab_retur_header_shopee_"+x).attr("class","");
                            $("#tab_retur_detail_shopee_"+x).attr("class","tab-pane");
                            $("#tab_retur_header_shopee_"+x).hide();
                            $("#tab_retur_detail_shopee_"+x).hide();
                            
                            var json = msg[x];
                            $("#DANADIKEMBALIKANSHOPEE_"+x).val(json.max_refund_amount);
                            $("#MAXDANADIKEMBALIKANSHOPEE_"+x).val(json.max_refund_amount);
                            
                            if(x == 1)
                            {
                                if(rowDetail.NEGOTIATIONCOUNTER < 2)
                                {
                                    if(rowDetail.NEGOTIATIONSTATUS == "PENDING_BUYER_RESPOND")
                                    {
                                        $("#DANADIKEMBALIKANSHOPEE_1").attr("readonly","readonly");
                                        $("#btn_max_kembali_shopee").hide();
                                        $("#HEADERRETURSHOPEE").hide();
                                        $("#returNegotiationShopee").hide();
                                        $("#returShopeeWaitResponse").show();
                                        $("#DETAILRETURSHOPEE_1").html('Dengan ini menyatakan bahwa : <br>Penjual telah mengajukan pengembalian dana sebesar <span style="font-weight:bold;">'+currency(json.max_refund_amount)+'</span>. Menunggu respon pembeli paling lambat <span style="font-weight:bold;">'+rowDetail.NEGOTIATIONDATE+'</span>');
                                    }
                                    else if(rowDetail.NEGOTIATIONSTATUS == "PENDING_RESPOND")
                                    {
                                        $("#HEADERRETURSHOPEE").hide();
                                        $("#DETAILRETURSHOPEE_1").html('Pembeli telah mengajukan negosiasi sebesar <span style="font-weight:bold;">'+currency(json.max_refund_amount)+'</span>. Mohon penjual dapat memberi respon selambat-lambatnya <span style="font-weight:bold;">'+rowDetail.NEGOTIATIONDATE+'</span>.&nbsp; Setelah klik tombol "Negosiasi Pengembalian Dana". untuk melanjutkan proses pengembalian.');
                                        
                                        $("#returAcceptNegotiationShopee").show();
                                        $("#returAcceptNegotiationShopee").css("width","49.75%");
                                        $("#returNegotiationShopee").css("width","49.75%");
                                    }
                                }
                            }
                            
                            if(json.eligibility)
                            {
                                if(active)
                                {
                                    $("#tab_retur_header_shopee_"+x).attr("class","active");
                                    $("#tab_retur_detail_shopee_"+x).attr("class","tab-pane active");
                                }
                                $("#tab_retur_header_shopee_"+x).css("display","");
                                $("#tab_retur_detail_shopee_"+x).css("display","");
                                active = false;
                                
                                if(x == msg.length-1)
                                {
                                    $("#uploadBuktiShopee").hide();
                                    $("#deskripsi_sengketa_shopee").val("");
                                    $("#penjelasan_bukti_shopee").html("");
                                    $("#proof_sengketa_shopee").html("");
                                    $("#email_sengketa_shopee").val("");
                                    $("#pilihDisputeShopee").val("");
                                    $("#pilihanDisputeShopee").val("");
                                    $("#HEADERRETURSHOPEE").html('Barang telah sampai di alamat penjual. Silahkan periksa kondisi barang dan ajukan banding jika barang tidak sesuai/rusak paling lambat <span style="font-weight:bold;">'+rowDetail.MINTGLCEKBARANG+'</span>. Jika tidak, dana akan <span style="font-weight:bold;">otomatis dikembalikan ke Pembeli</span>.<br><br>');
                                    var select = '<option value="">- Pilih Alasan -</option>';
                                    	    
                                     $.ajax({
                                    	type    : 'POST',
                                    	url     : base_url+'Shopee/getDispute/',
                                    	data    : {kode: row.KODEPENGEMBALIAN},
                                    	dataType: 'json',
                                    	success : function(msg){
                                    	    var dataDispute = msg;
                                    	    $("#dataDisputeShopee").val(JSON.stringify(dataDispute));
                                    	    for(var x = 0 ; x < dataDispute.length;x++)
                                    	    {
                                    	        select +=  '<option value="'+dataDispute[x].dispute_reason+'">'+dataDispute[x].dispute_text+'</option>';
                                    	    }
                                    	    $("#cb_alasan_sengketa_shopee").html(select);
                                    	}
                                    });
                                }
                            }
                        }
                        
                        Swal.close();
                	}
                });
            }
    	}
    });
}

function setMaksRefundShopee(){
  $("#DANADIKEMBALIKANSHOPEE_1").val($("#MAXDANADIKEMBALIKANSHOPEE_1").val());
}

function refundShopee(x){
    Swal.fire({
        title: 'Anda Yakin Melanjutkan Pengembalian Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var row = JSON.parse($("#rowDataShopee").val());
                var rowDetail = JSON.parse($("#dataReturShopee").val());
                loading();
                if(x == 0)
                {
                    $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Shopee/refund/',
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
                                $("#modal-retur-shopee").modal("hide");
                            
                                setTimeout(() => {
                                  reloadShopee();
                                }, "2000");
                            }
                    	}
                    });
                }
                else if(x == 1)
                {
                    $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Shopee/returnRefund/',
                    	data    : {kodepengembalian: row.KODEPENGEMBALIAN,offeramount: $("#DANADIKEMBALIKANSHOPEE_1").val(),solution:rowDetail.NEGOTIATIONSOLUTION},
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
                                $("#modal-retur-shopee").modal("hide");
                            
                                setTimeout(() => {
                                  reloadShopee();
                                }, "2000");
                            }
                    	}
                    });
                }
                else if(x == 11)
                {
                    $.ajax({
                        	type    : 'POST',
                        	url     : base_url+'Shopee/finalReturnRefund/',
                        	data    : {kodepengembalian: row.KODEPENGEMBALIAN},
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
                                    $("#modal-retur-shopee").modal("hide");
                                
                                    setTimeout(() => {
                                      reloadShopee();
                                    }, "2000");
                                }
                        	}
                    });
                }
                if(x == -1){
                    
                }
                else if(x == 2)
                {
                    
                    $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Shopee/menungguBarangDatang/',
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
                                $("#modal-retur-shopee").modal("hide");
                            
                                setTimeout(() => {
                                  reloadShopee();
                                }, "2000");
                            }
                    	}
                    });
                }
                else if(x == 3)
                {
                    var dataDisputeProof = [];
                    var dataProof = [];
                    var dataEvidence = $("#pilihanDisputeShopee").val()
                    var modulEvidence = JSON.parse(dataEvidence);
                    
                    for(var y = 0 ; y < modulEvidence.length;y++)
                    {
                        //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
                        if($("#src-input-shopee-"+y).val() != "")
                        {
                            dataDisputeProof.push({
                                "index" : (y+1),
                                "requirement" : $("#keterangan-input-shopee-"+y).val(),
                                "thumbnail" : $("#src-input-shopee-"+y).val(),
                                "url" : [$("#src-input-shopee-"+y).val()]
                            });
                            
                            dataProof.push({
                                "thumbnail" : $("#src-input-shopee-"+y).val(),
                                "url" : $("#src-input-shopee-"+y).val()
                            });
                        }
                    
                    }
                
                    // $.ajax({
                    // 	type    : 'POST',
                    // 	url     : base_url+'Shopee/uploadProof/',
                    // 	data    : {"kodepengembalian" : row.KODEPENGEMBALIAN, "kodepesanan" : row.KODEPESANAN, "dataDisputeProof" : JSON.stringify(dataDisputeProof), "dataProof" : JSON.stringify(dataProof), "description" : $("#deskripsi_sengketa_shopee").val()},
                    //     dataType: 'json',
                    // 	success : function(msg){
                           
                    //         Swal.close();	
                    //         Swal.fire({
                    //         	title            :  msg.msg,
                    //         	type             : (msg.success?'success':'error'),
                    //         	showConfirmButton: false,
                    //         	timer            : 2000
                    //         });
                    //         if(msg.success)
                    //         {
                    //             $("#modal-retur-shopee").modal("hide");
                            
                    //             setTimeout(() => {
                    //               reloadShopee();
                    //             }, "2000");
                    //         }
                    // 	}
                    // });
                    
                    $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Shopee/dispute/',
                    	data    : {"kodepengembalian" : row.KODEPENGEMBALIAN, "kodepesanan" : row.KODEPESANAN, "data" : JSON.stringify(dataDisputeProof), "id" :  $("#pilihDisputeShopee").val(), "description" : $("#deskripsi_sengketa_shopee").val(), "email" : $("#email_sengketa_shopee").val()},
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
                                $("#modal-retur-shopee").modal("hide");
                            
                                setTimeout(() => {
                                  reloadShopee();
                                }, "2000");
                            }
                    	}
                    });
                }
        	}
        });
}

function reloadShopee(){
    for(var x = 1 ; x <= 4 ; x++ )
    {
        $("#dataGridShopee"+x).DataTable().ajax.reload();
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

function focusOnRefundShopee(){
    setTimeout(() => {
     $("#DANADIKEMBALIKANSHOPEE_1").focus();
    }, "500");
}

//LIMIT ANGKA SAJA
function numberInputTrans(evt,index) {
	
	if(parseInt($("#DANADIKEMBALIKANSHOPEE_"+index).val()) < 0){
	    $("#DANADIKEMBALIKANSHOPEE_"+index).val(0);
		Swal.fire({
				title            : "Dana yang dikembalikan tidak boleh kurang dari Nol",
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
		});
	}
	else if(parseInt($("#DANADIKEMBALIKANSHOPEE_"+index).val()) > parseInt($("#MAXDANADIKEMBALIKANSHOPEE_"+index).val())){
	    $("#DANADIKEMBALIKANSHOPEE_"+index).val($("#MAXDANADIKEMBALIKANSHOPEE_"+index).val())
		Swal.fire({
				title            : "Dana yang dikembalikan tidak boleh lebih dari "+currency($("#MAXDANADIKEMBALIKANSHOPEE_"+index).val()),
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

function select2TemplateResult(state) {
  if (!state.id) return state.text;
  const desc = $(state.element).data('desc') || '';
  return `${state.text}<br><small style="font-size:8pt; font-weight:bold;">${desc}</small>`;
}

function select2TemplateSelection(state) {
  if (!state.id) return state.text;
  const desc = $(state.element).data('desc') || '';
  return state.text;
}

</script>

