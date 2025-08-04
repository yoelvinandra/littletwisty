
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Master User
    <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt;"  onclick="exportTableToExcel()">Excel</button>
  </h1>
  <!-- <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
  </ol> -->
</section>

<!-- Main content -->
<section class="content">
  
  <!-- Main row -->
  <div class="row">
      <div class="col-md-12">
        <div class="box">
			<div class="box-header">
				<button class="btn btn-success" onclick="javascript:tambah()">Tambah</button>
			</div>
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom" >
				<ul class="nav nav-tabs" id="tab_transaksi">
					<li class="active"><a href="#tab_grid" data-toggle="tab">Grid</a></li>
					<li><a href="#tab_form" data-toggle="tab">Tambah</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_grid">
						<div class="box-body">
							<table id="dataGrid" class="table table-bordered table-striped table-hover display nowrap" width="100%">
								<!-- class="table-hover"> -->
								<thead>
								<tr>
                                    <th width="35px"></th>
									<th width="25px">Login</th>
									<th>ID</th>
									<th>User ID</th>
									<th>Nama</th>
									<th>HP</th>
									<th>Email</th>
									<th>Catatan</th>
									<th width="40px">User Input</th>
									<th width="40px">Tgl. Input</th>
									<th width="25px">Aktif</th>
								</tr>
								</thead>
							</table>
						</div>
                        <div id="tableExcel" style="display:none;" ></div>
					</div>
					<div class="tab-pane" id="tab_form">
						<div class="box-body">
							 <div class="nav-tabs-custom" >
								<ul class="nav nav-tabs" id="tab_transaksi">
									<li class="active"><a href="#tab_umum" data-toggle="tab">Umum</a></li>
									<li><a href="#tab_hak_akses" data-toggle="tab">Hak Akses</a></li>
									<li><a href="#tab_lokasi_akses" data-toggle="tab">Lokasi</a></li>
									<li>									
										<button type="button" id="btn_simpan" class="btn btn-primary" onclick="javascript:simpan()">Simpan</button>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab_umum">
										<div class="box-body">
											<div><h2>Data Umum</h2></div>
											<div class="col-md-12">
												<!-- form start -->
												<form role="form" id="form_input">
													<input type="hidden" id="mode" name="mode">
													<input type="hidden" id="IDUSER" name="IDUSER">
													<div class="box-body">
														<div class="form-group col-md-6">
															<label for="USERID">User ID <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i> &nbsp;&nbsp;&nbsp;</label> <label><input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Aktif </label> &nbsp;  &nbsp; <input type="hidden"id="LOGIN" name="LOGIN" value="1" >
															<input type="text" class="form-control" id="USERID" name="USERID" placeholder="User ID" onkeydown="return onlyAlphabets(event)">
															<br>
															<label>Nama User <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
															<input type="text" class="form-control" id="USERNAME" name="USERNAME" placeholder="Nama User">
															<br>
															<label>Password <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
															<input type="password" class="form-control" id="PASS" name="PASS" placeholder="Password">
															<br>
															<label>Ulangi Password <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
															<input type="password" class="form-control" id="RE_PASS" name="RE_PASS" placeholder="Re-type Password">
															<br>
															<label>HP</label>
															<input type="text" class="form-control" id="HP" name="HP" placeholder="Handphone">
															<br>
															<label>Email</label>
															<input type="text" class="form-control" id="EMAIL" name="EMAIL" placeholder="Email">
														</div>
														<div class="form-group col-md-10">
															<label>Catatan</label>
															<textarea class="form-control" rows="3" id="CATATAN" name="CATATAN" placeholder="Catatan....."></textarea>
														</div>
													</div>
													<!-- /.box-body -->
												</form>	
											</div>
										</div>
									<!-- /.tab-pane -->
									</div>
									
									<div class="tab-pane" id="tab_hak_akses">
									  <div class="box-body">
									     <div><h2>Dashboard <label class="pull-right"><input type="checkbox" id="dashboardAll"> &nbsp;&nbsp;Semua Akses</label></h2></div>
										  <table id="dataGridDashboard" class="table table-bordered table-striped table-hover display nowrap" width="100%">
											  <!-- class="table-hover"> -->
											  <thead>
											  <tr>
												  <th></th>
												  <th width="150px">Header Modul</th>
												  <th>Nama Modul</th>
												  <th width="40px" > <!--<input type="checkbox" id="hakakses_Master"></input>-->  	Akses </th>
											  </tr>                 
											  </thead>
										  </table>
										  <br>
										  <div><h2>Master <label class="pull-right"><input type="checkbox" id="masterAll"> &nbsp;&nbsp;Semua Akses</label></h2></div>
										  <table id="dataGridMaster" class="table table-bordered table-striped table-hover display nowrap" width="100%">
											  <!-- class="table-hover"> -->
											  <thead>
											  <tr>
												  <th></th>
												  <th width="150px">Header Modul</th>
												  <th>Nama Modul</th>
												  <th width="40px" > <!--<input type="checkbox" id="hakakses_Master"></input>-->  	Akses </th>
												  <th width="40px" > <!--<input type="checkbox" id="tambah_Master"></input>--> 	Tambah</th>
												  <th width="40px" > <!--<input type="checkbox" id="ubah_Master"></input>-->  	Ubah  </th>
												  <th width="40px" > <!--<input type="checkbox" id="hapus_Master"></input>-->  	Hapus </th>
											  </tr>                 
											  </thead>
										  </table>
										  <br>
										  <div><h2>Transaksi <label class="pull-right"><input type="checkbox" id="transaksiAll"> &nbsp;&nbsp;Semua Akses</label></h2></div>
										  <table id="dataGridTransaksi" class="table table-bordered table-striped table-hover display nowrap" width="100%">
											 <!-- class="table-hover"> -->
											 <thead>
											 <tr>
												  <th></th>
												  <th width="150px">Header Modul</th>
												  <th>Nama Modul</th>
												  <th width="40px"><!--<input type="checkbox" id="hakakses_Transaksi"></input>-->  Akses</th>
												  <th width="40px"><!--<input type="checkbox" id="tambah_Transaksi"></input>--> 	Tambah</th>
												  <th width="40px"><!--<input type="checkbox" id="ubah_Transaksi"></input>-->  	Ubah</th>
												  <th width="40px"><!--<input type="checkbox" id="cetak_Transaksi"></input>-->  	Cetak</th>
												  <th width="40px"><!--<input type="checkbox" id="hapus_Transaksi"></input>--> 	Hapus</th>
											 </tr>
											 </thead>
										  </table>
										  <br>
										  <div><h2>Laporan <label class="pull-right"><input type="checkbox" id="laporanAll"> &nbsp;&nbsp;Semua Akses</label></h2></div>
										  <table id="dataGridLaporan" class="table table-bordered table-striped table-hover display nowrap" width="100%">
											 <!-- class="table-hover"> -->
											 <thead>
											 <tr>
												 <th><!--<input type="checkbox" id="lokasi_all"></input>--></th>
												 <th width="150px">Header Modul</th>
												 <th>Nama Modul</th>
												 <th width="40px"><!--<input type="checkbox" id="hakakses_Laporan"></input>--> Akses</th>
												 <th width="40px"><!--<input type="checkbox" id="lihatharga_Laporan"></input>--> Lihat Harga</th>
											 </tr>
											 </thead>
										  </table>
										</div>
									</div>
									<div class="tab-pane" id="tab_lokasi_akses">
									  <div class="box-body">
										  <div><h2>Lokasi <label class="pull-right"><input type="checkbox" id="lokasiAll"> &nbsp;&nbsp;Semua Lokasi</label></h2></div>
										  <table id="dataGridLokasi" class="table table-bordered table-striped table-hover display nowrap" width="100%">
											  <!-- class="table-hover"> -->
											  <thead>
											  <tr>
												  <th width="20px"></th>
												  <th width="100px">Kode Lokasi</th>
												  <th >Nama Lokasi</th>
												  <th width="50px"></th>
											  </tr>
											  </thead>
										  </table>
									  </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<!-- nav-tabs-custom -->
			</div>
		</div>
    <!-- /.col -->
	  </div>
  </div>
  <!-- /.row (main row) -->

</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<!-- /.content -->

<script>
var indexRow;
$(document).ready(function() {
    $("#mode").val('tambah');
    $("#STATUS").prop('checked',true).iCheck('update');
    
    $("#dashboardAll").click(function(){
        $('#dataGridDashboard tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
            
            checkbox.prop('checked', $("#dashboardAll").prop('checked'));
            
            var row = $('#dataGridDashboard').DataTable().row( $(this).parents('tr') ).data();
    		var check = 0;
    		
    		if($("#dashboardAll").prop('checked'))
    		{check = 1;}
    		else
    		{check = 0;}
    		
    		row.HAKAKSES	= check; 
    		
        });
    });
    
    $("#masterAll").click(function(){
        $('#dataGridMaster tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
            
            checkbox.prop('checked', $("#masterAll").prop('checked'));
            
            var row = $('#dataGridMaster').DataTable().row( $(this).parents('tr') ).data();
    		var check = 0;
    		
    		if($("#masterAll").prop('checked'))
    		{check = 1;}
    		else
    		{check = 0;}
    		
    		row.HAKAKSES	= check; 
    		row.TAMBAH 		= check;
    		row.UBAH 		= check;
    		row.CETAK 		= check;
    		row.HAPUS 		= check;
    		
        });
    });
    
    $("#transaksiAll").click(function(){
        $('#dataGridTransaksi tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
            
            checkbox.prop('checked', $("#transaksiAll").prop('checked'));
            
            var row = $('#dataGridTransaksi').DataTable().row( $(this).parents('tr') ).data();
    		var check = 0;
    		
    		if($("#transaksiAll").prop('checked'))
    		{check = 1;}
    		else
    		{check = 0;}
    		
    		row.HAKAKSES	= check; 
    		row.TAMBAH 		= check;
    		row.UBAH 		= check;
    		row.CETAK 		= check;
    		row.HAPUS 		= check;
    		
        });
    });
    
    $("#laporanAll").click(function(){
        $('#dataGridLaporan tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
            
            checkbox.prop('checked', $("#laporanAll").prop('checked'));
            
            var row = $('#dataGridLaporan').DataTable().row( $(this).parents('tr') ).data();
    		var check = 0;
    		
    		if($("#laporanAll").prop('checked'))
    		{check = 1;}
    		else
    		{check = 0;}
    		
    		row.HAKAKSES	= check; 
    		row.LIHATHARGA	= check; 
    		
        });
    });

    $('#dataGrid').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/User/dataGrid',
			dataSrc: "rows",
			dataFilter: function (data) {
                // Refresh the new table whenever DataTable reloads
                var allData = JSON.parse(data).rows; // Get all rows' data

                // Create the HTML structure for the new table
                var newTable = $('<table id="newTable" class="table table-bordered">');
                var thead = $('<thead>').append('<tr><th>User ID</th><th>Nama User</th><th>HP</th><th>Email</th><th>Catatan</th><th>User Buat</th><th>Tgl Entry</th><th>Status</th></tr>');
                var tbody = $('<tbody>');
                 // Loop through the DataTable data and create rows for the new table
         
                allData.forEach(function (row, index) {
                    var tr = $('<tr>');
            
                    tr.append('<td>' + row.USERID + '</td>');
                    tr.append('<td>' + row.USERNAME + '</td>');
                    tr.append('<td>' + (row.HP== null?"":row.HP) + '</td>');
                    tr.append('<td>' + (row.EMAIL== null?"":row.EMAIL) + '</td>');
                    tr.append('<td>' + (row.CATATAN== null?"":row.CATATAN) + '</td>');
                    tr.append('<td>' + row.USERBUAT + '</td>');
                    tr.append('<td class="text-center">' + row.TGLENTRY + '</td>');
                    tr.append('<td class="text-center">' + (row.STATUS == 1 ? 'AKTIF' : 'NON AKTIF') + '</td>');
            
                    // Append the row to the tbody
                    tbody.append(tr);
                });
            
                // Append the thead and tbody to the new table
                newTable.append(thead).append(tbody);
                // Append the new table to the DOM (you can specify where you want the new table to appear)
                $('#tableExcel').html(newTable); 
                
                return data;
            }
		},
        columns:[
            {data: ''},
            {data: 'STATUSLOGIN', visible: false},
            {data: 'IDUSER', visible: false},
            {data: 'USERID'},
            {data: 'USERNAME'},
			{data: 'HP'},
			{data: 'EMAIL'},
            {data: 'CATATAN'},
            {data: 'USERBUAT'},
            {data: 'TGLENTRY', className:"text-center"},
            {data: 'STATUS', className:"text-center"},
        ],
		columnDefs: [ 
			{
			"targets": 0,
            "data": null,
            "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
			},
            {
            "targets": -1,
			"render" :function (data) 
					{
						if(data == 1) return "YA";
						else return "TIDAK";
					},	
			}
		]
    });
	
	//DAPATKAN INDEX
	var table = $('#dataGrid').DataTable();
	$('#dataGrid tbody').on( 'click', 'button', function () {
		var row = table.row( $(this).parents('tr') ).data();
		var mode = $(this).attr("id");
		
		if(mode == "btn_ubah"){ ubah(row); }
		else if(mode == "btn_hapus"){ hapus(row); }

	} );
	
	$('#dataGridDashboard').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url       : base_url+'Master/Data/User/treeGrid',
			data      : function(e){
						e.jenismenu = "Dashboard";
						e.iduser 	= getIduser();
						},
			type      : "POST",
			dataSrc   : "rows",
			dataType  : 'json',
		},
        columns:[
            {data: 'KODEMODUL', visible: false},
            {data: 'HEADER'},
            {data: 'NAMAMODUL'},
            {data: 'HAKAKSES', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.HAKAKSES == 1) {
							return '<input type="checkbox" class="hakakses" checked>';
						}
						else{
							return '<input type="checkbox" class="hakakses">';
						}
						return data;
					},
					className: "dt-body-center"},
        ],
    });

	
	$('#dataGridMaster').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url       : base_url+'Master/Data/User/treeGrid',
			data      : function(e){
						e.jenismenu = "Data";
						e.iduser 	= getIduser();
						},
			type      : "POST",
			dataSrc   : "rows",
			dataType  : 'json',
		},
        columns:[
            {data: 'KODEMODUL', visible: false},
            {data: 'HEADER'},
            {data: 'NAMAMODUL'},
            {data: 'HAKAKSES', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.HAKAKSES == 1) {
							return '<input type="checkbox" class="hakakses" checked>';
						}
						else{
							return '<input type="checkbox" class="hakakses">';
						}
						return data;
					},
					className: "dt-body-center"},
			{data: 'TAMBAH', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.TAMBAH == 1) {
							return '<input type="checkbox" class="tambah" checked>';
						}
						else{
							return '<input type="checkbox" class="tambah">';
						}
						return data;
					},
					className: "dt-body-center"},
			{data: 'UBAH', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.UBAH == 1) {
							return '<input type="checkbox" class="ubah" checked>';
						}
						else{
							return '<input type="checkbox" class="ubah">';
						}
						return data;
					},
					className: "dt-body-center"},
            {data: 'HAPUS', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.HAPUS == 1) {
							return '<input type="checkbox" class="hapus" checked>';
						}
						else{
							return '<input type="checkbox" class="hapus">';
						}
						return data;
					},
					className: "dt-body-center"},
        ],
    });
	
// 	$("#hakakses_Master, #tambah_Master, #ubah_Master, #hapus_Master,#hakakses_Transaksi, #tambah_Transaksi, #ubah_Transaksi, #hapus_Transaksi,#cetak_Transaksi,#hakakses_Laporan,#lihatharga_Laporan").click(function(){
// 		//BUAT DETEKSI CHECKBOX MANA YANG DITEKAN UNTUK AKSES KESELURUHAN
// 		var Id = $(this).attr('id').split("_");
		
// 		if($(this).prop("checked"))
// 		{check = 1;}
// 		else
// 		{check = 0;}
		
// 		$("#dataGrid"+Id[1]+" tbody tr td ."+Id[0]).prop("checked",check)
		
// 		rowName = Id[0].toUpperCase();
// 		//alert(rowName);
		
// 		var row = $('#dataGrid'+Id[1]).DataTable().rows().data();
// 		for(var i = 0 ;i <row.length;i++)
// 		{
// 			if(rowName == 'HAKAKSES'){
// 				//ISI CHECKBOX 1 / 0
// 				row[i].HAKAKSES = check;
// 				$("#dataGrid"+Id[1]+" tbody tr td .hakakses").prop("checked",check);
// 				row[i].TAMBAH = check;
// 				$("#dataGrid"+Id[1]+" tbody tr td .tambah").prop("checked",check);
// 				row[i].UBAH = check;
// 				$("#dataGrid"+Id[1]+" tbody tr td .ubah").prop("checked",check);
// 				row[i].HAPUS = check;
// 				$("#dataGrid"+Id[1]+" tbody tr td .hapus").prop("checked",check);
				
// 				if(Id[1] == "Master"){
// 					$("#tambah_Master").prop("checked",check);
// 					$("#ubah_Master").prop("checked",check);
// 					$("#hapus_Master").prop("checked",check);
// 				}
// 				if(Id[1] == "Transaksi"){
// 					$("#tambah_Transaksi").prop("checked",check);
// 					$("#ubah_Transaksi").prop("checked",check);
// 					$("#hapus_Transaksi").prop("checked",check);
// 					$("#cetak_Transaksi").prop("checked",check);
					
// 					row[i].CETAK = check;
// 					$("#dataGrid"+Id[1]+" tbody tr td .cetak").prop("checked",check);
// 				}
// 				else if(Id[1] == "Laporan"){
// 					$("#lihatharga_Laporan").prop("checked",check);
					
// 					row[i].LIHATHARGA = check;
// 					$("#dataGrid"+Id[1]+" tbody tr td .lihatharga").prop("checked",check);
// 				}
				
				
// 			}
// 			else if(rowName == 'TAMBAH'){
// 				row[i].TAMBAH = check;
// 			}
// 			else if(rowName == 'UBAH'){
// 				row[i].UBAH = check;
// 			}
// 			else if(rowName == 'CETAK'){
// 				row[i].CETAK = check;
// 			}
// 			else if(rowName == 'HAPUS'){
// 				row[i].HAPUS = check;
// 			}
// 			else if(rowName == 'LIHATHARGA'){
// 				row[i].LIHATHARGA = check;
// 			}

// 		}
		
// 	})

    $('#dataGridDashboard tbody').on('click', 'tr input', function () {
		var kolom = $(this).attr("class");
		var row = $('#dataGridDashboard').DataTable().row( $(this).parents('tr') ).data();
		var check = 0;
		
		
		if($(this).prop("checked"))
		{check = 1;}
		else
		{check = 0;}
		
		if(kolom == "hakakses")
		{
			//alert(JSON.stringify($('#dataGridMaster').DataTable().row( $(this).parents('tr') ).indexes()));
			
			row.HAKAKSES	= check; 
			//row.TAMBAH 		= check;
			//row.UBAH 		= check;
			//row.HAPUS 		= check;
		}
	
		//alert(row.HAKAKSES+" "+row.TAMBAH+" "+row.UBAH+" "+row.HAPUS);
		//$(this).prop("checked",true);//CENTANG CHECKBOX
		//$('#table_hutang').DataTable().data()[indexHutang].LUNAS = 1; 
		//indexHutang++;
	});
	
	$('#dataGridMaster tbody').on('click', 'tr input', function () {
		var kolom = $(this).attr("class");
		var row = $('#dataGridMaster').DataTable().row( $(this).parents('tr') ).data();
		var check = 0;
		
		
		if($(this).prop("checked"))
		{check = 1;}
		else
		{check = 0;}
		
		if(kolom == "hakakses")
		{
			//alert(JSON.stringify($('#dataGridMaster').DataTable().row( $(this).parents('tr') ).indexes()));
			
			row.HAKAKSES	= check; 
			//row.TAMBAH 		= check;
			//row.UBAH 		= check;
			//row.HAPUS 		= check;
		}
		if(kolom == "tambah")
		{	
			row.TAMBAH 		= check;
		}
		if(kolom == "ubah")
		{	
			row.UBAH 		= check;
		}
		if(kolom == "hapus")
		{	
			row.HAPUS 		= check;
		}
	
		//alert(row.HAKAKSES+" "+row.TAMBAH+" "+row.UBAH+" "+row.HAPUS);
		//$(this).prop("checked",true);//CENTANG CHECKBOX
		//$('#table_hutang').DataTable().data()[indexHutang].LUNAS = 1; 
		//indexHutang++;
	});
	
	$('#dataGridTransaksi').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url       : base_url+'Master/Data/User/treeGrid',
			data      : function(e){
						e.jenismenu = "Transaksi";
						e.iduser 	= getIduser();
						},
			type      : "POST",
			dataSrc   : "rows",
			dataType  : 'json',
		},
        columns:[
            {data: 'KODEMODUL', visible: false},
			{data: 'HEADER'},
            {data: 'NAMAMODUL'},
            {data: 'HAKAKSES', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.HAKAKSES == 1) {
							return '<input type="checkbox" class="hakakses" checked>';
						}
						else{
							return '<input type="checkbox" class="hakakses">';
						}
						return data;
					},
					className: "dt-body-center"},
			{data: 'TAMBAH', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.TAMBAH == 1) {
							return '<input type="checkbox" class="tambah" checked>';
						}
						else{
							return '<input type="checkbox" class="tambah">';
						}
						return data;
					},
					className: "dt-body-center"},
			{data: 'UBAH', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.UBAH == 1) {
							return '<input type="checkbox" class="ubah" checked>';
						}
						else{
							return '<input type="checkbox" class="ubah">';
						}
						return data;
					},
					className: "dt-body-center"},
			{data: 'CETAK', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.CETAK == 1) {
							return '<input type="checkbox" class="cetak" checked>';
						}
						else{
							return '<input type="checkbox" class="cetak">';
						}
						return data;
					},
					className: "dt-body-center"},
            {data: 'HAPUS', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.HAPUS == 1) {
							return '<input type="checkbox" class="hapus" checked>';
						}
						else{
							return '<input type="checkbox" class="hapus">';
						}
						return data;
					},
					className: "dt-body-center"},
        ],
    });
	
	$('#dataGridTransaksi tbody').on('click', 'tr input', function () {
		var kolom = $(this).attr("class");
		var row = $('#dataGridTransaksi').DataTable().row( $(this).parents('tr') ).data();
		var check = 0;
		
		
		if($(this).prop("checked"))
		{check = 1;}
		else
		{check = 0;}
		
		if(kolom == "hakakses")
		{
			row.HAKAKSES	= check; 
			//row.TAMBAH 		= check;
			//row.UBAH 		= check;
			//row.CETAK 		= check;
			//row.HAPUS 		= check;
		}
		if(kolom == "tambah")
		{	
			row.TAMBAH 		= check;
		}
		if(kolom == "ubah")
		{	
			row.UBAH 		= check;
		}
		if(kolom == "cetak")
		{	
			row.CETAK 		= check;
		}
		if(kolom == "hapus")
		{	
			row.HAPUS 		= check;
		}
	
		//alert(row.HAKAKSES+" "+row.TAMBAH+" "+row.CETAK+" "+row.UBAH+" "+row.HAPUS);
		//$(this).prop("checked",true);//CENTANG CHECKBOX
		//$('#table_hutang').DataTable().data()[indexHutang].LUNAS = 1; 
		//indexHutang++;
	});
	
	$('#dataGridLaporan').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url       : base_url+'Master/Data/User/treeGrid',
			data      : function(e){
						e.jenismenu = "Laporan";
						e.iduser 	= getIduser();
						},
			type      : "POST",
			dataSrc   : "rows",
			dataType  : 'json',
		},
        columns:[
            {data: 'KODEMODUL', visible: false},
			{data: 'HEADER'},
            {data: 'NAMAMODUL'},
            {data: 'HAKAKSES', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.HAKAKSES == 1) {
							return '<input type="checkbox" class="hakakses" checked>';
						}
						else{
							return '<input type="checkbox" class="hakakses">';
						}
						return data;
					},
					className: "dt-body-center"},
			{data: 'LIHATHARGA', className:"text-center",
					render: function ( data, type, row ) {
						if ( type === 'display' && row.LIHATHARGA == 1) {
							return '<input type="checkbox" class="lihatharga" checked>';
						}
						else{
							return '<input type="checkbox" class="lihatharga">';
						}
						return data;
					},
					className: "dt-body-center"},
        ],
    });
	
	$('#dataGridLaporan tbody').on('click', 'tr input', function () {
		var kolom = $(this).attr("class");
		var row = $('#dataGridLaporan').DataTable().row( $(this).parents('tr') ).data();
		var check = 0;
		
		
		if($(this).prop("checked"))
		{check = 1;}
		else
		{check = 0;}
		
		if(kolom == "hakakses")
		{
			row.HAKAKSES	= check; 
			//row.LIHATHARGA 	= check;
		}
		if(kolom == "lihatharga")
		{	
			row.LIHATHARGA 	= check;
		}
		
	
		//alert(row.HAKAKSES+" "+row.LIHATHARGA);
		//$(this).prop("checked",true);//CENTANG CHECKBOX
		//$('#table_hutang').DataTable().data()[indexHutang].LUNAS = 1; 
		//indexHutang++;
	});
	
	$("#lokasiAll").click(function(){
        $('#dataGridLokasi tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
            
            checkbox.prop('checked', $("#lokasiAll").prop('checked'));
            
            var row = $('#dataGridLokasi').DataTable().row( $(this).parents('tr') ).data();
    		var check = 0;
    		
    		if($("#lokasiAll").prop('checked'))
    		{check = 1;}
    		else
    		{check = 0;}
    		
    		row.PILIHLOKASI	= check; 
    		
        });
    });
	
	$('#dataGridLokasi').DataTable({
        'paging'      : false,
        'lengthChange': false,
        'searching'   : false,
        'ordering'    : false,
        'info'        : false,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url       : base_url+'Master/Data/Lokasi/getAll',
			data      : function(e){
						e.iduser 	= getIduser();
						},
			type      : "POST",
			dataSrc   : "rows",
			dataType  : 'json',
		},
        columns:[
            {data: 'IDLOKASI', visible: false},
            {data: 'KODELOKASI', className:"text-center"},
            {data: 'NAMALOKASI'},
			{data: 'PILIHLOKASI',
					render: function ( data, type, row ) {
						if ( type === 'display' && row.PILIHLOKASI == 1) {
							return '<input type="checkbox" class="pilihlokasi" checked>';
						}
						else{
							return '<input type="checkbox" class="pilihlokasi">';
						}
						return data;
					},
			className: "dt-body-center"},
        ],
    });
	
	$('#dataGridLokasi tbody').on('click', 'tr input', function () {
		var kolom = $(this).attr("class");
		var row = $('#dataGridLokasi').DataTable().row( $(this).parents('tr') ).data();
		var check = 0;
		
		
		if($(this).prop("checked"))
		{check = 1;}
		else
		{check = 0;}
		
		if(kolom == "pilihlokasi")
		{
			
			row.PILIHLOKASI	= check; 
		}

		//$(this).prop("checked",true);//CENTANG CHECKBOX
		//$('#table_hutang').DataTable().data()[indexHutang].LUNAS = 1; 
		//indexHutang++;
	});
	
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });
});

function onlyAlphabets(event) {
    // Allow control keys (backspace, tab, arrows), and letters A-Z
    const allowedKeys = [
        8,    // Backspace
        9,    // Tab
        37,   // Left Arrow
        38,   // Up Arrow
        39,   // Right Arrow
        40,   // Down Arrow
        46,   // Delete
        65,   // 'A'
        90    // 'Z'
    ];

    // Allow lowercase a-z
    if ((event.keyCode >= 65 && event.keyCode <= 90) || 
        (event.keyCode >= 97 && event.keyCode <= 122) || 
        allowedKeys.includes(event.keyCode)) {
        return true;
    }

    // Prevent default if the key is not allowed
    return false;
}

function getIduser(){
	return $("#IDUSER").val();
}

function getData(data){
	var row = $('#dataGrid'+data).DataTable().rows().data();
	var counthakakses = 0;
	var counttambah = 0;
	var countubah = 0;
	var counthapus = 0;
	var countcetak = 0;
	var countlihatharga = 0;
	
	for(var i = 0 ; i< row.length;i++)
	{
		//alert(row[i].HAKAKSES);

		if(row[i].HAKAKSES == 1)
		{
			counthakakses++;
		}
		if(row[i].TAMBAH == 1)
		{
			counttambah++;
		}
		if(row[i].UBAH == 1)
		{
			countubah++;
		}
		if(row[i].HAPUS == 1)
		{
			counthapus++;
		}
		
		if(row[i].CETAK == 1)
		{
			countcetak++;
		}
		
		if(row[i].LIHATHARGA == 1)
		{
			countlihatharga++;
		}
		

	}


	if(counthakakses == row.length)
	{
		$("#hakakses_"+data).prop("checked",true);
	}
	if(counttambah == row.length)
	{
		$("#tambah_"+data).prop("checked",true);
	}
	if(countubah == row.length)
	{
		$("#ubah_"+data).prop("checked",true);
	}
	if(counthapus == row.length)
	{
		$("#hapus_"+data).prop("checked",true);
	}
		
	
	if(countcetak == row.length)
	{
		$("#cetak_"+data).prop("checked",true);
	}
	if(countlihatharga == row.length)
	{
		$("#lihatharga"+data).prop("checked",true);
	}

}

function exportTableToExcel() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcel'), {sheet:"Sheet 1"});
   // Access the worksheet (first sheet)
  const ws = wb.Sheets[wb.SheetNames[0]];
  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 150 }, // Column A width in pixels
    { wpx: 150 }, // Column B width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 120 }, // Column A width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 100 }, // Column B width in pixels
    { wpx: 70 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'USER_'+dateNowFormatExcel()+'.xlsx');
}

function tambah(){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
			$("#mode").val('tambah');
			reset();
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Tambah');

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

function ubah(row){
	$("#dashboardAll").prop('checked',false).iCheck('update');
	$("#masterAll").prop('checked',false).iCheck('update');
	$("#transaksiAll").prop('checked',false).iCheck('update');
	$("#laporanAll").prop('checked',false).iCheck('update');
	
    get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.UBAH==1) {
				
				$("#mode").val('ubah');
				// $("#USERID").prop('readonly');
				//pindah tab & ganti judul tab
				$('.nav-tabs a[href="#tab_form"]').tab('show');
				$('.nav-tabs a[href="#tab_form"]').html('Ubah');

                $('.nav-tabs a[href="#tab_umum"]').tab('show'); 

				//load row data to form
				if(row.STATUS == 0) $("#STATUS").prop('checked',false).iCheck('update');
				else if(row.STATUS == 1) $("#STATUS").prop('checked',true).iCheck('update');
				
				if(row.STATUSLOGIN == "TIDAK") $("#LOGIN").val(0);
				else if(row.STATUSLOGIN == "YA")  $("#LOGIN").val(1);
				
				$("#USERID").prop("readonly",true);
				$("#IDUSER").val(row.IDUSER);
				$("#USERID").val(row.USERID);
				$("#USERNAME").val(row.USERNAME);
				$("#HP").val(row.HP);
				$("#EMAIL").val(row.EMAIL);
				$("#CATATAN").val(row.CATATAN);
				
				$.ajax({
					type      : 'POST',
					url       : base_url+'Master/Data/User/getPass',
					data      : {id:row.IDUSER},
					success: function(msg){
						$("#PASS").val(msg);
						$("#RE_PASS").val(msg);
					}
				});
				
				$("#dataGridDashboard").DataTable().ajax.reload();
				$("#dataGridMaster").DataTable().ajax.reload();
				$("#dataGridTransaksi").DataTable().ajax.reload();
				$("#dataGridLaporan").DataTable().ajax.reload();
				$("#dataGridLokasi").DataTable().ajax.reload();
				
				getData("Master");
				getData("Transaksi");
				getData("Laporan");
				// getDataLokasi();
					
				 
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

function simpan() {
	// var isValid = $('#form_input').form('validate');
	var mode = $("#mode").val();
	
	//DASHBOARD
	var tableDashboard = $('#dataGridDashboard').DataTable();
	var dataDashboard = JSON.stringify(tableDashboard.rows().data());
	
	//MASTER
	var tableMaster = $('#dataGridMaster').DataTable();
	var dataMaster = JSON.stringify(tableMaster.rows().data());
	
	//TRANSAKSI
	var tableTransaksi = $('#dataGridTransaksi').DataTable();
	var dataTransaksi = JSON.stringify(tableTransaksi.rows().data());
	
	//LAPORAN
	var tableLaporan = $('#dataGridLaporan').DataTable();
	var dataLaporan = JSON.stringify(tableLaporan.rows().data());

	
    //LOKASI
	var tableLokasi = $('#dataGridLokasi').DataTable();
	var dataLokasi = JSON.stringify(tableLokasi.rows().data());
	
	$.ajax({
		type      : 'POST',
		url       : base_url+'Master/Data/User/simpan',
		data      : $('#form_input :input').serialize(),
		dataType  : 'json',
		success: function(msg){
			if (msg.success) {
			        var iduser = msg.iduser;
			        $.ajax({
                		type      : 'POST',
                		url       : base_url+'Master/Data/User/simpanDashboard',
                		data      : {"dataDashboard" : dataDashboard,"iduser" : iduser},
                		dataType  : 'json',
                		success: function(msg){
                			if (msg.success) {
            			    	$.ajax({
                            		type      : 'POST',
                            		url       : base_url+'Master/Data/User/simpanMaster',
                            		data      : {"dataMaster" : dataMaster,"iduser" : iduser},
                            		dataType  : 'json',
                            		success: function(msg){
                            			if (msg.success) {
                            				
                            				$.ajax({
                                        		type      : 'POST',
                                        		url       : base_url+'Master/Data/User/simpanTransaksi',
                                        		data      : {"dataTransaksi" : dataTransaksi,"iduser" : iduser},
                                        		dataType  : 'json',
                                        		success: function(msg){
                                        			if (msg.success) {
                                        				
                                        					$.ajax({
                                                        		type      : 'POST',
                                                        		url       : base_url+'Master/Data/User/simpanLaporan',
                                                        		data      : {"dataLaporan" : dataLaporan,"iduser" : iduser},
                                                        		dataType  : 'json',
                                                        		success: function(msg){
                                                        			if (msg.success) {
                                                        				
                                                        			    $.ajax({
                                                                    		type      : 'POST',
                                                                    		url       : base_url+'Master/Data/User/simpanLokasi',
                                                                    		data      : {"dataLokasi" : dataLokasi,"iduser" : iduser},
                                                                    		dataType  : 'json',
                                                                    		success: function(msg){
                                                                    			if (msg.success) {
                                                                    				
                                                                    				Swal.fire({
                                                                    					title            : 'Simpan Data Sukses',
                                                                    					type             : 'success',
                                                                    					showConfirmButton: false,
                                                                    					timer            : 1500
                                                                    				});
                                                                    				reset();
                                                                    				$("#dataGrid").DataTable().ajax.reload();
                                                                    				$("#dataGridDashboard").DataTable().ajax.reload();
                                                                    				$("#dataGridMaster").DataTable().ajax.reload();
                                                                    				$("#dataGridTransaksi").DataTable().ajax.reload();
                                                                    				$("#dataGridLaporan").DataTable().ajax.reload();
                                                                    				$("#dataGridLokasi").DataTable().ajax.reload();
                                                                    				
                                                                    				$('.nav-tabs a[href="#tab_umum"]').tab('show');
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

function hapus(row){
	
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.HAPUS==1) {
		    
            if (row) {
		     Swal.fire({
            		title: 'Anda Yakin Akan Menghapus User '+row.USERNAME+' ?',
            		showCancelButton: true,
            		confirmButtonText: 'Yakin',
            		cancelButtonText: 'Tidak',
            		}).then((result) => {
            		/* Read more about isConfirmed, isDenied below */
            			if (result.value) {
                           $("#mode").val('hapus');
                				$.ajax({
                					type    : 'POST',
                					dataType: 'json',
                					url     : base_url+"Master/Data/User/hapus",
                					data    : "id="+row.IDUSER + "&kode="+row.KODEUSER,
                					cache   : false,
                					success : function(msg){
                						if (msg.success) {
                							Swal.fire({
                								title            : 'User dengan nama '+row.USERNAME+' telah dihapus',
                								type             : 'success',
                								showConfirmButton: false,
                								timer            : 1500
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
            			} 
                     });
        	}
			
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

function reset(){
	//clear form input
	$('.nav-tabs a[href="#tab_form"]').html('Tambah');
	$("#USERID").prop("readonly",false);
	
	$("#dashboardAll").prop('checked',false).iCheck('update');
	$("#masterAll").prop('checked',false).iCheck('update');
	$("#transaksiAll").prop('checked',false).iCheck('update');
	$("#laporanAll").prop('checked',false).iCheck('update');
	
	$("#STATUS").prop('checked',true).iCheck('update');
	$("#IDUSER").val("");
	$("#PASS").val("");
	$("#RE_PASS").val("");
	$("#USERID").val("");
	$("#USERNAME").val("");
	$("#HP").val("");
	$("#EMAIL").val("");
	$("#CATATAN").val("");
	
    $("#dataGridDashboard").DataTable().ajax.reload();
	$("#dataGridMaster").DataTable().ajax.reload();
	$("#dataGridTransaksi").DataTable().ajax.reload();
	$("#dataGridLaporan").DataTable().ajax.reload();
	$("#dataGridLokasi").DataTable().ajax.reload();
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
