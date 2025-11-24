<meta charset="UTF-8">
<style>
    /* Custom CSS */
   .image-upload-section {
       margin-top: 20px;
   }

   .image-upload-box {
       padding: 20px;
       border: 2px solid #ccc;
       border-radius: 8px;
       background-color: #f9f9f9;
       box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
   }

   .image-upload-header {
       text-align: center;
       font-size: 24px;
       font-weight: bold;
       margin-bottom: 15px;
   }

   .file-input-container {
       text-align: center;
       margin-bottom: 20px;
   }

   .file-input-container input[type="file"] {
       display: none;
   }

   .file-input-container label {
       font-size: 18px;
       color: #007bff;
       cursor: pointer;
       padding: 10px 25px;
       border: 2px solid #007bff;
       border-radius: 5px;
       transition: all 0.3s ease;
   }

   .file-input-container label:hover {
       background-color: #007bff;
       color: white;
       border-color: #0056b3;
   }

   .image-preview {
       display: flex;
       flex-wrap: wrap;
       justify-content: center;
       margin-top: 20px;
   }

   .image-preview .image-item {
       position: relative;
       width: 120px;
       height: 120px;
       margin: 10px;
       border-radius: 8px;
       box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
   }

   .image-preview img {
       width: 100%;
       height: 100%;
       border-radius: 8px;
       object-fit: cover;
   }

   .image-preview .image-item .remove-btn,
   .image-preview .image-item .change-btn {
       position: absolute;
       top: 5px;
       right: 5px;
       background-color: rgba(0, 0, 0, 0.6);
       color: white;
       padding: 5px;
       font-size:10px;
       border-radius: 100%;
       cursor: pointer;
   }

   .alert-message {
       color: red;
       text-align: center;
       font-size: 18px;
       margin-top: 10px;
   }

   /* Responsive Design */
   @media (max-width: 768px) {
       .image-preview img {
           width: 90px;
           height: 90px;
       }

       .file-input-container label {
           font-size: 16px;
           padding: 8px 20px;
       }
   }
   
   td{
       cursor:pointer;
   }
   
   .align-middle {
    vertical-align: middle !important;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Master Produk
    <button type="button" class="btn pull-right btn-success" id="btn_print" style="font-size:10pt;"  onclick="exportTableToExcel()">Excel</button>
  </h1>
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
                        <?php if($_GET['i'] == 1){include("v_master_barang_shopee.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_tiktok">
                         <?php if($_GET['i'] == 2){include("v_master_barang_tiktok.php");}?>
                    </div>
                    <div class="tab-pane" id="tab_lazada">
                        <?php if($_GET['i'] == 3){include("v_master_barang_lazada.php");}?>
                    </div>
                    <div class="tab-pane active" id="tab_master">
                        <div class=" ">    
                            <!-- Main row -->
                            <div class="row">
                                <div class="col-md-12">
                                <div class="box" style="border:0px; padding:0px; margin:0px;">
                                <div class="box-header form-inline">
                                    <button class="btn btn-success" onclick="javascript:tambahHeader()">Tambah</button>
                                    <!--<?php echo form_open_multipart(base_url().'Master/Data/Barang/importExcelUrutan', ['id' => 'excelFormUrutan']); ?>     -->
                                    <!--    <button style="margin-top:-30px; margin-left:10px;" type="button" class="btn pull-right btn-primary" onclick="openFileExcelUrutan()" >Upload Format Urutan</button> -->
                                    <!--    <input style="display:none;" type="file"  name="excelFileUrutan" id="excelFileUrutan" accept=".xls,.xlsx" onchange="importExcelUrutan()" required>-->
                                    <!--    <button type="button" class="btn pull-right btn-success" style="margin-top:-30px; font-size:10pt; "  onclick="exportTableToExcelUrutan()">Download Format Urutan</button>-->
                                    <!--<?php echo form_close(); ?>-->
                                </div>
                                <!-- Custom Tabs -->
                                <div class="nav-tabs-custom" >
                                    <ul class="nav nav-tabs" id="tab_transaksi">
                                        <li class="active"><a href="#tab_grid" data-toggle="tab">Grid</a></li>
                                        <li><a href="#tab_form"   data-toggle="tab" >Tambah</a></li>
                                    </ul>		
                        			
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_grid">
                                            <div class="box-body">
                                                <table id="dataGrid" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                                    <!-- class="table-hover"> -->
                                                    <thead>
                                                    <span style="font-style:italic">*Geser keatas atau kebawah untuk merubah urutan produk</span>
                                                    <tr>
                                                        <th width="80px"></th>
                                                        <th width="80px"></th>
                                                        <th width="80px"></th>
                                                        <th width="80px"></th>
                                                        <th width="80px"></th>
                                                        <th width="80px"></th>
                                                        <th>Nama Produk</th>
                                                        <th>Marketplace</th>
                                                        <th width="100px">Jml Varian</th>
                                                        <th width="200px">Harga Produk</th>
                                                        <th width="40px">Tgl. Input</th>
                                                        <th width="25px">Aktif</th>								
                                                    </tr>
                                                    </thead>
                                                </table>                        
                                            </div>
                                            <div id="tableExcel" style="display:none;" ></div>
                                            <div id="tableExcelUrutan" style="display:none;" ></div>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="tab_form">
                                            <div class="box-body">
                                                <div class="col-md-12">
                                                <!-- form start -->
                                                <form role="form" id="form_input">
                                                    <input type="hidden" id="mode" name="mode">
                                                    <input type="hidden" id="datavarian" name="datavarian">
                                                    <input type="hidden" id="datavarianhapus" name="datavarianhapus">
                                                    <input type="hidden" id="datagambar" name="datagambar">
                                                    <input type="hidden" id="datagambarvarian" name="datagambarvarian">
                                                        <div class="box-body">
                                                            <div class="form-group col-md-8">
                                                                <h3 style="font-weight:bold;">Informasi Produk</h3>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Kategori <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <select id="KATEGORIONLINE" name="KATEGORIONLINE" style="border:1px solid #B5B4B4; border-radius:1px; width:100%; height:32px; padding-left:12px; padding-right:12px;">
                                                                            <option value="SEPATU BAYI">SEPATU BAYI</option>
                                                                            <option value="SEPATU ANAK LAKI-LAKI">SEPATU ANAK LAKI-LAKI</option>
                                                                            <option value="SEPATU ANAK PEREMPUAN">SEPATU ANAK PEREMPUAN</option>
                                                                            <option value="PEMBUNGKUS KADO DAN KEMASAN">PEMBUNGKUS KADO DAN KEMASAN</option>
                                                                            <option value="SET DAN PAKET HADIAH">SET DAN PAKET HADIAH</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <label>Nama Produk <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <input type="text" class="form-control" id="KATEGORI" name="KATEGORI" placeholder="Nama Produk">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <br>
                                                                        <label>Deskripsi <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <textarea class="form-control" rows="9" id="DESKRIPSI" name="DESKRIPSI" placeholder="Deskripsi....."></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>			
                                                            <div class="form-group col-md-4">
                                                                <h3 style="font-weight:bold;">Pengiriman</h3>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                        								    <label>Berat (gram) <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="BERAT" name="BERAT" placeholder="Dalam Gram">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <label>Ukuran Barang (cm) <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>Panjang</label>
                                                                            <input type="text" class="form-control" id="PANJANG" name="PANJANG" placeholder="Dalam Centimeter">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Lebar</label>
                                                                            <input type="text" class="form-control" id="LEBAR" name="LEBAR" placeholder="Dalam Centimeter">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>Tinggi</label>
                                                                            <input type="text" class="form-control" id="TINGGI" name="TINGGI" placeholder="Dalam Centimeter">
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <div class ="form-group col-md-8" id="checkbox-variation">
                                                                <label><input type="checkbox" class="flat-blue" id="VARIANSET" name="VARIANSET" value="1">&nbsp; Produk ini menggunakan varian &nbsp;&nbsp;&nbsp;</label>
                                                            </div>
                                                            <div class ="form-group col-md-8" id="FORMDATAINDUK">
                                                            <label><input type="checkbox" class="flat-blue" id="STATUSINDUK" name="STATUSINDUK" value="1">&nbsp; Produk Aktif &nbsp;&nbsp;&nbsp;</label>
                                                            <input type="hidden" id="IDBARANGINDUK" name="IDBARANGINDUK">
                                                            <br>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                         <label for="KODEBARANG">Kode Produk&nbsp;&nbsp;&nbsp;</label>
                                                                        <input type="text" class="form-control" id="KODEBARANGINDUK" name="KODEBARANGINDUK" placeholder="AUTO" readonly>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <label>Harga Jual Tampil<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <input type="text" class="form-control" id="HARGAJUALINDUK" name="HARGAJUALINDUK" placeholder="Harga Jual Tampil">
                                                                    </div>
                                                                     <div class="col-md-3">
                                                                        <label>Harga Beli <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <input type="text" class="form-control" id="HARGABELIINDUK" name="HARGABELIINDUK" placeholder="Harga Beli">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label>Satuan<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <input type="text" class="form-control" id="SATUANINDUK" name="SATUANINDUK" placeholder="Cth : PAIR">
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                        						        <label>SKU Shopee <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <input type="text" class="form-control" id="SKUSHOPEEINDUK" name="SKUSHOPEEINDUK" placeholder="SKU Shopee">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>SKU Tiktok <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <input type="text" class="form-control" id="SKUTIKTOKINDUK" name="SKUTIKTOKINDUK" placeholder="SKU Tiktok">
                                                                    </div>
                                                                    <div class="col-md-4">
                                        						        <label>SKU Lazada <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                        <input type="text" class="form-control" id="SKULAZADAINDUK" name="SKULAZADAINDUK" placeholder="SKU Lazada">
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                        						        <label>Barcode</label>
                                                                        <input type="text" class="form-control" id="BARCODEINDUK" name="BARCODEINDUK" placeholder="Barcode">
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row" hidden>
                                                                    <div class="col-md-6">
                                                                        <label>SKU Grab</label>
                                                                        <input type="text" class="form-control" id="SKUGRABINDUK" name="SKUGRABINDUK" placeholder="SKU Grab">
                                                                    </div>
                                                                     <div class="col-md-6">
                                        						        <label>SKU Gojek</label>
                                                                        <input type="text" class="form-control" id="SKUGOJEKINDUK" name="SKUGOJEKINDUK" placeholder="SKU Gojek">
                                                                    </div>
                                                                <br>
                                                                </div>
                                                                <div class="row" hidden>
                                                                    <div class="col-md-6">
                                        						        <label>SKU Tokped</label>
                                                                        <input type="text" class="form-control" id="SKUTOKPEDINDUK" name="SKUTOKPEDINDUK" placeholder="SKU Tokped">
                                                                    </div>
                                                                <br>
                                                                </div>
                                                            </div>        
                                                            <div class ="form-group col-md-12" id="FORMDATAVARIAN">
                                                                <h3 style="font-weight:bold; margin-bottom:-5px;">Varian Produk<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Min 1)</i></h3>
                                                                
                                                                <!--<button type="button" style="margin-bottom:-50px;" id="btn_tambah_varian" onclick="tambah()" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modal-varian"data-id="7">Tambah Varian</button> &nbsp;&nbsp;&nbsp;&nbsp;-->
                                                                <button type="button" style="margin-bottom:-50px; margin-top:10px;" id="btn_set_varian" onclick="tambahMassal()" class="btn btn-success btn-flat pull-left" data-toggle="modal" data-target="#modal-set-varian"data-id="7">Tambah Varian</button>
                                                                <!--<div style="font-style:italic; margin-left:130px; margin-top:17px;">*Geser keatas atau kebawah, dan simpan untuk merubah urutan varian</div>-->
                                                                <div style="margin-top:10px;padding-bottom:10px; width:100%; overflow-x:scroll;">
                                                                    <table id="dataGridVarian" class="table table-bordered table-striped table-hover display nowrap" width="100%">
                                                                        <!-- class="table-hover"> -->
                                                                        <thead>
                                                                        <tr>
                                                                            <th width="100px"></th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th></th>
                                                                            <th>Nama</th>
                                                                            <th width="50px">Sat</th>
                                                                            <th width="100px">Harga Jual Tampil</th>
                                                                            <th width="100px">Harga Beli</th>
                                                                            <th width="200px">Barcode</th>
                                                                            <th width="200px">SKU Shopee</th>
                                                                            <th width="200px">SKU Tiktok</th>
                                                                            <th width="200px">SKU Lazada</th>
                                                                            <th width="40px">User Input</th>
                                                                            <th width="40px">Tgl. Input</th>
                                                                            <th width="25px">Aktif</th>								
                                                                        </tr>
                                                                        </thead>
                                                                    </table>  
                                                                </div>
                                                            </div>
                                                            <div class ="form-group col-md-12">
                                                                <br>
                                                                <h3 style="font-weight:bold; margin-bottom:-5px;">Gambar Produk<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Min 2), Jika perlu tabel ukuran, harap upload pada Gambar 7 (untuk Lazada) dan Gambar 9 (untuk Shopee)</i></h3>
                                                                <br>
                                                                <table id="gambarproduk">
                                                                </table>  
                                                            </div>
                                                            <div class ="form-group col-md-12" id="DIVGAMBARVARIANSHOPEE">
                                                                <h3 style="font-weight:bold; margin-bottom:-5px;">Gambar Varian<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib (Setiap Varian)</i></h3>
                                                                <br>
                                                                <table id="gambarvarian">
                                                                </table>    
                                                            </div>
                                                        </div>
                                                        <div class="box-footer">&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" id="btn_simpan" class="btn btn-primary" style="margin-right:5px;" onclick="javascript:simpanHeader()">Simpan</button>
                                                            <button type="button" id="btn_simpan_shopee" class="btn" style="background:#EE4D2D; color:white; margin-right:5px;" onclick="javascript:simpanHeader('SHOPEE')">Simpan & Hubungkan Shopee</button>
                                                            <button type="button" id="btn_simpan_lazada" class="btn" style="background:#201ADC; color:white;" onclick="javascript:simpanHeader('LAZADA')">Simpan & Hubungkan Lazada</button>
                                                        </div>
                                                    </div>
                        						</form>
                        						<div class="modal fade" id="modal-varian" >
                        							<div class="modal-dialog modal-lg">
                        							<div class="modal-content">
                        								<div class="modal-body">
                                                            <div class="box-body">
                                                                <input type="hidden" id="IDBARANG" name="IDBARANG">
                                                                <div class="form-group col-md-12">
                                                                    
                                                                    <h3 style="font-weight:bold;">Varian Produk<label>&nbsp;&nbsp;&nbsp;<input type="checkbox" class="flat-blue" id="STATUS" name="STATUS" value="1">&nbsp; Varian Aktif &nbsp;&nbsp;&nbsp;</label></h3>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label for="KODEBARANG">Kode Varian&nbsp;&nbsp;&nbsp;</label>
                                                                           <input type="text" class="form-control" id="KODEBARANG" name="KODEBARANG" placeholder="AUTO" readonly>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-9">
                                                                            <label>Nama Varian  <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <div>
                                                                                <span class="pull-left" style="padding-top:7px; font-style:italic;" >LITTLE TWISTY -&nbsp;</span>
                                                                                <input type="text" class="form-control" id="NAMABARANG" name="NAMABARANG" placeholder="Contoh : LEON ZIPPER SHOES" style="width:80%;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label>Harga Jual Tampil<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="HARGAJUAL" name="HARGAJUAL" placeholder="Harga Jual Tampil">
                                                                        </div>
                                                                         <div class="col-md-3">
                                                                            <label>Harga Beli <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="HARGABELI" name="HARGABELI" placeholder="Harga Beli">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label>Warna</label>
                                                                            <input type="text" class="form-control" id="WARNABARANG"   name="WARNABARANG" placeholder="Cth : BLUE">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label>Ukuran</label>
                                                                            <input type="number" class="form-control" id="UKURANBARANG"  name="UKURANBARANG" placeholder="Cth : 0" onkeyup="checkValid(event)" mouseup="checkValid(event)" onkeydown="return ( event.ctrlKey || event.altKey || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) || (95<event.keyCode && event.keyCode<106)|| (event.keyCode==8) || (event.keyCode==9) || (event.keyCode>34 && event.keyCode<40) || (event.keyCode==46) )">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label>Satuan<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="SATUAN2" name="SATUAN2" placeholder="Cth : PAIR">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                        							        <label>SKU Shopee <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="SKUSHOPEE" name="SKUSHOPEE" placeholder="SKU Shopee">
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <label>SKU Tiktok <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="SKUTIKTOK" name="SKUTIKTOK" placeholder="SKU Tiktok">
                                                                        </div>
                                                                        <div class="col-md-4">
                                        							        <label>SKU Lazada <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="SKULAZADA" name="SKULAZADA" placeholder="SKU Lazada">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                        							        <label>Barcode</label>
                                                                            <input type="text" class="form-control" id="BARCODE" name="BARCODE" placeholder="Barcode">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row" hidden>
                                                                        <div class="col-md-6">
                                                                            <label>SKU Grab</label>
                                                                            <input type="text" class="form-control" id="SKUGRAB" name="SKUGRAB" placeholder="SKU Grab">
                                                                        </div>
                                                                         <div class="col-md-6">
                                        							        <label>SKU Gojek</label>
                                                                            <input type="text" class="form-control" id="SKUGOJEK" name="SKUGOJEK" placeholder="SKU Gojek">
                                                                        </div>
                                                                    <br>
                                                                    </div>
                                                                    <div class="row" hidden>
                                                                        <div class="col-md-6">
                                        							        <label>SKU Tokped</label>
                                                                            <input type="text" class="form-control" id="SKUTOKPED" name="SKUTOKPED" placeholder="SKU Tokped">
                                                                        </div>
                                                                    <br>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="box-footer">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                                <button type="button" id="btn_simpan_detail" class="btn btn-success" onclick="javascript:simpan()">Tambah</button>
                                                                </div>
                                                            </div>
                        								</div>
                        							</div>
                        						</div>
                        						
                        						<div class="modal fade" id="modal-set-varian" >
                        							<div class="modal-dialog modal-lg">
                        							<div class="modal-content">
                        								<div class="modal-body">
                                                            <div class="box-body">
                                                                
                                                                <div class="form-group col-md-12">
                                                                    
                                                                    <h3 style="font-weight:bold;">Tambah Varian Produk Massal</h3>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <label>Nama Varian  <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <div>
                                                                            <span class="pull-left" style="padding-top:7px; font-style:italic;" >LITTLE TWISTY -&nbsp;</span>
                                                                            <input type="text" class="form-control" id="NAMABARANGMASSAL" name="NAMABARANGMASSAL" placeholder="Contoh : LEON ZIPPER SHOES" style="width:88%;">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <label>Harga Jual Tampil<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="HARGAJUALMASSAL" name="HARGAJUALMASSAL" placeholder="Harga Jual Tampil">
                                                                        </div>
                                                                         <div class="col-md-3">
                                                                            <label>Harga Beli <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="HARGABELIMASSAL" name="HARGABELIMASSAL" placeholder="Harga Beli">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <label>Satuan<i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label>
                                                                            <input type="text" class="form-control" id="SATUANMASSAL" name="SATUANMASSAL" placeholder="Cth : PAIR">
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>Warna <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label><br>
                                                                            <select class="form-control select2" multiple="multiple" id="PILIHWARNAMASSAL" name="PILIHWARNAMASSAL" placeholder="Warna..." style="width:100%; float:left;"  onchange="">
                                                                      	    </select>
                                                                      	    <i>*ketik warna, lalu enter untuk menambah Warna Baru</i>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label>Ukuran <i style="color:grey;">&nbsp;&nbsp;&nbsp;Wajib</i></label><br>
                                                                            <select class="form-control select2" multiple="multiple" id="PILIHUKURANMASSAL" name="PILIHUKURANMASSAL" placeholder="Ukuran..." style="width:100%; float:left;"  onchange="">
                                                                            </select>
                                                                      	    <i>*ketik ukuran, lalu enter untuk menambah Ukuran Baru</i>
                                                                        </div>
                                                                    </div>
                                                                    <br>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <label style="color:red; font-size:16pt;">*SKU dan Barcode akan digenerate otomatis melalui system, pastikan dahulu sebelum menyimpan barang.</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="box-footer">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                                <button type="button" id="btn_simpan_massal_detail" class="btn btn-success" onclick="javascript:simpanMassal()">Tambah Varian</button>
                                                                </div>
                                                            </div>
                        								</div>
                        							</div>
                        						</div>
                        						
                        					  </div>
                                                    <!-- /.box-body -->
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
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
  
  <div class="modal fade" id="modal-lebih-jelas" style="z-index:999999999999999999999999999;">
	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
        	<div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-label="Close"  class="btn" style=" background:white; float:left;">
                        <i class='fa fa-arrow-left' ></i>
                    </button>
                    <h4 class="modal-title" id="largeModalLabel" style="float:left; padding-top:4px;">&nbsp;&nbsp; <span id="titleLebihJelas" style="font-size:14pt;"></span></h4>
                    <!--<button id='btn_cetak_konfirm_shopee'  style="float:right;" class='btn btn-warning' onclick="noteKonfirmShopee()">Cetak</button>-->
            </div>
    		<div class="modal-body">
    		    <div id="previewLebihJelas">
    		        
    		    </div>
    		</div>
    	</div>
	</div>
 </div>
 
  <!-- /.row (main row) -->
</section>
<!-- /.content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.2/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
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
    $("#btn_simpan_shopee").hide();
}
if('<?=$_SESSION[NAMAPROGRAM]['LAZADA_ACTIVE'] == 'NO'?>')
{
    $("#header_lazada").hide();
    $("#btn_simpan_lazada").hide();
}

var dataVarianLama = [];
var dataLama = {};
var kategoriBarangShopee = [
    100688,
    101060,
    101068,
    101336,
    100685
];
var kategoriBarangLazada = [
    14010,
    14010,
    14010,
    16722,
    13580
];
var indukBarangShopee = 0;
var indukBarangLazada = 0;
var	dataGambar = [
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
        	];
var dataGambarVarian = [
        	    {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
        	
        	];

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

$('#VARIANSET').on('ifChanged', function (event) {
    let isChecked = $(this).prop('checked');

    if (isChecked) {
        $("#FORMDATAINDUK").hide();
        $("#FORMDATAVARIAN").show();
        $("#DIVGAMBARVARIANSHOPEE").show();
    } else {
        $("#FORMDATAINDUK").show();
        $("#FORMDATAVARIAN").hide();
        $("#DIVGAMBARVARIANSHOPEE").hide();
    }
});

$(document).ready(function() {
    if(index == 0)
    {
    	var counter = 0;
        $("#mode").val('tambah');
        $("#btn_simpan_detail").html('Tambah');
        $("#STATUS").prop('checked',true).iCheck('update');
        $("#STOK").prop('checked',true).iCheck('update');
        
        	
        $('#KATEGORI, #NAMABARANG, #NAMABARANGMASSAL').on('keypress', function(event) {
            // List of characters to block
            const blockedChars = ['/', '\\', '.', ',','|'];
    
            // Check if the pressed key is in the blocked characters array
            if (blockedChars.includes(String.fromCharCode(event.which))) {
                event.preventDefault(); // Prevents the character from being typed
            }
        });
        
        $('.select2').select2();
        
        $('#PILIHWARNAMASSAL').select2({
            tags: true,  // Allows custom tags (user input)
            placeholder: "",
            allowClear: true,
            width: '100%',
        });
        
        $('#PILIHUKURANMASSAL').select2({
            tags: true,  // Allows custom tags (user input)
            placeholder: "",
            allowClear: true,
            width: '100%',
        });
    
        // When the Select2 dropdown is opened, add the button above the options
        $('#PILIHUKURANMASSAL').on('select2:close', function() {
            $('#PILIHUKURANMASSAL option').each(function() {
                var optionValue = $(this).val();
        
                // Check if the option value is a valid number
                if (isNaN(optionValue) || optionValue === '') {
                    // Remove the option if it's not a valid number
                    $(this).remove();
                    Swal.fire({
        				title            : 'Ukuran harus berupa angka',
        				type             : 'warning',
        				showConfirmButton: false,
        				timer            : 1500
        			});
                }
            });
        
            // Trigger select2 change event to update the dropdown
            $('#PILIHUKURANMASSAL').trigger('change');
        });
            
        $("#HARGABELI, #HARGAJUAL, #HARGABELIMASSAL, #HARGAJUALMASSAL,#HARGABELIINDUK, #HARGAJUALINDUK, #BERAT, #PANJANG, #LEBAR, #TINGGI").number(true, 0);
    	
        $('#dataGrid').DataTable({
            'pageLength'  : 25,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
    		"scrollX"	  : true,
    		ajax		  : {
    			url    : base_url+'Master/Data/Barang/dataGrid',
    			dataSrc: "rows",
    			dataFilter: function (data) {
    			    
    			     $.ajax({
                        type      : 'POST',
                        url       : base_url+'Master/Data/Barang/dataGridVarian',
                        dataType  : 'json',
                        beforeSend: function (){
                            //$.messager.progress();
                        },
                        success: function(dataVarian){
                             // Refresh the new table whenever DataTable reloads
                            var allData = dataVarian.rows; // Get all rows' data
            
                            // Create the HTML structure for the new table
                            var newTable = $('<table id="newTable" class="table table-bordered">');
                            var thead = $('<thead>').append('<tr><th>Kategori</th><th>Nama Produk</th><th>Kode Varian</th><th>Nama Varian</th><th>Warna</th><th>Ukuran</th><th>Satuan</th><th>Harga Jual Tampil</th><th>Harga Beli</th><th>Barcode</th><th>SKU Shopee</th><th>SKU Tiktok</th><th>SKU Lazada</th><th>Berat</th><th>Panjang</th><th>Lebar</th><th>Tinggi</th><th>User Buat</th><th>Tgl Entry</th><th>Status</th></tr>');
                            var tbody = $('<tbody>');
                             // Loop through the DataTable data and create rows for the new table
                     
                            allData.forEach(function (row, index) {
                                var tr = $('<tr>');
                                tr.append('<td>' + (row.KATEGORIONLINE) + '</td>');
                                tr.append('<td>' + (row.KATEGORI) + '</td>');
                                tr.append('<td>' + (row.KODEBARANG) + '</td>');
                                tr.append('<td>' + (row.NAMABARANG.split(" | ")[0]) + '</td>');
                                tr.append('<td>' + (row.WARNA == null?"":row.WARNA) + '</td>');
                                tr.append('<td>' + (row.UKURAN == null?"":row.UKURAN) + '</td>');
                                tr.append('<td>' + (row.SATUAN == null?"":row.SATUAN) + '</td>');
                                tr.append('<td>' + (row.HARGAJUAL == null?"":row.HARGAJUAL) + '</td>');
                                tr.append('<td>' + (row.HARGABELI == null?"":row.HARGABELI) + '</td>');
                                tr.append('<td>' + (row.BARCODE == null?"":row.BARCODE) + '</td>');
                                tr.append('<td>' + (row.SKUSHOPEE == null?"":row.SKUSHOPEE) + '</td>');
                                tr.append('<td>' + (row.SKUTIKTOK == null?"":row.SKUTIKTOK) + '</td>');
                                tr.append('<td>' + (row.SKULAZADA == null?"":row.SKULAZADA) + '</td>');
                                tr.append('<td>' + (row.BERAT == null?"":row.BERAT) + '</td>');
                                tr.append('<td>' + (row.PANJANG == null?"":row.PANJANG) + '</td>');
                                tr.append('<td>' + (row.LEBAR == null?"":row.LEBAR) + '</td>');
                                tr.append('<td>' + (row.TINGGI == null?"":row.TINGGI) + '</td>');
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
                            
                            //UNTUK URUTAN
                            // Create the HTML structure for the new table
                            var newTableUrutan = $('<table id="newTableUrutan" class="table table-bordered">');
                            var theadUrutan = $('<thead>').append('<tr><th>Kode Varian</th><th>SKU Varian</th><th>Nama Varian</th></tr>');
                            var tbodyUrutan = $('<tbody>');
                             // Loop through the DataTable data and create rows for the new table
                     
                            allData.forEach(function (row, index) {
                                trUrutan = $('<tr>');
                                trUrutan.append('<td>' + (row.KODEBARANG) + '</td>');
                                trUrutan.append('<td>' + (row.SKUSHOPEE == null?"":row.SKUSHOPEE) + '</td>');
                                trUrutan.append('<td>' + (row.NAMABARANG) + '</td>');
                                // Append the row to the tbody
                                tbodyUrutan.append(trUrutan);
                            });
                    
                            // Append the thead and tbody to the new table
                            newTableUrutan.append(theadUrutan).append(tbodyUrutan);
                            $('#tableExcelUrutan').html(newTableUrutan);
                        }
                    });
                   
                    
                    return data;
                }
    		},
            columns:[
                // { data: 'IDBARANG', visible: false},
                {data: ''},
                {data: 'DESKRIPSI',visible:false},
                {data: 'PANJANG',visible:false},
                {data: 'LEBAR',visible:false},
                {data: 'TINGGI',visible:false},
                {data: 'BERAT',visible:false},
                {data: 'KATEGORI'},
                {data: '', className:"text-center"},
                {data: 'JMLVARIAN', className:"text-center"},
                {data: 'RANGEHARGAUMUM', className:"text-center"},
                {data: 'TGLENTRY', className:"text-center"},
                {data: 'STATUS', className:"text-center"},
            ],
    		'columnDefs': [
    			{
    			    "targets": 0,
                    "data": null,
                    "defaultContent": "<button id='btn_ubah' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus' class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
    			},
    			{
                    "targets": -5,
                    "render" :function (data,display,row) 
    					{
    					    var logo = "";
    					    if(row.IDINDUKBARANGSHOPEE != 0)
    					    {
    					        logo += '<img alt="Shopee" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png">';
    					    }
    					    if(row.IDINDUKBARANGLAZADA != 0)
    					    {
    					        if(logo != ""){logo += '<br>'}
    					        logo += '<img alt="Lazada" style="width:60px;"src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Lazada_%282019%29.svg/960px-Lazada_%282019%29.svg.png">';
    					    }
    					    
    					    if(logo == "")return 'Tidak Terhubung';
    					    
    					    return logo
    					},	
    			},
    			{
                    "targets": -1,
                    "render" :function (data) 
    					{
    						if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                            else return '<input type="checkbox" class="flat-blue" disabled></input>';
    					},	
    			}
    		],
        });
    
        //DAPATKAN INDEX
    	$('#dataGrid tbody').on( 'click', 'button', function () {
    		var row = $('#dataGrid').DataTable().row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		
    		if(mode == "btn_ubah"){ ubahHeader(row); }
    		if(mode == "btn_hapus"){ hapusHeader(row); }
    	});
        
        const tbody = $('#dataGrid tbody')[0];
        const sortable = new Sortable(tbody, {
            animation: 150,
            ghostClass: 'dragging',
            handle: 'tr',
            onEnd: function(evt) {
                if(evt.oldIndex != evt.newIndex)
                {
                    let movedData = $('#dataGrid').DataTable().row(evt.oldIndex).data();
                    let temp;
                    
                    var dataList = $('#dataGrid').DataTable().rows().data();
                    $('#dataGrid').DataTable().clear();
                    
                    for(var x = 0 ; x < dataList.length; x++)
                    {
                        if(evt.newIndex <= evt.oldIndex)
                        {
                            
                           if(x == evt.newIndex)
                           {
                               temp = dataList[x];
                               dataList[x] = movedData;
                               movedData = temp;
                           }
                           else if(x <= evt.oldIndex && x > evt.newIndex)
                           {
                               temp = dataList[x];
                               dataList[x] = movedData;
                               movedData = temp;
                           }
                             $('#dataGrid').DataTable().row.add(dataList[x]).draw();
                        }
                        else
                        {
                           if(x >= evt.oldIndex && x < evt.newIndex)
                           {
                               dataList[x] = dataList[x+1];
                           }
                           else if(x == evt.newIndex)
                           {
                               dataList[x] = movedData;
                           }
                         $('#dataGrid').DataTable().row.add(dataList[x]).draw();
                        }
                    }
                    
                     var table = $('#dataGrid').DataTable();
        
                    // Get all data from the table
                    var data = table.rows().data();
                    
                     // Prepare an array with the order and other necessary data
                    var tableData = [];
                    data.each(function(value, index) {
                        // For example, push only the data you need (row ID, order, etc.)
                        tableData.push({
                            KATEGORI: value.KATEGORI,  // Assuming the ID is in the first column
                            KATEGORIONLINE: value.KATEGORIONLINE, // Assuming the name is in the second column
                        });
                    });
                    
                    
                    $.ajax({
                        type      : 'POST',
                        url       : base_url+'Master/Data/Barang/ubahUrutanTampil',
                        data      : {dataKategori:JSON.stringify(tableData)},
                        dataType  : 'json',
                        beforeSend: function (){
                            //$.messager.progress();
                        },
                        success: function(msg){
                            if (msg.success) {
                                Swal.fire({
                                    title            : 'Ubah Urutan Sukses',
                                    type             : 'success',
                                    showConfirmButton: false,
                                    timer            : 1500
                                });
                                $("#dataGrid").DataTable().ajax.reload();
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
        });
    
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
        
        
        $('#dataGridVarian').DataTable({
            'paging'      : false,
            'lengthChange': false,
            'searching'   : true,
            'ordering'    : false,
            'info'        : true,
            'autoWidth'   : false,
    		"scrollX"	  : false,
    		ajax		  : {
    			url    : base_url+'Master/Data/Barang/getDataVarian',
    			dataSrc: "rows",
    		},
            columns:[
                {data: ''},
                {data: 'IDBARANG', visible:false},
                {data: 'WARNA', visible:false},
                {data: 'SIZE', visible:false},
                {data: 'MODE', visible:false},
                {data: 'IDBARANG', visible:false},
                {data: 'KODEBARANG', visible:false},
                {data: 'NAMABARANG'},
                {data: 'SATUAN', visible:false},
                {data: 'HARGAJUAL', render:format_number, className:"text-right"},
                {data: 'HARGABELI', render:format_number, className:"text-right"},
                {data: 'BARCODE', className:"text-center"},
                {data: 'SKUSHOPEE', className:"text-center"},
                {data: 'SKUTIKTOK', className:"text-center"},
                {data: 'SKULAZADA', className:"text-center"},
                {data: 'USERBUAT' },
                {data: 'TGLENTRY', className:"text-center"},
                {data: 'STATUS', className:"text-center"},
            ],
    		'columnDefs': [
    			{
    			    "targets": 0,
                    "data": null,
                    "defaultContent": "<button id='btn_ubah_varian' type='button' data-toggle='modal' data-target='#modal-varian' data-id='7' class='btn btn-primary'><i class='fa fa-edit'></i></button> <button id='btn_hapus_varian' type='button'  class='btn btn-danger'><i class='fa fa-trash' aria-hidden='true' ></button>"	
    			},
    			{
                    "targets": -1,
                    "render" :function (data) 
    					{
    						if (data == 1) return '<input type="checkbox" class="flat-blue" checked disabled></input>';
                            else return '<input type="checkbox" class="flat-blue" disabled></input>';
    					},	
    			}
    		]
        });
        
         //DAPATKAN INDEX
    	$('#dataGridVarian tbody').on( 'click', 'button', function () {
    		var row = $('#dataGridVarian').DataTable().row( $(this).parents('tr') ).data();
    		var mode = $(this).attr("id");
    		
    		if(mode == "btn_ubah_varian"){ ubah(row); }
    		if(mode == "btn_hapus_varian"){ hapus(row); }
    	});
    	
    	const tbodyvarian = $('#dataGridVarian tbody')[0];
        // const sortablevarian = new Sortable(tbodyvarian, {
        //     animation: 150,
        //     ghostClass: 'dragging',
        //     handle: 'tr',
        //     onEnd: function(evt) {
                
        //         let movedData = $('#dataGridVarian').DataTable().row(evt.oldIndex).data();
        //         let temp;
                
        //         var dataList = $('#dataGridVarian').DataTable().rows().data();
        //         $('#dataGridVarian').DataTable().clear();
                
        //         for(var x = 0 ; x < dataList.length; x++)
        //         {
        //             if(evt.newIndex <= evt.oldIndex)
        //             {
                        
        //               if(x == evt.newIndex)
        //               {
        //                   temp = dataList[x];
        //                   dataList[x] = movedData;
        //                   movedData = temp;
        //               }
        //               else if(x <= evt.oldIndex && x > evt.newIndex)
        //               {
        //                   temp = dataList[x];
        //                   dataList[x] = movedData;
        //                   movedData = temp;
        //               }
        //                  $('#dataGridVarian').DataTable().row.add(dataList[x]).draw();
        //             }
        //             else
        //             {
        //               if(x >= evt.oldIndex && x < evt.newIndex)
        //               {
        //                   dataList[x] = dataList[x+1];
        //               }
        //               else if(x == evt.newIndex)
        //               {
        //                   dataList[x] = movedData;
        //               }
        //              $('#dataGridVarian').DataTable().row.add(dataList[x]).draw();
        //             }
        //         }
                
        //     }
        // });
    }
            
});

function exportTableToExcel() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcel'), {sheet:"Sheet 1"});
 const ws = wb.Sheets[wb.SheetNames[0]];
  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 150 }, // Column A width in pixels
    { wpx: 300 }, // Column A width in pixels
    { wpx: 70 }, // Column A width in pixels
    { wpx: 250 }, // Column B width in pixels
    { wpx: 100 },  // Column C width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 },  // Column C width in pixels
    { wpx: 70 },  // Column C width in pixels
    { wpx: 70 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 150 },  // Column C width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 }, // Column A width in pixels
    { wpx: 40 }, // Column B width in pixels
    { wpx: 100 }, // Column A width in pixels
    { wpx: 70 }, // Column B width in pixels
    { wpx: 50 },  // Column C width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'BARANG_'+dateNowFormatExcel()+'.xlsx');
}

function exportTableToExcelUrutan() {
  var wb = XLSX.utils.table_to_book(document.getElementById('tableExcelUrutan'), {sheet:"Sheet 1"});
 const ws = wb.Sheets[wb.SheetNames[0]];
  // Set column widths - specify column widths for each column in the 'cols' array
  ws['!cols'] = [
    { wpx: 70 }, // Column A width in pixels
    { wpx: 150 }, // Column A width in pixels
    { wpx: 300 }, // Column A width in pixels
  ];
  // Trigger download
  XLSX.writeFile(wb, 'URUTAN_BARANG_'+dateNowFormatExcel()+'.xlsx');
}

function checkValid(data) {
    var inputElement = event.target;
    const classList = inputElement.classList;
    const inputValue = inputElement.value;
    
    if(inputValue < 0)
    {
        inputElement.value = 0;
    }
    if(inputValue > 35)
    {
        inputElement.value = 35;
    }
}

function tambahHeader(){
    $("#btn_simpan").show();
    indukBarangShopee = 0;
    indukBarangLazada = 0;
    $("#FORMDATAINDUK").hide();
    $("#FORMDATAVARIAN").hide();	
	$("#dataGridVarian").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian/');
	$("#dataGridVarian").DataTable().ajax.reload();
    get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.TAMBAH==1) {
			$("#mode").val('tambah');

			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Tambah');
			
        	dataVarianLama = [];
        	dataGambar = [
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
        	];
        	dataGambarVarian = [
        	    {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
        	
        	];
        	
            $('#KATEGORIONLINE option:first').prop('selected', true);
	        $("#VARIANSET").prop('checked',true).iCheck('update');
        	$("#BERAT").val("");
        	$("#PANJANG").val("");
        	$("#LEBAR").val("");
        	$("#TINGGI").val("");
        	$("#DESKRIPSI").val("");
        	$("#KATEGORI").val("");
		    $("#checkbox-variation").show();
        	
        	//NONVARIAN
        	$("#IDBARANGINDUK").val("");
        	$("#KODEBARANGINDUK").val("");
        	$("#HARGAJUALINDUK").val("");
        	$("#HARGABELIINDUK").val("");
        	$("#SATUANINDUK").val("");
        	$("#BARCODEINDUK").val("");
        	$("#SKUSHOPEEINDUK").val("");
        	$("#SKUTIKTOKINDUK").val("");
        	$("#SKULAZADAINDUK").val("");
        	$("#SKUTOKPEDINDUK").val("");
        	$("#SKUGOJEKINDUK").val("");
        	$("#SKUGRABINDUK").val("");
	        $("#STATUSINDUK").prop('checked',true).iCheck('update');
        	
            $("#FORMDATAINDUK").hide();
            $("#FORMDATAVARIAN").show();
            $("#DIVGAMBARVARIANSHOPEE").show();
	        setGambarProdukMaster();
	        setGambarVarianMaster();
    
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

function ubahHeader(row){
     $("#btn_simpan").show();
     indukBarangShopee = row.IDINDUKBARANGSHOPEE;
     indukBarangLazada = row.IDINDUKBARANGLAZADA;
     $("#FORMDATAINDUK").hide();
     $("#FORMDATAVARIAN").hide();
     $('#checkbox-variation').hide();
     get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.UBAH==1) {
			$("#mode").val('ubah');
			
			//pindah tab & ganti judul tab
			$('.nav-tabs a[href="#tab_form"]').tab('show');
			$('.nav-tabs a[href="#tab_form"]').html('Ubah');
			
			$("#dataGridVarian").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian/'+encodeURIComponent(row.KATEGORI));
		    $("#dataGridVarian").DataTable().ajax.reload();
        	$("#BERAT").val(row.BERAT);
        	$("#PANJANG").val(row.PANJANG);
        	$("#LEBAR").val(row.LEBAR);
        	$("#TINGGI").val(row.TINGGI);
        	$("#DESKRIPSI").val(row.DESKRIPSI.replaceAll("\\R\\N","\\r\\n").replaceAll("???? ",""));
        	$("#KATEGORIONLINE").val(row.KATEGORIONLINE);
        	$("#KATEGORI").val(row.KATEGORI);
        	
        	//NONVARIAN
        	$("#IDBARANGINDUK").val(row.IDBARANG);
        	$("#KODEBARANGINDUK").val(row.KODEBARANG);
        	$("#HARGAJUALINDUK").val(row.HARGAJUAL);
        	$("#HARGABELIINDUK").val(row.HARGABELI);
        	$("#SATUANINDUK").val(row.SATUAN);
        	$("#BARCODEINDUK").val(row.BARCODE);
        	$("#SKUSHOPEEINDUK").val(row.SKUSHOPEE);
        	$("#SKUTIKTOKINDUK").val(row.SKUTIKTOK);
        	$("#SKULAZADAINDUK").val(row.SKULAZADA);
        	$("#SKUTOKPEDINDUK").val(row.SKUTOKPED);
        	$("#SKUGOJEKINDUK").val(row.SKUGOJEK);
        	$("#SKUGRABINDUK").val(row.SKUGRAB);
	        $("#STATUSINDUK").prop('checked',(row.STATUS == 1 ? true : false)).iCheck('update');
        	
        	dataLama = row;
        	
        	dataVarianLama = [];
        	dataGambar = [
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	{
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
        	];
        	dataGambarVarian = [
        	    {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
            	 {
            	    'ID'    : '',
            	    'NAMA'  : '',
            	    'URL'   : ''
            	},
        	
        	];
    		setTimeout(function() {
                
            $('#dataGridVarian').DataTable().rows().every(function () {
                var rowData = this.data();
                dataVarianLama.push(rowData);
            })
            
            if(dataVarianLama.length > 0)
            {
                $("#VARIANSET").prop('checked',true).iCheck('update');
                $("#FORMDATAINDUK").hide();
                $("#FORMDATAVARIAN").show();
                $("#DIVGAMBARVARIANSHOPEE").show();
                setGambarVarianMaster();
            }
            else
            {
                $("#VARIANSET").prop('checked',false).iCheck('update');
                $("#FORMDATAINDUK").show();
                $("#FORMDATAVARIAN").hide();
                $("#DIVGAMBARVARIANSHOPEE").hide();
            }
	        setGambarProdukMaster();
	        
	        if($("#IDBARANGINDUK").val() != 0)
	        {
	            $.ajax({
                	type    : 'POST',
                	url     : base_url+'Master/Data/Barang/getGambarBarang/',
                	data    : {idbarang: $("#IDBARANGINDUK").val()},
                	dataType: 'json',
                	success : function(msg){
                	    
                	    var imageProduk = msg.dataInduk;
                    	//GAMBAR PRODUK
                    	for(var y = 0 ; y < imageProduk.length ; y++)
                    	{
                    	   // $("#file-input-"+y).val("-");
                    	    $("#format-input-"+y).val('GAMBAR');
                    	    $("#index-input-"+y).val(y);
                    	    $("#src-input-"+y).val(imageProduk[y].URL);
                    	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                    	    $("#id-input-"+y).val(imageProduk[y].ID);
                    	    $("#preview-image-"+y).attr("src",imageProduk[y].URL);
                    	   
                        	$("#ubahGambarProduk-"+y).show();
                        	$("#hapusGambarProduk-"+y).show();
                        	
                        	dataGambar[y] = {
                               'ID'   : $("#id-input-"+y).val(),
                               'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                               'URL'  : $("#preview-image-"+y).attr("src"),
                            };
                    	    
                    	}
                    	
                	    var imageVarian = msg.dataGambarVarian;
                	    for(var y = 0 ; y < imageVarian.length ; y++)
                    	{
                    	    dataGambarVarian[y] = {
                               'ID'   : '',
                               'NAMA' : '',
                               'URL'  : '',
                            };
                                                                   
                    	    for(var z = 0 ; z < imageVarian.length ; z++)
                    	    {
                    	        if("Gambar Varian "+imageVarian[z].NAMA == $("#keterangan-input-varian-"+y).val())
                    	        {
                            	    // $("#file-input-varian-"+y).val("-");
                                    $("#format-input-varian-"+y).val('GAMBAR');
                                    $("#index-input-varian-"+y).val(y);
                                    $("#src-input-varian-"+y).val(imageVarian[z].URL);
                                    $("#id-input-varian-"+y).val(imageVarian[z].ID);
                                    $("#preview-image-varian-"+y).attr("src",imageVarian[z].URL);
                                    
                                    $("#ubahGambarVarian"+y).show();
                            	    $("#hapusGambarVarian"+y).show();
                            	    dataGambarVarian[y] = {
                                       'ID'   : $("#id-input-varian-"+y).val(),
                                       'NAMA' : imageVarian[z].NAMA,
                                       'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                    };
                    	        }
                    	    }
                    	}
                    	
                    	if(imageProduk.length == 0 && imageVarian.length == 0 && indukBarangShopee != 0)
                    	{
                    	       
                	        $.ajax({
                            	type    : 'POST',
                            	url     : base_url+'Shopee/getDataBarangdanVarian/',
                            	data    : {idindukbarangshopee: row.IDINDUKBARANGSHOPEE},
                            	dataType: 'json',
                            	success : function(msg){
                            	    
                            	    var imageProduk = msg.dataInduk.image;
                                	//GAMBAR PRODUK
                                	for(var y = 0 ; y < imageProduk.image_url_list.length ; y++)
                                	{
                                	   // $("#file-input-"+y).val("-");
                                	    $("#format-input-"+y).val('GAMBAR');
                                	    $("#index-input-"+y).val(y);
                                	    $("#src-input-"+y).val(imageProduk.image_url_list[y]);
                                	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                                	    $("#id-input-"+y).val(imageProduk.image_id_list[y]);
                                	    $("#preview-image-"+y).attr("src",imageProduk.image_url_list[y]);
                                	   
                                    	$("#ubahGambarProduk-"+y).show();
                                    	$("#hapusGambarProduk-"+y).show();
                                    	
                                    	dataGambar[y] = {
                                           'ID'   : $("#id-input-"+y).val(),
                                           'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                                           'URL'  : $("#preview-image-"+y).attr("src"),
                                        };
                                	    
                                	}
                                	
                            	    var imageVarian = msg.dataGambarVarian;
                            	    for(var y = 0 ; y < imageVarian.length ; y++)
                                	{
                                	    dataGambarVarian[y] = {
                                           'ID'   : '',
                                           'NAMA' : '',
                                           'URL'  : '',
                                        };
                                                                               
                                	    for(var z = 0 ; z < imageVarian.length ; z++)
                                	    {
                                	        if("Gambar Varian "+imageVarian[z].WARNA == $("#keterangan-input-varian-"+y).val())
                                	        {
                                        	    // $("#file-input-varian-"+y).val("-");
                                                $("#format-input-varian-"+y).val('GAMBAR');
                                                $("#index-input-varian-"+y).val(y);
                                                $("#src-input-varian-"+y).val(imageVarian[z].IMAGEURL);
                                                $("#id-input-varian-"+y).val(imageVarian[z].IMAGEID);
                                                $("#preview-image-varian-"+y).attr("src",imageVarian[z].IMAGEURL);
                                                $("#ubahGambarVarian"+y).show();
                                        	    $("#hapusGambarVarian"+y).show();
                                        	    dataGambarVarian[y] = {
                                                   'ID'   : $("#id-input-varian-"+y).val(),
                                                   'NAMA' : imageVarian[z].WARNA,
                                                   'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                                };
                                	        }
                                	    }
                                	}
                            	}
                            	    
                            });
                    	}
                	}
                	    
                });
	        }
        }, 1000);
    	
			
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



function simpanHeader(jenis = '') {
    
    var tableVarian = $('#dataGridVarian').DataTable();
    tableVarian.rows().eq(0).each(function (index) {
         var row = tableVarian.row(index).data();
         
         if(dataLama.KATEGORIONLINE != $("#KATEGORIONLINE").val() || dataLama.KATEGORI != $("#KATEGORI").val() || dataLama.DESKRIPSI != $("#DESKRIPSI").val() || dataLama.BERAT != $("#BERAT").val() || dataLama.PANJANG != $("#PANJANG").val() || dataLama.LEBAR != $("#LEBAR").val() || dataLama.TINGGI != $("#TINGGI").val())
         {
             if(row.MODE == "")
             {
                 row.MODE = "ubah";
             }
         }
    });
    
    var arrGambar = [];
    for(var g = 0 ; g < dataGambar.length;g++)
    {
        if(dataGambar[g].ID != "")
        {
            arrGambar.push(dataGambar[g]);
        }
    }
    $("#datagambar").val(JSON.stringify(arrGambar)); 
    
    var arrGambarVarian = [];
    for(var gv = 0 ; gv < dataGambarVarian.length;gv++)
    {
        if(dataGambarVarian[gv].ID != "")
        {
            arrGambarVarian.push(dataGambarVarian[gv]);
        }
    }
    $("#datagambarvarian").val(JSON.stringify(arrGambarVarian)); 
    
    var varian = $('#dataGridVarian').DataTable().rows().data().toArray();
    var warna = [];
    for(var y = 0 ; y < varian.length; y++)
    {
        var tempWarna = varian[y].WARNA;
    	
    	adaWarna = false;
    	for(var w = 0 ; w < warna.length; w++)
    	{
    	    if(warna[w] == tempWarna)
    	    {
    	        adaWarna = true;
    	    }
    	}
    	
    	if(!adaWarna)
    	{
    	    warna.push(tempWarna);
    	}
    }

	if(($('#VARIANSET').prop("checked") && tableVarian.rows().data().length == 0) || ($("#KATEGORIONLINE").val() == "" || $("#KATEGORI").val() == "" || $("#DESKRIPSI").val() == "" || $("#BERAT").val() == "" || $("#BERAT").val() == "0")){
        Swal.fire({ 
        	title            : "Terdapat Data Produk yang belum diisi",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if(!$('#VARIANSET').prop("checked") && ($("#HARGAJUALINDUK").val() == 0 || $("#HARGAJUALINDUK").val() == "" || $("#HARGABELIINDUK").val() == 0 || $("#HARGABELIINDUK").val() == "" || $("#SATUANINDUK").val() == "" || $("#SKUSHOPEEINDUK").val() == "" || $("#SKUTIKTOKINDUK").val() == ""|| $("#SKULAZADAINDUK").val() == "")){
         Swal.fire({ 
        	title            : "Terdapat Data Produk yang belum diisi",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if($("#KATEGORI").val().length < 5){
         Swal.fire({ 
        	title            : "Panjang Nama Produk min 5",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if(arrGambar.length < 2){
         Swal.fire({ 
        	title            : "Isi Gambar Min 2",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if(arrGambarVarian.length != warna.length){
         Swal.fire({ 
        	title            : "Gambar Varian harus diisi semua",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
    	var dataVarian = [];
        if(!$("#VARIANSET").prop('checked'))
        {
            var status = 0;
            if($("#STATUSINDUK").prop('checked'))
            {
                status = 1;
            }
        
            var data = {
                'IDBARANG'          : $("#IDBARANGINDUK").val(),     
                'KODEBARANG'        : $("#KODEBARANGINDUK").val(),      
                'NAMABARANG'        : $("#KATEGORI").val(),        
                'HARGAJUAL'         : $("#HARGAJUALINDUK").val(), 
                'HARGABELI'         : $("#HARGABELIINDUK").val(),  
                'SKUGRAB'           : $("#SKUGRABINDUK").val(),  
                'SKUGOJEK'          : $("#SKUGOJEKINDUK").val(),  
                'SKUSHOPEE'         : $("#SKUSHOPEEINDUK").val(),  
                'SKUTOKPED'         : $("#SKUTOKPEDINDUK").val(),  
                'SKUTIKTOK'         : $("#SKUTIKTOKINDUK").val(),  
                'SKULAZADA'         : $("#SKULAZADAINDUK").val(),  
                'SATUAN'            : $("#SATUANINDUK").val(),   
                'BARCODE'           : $("#BARCODEINDUK").val(), 
                'STATUS'            : status,   
                'MODE'              : $("#mode").val()
            };
            
		    dataVarian.push(data);
        }
        else
        {
            
        	tableVarian.rows().eq(0).each(function (index) {
        	    if(tableVarian.row(index).data().MODE == "")
        	    {
        	        tableVarian.row(index).data().MODE = "ubah";
        	    }
        	    
                if (tableVarian.row(index).data().MODE != "") {
                    var row = tableVarian.row(index).data();
                    dataVarian.push(row);
                }
            });
        }
        
        $("#datavarian").val(JSON.stringify(dataVarian));
        var idbaranghapus = [];
        //DATA YANG DIHAPUS
        for(var x = 0 ; x < dataVarianLama.length;x++)
        {
            var hapus = true;
            for(var y = 0 ; y < dataVarian.length ; y++)
            {
               if(dataVarianLama[x].IDBARANG == dataVarian[y].IDBARANG)
               {
                   hapus = false;
               }
            }
            
            if(hapus)
            {
                idbaranghapus.push(dataVarianLama[x].IDBARANG);
            }
        }
        
        $("#datavarianhapus").val(JSON.stringify(idbaranghapus));
        
        $.ajax({
            type      : 'POST',
            url       : base_url+'Master/Data/Barang/simpanAll',
            data      : $('#form_input :input').serialize(),
            dataType  : 'json',
            beforeSend: function (){
                //$.messager.progress();
            },
            success: function(msg){
                if (msg.success) {
                    
                    if(jenis != '')
                    {
                        $("#mode").val("ubah");
                        loadingMaster();
                        //JIKA DIA INDUK
                        if(!$("#VARIANSET").prop('checked'))
                        {
                             $.ajax({
                                type      : 'POST',
                                url       : base_url + 'Master/Data/Barang/getDataInduk/' + encodeURIComponent($("#KATEGORI").val()),
                                dataType  : 'json',
                                beforeSend: function (){
                                    //$.messager.progress();
                                },
                                success: function(msg){
                    
                                    //IDBARANGINDUK
                                    $("#IDBARANGINDUK").val(msg.rows[0].IDBARANG);
                                    $("#KODEBARANGINDUK").val(msg.rows[0].KODEBARANG);
                                    if(jenis == 'SHOPEE')
                                    {
                                        indukBarangShopee = msg.rows[0].IDINDUKBARANGSHOPEE;
                                        simpanHeaderShopee();
                                    }
                                    else if(jenis == 'LAZADA')
                                    {
                                        indukBarangLazada = msg.rows[0].IDINDUKBARANGLAZADA;
                                        simpanHeaderLazada();
                                    }
                                }
                                 
                             });
                        }
                        else
                        {
                            $("#dataGridVarian").DataTable().ajax.url(base_url+'Master/Data/Barang/getDataVarian/'+encodeURIComponent($("#KATEGORI").val()));
                            $("#dataGridVarian").DataTable().ajax.reload();
                            
                            setTimeout(function() {
                                var varian = $('#dataGridVarian').DataTable().rows().data().toArray();
                                
                                $("#IDBARANGINDUK").val(varian[0].IDBARANG);
                                if(jenis == 'SHOPEE')
                                {
                                    indukBarangShopee = varian[0].IDINDUKBARANGSHOPEE;
                                    simpanHeaderShopee();
                                }
                                else if(jenis == 'LAZADA')
                                {
                                    indukBarangLazada = varian[0].IDINDUKBARANGLAZADA;
                                    simpanHeaderLazada();
                                }
                            },2000);
                        }
                    }
                    else
                    {
                        Swal.close();
                        Swal.fire({
                            title            : 'Simpan Data Sukses',
                            type             : 'success',
                            showConfirmButton: false,
                            timer            : 1500
                        });
                    
                        $("#dataGrid").DataTable().ajax.reload();
                        tambahHeader();
                        $('.nav-tabs a[href="#tab_grid"]').tab('show');
                    }
                    
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

async function simpanHeaderShopee(){
    
    var arrLogistics = [];
    var aktif = 1;
    
    // Helper: convert jQuery ajax to Promise
    function ajaxPost(url, data = {}) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: resolve,
                error: reject
            });
        });
    }
    
    
    if(indukBarangShopee != 0)
    {
        var msg = await ajaxPost(base_url + 'Shopee/getDataBarangdanVarian/', { idindukbarangshopee: indukBarangShopee });
       	var logistic = msg.dataInduk.logistic_info;
        for(var x = 0 ; x < logistic.length; x++)
        {
            arrLogistics.push({
                 'enabled'     : logistic[x].enabled,
                 'logistic_id' : logistic[x].logistic_id 
             });
        }
        
        if(msg.dataInduk.item_status == "UNLIST")
        { 
            aktif = 1;
        }
       
    }
    else
    {
        var msg = await ajaxPost(base_url + 'Shopee/getPengiriman/');
        for(var x = 0 ; x < msg.rows.length ; x++)
        {
        	  arrLogistics.push({
               'enabled'     : true,
               'logistic_id' : msg.rows[x].IDPENGIRIMAN 
            });
        }
    }
    
    var arrAttribut = [];
    var arrImage = [];
        
    var sizeChart = "";
    var sizeChartID = "";
    var sizeChartTipe = "GAMBAR";
    
    for(var g = 0 ; g < dataGambar.length;g++)
    {
        if(dataGambar[g].ID != "")
        {
            arrImage.push(dataGambar[g].ID);
            sizeChartID = dataGambar[g].ID;
            sizeChart = dataGambar[g].URL;
        }
    }
    
    var arrImageVarian = [];
    for(var gv = 0 ; gv < dataGambarVarian.length;gv++)
    {
        if(dataGambarVarian[gv].ID != "")
        {
            arrImageVarian.push(dataGambarVarian[gv].ID);
        }
    }
    
    var varian = $('#dataGridVarian').DataTable().rows().data().toArray();
    var warna = [];
    var ukuran = [];
    for(var y = 0 ; y < varian.length; y++)
    {
        var tempWarna = varian[y].WARNA;
        var tempUk = varian[y].SIZE;
    	
    	adaWarna = false;
    	for(var w = 0 ; w < warna.length; w++)
    	{
    	    if(warna[w] == tempWarna)
    	    {
    	        adaWarna = true;
    	    }
    	}
    	
    	if(!adaWarna)
    	{
    	    warna.push(tempWarna);
    	}
    	
    	adaUkuran = false;
    	for(var u = 0 ; u < ukuran.length; u++)
    	{
    	    if(ukuran[u] == tempUk)
    	    {
    	        adaUkuran = true;
    	    }
    	}
    	
    	if(!adaUkuran)
    	{
    	    ukuran.push(tempUk);
    	}
    }
    
    var dataVarianSimpan = [];
    var dataVarianMaster = [];
    
    const resMaster = await ajaxPost(base_url + 'Master/Data/Barang/getDataVarian/' + encodeURIComponent($("#KATEGORI").val()));
    dataVarianMaster = resMaster.rows;
    dataVarianSimpan = [...dataVarianMaster];
    
    //DATA KLO BLM ADA PAKE YANG DIBAWAH INI GPP, TAPI KLO DAH DISIMPEN DI DB DLU. JADI KEDOBELAN
    
    if(indukBarangShopee != 0)
    {
        const msg = await ajaxPost(base_url + 'Shopee/getDataBarang/', { idindukbarangshopee: indukBarangShopee });

        for(var dv = 0 ; dv < dataVarianMaster.length; dv++)
        {
            var rowData = dataVarianMaster[dv];
            var ada = false;
            for(var x = 0 ; x < msg.dataVarian.length; x++)
            {
                if (rowData.IDBARANGSHOPEE == msg.dataVarian[x].ID) {
                    ada = true;
                }
            }
            
            if(!ada)
            {  
               // Update the NAMABARANG field
               rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:yellow; text-align:center; padding:5px; width:100px;'>Varian Baru</i>";
               rowData.MODE = "BARU";
               dataVarianSimpan[dv] = rowData;
            }
        }
        
        //CEK ADA YANG BERUBAH
        for(var dv = 0 ; dv < dataVarianMaster.length; dv++)
        {
            var rowData = dataVarianMaster[dv];
            var ada = false;
            for(var x = 0 ; x < msg.dataVarian.length; x++)
            {
                if (rowData.IDBARANGSHOPEE == msg.dataVarian[x].ID) {
                    rowData.MODE = "";
                    if(rowData.HARGAJUAL != msg.dataVarian[x].HARGA)
                    {
                    // Update the NAMABARANG field
                       rowData.IDBARANG   = msg.dataVarian[x].ID,
                       rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:lightblue; text-align:center; padding:5px; width:100px;'>Harga Diubah</i>";
                       rowData.MODE += "UBAH HARGA";
                       dataVarianSimpan[dv] = rowData;
                    }
                    if(rowData.SKUSHOPEE != msg.dataVarian[x].SKU)
                    {
                        if(rowData.MODE != "")
                        {
                            rowData.MODE += "|";
                        }
                    // Update the NAMABARANG field
                       rowData.IDBARANG   = msg.dataVarian[x].ID,
                       rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:lightblue; text-align:center; padding:5px; width:100px;'>SKU Diubah</i>";
                       rowData.MODE += "UBAH SKU";
                       // Set the updated data back into the row
                       dataVarianSimpan[dv] = rowData;
                    }
                }
            }
        }
        
        // CEK ADA YANG DIHAPUS APA NDAK
        for(var x = 0 ; x < msg.dataVarian.length; x++)
        {
            ada = false;
            for(var dv = 0 ; dv < dataVarianMaster.length; dv++)
            {
                var rowData = dataVarianMaster[dv];
        	    
                if (rowData.IDBARANGSHOPEE == msg.dataVarian[x].ID) {
                    ada = true;
                }
            }
            
            if(!ada)
            {
               var nama = msg.dataVarian[x].NAMA.replaceAll(',',' <span>|</span> SIZE ')+" <i class='pull-right' style='background:#FF5959; text-align:center; padding:5px; width:100px; color:white;'>Varian Dihapus</i>";
    
               // Update the NAMABARANG field
               var newRow = {
                  IDBARANG   : msg.dataVarian[x].ID,
                  NAMABARANG : nama,
                  HARGAJUAL : msg.dataVarian[x].HARGA,
                  SIZE : msg.dataVarian[x].SIZE,
                  WARNA : msg.dataVarian[x].WARNA,
                  HARGAJUAL : msg.dataVarian[x].HARGA,
                  SKUSHOPEE : msg.dataVarian[x].SKU,
                  MODE : 'HAPUS'
               };
               
               dataVarianSimpan.push(newRow);
            }
        }
    }

    $.ajax({
    	type    : 'POST',
    	url     : base_url+'Shopee/setBarang/',
    	data    : {
    	   "IDBARANG"       : indukBarangShopee,
    	   "KATEGORI"       : kategoriBarangShopee[document.getElementById('KATEGORIONLINE').selectedIndex], 
    	   "NAMA"           : $("#KATEGORI").val(), 
    	   "DESKRIPSI"      : $("#DESKRIPSI").val(), 
    	   "BERAT"          : $("#BERAT").val(), 
    	   "PANJANG"        : $("#PANJANG").val(), 
    	   "LEBAR"          : $("#LEBAR").val(), 
    	   "TINGGI"         : $("#TINGGI").val(), 
    	   "HARGA"          : $("#HARGAJUALINDUK").val(),      
    	   "SKU"            : $("#SKUSHOPEEINDUK").val(), 
    	   "AKTIF"          : aktif,
    	   "VARIAN"         : JSON.stringify(dataVarianSimpan),
    	   "WARNA"          : JSON.stringify(warna),
    	   "UKURAN"         : JSON.stringify(ukuran),
    	   "ATTRIBUT"       : JSON.stringify(arrAttribut),
    	   "GAMBARPRODUK"   : JSON.stringify(arrImage),
    	   "GAMBARVARIAN"   : JSON.stringify(arrImageVarian),
    	   "SIZECHART"      : sizeChart,
    	   "SIZECHARTID"    : sizeChartID,
    	   "SIZECHARTTIPE"  : sizeChartTipe,
    	   "LOGISTICS"      : JSON.stringify(arrLogistics),
    	},
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
                $("#dataGrid").DataTable().ajax.reload();
                // tambahHeader();
                // $('.nav-tabs a[href="#tab_grid"]').tab('show');
            }
    	}
    }); 
}

async function simpanHeaderLazada(){
    var aktif = 1;
    
    // Helper: convert jQuery ajax to Promise
    function ajaxPost(url, data = {}) {
        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                dataType: 'json',
                success: resolve,
                error: reject
            });
        });
    }
    
    var varian = $('#dataGridVarian').DataTable().rows().data().toArray();
    var warna = [];
    var ukuran = [];
    for(var y = 0 ; y < varian.length; y++)
    {
        var tempWarna = varian[y].WARNA;
        var tempUk = varian[y].SIZE;
    	
    	adaWarna = false;
    	for(var w = 0 ; w < warna.length; w++)
    	{
    	    if(warna[w] == tempWarna)
    	    {
    	        adaWarna = true;
    	    }
    	}
    	
    	if(!adaWarna)
    	{
    	    warna.push(tempWarna);
    	}
    	
    	adaUkuran = false;
    	for(var u = 0 ; u < ukuran.length; u++)
    	{
    	    if(ukuran[u] == tempUk)
    	    {
    	        adaUkuran = true;
    	    }
    	}
    	
    	if(!adaUkuran)
    	{
    	    ukuran.push(tempUk);
    	}
    }
    
    
    var arrImage = [];
    var arrImageID = [];
    var arrImageBukanLazada = [];
    for(var y = 0 ; y < 7 ;y++)
    {
        //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
        if($("#src-input-"+y).val() != "")
        {
            arrImageID.push($('#id-input-'+y).val());
            arrImage.push($('#src-input-'+y).val());
            if(!$("#src-input-"+y).val().includes('https://sg-test-11.slatic.net')){
                arrImageBukanLazada.push(
                    {
                        "id" : $("#id-input-"+y).val(),
                        "url" : $("#src-input-"+y).val(),
                        "url-baru" : ""
                    }
                )
            }
        }
    }
    
    var arrImageVarian = [];
    var arrImageIDVarian = [];
    for(var y = 0 ; y < warna.length; y++)
    {
        //CEK KALAU GAMBAR BELUM ADA NDAK USA DIKIRIM
        if($("#src-input-varian-"+y).val() != "")
        {
            arrImageIDVarian.push($('#id-input-varian-'+y).val());
            arrImageVarian.push($('#src-input-varian-'+y).val());
            if(!$("#src-input-"+y).val().includes('https://sg-test-11.slatic.net')){
                arrImageBukanLazada.push(
                    {
                        "id" : $('#id-input-varian-'+y).val(),
                        "url" :$("#src-input-varian-"+y).val(),
                        "url-baru" : ""
                    }
                )
            }
        }
    }
    
    let ajax1;

    if (arrImageBukanLazada.length > 0) {
         ajax1 = $.ajax({
             type    : 'POST',
             url     : base_url+'Lazada/changeLocalUrl/',
             data    : {
                 "url" : JSON.stringify(arrImageBukanLazada),
             },
             dataType: 'json'
         });
     } else {
         // kalau kosong langsung resolve dummy deferred
         ajax1 = $.Deferred().resolve().promise();
     }

     $.when(ajax1).done(async function(msg){
         var error = false;
         if(msg)
         {
             if (msg.success) {
                 for(var x = 0 ; x < arrImageID.length ; x++)
                 {
                     for(var y = 0 ; y < msg.data.length ; y++)
                     {
                         if(arrImageID[x] == msg.data[y]['id'])
                         {
                             arrImage[x] = msg.data[y]['url-baru']
                         }
                     }
                 }
                 for(var x = 0 ; x < arrImageIDVarian.length ; x++)
                 {
                     for(var y = 0 ; y < msg.data.length ; y++)
                     {
                         if(arrImageIDVarian[x] == msg.data[y]['id'])
                         {
                             arrImageVarian[x] = msg.data[y]['url-baru']
                         } 
                     }
                 }
             }
             else
             {
                 error = true;
                 Swal.close();	
                //  Swal.fire({
                //      title            : msg.msg,
                //      type             : (msg.success?'success':'error'),
                //      showConfirmButton: false,
                //      timer            : 2000
                //  });
                simpanHeaderLazada();
             }
         }
         
         if(!error)
         {
    
             var dataVarianSimpan = [];
             var dataVarianMaster = [];
             
             const resMaster = await ajaxPost(base_url + 'Master/Data/Barang/getDataVarian/' + encodeURIComponent($("#KATEGORI").val()));
             dataVarianMaster = resMaster.rows;
             dataVarianSimpan = [...dataVarianMaster];
             
             //DATA KLO BLM ADA PAKE YANG DIBAWAH INI GPP, TAPI KLO DAH DISIMPEN DI DB DLU. JADI KEDOBELAN
             
             if(indukBarangLazada != 0)
             {
                 const msg = await ajaxPost(base_url + 'Lazada/getDataBarang/', { idindukbaranglazada: indukBarangLazada });
               
                 for(var dv = 0 ; dv < dataVarianMaster.length; dv++)
                 {
                     var rowData = dataVarianMaster[dv];
                     var ada = false;
                     for(var x = 0 ; x < msg.dataVarian.length; x++)
                     {
                         if (rowData.IDBARANGLAZADA == msg.dataVarian[x].ID) {
                             ada = true;
                         }
                     }
                     
                     if(!ada)
                     {  
                        // Update the NAMABARANG field
                        rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:yellow; text-align:center; padding:5px; width:100px;'>Varian Baru</i>";
                        rowData.MODE = "BARU";
                        dataVarianSimpan[dv] = rowData;
                     }
                 }
                 
                 //CEK ADA YANG BERUBAH
                 for(var dv = 0 ; dv < dataVarianMaster.length; dv++)
                 {
                     var rowData = dataVarianMaster[dv];
                     var ada = false;
                     for(var x = 0 ; x < msg.dataVarian.length; x++)
                     {
                         if (rowData.IDBARANGLAZADA == msg.dataVarian[x].ID) {
                             rowData.MODE = "";
                             if(rowData.HARGAJUAL != msg.dataVarian[x].HARGA)
                             {
                             // Update the NAMABARANG field
                                rowData.IDBARANG   = msg.dataVarian[x].ID,
                                rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:lightblue; text-align:center; padding:5px; width:100px;'>Harga Diubah</i>";
                                rowData.MODE += "UBAH HARGA";
                                dataVarianSimpan[dv] = rowData;
                             }
                             if(rowData.SKULAZADA != msg.dataVarian[x].SKU)
                             {
                                 if(rowData.MODE != "")
                                 {
                                     rowData.MODE += "|";
                                 }
                             // Update the NAMABARANG field
                                rowData.IDBARANG   = msg.dataVarian[x].ID,
                                rowData.NAMABARANG = rowData.NAMABARANG+" <i class='pull-right'  style='background:lightblue; text-align:center; padding:5px; width:100px;'>SKU Diubah</i>";
                                rowData.MODE += "UBAH SKU";
                                // Set the updated data back into the row
                                dataVarianSimpan[dv] = rowData;
                             }
                         }
                     }
                 }
                 
                 // CEK ADA YANG DIHAPUS APA NDAK
                 for(var x = 0 ; x < msg.dataVarian.length; x++)
                 {
                     ada = false;
                     for(var dv = 0 ; dv < dataVarianMaster.length; dv++)
                     {
                         var rowData = dataVarianMaster[dv];
                 	    
                         if (rowData.IDBARANGLAZADA == msg.dataVarian[x].ID) {
                             ada = true;
                         }
                     }
                     
                     if(!ada)
                     {
                     var nama = msg.dataVarian[x].NAMA.replaceAll(' | SIZE ',' <span>|</span> SIZE ')+" <i class='pull-right' style='background:#FF5959; text-align:center; padding:5px; width:100px; color:white;'>Varian Dihapus</i>";
                          
                        // Update the NAMABARANG field
                        var newRow = {
                           IDBARANG   : msg.dataVarian[x].ID,
                           NAMABARANG : nama,
                           HARGAJUAL : msg.dataVarian[x].HARGA,
                           SIZE : msg.dataVarian[x].SIZE,
                           WARNA : msg.dataVarian[x].WARNA,
                           HARGAJUAL : msg.dataVarian[x].HARGA,
                           SKUSHOPEE : msg.dataVarian[x].SKU,
                           MODE : 'HAPUS'
                        };
                        
                        dataVarianSimpan.push(newRow);
                     }
                 }
             }
             
             $.ajax({
                type      : 'POST',
                url       : base_url + 'Lazada/getDataBarangdanVarian',
                data      : {idindukbaranglazada : indukBarangLazada},
                dataType  : 'json',
                beforeSend: function (){
                    //$.messager.progress();
                },
                success: function(msg){
                    if(indukBarangLazada != 0)
                    {
                        if(msg.status == 'Active')
                        {
                            aktif = 1;
                        }
                        else
                        {
                            aktif = 0;
                        }
                    }
                    
                     $.ajax({
                     	type    : 'POST',
                     	url     : base_url+'Lazada/setBarang/',
                     	data    : {
                     	   "IDBARANG"       : indukBarangLazada,
                     	   "KATEGORI"       : kategoriBarangLazada[document.getElementById('KATEGORIONLINE').selectedIndex], 
                     	   "NAMA"           : $("#KATEGORI").val(), 
                     	   "DESKRIPSI"      : $("#DESKRIPSI").val(), 
                     	   "BERAT"          : $("#BERAT").val(), 
                     	   "PANJANG"        : $("#PANJANG").val(), 
                     	   "LEBAR"          : $("#LEBAR").val(), 
                     	   "TINGGI"         : $("#TINGGI").val(), 
                     	   "HARGA"          : $("#HARGAJUALINDUK").val(),      
                     	   "SKU"            : $("#SKULAZADAINDUK").val(), 
                     	   "AKTIF"          : aktif,
                     	   "VARIAN"         : JSON.stringify(dataVarianSimpan),
                     	   "WARNA"          : JSON.stringify(warna),
                     	   "UKURAN"         : JSON.stringify(ukuran),
                     	   "GAMBARPRODUK"   : JSON.stringify(arrImage),
                     	   "GAMBARVARIAN"   : JSON.stringify(arrImageVarian),
                     	},
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
                                 $("#dataGrid").DataTable().ajax.reload();
                                 // tambahHeader();
                                 // $('.nav-tabs a[href="#tab_grid"]').tab('show');
                             }
                     	}
                     }); 
                }
             });
         }
    });
}

function hapusHeader(row){
    get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.HAPUS==1) {
		     if (row) {
    		     Swal.fire({
                		title: 'Anda Yakin Akan Menghapus Barang '+row.KATEGORI+' ?',
                		html:row.IDINDUKBARANGSHOPEE != 0?'<p style="font-size:12pt; color:red; font-weight:w600;">*Barang ini sudah terhubung dengan marketplace, maka data pada marketplace akan dihapus juga.</p>':'',
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
                    					url     : base_url+"Master/Data/Barang/hapusAll",
                    					data    : "kategori="+row.KATEGORI,
                    					cache   : false,
                    					success : function(msg){
                    						if (msg.success) {
                    							Swal.fire({
                    								title            : 'Barang dengan nama '+row.KATEGORI+' telah dihapus',
                    								type             : 'success',
                    								showConfirmButton: false,
                    								timer            : 1500
                    							});
                			        
                            			        if(row.IDINDUKBARANGSHOPEE != 0)
                            			        {
                            			             loadingMaster();
                                			         $.ajax({
                                                    	type    : 'POST',
                                                    	url     : base_url+'Shopee/removeBarang/',
                                                    	data    : {idindukbarangshopee: row.IDINDUKBARANGSHOPEE},
                                                    	dataType: 'json',
                                                    	success : function(msg){
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
                            			        
                            			        if(row.IDINDUKBARANGLAZADA != 0)
                            			        {
                            			             loadingMaster();
                            			             
                                                      $.ajax({
                                                                type      : 'POST',
                                                                url       : base_url + 'Lazada/getDataBarangdanVarian',
                                                                data      : {idindukbaranglazada : row.IDINDUKBARANGLAZADA},
                                                                dataType  : 'json',
                                                                beforeSend: function (){
                                                                    //$.messager.progress();
                                                                },
                                                                success: function(msg){
                                                                    
                                                			         $.ajax({
                                                                    	type    : 'POST',
                                                                    	url     : base_url+'Lazada/removeBarang/',
                                                                    	data    : {idindukbaranglazada: row.IDINDUKBARANGLAZADA, skulistlazada : JSON.stringify(msg.skus)},
                                                                    	dataType: 'json',
                                                                    	success : function(msg){
                                                                    	    Swal.close();	
                                                                            Swal.fire({
                                                                            	title            :  msg.msg,
                                                                            	type             : (msg.success?'success':'error'),
                                                                            	showConfirmButton: false,
                                                                            	timer            : 2000
                                                                            });
                                                                    	}
                                                            	        
                                                            	     });
                                                     }});
                            			        }
                            			        
                    							$("#dataGrid").DataTable().ajax.reload();
                    						
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

function tambah(){
	//clear form input
    $("#btn_simpan_detail").html('Tambah');
	$("#STATUS").prop('checked',true).iCheck('update');
	$("#IDBARANG").val("");
    $("#KODEBARANG").val("");            
    $("#NAMABARANG").val("");       
    $("#UKURANBARANG").val("");       
    $("#WARNABARANG").val("");   
    $("#BARCODE").val("");
	$("#SATUAN2").val("");
	$("#HARGABELI").val("");
	$("#HARGAJUAL").val("");
	$("#SKUSHOPEE").val("");
	$("#SKUTIKTOK").val("");
	$("#SKULAZADA").val("");
	
}

function tambahMassal(){
    $("#NAMABARANGMASSAL").val("");
    $("#SATUANMASSAL").val("");
    $("#HARGAJUALMASSAL").val("");
    $("#HARGABELIMASSAL").val("");
    $("#PILIHWARNAMASSAL").html("");
    $("#PILIHUKURANMASSAL").html("");
    
    var tableVarian = $('#dataGridVarian').DataTable();
    tableVarian.rows().eq(0).each(function (index) {
         var row = tableVarian.row(index).data();
         $("#NAMABARANGMASSAL").val(row.NAMABARANG.split("LITTLE TWISTY - ")[1].split(" | ")[0]);
         $("#SATUANMASSAL").val(row.SATUAN);
         $("#HARGAJUALMASSAL").val(row.HARGAJUAL);
         $("#HARGABELIMASSAL").val(row.HARGABELI);
        //  $("#PILIHWARNAMASSAL").val(row.NAMABARANG);
        //  $("#PILIHUKURANMASSAL").val(row.NAMABARANG);
    });
}

function simpanMassal(){
    if(!$("#NAMABARANGMASSAL").val().includes("LITTLE TWISTY - "))
    {
        $("#NAMABARANGMASSAL").val("LITTLE TWISTY - "+$("#NAMABARANGMASSAL").val());
    }
    var arrayWarna = [];
    var arrayUkuran = [];
    $('#PILIHWARNAMASSAL option:selected').each(function() {
        arrayWarna.push($(this).text().toUpperCase());
    });
    
    $('#PILIHUKURANMASSAL option:selected').each(function() {
        arrayUkuran.push($(this).text());
    });
    
    if($("#NAMABARANGMASSAL").val() == "" || $("#SATUANMASSAL").val() == "" || $("#HARGAJUALMASSAL").val() == "" || $("#HARGAJUALMASSAL").val() == "0" || $("#HARGABELIMASSAL").val() == "" || $("#HARGABELIMASSAL").val() == "0"){
        Swal.fire({
        	title            : "Terdapat Data Massal yang belum diisi",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if(arrayWarna.length == 0 || arrayUkuran.length == 0)
    {   
        Swal.fire({
        	title            : "Isi Warna dan Ukuran minimal 1",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
        
        var table = $('#dataGridVarian').DataTable(); // Get DataTable instance
         
        var status = 0;
        if($("#STATUS").prop('checked'))
        {
            status = 1;
        }
        
        arrayWarna.forEach(function(warna, index) {
                
            arrayUkuran.forEach(function(ukuran, index) {
                 
                var namabarang = $("#NAMABARANGMASSAL").val()+" | "+warna+" | SIZE "+ukuran;
                var barangMassal = $("#NAMABARANGMASSAL").val().split(" ");
                var SKUBARCODE = ""; 
               
                if(barangMassal.length < 2)
                {
                    SKUBARCODE = "LTW / "+barangMassal[barangMassal.length-1]+" "+warna.substring(0,4)+ukuran; 
                }
                else
                {
                    SKUBARCODE = "LTW / "+barangMassal[barangMassal.length-2]+" "+warna.substring(0,4)+ukuran; 
                }
                
                let randomDecimal = Math.random();
                // Define new row data
                var newRowData = {
                    SKUSHOPEE: SKUBARCODE,
                    SKUTIKTOK: SKUBARCODE,
                    SKULAZADA: SKUBARCODE,
                    IDBARANG: "X"+warna+"X"+$("#UKURANBARANG").val()+"X"+randomDecimal.toString(),
                    WARNA: warna,
                    SIZE: ukuran,
                    BARCODE:SKUBARCODE,
                    KODEBARANG: "",
                    NAMABARANG: namabarang,
                    SATUAN: $("#SATUANMASSAL").val(),
                    HARGAJUAL: $("#HARGAJUALMASSAL").val(),
                    HARGABELI: $("#HARGABELIMASSAL").val(),
                    USERBUAT: 'AUTOGEN',
                    TGLENTRY: 'AUTOGEN',
                    STATUS: status,
                    MODE:"tambah"
                };
            
                table.row.add(newRowData).draw();
            });
        });
        
        setGambarVarianMaster();
    	$("#modal-set-varian").modal('hide');	
    	
    	if($("#IDBARANGINDUK").val() != 0)
        {
            $.ajax({
            	type    : 'POST',
            	url     : base_url+'Master/Data/Barang/getGambarBarang/',
            	data    : {idbarang: $("#IDBARANGINDUK").val()},
            	dataType: 'json',
            	success : function(msg){
            	    
            	    var imageProduk = msg.dataInduk;
                	//GAMBAR PRODUK
                	for(var y = 0 ; y < imageProduk.length ; y++)
                	{
                	   // $("#file-input-"+y).val("-");
                	    $("#format-input-"+y).val('GAMBAR');
                	    $("#index-input-"+y).val(y);
                	    $("#src-input-"+y).val(imageProduk[y].URL);
                	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                	    $("#id-input-"+y).val(imageProduk[y].ID);
                	    $("#preview-image-"+y).attr("src",imageProduk[y].URL);
                	   
                    	$("#ubahGambarProduk-"+y).show();
                    	$("#hapusGambarProduk-"+y).show();
                    	
                    	dataGambar[y] = {
                           'ID'   : $("#id-input-"+y).val(),
                           'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                           'URL'  : $("#preview-image-"+y).attr("src"),
                        };
                	    
                	}
                	
            	    var imageVarian = msg.dataGambarVarian;
            	    for(var y = 0 ; y < imageVarian.length ; y++)
                	{
                	    dataGambarVarian[y] = {
                           'ID'   : '',
                           'NAMA' : '',
                           'URL'  : '',
                        };
                                                               
                	    for(var z = 0 ; z < imageVarian.length ; z++)
                	    {
                	        if("Gambar Varian "+imageVarian[z].NAMA == $("#keterangan-input-varian-"+y).val())
                	        {
                        	    // $("#file-input-varian-"+y).val("-");
                                $("#format-input-varian-"+y).val('GAMBAR');
                                $("#index-input-varian-"+y).val(y);
                                $("#src-input-varian-"+y).val(imageVarian[z].URL);
                                $("#id-input-varian-"+y).val(imageVarian[z].ID);
                                $("#preview-image-varian-"+y).attr("src",imageVarian[z].URL);
                                
                                $("#ubahGambarVarian"+y).show();
                        	    $("#hapusGambarVarian"+y).show();
                        	    dataGambarVarian[y] = {
                                   'ID'   : $("#id-input-varian-"+y).val(),
                                   'NAMA' : imageVarian[z].NAMA,
                                   'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                };
                	        }
                	    }
                	}
                	
                	if(imageProduk.length == 0 && imageVarian.length == 0 && indukBarangShopee != 0)
                	{
                	       
            	        $.ajax({
                        	type    : 'POST',
                        	url     : base_url+'Shopee/getDataBarangdanVarian/',
                        	data    : {idindukbarangshopee: indukBarangShopee},
                        	dataType: 'json',
                        	success : function(msg){
                        	    
                        	    var imageProduk = msg.dataInduk.image;
                            	//GAMBAR PRODUK
                            	for(var y = 0 ; y < imageProduk.image_url_list.length ; y++)
                            	{
                            	   // $("#file-input-"+y).val("-");
                            	    $("#format-input-"+y).val('GAMBAR');
                            	    $("#index-input-"+y).val(y);
                            	    $("#src-input-"+y).val(imageProduk.image_url_list[y]);
                            	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                            	    $("#id-input-"+y).val(imageProduk.image_id_list[y]);
                            	    $("#preview-image-"+y).attr("src",imageProduk.image_url_list[y]);
                            	   
                                	$("#ubahGambarProduk-"+y).show();
                                	$("#hapusGambarProduk-"+y).show();
                                	
                                	dataGambar[y] = {
                                       'ID'   : $("#id-input-"+y).val(),
                                       'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                                       'URL'  : $("#preview-image-"+y).attr("src"),
                                    };
                            	    
                            	}
                            	
                        	    var imageVarian = msg.dataGambarVarian;
                        	    for(var y = 0 ; y < imageVarian.length ; y++)
                            	{
                            	    dataGambarVarian[y] = {
                                       'ID'   : '',
                                       'NAMA' : '',
                                       'URL'  : '',
                                    };
                                                                           
                            	    for(var z = 0 ; z < imageVarian.length ; z++)
                            	    {
                            	        if("Gambar Varian "+imageVarian[z].WARNA == $("#keterangan-input-varian-"+y).val())
                            	        {
                                    	    // $("#file-input-varian-"+y).val("-");
                                            $("#format-input-varian-"+y).val('GAMBAR');
                                            $("#index-input-varian-"+y).val(y);
                                            $("#src-input-varian-"+y).val(imageVarian[z].IMAGEURL);
                                            $("#id-input-varian-"+y).val(imageVarian[z].IMAGEID);
                                            $("#preview-image-varian-"+y).attr("src",imageVarian[z].IMAGEURL);
                                            $("#ubahGambarVarian"+y).show();
                                    	    $("#hapusGambarVarian"+y).show();
                                    	    dataGambarVarian[y] = {
                                               'ID'   : $("#id-input-varian-"+y).val(),
                                               'NAMA' : imageVarian[z].WARNA,
                                               'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                            };
                            	        }
                            	    }
                            	}
                        	}
                        	    
                        });
                	}
            	}
            	    
            });
        }
    }
}

function simpan(){
    var cheapest = 999999999999999999999999;
    var tableVarian = $('#dataGridVarian').DataTable();
    tableVarian.rows().eq(0).each(function (index) {
        var row = tableVarian.row(index).data();
        if(row.IDBARANG != $("#IDBARANG").val())
        {
            if(cheapest > row.HARGAJUAL)
            {
                cheapest = row.HARGAJUAL;
            }
        }
    });
    
    if($("#NAMABARANG").val() == "" || $("#SKUSHOPEE").val() == "" || $("#SKUTIKTOK").val() == "" || $("#SKULAZADA").val() == "" || $("#SATUAN2").val() == "" || $("#HARGAJUAL").val() == "" || $("#HARGAJUAL").val() == "0" || $("#HARGABELI").val() == "" || $("#HARGABELI").val() == "0"){
        Swal.fire({
        	title            : "Terdapat Data Varian yang belum diisi",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if(parseFloat($("#HARGAJUAL").val()) / parseFloat(cheapest) > 5 && parseFloat($("#HARGAJUAL").val()) >= parseFloat(cheapest) && tableVarian.length > 1){
        Swal.fire({
        	title            : "Harga varian maksimal adalah 5x dari harga varian terendah",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else if(parseFloat(cheapest) / parseFloat($("#HARGAJUAL").val()) > 5 && parseFloat($("#HARGAJUAL").val()) < parseFloat(cheapest) && tableVarian.length > 1){
        Swal.fire({
        	title            : "Harga varian maksimal adalah 5x dari harga varian terendah",
        	type             : 'warning',
        	showConfirmButton: false,
        	timer            : 1500
        });
    }
    else
    {
        
        if(!$("#NAMABARANG").val().includes("LITTLE TWISTY - "))
        {
            $("#NAMABARANG").val("LITTLE TWISTY - "+$("#NAMABARANG").val());
        }
        
        var table = $('#dataGridVarian').DataTable(); // Get DataTable instance
         
        var status = 0;
        if($("#STATUS").prop('checked'))
        {
            status = 1;
        }
        var warna = "";
        if($("#WARNABARANG").val() != "")
        {
            warna = " | "+$("#WARNABARANG").val().toUpperCase();
        }
        var ukuran = "";
        if($("#UKURANBARANG").val() != "0" && $("#UKURANBARANG").val() != "")
        {
            ukuran = " | SIZE "+$("#UKURANBARANG").val();
        }
        var namabarang = $("#NAMABARANG").val()+warna+ukuran;
        
        //CEK IDBARANG SEBELUMNYA ADA ATAU TIDAK
        var adaBarang = false;
        for(var x = 0 ; x < dataVarianLama.length;x++)
        {
           if(dataVarianLama[x].IDBARANG == $("#IDBARANG").val())
           {
               adaBarang = true;
           }
        }
        
        if(!adaBarang)
        {
            //CEK SUDAH ADA BARANG NYA DI GRID ATAU TIDAK
            var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
                return table.cell(rowIdx, 1).data() == $("#IDBARANG").val(); // Match by IDBARANG column
            })[0];
            
            if (rowIndex !== undefined) {
            }
            else
            {
                let randomDecimal = Math.random();
                $("#IDBARANG").val("X"+$("#WARNABARANG").val().toUpperCase()+"X"+$("#UKURANBARANG").val()+"X"+randomDecimal.toString());
            }
        }
        
        // Define new row data
        var newRowData = {
            SKUSHOPEE: $("#SKUSHOPEE").val(),
            SKUTIKTOK: $("#SKUTIKTOK").val(),
            SKULAZADA: $("#SKULAZADA").val(),
            IDBARANG: $("#IDBARANG").val(),
            WARNA: $("#WARNABARANG").val().toUpperCase(),
            SIZE: $("#UKURANBARANG").val(),
            BARCODE: $("#BARCODE").val(),
            KODEBARANG: $("#KODEBARANG").val(),
            NAMABARANG: namabarang,
            SATUAN: $("#SATUAN2").val(),
            HARGAJUAL: $("#HARGAJUAL").val(),
            HARGABELI: $("#HARGABELI").val(),
            USERBUAT: 'AUTOGEN',
            TGLENTRY: 'AUTOGEN',
            STATUS: status,
            MODE:(adaBarang?"ubah":"tambah")
        };
    
        // Add the row to the table
        if($("#btn_simpan_detail").html() == "Tambah")
        {
            table.row.add(newRowData).draw();
        }
        else
        {
            // Find the row based on ID
            var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
                return table.cell(rowIdx, 1).data() == $("#IDBARANG").val(); // Match by IDBARANG column
            })[0];
            
            if (rowIndex !== undefined) {
                var row = table.row(rowIndex); // This returns a row object
                // Set the updated data back to the row
                row.data(newRowData);
            
                // Redraw the table to reflect the updated data
                row.invalidate().draw();
            }
        }
    	$("#modal-varian").modal('hide');
    }
    
}

function ubah(row){
	//load row data to form
	if(row.STATUS == 0) $("#STATUS").prop('checked',false).iCheck('update');
	else if(row.STATUS == 1) $("#STATUS").prop('checked',true).iCheck('update');
	
	if(row.STOK == 'TIDAK') $("#STOK").prop('checked',false).iCheck('update');
	else if(row.STOK == 'YA') $("#STOK").prop('checked',true).iCheck('update');
	
	var barang = row.NAMABARANG.split("LITTLE TWISTY - ")[1].split(" | ");

	$("#IDBARANG").val(row.IDBARANG);
	$("#KODEBARANG").val(row.KODEBARANG);
    $("#NAMABARANG").val(barang[0]);
    $("#WARNABARANG").val(row.WARNA);
    $("#UKURANBARANG").val(row.SIZE);
	$("#SATUAN2").val(row.SATUAN);
	$("#HARGABELI").val(row.HARGABELI);
	$("#HARGAJUAL").val(row.HARGAJUAL);
	$("#SKUSHOPEE").val(row.SKUSHOPEE);
	$("#SKUTIKTOK").val(row.SKUTIKTOK);
	$("#SKULAZADA").val(row.SKULAZADA);
	$("#BARCODE").val(row.BARCODE);
	$("#WARNABARANG").attr("readonly","readonly");
	$("#UKURANBARANG").attr("readonly","readonly");
    $("#btn_simpan_detail").html('Ubah');
}

function hapus(row){
	get_akses_user('<?=$_GET['kode']?>', function(data){
		if (data.HAPUS==1) {
		    $.ajax({
              type    : 'POST',
              url     :  base_url+"Master/Data/Barang/cekHapusData/",
              data    : {idbarang: row.IDBARANG},
              dataType: 'json',
              success : function(msg){
        		     if (row && msg.success) {
            		     Swal.fire({
                        		title: 'Anda Yakin Akan Menghapus Barang '+row.NAMABARANG+' ?',
                        		html:indukBarangShopee != 0?'<p style="font-size:12pt; color:red; font-weight:w600;">*Barang ini sudah terhubung dengan marketplace, maka hanya diperbolehkan simpan dan hubungkan shopee</p>':'',
                        		showCancelButton: true,
                        		confirmButtonText: 'Yakin',
                        		cancelButtonText: 'Tidak',
                        		}).then((result) => {
                        		/* Read more about isConfirmed, isDenied below */
                        			if (result.value) {
                        			    
                        			       //CEK IDBARANG SEBELUMNYA ADA ATAU TIDAK
                                        // var adaBarang = false;
                                        // for(var x = 0 ; x < dataVarianLama.length;x++)
                                        // {
                                        //   if(dataVarianLama[x].IDBARANG == row.IDBARANG)
                                        //   {
                                        //       adaBarang = true;
                                        //   }
                                        // }
                                        
                                        // if(!adaBarang)
                                        // {
                            			    var table = $('#dataGridVarian').DataTable(); // Get DataTable instance
                                            var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
                                                return table.cell(rowIdx, 1).data() == row.IDBARANG; // Match by IDBARANG column
                                            })[0];
                                            
                                            if (rowIndex !== undefined) {
                                                table.row(rowIndex).remove().draw(); 
                                //                 Swal.fire({
                            				// 		title            : 'Barang dengan nama '+row.NAMABARANG+' telah dihapus',
                            				// 		type             : 'success',
                            				// 		showConfirmButton: false,
                            				// 		timer            : 1500
                            				// 	});
                                                setGambarVarianMaster();
                                                
                                                if(indukBarangShopee != 0 || indukBarangLazada != 0)
                                                {
                                                        
                                                      $("#btn_simpan").hide();
                                                }
                                                
                                                if($("#IDBARANGINDUK").val() != 0)
                                    	        {
                                    	            $.ajax({
                                                    	type    : 'POST',
                                                    	url     : base_url+'Master/Data/Barang/getGambarBarang/',
                                                    	data    : {idbarang: $("#IDBARANGINDUK").val()},
                                                    	dataType: 'json',
                                                    	success : function(msg){
                                                    	    
                                                    	    var imageProduk = msg.dataInduk;
                                                        	//GAMBAR PRODUK
                                                        	for(var y = 0 ; y < imageProduk.length ; y++)
                                                        	{
                                                        	   // $("#file-input-"+y).val("-");
                                                        	    $("#format-input-"+y).val('GAMBAR');
                                                        	    $("#index-input-"+y).val(y);
                                                        	    $("#src-input-"+y).val(imageProduk[y].URL);
                                                        	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                                                        	    $("#id-input-"+y).val(imageProduk[y].ID);
                                                        	    $("#preview-image-"+y).attr("src",imageProduk[y].URL);
                                                        	   
                                                            	$("#ubahGambarProduk-"+y).show();
                                                            	$("#hapusGambarProduk-"+y).show();
                                                            	
                                                            	dataGambar[y] = {
                                                                   'ID'   : $("#id-input-"+y).val(),
                                                                   'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                                                                   'URL'  : $("#preview-image-"+y).attr("src"),
                                                                };
                                                        	    
                                                        	}
                                                        	
                                                    	    var imageVarian = msg.dataGambarVarian;
                                                    	    for(var y = 0 ; y < imageVarian.length ; y++)
                                                        	{
                                                        	    dataGambarVarian[y] = {
                                                                   'ID'   : '',
                                                                   'NAMA' : '',
                                                                   'URL'  : '',
                                                                };
                                                                                                       
                                                        	    for(var z = 0 ; z < imageVarian.length ; z++)
                                                        	    {
                                                        	        if("Gambar Varian "+imageVarian[z].NAMA == $("#keterangan-input-varian-"+y).val())
                                                        	        {
                                                                	    // $("#file-input-varian-"+y).val("-");
                                                                        $("#format-input-varian-"+y).val('GAMBAR');
                                                                        $("#index-input-varian-"+y).val(y);
                                                                        $("#src-input-varian-"+y).val(imageVarian[z].URL);
                                                                        $("#id-input-varian-"+y).val(imageVarian[z].ID);
                                                                        $("#preview-image-varian-"+y).attr("src",imageVarian[z].URL);
                                                                        
                                                                        $("#ubahGambarVarian"+y).show();
                                                                	    $("#hapusGambarVarian"+y).show();
                                                                	    dataGambarVarian[y] = {
                                                                           'ID'   : $("#id-input-varian-"+y).val(),
                                                                           'NAMA' : imageVarian[z].NAMA,
                                                                           'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                                                        };
                                                        	        }
                                                        	    }
                                                        	}
                                                        	
                                                        	if(imageProduk.length == 0 && imageVarian.length == 0 && indukBarangShopee != 0)
                                                        	{
                                                        	       
                                                    	        $.ajax({
                                                                	type    : 'POST',
                                                                	url     : base_url+'Shopee/getDataBarangdanVarian/',
                                                                	data    : {idindukbarangshopee: row.IDINDUKBARANGSHOPEE},
                                                                	dataType: 'json',
                                                                	success : function(msg){
                                                                	    
                                                                	    var imageProduk = msg.dataInduk.image;
                                                                    	//GAMBAR PRODUK
                                                                    	for(var y = 0 ; y < imageProduk.image_url_list.length ; y++)
                                                                    	{
                                                                    	   // $("#file-input-"+y).val("-");
                                                                    	    $("#format-input-"+y).val('GAMBAR');
                                                                    	    $("#index-input-"+y).val(y);
                                                                    	    $("#src-input-"+y).val(imageProduk.image_url_list[y]);
                                                                    	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                                                                    	    $("#id-input-"+y).val(imageProduk.image_id_list[y]);
                                                                    	    $("#preview-image-"+y).attr("src",imageProduk.image_url_list[y]);
                                                                    	   
                                                                        	$("#ubahGambarProduk-"+y).show();
                                                                        	$("#hapusGambarProduk-"+y).show();
                                                                        	
                                                                        	dataGambar[y] = {
                                                                               'ID'   : $("#id-input-"+y).val(),
                                                                               'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                                                                               'URL'  : $("#preview-image-"+y).attr("src"),
                                                                            };
                                                                    	    
                                                                    	}
                                                                    	
                                                                	    var imageVarian = msg.dataGambarVarian;
                                                                	    for(var y = 0 ; y < imageVarian.length ; y++)
                                                                    	{
                                                                    	    dataGambarVarian[y] = {
                                                                               'ID'   : '',
                                                                               'NAMA' : '',
                                                                               'URL'  : '',
                                                                            };
                                                                                                                   
                                                                    	    for(var z = 0 ; z < imageVarian.length ; z++)
                                                                    	    {
                                                                    	        if("Gambar Varian "+imageVarian[z].WARNA == $("#keterangan-input-varian-"+y).val())
                                                                    	        {
                                                                            	    // $("#file-input-varian-"+y).val("-");
                                                                                    $("#format-input-varian-"+y).val('GAMBAR');
                                                                                    $("#index-input-varian-"+y).val(y);
                                                                                    $("#src-input-varian-"+y).val(imageVarian[z].IMAGEURL);
                                                                                    $("#id-input-varian-"+y).val(imageVarian[z].IMAGEID);
                                                                                    $("#preview-image-varian-"+y).attr("src",imageVarian[z].IMAGEURL);
                                                                                    $("#ubahGambarVarian"+y).show();
                                                                            	    $("#hapusGambarVarian"+y).show();
                                                                            	    dataGambarVarian[y] = {
                                                                                       'ID'   : $("#id-input-varian-"+y).val(),
                                                                                       'NAMA' : imageVarian[z].WARNA,
                                                                                       'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                                                                    };
                                                                    	        }
                                                                    	    }
                                                                    	}
                                                                	}
                                                                	    
                                                                });
                                                        	}
                                                    	}
                                                    	    
                                                    });
                                    	        }
                                            }
                                        // }
                                //         else
                                //         {
                                       
                            				// $("#mode").val('hapus');
                            		
                                //             $.ajax({
                            				// 	type    : 'POST',
                            				// 	dataType: 'json',
                            				// 	url     : base_url+"Master/Data/Barang/hapus",
                            				// 	data    : "id="+row.IDBARANG + "&kode="+row.KODEBARANG,
                            				// 	cache   : false,
                            				// 	success : function(msg){
                            				// 		if (msg.success) {
                            						    
                            				// 			var table = $('#dataGridVarian').DataTable(); // Get DataTable instance
                                //                         var rowIndex = table.rows().eq(0).filter(function (rowIdx) {
                                //                             return table.cell(rowIdx, 1).data() == row.IDBARANG; // Match by IDBARANG column
                                //                         })[0];
                                                        
                                //                          if (rowIndex !== undefined) {
                                //                             table.row(rowIndex).remove().draw(); 
                                //                             Swal.fire({
                                //         						title            : 'Barang dengan nama '+row.NAMABARANG+' telah dihapus',
                                //         						type             : 'success',
                                //         						showConfirmButton: false,
                                //         						timer            : 1500
                                //         					});
                                //                         }
                                                        
                            				// 		    dataVarianLama = [];
                                //                         setTimeout(function() {
                                                            
                                //                         $('#dataGridVarian').DataTable().rows().every(function () {
                                                        
                                //                         var rowData = this.data();
                                //                         dataVarianLama.push(rowData);
                                //                         });
                                //                         setGambarVarianMaster();
                                        
                                //                          if(indukBarangShopee != 0)
                                //                 	     {
                                //                     	        $.ajax({
                                //                                 	type    : 'POST',
                                //                                 	url     : base_url+'Shopee/getDataBarangdanVarian/',
                                //                                 	data    : {idindukbarangshopee: indukBarangShopee},
                                //                                 	dataType: 'json',
                                //                                 	success : function(msg){
                                                                	    
                                //                                 	    var imageProduk = msg.dataInduk.image;
                                //                                     	//GAMBAR PRODUK
                                //                                     	for(var y = 0 ; y < imageProduk.image_url_list.length ; y++)
                                //                                     	{
                                //                                     	   // $("#file-input-"+y).val("-");
                                //                                     	    $("#format-input-"+y).val('GAMBAR');
                                //                                     	    $("#index-input-"+y).val(y);
                                //                                     	    $("#src-input-"+y).val(imageProduk.image_url_list[y]);
                                //                                     	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                                //                                     	    $("#id-input-"+y).val(imageProduk.image_id_list[y]);
                                //                                     	    $("#preview-image-"+y).attr("src",imageProduk.image_url_list[y]);
                                                                    	   
                                //                                         	$("#ubahGambarProduk-"+y).show();
                                //                                         	$("#hapusGambarProduk-"+y).show();
                                                                        	
                                //                                         	dataGambar[y] = {
                                //                                               'ID'   : $("#id-input-"+y).val(),
                                //                                               'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                                //                                               'URL'  : $("#preview-image-"+y).attr("src"),
                                //                                             };
                                                                    	    
                                //                                     	}
                                                                    	
                                //                                 	    var imageVarian = msg.dataGambarVarian;
                                //                                 	    for(var y = 0 ; y < imageVarian.length ; y++)
                                //                                     	{
                                //                                     	   dataGambarVarian[y] = {
                                //                                               'ID'   : '',
                                //                                               'NAMA' : '',
                                //                                               'URL'  : '',
                                //                                           };
                                                                           
                                //                                     	    for(var z = 0 ; z < imageVarian.length ; z++)
                                //                                     	    {
                                //                                     	        if("Gambar Varian "+imageVarian[z].WARNA == $("#keterangan-input-varian-"+y).val())
                                //                                     	        {
                                //                                             	    // $("#file-input-varian-"+y).val("-");
                                //                                                     $("#format-input-varian-"+y).val('GAMBAR');
                                //                                                     $("#index-input-varian-"+y).val(y);
                                //                                                     $("#src-input-varian-"+y).val(imageVarian[z].IMAGEURL);
                                //                                                     $("#id-input-varian-"+y).val(imageVarian[z].IMAGEID);
                                //                                                     $("#preview-image-varian-"+y).attr("src",imageVarian[z].IMAGEURL);
                                                                                    
                                //                                                     $("#ubahGambarVarian"+y).show();
                                //                                             	    $("#hapusGambarVarian"+y).show();
                                                                            	    
                                //                                             	    dataGambarVarian[y] = {
                                //                                                       'ID'   : $("#id-input-varian-"+y).val(),
                                //                                                       'NAMA' : imageVarian[z].WARNA,
                                //                                                       'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                //                                                     };
                                //                                     	        }
                                //                                     	    }
                                //                                     	}
                                //                                 	}
                                                                	    
                                //                                 });
                                //                 	        }
                                //                 	        else if($("#IDBARANGINDUK").val() != 0)
                                //                 	        {
                                //                 	            $.ajax({
                                //                                 	type    : 'POST',
                                //                                 	url     : base_url+'Master/Data/Barang/getGambarBarang/',
                                //                                 	data    : {idbarang: $("#IDBARANGINDUK").val()},
                                //                                 	dataType: 'json',
                                //                                 	success : function(msg){
                                                                	    
                                //                                 	    var imageProduk = msg.dataInduk;
                                //                                     	//GAMBAR PRODUK
                                //                                     	for(var y = 0 ; y < imageProduk.length ; y++)
                                //                                     	{
                                //                                     	   // $("#file-input-"+y).val("-");
                                //                                     	    $("#format-input-"+y).val('GAMBAR');
                                //                                     	    $("#index-input-"+y).val(y);
                                //                                     	    $("#src-input-"+y).val(imageProduk[y].URL);
                                //                                     	    $("#keterangan-input-"+y).val("Gambar Produk "+(y+1).toString());
                                //                                     	    $("#id-input-"+y).val(imageProduk[y].ID);
                                //                                     	    $("#preview-image-"+y).attr("src",imageProduk[y].URL);
                                                                    	   
                                //                                         	$("#ubahGambarProduk-"+y).show();
                                //                                         	$("#hapusGambarProduk-"+y).show();
                                                                        	
                                //                                         	dataGambar[y] = {
                                //                                               'ID'   : $("#id-input-"+y).val(),
                                //                                               'NAMA' : "INDUK_"+$("#index-input-"+y).val(),
                                //                                               'URL'  : $("#preview-image-"+y).attr("src"),
                                //                                             };
                                                                    	    
                                //                                     	}
                                                                    	
                                //                                 	    var imageVarian = msg.dataGambarVarian;
                                //                                 	    for(var y = 0 ; y < imageVarian.length ; y++)
                                //                                     	{
                                //                                     	   dataGambarVarian[y] = {
                                //                                               'ID'   : '',
                                //                                               'NAMA' : '',
                                //                                               'URL'  : '',
                                //                                           };
                                                                                    
                                //                                     	    for(var z = 0 ; z < imageVarian.length ; z++)
                                //                                     	    {
                                //                                     	        if("Gambar Varian "+imageVarian[z].NAMA == $("#keterangan-input-varian-"+y).val())
                                //                                     	        {
                                //                                             	    // $("#file-input-varian-"+y).val("-");
                                //                                                     $("#format-input-varian-"+y).val('GAMBAR');
                                //                                                     $("#index-input-varian-"+y).val(y);
                                //                                                     $("#src-input-varian-"+y).val(imageVarian[z].URL);
                                //                                                     $("#id-input-varian-"+y).val(imageVarian[z].ID);
                                //                                                     $("#preview-image-varian-"+y).attr("src",imageVarian[z].URL);
                                                                                    
                                //                                                     $("#ubahGambarVarian"+y).show();
                                //                                             	    $("#hapusGambarVarian"+y).show();
                                                                            	    
                                //                                             	    dataGambarVarian[y] = {
                                //                                                       'ID'   : $("#id-input-varian-"+y).val(),
                                //                                                       'NAMA' : imageVarian[z].NAMA,
                                //                                                       'URL'  : $("#preview-image-varian-"+y).attr("src"),
                                //                                                     };
                                //                                     	        }
                                //                                     	    }
                                //                                     	}
                                //                                 	}
                                                                	    
                                //                                 });
                                //                 	        }
                                                        
                                //                         }, 1000);
                            				// 		} else {
                            				// 				Swal.fire({
                            				// 					title            : msg.errorMsg,
                            				// 					type             : 'error',
                            				// 					showConfirmButton: false,
                            				// 					timer            : 1500
                            				// 				});
                            				// 		}
                            				// 	}
                            				// });
                                //         }
                        			} 
                                 });
        		        } 
        		        else
        		        {
        		            Swal.fire({
                				title            : msg.errorMsg,
                				type             : 'warning',
                				showConfirmButton: false,
                				timer            : 1500
                			});
        		        }
                    }
                });
			
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

function setGambarProdukMaster(){
    
    //GAMBAR PRODUK
    var htmlGambarProduk = "<tr>";
    var label = "Gambar Utama";
    
    for(var y = 0 ; y < 9 ;y++)
    {
        var marginRight = "30px";
        
        if(y % 9 == 0 && y != 0)
        {
            marginRight = "";
        }
        
        if(y % 5 == 0 && y != 0)
        {
            htmlGambarProduk +="</tr><tr>";
        }
        
        if(y > 0)
        {
            label = "Gambar "+(y+1);
        }
        
        htmlGambarProduk += `
                        <td>
                            <input type="file" id="file-input-`+y+`" accept="image/jpeg,image/jpg,image/png" style="display:none;" value="">
                            <input type="hidden"  id="format-input-`+y+`" value="">
                            <input type="hidden"  id="index-input-`+y+`" value="`+y+`">
                            <input type="hidden"  id="src-input-`+y+`" value="">
                            <input type="hidden"  id="keterangan-input-`+y+`" value="Gambar Produk `+(y+1).toString()+`">
                            <input type="hidden"  id="id-input-`+y+`" value="">
                            
                            <div style="margin-bottom:20px;">
                                <img id="preview-image-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; margin-right:`+marginRight+`; cursor:pointer; border:2px solid #dddddd;'>
                                <div style="text-align:center; margin-right:`+marginRight+`"><b>`+label+`</b><br>
                                <span id="ubahGambarProduk-`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                &nbsp;
                                <span id="hapusGambarProduk-`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                </div>
                            </div>
                        </td>`;  
                        
        utama = "";
    
    }
    htmlGambarProduk += "</tr>";
    $("#gambarproduk").html(htmlGambarProduk);
    $("#gambarproduk").css('margin-bottom','-40px');
    
    for(var y = 0 ; y < 9 ;y++)
    {
        const fileInput = document.getElementById('file-input-'+y);
        const previewImage = document.getElementById('preview-image-'+y);
        const title = document.getElementById('keterangan-input-'+y);
        const format = document.getElementById('format-input-'+y);
        const index = document.getElementById('index-input-'+y);
        const url =  document.getElementById('src-input-'+y);
        const id = document.getElementById('id-input-'+y);
        
        const ubahImage = document.getElementById('ubahGambarProduk-'+y);
        const hapusImage = document.getElementById('hapusGambarProduk-'+y);
        
        previewImage.addEventListener('click', () => {
          if(url.value != '')
          {
              lihatLebihJelas(format.value,title.value,url.value);
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
          for(var k = 0 ; k < dataGambar.length; k++)
          {
              if(dataGambar[k].ID == id.value)
              {
                  dataGambar[k].ID   = "";
                  dataGambar[k].NAMA = "";
                  dataGambar[k].URL  = "";
              }
          }
          
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
        
            const maxSizeMB = 1;
            if (file.size > maxSizeMB * 1024 * 1024) {
                fileInput.value = '';
              Swal.fire({
                title: 'Ukuran gambar melebihi 1 MB',
                icon: 'warning',
                showConfirmButton: false,
                timer: 2000
              });
              return;
            }
            
            const img = new Image();
            img.onload = function () {
                if (img.width < 350 || img.height < 350) {
                     Swal.fire({
                        title: 'Panjang dan Lebar gambar minimal 350px',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000
                      });
                      
                      return;
                } 
                else
                {
                    // Upload file asli ke server
                    const formData = new FormData();
                    formData.append('index', index.value);
                    formData.append('kode', "INDUK_"+index.value);
                    formData.append('file', file);
                    formData.append('tipe', 'GAMBAR');
                    formData.append('size', file.size);
                    formData.append("reason","produk");
                    
                    $.ajax({
                      type: 'POST',
                      url: base_url + 'Shopee/uploadLocalUrl/',
                      data: formData,
                      contentType: false,
                      processData: false,
                      dataType: 'json',
                      success: function (msg) {
                       
                        if (msg.success) {
                         format.value = "GAMBAR";
                         previewImage.src = msg.url;
                         url.value =  msg.url;
                         id.value = msg.id;
                         
                         dataGambar[index.value] = {
                            'ID'   : id.value,
                            'NAMA' : "INDUK_"+index.value,
                            'URL'  : url.value,
                         };
                
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
                URL.revokeObjectURL(img.src); // free memory
            };
            
            img.src = URL.createObjectURL(file); 
          }
        
          // Tipe file tidak valid
          else {
             Swal.fire({
                	title            : 'Hanya mendukung file Gambar',
                	type             : 'warning',
                	showConfirmButton: false,
                	timer            : 2000
            });
          }
        });
    }
}

function setGambarVarianMaster(){
    var varian = $('#dataGridVarian').DataTable().rows().data().toArray();
   
    var warna = [];
    var ukuran = [];
    for(var y = 0 ; y < varian.length; y++)
    {
        var tempWarna = varian[y].WARNA;
        var tempUk = varian[y].SIZE;
    	
    	adaWarna = false;
    	for(var w = 0 ; w < warna.length; w++)
    	{
    	    if(warna[w] == tempWarna)
    	    {
    	        adaWarna = true;
    	    }
    	}
    	
    	if(!adaWarna)
    	{
    	    warna.push(tempWarna);
    	}
    	
    	adaUkuran = false;
    	for(var u = 0 ; u < ukuran.length; u++)
    	{
    	    if(ukuran[u] == tempUk)
    	    {
    	        adaUkuran = true;
    	    }
    	}
    	
    	if(!adaUkuran)
    	{
    	    ukuran.push(tempUk);
    	}
    }
    
    var htmlGambarVarian = "<tr>";
        
    for(var y = 0 ; y < warna.length ;y++)
    {
         var marginRight = "30px";
         
         if(y % 9 == 0 && y != 0)
         {
             marginRight = "";
         }
         
         if(y % 5 == 0 && y != 0)
         {
             htmlGambarVarian +="</tr><tr>";
         }
         
         htmlGambarVarian += `
                         <td>
                             <input type="file" id="file-input-varian-`+y+`" accept="image/jpeg,image/jpg,image/png" style="display:none;" value="">
                             <input type="hidden"  id="format-input-varian-`+y+`" value="">
                             <input type="hidden"  id="index-input-varian-`+y+`" value="`+y+`">
                             <input type="hidden"  id="src-input-varian-`+y+`" value="">
                             <input type="hidden"  id="keterangan-input-varian-`+y+`" value="Gambar Varian `+warna[y]+`">
                             <input type="hidden"  id="id-input-varian-`+y+`" value="">
                            
                             <div style="margin-bottom:20px;">
                                 <img id="preview-image-varian-`+y+`" onclick='' src='`+base_url+`/assets/images/addphoto.webp' style='width:100px; margin-right:`+marginRight+`; cursor:pointer; border:2px solid #dddddd;'>
                                 <div style="text-align:center; margin-right:`+marginRight+`"><b>`+warna[y]+`</b><br>
                                 <span id="ubahGambarVarian`+y+`" onclick='' style="display:none; color:blue; cursor:pointer;">Ubah</span>
                                 &nbsp;
                                 <span id="hapusGambarVarian`+y+`" onclick='' style="display:none; color:<?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?>; cursor:pointer;">Hapus</span>
                                 </div>
                             </div>
                         </td>`;  
                         
         utama = "";
     
    }
    
    htmlGambarVarian += "</tr>";
    $("#gambarvarian").html(htmlGambarVarian);
    $("#gambarvarian").css('margin-bottom','-20px');
    
    for(var y = 0 ; y < warna.length ;y++)
    {
      
        const fileInput = document.getElementById('file-input-varian-'+y);
        const previewImage = document.getElementById('preview-image-varian-'+y);
        const title = document.getElementById('keterangan-input-varian-'+y);
        const format = document.getElementById('format-input-varian-'+y);
        const index = document.getElementById('index-input-varian-'+y);
        const url =  document.getElementById('src-input-varian-'+y);
        const id =  document.getElementById('id-input-varian-'+y);
        
        const ubahImage = document.getElementById('ubahGambarVarian'+y);
        const hapusImage = document.getElementById('hapusGambarVarian'+y);
        
        previewImage.addEventListener('click', () => {
          if(url.value != '')
          {
              lihatLebihJelas(format.value,title.value,url.value);
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
          for(var k = 0 ; k < dataGambarVarian.length; k++)
          {
              if(dataGambarVarian[k].ID == id.value)
              {
                  dataGambarVarian.splice(k,1);
              }
          }
          
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
        
            const maxSizeMB = 1;
            if (file.size > maxSizeMB * 1024 * 1024) {
                fileInput.value = '';
              Swal.fire({
                title: 'Ukuran gambar melebihi 1 MB',
                icon: 'warning',
                showConfirmButton: false,
                timer: 2000
              });
              return;
            }
            
            const img = new Image();
            img.onload = function () {
                if (img.width < 350 || img.height < 350) {
                     Swal.fire({
                        title: 'Panjang dan Lebar gambar minimal 350px',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000
                      });
                } 
                else
                {
                    // Upload file asli ke server
                    const formData = new FormData();
                    formData.append('index', index.value);
                    formData.append('kode', warna[index.value]);
                    formData.append('file', file);
                    formData.append('tipe', 'GAMBAR');
                    formData.append('size', file.size);
                    formData.append("reason","produk");
                    
                    $.ajax({
                      type: 'POST',
                      url: base_url + 'Shopee/uploadLocalUrl/',
                      data: formData,
                      contentType: false,
                      processData: false,
                      dataType: 'json',
                      success: function (msg) {
                      
                        if (msg.success) {
                         format.value = "GAMBAR";
                         previewImage.src = msg.url;
                         url.value =  msg.url;
                         id.value = msg.id;
                         
                         dataGambarVarian[index.value] = {
                            'ID'   : id.value,
                            'NAMA' : warna[index.value],
                            'URL'  : url.value,
                         };
                
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
                URL.revokeObjectURL(img.src); // free memory
            };
            
            img.src = URL.createObjectURL(file); 
          }
        });
    }
}

function lihatLebihJelas(jenis,title,url){

    $("#modal-lebih-jelas").modal("show");
    $("#titleLebihJelas").html(title);
    $("#previewLebihJelas").css("color","#3296ff");
    $("#previewLebihJelas").css("cursor","pointer");
    $("#previewLebihJelas").css("text-align","center");
    $("#previewLebihJelas").css("background","#d4d4d7");
    if(jenis == "GAMBAR")
    {
        $("#previewLebihJelas").html("<img src='"+url+"' max-width=100%; height=600px;>");
    }
    else
    {
        $("#previewLebihJelas").html("<iframe src='"+url+"' max-width=100%; height=600px;>");
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

function openFileExcelUrutan(){
     document.getElementById('excelFileUrutan').click();
}

function importExcelUrutan(){
    document.getElementById("excelFormUrutan").submit();
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
