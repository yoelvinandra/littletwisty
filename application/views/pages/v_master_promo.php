
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row">
        <div class="col-md-10">
            <div style="font-size:24px !important;">Master Potongan Member</div>
        </div>
        <div class="col-md-2">
        <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt;"  onclick="exportTableToExcelMember()">Excel</button>
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
            <div class="box-header">
                <h3 style="font-weight:bold;">&nbsp; Potongan Membership 
                <span style="float:right;">&nbsp;&nbsp;&nbsp;<button onclick='simpanPromo()' class='btn btn-success'>Simpan</button></span>
                <span style="float:right;"><input type="number" class="form-control" id="diskonmemberall" value="'+data+'" placeholder="0" min="0" max="100" onkeyup="checkValidAll(event,'PROMO')" mouseup="checkValidAll(event,'PROMO')" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input></span>
                <span style="float:right; margin-top:6px;">Ubah potongan, produk yang dicentang &nbsp;&nbsp;</span>
                </h3>
            </div>
        <!-- Custom Tabs -->
            <div class="box-body">
                <table id="dataGridPromo" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                    <!-- class="table-hover"> -->
                    <thead>
                    <tr>
                        <th width="35px"></th>
                        <th width="35px"></th>
                        <th width="50px">Kode</th>
                        <th>Nama</th>         
                        <th width="50px">Potongan (%)</th>  
                        <th width="25px"><input id="checkAll" type="checkbox"></input></th>                                
                    </tr>
                    </thead>
                </table>
            </div>
            <div id="tableExcelMember" style="display:none;" ></div>
        <!-- nav-tabs-custom -->
        </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row (main row) -->
  <!--MODAL CUSTOMER KONSINYASI-->
    <div class="modal fade" id="modal-customer-konsinyasi" >
    	<div class="modal-dialog" style="width:70%;">
    	<div class="modal-content">
    		<div class="modal-body">
    			<table id="table_customer_konsinyasi" class="table table-bordered table-striped table-hover display nowrap">
    				<thead>
    					<tr>
    						<th></th>
    						<th width="50px">Kode</th>
    						<th>Nama</th>
    						<th>Alamat</th>
    						<th>Telp</th>
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
var indexRow;
$(document).ready(function() {
    
    $('#dataGridPromo').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : false,
		"scrollX"	  : true,
		ajax		  : {
			url    : base_url+'Master/Data/Promo/dataGridPromo',
			dataSrc: "rows",
			dataFilter: function (data) {
                // Refresh the new table whenever DataTable reloads
                var allData = JSON.parse(data).rows; // Get all rows' data

                // Create the HTML structure for the new table
                var newTable = $('<table id="newTable" class="table table-bordered">');
                var thead = $('<thead>').append('<tr><th>Kode Customer</th><th>Nama Customer</th><th>Potongan %</th></tr>');
                var tbody = $('<tbody>');
                 // Loop through the DataTable data and create rows for the new table
         
                allData.forEach(function (row, index) {
                    var tr = $('<tr>');
                    tr.append('<td>' + (row.KODECUSTOMER) + '</td>');
                    tr.append('<td>' + (row.NAMACUSTOMER) + '</td>');
                    tr.append('<td>' + (row.DISKONMEMBER == null?"":row.DISKONMEMBER) + '</td>');;
            
                    // Append the row to the tbody
                    tbody.append(tr);
                });
            
                // Append the thead and tbody to the new table
                newTable.append(thead).append(tbody);
                // Append the new table to the DOM (you can specify where you want the new table to appear)
                $('#tableExcelMember').html(newTable); 
                
                return data;
            }
		},
        columns:[
            {data: 'IDCUSTOMER', visible:false},
            {data: 'DISKONMEMBER', visible:false},
            {data: 'KODECUSTOMER'},
            {data: 'NAMACUSTOMER'}, 
            {data: 'DISKONMEMBER', className:"text-center"},  
            {data: '', className:"text-center"},       
        ],
		columnDefs: [
		    {
                "targets": -1,
                "render" :function (data) 
                {
                    return '<input type="checkbox" class="flat-blue"></input>';
                },	
			},
			{
                "targets": -2,
                "render" :function (data) 
                {
                    
                    return '<input type="number" class="form-control diskonmember" value="'+data+'" placeholder="0" min="0" max="100" onkeyup="checkValidPromo(event)" mouseup="checkValidPromo(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )"></input>';
                },	
			},
		]
    });
    
    $("#checkAll").click(function(){
        $('#dataGridPromo tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
            
            checkbox.prop('checked', $("#checkAll").prop('checked'));
    
            var numberInput = checkbox.closest('tr').find('input[type="number"]');
        
            if (checkbox.prop('checked')) {
                numberInput.val($("#diskonmemberall").val());  
            }
        });
    });
    
    $('#dataGridPromo tbody').on('click', 'input[type="checkbox"]', function () {
	    var checkbox = $(this);
    
        var numberInput = checkbox.closest('tr').find('input[type="number"]');

        if (checkbox.prop('checked')) {
            numberInput.val($("#diskonmemberall").val());  
        }

	});

});

function exportTableToExcelMember() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcelMember'), {sheet:"Sheet 1"});
  // Access the worksheet (first sheet)
  const ws = wb.Sheets[wb.SheetNames[0]];

  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 70 }, // Column A width in pixels
    { wpx: 200 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'PROMO_MEMBER_'+dateNowFormatExcel()+'.xlsx');
}

function checkValidAll(data,jenis) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    if(inputValue > 100)
    {
        inputElement.value = 100;
    }
    if(jenis == "PROMO")
    {
        $('#dataGridPromo tbody input[type="checkbox"]').each(function() {
            
            var checkbox = $(this);
        
            var numberInput = checkbox.closest('tr').find('input[type="number"]');
        
            if (checkbox.prop('checked')) {
                numberInput.val(inputElement.value);  
            }
        });
    }
    else
    {
       
    }
}

function checkValidPromo(data) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    if(inputValue > 100)
    {
        inputElement.value = 100;
    }
}

function checkValidKonsinyasi(data) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
}

function simpanPromo() {
    var table = $('#dataGridPromo').DataTable();
    
     var allData = [];

     // Loop through each row of the DataTable
     table.rows().every(function() {
         var row = this.node();
         var rowData = {}; 
         var dataRow = this.data();
         rowData.id = dataRow.IDCUSTOMER;
         rowData.diskonmemberlama = dataRow.DISKONMEMBER;
         // Get number input value
         var numberInput = $(row).find('input[type="number"]');
         rowData.diskonmemberbaru = numberInput.val(); // Get the value of the number input

         // Push the row data object into the allData array
         if(rowData.diskonmemberlama != rowData.diskonmemberbaru)
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
            url       : base_url+'Master/Data/Promo/simpanPromo',
            data      : {'data' : JSON.stringify(allData)},
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
                    $("#dataGridPromo").DataTable().ajax.reload();
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
