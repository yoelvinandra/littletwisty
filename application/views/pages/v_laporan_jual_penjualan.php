
<div >
    <!-- Main row -->
    <div class="row" style="overflow-y:hidden;">
        <div class="col-md-12" >
            <div class="box">
                <div class="box-header">
                    <!-- <h2 class="page-header">AdminLTE Custom Tabs</h2> -->
                    <h3 class="box-title" style="font-size:20pt;">Laporan Penjualan</h3> <button class="btn pull-right" id="btn_print" style="font-size:20pt;"><i class="fa fa-print" ></i></button>
                    <!-- <button class="btn">Tambah</button> -->
                </div>
                <!-- Custom Tabs -->
                <div >
                    <ul class="nav nav-tabs" id="tab-filter"  >
                        <li class="active" id="tab_filter"><a href="#tab_form" data-toggle="tab">Filter</a></li>
                    </ul>
                    <div class="tab-content" id="tab-report">
                        <div class="tab-pane active" id="tab_form">
							
                            <div class="col-sm-12" >
                                <!-- form start -->
                                <form method='post' target="LaporanPenjualan" action='<?=base_url()?>Penjualan/Laporan/LaporanPenjualan/laporan<?='?kode='.$kodemenu?>' id="form_input">
                                    <div class="box-body">
                                        <div class="form-group col-sm-8" >
											<h4 class="box-title">
												<b>Filter Data</b>
											</h4>
											<div class="form-group">
                                                <label for="Lokasi">Group Lokasi</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-map-marker"></i>
                                                    </div>
                                                    <select class="form-control"id="txt_group_lokasi" name="txt_group_lokasi" style="width:97%;">
													</select>
														
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Lokasi">Lokasi</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-map-marker"></i>
                                                    </div>
                                                    <select class="form-control select2" multiple="multiple" id="txt_lokasi" name="txt_lokasi[]" placeholder="Lokasi..." style="width:97%;">
														<?=comboGrid("model_master_lokasi")?>
													</select>
														
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="TglTrans">Tgl Trans</label>

                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="tgltrans"  name="tglTrans" style="width:97%; border:1px solid #B5B4B4; border-radius:5px;">
                                                </div>
                                                <!-- /.input group -->
                                            </div>

                                            <div class="form-group">
                                                <label for="Status">Status</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-sticky-note"></i>
                                                    </div>
                                                    <select class="form-control select2" multiple="multiple" id="cbStatus" name="cbStatus[]" style="width:97%;">
                                                        <option value="I" selected>Input</option>
                                                        <option value="S" selected>Slip</option>
                                                        <option value="P" selected>Posting</option>
                                                        <option value="D">Delete</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="Status">Status Marketplace</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-sticky-note"></i>
                                                    </div>
                                                    <select class="form-control select2" multiple="multiple" id="cbStatusMarketplace" name="cbStatusMarketplace[]" style="width:97%;">
                                                        <option value="1" selected>Pesanan Disiapkan</option>
                                                        <option value="2" selected>Pesanan Dikirim</option>
                                                        <option value="3|COMPLETED" selected>Pesanan Selesai</option>
                                                        <option value="3|CANCELLED">Pesanan Batal</option>
                                                        <option value="4">Retur Pesanan</option>
                                                    </select>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <label for="Kolom">Kolom</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-filter"></i>
                                                    </div>
                                                    <select class="form-control select2 " id="kolom" name="kolom" placeholder="Kolom" style="width:97%;">
                                                        <option value="TPENJUALAN.KODEPENJUALAN">Kode Penjualan</option>
                                                        <option value="TPENJUALAN.KODEPENJUALAN">Kode Penjualan Marketplace</option>
														<option value="MCUSTOMER.KODECUSTOMER">Kode Customer</option>
                                                        <option value="MCUSTOMER.NAMACUSTOMER">Nama Customer</option>
														<option value="MBARANG.KODEBARANG">Kode Produk</option>
                                                        <option value="MBARANG.NAMABARANG">Nama Produk</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Operator">Operator</label>
                                                <div id="lap_operatorString" class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-gear"></i>
                                                    </div>
                                                    <select id="operatorString" class="form-control select2" name="operatorString" style="width:97%;">
                                                        <?=operator_laporan("String")?>
                                                    </select>
                                                </div>
                                                <div style="display:none;" id="lap_operatorNumber" class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-gear"></i>
                                                    </div>
                                                    <select id="operatorNumber" class="form-control select2" name="operatorNumber" style="width:97%;">
                                                        <?=operator_laporan("Number")?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Nilai">Nilai</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-window-maximize"></i>
                                                    </div>
                                                    <div id="hide_nilai" style="display:none;">
                                                        <input class="form-control " id="txt_nilai" name="txt_nilai"  prompt="Nilai" style="width:97%; border:1px solid #B5B4B4; border-radius:5px;">
                                                    </div>

                                                    <div id="hide_nilai_list_jual">
                                                        <select id="txt_nilai_list_jual" class="form-control select2" name="txt_nilai_list_jual"  prompt="Nilai" style="width:97%;"/>
															<?=comboGrid("model_jual_penjualan")?>
                                                        </select>
                                                    </div>
                                                      <div id="hide_nilai_list_ref" style="display:none;">
                                                        <select id="txt_nilai_list_ref" class="form-control select2" name="txt_nilai_list_ref"  prompt="Nilai" style="width:97%;"/>
															<?=comboGridTransReferensi("model_jual_penjualan")?>
                                                        </select>
                                                    </div>
													<div id="hide_nilai_list_customer" style="display:none;">
                                                        <select id="txt_nilai_list_customer" class="form-control select2 " name="txt_nilai_list_customer"  prompt="Nilai" style="width:97%;"/>
															<?=comboGrid("model_master_customer")?>
														</select>
                                                    </div>
                                                    <div id="hide_nilai_list_barang" style="display:none;">
                                                        <select id="txt_nilai_list_barang" class="form-control select2 " name="txt_nilai_list_barang"  prompt="Nilai" style="width:97%;"/>
															<?=comboGrid("model_master_barang")?>
														</select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Parameter Kolom </label>
												<span class="pull-right">
													<li class="fa fa-plus-square" id="btn_add" style="cursor:pointer; font-size:20pt; text-align:right;"></li>&nbsp;
                                                    <li class="fa fa-minus-square" id="btn_remove" style="cursor:pointer; font-size:20pt; text-align:right;"></li>&nbsp;&nbsp;&nbsp;&nbsp;
												</span>
                                                <select multiple class="form-control" name="list_filter_laporan" id="list_filter_laporan" style="width:97%" >
                                                </select>
                                            </div>

                                        </div>
                                        <div class="form-group col-sm-4">
											<h4 class="box-title">
												<b>Jenis Laporan</b>
											</h4>
											<div class="form-group">
												<label>
												  <input type="radio" id="excel" name="excel" value="tidak" class="flat-blue" checked> Cetak 
												</label>
												&nbsp;&nbsp;&nbsp;
												<label>
												  <input type="radio" name="excel" value="ya" class="flat-blue"> Ubah Konversi Jadi Excel
												</label>
											</div>
											<div class="info-box bg-yellow" onclick="simpan('REKAP','Laporan Rekap Penjualan')" style="cursor:pointer">
												<span class="info-box-icon"><i class="ion ion-ios-book-outline" style="margin-top:20%; font-size:40pt;"></i></span>

												<div class="info-box-content">
												  <span class="info-box-number">Laporan Rekap</span>
												</div>
												<!-- /.info-box-content -->
											</div>	
                                            <div class="info-box bg-yellow" onclick="simpan('REGISTER','Laporan Register Penjualan')" style="cursor:pointer;">
												<span class="info-box-icon"><i class="ion ion-ios-book-outline" style="margin-top:20%; font-size:40pt;"></i></span>

												<div class="info-box-content">
												   <span class="info-box-number">Laporan Register</span>
												</div>
												<!-- /.info-box-content -->
											</div>
											<div class="info-box bg-yellow" onclick="simpan('RINCIAN','Laporan Penjualan Harian')" style="cursor:pointer">
												<span class="info-box-icon"><i class="ion ion-ios-book-outline" style="margin-top:20%; font-size:40pt;"></i></span>

												<div class="info-box-content">
												  <span class="info-box-number">Laporan Harian</span>
												</div>
												<!-- /.info-box-content -->
											</div>	
                                        </div>
                                    </div>
									
									<!-- NAMA LAPORAN -->
									<input type="hidden" name="file_name" id="file_name" value="LaporanPenjualan">
									<input type="hidden" id="data_filter" name="data_filter">
									<input type="hidden" id="data_tampil" name="data_tampil">
									<input type="hidden" id="group_lokasi" name="group_lokasi">
                                    <!-- /.box-body -->
                                </form>
                                <!-- Button -->
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>

                </div>
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.row (main row) -->

</div>

<!-- /.content -->
<script>
    var kolom = "Kode Penjualan";
    var namaKolom = "Penjualan";
    var kolomVal = "TPENJUALAN.KODEPENJUALAN"
    var checkData = "Kode";
    var operator = "Adalah";
    var operatorVal = "ADALAH";
    var tipedata = "STRING"
	var counter = 0;
	
    $(document).ready(function() {
        
        $('#tgltrans').daterangepicker({
		   locale: {
				  format: 'YYYY-MM-DD'
			}
		});

         $('.select2').select2({
    		 dropdownAutoWidth: true, 
    	 });

        var format_uang = $.fn.dataTable.render.number(',', '.', 2, '');
        
        //footer_js.php
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
        		}
        		
        	}
        });
        
        $("#txt_group_lokasi").click(function() {
        
            if($("#txt_group_lokasi").val() == "")
            {
                $("#txt_lokasi").val([]);
                $('#txt_lokasi').trigger('change');
            }
            else
            {
                var arrLokasi = $("#txt_group_lokasi").val().split(",");
                $("#txt_lokasi").val(arrLokasi);
                $('#txt_lokasi').trigger('change');
            }
            $("#group_lokasi").val($("#txt_group_lokasi option:selected").text());
        })
        
        $("#txt_lokasi").on('change',function() {
             var arrLokasi = $(this).val();
             var valueLokasi = "";
             for(var x = 0 ; x < arrLokasi.length ; x++)
             {
                 valueLokasi += arrLokasi[x]+",";
             }
             valueLokasi = valueLokasi.slice(0, -1);
             
             $("#txt_group_lokasi").val(valueLokasi);
             $('#txt_group_lokasi').trigger('change');
             $("#group_lokasi").val($("#txt_group_lokasi option:selected").text());
        })

    });

    $("#kolom").change(function() {
        kolom = $("#kolom option:selected").text();
        kolomVal = $("#kolom option:selected").val();

        checkData = kolom.substr(0, 4); // CEK NAMA FILTER, APAKAH KODE DAN NAMA
        namaKolom = kolom.substr(5, kolom.length - 1); // CEK JENIS FILTER APA (SUPPLIER,BARANG,PO)

        if (checkData == "Kode" || checkData == "Nama") {
            //UNTUK KOLOM BESERTA COMBOGRID
            if (namaKolom == 'Produk') {
                $('#hide_nilai_list_barang').show();
                $('#hide_nilai_list_jual').hide();
                $('#hide_nilai_list_customer').hide();
                $('#hide_nilai_list_ref').hide();
            }
			else if (namaKolom == 'Customer') {
				$('#hide_nilai_list_customer').show();
                $('#hide_nilai_list_barang').hide();
                $('#hide_nilai_list_jual').hide();
                $('#hide_nilai_list_ref').hide();
				 
            } 
            else if (namaKolom == 'Penjualan') {
                $('#hide_nilai_list_jual').show();
                $('#hide_nilai_list_barang').hide();
				$('#hide_nilai_list_customer').hide();
                $('#hide_nilai_list_ref').hide();
            } 
            else if (namaKolom == 'Penjualan Marketplace') {
                $('#hide_nilai_list_ref').show();
                $('#hide_nilai_list_jual').hide();
                $('#hide_nilai_list_barang').hide();
				$('#hide_nilai_list_customer').hide();
            }


            tipedata = "STRING";
            $('#lap_operatorString').show();
            $('#lap_operatorNumber').hide();

            $('#hide_nilai').hide();
            $('.label_nilai').hide();

            $("#operatorString").val('ADALAH');
            operator = "Adalah";
            operatorVal = "ADALAH";
        } else {
            tipedata = "NUMBER";
            $('#lap_operatorString').hide();
            $('#lap_operatorNumber').show();

            $('#hide_nilai_list_jual').hide();
            $('#hide_nilai_list_barang').hide();
            $('#hide_nilai_list_customer').hide();
            $('#hide_nilai_list_ref').hide();

            $('#hide_nilai').show();
            $('.label_nilai').show();

            $("#operatorNumber").val('SAMA DENGAN');
            operator = "Sama dengan";
            operatorVal = "SAMA DENGAN";
        }
		$('#txt_nilai').val('');
    });

    //OPERATOR STRING
    $("#operatorString").change(function() {
        var operatorString = $("#operatorString option:selected").text();
        var operatorStringVal = $("#operatorString option:selected").val();
        operator = operatorString;
        operatorVal = operatorStringVal;

        if (operatorStringVal == "ADALAH" || operatorStringVal == "TIDAK MENCAKUP") {
            //UNTUK KOLOM BESERTA COMBOGRID
			 if (namaKolom == 'Produk') {
                $('#hide_nilai_list_barang').show();
                $('#hide_nilai_list_jual').hide();
                $('#hide_nilai_list_customer').hide();
                $('#hide_nilai_list_ref').hide();
            }
			else if (namaKolom == 'Customer') {
				$('#hide_nilai_list_customer').show();
                $('#hide_nilai_list_barang').hide();
                $('#hide_nilai_list_jual').hide();
                $('#hide_nilai_list_ref').hide();
				 
            } 
            else if (namaKolom == 'Penjualan') {
                $('#hide_nilai_list_jual').show();
                $('#hide_nilai_list_barang').hide();
				$('#hide_nilai_list_customer').hide();
                $('#hide_nilai_list_ref').hide();
            }
            else if (namaKolom == 'Penjualan Marketplace') {
                $('#hide_nilai_list_ref').show();
                $('#hide_nilai_list_jual').hide();
                $('#hide_nilai_list_barang').hide();
				$('#hide_nilai_list_customer').hide();
            }

            $('#hide_nilai').hide();
            $('.label_nilai').show();
            $('#txt_nilai').removeAttr('disabled');
        } else if (operatorStringVal == "KOSONG" || operatorStringVal == "TIDAK KOSONG") {
            $('#hide_nilai_list_jual').hide();
            $('#hide_nilai_list_barang').hide();
            $('#hide_nilai_list_customer').hide();
            $('#hide_nilai_list_ref').hide();

            $('#hide_nilai').show();
            $('.label_nilai').show();
            $('#txt_nilai').attr('disabled', 'disabled');
        } else {
            $('#hide_nilai_list_jual').hide();
            $('#hide_nilai_list_barang').hide();
            $('#hide_nilai_list_customer').hide();
            $('#hide_nilai_list_ref').hide();

            $('#hide_nilai').show();
            $('.label_nilai').show();
            $('#txt_nilai').removeAttr('disabled');
        }
    });

    //OPERATOR NUMBER
    $("#operatorNumber").change(function() {
        var operatorNumber = $("#operatorNumber option:selected").text();
        var operatorNumberVal = $("#operatorNumber option:selected").val();
        operator = operatorNumber;
        operatorVal = operatorNumberVal;

        if (operatorNumberVal == "NOL" || operatorNumberVal == "TIDAK NOL") {
            $('#hide_nilai_list_jual').hide();
            $('#hide_nilai_list_barang').hide();
            $('#hide_nilai_list_customer').hide();

            $('#hide_nilai').show();
            $('.label_nilai').show();
            $('#txt_nilai').attr('disabled', 'disabled');
        } else {
            $('#hide_nilai_list_jual').hide();
            $('#hide_nilai_list_barang').hide();
            $('#hide_nilai_list_customer').hide();

            $('#hide_nilai').show();
            $('.label_nilai').show();
            $('#txt_nilai').removeAttr('disabled');
        }
    });
    $("#btn_add").click(function() {
        var nilai = "";
        var checknilai = 0;

        //UNTUK KOLOM BESERTA COMBOGRID
		if (namaKolom == 'Produk' && (operator == "Adalah" || operator == "Tidak mencakup")) {
			var value = $('#txt_nilai_list_barang').val().split(" | ");
			
			if(checkData == "Kode") nilai = value[0];
			if(checkData == "Nama")
    		{
        		for(var x = 1 ; x < value.length ; x++)
        		{
        		    var param = "";
        		    if(x != value.length - 1)
        		    {
        		       param = " | ";
        		    }
        		    if(checkData == "Nama") nilai += value[x]+param;
        		}
        		
    		}
				
            if (nilai != "" && nilai != null) {
                checknilai = 1;
            }
        } else if (namaKolom == 'Customer' && (operator == "Adalah" || operator == "Tidak mencakup")) {
			var value = $('#txt_nilai_list_customer').val().split(" | ");
			
			if(checkData == "Kode") nilai = value[0];
			else if(checkData == "Nama") nilai = value[1];
				
            if (nilai != "" && nilai != null) {
                checknilai = 1;
            }
        } else if (namaKolom == 'Penjualan' && (operator == "Adalah" || operator == "Tidak mencakup")) {
            nilai = $('#txt_nilai_list_jual').val();
            if (nilai != "" && nilai != null) {
                checknilai = 1;
            }
        } else if (namaKolom == 'Penjualan Marketplace' && (operator == "Adalah" || operator == "Tidak mencakup")) {
            nilai = $('#txt_nilai_list_ref').val();
            if (nilai != "" && nilai != null) {
                checknilai = 1;
            }
        } else if (operator != "Kosong" && operator != "Tidak kosong" && operator != "Nol" && operator != "Tidak nol") {
            nilai = $('#txt_nilai').val();
            if (nilai != "" && nilai != null) {
                checknilai = 1;
            }
        } else {
            checknilai = 1;
        }

        if (checknilai == 1) {
            var text_laporan = kolom + " " + operator + " " + nilai;

            //PENGGANTI WIDTH SUPAYA TIDAK PENUH
            for (var i = text_laporan.length; i <= 38; i++) {
                text_laporan += "&nbsp;&nbsp;";
            }

            $('#list_filter_laporan').append('<option value="' + tipedata + '|' + kolomVal + '|' + operatorVal + '|' + nilai + '">' + text_laporan + '</option>');
        } else {
            alert("Isi Nilai Telebih Dahulu");
        }
    });

    $("#btn_remove").click(function() {
        $('#list_filter_laporan option:selected').remove();

    });

    function simpan(modeSimpan,tabLaporan) {
        $("#file_name").val("PENJUALAN_"+modeSimpan+"_"+dateNowFormatExcel());
        var arraySimpan = [];
        var lokasi = $('#txt_lokasi').val();

        var options = $('#list_filter_laporan option');

        var values = $.map(options, function(option) {
                var subVal = option.value.split("|");
                var valueExcept = subVal[3];
                for(var x = 4 ; x < subVal.length ; x++)
            	{
            	    var param = "|";
            	    if(checkData == "Nama") valueExcept += param+subVal[x];
            	}
            		
                arraySimpan.push({
                    "TIPEDATA": subVal[0],
                    "KOLOM": subVal[1],
                    "OPERATOR": subVal[2],
                    "NILAI": valueExcept
                });
        });
        
        //SIMPAN DATA FILTER
        $('#data_filter').val(JSON.stringify(arraySimpan));
        $('#data_tampil').val(modeSimpan);
		
		if(lokasi != ""){
			var tab_title = $('#file_name').val();
			var tab_name = tab_title+counter;
			$('#form_input').attr('target',tab_title);
			
			if($("input[name='excel']:checked").val() == "tidak"){
				$("#tab-filter li").removeClass("active");
				$("#tab-report div").removeClass("active");
				$("#tab-filter").append('<li class="active '+tab_title+'" id="tab_'+counter+'"><a href="#tab_grid_'+counter+'" data-toggle="tab" >'+tabLaporan+' &nbsp;<i class="fa fa-close" style="cursor:pointer;" onclick=hapus_tab('+counter+')></i></a></li>');
				
				$("#tab-report").append('<div class="tab-pane active" style="border:0px;" id="tab_grid_'+counter+'"><iframe id="'+tab_title+'" name="'+tab_title+'" width="100%"  height="83%" frameBorder="0" src="#"></iframe></div>');
				counter++;
			}
			
			$('#form_input').submit();
			return false;

		}
		else
		{
			alert('Data Lokasi Tidak Boleh Kosong');
		}
        

    }
	
	function hapus_tab(index)
	{
		$("#tab-filter li").removeClass("active");
		$("#tab-report div").removeClass("active");
			
		$("#tab_"+index).remove();
		$("#tab_grid_"+index).remove();
		
		$("#tab_filter").addClass("active");
		$("#tab_form").addClass("active");
	}
	
	$("#btn_print").click(function(){
		 var iframes = document.getElementsByTagName('iframe');
  
        // Use forEach to loop through the iframes
        var index = null;
        Array.from(iframes).forEach(function(iframe, i) {
          if (iframe.id === $(".active").attr("class").split(" ")[0]) {
            index = i;
          }
        });
        if(index != null)
        { 
		    window.frames[index].print();
        }
	})
	

</script>