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
    
    #tab_kirim_lazada .active a {
         color:black;
         font-weight:bold;
    }
    
    #tab_kirim_lazada li a {
        color:#949494;
        font-weight:normal;
    }
    
    #tab_retur_lazada .active a {
         color:black;
         font-weight:bold;
    }
    
    #tab_retur_lazada li a {
        color:#949494;
        font-weight:normal;
    }
    
    #modal-retur-lazada .modal-dialog {
        max-width: 700px;
        margin: 30px auto;
    }
    
    #modal-retur-lazada .modal-content {
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    #modal-retur-lazada .modal-body {
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
                    <button class="btn" style="background:#201ADC; color:white;" onclick="javascript:sinkronLazadaNow()">Sinkronisasi Hari Ini</button>&nbsp;
      				<button class="btn" style="background:white; color:#201ADC; border:1px solid #201ADC;" onclick="javascript:sinkronLazada()">Sinkronisasi 15 Hari Terakhir</button>
      				<div id="filter_tgl_lazada_1" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_lazada_1" style="width:100px;" name="tgl_awal_filter_lazada_1" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_lazada_1" style="width:100px;" name="tgl_akhir_filter_lazada_1" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshLazada(1)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_lazada_2" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_lazada_2" style="width:100px;" name="tgl_awal_filter_lazada_2" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_lazada_2" style="width:100px;" name="tgl_akhir_filter_lazada_2" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshLazada(2)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_lazada_3" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_lazada_3" style="width:100px;" name="tgl_awal_filter_lazada_3" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_lazada_3" style="width:100px;" name="tgl_akhir_filter_lazada_3" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshLazada(3)">Tampilkan</button>
      				</div>
      				<div id="filter_tgl_lazada_4" style="display: inline" class="pull-right">
      					<input type="text" class="form-control" id="tgl_awal_filter_lazada_4" style="width:100px;" name="tgl_awal_filter_lazada_4" readonly> - 
      					<input type="text" class="form-control" id="tgl_akhir_filter_lazada_4" style="width:100px;" name="tgl_akhir_filter_lazada_4" readonly>&nbsp;
      					<button class="btn btn-success" onclick="javascript:refreshLazada(4)">Tampilkan</button>
      				</div>
      			</div>
      		    <div class="nav-tabs-custom" >
                  <ul class="nav nav-tabs" id="tab_transaksi_lazada">
      				<li class="active" onclick="javascript:changeTabLazada(1)" ><a href="#tab_1_lazada" data-toggle="tab">Persiapan Pesanan &nbsp;<span id="totalLazada1" style=" display:none; color:white; background:red; border-radius:100px; padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabLazada(2)"><a href="#tab_2_lazada" data-toggle="tab">Proses Pengiriman &nbsp;<span id="totalLazada2" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabLazada(3)"><a href="#tab_3_lazada" data-toggle="tab">Selesai Pesanan &nbsp;<span id="totalLazada3" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li onclick="javascript:changeTabLazada(4)"><a href="#tab_4_lazada" data-toggle="tab">Pengembalian Pesanan &nbsp;<span id="totalLazada4" style=" display:none; color:white; background:red; border-radius:100px;  padding:2px 8px 2px 8px; font-weight:bold; font-size:10pt;"></span></a></li>
      				<li class="pull-right" style="width:250px">
      					<div class="input-group " id="filter_status_lazada_1">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_lazada_1" name="cb_trans_status_lazada_1" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="UNPAID">Belum Bayar</option>
      					  	<option value="PENDING">Siap Dikemas</option>
      					  	<option value="PACKED">Dikemas</option>
      					  	<option value="READY_TO_SHIP">Siap Dikirim</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_lazada_2">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_lazada_2" name="cb_trans_status_lazada_2" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="SHIPPED">Dalam Pengiriman</option>
      					  	<option value="DELIVERED">Telah Dikirim</option>
      					  	<option value="FAILED">Gagal Kirim</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_lazada_3">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_lazada_3" name="cb_trans_status_lazada_3" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="COMPLETED">Selesai</option>
      					  	<option value="CANCELLED">Pembatalan</option>
      					  </select>
      					</div>
      					<div class="input-group " id="filter_status_lazada_4">
      					 <div class="input-group-addon">
      						 <i class="fa fa-filter"></i>
      					 </div>
      					  <select id="cb_trans_status_lazada_4" name="cb_trans_status_lazada_4" class="form-control "  panelHeight="auto" required="true">
      					  	<option value="SEMUA">Semua Transaksi </option>
      					  	<option value="RETURNED|REQUEST_INITIATE">Pengembalian Diajukan</option>
      					  	<option value="RETURNED|BUYER_RETURN_ITEM|RETURN_PICKUP_PENDING|REFUND_PENDING">Pengembalian Diproses</option>
      					  	<option value="RETURNED|DISPUTE">Pengembalian dalam Sengketa</option>
      					  </select>
      					</div>
      				</li>
                  </ul>
                  <div class="tab-content">
                      <div class="tab-pane active" id="tab_1_lazada">
                          <div class="box-body ">
                      		  <button class="btn btn-warning" id="cetakLangsungSemua" style="margin-bottom:10px; display:none;" onclick="javascript:cetakLazadaSemua(1)" >Cetak Semua Pesanan</button>
                      		  <button class="btn btn-success" id="kirimLangsungSemua" style="margin-bottom:10px; display:none;" onclick="javascript:kirimLazadaSemua()" >Atur Semua Pengiriman</button>
                              <table id="dataGridLazada1" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
                      <div class="tab-pane" id="tab_2_lazada">
                          <div class="box-body ">
                              <table id="dataGridLazada2" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
                       <div class="tab-pane" id="tab_3_lazada">
                          <div class="box-body ">
                              <table id="dataGridLazada3" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
                       <div class="tab-pane" id="tab_4_lazada">
                          <div class="box-body ">
                              <table id="dataGridLazada4" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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
<input type="hidden" id="STATUSLAZADA1">
<input type="hidden" id="STATUSLAZADA2">
<input type="hidden" id="STATUSLAZADA3">
<input type="hidden" id="STATUSLAZADA4">
<!--MODAL BATAL-->

<div class="modal fade" id="modal-form-lazada">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Detail Pesanan&nbsp;&nbsp;<b id="NOLAZADA" style="font-size:14pt;"></b>&nbsp;&nbsp;&nbsp;-&nbsp;<i id="STATUSLAZADA"  style="font-size:12pt;"></i></h4>
            <!--<button onclick="ubahLazada()" id="ubahLazadaDetail" style="margin-left:15px;" class='btn btn-primary'>Ubah</button> -->
            <button onclick="hapusLazada()" id="hapusLazadaDetail" style="margin-left:5px;" class='btn btn-danger'>Batal</button>
            <button onclick="cetakLazada()" id="cetakLazadaDetail" style="margin-left:5px;" class='btn btn-warning'>Cetak</button>
            <button onclick="kirimLazada()" id='kirimLazadaDetail' class='btn btn-success' style='float:right;'>Atur Pengiriman</button>
            <button onclick="lacakLazada()" id='lacakLazadaDetail' class='btn btn-success' style='float:right;'>Lacak Pesanan</button>
            <button onclick="returBarangLazada()" id='returBarangLazadaDetail' class='btn btn-danger' style='float:right;'>Retur B. Manual</button>
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-4 col-sm-4 col-xs-4" style="padding:0px;">
                   <label>Tgl Pesanan</label>
                   <div id="TGLPESANANLAZADA">-</div>
                   <br>
                   <label>Min Tgl Kirim</label>
                   <div id="TGLKIRIMLAZADA">-</div>
                   <br>
                   <label>Metode Bayar</label>
                   <div id="PEMBAYARANLAZADA">-</div>
                   <br>
                   <label>Kurir / No. Resi</label>
                   <div id="KURIRLAZADA">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                   <label>Pembeli </label>
                   <div id="NAMAPEMBELILAZADA">-</div>
                   <br>
                   <label>Telp </label>
                   <div id="TELPPEMBELILAZADA">-</div>
                   <br>
                   <label>Alamat </label>
                   <div id="ALAMATPEMBELILAZADA">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4"style="padding:0px;">
                   <label>Catatan Pembeli</label>
                   <div id="CATATANPEMBELILAZADA">-</div>
                   <br>
                   <label class="noKembaliLazada">No. Pengembalian</label>
                   <div class="noKembaliLazada" id="NOPENGEMBALIANLAZADA">-</div>
                   <br>
                   <label class="alasanKembaliLazada">Alasan Batal / Kembali</label>
                   <div class="alasanKembaliLazada" id="ALASANPENGEMBALIANLAZADA">-</div>
                </div>
      	    	<!--SATU TABEL-->
      	    	<div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:15px; padding:0px;" >
          	    	<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          	    		<div class="row"> 
              				<div class=" col-sm-12">
              					<table id="dataGridDetailLazada" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
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
              						<tbody class="table-responsive-lazada">
              						</tbody>
              					</table> 
              				</div>
          	    		</div>
          	    	</div> 
          	    	<div class="row" style="margin:0px;padding:0px;"> 
              				<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
              					<table id="footerLazada" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<tfoot>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
              								<th style="vertical-align:middle; text-align:center;" id="TOTALQTYLAZADA" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:right;" id="SUBTOTALLAZADA" width="100px"></th>
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
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="TOTALPEMBELILAZADA">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Diskon Pesanan</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="DISKONPEMBELILAZADA">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Biaya Pengiriman</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYAKIRIMPEMBELILAZADA">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold">Biaya Lain</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYALAINPEMBELILAZADA">
                  	    	</div>
                  	    	<div class="col-md-9" align="right" style="font-weight:bold; padding-top:15px;">Pembayaran Pembeli</div>
                  	    	<div class="col-md-3 " style="text-align:right; padding-top:15px; padding-bottom:15px; padding-right:10px; border-top:1px solid #cecece; font-weight:bold" id="PEMBAYARANPEMBELILAZADA">	
                  	    	</div>
              	    	</div>
              	    </div>
      	    	</div>
      	    	<div class="col-md-6 col-sm-6 col-xs-6  "style="padding:0px 0px 0px 15px;">
          	    	    <div style="font-weight:bold; margin:auto;" ><i style="font-size:14pt;">Informasi Penjual <span id="ADDINFOINFORMASIPENJUAL" style="font-weight:500;"><br>Informasi penjual tertunda sampai pesanan diterima customer</span></i></div>
          	    	    <div class="row" id="DETAILINFORMASIPENJUALLAZADA">
          	    			<div class="col-md-12">
                  	    	    <div class="col-md-9" align="right" style="font-weight:bold">Total</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="TOTALPENJUALLAZADA">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Refund </div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="REFUNDPENJUALLAZADA">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Diskon Penjual</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="DISKONPENJUALLAZADA">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Biaya Pengiriman Final</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px;" id="BIAYAKIRIMPENJUALLAZADA">
              	    			</div>
              	    			<div class="col-md-9" align="right" style="font-weight:bold">Biaya Layanan</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-bottom:15px; padding-right:10px; " id="BIAYALAYANANPENJUALLAZADA">
              	    			</div>
              	    			<hr></hr>
              	    			<div class="col-md-9" align="right" style="font-weight:bold; padding-top:15px;">Total Penjualan</div>
              	    			<div class="col-md-3 " style="text-align:right; padding-top:15px; padding-bottom:15px; padding-right:10px; border-top:1px solid #cecece;  font-weight:bold" id="GRANDTOTALPENJUALLAZADA">
              	    		    </div>
              	    			<div class="col-md-9 penyelesaianLazada"  align="right" style="font-weight:bold;">Penyelesaian Pembayaran</div>
              	    			<div class="col-md-3 penyelesaianLazada"  style="text-align:right; padding-bottom:15px; padding-right:10px;font-weight:bold " id="PENYELESAIANPENJUALLAZADA">		
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

<div class="modal fade" id="modal-ubah-lazada">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	     <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Ubah Pesanan&nbsp;&nbsp;<b id="NOLAZADAUBAH" style="font-size:14pt;"></b></h4>
            <button id='btn_ubah_konfirm_lazada'  style="float:right;" class='btn btn-primary' onclick="ubahKonfirmLazada()">Ubah</button>
        </div>
		<div class="modal-body" style="height:395px;">
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailLazadaUbah" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
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
            					<tbody class="table-responsive-lazada-ubah">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
          		<div class="row" style="margin:0px;padding:0px;"> 
            			<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
            				<table id="footerLazadaUbah" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<tfoot>
            						<tr>
            						    <th width="103px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
            							<th style="vertical-align:middle; text-align:center;" id="TOTALQTYLAZADAUBAH" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:right;" id="SUBTOTALLAZADAUBAH" width="100px"></th>
            						</tr>
            					</tfoot>
            				</table> 
            			</div>
            		</div>
      	    </div>
            <input type="hidden" id="itemUbahLazada">
			<br>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="modal-alasan-lazada">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	     <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Pembatalan Pesanan&nbsp;&nbsp;<b id="NOLAZADABATAL" style="font-size:14pt;"></b></h4>
            <button id='btn_hapus_konfirm_lazada'  style="float:right;" class='btn btn-danger' onclick="hapusKonfirmLazada()">Batal</button>
        </div>
		<div class="modal-body" style="height:480px;">
		    <div>
      	    <label>Alasan Pembatalan</label>
			<select id="cb_alasan_pembatalan_lazada" name="cb_alasan_pembatalan_lazada" class="form-control "  panelHeight="auto" required="true">
      		</select>
      		</div>
      		<br>
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailLazadaBatal" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
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
            					<tbody class="table-responsive-lazada-batal">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
          		<div class="row" style="margin:0px;padding:0px;"> 
            			<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
            				<table id="footerLazadaBatal" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<tfoot>
            						<tr>
            							<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
            							<th style="vertical-align:middle; text-align:center;" id="TOTALQTYLAZADABATAL" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="50px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:center;" width="100px"></th>
            							<th style="vertical-align:middle; text-align:right;" id="SUBTOTALLAZADABATAL" width="100px"></th>
            						</tr>
            					</tfoot>
            				</table> 
            			</div>
            		</div>
      	    </div>
      		<input type="hidden" id="itemBatalLazada">
			<br>
		</div>
	</div>
	</div>
</div>

<div class="modal fade" id="modal-kirim-lazada">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Atur Pengiriman&nbsp;&nbsp;<span id="countAturPengiriman" style="font-size:14pt;"></span></h4>
                <button onclick="kirimKonfirmLazada()" id='kirim_konfirm_lazada' class='btn btn-success' style='float:right;'>Kirim</button>
        </div>
		<div class="modal-body" style="height:395px;">
      	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
          		<div class="x_content" style="height:363px; overflow-y:auto; overflow-x:hidden;">
          			<div class="row"> 
            			<div class=" col-sm-12">
            				<table id="dataGridDetailLazadaKirim" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
            					<thead>
            						<tr>
            							<th style="vertical-align:middle; text-align:center;" width="150px">Kurir</th>
            							<th style="vertical-align:middle; text-align:center;" width="150px" >No. Pesanan</th>
            							<th style="vertical-align:middle; text-align:center;" width="80px">T. Qty</th>
            							<th style="vertical-align:middle; text-align:center;" width="150px" >No Resi</th>
            							<th style="vertical-align:middle; text-align:center;" width="150px" >Min Tgl Kirim</th>
            							<th style="vertical-align:middle; text-align:center;" >Catatan Penjual</th>
            						</tr>
            					</thead>
            					<tbody class="table-responsive-lazada-kirim">
            					</tbody>
            				</table> 
            			</div>
          			</div>
          		</div> 
      	    </div>
		</div>
		<input type="hidden" id="rowDataPengirimanLazada">
	</div>
	</div>
</div>

<div class="modal fade" id="modal-kirim-all-lazada">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Atur Semua Pengiriman&nbsp;&nbsp;<span id="countAturSemuaPengirimanLazada" style="font-size:14pt;"></span></h4>
                <button onclick="kirimKonfirmAllLazada()" id='kirim_konfirm_all_lazada' class='btn btn-success' style='float:right;'>Kirim</button>
        </div>
		<div class="modal-body" style="height:655px;">
		        <div style="margin-left:25px; margin-bottom:25px;">
		            <label><input type="checkbox" id="pilihKirimanAllKurirLazada" checked> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pilih Semua Kurir</label>
		            <span style="float:right; margin-top:3px; margin-right:30px;" id="keteranganKurirLazada">Terdapat &nbsp;<span id="countAllPesananLazada" style="font-weight:bold; font-size:14pt; "></span>&nbsp; Pesanan dari &nbsp;<span id="countAllKurirLazada" style="font-weight:bold; font-size:14pt; "></span>&nbsp; Kurir</span>
		        </div>
		        <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:20px; padding:0px;" >
                      <div class="box-body ">
                      		<div class="x_content" style="height:508px; overflow-y:auto; overflow-x:hidden;">
                      			<div class="row"> 
                        			<div class=" col-sm-12" id="dataGridDetailAllLazada">
                        			</div>
                      			</div>
                      		</div> 
                      </div>
                </div>
      	    </div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-lacak-lazada">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
    	<div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Lacak Pesanan&nbsp;&nbsp;<b id="NOLAZADALACAK" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body" style="height:425px;">
      	    <div class="row"> 
            	<div class="col-sm-5" style="padding:0px 20px 0px 20px; border-right:1px solid #cecece;">
            	   <label>Metode Bayar</label>
                   <div id="METODEBAYARLACAKLAZADA">-</div>
                   <br>
            	   <label>Kurir</label>
                   <div id="KURIRLACAKLAZADA">-</div>
                   <br>
                   <label>Resi</label>
                   <div id="RESILACAKLAZADA">-</div>
                   <br>
                   <label>Tgl Kirim</label>
                   <div id="TGLKIRIMLACAKLAZADA">-</div>
                   <br>
                   <label>Alamat</label>
                   <div id="ALAMATLACAKLAZADA">-</div>
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

<div class="modal fade" id="modal-barang-lazada">
	<div class="modal-dialog">
	<div class="modal-content">
    	 <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                    <i class='fa fa-arrow-left' ></i>
                </button>
                <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Ganti Produk Asal&nbsp;&nbsp;<b id="warnaOldLazada" style="font-size:14pt;"></b><b> / </b><b id="sizeOldLazada" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body">
			<table id="table_barang_lazada" class="table table-bordered table-striped table-hover display nowrap" width="100%">
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

<div class="modal fade" id="modal-note-lazada">
	<div class="modal-dialog ">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Catatan Pesanan&nbsp;&nbsp;<b id="NOLAZADACATATAN" style="font-size:14pt;"></b></h4>
                    <button id='btn_note_konfirm_lazada'  style="float:right;" class='btn btn-success' onclick="noteKonfirmLazada()">Simpan</button>
            </div>
    		<div class="modal-body">
    		    <textarea id="note_lazada" maxlines="5" style="width:100%; height:200px; border:0.5px solid #cecece; padding:10px;" placeholder="Masukkan Catatan.....">
    		    </textarea>
    		    <input type="hidden" id="fromNoteLazada">
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-cetak-lazada">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Cetak Pesanan&nbsp;&nbsp;<span id="countCetak" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_lazada'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmLazada()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewCetakLazada">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-cetak-all-lazada">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; Cetak Semua Pesanan&nbsp;&nbsp;<span id="countCetakSemua" style="font-size:14pt;"></span></h4>
                    <button id='btn_cetak_all_konfirm_lazada'  style="float:right;" class='btn btn-warning' onclick="cetakAllKonfirmLazada()">Cetak</button>
            </div>
    		<div class="modal-body" style="height:600px;">
          	    <div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:0px; margin-bottom:15px; padding:0px;" >
              		<div class="x_content" style="height:568px; overflow-y:auto; overflow-x:hidden;">
              			<div class="row"> 
                			<div class=" col-sm-12">
                				<table id="dataGridDetailLazadaAllCetak" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
                					<thead>
                						<tr>
                						    <th style="vertical-align:middle; text-align:center;" width="30px"><input type="checkbox" id="pilihCetakAllLazada" checked width="30px"></th>
                							<th style="vertical-align:middle; text-align:center;" width="150px" >No. Pesanan</th>
                							<th style="vertical-align:middle; text-align:center;" width="150px">Metode Bayar</th>
                							<th style="vertical-align:middle; text-align:center;" width="100px">Kurir</th>
                							<th style="vertical-align:middle; text-align:center;" width="150px">Resi</th>
                						</tr>
                					</thead>
                					<tbody class="table-responsive-lazada-all-cetak">
                					</tbody>
                				</table> 
                			</div>
              			</div>
              		</div> 
          	    </div>
		    </div>
		    <input type="hidden" id="dataCetakSemuaLazada">
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-pengembalian-lazada">
	<div class="modal-dialog modal-lg">
	<div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Detail Pengembalian&nbsp;&nbsp;<b id="NOLAZADAPENGEMBALIAN" style="font-size:14pt;"></b>&nbsp;&nbsp;&nbsp;-&nbsp;<i id="STATUSLAZADAPENGEMBALIAN"  style="font-size:12pt;"></i></h4>
            <button onclick="returLazada()" id='returLazadaDetail' class='btn btn-success' style='float:right;'>Jawab</button>
            <button id='returLazadaWait' class='btn' style='float:right; background:#888888; color:white;'></button>
            
        </div>
		<div class="modal-body">
      	    <div class="row"  style="margin-left:4px; margin-right:4px; ">
      	        <div class="col-md-4 col-sm-4 col-xs-4" style="padding:0px;">
                   <label>Tgl Pengembalian</label>
                   <div id="TGLLAZADAPENGEMBALIAN">-</div>
                   <br>
                   <label>Tenggat Waktu</label>
                   <div id="MINTGLLAZADAPENGEMBALIAN">-</div>
                   <br>
                   <label>No. Resi Pengembalian</label>
                   <div id="RESILAZADAPENGEMBALIAN">-</div>
                   <br>
                   <label>No. Pesanan</label>
                   <div id="NOLAZADAPESANANPENGEMBALIAN">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                   <label>Pembeli </label>
                   <div id="NAMAPEMBELILAZADAPENGEMBALIAN">-</div>
                   <br>
                   <label>Telp </label>
                   <div id="TELPPEMBELILAZADAPENGEMBALIAN">-</div>
                   <br>
                   <label>Alamat </label>
                   <div id="ALAMATPEMBELILAZADAPENGEMBALIAN">-</div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4"style="padding:0px;"> 
                   <label>Alasan Pilihan Pembeli</label>
                   <div id="ALASANLAZADAPILIHAN">-</div>
                   <br>
                   <label>Alasan Pengembalian Pembeli</label>
                   <div id="ALASANLAZADAPENGEMBALIAN" style="max-height:70px; overflow-x:hidden;">-</div>
                   <br>
                   <label>Bukti Pengembalian Pembeli</label>
          	    	<div id="GAMBARPENGEMBALIANLAZADA" style="max-height:70px; overflow-x:hidden; width:50%; float:left;"></div>
          	    	<div id="VIDEOPENGEMBALIANLAZADA" style="max-height:70px; overflow-x:hidden; width:50%;"></div>
                </div>
      	    	<!--SATU TABEL-->
      	    	<div class="col-md-12 col-sm-12 col-xs-12 " style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:6px; padding:0px;" >
          	    	<div class="x_content" style="height:320px; overflow-y:auto; overflow-x:hidden;">
          	    		<div class="row"> 
              				<div class=" col-sm-12">
              					<table id="dataGridDetailPengembalianLazada" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<thead>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Nama</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Jml</th>
              								<th style="vertical-align:middle; text-align:center;" width="50px">Sat</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px" >Harga</th>
              								<th style="vertical-align:middle; text-align:center;" width="100px">Dana Kembali</th>
              							</tr>
              						</thead>
              						<tbody class="table-responsive-lazada-pengembalian">
              						</tbody>
              					</table> 
              				</div>
          	    		</div>
          	    	</div> 
          	    	<div class="row" style="margin:0px;padding:0px;"> 
              				<div class=" col-sm-12" style="margin:0px;padding:0px; height:40px;">
              					<table id="footerLazada" class="table table-bordered table-striped table-hover display nowrap" width="100%" >
              						<tfoot>
              							<tr>
              								<th style="vertical-align:middle; text-align:center;" width="400px" >Total</th>
              								<th style="vertical-align:middle; text-align:center;" id="TOTALQTYPENGEMBALIANLazada" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="50px"></th>
              								<th style="vertical-align:middle; text-align:center;" width="100px"></th>
              								<th style="vertical-align:middle; text-align:right;" id="SUBTOTALPENGEMBALIANLazada" width="100px"></th>
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

<div class="modal fade" id="modal-lebih-jelas-lazada" style="z-index:999999999999999999999999999;">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; <span id="titleLebihJelasLazada" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_lazada'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmLazada()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewLebihJelasLazada">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
</div>

<div class="modal fade" id="modal-retur-lazada">
	<div class="modal-dialog" style="width:700px;">
	    <div class="modal-content">
	    <div class="modal-header">
            <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                <i class='fa fa-arrow-left' ></i>
            </button>
            <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp;Proses Pengembalian&nbsp;&nbsp;<b id="NOLAZADARETUR" style="font-size:14pt;"></b></h4>
        </div>
		<div class="modal-body">
		    <div id="HEADERRETURLAZADA"></div>
		    Jadi penjual memilih :
		    <br><br>
          	<ul class="nav nav-tabs" id="tab_retur_lazada">
          		<li id="tab_retur_header_lazada_0"><a href="#tab_retur_detail_lazada_0" data-toggle="tab">Kembalikan Dana ke Pembeli</a></li>
          	    <li id="tab_retur_header_lazada_1" onclick="focusOnRefundLazada()"><a href="#tab_retur_detail_lazada_1" data-toggle="tab">Pengembalian Barang dan Dana</a></li>
          	    <li id="tab_retur_header_lazada_2"><a href="#tab_retur_detail_lazada_2" data-toggle="tab">Ajukan Banding</a></li>
            </ul>
            <div class="tab-content" style="border:1px solid #dddddd; background:white; border-radius:0px 0px 3px 3px; padding: 10px 10px 10px 10px;">
                <div class="tab-pane " id="tab_retur_detail_lazada_0" style="padding:5px 0px 5px 0px;">
                    <div id="DETAILRETURLAZADA_0">Dengan ini menyatakan bahwa : <br>Penjual telah setuju untuk melakukan <b>Pengembalian Dana Penuh</b>, dan pembeli &nbsp;<i>tidak perlu mengembalikan produk</i>.&nbsp; Setelah klik tombol "Setujui dan Kembalikan Dana". untuk melanjutkan proses pengembalian.</div>
                    <br><br>
                    <label style="width:100%; text-align:center; font-size:18pt;">Total Dana Kembali</label>
                    <div style="width:100%; text-align:center;"><input type="text" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANLAZADA_0" onkeyup="return numberInputTrans(event,0)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                    <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANLAZADA_0">
                    <br><br><br>
                     <button onclick="refundLazada(0)" id='returRefundLazada' class='btn btn-success' style='width:100%; font-weight:bold;'>Setuju&nbsp;&nbsp;dan&nbsp;&nbsp;Kembalikan&nbsp;&nbsp;Dana</button>
                </div>
                <div class="tab-pane" id="tab_retur_detail_lazada_1" style="padding:5px 0px 5px 0px;">
                    <div id="DETAILRETURLAZADA_1"></div>
                    <br><br>
                    <label style="width:100%; text-align:center; font-size:18pt;">Total Dana Pengembalian yang Diajukan</label>
                    <div style="width:100%; text-align:center;"><input type="text" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANLAZADA_1" onkeyup="return numberInputTrans(event,1)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                    <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANLAZADA_1">
                     <!--<div style="width:100%; margin-top:10px; text-align:center;"><button id='btn_max_kembali_lazada' onclick="setMaksRefundLazada()" style='border:1px solid #CECECE; margin:auto;' class='btn' >Maks Pengembalian</button></div><br><br>-->
                     <br><br><br>
                     <button onclick="refundLazada(1)" id='returNegotiationLazada' class='btn btn-warning' style='width:100%; font-weight:bold;'>Pengembalian&nbsp;&nbsp;Barang&nbsp;&nbsp;dan&nbsp;&nbsp;Dana</button>
                     <button id='returLazadaWaitResponse' class='btn' style='width:100%; background:#888888; color:white; font-weight:bold;'>Menunggu&nbsp;&nbsp;Respon&nbsp;&nbsp;Pembeli</button>
                </div>
                <div class="tab-pane" id="tab_retur_detail_lazada_2" style="padding:5px 0px 5px 0px;">
                    <div id="DISPUTESEBELUMBARANGDATANG">
                        <div id="DETAILRETURLAZADA_2">Dengan ini menyatakan bahwa : <br>Penjual mengajukan banding terhadap barang yang telah dikirimkan oleh Pembeli (Terkait kerusakan, barang yang dikembalikan berbeda, dll).</div>
            		    <div id="ALASANBANDING">
            		        <br>
                      	    <label>Alasan Banding</label>
                			<select id="cb_alasan_sengketa_lazada" name="cb_alasan_sengketa_lazada" class="form-control "  panelHeight="auto" required="true">
                      		
                      		</select>
                      	</div>
            		    <br>
                		<div>
                      	    <label>Penjelasan Banding</label>
                		    <textarea id="deskripsi_sengketa_lazada" maxlines="2" style="width:100%; height:80px; border:0.5px solid #cecece; padding:10px;" placeholder="Masukkan Penjelasan....."></textarea>
                		</div>
                		<br>
                      	<div id="uploadBuktiLazada">
                      	    <label>Upload Bukti</label>
                      	    <div id="penjelasan_bukti_lazada"></div>
                			<div id="proof_sengketa_lazada" style="border:1px solid; background:white; border-radius:0px 0px 3px 3px; margin-top:15px; margin-bottom:15px; padding:10px;">
                			    
                			</div>
                		 </div>
                         <div style="width:100%; text-align:center;"><input type="hidden" readonly class="form-control has-feedback-left" id="DANADIKEMBALIKANLAZADA_2" onkeyup="return numberInputTrans(event,3)" placeholder="0"  value="0" style="width:250px; padding-top:35px; padding-bottom:35px; font-weight:bold; font-size:32pt; margin:auto; text-align:center;"></div>
                         <input type="hidden" class="form-control has-feedback-left" id="MAXDANADIKEMBALIKANLAZADA_2">
                         <input type="hidden" id="dataDisputeLazada">
                         <input type="hidden" id="pilihanDisputeLazada">
                         <input type="hidden" id="pilihDisputeLazada">
                         <br>
                         <button onclick="refundLazada(2)" id='returDisputeLazada' class='btn btn-danger' style='width:100%; font-weight:bold;'>Ajukan&nbsp;&nbsp;Banding</button>
                    </div>
                    <div id="DISPUTESESUDAHBARANGDATANG">Dengan ini menyatakan bahwa : <br>Penjual mengajukan banding terhadap barang yang telah dikirimkan oleh Pembeli (Terkait kerusakan, barang yang dikembalikan berbeda, dll).<br><br>Untuk transaksi hanya dapat dilakukan pada aplikasi lazada. Status transaksi dan stok akan terupdate, melalui sinkronisasi otomatis maupun sinkronisasi manual.</div>
                </div>
            </div>  
        </div>
      </div>
	</div>
    <input type="hidden" id="dataReturLazada">
</div>

<input type="hidden" id="kategori_item_lazada" value="">
<input type="hidden" id="rowDataLazada">

<script>

var firsTimeLazada = ["",true,true,true,true];
var sinkronLazadaState = false;
var doneSinkronLazada =  ["",true,true,true,true];
var totalPesananLazadaAll = 0;

setTimeout(() => {
    changeTabLazada(1);
    changeTabLazada(2);
    changeTabLazada(3);
    changeTabLazada(4);
	
	$("#filter_status_lazada_"+1+", #filter_tgl_lazada_"+1).show();
    
    for(var x = 1; x <= 4 ; x++)
    {
       if(1 != x)
       {
            $("#filter_status_lazada_"+x+", #filter_tgl_lazada_"+x).hide();
       }
    }
}, "100");

$(document).ready(function(){
	
    //TAMBAH
	$('#tgl_awal_filter_lazada_1, #tgl_akhir_filter_lazada_1, #tgl_awal_filter_lazada_2, #tgl_akhir_filter_lazada_2, #tgl_awal_filter_lazada_3, #tgl_akhir_filter_lazada_3, #tgl_awal_filter_lazada_4, #tgl_akhir_filter_lazada_4').datepicker({
		format: 'yyyy-mm-dd',
		 autoclose: true, // Close the datepicker automatically after selection
        container: 'body', // Attach the datepicker to the body element
        orientation: 'bottom auto' // Show the calendar below the input
	});
	$("#tgl_awal_filter_lazada_1, #tgl_awal_filter_lazada_2, #tgl_awal_filter_lazada_3, #tgl_awal_filter_lazada_4").datepicker('setDate', "<?=TGLAWALFILTERMARKETPLACE?>");
	$("#tgl_akhir_filter_lazada_1, #tgl_akhir_filter_lazada_2, #tgl_akhir_filter_lazada_3, #tgl_akhir_filter_lazada_4").datepicker('setDate', new Date());
	
	$("#STATUSLAZADA1").val('UNPAID,PENDING,PACKED,READY_TO_SHIP');
	$("#STATUSLAZADA2").val('SHIPPED,DELIVERED,FAILED');
	$("#STATUSLAZADA3").val('COMPLETED,CANCELLED');
	$("#STATUSLAZADA4").val('RETURNED|REQUEST_INITIATE,RETURNED|BUYER_RETURN_ITEM|RETURN_PICKUP_PENDING|REFUND_PENDING,RETURNED|DISPUTE');
	
	$('body').keyup(function(e){
		hotkey(e);
	});
	
	$("#modal-barang").on('shown.bs.modal', function(e) {
        $('div.dataTables_filter input', $("#table_barang").DataTable().table().container()).focus();
    });
    
    //TABLE BARANG
	$("#table_barang_lazada").DataTable({
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
					e.kategori 	    = getKategoriLazada();
					e.marketplace 	= "Lazada";
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
	$('#table_barang_lazada tbody').on('click', 'tr', function () {
		var row = $('#table_barang_lazada').DataTable().row( this ).data();
		$("#modal-barang-lazada").modal('hide');
		
		 $(".table-responsive-lazada-ubah").html('');
         var itemDetail = JSON.parse($("#itemUbahLazada").val()); 
         for(var x = 0 ; x < itemDetail.length ; x++)
         {
             if(itemDetail[x]['WARNAOLD'] == $("#warnaOldLazada").html() && itemDetail[x]['SIZEOLD'] && $("#sizeOldLazada").html())
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
             
             $(".table-responsive-lazada-ubah").append(setDetail(itemDetail,x,namaBarang,true));
         }
         
         
        $("#itemUbahLazada").val(JSON.stringify(itemDetail));
            
		var table = $('#table_barang_lazada').DataTable();
		table.search("").draw();
	});
});

function getKategoriLazada(){
	return $("#kategori_item_lazada").val();
}

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_lazada_1").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#STATUSLAZADA1").val('UNPAID,PENDING,PACKED,READY_TO_SHIP');
	}	
	else
	{
		$("#STATUSLAZADA1").val($(this).val());
	}
	$("#dataGridLazada1").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_lazada_2").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#STATUSLAZADA2").val('SHIPPED,FAILED');
	}	
	else
	{
		$("#STATUSLAZADA2").val($(this).val());
	}
	$("#dataGridLazada2").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_lazada_3").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#STATUSLAZADA3").val('COMPLETED,CANCELLED');
	}	
	else
	{
		$("#STATUSLAZADA3").val($(this).val());
	}
	$("#dataGridLazada3").DataTable().ajax.reload();
	
});

//MENAMPILKAN TRANSAKSI
$("#cb_trans_status_lazada_4").change(function(event){
    loading();
	if($(this).val()  == 'SEMUA' )
	{
		$("#STATUSLAZADA4").val('RETURNED|REQUEST_INITIATE,RETURNED|BUYER_RETURN_ITEM|RETURN_PICKUP_PENDING|REFUND_PENDING,RETURNED|DISPUTE');
	}	
	else
	{
		$("#STATUSLAZADA4").val($(this).val());
	}
	$("#dataGridLazada4").DataTable().ajax.reload();
	
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

function getStatusLazada(index){
	return $("#STATUSLAZADA"+index).val();
}

function refreshLazada(index){
    loading();
    $("#dataGridLazada"+index).DataTable().ajax.reload();
}

function changeTabLazada(index){
    
    if(!sinkronLazadaState)
    {
        loading();
    }
    
    
    // if(firsTimeLazada[1])
    // {
    //      $.ajax({
    //     	type    : 'POST',
    //     	url     : base_url+'Lazada/init/<?=date('Y-m-d')?>/<?=date('Y-m-d')?>/update_time',
    //     	dataType: 'json',
    //     	success : function(msg){
        	    
    //     	}
	   //  });
    // }
    // else if(!firsTimeLazada[index])
    // {
    //      $.ajax({
    //     	type    : 'POST',
    //     	url     : base_url+'Lazada/init/<?=date('Y-m-d')?>/<?=date('Y-m-d')?>/update_time',
    //     	dataType: 'json',
    //     	success : function(msg){
        	    
    //     	}
	   //  });
    // }
    
    $("#filter_status_lazada_"+index+", #filter_tgl_lazada_"+index).show();
    
    for(var x = 1; x <= 4 ; x++)
    {
       if(index != x)
       {
            $("#filter_status_lazada_"+x+", #filter_tgl_lazada_"+x+"").hide();
       }
    }
    
    if(firsTimeLazada[index])
    {
        firsTimeLazada[index] = false;
    	//GRID BARANG
    	if(index != 4)
    	{
        	$('#dataGridLazada'+index).DataTable({
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
        			url    : base_url+'Lazada/dataGrid/',
        			dataSrc: "rows",
        			type   : "POST",
        			data   : function(e){
        			        e.state          = index;
        					e.status 		 = getStatusLazada(index);
        					e.tglawal        = $('#tgl_awal_filter_lazada_'+index).val();
        					e.tglakhir       = $('#tgl_akhir_filter_lazada_'+index).val();
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
                            if (row.STATUS.toUpperCase() == "SIAP DIKEMAS" || row.STATUS.toUpperCase() == "DIKEMAS" || row.STATUS.toUpperCase() == "PROSES KIRIM" ||  row.STATUS.toUpperCase() == "SIAP DIKIRIM") {
                                html += "<button id='btn_lihat_lazada' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<button  style='margin-top:5px;' id='btn_hapus_lazada' class='btn btn-danger'  style='width:122px;' >Batal</button>"; //<button id='btn_edit_lazada' class='btn btn-primary' style='width:59.5px;' >Ubah</button> 
                                if(row.KURIR.toUpperCase() != "DELIVERED BY SELLER")
                                {
                                    html+= "<button  style='margin-top:auto;' id='btn_cetak_lazada' class='btn btn-warning'  style='width:122px;'>Cetak</button>";
                                }
                                if(row.STATUS.toUpperCase() == "DIKEMAS"  && row.KURIR.toUpperCase() != "DELIVERED BY SELLER")
                                {
                                    html += "<div style='margin-top:auto;'><button id='btn_kirim_lazada' class='btn btn-success' style='width:122px;'>Atur Pengiriman</button></div>";
                                }
                            } else if (row.STATUS.toUpperCase() == "DALAM PENGIRIMAN" || row.STATUS.toUpperCase() == "GAGAL PENGIRIMAN") {
                                html += "<button id='btn_lihat_lazada' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<div style='margin-top:auto;'><button id='btn_lacak_lazada' class='btn btn-success' style='width:122px;'>Lacak Pesanan</button></div>";
                            } else if(row.STATUS.toUpperCase() == "SELESAI" && row.KODEPENGEMBALIAN != "" && (row.BARANGSAMPAI == 0 && row.BARANGSAMPAIMANUAL == 0)){
                                html += "<button id='btn_lihat_lazada' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
                                html += "<button  style='width:122px; margin-top:5px;' id='btn_retur_manual_lazada' class='btn btn-danger'  style='width:122px;' >Retur B. Manual</button>";
                            } else {
                                html += "<button id='btn_lihat_lazada' style='border:1px solid #CECECE; width:122px;' class='btn' >Detail Pesanan</button>";
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
    	    $('#dataGridLazada'+index).DataTable({
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
        			url    : base_url+'Lazada/dataGrid/',
        			dataSrc: "rows",
        			type   : "POST",
        			data   : function(e){
        			        e.state          = index;
        					e.status 		 = getStatusLazada(index);
        					e.tglawal        = $('#tgl_awal_filter_lazada_'+index).val();
        					e.tglakhir       = $('#tgl_akhir_filter_lazada_'+index).val();
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
                                html += "<button id='btn_kembali_lazada' style='border:1px solid #CECECE;' class='btn' >Detail Pengembalian</button>";
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
    	var table = $('#dataGridLazada'+index).DataTable();
    	$('#dataGridLazada'+index+' tbody').on( 'click', 'button', function () {
    		var row = table.row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		$("#rowDataLazada").val(JSON.stringify(row));
    		
    		if(mode == "btn_lihat_lazada"){ lihatLazada();}
    // 		else if(mode == "btn_edit_lazada"){ubahLazada();}
    		else if(mode == "btn_cetak_lazada"){cetakLazada();}
    		else if(mode == "btn_hapus_lazada"){hapusLazada();}
    		else if(mode == "btn_kirim_lazada"){kirimLazada();}
    		else if(mode == "btn_lacak_lazada"){lacakLazada();}
    		else if(mode == "btn_kembali_lazada"){kembaliLazada();}
    		else if(mode == "btn_retur_lazada"){returLazada();}
    	    else if(mode == "btn_retur_manual_lazada"){returBarangLazada();}
    		
    	} );
    	
    	$('#dataGridLazada'+index+' tbody').on( 'click', 'i', function () {
    		var row = table.row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		$("#rowDataLazada").val(JSON.stringify(row));
    		$("#fromNoteLazada").val("GRID_X");
    		if(mode == "editNoteLazada"){catatanPenjualLazada();}
    	} );
    }
    else
    {
        $("#dataGridLazada"+index).DataTable().ajax.reload();
    }
    
    // Close SweetAlert after data is loaded
    $('#dataGridLazada'+index).DataTable().on('xhr.dt', function () {
        if(!sinkronLazadaState)
        {
           setTimeout(() => {
               if($('#dataGridLazada'+index).DataTable().data().count() == 0)
               {
                   $("#totalLazada"+index).hide();
               }
               else
               {
                    $("#totalLazada"+index).show();
                    $("#totalLazada"+index).html($('#dataGridLazada'+index).DataTable().data().count());
               }
               recountCetakdanKirim(); 
               Swal.close();
           }, "500");
        }
        else
        {
            //JIKA SUDAH DONE SEMUA MAKA SINKRON STATE FALSE, JIKA TIDAK DIKEMBALIKAN TRUE
            sinkronLazadaState = false;
            doneSinkronLazada[index] = true;
            for(var x = 1; x <= 4 ; x++)
            {
                if(!doneSinkronLazada[x]){
                    sinkronLazadaState = true;
                }
            }
            
            if(!sinkronLazadaState)
            {
                Swal.close();
                
                setTimeout(() => {
                    var caption = "Tidak Ada Pesanan Baru";
                    if(totalPesananLazadaAll > 0)
                    {
                        caption = 'Terdapat '+totalPesananLazadaAll+' Pesanan Baru'
                    }
                    
                    for(var x = 1; x <= 4 ; x++)
                    {
                       if($('#dataGridLazada'+x).DataTable().data().count() == 0)
                       {
                           $("#totalLazada"+x).hide();
                       }
                       else
                       {
                            $("#totalLazada"+x).show();
                            $("#totalLazada"+x).html($('#dataGridLazada'+x).DataTable().data().count());
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
    var data = $("#dataGridLazada1").DataTable().rows().data();
    var countCetak = 0;
    var countKirim = 0;
    for(var x = 0; x < data.length; x++)
    {
        if((data[x]['STATUS'].toUpperCase() == "SIAP DIKEMAS" || data[x]['STATUS'].toUpperCase() == "DIKEMAS" || data[x]['STATUS'].toUpperCase() == "PROSES KIRIM" || data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM") && data[x]['KURIR'].toUpperCase() != "DELIVERED BY SELLER")
        {
            countCetak++;
        }
        
        if(data[x]['STATUS'].toUpperCase() == "DIKEMAS"  && data[x]['KURIR'].toUpperCase() != "DELIVERED BY SELLER")
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

function lihatLazada(){
    var row = JSON.parse($("#rowDataLazada").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Lazada/loadDetail/',
    	data    : {kode: row.KODEPESANAN,metodebayar : row.METODEBAYAR},
    	dataType: 'json',
    	success : function(msg){
    	    $("#cetakLazadaDetail").hide();
    	    $("#hapusLazadaDetail").hide();
    	    $("#ubahLazadaDetail").hide();
    	    $("#kirimLazadaDetail").hide();
    	    $("#lacakLazadaDetail").hide();
            $("#DETAILINFORMASIPENJUALLAZADA").hide();
            $("#ADDINFOINFORMASIPENJUAL").show();
            
            if(row.STATUS.toUpperCase() == "SELESAI")
            {
               $("#DETAILINFORMASIPENJUALLAZADA").show();
               $("#ADDINFOINFORMASIPENJUAL").hide();
            }
            
    	    if(row.STATUS.toUpperCase() == "DIKEMAS" && row.KURIR.toUpperCase() != "DELIVERED BY SELLER")
    	    {
    	        $("#kirimLazadaDetail").show();
    	    }
    	    if(row.STATUS.toUpperCase() == "SIAP DIKEMAS" || row.STATUS.toUpperCase() == "DIKEMAS" || row.STATUS.toUpperCase() == "PROSES KIRIM" || row.STATUS.toUpperCase() == "SIAP DIKIRIM")
    	    {
    	        $("#hapusLazadaDetail").show();
    	        $("#ubahLazadaDetail").show();
    	        if(row.KURIR.toUpperCase() != "DELIVERED BY SELLER")
    	        {
    	            $("#cetakLazadaDetail").show();
    	        }
    	    }
    	    if(row.STATUS.toUpperCase() == "DALAM PENGIRIMAN" || row.STATUS.toUpperCase() == "GAGAL PENGIRIMAN")
    	    {
    	        $("#lacakLazadaDetail").show();
    	    }
    	    
            $("#NOLAZADA").html("#"+row.KODEPESANAN);
            $("#STATUSLAZADA").html(row.STATUS);
            $("#TGLPESANANLAZADA").html(row.TGLPESANAN.replaceAll("<br>"," "));
            $("#TGLKIRIMLAZADA").html(row.KURIR==""?"-":row.MINTGLKIRIM);
            $("#PEMBAYARANLAZADA").html(row.METODEBAYAR);
            $("#KURIRLAZADA").html((row.KURIR==""?"-":row.KURIR)+" / "+(row.RESI==""?"-":row.RESI));
            $("#NAMAPEMBELILAZADA").html(row.BUYERNAME+" ("+row.USERNAME+")");
            $("#TELPPEMBELILAZADA").html(row.BUYERPHONE);
            $("#ALAMATPEMBELILAZADA").html(row.BUYERALAMAT);
            $("#CATATANPEMBELILAZADA").html(row.CATATANBELI);
            $("#CATATANPEMBELILAZADA").html($("#CATATANPEMBELILAZADA div").html()==""?"<div>-</div>":row.CATATANBELI);
            $("#ALASANPENGEMBALIANLAZADA").html(row.CATATANPENGEMBALIAN);
            
            if($("#ALASANPENGEMBALIANLAZADA div").html() != "")
            {
                $(".alasanKembaliLazada").show();
                $("#ALASANPENGEMBALIANLAZADA").html(row.CATATANPENGEMBALIAN);
            }
            else
            {
                $(".alasanKembaliLazada").hide();
                $("#ALASANPENGEMBALIANLAZADA").html("<div>-</div>");
            }
            
            $(".noKembaliLazada").show();
            if(row.KODEPENGEMBALIAN != null)
            {
                $(".noKembaliLazada").show();
                $("#NOPENGEMBALIANLAZADA").html(row.KODEPENGEMBALIAN);
            }
            
            $(".table-responsive-lazada").html('');
            
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
                
                $(".table-responsive-lazada").append(setDetail(msg.DETAILBARANG,x,namaBarang,false));
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
                totalCurrKembali += parseInt(msg.DETAILBARANG[x].JUMLAHKEMBALI);
            }
            var totalKembali = "";
            if(totalCurrKembali > 0 && row.BARANGSAMPAI == 1)
            {
                totalKembali = "<span style='color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>' > (-"+currency(totalCurrKembali.toString())+")</span>";
            }
            $("#TOTALQTYLAZADA").html(currency(totalCurr)+totalKembali);
            $("#SUBTOTALLAZADA").html(currency(msg.SUBTOTALBELI));
           
            $("#TOTALPENJUALLAZADA").html(currency(msg.SUBTOTALBELI));
            $("#DISKONPENJUALLAZADA").html(currency(msg.DISKONJUAL));
            $("#BIAYAKIRIMPENJUALLAZADA").html(currency(msg.BIAYAKIRIMJUAL));
            $("#BIAYALAYANANPENJUALLAZADA").html(currency(msg.BIAYALAYANANJUAL));
            $("#GRANDTOTALPENJUALLAZADA").html(currency(msg.PENERIMAANJUAL));
            $("#REFUNDPENJUALLAZADA").html(currency(msg.REFUNDJUAL));
            $("#PENYELESAIANPENJUALLAZADA").html(currency(msg.PENYELESAIANPENJUAL));
           
           
           $("#TOTALPEMBELILAZADA").html(currency(msg.SUBTOTALBELI));
           $("#DISKONPEMBELILAZADA").html(currency(msg.DISKONBELI));
           $("#BIAYAKIRIMPEMBELILAZADA").html(currency(msg.BIAYAKIRIMBELI));
           $("#BIAYALAINPEMBELILAZADA").html(currency(msg.BIAYALAINBELI));
           $("#PEMBAYARANPEMBELILAZADA").html(currency(msg.PEMBAYARANBELI));
           if(row.STATUS == "Selesai" || row.STATUS == "Pembatalan")
           {
               $(".penyelesaianLazada").show();
           }
           else
           {
               $(".penyelesaianLazada").hide();
           }
            Swal.close();
            $("#modal-form-lazada").modal('show');
    			
    	}
    });
}

function kembaliLazada(){
    var row = JSON.parse($("#rowDataLazada").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Lazada/loadDetailPengembalian/',
    	data    : {kode: row.KODEPENGEMBALIAN},
    	dataType: 'json',
    	success : function(msg){
    	    
    		$("#dataReturLazada").val(JSON.stringify(msg));	
    		
            $("#NOLAZADAPENGEMBALIAN").html("#"+row.KODEPENGEMBALIAN);
            $("#NOLAZADAPESANANPENGEMBALIAN").html(row.KODEPESANAN);
            $("#STATUSLAZADAPENGEMBALIAN").html(row.STATUS.replaceAll("<br>"," "));
            $("#TGLLAZADAPENGEMBALIAN").html(row.TGLPENGEMBALIAN.replaceAll("<br>"," "));
            if( row.MINTGLPENGEMBALIAN == "0000-00-00 00:00:00")
            {
                $("#MINTGLLAZADAPENGEMBALIAN").html("-");
            }
            else
            {
                $("#MINTGLLAZADAPENGEMBALIAN").html(row.MINTGLPENGEMBALIAN.replaceAll("<br>"," "));
            }
            $("#RESILAZADAPENGEMBALIAN").html((row.RESIPENGEMBALIAN==""?"-":row.RESIPENGEMBALIAN));
            $("#NAMAPEMBELILAZADAPENGEMBALIAN").html(row.BUYERNAME+" ("+row.USERNAME+")");
            $("#TELPPEMBELILAZADAPENGEMBALIAN").html(row.BUYERPHONE);
            $("#ALAMATPEMBELILAZADAPENGEMBALIAN").html(row.BUYERALAMAT);
            $("#ALASANLAZADAPILIHAN").html(msg.ALASANPILIHPENGEMBALIAN);
            $("#ALASANLAZADAPENGEMBALIAN").html(row.CATATANPENGEMBALIAN);
            $("#ALASANLAZADAPENGEMBALIAN").html($("#ALASANLAZADAPENGEMBALIAN div").html()==""?"<div>-</div>":row.CATATANPENGEMBALIAN);
            
            $(".table-responsive-lazada-pengembalian").html('');
            
            $("#returLazadaDetail").hide();
            $("#returLazadaWait").show();
            
            if(row.TIPEPENGEMBALIAN.toUpperCase() == "RETURN_DELIVERED")
            {
                $("#STATUSLAZADAPENGEMBALIAN").html($("#STATUSLAZADAPENGEMBALIAN").html()+"<br>&nbsp;<i style='color:red;'>(Barang Telah Diterima Penjual)</i>");
            }
            
            if (row.STATUS.toUpperCase() == "PENGEMBALIAN<BR>DIAJUKAN" || row.TIPEPENGEMBALIAN.toUpperCase() == "RETURN_DELIVERED") {
                $("#returLazadaDetail").show();
                $("#returLazadaWait").hide();
            }
            
            if (row.STATUS.toUpperCase() == "PENGEMBALIAN<BR>DIPROSES" &&  row.TIPEPENGEMBALIAN.toUpperCase() != "RETURN_DELIVERED") {
                $("#returLazadaWait").html("Menunggu Barang Tiba");
            }
            
            if (row.STATUS.toUpperCase() == "PENGEMBALIAN<BR>DALAM SENGKETA" || row.STATUSPENGEMBALIAN.toUpperCase() == "REFUND_PENDING") {
                $("#returLazadaDetail").hide();
                $("#returLazadaWait").show();
                $("#returLazadaWait").html("Menunggu Respon Lazada");
            }
            
            var totalCurr = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
                var namaBarang = msg.DETAILBARANG[x].NAMA;
                if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
                {
                    namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
                }
                
                $(".table-responsive-lazada-pengembalian").append(`<tr>
                	<td style="vertical-align:middle; text-align:left;" width="400px" >`+namaBarang+`</td>
                  	<td style="vertical-align:middle; text-align:center;" width="50px">`+currency(msg.DETAILBARANG[x].JUMLAH.toString())+`</td>
                  	<td style="vertical-align:middle; text-align:center;" width="50px">`+msg.DETAILBARANG[x].SATUAN.toString()+`</td>
                  	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(msg.DETAILBARANG[x].HARGA.toString())+`</td>
                  	<td style="vertical-align:middle; text-align:right;" width="100px">`+currency(msg.DETAILBARANG[x].SUBTOTAL.toString())+`</td>
                </tr>`);
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
            }
            
            $("#TOTALQTYPENGEMBALIANLazada").html(currency(totalCurr));
            $("#SUBTOTALPENGEMBALIANLazada").html(currency(msg.TOTALREFUND));
            
            var buktiGambar = "";
            for(var x = 0 ; x < msg.GAMBAR.length;x++)
            {
                buktiGambar += "<span style='color : blue; cursor:pointer; text-align:center;' onclick='lihatLebihJelasLazada(`GAMBAR`,`Gambar "+(x+1)+"`,`"+msg.GAMBAR[x]+"`)' >Gambar "+(x+1)+"</span><br>";
            }
            $("#GAMBARPENGEMBALIANLAZADA").html(buktiGambar);
            
            var buktiVideo = "";
            for(var x = 0 ; x < msg.VIDEO.length;x++)
            {
                buktiVideo += "<span style='color : blue; cursor:pointer; text-align:center;' onclick='lihatLebihJelasLazada(`VIDEO`,`Video "+(x+1)+"`,`"+msg.VIDEO[x]['video_url']+"`)' >Video "+(x+1)+"</span><br>";
            }
            $("#VIDEOPENGEMBALIANLAZADA").html(buktiVideo);
            
            Swal.close();
            $("#modal-pengembalian-lazada").modal('show');
    	}
    });
}

function lihatLebihJelasLazada(jenis,title,url){

    $("#modal-lebih-jelas-lazada").modal("show");
    $("#titleLebihJelasLazada").html(title);
    $("#previewLebihJelasLazada").css("color","#3296ff");
    $("#previewLebihJelasLazada").css("cursor","pointer");
    $("#previewLebihJelasLazada").css("text-align","center");
    $("#previewLebihJelasLazada").css("background","#d4d4d7");
    if(jenis == "GAMBAR")
    {
        $("#previewLebihJelasLazada").html("<img src='"+url+"' max-width=100%; height=600px;>");
    }
    else
    {
        $("#previewLebihJelasLazada").html("<iframe src='"+url+"' max-width=100%; height=600px;>");
    }
}

// function ubahLazada(){
//     $("#modal-form-lazada").modal('hide');
//     var row = JSON.parse($("#rowDataLazada").val());
//     loading();
//     $.ajax({
//     	type    : 'POST',
//     	url     : base_url+'Lazada/loadDetail/',
//     	data    : {kode: row.KODEPESANAN},
//     	dataType: 'json',
//     	success : function(msg){
//             $("#NOLAZADAUBAH").html("#"+row.KODEPESANAN);
//             $(".table-responsive-lazada-ubah").html('');
            
//             var totalCurr = 0;
//             for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
//             {
//                 var namaBarang = msg.DETAILBARANG[x].NAMA;
//                 if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
//                 {
//                     namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
//                 }
                
//                 $(".table-responsive-lazada-ubah").append(setDetail(msg.DETAILBARANG,x,namaBarang,true));
//                 totalCurr += msg.DETAILBARANG[x].JUMLAH;
//             }
//             $("#TOTALQTYLAZADAUBAH").html(currency(totalCurr));
//             $("#SUBTOTALLAZADAUBAH").html(currency(msg.SUBTOTALBELI));
//             Swal.close();
//             $("#itemUbahLazada").val(JSON.stringify(msg.DETAILBARANG));
//             $("#modal-ubah-lazada").modal('show');
//     	}
//     });
// }

function openItemLazada(indexItem){
    var itemDetail = JSON.parse($("#itemUbahLazada").val());
    $("#kategori_item_lazada").val(itemDetail[indexItem].KATEGORI);
    $("#warnaOldLazada").html(itemDetail[indexItem].WARNAOLD);
    $("#sizeOldLazada").html(itemDetail[indexItem].SIZEOLD);
    $("#table_barang_lazada").DataTable().ajax.reload();
    $("#modal-barang-lazada").modal('show');
}

function resetItemLazada(indexItem){
    var itemDetail = JSON.parse($("#itemUbahLazada").val());
    
    itemDetail[indexItem]['NAMA']   = itemDetail[indexItem]['NAMAOLD'];
    itemDetail[indexItem]['WARNA']  = itemDetail[indexItem]['WARNAOLD'];
    itemDetail[indexItem]['SIZE']   = itemDetail[indexItem]['SIZEOLD'];
    itemDetail[indexItem]['SKU']    = itemDetail[indexItem]['SKUOLD'];

    $(".table-responsive-lazada-ubah").html('');
    
    for(var x = 0 ; x < itemDetail.length ; x++)
    {
        var namaBarang = itemDetail[x].NAMA;
        if(itemDetail[x].SIZE != itemDetail[x].SIZEOLD || itemDetail[x].WARNA != itemDetail[x].WARNAOLD)
        {
            namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span style='color:#949494; font-style:italic;'>Marketplace : "+itemDetail[x].WARNAOLD+" / "+itemDetail[x].SIZEOLD+"</span>");
        }
            	 
       $(".table-responsive-lazada-ubah").append(setDetail(itemDetail,x,namaBarang,true));        
    }
     
     
    $("#itemUbahLazada").val(JSON.stringify(itemDetail));
}

function ubahKonfirmLazada(){
     Swal.fire({
        title: 'Anda Yakin Mengubah Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var row = JSON.parse($("#rowDataLazada").val());
                loading();
                $.ajax({
                	type    : 'POST',
                	url     : base_url+'Lazada/ubah/',
                	data    : {kode: row.KODEPESANAN, dataItem: $("#itemUbahLazada").val()},
                	dataType: 'json',
                	success : function(msg){
                	    
                        if(msg.success)
                        {
                            Swal.close();
                            $("#modal-ubah-lazada").modal('hide');
                        }
                        
                	    Swal.fire({
                        	title            :  msg.msg,
                        	type             : (msg.success?'success':'error'),
                        	showConfirmButton: false,
                        	timer            : 2000
                        });
                        
                        setTimeout(() => {
                          reloadLazada();
                        }, "2000");
                	}
                });
        	}
        });
}

function setDetail(itemDetail,x,namaBarang,action=false)
{
    var row = JSON.parse($("#rowDataLazada").val());
    var actButton = '';
    var jmlKembali = '';
    if(action)
    {
       actButton = `<td style="vertical-align:middle; text-align:center;" width="103px" ><button id="btn_edit_detail_lazada" class="btn btn-primary" onclick="openItemLazada(`+x+`)"><i class="fa fa-edit"></i></button> <button id="btn_back_detail_lazada" class="btn btn-danger" onclick="resetItemLazada(`+x+`)"><i class="fa fa-refresh"></i></button></td>`;
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

function cetakLazada(){
    $("#modal-form-lazada").modal('hide');
    var row = JSON.parse($("#rowDataLazada").val());
    var rows = [{order_number : row.KODEPESANAN, package_id : row.KODEPACKAGING}];
    loading();
     $.ajax({
     	type    : 'POST',
     	url     : base_url+'Lazada/print/',
     	data    : {dataNoPesanan: JSON.stringify(rows)},
     	dataType: 'json',
     	success : function(msg){
     	        
     	        if(msg.success)
                {
                    Swal.close();
                    $("#modal-note-lazada").modal('hide');
                    
                 	setTimeout(() => {
                      reloadLazada();
                      $("#countCetak").html("("+rows.length+")");
                      $("#previewCetakLazada").html("<iframe src='"+msg.merge_url+"' width=100%; height=600px;>");
                      $("#modal-cetak-lazada").modal('show');
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

function cetakLazadaSemua(index){
    $("#modal-cetak-all-lazada").modal('show');
    $("#pilihCetakAllLazada").prop("checked",true);
    var data = $("#dataGridLazada"+index).DataTable().rows().data();
    var detailData = "";
    var dataSimpan = [];
    for(var x = 0; x < data.length; x++)
    {
        if((data[x]['STATUS'].toUpperCase() == "SIAP DIKEMAS" || data[x]['STATUS'].toUpperCase() == "DIKEMAS" || data[x]['STATUS'].toUpperCase() == "PROSES KIRIM" || data[x]['STATUS'].toUpperCase() == "SIAP DIKIRIM") && data[x]['KURIR'].toUpperCase() != "DELIVERED BY SELLER")
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
    $("#dataCetakSemuaLazada").val(JSON.stringify(dataSimpan));
    $(".table-responsive-lazada-all-cetak").html(detailData);
    
    for(var x = 0; x < dataSimpan.length; x++)
    {
     $('#cetak'+x).change(function () {
         var count = 0;
         for(var x = 0; x < dataSimpan.length; x++)
         {
             if($(".table-responsive-lazada-all-cetak").find("#cetak"+x).is(':checked'))
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
             $("#pilihCetakAllLazada").prop("checked",true);
         }
         else
         {
             $("#pilihCetakAllLazada").prop("checked",false);
         }
      });
    }
    
    $("#pilihCetakAllLazada").change(function(){
        for(var x = 0; x < dataSimpan.length; x++)
         {
            $(".table-responsive-lazada-all-cetak").find("#cetak"+x).prop("checked",$(this).prop("checked"));
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

function cetakAllKonfirmLazada(){
    var dataSimpan = JSON.parse($("#dataCetakSemuaLazada").val());
    var rows = [];
    for(var x = 0; x < dataSimpan.length; x++)
    {
        if($(".table-responsive-lazada-all-cetak").find("#cetak"+x).is(':checked'))
        {
           rows.push({order_number:dataSimpan[x].KODEPESANAN,package_id : dataSimpan[x].KODEPACKAGING});
        }
    }
    if(rows.length > 0)
    {
        loading();
        $.ajax({
         	type    : 'POST',
         	url     : base_url+'Lazada/print/',
         	data    : {dataNoPesanan: JSON.stringify(rows)},
         	dataType: 'json',
         	success : function(msg){
         	        
         	        if(msg.success)
                    {
                        Swal.close();
                        $("#modal-note-lazada").modal('hide');
                        $("#modal-cetak-all-lazada").modal('hide');
                        
                     	setTimeout(() => {
                          reloadLazada();
                          $("#countCetak").html("("+rows.length+")");
                          var iframe = "";
                          iframe += "<iframe id='LazadaCETAK"+x+"' src='"+msg.merge_url+"' width=100%; height=600px;/><br><br>";
                          $("#previewCetakLazada").html(iframe);
                          $("#modal-cetak-lazada").modal('show');
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


function kirimLazada(){
    $("#modal-form-lazada").modal('hide');
    var row = JSON.parse($("#rowDataLazada").val());
    var rows = [row];
    loading();
    
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Lazada/cekStokLokasi/',
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
                Swal.close();
                $("#modal-kirim-lazada").modal('show');
                
                var countPengiriman = 1;
                $("#countAturPengiriman").html("("+countPengiriman.toString()+")");
                $(".table-responsive-lazada-kirim").html('');
                var indexKirim = 0;
                
                var dataKirim = ` <tr>
                        	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px" >`+rows[indexKirim].KURIR+`</td>
                          	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="150px">`+rows[indexKirim].KODEPESANAN+`</td>
                          	<td style="vertical-align:top; text-align:center; padding-top:17px;" width="80px">`+currency(rows[indexKirim].TOTALBARANG)+`</td>
                          	<td style="vertical-align:top; text-align:center;  padding-top:17px;" width="150px">`+rows[indexKirim].RESI+` </td>
                          	<td style="vertical-align:top; text-align:center;  padding-top:17px;" width="150px">	`+rows[indexKirim].MINTGLKIRIM+` </td>
                          	<td style="vertical-align:top; text-align:left; padding-top:17px;" id="editNoteLazadaDiv`+indexKirim+`">`+rows[indexKirim].CATATANJUAL+`</td>
                        </tr>`;
                
                $(".table-responsive-lazada-kirim").append(dataKirim);
                
                $("#rowDataPengirimanLazada").val(JSON.stringify(rows));
                $('#editNoteLazadaDiv'+indexKirim).find('#editNoteLazada').click(function(){
                   $("#fromNoteLazada").val("KIRIMLAZADA_"+indexKirim);
                    catatanPenjualLazada();
                });
        }
    }});
 
}

function kirimKonfirmLazada(){
    
    Swal.fire({
        title: 'Anda Yakin Mengirim Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var row = JSON.parse($("#rowDataLazada").val());
                var rows = [{order_number : row.KODEPESANAN, package_id : row.KODEPACKAGING}];
                
                loading();
                $.ajax({
                 	type    : 'POST',
                 	url     : base_url+'Lazada/kirim/',
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
                             $("#modal-kirim-lazada").modal('hide');
                            	
                          	setTimeout(() => {
                            reloadLazada();
                          }, "2000");
                 	}
                 });
                }
        });
}

function kirimLazadaSemua() {
    loading();
    $.ajax({
        type: 'POST',
        url: base_url + 'Lazada/cekStokLokasi/',
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
                $("#pilihKirimanAllKurirLazada").prop('checked', true);
                $("#modal-kirim-all-lazada").modal('show');
                $('#tab_kirim_lazada a:first').tab('show');

                var data = $("#dataGridLazada1").DataTable().rows().data();
                var dataSimpan = [];
                var dataPerKurir = [];

                for (var x = 0; x < data.length; x++) {
                    if (data[x]['STATUS'].toUpperCase() === "DIKEMAS"  && data[x]['KURIR'].toUpperCase() != "DELIVERED BY SELLER") {
                        dataSimpan.push(data[x]);
                    }
                }

                if (dataSimpan.length > 0) {
                    $("#keteranganKurirLazada").show();
                    $("#countAturSemuaPengirimanLazada").html("(" + dataSimpan.length + ")");
                } else {
                    $("#keteranganKurirLazada").hide();
                    $("#countAturSemuaPengirimanLazada").html("");
                }

                // Group by KURIR
                for (var x = 0; x < dataSimpan.length; x++) {
                    var ada = false;
                    for (var y = 0; y < dataPerKurir.length; y++) {
                        if (dataSimpan[x]['KURIR'] === dataPerKurir[y]["name"]) {
                            ada = true;
                            break;
                        }
                    }
                    if (!ada) {
                        dataPerKurir.push({
                            name: dataSimpan[x]['KURIR'],
                            order: [],
                            loading_done: false
                        });
                    }
                }

                $("#countAllPesananLazada").html(dataSimpan.length);
                $("#countAllKurirLazada").html(dataPerKurir.length);

                var detailPesanan = "";
                for (var y = 0; y < dataPerKurir.length; y++) {
                    var index = 0;

                    for (var x = 0; x < dataSimpan.length; x++) {
                        if (dataSimpan[x]['KURIR'] === dataPerKurir[y]["name"]) {
                            if (index === 0) {
                                detailPesanan += `
                                    <table class="table table-bordered table-striped table-hover display nowrap" width="100%" style="background:#CFECF7; border-color:#CFECF7; margin:0px; border-collapse: collapse;">
                                        <tr style="border-color:#CFECF7;">
                                            <td style="text-align:center;" width="45px">
                                                <input type="checkbox" id="pilihKirimanAllLAZADA_${y}" checked>
                                            </td>
                                            <td colspan="3" style="font-weight:bold; font-size:14pt;">${dataPerKurir[y]["name"]}</td>
                                            <td style="text-align:center;">
                                                <div style="width:250px; margin-top:2px;">
                                                    Terdapat &nbsp;<span id="countKurirAllLAZADA_${y}" style="font-weight:bold; font-size:14pt;">...</span>&nbsp; Pesanan
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                    <table id="dataGridDetailAllLazadaKirim${y}" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="70px"></th>
                                                <th style="text-align:center" width="150px">No. Pesanan</th>
                                                <th style="text-align:center" width="80px">T. Qty</th>
                                                <th style="text-align:center" width="150px">No. Resi</th>
                                                <th style="text-align:center" width="150px">Min Tgl Kirim</th>
                                                <th style="text-align:center" >Catatan Penjual</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-responsive-lazada-all-kirim_${y}"></tbody>
                                    </table><br>
                                `;
                            }
                            dataPerKurir[y]["order"].push(dataSimpan[x]);
                            index++;
                        }
                    }
                }

                $("#dataGridDetailAllLazada").html(detailPesanan);

                // Checkbox: Pilih Semua
                $("#pilihKirimanAllKurirLazada").change(function () {
                    for (var index = 0; index < dataPerKurir.length; index++) {
                        $("#pilihKirimanAllLAZADA_" + index).prop("checked", $(this).prop("checked"));
                        for (var c = 0; c < dataPerKurir[index]['order'].length; c++) {
                            $(".table-responsive-lazada-all-kirim_" + index + " #pilihKirimanLAZADA_" + c).prop("checked", $(this).prop("checked"));
                        }
                    }
                    recountPengiriman();
                });

                // LOOP PER KURIR
                for (let i = 0; i < dataPerKurir.length; i++) {
                    let detailKirim = "";
                    let items = dataPerKurir[i]['order'];
                    $("#countKurirAllLAZADA_" + i).html(items.length);

                    for (let indexKirim = 0; indexKirim < items.length; indexKirim++) {
                        detailKirim += `
                            <tr>
                                <td style="text-align:center; padding-top:17px;" width="70px">
                                    <input type="checkbox" id="pilihKirimanLAZADA_${indexKirim}" checked>
                                </td>
                                <td style="text-align:center; padding-top:17px;" width="150px">${items[indexKirim].KODEPESANAN}</td>
                                <td style="text-align:center; padding-top:17px;" width="80px">${currency(items[indexKirim].TOTALBARANG)}</td>
                                <td style="text-align:center; padding-top:17px;" width="150px">${items[indexKirim].RESI}</td>
                                <td style="text-align:center; padding-top:17px;" width="150px">${items[indexKirim].MINTGLKIRIM}</td>
                                <td style="text-align:left; padding-top:17px;" id="editNoteLazadaDiv_${indexKirim}">${items[indexKirim].CATATANJUAL}</td>
                            </tr>
                        `;
                    }

                    $(".table-responsive-lazada-all-kirim_" + i).html(detailKirim);

                    // Handler checkbox per kurir
                    $("#pilihKirimanAllLAZADA_" + i).change(function () {
                        let index = i;
                        for (let c = 0; c < dataPerKurir[index]['order'].length; c++) {
                            $(".table-responsive-lazada-all-kirim_" + index + " #pilihKirimanLAZADA_" + c).prop("checked", $(this).prop("checked"));
                        }

                        // Sync with "all kurir" checkbox
                        let count = dataPerKurir.filter((_, idx) => $("#pilihKirimanAllLAZADA_" + idx).prop("checked")).length;
                        $("#pilihKirimanAllKurirLazada").prop("checked", count === dataPerKurir.length);

                        recountPengiriman();
                    });

                    // Handler checkbox per order
                    items.forEach((_, indexKirim) => {
                        $(".table-responsive-lazada-all-kirim_" + i + " #pilihKirimanLAZADA_" + indexKirim).change(function () {
                            let index = i;
                            let checkedCount = 0;
                            for (let c = 0; c < dataPerKurir[index]['order'].length; c++) {
                                if ($(".table-responsive-lazada-all-kirim_" + index + " #pilihKirimanLAZADA_" + c).prop("checked")) {
                                    checkedCount++;
                                }
                            }

                            $("#pilihKirimanAllLAZADA_" + index).prop("checked", checkedCount === dataPerKurir[index]['order'].length);

                            // Sync with all kurir checkbox
                            let allChecked = true;
                            for (let z = 0; z < dataPerKurir.length; z++) {
                                if (!$("#pilihKirimanAllLAZADA_" + z).prop("checked")) {
                                    allChecked = false;
                                    break;
                                }
                            }
                            $("#pilihKirimanAllKurirLazada").prop("checked", allChecked);

                            recountPengiriman();
                        });
                        
                        $(".table-responsive-lazada-all-kirim_"+i+" #editNoteLazadaDiv_"+indexKirim).find('#editNoteLazada').click(function(){
                          var indexKirim = this.parentNode.id.split("_")[this.parentNode.id.split("_").length-1];
                          
                          $("#rowDataPengirimanLazada").val(JSON.stringify(dataPerKurir[$(this).closest('tbody').attr("class").split("_")[1]]['order']));
                          $("#fromNoteLazada").val("KIRIMLAZADA_"+indexKirim+"_"+$(this).closest('tbody').attr("class").split("_")[1]);
                            catatanPenjualLazada();
                        });
                    });
                            
                }
            }
        }
    });
}
function recountPengiriman() {
    var data = $("#dataGridLazada1").DataTable().rows().data();
    var dataSimpan = [];
    var dataPerKurir = [];
    
    for(var x = 0; x < data.length; x++)
    {
        if(data[x]['STATUS'].toUpperCase() == "DIKEMAS"  && data[x]['KURIR'].toUpperCase() != "DELIVERED BY SELLER")
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
                if($(".table-responsive-lazada-all-kirim_"+y+" #pilihKirimanLAZADA_"+index).prop('checked'))
                {
                    countSemuaPengiriman++;
                }
            index++;
            }
        }
    }
    if(countSemuaPengiriman > 0)
    {
        $("#countAturSemuaPengirimanLazada").html("("+countSemuaPengiriman.toString()+")");
    }
    else
    {
        $("#countAturSemuaPengirimanLazada").html("");
    }
}

function kirimKonfirmAllLazada(){
    Swal.fire({
        title: 'Anda Yakin Mengirim Semua Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                var data = $("#dataGridLazada1").DataTable().rows().data();
                var rows = [];
                for(var x = 0; x < data.length; x++)
                {
                    if(data[x]['STATUS'].toUpperCase() == "DIKEMAS"  && data[x]['KURIR'].toUpperCase() != "DELIVERED BY SELLER")
                    {
                        rows.push({order_number : data[x]['KODEPESANAN'], package_id : data[x]['KODEPACKAGING']});
                    }
                }
                
                var data = $("#dataGridLazada1").DataTable().rows().data();
                var detailData = "";
                var dataSimpan = [];
                var dataPerKurir = [];
                var rows = [];
                for(var x = 0; x < data.length; x++)
                {
                    if(data[x]['STATUS'].toUpperCase() == "DIKEMAS"  && data[x]['KURIR'].toUpperCase() != "DELIVERED BY SELLER")
                    {
                        dataSimpan.push({order_number : data[x]['KODEPESANAN'], KURIR : data[x]['KURIR'],  package_id : data[x]['KODEPACKAGING']});
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
                            if($(".table-responsive-lazada-all-kirim_"+y+" #pilihKirimanLAZADA_"+index).prop('checked'))
                            {
                                rows.push(dataSimpan[x]);
                            }
                            index++;
                        }
                    }
                }
                
                loading();
                $.ajax({
                 	type    : 'POST',
                 	url     : base_url+'Lazada/kirim/',
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
                             $("#modal-kirim-all-lazada").modal('hide');
                            	
                          	setTimeout(() => {
                            reloadLazada();
                          }, "2000");
                 	}
                 });
        	}
    });
}

function lacakLazada(){
    $("#modal-form-lazada").modal('hide');
    var row = JSON.parse($("#rowDataLazada").val());
    
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Lazada/setLacak/',
    	data    : {kode: row.KODEPESANAN},
    	dataType: 'json',
    	success : function(msg){
    	        Swal.close();
    	      
            
                $("#modal-lacak-lazada").modal('show');
                
                $("#NOLAZADALACAK").html("#"+row.KODEPESANAN);
                $("#KURIRLACAKLAZADA").html(row.KURIR);
                $("#METODEBAYARLACAKLAZADA").html(row.METODEBAYAR);
                $("#RESILACAKLAZADA").html(row.RESI);
                $("#ALAMATLACAKLAZADA").html(row.BUYERALAMAT);
                $("#TGLKIRIMLACAKLAZADA").html("-"); 
                
                var stepTracker = "";
                for(var x = msg.length-1 ; x >= 0;x--)
                {
                    if(x==msg.length-1)
                    {
                        stepTracker += `<div class="step active"><div class="circle">&nbsp</div><div class="label-step" style="font-weight:bold;">`+msg[x]['description']+`<br><span style="color:#949494; font-style:italic;">`+msg[x]['event_time']+`</span></div></div>`;
                    }
                    else
                    {
                        stepTracker += `<div class="step"><div class="circle">&nbsp</div><div class="label-step">`+msg[x]['description']+`<br><span style="color:#949494; font-style:italic;">`+msg[x]['event_time']+`</span></div></div>`;
                    }
                    
                    if(msg[x]['detail_type'] == "picked_up" || (msg[x]['detail_type'] == "ship_info" && msg[x]['status_code'] == 100018))
                    {
                       //PASTI YANG INDEX TERAKHIR
                        $("#TGLKIRIMLACAKLAZADA").html(msg[x]['event_time']); 
                    }
                }
                
                
                $(".step-tracker").html(stepTracker);
            
    	}
    });
}

function hapusLazada(){
    $("#modal-form-lazada").modal('hide');
    var row = JSON.parse($("#rowDataLazada").val());
    loading();
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Lazada/loadDetail/',
    	data    : {kode: row.KODEPESANAN,metodebayar : row.METODEBAYAR},
    	dataType: 'json',
    	success : function(msg){
    	    $('#cb_alasan_pembatalan_lazada').val('');
            $("#NOLAZADABATAL").html("#"+row.KODEPESANAN);
            $(".table-responsive-lazada-batal").html('');
            
            var totalCurr = 0;
            for(var x = 0 ; x < msg.DETAILBARANG.length ; x++)
            {
              var namaBarang = msg.DETAILBARANG[x].NAMA;
              if(msg.DETAILBARANG[x].SIZE != msg.DETAILBARANG[x].SIZEOLD || msg.DETAILBARANG[x].WARNA != msg.DETAILBARANG[x].WARNAOLD)
              {
                  namaBarang += ("&nbsp&nbsp&nbsp&nbsp<span  style='color:#949494; font-style:italic;'>Marketplace : "+msg.DETAILBARANG[x].WARNAOLD+" / "+msg.DETAILBARANG[x].SIZEOLD+"</span>");
              }
          
                $(".table-responsive-lazada-batal").append(setDetail(msg.DETAILBARANG,x,namaBarang,false));
                totalCurr += msg.DETAILBARANG[x].JUMLAH;
            }
            $("#TOTALQTYLAZADABATAL").html(currency(totalCurr));
            $("#SUBTOTALLAZADABATAL").html(currency(msg.SUBTOTALBELI));
            $("#itemBatalLazada").val(JSON.stringify(msg.DETAILBARANG));
             $.ajax({
            	type    : 'POST',
            	url     : base_url+'Lazada/getAlasanPembatalan/',
            	data    : {kode: row.KODEPESANAN},
            	dataType: 'json',
            	success : function(msg){
                    Swal.close();
                    var option = '<option value="">- Pilih Alasan -</option>';
          			for(var x = 0 ; x < msg.data.length ; x++)
          			{
          			    option += '<option value="'+msg.data[x].reason_id+'">'+msg.data[x].reason_name+'</option>';
          			}
      			
                    $("#cb_alasan_pembatalan_lazada").html(option);
                    $("#itemBatalLazada").val(JSON.stringify(msg.DETAILBARANG));
                    $("#modal-alasan-lazada").modal('show');
                }
                 
             });
    	}
    });
}

function hapusKonfirmLazada(){
    Swal.fire({
        title: 'Anda Yakin Membatalkan Pesanan Ini ?',
        showCancelButton: true,
        confirmButtonText: 'Yakin',
        cancelButtonText: 'Tidak',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        	if (result.value) {
                if($('#cb_alasan_pembatalan_lazada').val() == "")
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
                    $("#modal-alasan-lazada").modal('hide');
                    loading()
                     $.ajax({
                    	type    : 'POST',
                    	url     : base_url+'Lazada/hapus/',
                    	data    : {kode: $("#NOLAZADABATAL").text().split("#")[1], alasan:$('#cb_alasan_pembatalan_lazada').val()},
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
                                 reloadLazada();
                               }, "2000");
                    	}
                    });
                
                }
        	}
        });
}

function sinkronLazadaNow(){
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
                	url     : base_url+'Lazada/cekStokLokasi/',
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
                            totalPesananLazadaAll = 0;
                            sinkronLazadaState = true;
                            var dateNow = "<?=date('Y-m-d')?>";
                             $.ajax({
                            	type    : 'GET',
                            	url     : base_url+'Lazada/init/'+dateNow+'/'+dateNow+'/update',
                            	dataType: 'json',
                            	success : function(msg){
                            	    totalPesananLazadaAll = msg.total;
                            	    
                            	    var indexTab = 0;
                                    var tabs = document.querySelectorAll('#tab_transaksi_lazada li');

                                    tabs.forEach(function(tab, index) {
                                        if (tab.classList.contains('active')) {
                                            indexTab = (index+1);
                                        }
                                    });
                                    
                                    for(var x = 1; x <= 4 ; x++)
                                    {
                                        if(x != indexTab)
                                        {
                                            doneSinkronLazada[x] = false;
                                            changeTabLazada(x);
                                        }
                                    }
                                    
                                    doneSinkronLazada[indexTab] = false;
                                    changeTabLazada(indexTab);

                            }});
                        }
                	}
                });
        	}
        });
}

function sinkronLazada(){
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
                	url     : base_url+'Lazada/cekStokLokasi/',
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
                            totalPesananLazadaAll = 0;
                            sinkronLazadaState = true;
                            const date = new Date();
                            const year = date.getFullYear();
                            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                            const day = String(date.getDate()).padStart(2, '0');
                            
                            const formattedDate = `${year}-${month}-${day}`;
                            
                             $.ajax({
                            	type    : 'GET',
                            	url     : base_url+'Lazada/init/'+ "<?=TGLAWALFILTERMARKETPLACE?>"+"/"+formattedDate,
                            	dataType: 'json',
                            	success : function(msg){
                            	    totalPesananLazadaAll = msg.total;
                            	    
                                    var indexTab = 0;
                                    var tabs = document.querySelectorAll('#tab_transaksi_lazada li');

                                    tabs.forEach(function(tab, index) {
                                        if (tab.classList.contains('active')) {
                                            indexTab = (index+1);
                                        }
                                    });
                                    
                                    for(var x = 1; x <= 4 ; x++)
                                    {
                                        if(x != indexTab)
                                        {
                                            doneSinkronLazada[x] = false;
                                            changeTabLazada(x);
                                        }
                                    }
                                    
                                    doneSinkronLazada[indexTab] = false;
                                    changeTabLazada(indexTab);

                            }});
                        }
                	}
                });
        	}
        });
}

function catatanPenjualLazada(){
    var row;
    if($("#fromNoteLazada").val().split("_")[0] == "GRID")
    {
        row = JSON.parse($("#rowDataLazada").val());
    }
    else
    {
        var rows = JSON.parse($("#rowDataPengirimanLazada").val());
        row = rows[$("#fromNoteLazada").val().split("_")[1]];
    }

    
    $("#NOLAZADACATATAN").html("#"+row.KODEPESANAN);
    $("#note_lazada").val(row.CATATANJUALRAW);
    $("#modal-note-lazada").modal("show");
}

function noteKonfirmLazada(){
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
                 	url     : base_url+'Lazada/catatanPenjual/',
                 	data    : {kode: $("#NOLAZADACATATAN").text().split("#")[1], note: $("#note_lazada").val()},
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
                                $("#modal-note-lazada").modal('hide');
                                if($("#fromNoteLazada").val().split("_")[0] == "KIRIMLAZADA")
                                {
                                    var indexKirim = $("#fromNoteLazada").val().split("_")[1];
                                    
                                    var rows = JSON.parse($("#rowDataPengirimanLazada").val());
                                    rows[indexKirim]['CATATANJUAL'] = `<i class='fa fa-edit' id='editNoteLazada' style='cursor:pointer;'></i>
                                          <div style='width:250px; white-space: pre-wrap;      /* CSS3 */   
                                                        white-space: -moz-pre-wrap; /* Firefox */    
                                                        white-space: -pre-wrap;     /* Opera <7 */   
                                                        white-space: -o-pre-wrap;   /* Opera 7 */    
                                                        word-wrap: break-word;      /* IE */'>`+$("#note_lazada").val()+`</div>`;
                                    rows[indexKirim]['CATATANJUALRAW'] = $("#note_lazada").val();
                                    
                                    
                                    if($("#fromNoteLazada").val().split("_").length == 3)
                                    {
                                        var index = $("#fromNoteLazada").val().split("_")[2];
                                        
                                        $(".table-responsive-lazada-all-kirim_"+index+" #editNoteLazadaDiv_"+indexKirim).html(rows[indexKirim]['CATATANJUAL']);
                                        
                                        $("#rowDataPengirimanLazada").val(JSON.stringify(rows));
                                        $(".table-responsive-lazada-all-kirim_"+index+" #editNoteLazadaDiv_"+indexKirim).find('#editNoteLazada').click(function(){
                                           $("#fromNoteLazada").val("KIRIMLAZADA_"+indexKirim+"_"+index);
                                           catatanPenjualLazada();
                                        });
                                    }
                                    else
                                    {
                                        $('#editNoteLazadaDiv'+indexKirim).html(rows[indexKirim]['CATATANJUAL']);
                                        
                                        $("#rowDataPengirimanLazada").val(JSON.stringify(rows));
                                        $('#editNoteLazadaDiv'+indexKirim).find('#editNoteLazada').click(function(){
                                           $("#fromNoteLazada").val("KIRIMLAZADA_"+indexKirim);
                                           catatanPenjualLazada();
                                        });
                                    }
                                }
                             	
                             	setTimeout(() => {
                                  reloadLazada();
                                }, "2000");
                            }
                 	}
                 });
        	}
        });
}

function returBarangLazada(){
    $("#modal-pengembalian-lazada").modal("hide");
    var row = JSON.parse($("#rowDataLazada").val());
    
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
                	url     : base_url+'Lazada/setReturBarang/',
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
                                reloadLazada();
                            }, "2000");
                        }
                	}
                });
        	}
        });
    }
}

function returLazada(){
    $("#modal-pengembalian-lazada").modal("hide");
    var row = JSON.parse($("#rowDataLazada").val());
    var rowDetail = JSON.parse($("#dataReturLazada").val());
    loading();
    
    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Lazada/cekStokLokasi/',
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
                $("#tab_retur_header_lazada_0").attr("class","active");
                $("#tab_retur_detail_lazada_0").attr("class","tab-pane active");
                
                $("#tab_retur_header_lazada_1").attr("class","");
                $("#tab_retur_detail_lazada_1").attr("class","tab-pane");
                
                $("#tab_retur_header_lazada_2").attr("class","");
                $("#tab_retur_detail_lazada_2").attr("class","tab-pane");
                
                $("#deskripsi_sengketa_lazada").val("");
                $("#returLazadaWaitResponse").hide();
                $("#returNegotiationLazada").css("width","100%");
                $("#btn_max_kembali_lazada").show();
                // $("#DANADIKEMBALIKANLAZADA_1").removeAttr("readonly");
                $("#returNegotiationLazada").show();
                $("#HEADERRETURLAZADA").show();
                $("#DETAILRETURLAZADA_1").html('Dengan ini menyatakan bahwa : <br>Penjual ingin melakukan <b>Pengembalian Barang dan Dana</b> kepada pembeli, dengan catatan :<ol><li>Item harus dikirim oleh pelanggan dan diverifikasi kualitasnya sebelum menyetujui pengembalian dana.</li><li>Dengan memilih "Pengembalian Barang dan Dana", Pembeli meminta pelanggan untuk mengirimkan kembali barang yang sudah diterima. Setelah pembeli menerima barang yang dikembalikan pelanggan, harap konfirmasikan pengiriman barang yang dikembalikan dan selesaikan pemeriksaan kualitas dalam batas waktu (SLA).</li><li>Penjual dapat memilih untuk mengembalikan dana sepenuhnya atau sebagian berdasarkan kesepakatan penjual dengan pembeli atau menolak pengembalian dan mengajukan banding. Agen CS Lazada akan menghubungi penjual, jika membutuhkan bantuan untuk memproses banding.</li></ol>');
                $("#NOLAZADARETUR").html("#"+row.KODEPENGEMBALIAN);
                $("#HEADERRETURLAZADA").html('Pembeli akan mengirimkan barang paling lambat pada <span style="font-weight:bold;">'+row.MINTGLPENGEMBALIAN+'</span>. Anda dapat mengajukan banding setelah menerima barang dari Pembeli atau menawarkan pengembalian Dana sebagian kepada Pembeli.<br><br>');
                $("#modal-retur-lazada").modal("show");
                
                for(var x = 0 ; x < 3 ; x++)
                {
                    $("#DANADIKEMBALIKANLAZADA_"+x).number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                    $("#MAXDANADIKEMBALIKANLAZADA_"+x).number(true,"<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT']?>");
                    
                    $("#DANADIKEMBALIKANLAZADA_"+x).val(rowDetail.TOTALREFUND);
                    $("#MAXDANADIKEMBALIKANLAZADA_"+x).val(rowDetail.TOTALREFUND);
                }
                
                
                if(rowDetail.REFUNDTYPE == 'ONLY_REFUND' || row.TIPEPENGEMBALIAN == 'RETURN_DELIVERED' )
                {
                    $("#tab_retur_header_lazada_1").hide();
                    $("#tab_retur_detail_lazada_1").hide();
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
                    // 	url     : base_url+'Lazada/getDispute/',
                    // 	data    : {kodepengembalian: row.KODEPENGEMBALIAN},
                    // 	dataType: 'json',
                    // 	success : function(msg){
                    // 	    var dataDispute = msg;
                    // 	    for(var x = 0 ; x < dataDispute.length;x++)
                    // 	    {
                    // 	        select +=  '<option value="'+dataDispute[x].reason_id+'">'+dataDispute[x].muti_language_text+'</option>';
                    // 	    }
                    // 	    $("#cb_alasan_sengketa_lazada").html(select);
                    // 	}
                    // });
                }
                
                $("#penjelasan_bukti_lazada").html("Kamu dapat menambahkan 8 Foto, ukuran file tidak bisa lebih dari 10MB.");
                
                var htmlProof = "<table><tr>";
                for(var y = 0 ; y < 8 ;y++)
                {
                    if(y % 4 == 0)
                    {
                        htmlProof += "</tr><tr>";
                    }
                    
                    htmlProof += `<td>
                                        <input type="file" id="file-input-lazada-`+y+`" accept="image/*,video/*" style="display:none;" value="">
                                        <input type="hidden"  id="keterangan-input-lazada-`+y+`" value="">
                                        <input type="hidden"  id="format-input-lazada-`+y+`" value="">
                                        <input type="hidden"  id="index-input-lazada-`+y+`" value="`+y+`">
                                        <input type="hidden"  id="src-input-lazada-`+y+`" value="">
                                        <input type="hidden"  id="id-input-lazada-`+y+`" value="">
                                        <div style="margin-bottom:20px; margin-right:10px;">
                                            <img id="preview-image-lazada-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; height:100px; cursor:pointer; border:2px solid #dddddd;'>
                                            <br>
                                            <div style="text-align:center;">
                                                <span id="ubahProofLazada-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                                &nbsp;
                                                <span id="hapusProofLazada-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                            </div>
                                        </div>
                                    </td>`;  
                
                }
                htmlProof += "</table>";
                $("#proof_sengketa_lazada").html(htmlProof);
            
                for(var y = 0 ; y < 8 ; y++)
                {
                        const fileInput = document.getElementById('file-input-lazada-'+y);
                        const previewImage = document.getElementById('preview-image-lazada-'+y);
                        const title = document.getElementById('keterangan-input-lazada-'+y);
                        const format = document.getElementById('format-input-lazada-'+y);
                        const index = document.getElementById('index-input-lazada-'+y);
                        const url =  document.getElementById('src-input-lazada-'+y);
                        const id =  document.getElementById('id-input-lazada-'+y);
                        
                        const ubahImage = document.getElementById('ubahProofLazada-'+y);
                        const hapusImage = document.getElementById('hapusProofLazada-'+y);
                        
                        previewImage.addEventListener('click', () => {
                          if(url.value != '')
                          {
                              lihatLebihJelasLazada(format.value,title.value,url.value);
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
                        
                            var row = JSON.parse($("#rowDataLazada").val());
                            // Upload file asli ke server
                            const formData = new FormData();
                            formData.append('index', index.value);
                            formData.append('kode', row.KODEPENGEMBALIAN);
                            formData.append('file', file);
                            formData.append('tipe', 'GAMBAR');
                            formData.append('size', file.size);
                            formData.append("reason","proof/LAZADA");
                        
                            loading();
                            
                            $.ajax({
                              type: 'POST',
                              url: base_url + 'Lazada/uploadLocalUrlProof/',
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
                              
                               var row = JSON.parse($("#rowDataLazada").val());
                                // Upload file asli ke server
                                const formData = new FormData();
                                formData.append('index', index.value);
                                formData.append('kode', row.KODEPENGEMBALIAN);
                                formData.append('file', file);
                                formData.append('tipe', 'VIDEO');
                                formData.append('size', file.size);
                                formData.append("reason","proof/LAZADA");
                            
                                loading();
                                
                                $.ajax({
                                  type: 'POST',
                                  url: base_url + 'Lazada/uploadLocalUrlProof/',
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

function setMaksRefundLazada(){
  $("#DANADIKEMBALIKANLAZADA_1").val($("#MAXDANADIKEMBALIKANLAZADA_1").val());
}

function refundLazada(x){
        
   var gambarada = false;
   for(var y = 0 ; y < 8;y++)
   {
       //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
       if($("#src-input-lazada-"+y).val() != "")
       {
          gambarada = true;
       }
   
   }
        
    if(x == 2 && ($("#deskripsi_sengketa_lazada").val() == "" || !gambarada || $("#cb_alasan_sengketa_lazada").val() == "-"))
    {
        if($("#cb_alasan_sengketa_lazada").val() == "-")
        {
             Swal.fire({
                 	title            : 'Alasan Banding harus dipilih',
                 	type             : 'warning',
                 	showConfirmButton: false,
                 	timer            : 2000
             });
        }
        else if($("#deskripsi_sengketa_lazada").val() == "")
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
                setRefundLazada(x);
        	}
        });
    }
}

function setRefundLazada(x)
{
    var row = JSON.parse($("#rowDataLazada").val());
    var rowDetail = JSON.parse($("#dataReturLazada").val());
    loading();
    if(x == 0)
    {
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Lazada/refund/',
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
                    $("#modal-retur-lazada").modal("hide");
                
                    setTimeout(() => {
                      reloadLazada();
                    }, "2000");
                }
        	}
        });
    }
    else if(x == 1)
    {
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Lazada/returnRefund/',
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
                    $("#modal-retur-lazada").modal("hide");
                
                    setTimeout(() => {
                      reloadLazada();
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
            if($("#src-input-lazada-"+y).val() != "")
            {
                dataDisputeProof.push({
                    "id" : (y+1),
                    "requirement" : $("#keterangan-input-lazada-"+y).val(),
                    "thumbnail" : $("#src-input-lazada-"+y).val(),
                    "url" : $("#src-input-lazada-"+y).val(),
                    "url-baru" : ""
                });
            }
        
        }
        
       $.ajax({
            type    : 'POST',
            url     : base_url+'Lazada/changeLocalUrl/',
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
                    	url     : base_url+'Lazada/dispute/',
                    	data    : {kodepengembalian: row.KODEPENGEMBALIAN,kodepesanan: row.KODEPESANAN,pilihandispute:$("#cb_alasan_sengketa_lazada").val(),alasandispute:$("#deskripsi_sengketa_lazada").val(),disputeproof:JSON.stringify(dataDisputeProof)},
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
                                $("#modal-retur-lazada").modal("hide");
                            
                                setTimeout(() => {
                                  reloadLazada();
                                }, "2000");
                            }
                    	}
                    });
                }
                else
                {
                    Swal.close();	
                    setRefundLazada(2);
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

function reloadLazada(){
    for(var x = 1 ; x <= 4 ; x++ )
    {
        $("#dataGridLazada"+x).DataTable().ajax.reload();
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

function focusOnRefundLazada(){
    setTimeout(() => {
    //  $("#DANADIKEMBALIKANLAZADA_1").focus();
    }, "500");
}

//LIMIT ANGKA SAJA
function numberInputTrans(evt,index) {
	
	if(parseInt($("#DANADIKEMBALIKANLAZADA_"+index).val()) < 0){
	    $("#DANADIKEMBALIKANLAZADA_"+index).val(0);
		Swal.fire({
				title            : "Dana yang dikembalikan tidak boleh kurang dari Nol",
				type             : 'error',
				showConfirmButton: false,
				timer            : 1500
		});
	}
	else if(parseInt($("#DANADIKEMBALIKANLAZADA_"+index).val()) > parseInt($("#MAXDANADIKEMBALIKANLAZADA_"+index).val())){
	    $("#DANADIKEMBALIKANLAZADA_"+index).val($("#MAXDANADIKEMBALIKANLAZADA_"+index).val())
		Swal.fire({
				title            : "Dana yang dikembalikan tidak boleh lebih dari "+currency($("#MAXDANADIKEMBALIKANLAZADA_"+index).val()),
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

