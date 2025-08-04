
<div>
    <!-- Main row -->
    <div class="row" style="overflow-y:hidden;">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <!-- <h2 class="page-header">AdminLTE Custom Tabs</h2> -->
                    <h3 class="box-title" style="font-size:20pt;">Laporan History Program </h3> <button class="btn pull-right" id="btn_print" style="font-size:20pt;"><i class="fa fa-print" ></i></button>
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
                                <form method='post' target="LaporanHistory" action='<?=base_url()?>Master/Laporan/LaporanHistory/laporan<?='?kode='.$kodemenu?>' id="form_input">
                                    <div class="box-body">
                                        <div class="form-group col-sm-6">
											<h4 class="box-title">
												<b>Filter Data</b>
											</h4>

                                            <div class="form-group">
                                                <label for="tglhistory">
                                                    <input type="radio" id="tgl" name="tgl" value="TGLHISTORY" class="flat-blue" checked> 
                                                    Tgl History
                                                </label>

                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="tglHistory"  name="tglHistory" style="width:97%; border:1px solid #B5B4B4; border-radius:5px;">
                                                </div>
                                                <!-- /.input group -->
                                            </div>

                                            <div class="form-group">
                                                <label for="tgltrans">
                                                    <input type="radio" id="tgl" name="tgl" value="TGLTRANS" class="flat-blue"> 
                                                     Tgl Trans
                                                </label>

                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input type="text" class="form-control" id="tglTrans"  name="tglTrans" style="width:97%; border:1px solid #B5B4B4; border-radius:5px;">
                                                </div>
                                                <!-- /.input group -->
                                            </div>

                                            <div class="form-group">
                                                <label for="Nilai">User</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-user"></i>
                                                    </div>
                                                    <div id="hide_nilai_user">
                                                        <input class="form-control " id="txt_nilai_user" name="txt_nilai_user"  prompt="Nama" style="width:97%; border:1px solid #B5B4B4; border-radius:5px;">
                                                    </div>
												</div>
											</div>
											
											<div class="form-group">
                                                <label for="Nilai">Kode</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-window-maximize"></i>
                                                    </div>
													<div id="hide_nilai_kode">
                                                        <input class="form-control " id="txt_nilai_kode" name="txt_nilai_kode"  prompt="Kode" style="width:97%; border:1px solid #B5B4B4; border-radius:5px;">
                                                    </div>
												</div>
											</div>
                                            <hr></hr>
											<div class="form-group">
                                                <label for="Nilai">Kode Barang</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-window-maximize"></i>
                                                    </div>
													<div id="hide_nilai_kode">
                                                        <select id="txt_nilai_list_barang" class="form-control select2" name="txt_nilai_list_barang" style="width:97%" prompt="Nilai"/>
															<?=comboGrid("model_master_barang")?>
														</select>
                                                    </div>
												</div>
											</div>
                                            <div class="form-group">
                                                <label for="Lokasi">Lokasi</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-map-marker"></i>
                                                    </div>
                                                    <select class="form-control select2" id="txt_lokasi" name="txt_lokasi[]" placeholder="Lokasi..." style="width:97%;">
														<?=comboGrid("model_master_lokasi")?>
													</select>
														
                                                </div>
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
                                            <div class="info-box bg-yellow" onclick="simpan('REGISTER','Laporan History')" style="cursor:pointer;">
												<span class="info-box-icon"><i class="ion ion-ios-book-outline" style="margin-top:20%; font-size:40pt;"></i></span>

												<div class="info-box-content">
												   <span class="info-box-number">Laporan History</span>
												   <span>Menampilkan Laporan History.</span>
												</div>
												<!-- /.info-box-content -->
											</div>
											 <div class="info-box bg-yellow" onclick="simpan('REGISTERBARANG','Laporan History Barang')" style="cursor:pointer;">
												<span class="info-box-icon"><i class="ion ion-ios-book-outline" style="margin-top:20%; font-size:40pt;"></i></span>

												<div class="info-box-content">
												   <span class="info-box-number">Laporan History Barang</span>
												   <span>Menampilkan Laporan History Barang.</span>
												</div>
												<!-- /.info-box-content -->
											</div>
                                        </div>
                                    </div>
									
									<!-- NAMA LAPORAN -->
									<input type="hidden" name="file_name" id="file_name" value="LaporanHistory">
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

// var kolom = "Kode Transfer Persediaan";
// var namaKolom = "Transfer Persediaan";
// var kolomVal = "TTRANSFER.KODETRANSFER";
var checkData = "Kode";
var operator = "Adalah";
var operatorVal = "ADALAH";
var tipedata = "STRING";

$(document).ready(function(){
    $('#tglTrans').daterangepicker({
		locale: {
			  format: 'YYYY-MM-DD'
		}
	});

	$('#tglHistory').daterangepicker({
		locale: {
			  format: 'YYYY-MM-DD'
		}
	});

    $('.select2').select2({
		  theme: "classic"
	});

    var format_uang = $.fn.dataTable.render.number(',', '.', 2, '');
});

function simpan(modeSimpan,tabLaporan) {
    var name = "";
    if(tabLaporan == "REGISTERBARANG")
    {
        name = "BARANG";
    }
    $("#file_name").val("HISTORY_PROGRAM_"+name+"_"+dateNowFormatExcel());
    $('#data_tampil').val(modeSimpan);
    var arraySimpan = [];
	
    var options = $('#list_filter_laporan option');
    	var tab_title = $('#file_name').val();
    	var tab_name = tab_title+counter;
    	$('#form_input').attr('target',tab_title);
		
			if($("input[name='excel']:checked").val() == "tidak"){
				$("#tab-filter li").removeClass("active");
				$("#tab-report div").removeClass("active");
				$("#tab-filter").append('<li class="'+tab_title+' active" id="tab_'+counter+'"><a href="#tab_grid_'+counter+'" data-toggle="tab" >'+tabLaporan+' &nbsp;<i class="fa fa-close" style="cursor:pointer;" onclick=hapus_tab('+counter+')></i></a></li>');
				
				$("#tab-report").append('<div class="tab-pane active" style="border:0px;" id="tab_grid_'+counter+'"><iframe id="'+tab_title+'" name="'+tab_title+'" width="100%"  height="83%" frameBorder="0" src="#"></iframe></div>');
				counter++;
			}
		
    	$('#form_input').submit();
    	return false;
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