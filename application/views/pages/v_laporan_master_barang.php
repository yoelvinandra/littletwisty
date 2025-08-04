
<div>
    <!-- Main row -->
    <div class="row" style="overflow-y:hidden;">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <!-- <h2 class="page-header">AdminLTE Custom Tabs</h2> -->
                    <h3 class="box-title" style="font-size:20pt;">Laporan Master Barang & Jasa</h3> <button class="btn pull-right" id="btn_print" style="font-size:20pt;"><i class="fa fa-print" ></i></button>
                    <!-- <button class="btn">Tambah</button> -->
                </div>
                <!-- Custom Tabs -->
                <div >
                    <ul class="nav nav-tabs" id="tab-filter" >
                        <li class="active" id="tab_filter"><a href="#tab_form" data-toggle="tab">Filter</a></li>
                    </ul>
                    <div class="tab-content" id="tab-report">
                        <div class="tab-pane active" id="tab_form">
							
                            <div class="col-sm-12">
                                <!-- form start -->
                                <form method='post' target="LaporanBarang" action='<?=base_url()?>Master/Laporan/LaporanBarang/laporan<?='?kode='.$kodemenu?>' id="form_input">
                                    <div class="box-body">
                                        <div class="form-group col-sm-6">
											<h4 class="box-title">
												<b>Filter Data</b>
											</h4>
											
                                            <div class="form-group">
                                                <label for="Status">Status</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-sticky-note"></i>
                                                    </div>
                                                    <select class="form-control select2"id="cbStatus" name="cbStatus[]" style="width:97%;">
                                                        <option value="" selected>Semua</option>
                                                        <option value="1" >Aktif</option>
                                                        <option value="0" >Tidak</option>
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

                                                   <div id="hide_nilai_list_barang">
														<select id="txt_nilai_list_barang" class="form-control select2" name="txt_nilai_list_barang" style="width:97%" prompt="Nilai"/>
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
                                        <div class="form-group col-sm-6">
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
											<div class="info-box bg-yellow" onclick="simpan('REKAP')" style="cursor:pointer">
												<span class="info-box-icon"><i class="ion ion-ios-book-outline" style="margin-top:20%; font-size:40pt;"></i></span>

												<div class="info-box-content">
												  <span class="info-box-number">Laporan Barang & Jasa</span>
												  <span>Menampilkan data dari barang yang sudah pernah dibuat.</span>
												</div>
												<!-- /.info-box-content -->
											</div>	

                                        </div>
                                    </div>
									
									<!-- NAMA LAPORAN -->
									<input type="hidden" name="file_name" id="file_name" value="LaporanBarang">
									<input type="hidden" id="data_filter" name="data_filter">
									<input type="hidden" id="data_tampil" name="data_tampil">
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
<script>
var counter = 0;

var kolom = "Kode Produk";
var namaKolom = "Barang";
var kolomVal = "MBARANG.KODEBARANG"
var checkData = "Kode";
var operator = "Adalah";
var operatorVal = "ADALAH";
var tipedata = "STRING";

$(document).ready(function(){
	 $('.select2').select2({
		  theme: "classic"
	});

    var format_uang = $.fn.dataTable.render.number(',', '.', 2, '');

});
//FILTER KOLOM
$("#kolom").change(function() {
		kolom = $("#kolom option:selected").text();
		kolomVal = $("#kolom option:selected").val();

		checkData = kolom.substr(0, 4); // CEK NAMA FILTER, APAKAH KODE DAN NAMA
		namaKolom = kolom.substr(5, kolom.length - 1); // CEK JENIS FILTER APA (SUPPLIER,BARANG,PO)
		
		
		if(checkData == "Kode" || checkData == "Nama" )
		{
			//UNTUK KOLOM BESERTA COMBOGRID

			if(namaKolom == 'Merk')
			{
				$('#hide_nilai_list_merk').show();
				$('#hide_nilai_list_barang').hide();
			}
			else if(namaKolom == 'Produk')
			{
				$('#hide_nilai_list_barang').show();
				$('#hide_nilai_list_merk').hide();
			}
			tipedata = "STRING";
			$('#lap_operatorString').show();
			$('#lap_operatorNumber').hide();
			
			
			$('#hide_nilai').hide();
			$('.label_nilai').show();
			
			$("#operatorString").val('ADALAH');
			operator = "Adalah";
			operatorVal = "ADALAH";
		}
		
		else
		{
			tipedata = "NUMBER";
			$('#lap_operatorString').hide();
			$('#lap_operatorNumber').show();
			
			$('#hide_nilai_list_merk').hide();
			$('#hide_nilai_list_barang').hide();
			
			$('#hide_nilai').show();
			$('.label_nilai').show();
			
			$("#operatorNumber").val('SAMA DENGAN');
			operator = "Sama dengan";
			operatorVal = "SAMA DENGAN";
		}
		
		//CLEAR FIELD SETIAP UBAH
		$('#txt_nilai').val('');

});

//OPERATOR STRING
$("#operatorString").change(function() {
        var operatorString = $("#operatorString option:selected").text();
        var operatorStringVal = $("#operatorString option:selected").val();
        operator = operatorString;
        operatorVal = operatorStringVal;

		if(operatorStringVal == "ADALAH" || operatorStringVal == "TIDAK MENCAKUP" )
		{
			//UNTUK KOLOM BESERTA COMBOGRID
			if(namaKolom == 'Produk')
			{
				$('#hide_nilai_list_barang').show();
			}
		
			$('#hide_nilai').hide();
			$('.label_nilai').show();
			$('#txt_nilai').removeAttr('disabled');
		}
		else if(operatorStringVal == "KOSONG" || operatorStringVal == "TIDAK KOSONG")
		{

			$('#hide_nilai_list_barang').hide();
			
			$('#hide_nilai').show();
			$('.label_nilai').show();
			$('#txt_nilai').attr('disabled', 'disabled');
		}
		else
		{
			$('#hide_nilai_list_barang').hide();
			
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
		
		if(operatorNumberVal == "NOL" || operatorNumberVal == "TIDAK NOL")
		{
			$('#hide_nilai_list_barang').hide();
			
			$('#hide_nilai').show();
			$('.label_nilai').show();
			$('#txt_nilai').attr('disabled', 'disabled');
		}
		else
		{
			$('#hide_nilai_list_barang').hide();
			
			$('#hide_nilai').show();
			$('.label_nilai').show();
			$('#txt_nilai').removeAttr('disabled');
		}
});


//TAMBAH FILTER
$("#btn_add").click(function(){

	var nilai="";
	var checknilai = 0;
	
	//UNTUK KOLOM BESERTA COMBOGRID

	if(namaKolom == 'Produk' && (operator == "Adalah" || operator == "Tidak mencakup"))
	{
		var value = $('#txt_nilai_list_barang').val().split(" | ");
			
		if(checkData == "Kode") nilai = value[0];
		else if(checkData == "Nama") nilai = value[1];
			
        if (nilai != "" && nilai != null) {
            checknilai = 1;
        }
	}
	else if(operator != "Kosong" && operator != "Tidak kosong" && operator != "Nol" && operator != "Tidak nol")
	{
		 nilai = $('#txt_nilai').val();
         if (nilai != "") {
             checknilai = 1;
         }
	}
	else
	{
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

//HAPUS FILTER

$("#btn_remove").click(function() {
    $('#list_filter_laporan option:selected').remove();

});

function simpan(modeSimpan) {
    var arraySimpan = [];
    var lokasi = $('#txt_lokasi').val();
	
    var options = $('#list_filter_laporan option');

    var values = $.map(options, function(option) {
        var subVal = option.value.split("|");
        arraySimpan.push({
            "TIPEDATA": subVal[0],
            "KOLOM": subVal[1],
            "OPERATOR": subVal[2],
            "NILAI": subVal[3]
        });
    });
    //SIMPAN DATA FILTER
    $('#data_filter').val(JSON.stringify(arraySimpan));
    $('#data_tampil').val(modeSimpan);

    if(lokasi != ""){
    	var tab_title = $('#file_name').val();
    	var tab_name = tab_title+counter;
    	$('#form_input').attr('target',tab_name);
		
		if($("input[name='excel']:checked").val() == "tidak"){
			$("#tab-filter li").removeClass("active");
			$("#tab-report div").removeClass("active");
			$("#tab-filter").append('<li class="active" id="tab_'+counter+'"><a href="#tab_grid_'+counter+'" data-toggle="tab" >Laporan &nbsp;<i class="fa fa-close" style="cursor:pointer;" onclick=hapus_tab('+counter+')></i></a></li>');
			
			$("#tab-report").append('<div class="tab-pane active" style="border:0px;" id="tab_grid_'+counter+'"><iframe id="'+tab_name+'" name="'+tab_name+'" width="100%"  height="83%" frameBorder="0" src="#"></iframe></div>');
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
	var tab_title = $('#file_name').val();
	var index = tab_title+$(".active").attr("id").substr(-1);
	
	window.frames[index].print();
})
</script>