
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row">
        <div class="col-md-10">
            <div style="font-size:24px !important;">Master Harga</div>
        </div>
        <div class="col-md-2">
        <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt;"  onclick="exportTableToExcel()">Excel</button>
        </div>
    </div>
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
            <div class="box-header" style="margin-top:-15px;">
                <h3 style="font-weight:bold;">&nbsp;Atur Harga&nbsp;&nbsp;&nbsp; 
                <span style="float:right;">&nbsp;&nbsp;&nbsp;<button onclick='simpanHarga()' class='btn btn-success'>Simpan</button></span>
                
                <span style="font-size:16px; font-weight:normal; font-style:italic;">
                <br>&nbsp;Harga Jual Tampil = base price pada marketplace
                <br>&nbsp;Harga Coret / Konsinyasi = harga acuan untuk harga promo / diskon pada marketplace.</span>
                </h3>
                <br>
                 <div class="row col-md-12" style="padding:0px;">
                      <div class="col-md-4" style="padding-top:6px;">
                         <span style=" margin-left:4px; font-weight:bold;">Semua Customer &nbsp;&nbsp;</span>
                         <span style=""> <input type="checkbox" class="flat-blue" id="CUSTOMER" name="CUSTOMER" value="1" checked></span>
                         <span style=" margin-left:10px; font-weight:bold;">&nbsp;&nbsp;&nbsp;&nbsp;Tampilkan Varian &nbsp;&nbsp;</span>
                         <span style=""> <input type="checkbox" class="flat-blue" id="VARIAN" name="VARIAN" value="1"></span>
                     </div>
                     <div class="col-md-2">
                        <label>Harga Jual Tampil</label>
                        <input type="text" class="form-control" style="width:100px;" class="ubahHargaAll" id="hargaAllJual" value="" placeholder="0" min="0" max="100" onblur="limitHargaAll(event,'JUAL')" onkeyup="hargaAll(event,'JUAL')" mouseup="hargaAll(event,'JUAL')" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>
                     </div>
                     <div class="col-md-2">
                        <label>Harga Coret</label>
                        <input type="text" class="form-control" style="width:100px;" class="ubahHargaAll" id="hargaAllCoret" value="" placeholder="0" min="0" max="100" onblur="limitHargaAll(event,'CORET')" onkeyup="hargaAll(event,'CORET')" mouseup="hargaAll(event,'CORET')" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>
                     </div>
                     <div class="col-md-2">
                        <label>Harga Konsinyasi</label>
                        <input type="text" class="form-control" style="width:100px;" class="ubahHargaAll" id="hargaAllKonsinyasi" value="" placeholder="0" min="0" max="100" onblur="limitHargaAll(event,'KONSINYASI')" onkeyup="hargaAll(event,'KONSINYASI')" mouseup="hargaAll(event,'KONSINYASI')" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>
                     </div>
                      <div class="col-md-2">
                        <label>Harga Beli</label>
                        <input type="text" class="form-control" style="width:100px;" class="ubahHargaAll" id="hargaAllBeli" value="" placeholder="0" min="0" max="100" onblur="limitHargaAll(event,'BELI')" onkeyup="hargaAll(event,'BELI')" mouseup="hargaAll(event,'BELI')" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>
                     </div>
                 </div>
                
                
      
                 <br>
                 <div id="labelKeterangan" style="color:red;"></div>
                 <br>
                 <button type="button" id="btn_customer" class="btn btn-primary" data-toggle="modal" data-target="#modal-customer"  >Pilih Customer</button>
                 <input type="hidden" id="IDCUSTOMER" value ="">
                 <span>
                     <label id="NAMACUSTOMER" style="font-size:14pt; "></label>
                 </span>
             </div>
        <!-- Custom Tabs -->
            <div class="box-body">
                <table id="dataGrid" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                    <!-- class="table-hover"> -->
                    <thead>
                    <tr>
                        <th width="35px"></th>
                        <th width="35px"></th>      
                        <th>Nama</th>   
                        <th width="40px">Harga Jual Tampil</th>         
                        <th width="40px">Harga Coret</th>
                        <th width="40px">Harga Konsinyasi</th>
                        <th width="40px">Harga Beli</th>   
                    </tr>
                    </thead>
                </table>
            </div>
            <div id="tableExcel" style="display:none;" ></div>
        <!-- nav-tabs-custom -->
        </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row (main row) -->
  <!--MODAL CUSTOMER KONSINYASI-->
    <div class="modal fade" id="modal-customer" >
    	<div class="modal-dialog" style="width:70%;">
    	<div class="modal-content">
    		<div class="modal-body">
    			<table id="table_customer" class="table table-bordered table-striped table-hover display nowrap">
    				<thead>
    					<tr>
    						<th></th>
    						<th width="50px">Kode</th>
    						<th>Nama</th>
    						<th>Alamat</th>
    						<th>Telp</th>
    						<th>Konsinyasi</th>
    					</tr>
    				</thead>
    			</table>
    		</div>
    	</div>
    	</div>
    </div>  
</section>
<!-- /.content -->
<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.7/dist/inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script>
function currency(amount){
   return Number(amount).toLocaleString()
}
var indexRow;
$(document).ready(function() {
    
    $("#btn_customer").hide();
    
    $("#CUSTOMER").prop('checked',true).iCheck('update').on('ifChanged', function(event) {
        if ($(this).prop('checked')) {
           $("#btn_customer").hide();
           $("#IDCUSTOMER").val("");
           $("#NAMACUSTOMER").html("");
           
        } else {
           $("#btn_customer").show();
        }
        checkCondition();
    });
    
    $("#VARIAN").prop('checked',false).iCheck('update').on('ifChanged', function(event) {
        checkCondition();
    });
    
    $('#dataGrid').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
        "scrollX"     : true,
        ajax          : {
            url    : base_url + 'Master/Data/Harga/dataGridHeader',
            dataSrc: "rows",
            dataFilter: function (data) {
                // Refresh the new table whenever DataTable reloads
                var allData = JSON.parse(data).rows; // Get all rows' data

                // Create the HTML structure for the new table
                var newTable = $('<table id="newTable" class="table table-bordered">');
                if($("#VARIAN").prop("checked"))
                {
                    var thead = $('<thead>').append('<tr><th>Customer</th><th>Nama Varian</th><th>Harga Jual Tampil</th><th>Harga Coret</th><th>Harga Konsinyasi</th><th>Harga Beli</th></tr>');
                }
                else
                {
                    var thead = $('<thead>').append('<tr><th>Customer</th><th>Nama Produk</th><th>Harga Jual Tampil</th><th>Harga Coret</th><th>Harga Konsinyasi</th><th>Harga Beli</th></tr>');
                }
                var tbody = $('<tbody>');
                 // Loop through the DataTable data and create rows for the new table
         
                allData.forEach(function (row, index) {
                    var tr = $('<tr>');
                    tr.append('<td>' + ($("#NAMACUSTOMER").text().split("Customer : ")[1]??"All Customer") + '</td>');
                    tr.append('<td>' + (row.NAMABARANG) + '</td>');
                    tr.append('<td>' + (row.HARGAJUAL == null?"":row.HARGAJUAL) + '</td>');
                    tr.append('<td>' + (row.HARGACORET == null?"":row.HARGACORET) + '</td>');
                    tr.append('<td>' + (row.HARGAKONSINYASI == null?"":row.HARGAKONSINYASI) + '</td>');
                    tr.append('<td>' + (row.HARGABELI == null?"":row.HARGABELI) + '</td>');
            
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
        columns: [
            { data: 'IDCUSTOMER', visible: false },
            { data: 'IDBARANG', visible: false },
            { data: 'NAMABARANG' },
            { data: 'HARGAJUAL', className: "text-right", },
            { data: 'HARGACORET', className: "text-right", },
            { data: 'HARGAKONSINYASI', className: "text-right", },
            { data: 'HARGABELI', className: "text-right", },
        ],
        columnDefs: [
            {
                "targets": -4,
                "render": function (data) {
                    return '<input type="text" style="text-align:right; width:100px;" class="form-control HARGAJUAL '+data+'" value="' + data + '" placeholder="0" min="0" onblur="limitHarga(event)" onkeyup="harga(event)" mouseup="harga(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false) || (95 < event.keyCode && event.keyCode < 106) || (event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode > 34 && event.keyCode < 40) || (event.keyCode == 46) )"></input>';
                },
            },
            {
                "targets": -3,
                "render": function (data) {
                    return '<input type="text" style="text-align:right; width:100px;" class="form-control HARGACORET '+data+'" value="' + data + '" placeholder="0" min="0" onblur="limitHarga(event)" onkeyup="harga(event)" mouseup="harga(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false) || (95 < event.keyCode && event.keyCode < 106) || (event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode > 34 && event.keyCode < 40) || (event.keyCode == 46) )"></input>';
                },
            },
            {
                "targets": -2,
                "render": function (data) {
                    return '<input type="text" style="text-align:right; width:100px;" class="form-control HARGAKONSINYASI '+data+'" value="' + data + '" placeholder="0" min="0" onblur="limitHarga(event)" onkeyup="harga(event)" mouseup="harga(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false) || (95 < event.keyCode && event.keyCode < 106) || (event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode > 34 && event.keyCode < 40) || (event.keyCode == 46) )"></input>';
                },
            },
            {
                "targets": -1,
                "render": function (data) {
                    return '<input type="text" style="text-align:right; width:100px;" class="form-control HARGABELI '+data+'" value="' + data + '" placeholder="0" min="0" onblur="limitHarga(event)" onkeyup="harga(event)" mouseup="harga(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47 < event.keyCode && event.keyCode < 58 && event.shiftKey == false) || (95 < event.keyCode && event.keyCode < 106) || (event.keyCode == 8) || (event.keyCode == 9) || (event.keyCode > 34 && event.keyCode < 40) || (event.keyCode == 46) )"></input>';
                },
            },
        ],
        drawCallback: function(settings) {
            // Apply Inputmask to all input fields of class HARGAKONSINYASI
            $(".HARGABELI").each(function() {
                $(this).number(true, 0);
            });
            $(".HARGAJUAL").each(function() {
                $(this).number(true, 0);
            });
            $(".HARGACORET").each(function() {
                $(this).number(true, 0);
            });
            $(".HARGAKONSINYASI").each(function() {
                $(this).number(true, 0);
            });
        }
    });
    
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
            { data: 'KODE' },
            { data: 'NAMA' },
			{ data: 'ALAMAT'},
			{ data: 'TELP' },
			{ data: 'KONSINYASI', className: "text-center",}
        ],
        columnDefs: [
            {
                "targets": -1,
                "render": function (data) {
                    if(data == "1")
                    {
                        return 'YA';
                    }
                    else
                    {
                        return 'TIDAK';
                    }
                },
            },
        ],
		
    });
    
    //BUAT NAMBAH BARANG BIASA
	$('#table_customer tbody').on('click', 'tr', function () {
		$(".ubahHargaAll").val("");
		var row = $('#table_customer').DataTable().row( this ).data();
		$("#modal-customer").modal('hide');	
		$("#NAMACUSTOMER").html("&nbsp;Customer : "+row.NAMA);
		$("#IDCUSTOMER").val(row.ID);
		
		checkCondition();
		
	});
	
	$("#hargaAllJual").number(true, 0);
	$("#hargaAllBeli").number(true, 0);
    $("#hargaAllCoret").number(true, 0);
    $("#hargaAllKonsinyasi").number(true, 0);
});

function checkCondition(){
    var url = "";
    $("#hargaAllJual").val("");
	$("#hargaAllBeli").val("");
    $("#hargaAllCoret").val("");
    $("#hargaAllKonsinyasi").val("");
    $("#labelKeterangan").html("");
    //CUSTOMER TERPILIH & VARIAN TAMPILKAN SEMUA
    if(!$("#CUSTOMER").prop("checked") && $("#VARIAN").prop("checked") && $("#IDCUSTOMER").val() != "")
    {
		url = 'Master/Data/Harga/dataGrid/'+$("#IDCUSTOMER").val();
    }
    else if(!$("#CUSTOMER").prop("checked") && !$("#VARIAN").prop("checked") && $("#IDCUSTOMER").val() != "")
    {
		url = 'Master/Data/Harga/dataGridHeader/'+$("#IDCUSTOMER").val();
		$("#labelKeterangan").html("*Harga pada tabel, adalah harga tertinggi dari keseluruhan varian pada Produk yang tertera");
    }
    else if($("#CUSTOMER").prop("checked")  && !$("#VARIAN").prop("checked") )
    {
        url = 'Master/Data/Harga/dataGridHeader';
        $("#labelKeterangan").html("*Harga pada tabel, adalah harga tertinggi dari keseluruhan varian pada Produk yang tertera dari Semua Customer");
    }
    else if($("#CUSTOMER").prop("checked") && $("#VARIAN").prop("checked"))
    {
		url = 'Master/Data/Harga/dataGrid';
		$("#labelKeterangan").html("*Harga pada tabel, adalah harga tertinggi dari varian yang tertera dari Semua Customer");
    }
    else
    {
       	url = 'Master/Data/Harga/dataGridNone';
    }
    
    $("#dataGrid").DataTable().ajax.url(base_url+url);
    
	$("#dataGrid").DataTable().ajax.reload();
}
    
function exportTableToExcel() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcel'), {sheet:"Sheet 1"});
   // Access the worksheet (first sheet)
  const ws = wb.Sheets[wb.SheetNames[0]];

  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 200 }, // Column A width in pixels
    { wpx: 400 }, // Column B width in pixels
    { wpx: 70 },  // Column C width in pixels
    { wpx: 70 },  // Column C width in pixels
    { wpx: 70 }, // Column A width in pixels
    { wpx: 70 }, // Column A width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'HARGA_'+dateNowFormatExcel()+'.xlsx');
}

function hargaAll(data,jenis) {
    var error = '';
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = parseInt(inputElement.value.replaceAll(",",""));
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
}

function limitHargaAll(data,jenis)
{
    var error = '';
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = parseInt(inputElement.value.replaceAll(",",""));
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    
    if(jenis == "BELI")
    {
        // Now, let's update the inputs programmatically
        var table = $('#dataGrid').DataTable();
        table.rows().every(function () {
            var rowData = this.data(); // Get the data for each row
            
            // Find the corresponding input field for the current row
            var inputField = $(this.node()).find('input.HARGABELI');
            const beforePrice = inputField.attr('class').toString().split(" ")[2];
            
            if(parseFloat(beforePrice) / parseFloat(inputValue) > 5 && parseFloat(beforePrice) > parseFloat(inputValue))
            {
                error = "Beberapa harga produk tidak berubah,<br>karena Harga minimal adalah 5x lebih kecil dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else if(parseFloat(inputValue) / parseFloat(beforePrice) > 5 && parseFloat(inputValue) > parseFloat(beforePrice))
            {
                error = "Beberapa harga produk tidak berubah,<br>Harga maksimal adalah 5x lebih besar dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else
            {
                inputField.val(currency(inputValue));
            }
        });
    }
    else if(jenis == "JUAL")
    {
        // Now, let's update the inputs programmatically
        var table = $('#dataGrid').DataTable();
        table.rows().every(function () {
            var rowData = this.data(); // Get the data for each row
            
            // Find the corresponding input field for the current row
            var inputField = $(this.node()).find('input.HARGAJUAL');
            const beforePrice = inputField.attr('class').toString().split(" ")[2];
            
            if(parseFloat(beforePrice) / parseFloat(inputValue) > 5 && parseFloat(beforePrice) > parseFloat(inputValue))
            {
                error = "Beberapa harga produk tidak berubah,<br>Harga minimal adalah 5x lebih kecil dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else if(parseFloat(inputValue) / parseFloat(beforePrice) > 5 && parseFloat(inputValue) > parseFloat(beforePrice))
            {
                error = "Beberapa harga produk tidak berubah,<br>Harga maksimal adalah 5x lebih besar dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else
            {
                inputField.val(currency(inputValue));
            }
        });
    }
    else if(jenis == "CORET")
    {
        // Now, let's update the inputs programmatically
        var table = $('#dataGrid').DataTable();
        table.rows().every(function () {
            var rowData = this.data(); // Get the data for each row
            
            // Find the corresponding input field for the current row
            var inputField = $(this.node()).find('input.HARGACORET');
            const beforePrice = inputField.attr('class').toString().split(" ")[2];
            
            if(parseFloat(beforePrice) / parseFloat(inputValue) > 5 && parseFloat(beforePrice) > parseFloat(inputValue))
            {
                error = "Beberapa harga produk tidak berubah,<br>Harga minimal adalah 5x lebih kecil dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else if(parseFloat(inputValue) / parseFloat(beforePrice) > 5 && parseFloat(inputValue) > parseFloat(beforePrice))
            {
                error = "Beberapa harga produk tidak berubah,<br>Harga maksimal adalah 5x lebih besar dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else
            {
                inputField.val(currency(inputValue));
            }
        });
    }
    else if(jenis == "KONSINYASI")
    {
        // Now, let's update the inputs programmatically
        var table = $('#dataGrid').DataTable();
        table.rows().every(function () {
            var rowData = this.data(); // Get the data for each row
            
            // Find the corresponding input field for the current row
            var inputField = $(this.node()).find('input.HARGAKONSINYASI');
            const beforePrice = inputField.attr('class').toString().split(" ")[2];
            
            if(parseFloat(beforePrice) / parseFloat(inputValue) > 5 && parseFloat(beforePrice) > parseFloat(inputValue))
            {
                error = "Beberapa harga produk tidak berubah,<br>Harga minimal adalah 5x lebih kecil dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else if(parseFloat(inputValue) / parseFloat(beforePrice) > 5 && parseFloat(inputValue) > parseFloat(beforePrice))
            {
                error = "Beberapa harga produk tidak berubah,<br>Harga maksimal adalah 5x lebih besar dari harga saat ini";
                inputField.val(currency(beforePrice));
            }
            else
            {
                inputField.val(currency(inputValue));
            }
        });
    }
    
    if(error != "")
    {
        Swal.fire({
            title            : error,
            type             : 'warning',
            showConfirmButton: false,
            timer            : 1500
        });
    }
}

function harga(data) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue =  parseInt(inputElement.value.replaceAll(",",""));
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
}

function limitHarga(data){
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue =  parseInt(inputElement.value.replaceAll(",",""));
    const beforePrice = classList.toString().split(" ")[2];
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    
    if(parseFloat(beforePrice) / parseFloat(inputValue) > 5 && parseFloat(beforePrice) > parseFloat(inputValue))
    {
        Swal.fire({
            title            : "Harga minimal adalah 5x lebih kecil dari harga saat ini",
            type             : 'warning',
            showConfirmButton: false,
            timer            : 1500
        });
        inputElement.value = currency(beforePrice);
    }
    else if(parseFloat(inputValue) / parseFloat(beforePrice) > 5 && parseFloat(inputValue) > parseFloat(beforePrice))
    {
         Swal.fire({
            title            : "Harga maksimal adalah 5x lebih besar dari harga saat ini",
            type             : 'warning',
            showConfirmButton: false,
            timer            : 1500
        });
        inputElement.value = currency(beforePrice);
    }
}

function simpanHarga() {
    
    if($("#IDCUSTOMER").val() == "" && !$("#CUSTOMER").prop("checked"))
    {
        Swal.fire({
            title            : "Pilih Customer Dahulu",
            type             : 'warning',
            showConfirmButton: false,
            timer            : 1500
        });
    }
    else
    {
        var table = $('#dataGrid').DataTable();
    
        var allData = [];

         // Loop through each row of the DataTable
         table.rows().every(function() {
             var row = this.node();
             var rowData = {}; 
             var dataRow = this.data();
             rowData.idcustomer = dataRow.IDCUSTOMER;
             rowData.idbarang = dataRow.IDBARANG;
             var hargabeli = parseInt(dataRow.HARGABELI);
             var hargajual = parseInt(dataRow.HARGAJUAL);
             var hargacoret = parseInt(dataRow.HARGACORET);
             var hargakonsinyasi = parseInt(dataRow.HARGAKONSINYASI);
             // Get number input value
             var numberInputBeli = $(row).find('input.HARGABELI');
             rowData.hargabelinew = numberInputBeli.val(); // Get the value of the number input
             
             var numberInputJual = $(row).find('input.HARGAJUAL');
             rowData.hargajualnew = numberInputJual.val(); // Get the value of the number input
             
             var numberInputCoret = $(row).find('input.HARGACORET');
             rowData.harganew = numberInputCoret.val(); // Get the value of the number input
             
             var numberInputKonsinyasi = $(row).find('input.HARGAKONSINYASI');
             rowData.hargakonsinyasinew = numberInputKonsinyasi.val(); // Get the value of the number input
    
             // Push the row data object into the allData array
             if(parseInt(hargacoret) != parseInt(rowData.harganew) || parseInt(hargakonsinyasi) != parseInt(rowData.hargakonsinyasinew) || parseInt(hargajual) != parseInt(rowData.hargajualnew) || parseInt(hargabeli) != parseInt(rowData.hargabelinew))
             {
                allData.push(rowData);
             }
         });

     // Log the collected data or process it further
	// var isValid = $('#form_input').form('validate');
	if (1) {
		mode = $('[name=mode]').val();
        $.ajax({
            type      : 'POST',
            url       : base_url+'Master/Data/Harga/simpanHarga',
            data      : {
                            'allcustomer' : $("#CUSTOMER").prop("checked").toString() , 
                            'varian' : $("#VARIAN").prop("checked").toString(),
                            'data_detail' : JSON.stringify(allData),
            },
            dataType  : 'json',
            beforeSend: function (){
                //$.messager.progress();
            },
            success: function(msg){
                if (msg.success) {
                    Swal.fire({
                        title            : 'Simpan Data Sukses',
                        type             : 'success',
                        showConfirmButton: false,
                        timer            : 1500
                    });
                    $("#dataGrid").DataTable().ajax.reload();
                   
					var doneStok = [true,true,true];     
					if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'YES'?>')
                    {
                       doneStok[0] = false;
                    }
                    if('<?=$_SESSION[NAMAPROGRAM]['TIKTOK_ACTIVE'] == 'YES'?>')
                    {
                         doneStok[1] = false;
                    }
                    if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'YES'?>')
                    {
                         doneStok[2] = false;
                    }
                    
                    if(doneStok.length > 0)
                    {
                        var loadingStok = false
                        for(var d = 0 ; d < doneStok.length;d++)
                        {
                          if(!doneStok[d])
                          {
                              loadingStok = true;
                          }
                        }
                        if(loadingStok)
                        {
                         loadingMaster();
                        }
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'YES'?>')
                    {
                        $.ajax({
                            type      : 'POST',
                            url       : base_url+'Shopee/setHargaBarang',
                            data      : {
                                            'allcustomer' : $("#CUSTOMER").prop("checked").toString() , 
                                            'varian' : $("#VARIAN").prop("checked").toString(),
                                            'data_detail' : JSON.stringify(allData),
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                               doneStok[0] = true;
                               cekDone = true;
                               for(var d = 0 ; d < doneStok.length;d++)
                               {
                                   if(!doneStok[d])
                                   {
                                       cekDone = false
                                   }
                               }
                               
                               if(cekDone)
                               {
                                   Swal.close();    
                               }
                               
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
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['TIKTOK_ACTIVE'] == 'YES'?>')
                    {
                        $.ajax({
                            type      : 'POST',
                            url       : base_url+'Tiktok/setHargaBarang',
                            data      : {
                                            'allcustomer' : $("#CUSTOMER").prop("checked").toString() , 
                                            'varian' : $("#VARIAN").prop("checked").toString(),
                                            'data_detail' : JSON.stringify(allData),
                            },
                            dataType  : 'json',
                            beforeSend: function (){
                                //$.messager.progress();
                            },
                            success: function(msg){
                               doneStok[1] = true;
                               cekDone = true;
                               for(var d = 0 ; d < doneStok.length;d++)
                               {
                                   if(!doneStok[d])
                                   {
                                       cekDone = false
                                   }
                               }
                               
                               if(cekDone)
                               {
                                   Swal.close();    
                               }
                               
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
                    }
                    
                    if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'YES'?>')
                    {
                      $.ajax({
                        type      : 'POST',
                        url       : base_url+'Lazada/setHargaBarang',
                        data      : {
                                        'allcustomer' : $("#CUSTOMER").prop("checked").toString() , 
                                        'varian' : $("#VARIAN").prop("checked").toString(),
                                        'data_detail' : JSON.stringify(allData),
                        },
                        dataType  : 'json',
                        beforeSend: function (){
                            //$.messager.progress();
                        },
                        success: function(msg){
                           doneStok[2] = true;
                           cekDone = true;
                           for(var d = 0 ; d < doneStok.length;d++)
                           {
                               if(!doneStok[d])
                               {
                                   cekDone = false
                               }
                           }
                           
                           if(cekDone)
                           {
                               Swal.close();    
                           }
                           
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
                    }
                } else {
                    Swal.fire({
                        title            : msg.errorMsg,
                        type             : 'error',
                        showConfirmButton: false,
                        timer            : 1500
                    });
                }
            },
            
         });
	    }
	}
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

function loadingMaster(){
    Swal.fire({
      title: '',
      html: '<div style="font-size:20pt; font-weight:600;">Menghubungkan Master Barang dengan Marketplace... <div>',                // no text or HTML content
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      }
    });
}
</script>
