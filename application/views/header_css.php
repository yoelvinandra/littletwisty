<!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.dataTables.min.css"> -->
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/buttons.dataTables.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/AdminLTE.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/select2/dist/css/select2.min.css">
   <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="../../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <script>
	var base_url = '<?=base_url();?>';
    var decimaldigitqty = '<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITQTY'];?>';
    var decimaldigitamount = '<?=$_SESSION[NAMAPROGRAM]['DECIMALDIGITAMOUNT'];?>';
  </script>
	
  <!-- jQuery 3 -->
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <!-- Select2 -->
  <script src="<?php echo base_url(); ?>assets/bower_components/select2/dist/js/select2.full.min.js"></script>
  <!-- DataTables -->
  <script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <!-- button datatable -->
  <script src="<?php echo base_url(); ?>assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
  <!-- Sparkline -->
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
  <!-- bootstrap datepicker -->
  <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <!-- Slimscroll -->
  <script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url(); ?>assets/js/adminlte.min.js"></script>
  <!-- ChartJS -->
  <script src="<?php echo base_url(); ?>assets/bower_components/chart.js/Chart.js"></script>
  <!-- date-range-picker -->
  <script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js"></script> 
  <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- timepicker -->
  <script src="<?php echo base_url(); ?>assets/plugins/timepicker/bootstrap-timepicker.min.js"></script> 
<!-- <script src="assets/js/sweetalert.min.js"></script> -->
<script src="<?php echo base_url(); ?>assets/js/sweetalert2.all.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.number.js"></script>
<!-- <script src="<?php echo base_url(); ?>assets/js/moment.js"></script> -->

<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      text-align: left;
    }
    .select2-container .select2-selection--multiple {
        height: auto !important; /* Make the height auto so it adapts to the content */
        padding: 5px !important; /* Adjust padding if needed */
    }
   .select2-container--default .select2-selection--multiple .select2-selection__choice {
      margin : 0px;
      padding:0px 5px;
      border: 1px solid #dddddd;
      background-color: #dddddd; /* Background color for selected items */
      color: black; /* Text color inside selected items */
      margin-right: 5px; /* Space between selected items */
    }
    
     /* Customizing the select box (input field) */
    .select2-container--default .select2-selection--multiple {
      border: 1px solid #d2d6de; /* Change the border color to Tomato */
      border-radius: 1px; /* Rounded corners */
      padding: 6px; /* Padding inside the select box */
      height:34px;
    }
    
     /* Customizing the select box (input field) */
    .select2-container--default .select2-selection--single {
      border: 1px solid #d2d6de; /* Change the border color to Tomato */
      border-radius: 1px; /* Rounded corners */
      padding: 6px 4px 6px 4px; /* Padding inside the select box */
      height:34px;
    }
    
    .select2-container--default .select2-selection__arrow {
       margin-top: 3px; 
    }
    
    label{
        padding-top:5px !important;
    }
    /*
 * Skin: Green
 * -----------
 */
    .skin-custom .main-header .navbar {
      background-color: <?=$_SESSION[NAMAPROGRAM]['TEMAWARNA']?>;
    }
    .skin-custom .main-header .navbar .nav > li > a {
      color: #ffffff;
    }
    .skin-custom .main-header .navbar .nav > li > a:hover,
    .skin-custom .main-header .navbar .nav > li > a:active,
    .skin-custom .main-header .navbar .nav > li > a:focus,
    .skin-custom .main-header .navbar .nav .open > a,
    .skin-custom .main-header .navbar .nav .open > a:hover,
    .skin-custom .main-header .navbar .nav .open > a:focus,
    .skin-custom .main-header .navbar .nav > .active > a {
      background: rgba(0, 0, 0, 0.1);
      color: #f6f6f6;
    }
    .skin-custom .main-header .navbar .sidebar-toggle {
      color: #ffffff;
    }
    .skin-custom .main-header .navbar .sidebar-toggle:hover {
      color: #f6f6f6;
      background: rgba(0, 0, 0, 0.1);
    }
    .skin-custom .main-header .navbar .sidebar-toggle {
      color: #fff;
    }
    .skin-custom .main-header .navbar .sidebar-toggle:hover {
      /*background-color: #008d4c;*/
        filter: brightness(85%);
    }
    @media (max-width: 767px) {
      .skin-custom .main-header .navbar .dropdown-menu li.divider {
        background-color: rgba(255, 255, 255, 0.1);
      }
      .skin-custom .main-header .navbar .dropdown-menu li a {
        color: #fff;
      }
      .skin-custom .main-header .navbar .dropdown-menu li a:hover {
        /*background: #008d4c;*/
        filter: brightness(85%);
      }
    }
    .skin-custom .main-header .logo {
      /*background-color: #008d4c;*/
      /*filter: brightness(85%);*/
      color: #ffffff;
      border-bottom: 0 solid transparent;
    }
    /*.skin-custom .main-header .logo:hover {*/
    /*  filter: brightness(85%);*/
    /*}*/
    .skin-custom .main-header li.user-header {
      background-color: <?=$_SESSION[NAMAPROGRAM]['TEMAWARNA']?>;
    }
    .skin-custom .content-header {
      background: transparent;
    }
    .skin-custom .content-wrapper {
      background: #fafafa;
    }
    .skin-custom .wrapper,
    .skin-custom .main-sidebar,
    .skin-custom .left-side {
      background-color: #222d32;
    }
    .skin-custom .user-panel > .info,
    .skin-custom .user-panel > .info > a {
      color: #fff;
    }
    .skin-custom .sidebar-menu > li.header {
      color: #4b646f;
      background: #1a2226;
    }
    .skin-custom .sidebar-menu > li > a {
      border-left: 3px solid transparent;
    }
    .skin-custom .sidebar-menu > li:hover > a,
    .skin-custom .sidebar-menu > li.active > a,
    .skin-custom .sidebar-menu > li.menu-open > a {
      color: #ffffff;
      background: #1e282c;
    }
    .skin-custom .sidebar-menu > li.active > a {
      border-left-color: <?=$_SESSION[NAMAPROGRAM]['TEMAWARNA']?>;
    }
    .skin-custom .sidebar-menu > li > .treeview-menu {
      margin: 0 1px;
      background: #2c3b41;
    }
    .skin-custom .sidebar a {
      color: #b8c7ce;
    }
    .skin-custom .sidebar a:hover {
      text-decoration: none;
    }
    .skin-custom .sidebar-menu .treeview-menu > li > a {
      color: #8aa4af;
    }
    .skin-custom .sidebar-menu .treeview-menu > li.active > a,
    .skin-custom .sidebar-menu .treeview-menu > li > a:hover {
      color: #ffffff;
    }
    .skin-custom .sidebar-form {
      border-radius: 3px;
      border: 1px solid #374850;
      margin: 10px 10px;
    }
    .skin-custom .sidebar-form input[type="text"],
    .skin-custom .sidebar-form .btn {
      box-shadow: none;
      background-color: #374850;
      border: 1px solid transparent;
      height: 35px;
    }
    .skin-custom .sidebar-form input[type="text"] {
      color: #666;
      border-top-left-radius: 2px;
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
      border-bottom-left-radius: 2px;
    }
    .skin-custom .sidebar-form input[type="text"]:focus,
    .skin-custom .sidebar-form input[type="text"]:focus + .input-group-btn .btn {
      background-color: #fff;
      color: #666;
    }
    .skin-custom .sidebar-form input[type="text"]:focus + .input-group-btn .btn {
      border-left-color: #fff;
    }
    .skin-custom .sidebar-form .btn {
      color: #999;
      border-top-left-radius: 0;
      border-top-right-radius: 2px;
      border-bottom-right-radius: 2px;
      border-bottom-left-radius: 0;
    }

</style>


