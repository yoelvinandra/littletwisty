
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="row">
        <div class="col-md-10">
            <div style="font-size:24px !important;">Master Potongan Member & Promosi Marketplace</div>
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
      <div class="nav-tabs-custom"  style="padding:0px; margin:0px;">
            <ul class="nav nav-tabs" id="tab_all_transaksi">
                <li class="active"><a href="#tab_master" onclick="javascript:changeTabMarketplace(0)" data-toggle="tab"><b>Master</b></a></li>
				<li id="header_shopee"><a href="#tab_shopee" data-toggle="tab" onclick="javascript:changeTabMarketplace(1)"><img alt="Shopee" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png"></a></li>
			    <!--<li id="header_tiktok"><a href="#tab_tiktok" data-toggle="tab" onclick="javascript:changeTabMarketplace(2)"><img alt="Tiktok" style="width:60px;"src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAaAAAAB5CAMAAABIrgU4AAABklBMVEX///8AAAAA8ur/AE/ExMQA9+/Y2Ng6Ojq7u7tLS0sEBAT/AE49rKia+vb29vaSkpLv7+//AEbp6enNzc1hYWGxsbH/gqgWFhbZ2dn/AEr/8PXk5ORYO0b/bpM2NjYu7Ob/AEL/zNYACgBVITKJiYmYmJjgI1b//P5UVFQiIiL/ytz/6vCmDTdCQkIXFxfq/v1nZ2f/1+LI/Pp5eXkrKysPAACgoKBq9vH/W4Pk/v3/jayBgYHX/fv/dpzvGFP/qL7/J2aS+fUL5N3/m7UAGhhY9vH/ADWlOllkNEAvERn4E1j/X46uN1b/QHVpFistGCDFJ1CJIDywHEdMJzPqIVj/scRyIDrjSXjAhpceEBQvlJG1/fohWlcew74IGhomS0ootK+rYnNCMTp+MkNGDR9oLUFkmp3hgqDaBUXAJ097vrx8b3ObdoEzeXYdLCsA0couFRwTKCchPj6eKkgiAAJAEiA0g4D/RG58OlDBNl4uamh8GDbYMGAYOTiWsbAAIiFIABZOs7C/UXS929rNu8CZID/l0ExxAAAQtklEQVR4nO2d+V8TSRqH0ymaIw0ZEs4WgRgkEpDDgASYJIQAIxNAQYQdiUTGY4bZXY3M6OiOuu7uDP/3dvWVrrM75Gjysb8/aehUddeTt+qtt96q9vkuoy4/qc1LleSpLvIAXXF5gK64aID8bt+Up7J0QKOIJqGm3L41T1AqILFTQJSJRCLfpd2+NU9QVED3AAChfrdvzRMUFdAWBORZ0JUQFdD3eQDkbbdvzRMUFVBgRwG06/ateYKiA7oPgJS/5va9efIxAAkP9hRAk27fmycfC1B0Py+BFbfvzZOPBUh4fAbk4Vtu35wnJiDhZUbOen3cFRALkPAQyOtu35wnDqAvMXnJC8e5LyYg4eCRdOj23XniABKOMllvKuS6OIAUQl7A1HXxACmEbrh9f1+9uICEo795foLL4gMSxn7whiF3ZQNIePxN0O1b/LplB0gQjgtu3+NXLXtAwvFmITXr9n1+tXIASDjOiclCYtntW/065QSQ8ORE9Ps3k2tdXQUPU4PlCJBQ7BRFLYHOG5AaLGeABOHtSc4PGXW5fcNfm5wCEoSnpyW/KHqAGizngBREx50lr4trsCoBJAh3n/44nF5ZX/UCQA1TZYAE4bYsAyB5MdSGqUJA0dsKHinkAWqYLgEIAAag9paK1KNH+bpblf+0WkJ+g9h1zp4k2FNh9d1VNBteWf0ClrUE5LQQXb1aC/XNjyj/Gfm2xSznJnrdhLOnH2qrrPqJagCFscpaqyiLLxcB9alfGg/o/40vGuV8g17X6xBQR2XV36ym2cJYZYPVFMYVDVBAYKqGgOJq1zVu+aRPL6chgOIt9kWyhQNqrAVt7byLMh6rhoDmYRfTPmP5ZGZIK6chgKarajZ3AV0HsaMB+mOpgFheXEUNJMAeLYgON3qv0xBA31bVbO52cdcByOy9pBpR7SxoAXYxQ9PIZzqKhgCqrknrAmg2lSgUuroKKeuHDEAQ0TPKWFQ7C1KbfRA11A7tORsCqLq2rHkXl1jbtBxUYVnUYQKS8lJsK4CbUe0sSO3OMEDT2nM2AtB8de1ZS0Czy2v4SSJOAIX6V7Ny7PmLIsKoZhY0o/pw2NxlIqyW0whAVfZJteviUoVNPy6HgHw3hoH86OLiaKFo/qlmFtSmfiP4LfKh/rNuAKCRamapvtoBSq2N4nREUXQKyDc1uRuRwaO9vYuXCxuqJQVqZUG6E9Vu7eNm9KBOAwBVm0xWG0CzawQbMXdy2vnTz+l0/+oddQMdF5CC6HA3EpIUlyFzdra3c+/D1i8QkFw9IH3KE5y3fGaMC5cEhHqEfNFmqYmuZPLvP83P9fb2zv3jnz+k0+uTzKzNmoxBCZSOf7T0qqg6ZmMxWZZDw2rtNoAUTS4BWZaARSwLautAtIA1ysDMgKm4+aQT5t87jM8uB6h73qb6sgId+C9+OTnqL/3+GvnCgw8xsNt/h7oRtAYWNJtE6JRO/rhrlDYWU9rbMSCf7076PQhZITlbbhhHn2Gkb7DV1FD5spsdMJoQnzBDcTaAguEhTGEqQax6oaeVWr2i5YLSPp04UEWBhYdnod3VO2ThNoC68VscIsa8xKYVz8nxr5bSxmKgIkAKotWVpawc0SE5XA/C2rmN1Q0M9S2OL7Z3M7+IAgoudmCavkkd8bFSBJYdznb5xdxxnMSjauEiE8muEyvIfECDc/g9TuB9asLiHIgnr86R0ioHpGjqxuT6dlaKRCKhUKSmgOy+iAAKjhNBqGl6/4IDYvhtCaWnOXlyFy/UVHRsTwoN4/upuWPQIOmuLGI/D8vwI47+cY5dfSlAim5dm5q6M7m60j9MMXrbFqoNoHEivBFntLwzQPD5j/EGQhR4cw/I2VX0azxAFG9lEf22r1DmM3pM/jguC6hC1QMQtpYHi2V1XU4AzcKBYITJxtAzIEW2EY+OA2hohvh+H1ZtwtK7PaHU17SAgvjAz1sXdQBodtQvln4lyiR1kAGRXetAxAZE8onjfFJlPqfUse/qAAr2IGrlR7MpfOaGyELp1VMALW8qfJ4ymCCKjp2BkPVUAqaTMEj0bwSfZdN/EzvpqzpXB9Bg24hVE9xgKcU/6A07rp4EpExExBzHPbAq+jIjSekyIRYgin+A8ynPf5iZBlcHUCt6yUgP7YsGINJ+pnlTWFtA8NF/ZzQRoehzIEnr5qSV0cVR/IN2vNpyeOeYVdmVBdTGA0T6B72VVE8AKlSUsCkIe8r8z/S26YCGiC8NEDEl00EQmXyaEhBt/OFHpG0ALSuPnXvLbCNS5xnF2zZcOSog0j9YIGN+xgRVPGVX1YSAKHzmbVYMbADBkeAPSuPEJ+bm5yZoztURABHjDD0aoFaif1sg+jdzBiSeFPGry2o+QEFyfmrHxwZQCv6GyTbq6GsdCneHh1rbJ4i/vVE6OaBP0SmAWgn/gOzfTANSvBOifGWgG9naut52Lhw0HSCK/VRaPQYIGtArvNDpnrLXEezBDSJ6AYCsH1ZNAqIktlJylg0PYZSo+/GL55/0WHTmU76pAM0FSf/AQdIUF1CCMkkM4NBvYmb7bA/I7zUTItxs0j+IU57XnALh3knx/r8AMJcL1H80D6Begs/ATQdLRFxA8JeMBcEGxokiFtGJV/S20nTaKYc4oD5i0Jqm5fwbI1AOC198/1sG4GoeQOSA7WjBmgcIThZL2ChAgY6b7suM0mzqXAgHRPhvbTQ+xhwV97AfxCyroZIcgksGzldUL61aASI07miJlQcI9nClx8ife2lORzfqKsTPgLyr9nE4IFy0/s0ShENt97VpPpIsh8DuyuHhev82aFpA31yqegQQ7GrQecgM6RFDIUktQvTMOE7cDhA9BKX3cCLqITww+cgKnPLK0y0nSSPVqE6AyKHCUfUIoDXCj2JFJVATuqdMhdSVIRtAjBCu3sPlkPWnB3um+bxfIbNUmg+Q06x3DiA4FuTQMDYe0jS0iFy1pbSL2oh8QAH61pZZYwSy9nDF30w+w7SF6uYDxGxLfvVWQNDbzaG+B2tY60auGoFvhYExbRsLGqEWt6zPgRDv5H7G4JOmJnk1IaAZzhoDu3oroJQyn8ficMxikKvO4VthHACim3lBD8JZAxiGAUms9m5CQDZRbEb1fEBxZjHIgviGAijrBFCAZuZJio/97pGkz3oY9TcjIGd+XEWARpjFIF5CFAKCfrYdIGqqkRZGyCEh2uf6rHSJ9aKMpgQ04mRnvt0YhEyDAsxiLmdBgjBPDkOaAZWQ/NUdzb/OMhPZmhIQfVbJr54AhGaSMotBrvridAwSaL6Mvs6AXBXT4m7rzDfNNCcgJ52cnZuNhsNYydVh5KrXCqAlh4DihC9Di5PqBsR+0UyTAnKwncBuooqu1rFOTUBLuQ7bxX4epD8NXpbmZCNpEFENUJr9qqYmAbQwjYX+B2zjcXahHvSZWfkn6CrPPaVdmJGEDjyqi8c8NB8BmSBHtdUFzouAmgPQQh+RL2O7YmcDSDxBIpYBPDtXExpIgLE4wIrF9Q71YZ8MYL6MBghdwlAB8V6ltUYBtHXVAMFTQXrwp7cLKPAApTZxb4qego+lIS5wotlzypAzh302gQ5DGiA0kg2nQfJ7zvH+SQqgD1cNkNp2+LIdY1MDq3p0wQ56CdiqM8UzDGMtfpQBoTR1PUjzqsPYh1gnRwP0LxUQ5zE2KYCeXy1AujsUxNM4KDMNTvUoINjHnWJJpQQhnE8UvrxU2+aAA9K9StzM0a2Walvn0AtgW8tL7KdYHqUA2rlagOZ0EK34NgR+J8cFpKbN42nZ0+gqATHsHcT0WRAz9TeIV9pmLTJJATQG+Bakx+9QQLGrBchMnu/DcrMHuJ0cP6tnDQ/7Qy2Ml9N6esbxPZHRCyDJemIca3dDmDBzS51dlC7uzR4ExPay1yjxu40zCMjM0bu0ag4oSAzCFVSPJy7CoAu5cWtkfrEn7Au3LM6Tu4bexcrvz2ZuP+nBsVpWatUV7xyavhDdh4CYh/SmtDw6EZm0aUvkkapf0llzQL4wnpvBCyjYAOpiZEcHFto62igbitVJ0HfGaxfZ+4OITq7syakLdjnUebR5K3eXvsaHpFi+1ABV/QrI2gMiBmHeqXx2yfPKkC062bxl6EDp9reNL3N22OH+3Zx5++p+PiKl9GOePVE1Mu1HkWn6QzX8EKn6PcR1AORDj47hbhCyA5SCfgJrczepeNlD8HEBhXHzK0+BVXvAd7xs/MYM9ehLsFii/YaWxPBd1e9GqwegbnwQZqfI2e4PUuM9zvZvKa2yA+R8uVPhbSLGAwrlB0+QwVJFM7/os19CRh4qOgSpmdtAktgt71D1AORrwX6ftAR1avWULZDQQ/rd4Q67fSCBlfLvnAcoiJu5eWwxXObAlhugHn8KYZvIVZlb8cTSZ+vlF3oEnN3yDlUXQEQefbyKXd7qFhQnhKIfgRRJW77JPSdhiDBz4y/QMzkh96UWd7KkCaXKR5EgveLjX0BtpkF1AuSbF7A/O6ueug1fISQ6sKHiPpAB0iD8w5Ra8KHN+HOCWPLWtLH/MzYKzVqOUjhFnFc9DcjZYSJc1QnQID4IMwIKjg6ygD9p7AwlUg+UYfw92gnZnHaFm3mbUTm0WerWx7f/Tli+v1ywnLSERjwCWhqQlK/+BSd1AuRrx55tpJqjYOCZRqfcrd7R+3tA3sZ+r3bHkeG+trEeqG6Mpdb25WQz2ZWYhUcwdiWRQxjRiNTjvDYEbVftxNUNENHJ0aOmzgDBs5T8OU43V9zPyPIq3hp2gAaxUswJ2yhlJqTp84noJyWKWMTwnpYGVP00tY6A8Bcn0Ds5h4C00bjEevHDs5gcWiJHcNsD/XBf2wjEJojceVMDnX4SEerACcKCnmbPcMwrEg6Ikit1OUC+HjzkQ0vDcgxIQZTcFEuvnhZRO4q++c/HWP59P62ztwVE7Ao0zDzJ3j989/gERSTmOjE++iQVyP3s+KpjjQsBi4wzlxENDgjIJSYg66dzZA+GD8LTlNYfF9D6+ZlahbVk8r/Hr5581oIqd9+9+Lj/v+30+iG9IRRASOHkMEicOaKbOdz+T+/jFL2GiPQXbori6Cnu8MHQqu0iuWMNtiNqobRQN/US7IsU8wi396GilI2V0m6bZZJKFBJ/tmglt//11+Hh5BTzZxpssS28B7tFI6xdoM1VTX1+cnxayo36R0udr54Q+/bGHukGNFwDA/LE0Jpf5Dr2gWLxy5fzIuXcf32OamSueKqPZpP+ktMQIKLohb6VKJS2r8XT5ZWq4BAnKx99AAJyvvo5kCeeUg6PqaPzAdTQqqdaKsE6yo/HJ6/ziQx7BlR3pTYr7OTKfGoyR/Vkp+Uk7agtpoqGf4AsG3qqo2a7KhiGDh4afCTgDUCN0p/c47qt3duReVSMFPH4NE5/HTjiM7OTL/OpOhnOUwWa/PEt632opvXc/WDiAbLXvzVYk9v7LzhHYwrFd/uWg7DkrOcfNFpT6djtI1ZyXvzotvWcssiSF4FrvK6tZjOxh2MbBJ3zlw9j1lP+ZJm6MOWp7prajkiZs9tHz8611wQKG6+fbT3fy1jpSHIkO+mtMLilyV1gvN4s9snSqZXxgOyKh8dNrS7lQ7IGQ8LxyCGwtOL1bi5r6nAlG9IZIcYTimT7Dz08V0C37hyms/AwWVmCViTB40sjkezwIf01lJ5c0K1rd9b7t3ezWSCBfHZ3O71+45pHp3H6P7gXO06fV6kXAAAAAElFTkSuQmCC"></a></li>-->
				<li id="header_lazada"><a href="#tab_lazada" data-toggle="tab" onclick="javascript:changeTabMarketplace(3)"><img alt="Lazada" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Lazada_%282019%29.svg/960px-Lazada_%282019%29.svg.png"></a></li>
			</ul>
            <div class="tab-content" style="padding:0px; margin:0px;">
                    <div class="tab-pane" id="tab_shopee">
                        <?php if($_GET['i'] == 1){include("v_master_promo_shopee.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_tiktok">
                         <?php if($_GET['i'] == 2){include("v_master_promo_tiktok.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_lazada">
                        <?php if($_GET['i'] == 3){include("v_master_promo_lazada.php");}?>
                    </div>
                    <div class="tab-pane active" id="tab_master">
                          <!-- Main row -->
                          <div class="row">
                             <div class="col-md-12">
                                <div class="box" style="border:0px; padding:0px; margin:0px;">
                                    <div class="box-header form-inline">
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
                    </div>
        <!-- nav-tabs-custom -->
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
if('<?=$_SESSION[NAMAPROGRAM]['USERNAME'] == 'USERTES'?>')
{
    $("#header_shopee").hide();
    $("#header_tiktok").hide();
    $("#header_lazada").hide();
}

if('<?=$_SESSION[NAMAPROGRAM]['SHOPEE_ACTIVE'] == 'NO'?>')
{
    $("#header_shopee").hide();
}
if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'NO'?>')
{
    $("#header_lazada").hide();
}

const params = new URLSearchParams(window.location.search);

const index = params.get('i')??0;

if(index == 1)
{
    $('.nav-tabs a[href="#tab_shopee"]').tab('show');
}
else if(index == 2)
{
    $('.nav-tabs a[href="#tab_tiktok"]').tab('show');
}
else if(index == 3)
{
    $('.nav-tabs a[href="#tab_lazada"]').tab('show');
}

function changeTabMarketplace(index){
    
    let url = new URL(window.location.href);
    url.searchParams.set("i", index);
    
     if(index == 0)
     {
         $("#tab_grid").hide();
     }
     else if(index == 1)
     {
         $("#tab_shopee").hide();
     }
     else if(index == 2)
     {
         $("#tab_tiktok").hide();
     }
     else if(index == 3)
     {
         $("#tab_lazada").hide();
     }
    
    // Redirect to the updated URL
    window.location.href = url.toString();
}

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
