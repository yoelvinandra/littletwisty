
<?php

$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	
?>
    <style>
        #DASHBOARDTERBANYAK > .nav-tabs > li.active {
        border-top: 3px solid #00a65a; /* Change this to the color you want */
    }
    </style>
    <section class="content">
      <div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12" >
          <div class="box box-primary">
            <div class="box-header with-border" style="height:870px; margin:0px 0px 0px 30px" >
			    <div class="row col-md-12">
			         <h3 class="HEADERPERIODE" style="font-weight:bold;">Grafik Penjualan</h3>
    			</div>
    			<div class="row col-md-12" style="padding-top:5px;">
        			<div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
        			   <div class="col-md-2 col-sm-2 col-xs-2"  style="padding-top:7px;">
        			       G.Lokasi 
        			   </div>
        			   <div class="col-md-10 col-sm-10 col-xs-10">
        			       <select class="form-control"id="txt_group_lokasi" name="txt_group_lokasi" style="width:100%;">
    					   </select>
        			   </div>
        			</div>
        		</div>
				<div class="row col-md-12" style="padding-top:5px;">
			        <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
			            <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:7px;">
			                Customer 
			            </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
			                <select class="form-control" id="CUSTOMER" name="CUSTOMER" placeholder="Customer..." style="width:100%;"  onchange="getCustomer(this.value);">
                  	            <option value="0">Semua Customer</option>
                        		<?=comboGridDashboard("model_master_customer")?>
                    	    </select>
			            </div>
    			    </div>
				</div>	
				<div class="row col-md-12" style="padding-top:5px;">
			        <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
			            <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:7px;">
			                Periode 
			            </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
			                 <select class="form-control" id="PERIODE" name="PERIODE" placeholder="Periode..." style="width:100%;"  onchange="getPeriode(this.value);">
                        		<option value="1">Hari ini</option>
                        		<option value="2">Kemarin</option>
                        		<option value="3" selected>Minggu ini</option>
                        		<option value="4">Bulan ini</option>
                        		<option value="5">Tahun ini</option>
                        		<option value="99">Custom</option>
                        	</select>
			            </div>
    			    </div>
				</div>
				
				<div class="row col-md-12" style="padding-top:12px;" id="periodepenjualantanggal" >
    			    <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
			            <div class="col-md-2 col-sm-2 col-xs-2">
			                Tanggal
			             </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
        			         <input type="text" class="form-control" id="TGLAWALPENJUALAN"  name="TGLAWALPENJUALAN" style="width:100px; border:1px solid #B5B4B4; border-radius:1px; float:left; margin-top:-7px;" placeholder="..."> 
    			              <span><span style="float:left; margin:0px 10px 0px 10px;">s/d</span><input type="text" class="form-control" id="TGLAKHIRPENJUALAN"  name="TGLAKHIRPENJUALAN" style="width:100px; border:1px solid #B5B4B4; border-radius:1px; margin-top:-7px;  float:left;" placeholder="..."> </span>
                		  </div>
                	  </div>
				</div>
    			<div class="row col-md-12" style="padding-top:5px;" id="periodepenjualanbulan" >
    			    <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
			            <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:7px;">
			                Bulan
			             </div>
			            <div class="col-md-10 col-sm-10 col-xs-10" style=" height:45px;">
        			         <select class="form-control select2" multiple="multiple" id="bln_penjualan" name="bln_penjualan[]" placeholder="Lokasi..." style="width:100%; float:right; height:120px;" onchange="ubahBulanPenjualan();">
                    		     <option value="JAN" selected>Jan</option>
                    		     <option value="FEB" selected>Feb</option>
                    		     <option value="MAR" selected>Mar</option>
                    		     <option value="APR" selected>Apr</option>
                    		     <option value="MEI" selected>Mei</option>
                    		     <option value="JUN" selected>Jun</option>
                    		     <option value="JUL" selected>Jul</option>
                    		     <option value="AGU" selected>Agu</option>
                    		     <option value="SEP" selected>Sep</option>
                    		     <option value="OKT" selected>Okt</option>
                    		     <option value="NOV" selected>Nov</option>
                			     <option value="DES" selected>Des</option>
                		    </select>
                		  </div>
                	  </div>
				</div>
    			
    			<div class="row col-md-12" style="text-align:center;">
    			    <div class="nav-tabs-custom"  style="margin-top:15px; box-shadow:none !important;">
                        <ul class="nav nav-tabs">
                            <li class="active" id="grandtotal_nota"><a href="#tab_grandtotal_nota" data-toggle="tab">Grand Total</a></li>
                			<li id="total_nota"><a href="#tab_jumlah_nota" data-toggle="tab">Total Nota</a></li>
                        </ul>
                        
                        <div class="tab-content" style="height:270px;">
                            <div class="tab-pane active" id="tab_grandtotal_nota" style="height:200px;">
                                 <div style="text-align:right;">Total Nominal Nota&nbsp;&nbsp;&nbsp;<b id="totalnominal" style="font-size:14pt;">Rp<?=$dashboardNow->GRANDTOTAL??0?></b></div>
                                 <br>
    			                 <div class="loading1" style="text-align:center; height:200px;" ><br><br><br>Tunggu Sebentar...</div>
                                <canvas id="myChartGrandTotal" ></canvas> 
                            </div> 
                            <div class="tab-pane" id="tab_jumlah_nota"  style="height:200px;">
                                 <div style="text-align:right;">Total Jumlah Nota&nbsp;<b id="totalnota" style="font-size:14pt;"><?=$dashboardNow->JMLNOTA?></b>&nbsp;Transaksi</div>
                                 <br>
    			                 <div class="loading1" style="text-align:center; height:200px;"><br><br><br>Tunggu Sebentar...</div>
                                <canvas id="myChartNota" ></canvas> 
                            </div>
                        </div>
                        
                        <hr></hr>
                        <div  style="height:200px;">
                             <div style="text-align:right;" >Total Barang&nbsp;&nbsp;&nbsp;<b id="totalbarang" style="font-size:14pt;"><?=$dashboardNow->TOTALBARANG??0?></b>&nbsp;Psg</div>
                             <br>
    			             <div class="loading1" style="text-align:center; height:200px;"><br><br><br>Tunggu Sebentar...</div>
                            <canvas id="myChartTotalBarang" ></canvas> 
                        </div> 
                    </div>
                    <br>
    			</div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        
        <div class="col-md-6 col-sm-12 col-xs-12" >
          <div class="box box-success">
            <div class="box-header with-border" style="height:870px; margin:0px 0px 0px 30px" >
			    <div class="row col-md-12" style="padding-top:5px;">
			        <h3 class="HEADERPERIODE" style="font-weight:bold;">Penjualan Terbanyak</h3>
    			</div>
    			<div class="row col-md-12" style="padding-top:5px;">
        			<div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
        			   <div class="col-md-2 col-sm-2 col-xs-2"  style="padding-top:7px;">
        			       G.Lokasi 
        			   </div>
        			   <div class="col-md-10 col-sm-10 col-xs-10">
        			       <select class="form-control"id="txt_group_lokasi_terlaris" name="txt_group_lokasi_terlaris" style="width:100%;">
    					   </select>
        			   </div>
        			</div>
        		</div>
    			<div class="row col-md-12"  id="periodeterlaristanggal" style="padding-top:5px;">
			        <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
			            <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:7px;">
			                Customer 
			            </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
			                <select class="form-control" id="CUSTOMER_TERLARIS" name="CUSTOMER_TERLARIS" placeholder="Customer..." style="width:100%;"  onchange="getCustomerTerlaris(this.value);">
                  	            <option value="0">Semua Customer</option>
                        		<?=comboGridDashboard("model_master_customer")?>
                        	</select>
			            </div>
    			    </div>
				</div>
    			<div class="row col-md-12"  id="periodeterlaristanggal" style="padding-top:5px;">
			        <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
			            <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:7px;">
			                Periode 
			            </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
			                <select class="form-control" id="PERIODE_TERLARIS" name="PERIODE_TERLARIS" placeholder="Periode..." style="width:100%;"  onchange="getPeriodeTerlaris(this.value);">
                        		<option value="1">Hari ini</option>
                        		<option value="2">Kemarin</option>
                        		<option value="3" selected>Minggu ini</option>
                        		<option value="4">Bulan ini</option>
                        		<option value="5">Tahun ini</option>
                        		<option value="99">Custom</option>
                        	</select>
			            </div>
    			    </div>
				</div>
    			<div class="row col-md-12"  id="periodeterlaristanggal" style="padding-top:12px;">
			        <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
			            <div class="col-md-2 col-sm-2 col-xs-2">
			                Tanggal 
			            </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
			                <input type="text" class="form-control" id="TGLAWALTERLARIS"  name="TGLAWALTERLARIS" style="width:100px; border:1px solid #B5B4B4; border-radius:1px; float:left; margin-top:-7px;" placeholder="..."> 
			                <span><span style="float:left; margin:0px 10px 0px 10px;">s/d</span><input type="text" class="form-control" id="TGLAKHIRTERLARIS"  name="TGLAKHIRTERLARIS" style="width:100px; border:1px solid #B5B4B4; border-radius:1px; margin-top:-7px;  float:left;" placeholder="..."> </span>
			            </div>
    			    </div>
				</div>
    			<div class="row col-md-12"  style="padding: 0px 0px 0px 8px;">
    			    <div class="nav-tabs-custom" id="DASHBOARDTERBANYAK" style="margin-top:15px; box-shadow:none !important;">
                        <ul class="nav nav-tabs">
                            <li id="produk_terlaris" class="active"><a href="#tab_produk_terlaris" data-toggle="tab">Produk Terlaris</a></li>
                			<li id="warna_terlaris" ><a href="#tab_warna_terlaris"  data-toggle="tab">Warna Terlaris</a></li>
                			<li id="ukuran_terlaris" ><a href="#tab_ukuran_terlaris" data-toggle="tab">Ukuran Terlaris</a></li>
                
                        </ul>
                        <div class="tab-content" >
                            <div class="tab-pane active" id="tab_produk_terlaris">
                                <br>
    			                <div class="loading2" style="text-align:center; height:10px;"><br><br><br><br><br><br><br><br>Tunggu Sebentar...</div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="doughnutJumlah" style="margin:10px 0px 10px 10%; width:300px; height:200px;"></canvas>
                                    </div>
                                    <div class="col-md-12">      
                                         <div class="legendDoughnut" style="margin-top:20px; overflow-y:scroll; height:265px;" ></div>
                                    </div>
                                </div>
                            </div> 
                            <div class="tab-pane" id="tab_warna_terlaris">
    			                <div class="loading2" style="text-align:center; height:10px;">
                                <br><br><br><br><br><br><br><br><br>Tunggu Sebentar...</div>
    			                <div class="row">
    			                    <div class="row col-md-12" >
                            			<div class=" col-md-12 col-sm-6 col-xs-6" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
                            			   <div class="col-md-2 col-sm-2 col-xs-2"  style="padding-top:7px;">
                            			       Model
                            			   </div>
                            			   <div class="col-md-10 col-sm-10 col-xs-10">
                            			       <select class="form-control"id="modelWarna" name="MODELWARNA" style="width:100%;">
                        					   </select>
                            			   </div>
                            			</div>
                            		</div>
                                    <div class="col-md-12">
                            		    <br>
                                        <canvas id="doughnutWarna" style="margin:20px 0px 10px 10%; width:300px; height:200px;"></canvas>
                                    </div>
                                    <div class="col-md-12">      
                                         <div class="legendDoughnut" style="margin-top:20px; overflow-y:scroll; height:245px;" ></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_ukuran_terlaris" >
    			                <div class="loading2" style="text-align:center; height:10px;">
                                <br><br><br><br><br><br><br><br><br>Tunggu Sebentar...</div>
                                <div class="row" >
                                    <div class="row col-md-12" >
                            			<div class=" col-md-12 col-sm-6 col-xs-6" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
                            			   <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:7px;" >
                            			       Model
                            			   </div>
                            			   <div class="col-md-10 col-sm-10 col-xs-10">
                            			       <select class="form-control"id="modelSize" name="MODELSIZE" style="width:100%;">
                        					   </select>
                            			   </div>
                            			</div>
                            		</div>
                                    <div class="col-md-12">
                                        <canvas id="doughnutSize" style="margin:20px 0px 10px 10%; width:300px; height:200px;"></canvas>
                                    </div>
                                    <div class="col-md-12">      
                                         <div class="legendDoughnut" style="margin-top:20px; overflow-y:scroll; height:245px;" ></div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                    <br>
    			</div>
             </div>
            <!-- /.box-body -->
            </div>
          <!-- /.box -->
            </div>
        </div>
        
      <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12" >
          <div class="box box-warning">
            <div class="box-header with-border" style="height:700px; margin:0px 0px 0px 30px">
			    <div class="row col-md-12">
			         <h3 class="HEADERPERIODE" style="font-weight:bold;">Data Penjualan Terbanyak</h3>
    			</div>
    			<div class="row col-md-12" style="padding-top:5px;">
    			    <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
        			     <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:7px;">
    			            Barang 
    			        </div>
    			        <div class="col-md-10 col-sm-10 col-xs-10">
    			            <select class="form-control select2" id="BARANGCUSTOMER" name="BARANGCUSTOMER" placeholder="Barang..." style="width:100%;"  onchange="getBarangCustomer(this.value);">
                  	            <option value="0">Semua Produk</option>
                        		<?=comboGrid("model_master_barang")?>
                        	</select>
    			        </div>
    			      </div>
    			</div>
    			<div class="row col-md-12" style="padding-top:5px;">
    			    <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
    			        <div class="col-md-2 col-sm-2 col-xs-2"  style="padding-top:7px;">
    			            Periode 
    			        </div>
    			        <div class="col-md-10 col-sm-10 col-xs-10">
    			            <select class="form-control" id="PERIODE_CUSTOMER" name="PERIODE_CUSTOMER" placeholder="Periode..." style="width:100%;"  onchange="getPeriodeCustomer(this.value);">
                        		<option value="1">Hari ini</option>
                        		<option value="2">Kemarin</option>
                        		<option value="3" selected>Minggu ini</option>
                        		<option value="4">Bulan ini</option>
                        		<option value="5">Tahun ini</option>
                        		<option value="99">Custom</option>
                        	</select>
    			        </div>
			        </div>
    			</div>
    			<div class="row col-md-12" style="padding-top:12px;">
    			    <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
    			        <div class="col-md-2 col-sm-2 col-xs-2">
    			            Tanggal 
    			        </div>
    			        <div class="col-md-10 col-sm-10 col-xs-10">
    			            <input type="text" class="form-control" id="TGLAWALCUSTOMER"  name="TGLAWALCUSTOMER" style="width:100px; border:1px solid #B5B4B4; border-radius:1px; float:left; margin-top:-7px;" placeholder="..."> 
    			            <span><span style="float:left; margin:0px 10px 0px 10px;">s/d</span><input type="text" class="form-control" id="TGLAKHIRCUSTOMER"  name="TGLAKHIRCUSTOMER" style="width:100px; border:1px solid #B5B4B4; border-radius:1px; margin-top:-7px;  float:left;" placeholder="..."> </span>
    			        </div>
			        </div>
    			</div>
    			<div class="row col-md-12" style="text-align:center;">
        			<div class="nav-tabs-custom"  style="margin-top:15px; box-shadow:none !important;">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_dashboard_customer_terbanyak" data-toggle="tab">Customer</a></li>
                    		<li><a href="#tab_dashboard_kota_terbanyak" data-toggle="tab">Kota</a></li>
                        </ul>
                        
                        <div class="tab-content" style="height:270px;">
                            <div class="tab-pane active" id="tab_dashboard_customer_terbanyak" style="height:200px;">
                    			<div class="row col-md-12">
                    			      <div class="loading3" style="text-align:center;"><br><br>Tunggu Sebentar...</div>
                    			      <div class="tableCustomer" style="overflow-y:scroll; height:390px; padding-right:15px;" >
                                             
                                      </div>
                                    <br>
                    			</div>
                            </div> 
                            <div class="tab-pane" id="tab_dashboard_kota_terbanyak"  style="height:200px;">
                                <div class="row col-md-12">
                                    <select class="form-control" id="CUSTOMER_KOTA" name="CUSTOMER_KOTA" placeholder="Customer..." style="width:100%;"  onchange="getCustomerKota(this.value);">
                          	            <option value="0">Semua Customer</option>
                                		<?=comboGridDashboard("model_master_customer")?>
                                	</select>
                            	</div>
                            	<br>
                            	<br>
                                <div class="row col-md-12">
                    			      <div class="loading3" style="text-align:center;"><br><br>Tunggu Sebentar...</div>
                    			      <div class="tableKota" style="overflow-y:scroll; height:390px; padding-right:15px;" >
                                             
                                      </div>
                                    <br>
                    			</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12" >
          <div class="box box-danger">
            <div class="box-header with-border" style="height:700px; margin:0px 0px 0px 30px">
			    <div class="row col-md-12">
			        <div class="col-md-10 col-sm-10 col-xs-10" style="padding: 0px 0px 5px 0px">
			            <h3 class="HEADERPERIODE" style="font-weight:bold;">Kebutuhan Stok Barang</h3>
    			    </div>
    			    <div class="col-md-2 col-sm-2 col-xs-2" style="padding: 15px 0px 5px 0px">
              	        <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt; float:right; margin-left:10px;"  onclick="exportTableToExcelStok()">Excel</button>
              	      
                    </div>
    			</div>
    			<div class="row col-md-12" style="padding-top:5px;">
    			    <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt;" >
    			        <div class="col-md-2 col-sm-2 col-xs-2"  style="padding-top:7px;">
    			            Lokasi 
    			        </div>
    			        <div class="col-md-10 col-sm-10 col-xs-10">
    			            <select class="form-control" id="LOKASI_STOK" name="LOKASI_STOK" placeholder="Lokasi..." style="width:100%; float:right;"  onchange="getLokasiStok(this.value);">
                        		<?=comboGrid("model_master_lokasi")?>
                        	</select>
    			        </div>
    			     </div>
    			</div>
    			<div class="row col-md-12" style="padding-top:12px;">
			        <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt">
			            <div class="col-md-2 col-sm-2 col-xs-2">
			                Tanggal 
			            </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
			                <input type="text" class="form-control" id="TGLSTOK"  name="TGLSTOK" style="width:100px; border:1px solid #B5B4B4; border-radius:1px; float:left; margin-top:-7px;" placeholder="...">
			            </div>
    			    </div>
    			</div>
    			
    			<div class="row col-md-12" style="padding-top:5px;" >
    			    <div class="row col-md-12 col-sm-12 col-xs-12" style="padding: 0px 0px 0px 0px; font-size:12pt">
			            <div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:9px;">
			                Stok&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;< 
			            </div>
			            <div class="col-md-10 col-sm-10 col-xs-10">
			                   <span style="margin-top:-7px;"><input style="width:100px; float:left;" type="number" class="form-control" id="LIMITSTOK" name="LIMITSTOK" placeholder="Limit" min="-1000" max="1000" 
                			        onkeydown="return (event.ctrlKey || event.altKey 
                                        || (44 < event.keyCode && event.keyCode < 58 && event.shiftKey == false) 
                                        || (95 < event.keyCode && event.keyCode < 106)
                                        || (event.keyCode == 8) || (event.keyCode == 9) 
                                        || (event.keyCode > 34 && event.keyCode < 40) 
                                        || (event.keyCode == 46)
                                        || (event.key === '-' || event.key === 'Minus') // Allow minus key
                                    );">&nbsp;&nbsp;&nbsp;<button type="button" class="btn  btn-primary" id="btn_print" style="font-size:10pt; float:left; margin-left:10px; "  onclick="changeStok()">Tampilkan</button></span>
			            </div>
    			    </div>
    			</div>
    			<div class="row col-md-12">
    			     <hr style="margin:20px 0px 20px 0px" ></hr>
    			      <div class="loading4" style="text-align:center;"><br><br>Tunggu Sebentar...</div>
    			      <div class="tableStok" id="tableStoked" style="overflow-y:scroll; height:390px; padding-right:15px;" >
                             
                      </div>
                    <br>
    			</div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col (RIGHT) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
<!-- ./wrapper -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<!-- Inside your box-body section -->
<script>
  
$(document).ready(function(){

    $.ajax({
    	type    : 'POST',
    	async   : false,
    	url     : base_url+'Master/Data/Lokasi/cekGroupLokasi/',
    	data    : {group: JSON.stringify(arrGroupLokasi)},
    	dataType: 'json',
    	success : function(msg){
    	       
    		for(var x = 0 ; x < msg.rows.length ; x++)
    		{
    		    $("#txt_group_lokasi").append('<option value="'+msg.rows[x].VALUE+'">'+msg.rows[x].TEXT+'</option>');
    		    $("#txt_group_lokasi_terlaris").append('<option value="'+msg.rows[x].VALUE+'">'+msg.rows[x].TEXT+'</option>');
    		}
    		
    	}
    });
    
    $("#periodepenjualanbulan").hide();
    
    $("#txt_group_lokasi").change(function(){
       setPeriode(periode,tglawal,tglakhir,customer);
    });
     
    $("#txt_group_lokasi_terlaris").change(function(){
       setPeriodeTerlaris(periodeTerlaris,tglawalTerlaris,tglakhirTerlaris,customerTerlaris);  
    });
    
});
    
	$('#TGLAWALPENJUALAN, #TGLAKHIRPENJUALAN, #TGLAWALTERLARIS, #TGLAKHIRTERLARIS, #TGLAWALCUSTOMER, #TGLAKHIRCUSTOMER, #TGLSTOK').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true, // Close the datepicker automatically after selection
        container: 'body', // Attach the datepicker to the body element
        orientation: 'bottom auto' // Show the calendar below the input
	});
	
	$("#TGLAWALPENJUALAN").change(function() {
	    $("#TGLAKHIRPENJUALAN").datepicker('setDate', null);
	    tglawal = $(this).val();
    });
    
    $("#TGLAKHIRPENJUALAN").change(function() {
       if($(this).val() != "")tglakhir = $(this).val();
       if(periode == "99" && $(this).val() != "")setPeriode(periode,tglawal,tglakhir,customer);
    });
    
    $("#TGLAWALTERLARIS").change(function() {
	    $("#TGLAKHIRTERLARIS").datepicker('setDate', null);
	    tglawalTerlaris = $(this).val();
    });
    
    $("#TGLAKHIRTERLARIS").change(function() {
        if($(this).val() != "")tglakhirTerlaris = $(this).val();
       if(periodeTerlaris == "99" && $(this).val() != "")setPeriodeTerlaris(periodeTerlaris,tglawalTerlaris,tglakhirTerlaris,customerTerlaris);
    });
    
    $("#TGLAWALCUSTOMER").change(function() {
	    $("#TGLAKHIRCUSTOMER").datepicker('setDate', null);
	    tglawalCustomer = $(this).val();
    });
    
    $("#TGLAKHIRCUSTOMER").change(function() {
        if($(this).val() != "")tglakhirCustomer = $(this).val();
       if(periodeCustomer == "99" && $(this).val() != "")setPeriodeCustomer(periodeCustomer,tglawalCustomer,tglakhirCustomer,barangCustomer,customerKota);
    });
	
	$('#TGLAWALPENJUALAN, #TGLAKHIRPENJUALAN, #TGLAWALTERLARIS, #TGLAKHIRTERLARIS, #TGLAWALCUSTOMER, #TGLAKHIRCUSTOMER, #TGLSTOK').attr('disabled','disabled');
	
    let today = new Date().toISOString().slice(0, 10);
    var tglawal =  today;
    var tglakhir =  today;
    var periode = "3";
    var customer = "0";
    var myChartGrandTotal;
    var myChartNota;
    var myChartTotalBarang;
    
    getPeriode(periode);
    
    var tglawalTerlaris =  today;
    var tglakhirTerlaris =  today;
    var periodeTerlaris = "3";
    var customerTerlaris = "0";
    var doughnutChartJumlah;
    var doughnutChartWarna;
    var doughnutChartSize;
    var activeChartDoughtnut = 1;
    
    $.ajax({
    	type    : 'POST',
    	async   : false,
    	url     : base_url+'Master/Data/Barang/comboGridKategori/',
    	data    : {},
    	dataType: 'json',
    	success : function(msg){
    	       
    		for(var x = 0 ; x < msg.rows.length ; x++)
    		{
    		    var namaBarang = msg.rows[x].NAMABARANG.split(" | ")[0];
    		    var selected = "";
    		    if(x == 0)
    		    {
    		        selected = selected;
    		    }
    		    $("#modelWarna").append('<option value="'+namaBarang+'">'+namaBarang+'</option>');
    		    $("#modelSize").append('<option value="'+namaBarang+'">'+namaBarang+'</option>');
    		}
    		
            getPeriodeTerlaris(periodeTerlaris);
    
    	}
    });
    
    $("#modelWarna").change(function(){
        setPeriodeTerlaris(periodeTerlaris,tglawalTerlaris,tglakhirTerlaris,customerTerlaris);
    });
     
    $("#modelSize").change(function(){
         setPeriodeTerlaris(periodeTerlaris,tglawalTerlaris,tglakhirTerlaris,customerTerlaris);
    });
    
    var barangCustomer = "0"; 
    var customerKota = "0";
    var tglawalCustomer =  today;
    var tglakhirCustomer =  today;
    var periodeCustomer = "3";
    getPeriodeCustomer(periodeCustomer);
    
    var lokasiStok = $("#LOKASI_STOK").val();
    var limitStok = 10;
    $("#LIMITSTOK").val(limitStok);
    getStok(lokasiStok,limitStok);
    
     $('.select2').select2({
		 dropdownAutoWidth: true, 
	 });
	 
	$("#LIMITSTOK").bind('keyup mouseup', function () {
       limitStok = $(this).val();
    });
	 
	function exportTableToExcelStok() {
      var wb = XLSX.utils.table_to_book(document.getElementById('tableStoked'), {sheet:"Sheet 1"});
      const ws = wb.Sheets[wb.SheetNames[0]];

      // Set column widths - specify column widths for each column in the 'cols' array
      ws['!cols'] = [
        { wpx: 25 }, // Column A width in pixels
        { wpx: 400 }, // Column A width in pixels
        { wpx: 50 },  // Column C width in pixels
      ];
      // Trigger download
      XLSX.writeFile(wb, 'STOK_KURANG_'+$('#LOKASI_STOK option:selected').text()+'_'+dateNowFormatExcel()+'.xlsx');
    }
    
    function ubahBulanPenjualan(){
        var bulan = $('#bln_penjualan').val();

        $('#bln_penjualan').attr('disabled','disabled');
        var resultTotal = [];
        var resultJml = [];
        var resultBarang = [];
        var jmlNota = 0;
        var grandtotal = 0;
        var totalBarang = 0;
        for(var x = 0 ; x < bulan.length;x++)
        {
            for(var y = 0 ; y < label.length;y++)
            {
                if(bulan[x] == label[y])
                {
                    resultTotal.push(total[y]);
                    resultJml.push(jnota[y]);
                    resultBarang.push(tbarang[y]);
                    grandtotal += parseInt(total[y]);
                    jmlNota += parseInt(jnota[y]);
                    totalBarang += parseInt(tbarang[y]);
                }
            }
        }
        
        let jmlnotaFormat = new Intl.NumberFormat('en-US', { 
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
            style: 'currency', 
            currency: 'IDR' 
        }).format(jmlNota).replace("IDR","");
        
        let grandtotalFormat = new Intl.NumberFormat('en-US', { 
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
            style: 'currency', 
            currency: 'IDR' 
        }).format(grandtotal).replace("IDR","Rp");
        
        let totalbarangFormat = new Intl.NumberFormat('en-US', { 
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
            style: 'currency', 
            currency: 'IDR' 
        }).format(totalBarang).replace("IDR","");
        
        $("#totalnota").html(jmlnotaFormat);
        $("#totalnominal").html(grandtotalFormat);
        $("#totalbarang").html(totalbarangFormat);
        
        $(".loading1").show();
        
        var lineGrandTotal = document.getElementById('myChartGrandTotal').getContext('2d');
        if (window.myChartGrandTotal instanceof Chart) {
             myChartGrandTotal.destroy();
        }
        
        var lineNota = document.getElementById('myChartNota').getContext('2d');
        if (window.myChartNota instanceof Chart) {
             myChartNota.destroy();
        }
        
        var lineTotalBarang = document.getElementById('myChartTotalBarang').getContext('2d');
        if (window.myChartTotalBarang instanceof Chart) {
             myChartTotalBarang.destroy();
        }
        setTimeout(function() {
            $(".loading1").hide();
            $('#bln_penjualan').removeAttr('disabled');
            myChartGrandTotal = new Chart(lineGrandTotal, {
                type: 'line',
                 options: {
                  responsive: true,
                  maintainAspectRatio: false,  // Allows you to manually control width and height
                },
                data: {
                    labels: bulan,
                    datasets: [
                        {
                            label: 'Nominal Nota',
                            data: resultTotal,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: false
                    }]
                },
            });
            
            myChartNota = new Chart(lineNota, {
                type: 'line',
                 options: {
                  responsive: true,
                  maintainAspectRatio: false,  // Allows you to manually control width and height
                },
                data: {
                    labels: bulan,
                    datasets: [
                    {
                        label: 'Jumlah Nota',
                        data: resultJml,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                            fill: false
                        }]
                },
            });
            
            myChartTotalBarang = new Chart(lineTotalBarang, {
                type: 'line',
                options: {
                  responsive: true,
                  maintainAspectRatio: false,  // Allows you to manually control width and height
                },
                data: {
                    labels: bulan,
                    datasets: [
                    {
                        label: 'Total Barang',
                        data: resultBarang,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                            fill: false
                        }]
                },
            });
            
        }, 500);
    }
    
    function getLastDateOfMonth(year, month) {
        // Month is 0-based in JavaScript Date object, so we need to subtract 1 from the provided month.
        var date = new Date(year, month, 0);  // Pass 0 as the day to get the last day of the previous month
        return date.getDate();  // This will return the last date of the month
    }
    
    $("#grandtotal_nota").click(function(){
        
        if(periode == "5")
        {
            ubahBulanPenjualan();   
        }
        else
        {
            $(".loading1").show();
            var lineGrandTotal = document.getElementById('myChartGrandTotal').getContext('2d');
            if (window.myChartGrandTotal instanceof Chart) {
                 myChartGrandTotal.destroy();
            }
            var lineTotalBarang = document.getElementById('myChartTotalBarang').getContext('2d');
            if (window.myChartTotalBarang instanceof Chart) {
                 myChartTotalBarang.destroy();
            }
            
            setTimeout(function() {
                $(".loading1").hide();
                myChartGrandTotal = new Chart(lineGrandTotal, {
                    type: 'line',
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,  // Allows you to manually control width and height
                    },
                    data: {
                        labels: label,
                        datasets: [
                            {
                                label: 'Nominal Nota',
                                data: total,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                fill: false
                        }]
                    },
                });
                
                myChartTotalBarang = new Chart(lineTotalBarang, {
                    type: 'line',
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,  // Allows you to manually control width and height
                    },
                    data: {
                        labels: label,
                        datasets: [
                            {
                                label: 'Total Barang',
                                data: tbarang,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                fill: false
                        }]
                    },
                });
                
            }, 500);
        }
        
    });
    
    $("#total_nota").click(function(){
        
        if(periode == "5")
        {
            ubahBulanPenjualan();   
        }
        else
        {
            $(".loading1").show();
            var lineNota = document.getElementById('myChartNota').getContext('2d');
            if (window.myChartNota instanceof Chart) {
                 myChartNota.destroy();
            }
            var lineTotalBarang = document.getElementById('myChartTotalBarang').getContext('2d');
            if (window.myChartTotalBarang instanceof Chart) {
                 myChartTotalBarang.destroy();
            }
            
            setTimeout(function() {
                $(".loading1").hide();
                myChartNota = new Chart(lineNota, {
                    type: 'line',
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,  // Allows you to manually control width and height
                    },
                    data: {
                        labels: label,
                        datasets: [
                        {
                            label: 'Jumlah Nota',
                            data: jnota,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                                fill: false
                            }]
                    },
                });
                
                myChartTotalBarang = new Chart(lineTotalBarang, {
                    type: 'line',
                    options: {
                      responsive: true,
                      maintainAspectRatio: false,  // Allows you to manually control width and height
                    },
                    data: {
                        labels: label,
                        datasets: [
                            {
                                label: 'Total Barang',
                                data: tbarang,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                fill: false
                        }]
                    },
                });
                    
            }, 500);
        }
    });
    
    $("#produk_terlaris").click(function(){
        $(".legendDoughnut").html("");
        activeChartDoughtnut = 1;
        $(".loading2").html("<br><br><br><br><br><br><br><br>Tunggu Sebentar...");
        $(".loading2").show();
        var donnutJumlah = document.getElementById('doughnutJumlah').getContext('2d');
        if (window.doughnutChartJumlah instanceof Chart) {
             doughnutChartJumlah.destroy();
        }
        
        setTimeout(function() {
            $(".loading2").hide();
            doughnutChartJumlah = new Chart(donnutJumlah, {
                    type: 'doughnut',  // Type of chart (doughnut)
                    data: {
                        labels: label1,  // Labels for each segment
                        datasets: [{
                            label: 'Produk Terlaris',
                            data: qty1,  // Data for each segment
                            backgroundColor: warna,  // Segment colors
                            borderColor: ['#ffffff', '#ffffff', '#ffffff'],  // Border color for each segment
                            borderWidth: 2
                        }]
                    },
                    options: {
                    responsive: false,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display : false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw + ' Pasang';
                                    }
                                }
                            }
                        }
                    }
                });
             setLegend(label1,qty1);
        }, 500);
    });
    
    $("#warna_terlaris").click(function(){
        $(".legendDoughnut").html("");
        activeChartDoughtnut = 2;
        $(".loading2").html("<br><br><br><br><br><br><br><br>Tunggu Sebentar...");
        $(".loading2").show();
        var donnutWarna = document.getElementById('doughnutWarna').getContext('2d');
        if (window.doughnutChartWarna instanceof Chart) {
             doughnutChartWarna.destroy();
        }
        
        setTimeout(function() {
            $(".loading2").hide();
            doughnutChartWarna = new Chart(donnutWarna, {
                type: 'doughnut',  // Type of chart (doughnut)
                data: {
                    labels: label2,  // Labels for each segment
                    datasets: [{
                        label: 'Warna Terlaris',
                        data: qty2,  // Data for each segment
                        backgroundColor: warna,  // Segment colors
                        borderColor: ['#ffffff', '#ffffff', '#ffffff'],  // Border color for each segment
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display : false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.raw+ ' Pasang';
                        }
                            }
                        }
                    }
                }
            });
            setLegend(label2,qty2);
        }, 500);
    });
    
    $("#ukuran_terlaris").click(function(){
        $(".legendDoughnut").html("");
        activeChartDoughtnut = 3;
        $(".loading2").html("<br><br><br><br><br><br><br><br>Tunggu Sebentar...");
        $(".loading2").show();
        var donnutSize = document.getElementById('doughnutSize').getContext('2d');
        if (window.doughnutChartSize instanceof Chart) {
             doughnutChartSize.destroy();
        }
        
        setTimeout(function() {
            $(".loading2").hide();
            doughnutChartSize = new Chart(donnutSize, {
                 type: 'doughnut',  // Type of chart (doughnut)
                 data: {
                     labels: label3,  // Labels for each segment
                     datasets: [{
                         label: 'Ukuran Terlaris',
                         data: qty3,  // Data for each segment
                         backgroundColor: warna,  // Segment colors
                         borderColor: ['#ffffff', '#ffffff', '#ffffff'],  // Border color for each segment
                         borderWidth: 2
                     }]
                 },
                 options: {
                    responsive: false,
                    maintainAspectRatio: true,
                    plugins: {
                         legend: {
                                display : false,
                         },
                         tooltip: {
                             callbacks: {
                                 label: function(tooltipItem) {
                                     return tooltipItem.raw+ ' Pasang';
                                 }
                             }
                         }
                     }
                }
            });
            setLegend(label3,qty3);
        }, 500);
        
    });
    
    function setLegend(label,value){
        var table = "";
        if(label.length > 0)
        {
            table = "<table width='100%' style=' border-collapse:separate; border-spacing: 0 10px;'>";
            for(var x = 0 ; x < label.length;x++)
            {
                let jmlFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(value[x]).replace("IDR","");
                table += "<tr valign='top'><td width='50px'><div style='width:30px; height:20px; margin-bottom:5px; background:"+warna[x]+"'>&nbsp;</div></td><td style='font-size:10pt;'>"+label[x]+"</td><td width='70px' style='font-size:10pt;'>&nbsp;&nbsp;"+jmlFormat+" Psg</td></tr>";
            }
            table += "</table>";
        }
        else
        {
            table = "<br><br><br><br><br><br><br><br>Tidak Ada Data";
            // $(".loading2").html("<br><br><br><br><br><br><br><br>Tidak Ada Data");
            // $(".loading2").show();
        }
        $(".legendDoughnut").html(table);
    }
    
    function getPeriode(periodeVal){
        periode = periodeVal;
        $("#periodepenjualantanggal").show();
        $("#periodepenjualanbulan").hide();
        if(periode != "99")
        {
            if(periode == "1")
    	    {
    	        tglawal = today;
    	        tglakhir = today;
    	    }
    	    else if(periode == "2")
    	    {
    	        //KEMARIN
                let currentDate = new Date();
                currentDate.setDate(currentDate.getDate() - 1);
                
                let year = currentDate.getFullYear();
                let month = String(currentDate.getMonth() + 1).padStart(2, '0');  // Months are 0-indexed
                let day = String(currentDate.getDate()).padStart(2, '0');
                
                tglawal = `${year}-${month}-${day}`;
    	        tglakhir = today;
    	    }
        	else if(periode == "3")
    	    {
    	        //MINGGU INI
    	        
                // Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Menghitung hari dalam minggu ini (0 = Minggu, 1 = Senin, ..., 6 = Sabtu)
                const currentDayOfWeek = currentDate.getDay();
                
                // Menghitung perbedaan untuk mencapai Senin (jika hari ini Minggu, maka dikurangi 6 hari)
                const diffToMonday = currentDayOfWeek === 0 ? -6 : 1 - currentDayOfWeek; 
                
                // Menghitung tanggal Senin (awal minggu)
                const firstDayOfWeek = new Date(currentDate);
                firstDayOfWeek.setDate(currentDate.getDate() + diffToMonday);
                
                // Menghitung tanggal Minggu (akhir minggu)
                const lastDayOfWeek = new Date(firstDayOfWeek);
                lastDayOfWeek.setDate(firstDayOfWeek.getDate() - 6);
                
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawal = firstDayOfWeek.toISOString().split('T')[0];
                tglakhir = today;
    	    }
        	else if(periode == "4")
    	    {
            	// Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Mendapatkan tahun dan bulan dari tanggal hari ini
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth(); // 0 = Januari, 1 = Februari, ..., 11 = Desember
    
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawal = year+"-"+(parseInt(month)+1)+"-01";
                tglakhir = today;
    	    }
        	else if(periode == "5")
    	    {
            	// Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Mendapatkan tahun dan bulan dari tanggal hari ini
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth(); // 0 = Januari, 1 = Februari, ..., 11 = Desember
    
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawal = year+"-"+"01-01";
                tglakhir = today;
                
                $('#bln_penjualan').val(['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGU', 'SEP', 'OKT', 'NOV', 'DES']).trigger('change');
                $("#periodepenjualantanggal").hide();
                $("#periodepenjualanbulan").show();
    	    }
    	    $("#TGLAWALPENJUALAN").datepicker('setDate',tglawal);
    	    $("#TGLAKHIRPENJUALAN").datepicker('setDate',tglakhir);
    	    $("#TGLAWALPENJUALAN,#TGLAKHIRPENJUALAN").attr('disabled','disabled');
    	   
    	   setPeriode(periode,tglawal,tglakhir,customer);
        }
        else
        {
    	    $("#TGLAWALPENJUALAN,#TGLAKHIRPENJUALAN").removeAttr('disabled');
        }
    }
    
    function getCustomer(customerVal)
    {
        customer = customerVal;
        setPeriode(periode,tglawal,tglakhir,customer);
    }
    
    var label = [];
    var jnota = [];
    var tbarang = [];
    var total = [];
    
    function setPeriode(periode,tglawal,tglakhir,customer){
        $("#txt_group_lokasi,#CUSTOMER,#PERIODE,#bln_penjualan").attr("disabled","disabled");
        var lineGrandTotal = document.getElementById('myChartGrandTotal').getContext('2d');
        if (window.myChartGrandTotal instanceof Chart) {
             myChartGrandTotal.destroy();
        }
        
        var lineNota = document.getElementById('myChartNota').getContext('2d');
        if (window.myChartNota instanceof Chart) {
             myChartNota.destroy();
        }
        
        var lineTotalBarang = document.getElementById('myChartTotalBarang').getContext('2d');
        if (window.myChartTotalBarang instanceof Chart) {
             myChartTotalBarang.destroy();
        }
        
        $(".loading1").show();
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Penjualan/Transaksi/Penjualan/dashboardGrandTotal',
        	data    : {periode:periode,tglawal:tglawal,tglakhir:tglakhir,customer:customer,lokasi:$("#txt_group_lokasi").val()},
        	dataType: 'json',
        	success : function(msg){
        	    $("#txt_group_lokasi,#CUSTOMER,#PERIODE,#bln_penjualan").removeAttr("disabled");
                $(".loading1").hide();
            	var data = msg.result;
            	label = [];
            	jnota = [];
            	tbarang = [];
            	total = [];
            	var jmlNota = 0;
            	var grandtotal = 0;
            	var totalBarang = 0;
            	for(var x = 0 ; x < data.length; x++)
            	{
            	    label.push(data[x].LABEL);
            	    total.push(data[x].GRANDTOTAL);
            	    jnota.push(data[x].JMLNOTA);
            	    tbarang.push(data[x].TOTALBARANG);
            	    jmlNota += parseInt(data[x].JMLNOTA);
            	    grandtotal += parseInt(data[x].GRANDTOTAL);
            	    totalBarang += parseInt(data[x].TOTALBARANG);
            	}
            	
            	let jmlnotaFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(jmlNota).replace("IDR","");
            	
            	let grandtotalFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(grandtotal).replace("IDR","Rp");
                
                let totalBarangFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(totalBarang).replace("IDR","");
                
                if(periode == 5)
                {
                    setTimeout(function() {
                     ubahBulanPenjualan(); 
                    }
                    ,500);
                }
                else
                {
                	$("#totalnota").html(jmlnotaFormat);
                	$("#totalnominal").html(grandtotalFormat);
                	$("#totalbarang").html(totalBarangFormat);
                	
                    myChartGrandTotal = new Chart(lineGrandTotal, {
                        type: 'line',
                        options: {
                          responsive: true,
                          maintainAspectRatio: false,  // Allows you to manually control width and height
                        },
                        data: {
                            labels: label,
                            datasets: [
                                {
                                    label: 'Nominal Nota',
                                    data: total,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 2,
                                    fill: false
                            }]
                        },
                    });
                    
                    myChartNota = new Chart(lineNota, {
                        type: 'line',
                        options: {
                          responsive: true,
                          maintainAspectRatio: false,  // Allows you to manually control width and height
                        },
                        data: {
                            labels: label,
                            datasets: [
                                {
                                    label: 'Jumlah Nota',
                                    data: jnota,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 2,
                                    fill: false
                                }]
                        },
                    });
                    
                    myChartTotalBarang = new Chart(lineTotalBarang, {
                        type: 'line',
                        options: {
                          responsive: true,
                          maintainAspectRatio: false,  // Allows you to manually control width and height
                        },
                        data: {
                            labels: label,
                            datasets: [
                                {
                                    label: 'Total Barang',
                                    data: tbarang,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 2,
                                    fill: false
                                }]
                        },
                    });
                }
        	}
        });
    }
    
    
    function getPeriodeTerlaris(periodeVal){
        periodeTerlaris = periodeVal;
        if(periodeTerlaris != "99")
        {
            if(periodeTerlaris == "1")
    	    {
    	        tglawalTerlaris = today;
    	        tglakhirTerlaris = today;
    	    }
    	    else if(periodeTerlaris == "2")
    	    {
    	        //KEMARIN
                let currentDate = new Date();
                currentDate.setDate(currentDate.getDate() - 1);
                
                let year = currentDate.getFullYear();
                let month = String(currentDate.getMonth() + 1).padStart(2, '0');  // Months are 0-indexed
                let day = String(currentDate.getDate()).padStart(2, '0');
                
                tglawalTerlaris = `${year}-${month}-${day}`;
    	        tglakhirTerlaris = today;
    	    }
        	else if(periodeTerlaris == "3")
    	    {
    	        //MINGGU INI
    	        
                // Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Menghitung hari dalam minggu ini (0 = Minggu, 1 = Senin, ..., 6 = Sabtu)
                const currentDayOfWeek = currentDate.getDay();
                
                // Menghitung perbedaan untuk mencapai Senin (jika hari ini Minggu, maka dikurangi 6 hari)
                const diffToMonday = currentDayOfWeek === 0 ? -6 : 1 - currentDayOfWeek; 
                
                // Menghitung tanggal Senin (awal minggu)
                const firstDayOfWeek = new Date(currentDate);
                firstDayOfWeek.setDate(currentDate.getDate() + diffToMonday);
                
                // Menghitung tanggal Minggu (akhir minggu)
                const lastDayOfWeek = new Date(firstDayOfWeek);
                lastDayOfWeek.setDate(firstDayOfWeek.getDate() - 6);
                
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawalTerlaris = firstDayOfWeek.toISOString().split('T')[0];
                tglakhirTerlaris = today;
    	    }
        	else if(periodeTerlaris == "4")
    	    {
            	// Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Mendapatkan tahun dan bulan dari tanggal hari ini
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth(); // 0 = Januari, 1 = Februari, ..., 11 = Desember
    
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawalTerlaris = year+"-"+(parseInt(month)+1)+"-01";
                tglakhirTerlaris = today;
    	    }
        	else if(periodeTerlaris == "5")
    	    {
            	// Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Mendapatkan tahun dan bulan dari tanggal hari ini
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth(); // 0 = Januari, 1 = Februari, ..., 11 = Desember
    
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawalTerlaris = year+"-"+"01-01";
                tglakhirTerlaris = today;
    	    }
    	    
    	     $("#TGLAWALTERLARIS").datepicker('setDate',tglawalTerlaris);
    	     $("#TGLAKHIRTERLARIS").datepicker('setDate',tglakhirTerlaris);
    	     $("#TGLAWALTERLARIS,#TGLAKHIRTERLARIS").attr('disabled','disabled');
    	    
    	   setPeriodeTerlaris(periodeTerlaris,tglawalTerlaris,tglakhirTerlaris,customerTerlaris);
        }
        else
        {
    	     $("#TGLAWALTERLARIS,#TGLAKHIRTERLARIS").removeAttr('disabled');
        }
    }
    
    function getCustomerTerlaris(customerVal)
    {
        customerTerlaris = customerVal;
        setPeriodeTerlaris(periodeTerlaris,tglawalTerlaris,tglakhirTerlaris,customerTerlaris);
    }
    
    var label1 = [];
    var label2 = [];
    var label3 = [];
    var qty1 = [];
    var qty2 = [];
    var qty3 = [];
    var warna = ["#4169E1","#DC143C","#50C878","#DAA520","#40E0D0",
                 "#32CD32","#0047AB","#FF00FF","#6A5ACD","#008080",
                 "#FF4500","#7FFF00","#FF1493","#1E90FF","#FF6347",
                 "#BA55D3","#8A2BE2","#4B0082","#FF8C00","#7FFFD4",
                 "#FFC0CB","#9ACD32","#CCCCFF","#E6E6FA","#FF7F50",
                 "#87CEEB","#F0E68C","#808000","#8E4585","#A0522D"
                ];
    
    function setPeriodeTerlaris(periode,tglawal,tglakhir,customer){ 
        $("#txt_group_lokasi_terlaris,#CUSTOMER_TERLARIS,#PERIODE_TERLARIS,#modelWarna,#modelSize").attr("disabled","disabled");
        
        $(".legendDoughnut").html("");
        
        var donnutJumlah = document.getElementById('doughnutJumlah').getContext('2d');
        if (window.doughnutChartJumlah instanceof Chart) {
             doughnutChartJumlah.destroy();
        }
        
        var donnutWarna = document.getElementById('doughnutWarna').getContext('2d');
        if (window.doughnutChartWarna instanceof Chart) {
             doughnutChartWarna.destroy();
        }
        
        var donnutSize = document.getElementById('doughnutSize').getContext('2d');
        if (window.doughnutChartSize instanceof Chart) {
             doughnutChartSize.destroy();
        }
        
        $(".loading2").html("<br><br><br><br><br><br><br><br>Tunggu Sebentar...");
        $(".loading2").show();
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Penjualan/Transaksi/Penjualan/dashboardItem',
        	data    : {periode:periode,tglawal:tglawal,tglakhir:tglakhir,customer:customer,lokasi:$("#txt_group_lokasi_terlaris").val(),kategoriWarna:$("#modelWarna").val(),kategoriSize:$("#modelSize").val()},
        	dataType: 'json',
        	success : function(msg){
				$("#txt_group_lokasi_terlaris,#CUSTOMER_TERLARIS,#PERIODE_TERLARIS,#modelWarna,#modelSize").removeAttr("disabled");
                $(".loading2").hide();
            	var data = msg.result;
            	label1 = [];
            	label2 = [];
            	label3 = [];
            	qty1 = [];
            	qty2 = [];
            	qty3 = [];
            	
            	for(var x = 0 ; x < data.qty.length; x++)
            	{
            	    if(parseInt(data.qty[x].QTY) != 0)
            	    {
                	    label1.push(data.qty[x].NAMA);
                	    qty1.push(parseInt(data.qty[x].QTY));
            	    }
            	}
            	for(var x = 0 ; x < data.warna.length; x++)
            	{
            	    if(parseInt(data.warna[x].QTY) != 0)
            	    {
                	    if(data.warna[x].WARNA == "")
                	    {
                	        label2.push("TIDAK ADA WARNA");
                	    }
                	    else
                	    { 
                	        label2.push("WARNA "+data.warna[x].WARNA);
                	    }
                	    qty2.push(parseInt(data.warna[x].QTY));
            	    }
            	}
            	for(var x = 0 ; x < data.ukuran.length; x++)
            	{
            	    if(parseInt(data.ukuran[x].QTY) != 0)
            	    {
                	    if(data.ukuran[x].SIZE == "" || data.ukuran[x].SIZE == "0" || data.ukuran[x].SIZE == 0)
                	    {
                	        label3.push("TIDAK ADA UKURAN");
                	    }
                	    else
                	    { 
                	        label3.push("SIZE "+data.ukuran[x].SIZE);
                	    }
                	    qty3.push(parseInt(data.ukuran[x].QTY));
            	    }
            	}
            	
                doughnutChartJumlah = new Chart(donnutJumlah, {
                    type: 'doughnut',  // Type of chart (doughnut)
                    data: {
                        labels: label1,  // Labels for each segment
                        datasets: [{
                            label: 'Produk Terlaris',
                            data: qty1,  // Data for each segment
                            backgroundColor: warna,  // Segment colors
                            borderColor: ['#ffffff', '#ffffff', '#ffffff'],  // Border color for each segment
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display : false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw + ' Pasang';
                                    }
                                }
                            }
                        }
                    }
                });
                
                doughnutChartWarna = new Chart(donnutWarna, {
                    type: 'doughnut',  // Type of chart (doughnut)
                    data: {
                        labels: label2,  // Labels for each segment
                        datasets: [{
                            label: 'Warna Terlaris',
                            data: qty2,  // Data for each segment
                            backgroundColor: warna,  // Segment colors
                            borderColor: ['#ffffff', '#ffffff', '#ffffff'],  // Border color for each segment
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display : false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw+ ' Pasang';
                                    }
                                }
                            }
                        }
                    }
                });
                
                doughnutChartSize = new Chart(donnutSize, {
                    type: 'doughnut',  // Type of chart (doughnut)
                    data: {
                        labels: label3,  // Labels for each segment
                        datasets: [{
                            label: 'Ukuran Terlaris',
                            data: qty3,  // Data for each segment
                            backgroundColor: warna,  // Segment colors
                            borderColor: ['#ffffff', '#ffffff', '#ffffff'],  // Border color for each segment
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display : false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.raw+ ' Pasang';
                                    }
                                }
                            }
                        }
                    }
                });
                
                if(activeChartDoughtnut == 1)
                {
                    setLegend(label1,qty1);
                }
                else if(activeChartDoughtnut == 2)
                {
                    setLegend(label2,qty2);
                }
                else if(activeChartDoughtnut == 3)
                {
                    setLegend(label3,qty3);
                }
               
        	}
        });
    }
    
    
    function getPeriodeCustomer(periodeVal){
        periodeCustomer = periodeVal;
        
        if(periodeCustomer != "99")
        {
            if(periodeCustomer == "1")
    	    {
    	        tglawalCustomer = today;
    	        tglakhirCustomer = today;
    	    }
    	    else if(periodeCustomer == "2")
    	    {
    	        //KEMARIN
                let currentDate = new Date();
                currentDate.setDate(currentDate.getDate() - 1);
                
                let year = currentDate.getFullYear();
                let month = String(currentDate.getMonth() + 1).padStart(2, '0');  // Months are 0-indexed
                let day = String(currentDate.getDate()).padStart(2, '0');
                
                tglawalCustomer = `${year}-${month}-${day}`;
    	        tglakhirCustomer = today;
    	    }
        	else if(periodeCustomer == "3")
    	    {
    	        //MINGGU INI
    	        
                // Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Menghitung hari dalam minggu ini (0 = Minggu, 1 = Senin, ..., 6 = Sabtu)
                const currentDayOfWeek = currentDate.getDay();
                
                // Menghitung perbedaan untuk mencapai Senin (jika hari ini Minggu, maka dikurangi 6 hari)
                const diffToMonday = currentDayOfWeek === 0 ? -6 : 1 - currentDayOfWeek; 
                
                // Menghitung tanggal Senin (awal minggu)
                const firstDayOfWeek = new Date(currentDate);
                firstDayOfWeek.setDate(currentDate.getDate() + diffToMonday);
                
                // Menghitung tanggal Minggu (akhir minggu)
                const lastDayOfWeek = new Date(firstDayOfWeek);
                lastDayOfWeek.setDate(firstDayOfWeek.getDate() - 6);
                
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawalCustomer = firstDayOfWeek.toISOString().split('T')[0];
                tglakhirCustomer = today;
    	    }
        	else if(periodeCustomer == "4")
    	    {
            	// Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Mendapatkan tahun dan bulan dari tanggal hari ini
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth(); // 0 = Januari, 1 = Februari, ..., 11 = Desember
    
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawalCustomer = year+"-"+(parseInt(month)+1)+"-01";
                tglakhirCustomer = today;
    	    }
        	else if(periodeCustomer == "5")
    	    {
            	// Mendapatkan tanggal hari ini
                const currentDate = new Date();
                
                // Mendapatkan tahun dan bulan dari tanggal hari ini
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth(); // 0 = Januari, 1 = Februari, ..., 11 = Desember
    
                // Format tanggal ke 'YYYY-MM-DD' (seperti PHP date format)
                tglawalCustomer = year+"-"+"01-01";
                tglakhirCustomer = today;
    	    }
    	    
    	     $("#TGLAWALCUSTOMER").datepicker('setDate',tglawalCustomer);
    	     $("#TGLAKHIRCUSTOMER").datepicker('setDate',tglakhirCustomer);
    	     $("#TGLAWALCUSTOMER,#TGLAKHIRCUSTOMER").attr('disabled','disabled');
    	    
    	   setPeriodeCustomer(periodeCustomer,tglawalCustomer,tglakhirCustomer,barangCustomer,customerKota);
        }
        else
        {
    	     $("#TGLAWALCUSTOMER,#TGLAKHIRCUSTOMER").removeAttr('disabled');
        }
    }
    
    function getBarangCustomer(barangVal)
    {
        barangCustomer = barangVal;
        setPeriodeCustomer(periodeCustomer,tglawalCustomer,tglakhirCustomer,barangCustomer,customerKota);
    }
    
    var labelCustomer = [];
    var qtyCustomer = [];
    var grandtotalCustomer = [];
    
    function setPeriodeCustomer(periode,tglawal,tglakhir,barang,customer){ 
        $("#BARANGCUSTOMER,#PERIODE_CUSTOMER").attr("disabled","disabled");
        $(".tableCustomer").html("");
        $(".tableKota").html("");
        $(".loading3").html("<br><br><br><br><br><br><br><br>Tunggu Sebentar...");
        $(".loading3").show();
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Penjualan/Transaksi/Penjualan/dashboardCustomer',
        	data    : {periode:periode,tglawal:tglawal,tglakhir:tglakhir,barang:barang,customer:customer},
        	dataType: 'json',
        	success : function(msg){
                $("#BARANGCUSTOMER,#PERIODE_CUSTOMER").removeAttr("disabled");
                $(".loading3").hide();
            	var dataCustomer = msg.resultCustomer;
            	labelCustomer = [];
            	qtyCustomer = [];
            	grandtotalCustomer = [];
            	
            	for(var x = 0 ; x < dataCustomer.length; x++)
            	{
            	    labelCustomer.push(dataCustomer[x].NAMA);
            	    qtyCustomer.push(parseInt(dataCustomer[x].QTY));
            	    grandtotalCustomer.push(parseInt(dataCustomer[x].GRANDTOTAL));
            	}
                setTableCustomer(labelCustomer,qtyCustomer,grandtotalCustomer);
                
                var dataKota = msg.resultKota;
            	labelKota = [];
            	qtyKota = [];
            	grandtotalKota = [];
            	
            	for(var x = 0 ; x < dataKota.length; x++)
            	{
            	    labelKota.push(dataKota[x].NAMA);
            	    qtyKota.push(parseInt(dataKota[x].QTY));
            	    grandtotalKota.push(parseInt(dataKota[x].GRANDTOTAL));
            	}
                setTableKota(labelKota,qtyKota,grandtotalKota);
        	}
        });
    }
    
    function setTableCustomer(label,value,valueGrand){
               
        $(".loading3").hide();
        var table = "";
        var total = 0;
        var grand = 0;
        if(label.length > 0)
        {
            table = "<table width='100%'  style=' border-collapse:separate; border-spacing: 0 10px;'>";
            table += "<tr valign='top' ><th style='font-size:12pt;'>No </th><th width='55%' style='font-size:12pt; text-align:left;'>Detail</th><th width='10%' style='text-align:center; font-size:12pt;'>Jumlah</th><th style='text-align:right; font-size:12pt;'>Grand Total</th></tr>";
            for(var x = 0 ; x < label.length;x++)
            {
                let jmlFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(value[x]).replace("IDR","");
                
                let grandFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(valueGrand[x]).replace("IDR","");
                
                table += "<tr valign='top'><td style='font-size:10pt;  text-align:left;'>"+(x+1)+".</td><td style='font-size:10pt; text-align:left;'>"+label[x]+"</td><td style='text-align:center;font-size:10pt;'>"+jmlFormat+" Psg</td><td style='text-align:right;font-size:10pt;'>Rp"+grandFormat+"</td></tr>";
                total += parseInt(value[x]);
                grand += parseInt(valueGrand[x]);
            }
            
            let totalFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
            }).format(total).replace("IDR","");
            
            let grandtotalFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
            }).format(grand).replace("IDR","");
                
            table += "<tr ><th style='border-top:1px solid black;'></th><th style='border-top:1px solid black;'></th><th style='text-align:center; border-top:1px solid black;'>"+totalFormat+" Psg</th><th style='text-align:right; border-top:1px solid black;'>Rp"+grandtotalFormat+"</th></tr></table>";
        }
        else
        {
            table = "<br><br><br><br><br><br><br><br>Tidak Ada Data";
            // $(".loading3").html("<br><br><br><br><br><br><br><br>Tidak Ada Data");
            // $(".loading3").show();
        }
        $(".tableCustomer").html(table);
    }
    
    function getCustomerKota(customerVal)
    {
        customerKota = customerVal;
        setPeriodeCustomer(periodeCustomer,tglawalCustomer,tglakhirCustomer,barangCustomer,customerKota);
    }
    
    function setTableKota(label,value,valueGrand){
               
        $(".loading3").hide();
        var table = "";
        var total = 0;
        var grand = 0;
        if(label.length > 0)
        {
            table = "<table width='100%'  style=' border-collapse:separate; border-spacing: 0 10px;'>";
            table += "<tr valign='top' ><th style='font-size:12pt;'>No </th><th width='55%' style='font-size:12pt; text-align:left;'>Detail</th><th width='10%' style='text-align:center; font-size:12pt;'>Jumlah</th><th style='text-align:right; font-size:12pt;'>Grand Total</th></tr>";
            for(var x = 0 ; x < label.length;x++)
            {
                let jmlFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(value[x]).replace("IDR","");
                
                let grandFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(valueGrand[x]).replace("IDR","");
                
                table += "<tr valign='top'><td style='font-size:10pt;  text-align:left;'>"+(x+1)+".</td><td style='font-size:10pt; text-align:left;'>"+label[x]+"</td><td style='text-align:center;font-size:10pt;'>"+jmlFormat+" Psg</td><td style='text-align:right;font-size:10pt;'>Rp"+grandFormat+"</td></tr>";
                total += parseInt(value[x]);
                grand += parseInt(valueGrand[x]);
            }
            
            let totalFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
            }).format(total).replace("IDR","");
            
            let grandtotalFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
            }).format(grand).replace("IDR","");
                
            table += "<tr ><th style='border-top:1px solid black;'></th><th style='border-top:1px solid black;'></th><th style='text-align:center; border-top:1px solid black;'>"+totalFormat+" Psg</th><th style='text-align:right; border-top:1px solid black;'>Rp"+grandtotalFormat+"</th></tr></table>";
        }
        else
        {
            table = "<br><br><br><br><br><br><br><br>Tidak Ada Data";
            // $(".loading3").html("<br><br><br><br><br><br><br><br>Tidak Ada Data");
            // $(".loading3").show();
        }
        $(".tableKota").html(table);
    }
    
    function changeStok()
    {
        getStok(lokasiStok,limitStok);
    }
    
    function getLokasiStok(lokasiVal)
    {
        lokasiStok = lokasiVal;
        getStok(lokasiStok,limitStok);
    }
    var labelStok = [];
    var qtyStok = [];
    function getStok(lokasi,limitStok){ 
        $("#LOKASI_STOK,#LIMITSTOK").attr("disabled","disabled");
        $(".tableStok").html("");
        $(".loading4").html("<br><br><br><br><br><br><br><br>Tunggu Sebentar...");
        $(".loading4").show();
        $.ajax({
        	type    : 'POST',
        	url     : base_url+'Penjualan/Transaksi/Penjualan/dashboardStok',
        	data    : {lokasi:lokasi,limitStok : limitStok },
        	dataType: 'json',
        	success : function(msg){
                $("#LOKASI_STOK,#LIMITSTOK").removeAttr("disabled");
                $(".loading4").hide();
            	var data = msg.result;
            	labelStok = [];
            	qtyStok = [];
            	
            	for(var x = 0 ; x < data.length; x++)
            	{
            	    labelStok.push(data[x].NAMA);
            	    qtyStok.push(parseInt(data[x].QTY));
            	}
            	
	            $("#TGLSTOK").datepicker('setDate',today);
            	
                setTableStok(labelStok,qtyStok);
        	}
        });
    }
    
    function setTableStok(label,value){
               
        $(".loading4").hide();
        var table = "";
        if(label.length > 0)
        {
            table = "<table width='100%' style=' border-collapse:separate; border-spacing: 0 10px;'>";
            table += "<tr valign='top' ><th style='font-size:12pt;'>No </th><th width='80%' style='font-size:12pt;'>Detail</th><th width='10%' style='font-size:12pt;'>Stok</th></tr>";
            for(var x = 0 ; x < label.length;x++)
            {
                 let jmlFormat = new Intl.NumberFormat('en-US', { 
            	    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                    style: 'currency', 
                    currency: 'IDR' 
                }).format(value[x]).replace("IDR","");
                
                table += "<tr valign='top'><td style='font-size:10pt;'>"+(x+1)+".</td><td style='font-size:10pt;'>"+label[x]+"</td><td style='text-align:right;font-size:10pt;'>"+jmlFormat+" Psg</td></tr>";
            }
            table += "</table>";
        }
        else
        {
            table = "<br><br><br><br><br><br><br><br>Tidak Ada Data";
            // $(".loading4").html("<br><br><br><br><br><br><br><br>Tidak Ada Data");
            // $(".loading4").show();
        }
        $(".tableStok").html(table);
    }
</script>
</body>
</html>

