<style>
</style>
<!-- Main content -->
<section class="content">
  
  <!-- Main row -->
  <div class="row">
     <div class="col-md-12"  style="border:0px; padding:0px 15px 0px 15px;">
        <div class="box" style="border:0px; padding:0px; margin:0px;">
            <div class="box-header form-inline" style="padding:0px;">
                <div>&nbsp;<span  style="font-weight:bold; font-size:18pt;">Atur Promo Lazada</span>&nbsp;&nbsp;&nbsp; 
                    <span style="float:right;">&nbsp;&nbsp;&nbsp;<button onclick='simpanPromoLazada()' class='btn btn-success'>Simpan</button></span>
                    <span  style="float:right;margin-right:20px; width:270px;">
                        <label style="width:130px;">Tgl Promo Berakhir</label>
                        <input type="text" class="form-control" style="width:120px; text-align:center;" id="TGLPROMOBERAKHIRALL" placeholder="YYYY-MM-DD"> 
                     </span>
                    <span  style="float:right; margin-right:20px; width:250px;">
                        <label style="width:110px;">Tgl Promo Mulai</label>
                        <input type="text" class="form-control" style="width:120px; text-align:center;" id="TGLPROMOMULAIALL" placeholder="YYYY-MM-DD">
                     </span>
                </div>
                <span style="font-size:16px; font-weight:normal; font-style:italic;">
                     &nbsp;Jika ingin mengatur diskon secara permanent, kosongi bagian tanggal promo mulai dan tanggal promo berakhir<br>
                     &nbsp;Untuk Harga dan Harga Promo, diubah di master harga.
                </span>
             </div>
        <!-- Custom Tabs -->
            <div class="box-body" style="border:0px;">
                <table id="dataGridLazada" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                    <!-- class="table-hover"> -->
                    <thead>
                    <tr>
                        <th width="35px"></th>
                        <th width="35px"></th>      
                        <th width="35px"></th>      
                        <th>Nama</th>   
                        <th width="40px">Harga Jual Tampil</th>         
                        <th width="40px">Harga Promo</th>
                        <th width="40px">Tgl Promo Mulai</th>
                        <th width="40px">Tgl Promo Berakhir</th>   
                    </tr>
                    </thead>
                </table>
            </div>
        <!-- nav-tabs-custom -->
        </div>
    </div>
    <!-- /.col -->
  </div>
</section>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />

<!-- /.content -->
<script>
$('#dataGridLazada').DataTable({
    'paging'      : true,
    'lengthChange': true,
    'searching'   : true,
    'ordering'    : false,
    'info'        : true,
    'autoWidth'   : false,
    "scrollX"     : true,
    ajax          : {
        url    : base_url + 'Lazada/dataGridPromo',
        dataSrc: "rows",
    },
    columns: [
        { data: 'IDBARANGLAZADA', visible: false },
        { data: 'IDINDUKBARANGLAZADA', visible: false },
        { data: 'SKULAZADA', visible: false },
        { data: 'NAMABARANG' },
        { data: 'HARGA', className: "text-right", },
        { data: 'HARGAPROMO', className: "text-right", },
        { data: 'PROMOMULAI', className: "text-center", },
        { data: 'PROMOBERAKHIR', className: "text-center", },
    ],
    columnDefs: [
        {
            "targets": 4,
            "render": function (data) {
                return '<input type="text" style="text-align:right; width:100px;" class="form-control HARGA '+data+'" value="' + data + '" placeholder="0" min="0" disabled>';
            },
        },
        {
            "targets": 5,
            "render": function (data) {
                return '<input type="text" style="text-align:right; width:100px;" class="form-control HARGAPROMO '+data+'" value="' + data + '" placeholder="0" min="0"disabled>';
            },
        },
        {
            "targets": 6,
            "render": function (data) {
                return '<input type="text" style="text-align:center; width:100px;" class="form-control TGLPROMOMULAI '+data+'" value="' + data + '" placeholder="YYYY-MM-DD">';
            },
        },
        {
            "targets": 7,
            "render": function (data) {
                return '<input type="text" style="text-align:center; width:100px;" class="form-control TGLPROMOBERAKHIR '+data+'" value="' + data + '" placeholder="YYYY-MM-DD" >';
            },
        },
    ],
    drawCallback: function(settings) {
        // Apply Inputmask to all input fields of class HARGAKONSINYASI
        $(".HARGA").each(function() {
            $(this).number(true, 0);
        });
        $(".HARGAPROMO").each(function() {
            $(this).number(true, 0);
        });
        
        $('.TGLPROMOMULAI, .TGLPROMOBERAKHIR, #TGLPROMOMULAIALL, #TGLPROMOBERAKHIRALL').datepicker({
    		format: 'yyyy-mm-dd',
    		 autoclose: true, // Close the datepicker automatically after selection
            container: 'body', // Attach the datepicker to the body element
            orientation: 'bottom auto' // Show the calendar below the input
    	});

    }
});

$("#TGLPROMOMULAIALL").change(function(){
    $(".TGLPROMOMULAI").datepicker('setDate',$("#TGLPROMOMULAIALL").val());
})

$("#TGLPROMOBERAKHIRALL").change(function(){
    $(".TGLPROMOBERAKHIR").datepicker('setDate',$("#TGLPROMOBERAKHIRALL").val());
})

function simpanPromoLazada(){
    loading();
    var table = $('#dataGridLazada').DataTable();

    var allData = [];
    var errorMsg = "";

     // Loop through each row of the DataTable
    table.rows().every(function() { 
        var dataRow = this.data();
        var row = this.node();
        var rowData = {};
        
        rowData.IDBARANGLAZADA      = dataRow.IDBARANGLAZADA;
        rowData.IDINDUKBARANGLAZADA = dataRow.IDINDUKBARANGLAZADA;
        rowData.NAMABARANG          = dataRow.NAMABARANG;
        rowData.SKULAZADA           = dataRow.SKULAZADA;
        rowData.HARGA               = dataRow.HARGA;
        rowData.HARGAPROMO          = dataRow.HARGAPROMO;
        
        var tglPromoMulai = $(row).find('input.TGLPROMOMULAI');
        rowData.PROMOMULAI = tglPromoMulai.val(); // Get the value of the number input
        
        var tglPromoBerakhir = $(row).find('input.TGLPROMOBERAKHIR');
        rowData.PROMOBERAKHIR = tglPromoBerakhir.val(); // Get the value of the number input
        
        allData.push(rowData);
    });
    
    if(errorMsg == "")
    {
        $.ajax({
            type      : 'POST',
            url       : base_url+'Lazada/setPromo',
            data      : {
                 'databarang' : JSON.stringify(allData),
            },
            dataType  : 'json',
            beforeSend: function (){
                //$.messager.progress();
            },
            success: function(msg){
                Swal.close();	
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
        	title            :  errorMsg,
        	type             : 'error',
        	showConfirmButton: false,
        	timer            : 2000
        });
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
</script>
