<?php
defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);

$_SESSION[NAMAPROGRAM]['WARNA_STATUS_S'] = "#90EE90";
$_SESSION[NAMAPROGRAM]['WARNA_STATUS_C'] = "#FF991C";
$_SESSION[NAMAPROGRAM]['WARNA_STATUS_P'] = "#FFFDD1";
$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D'] = "#FF5959";


$_SESSION[NAMAPROGRAM]['WARNA_STATUS_BELUM_BAYAR'] = "#CFECF7";

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $modeDev = "";
    if(base_url() == "https://varshadigital.id/pos/")
    {
        $modeDev = "Dev";
    }
    
    if ($menu == '') {
        echo "<title>..:: ".$modeDev." POSinaja ::..</title>";
    } else {
        echo "<title>..:: ".$modeDev." " . $menu . " ::..</title>";
    }
    ?>

    <style>
        .status-cetak{
           background-color: <?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_S']?> !important;
        }
        .status-cicil{
           background-color: <?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_C']?> !important;
        }
        .status-lanjut{
           background-color: <?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_P']?> !important;
        }
        .status-batal {
            background-color: <?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_D']?> !important;
        }
        .status-belum-bayar{
            background-color: <?=$_SESSION[NAMAPROGRAM]['WARNA_STATUS_BELUM_BAYAR']?> !important;
        }
        
        .btn-docker-blue {
            background-color: #000080;
            border-color: #000080;
            color: white;
           filter:brightness(70%);
        }
        
        .btn-docker-blue:hover {
           color: white;
        }
        
        /* SIDEBAR MENU OPEN */
        .sidebar-menu .treeview.active > .treeview-menu {
            display: block;
        }
        
        .sidebar-menu .treeview > .treeview-menu {
            display: block; /* Always show the submenu */
        }
        
        /* Hide the submenu toggle arrow */
        .sidebar-menu .treeview .fa-angle-left {
            display: none;
        }
        /* SIDEBAR MENU OPEN */
        
        .main-sidebar .sidebar {
            height: 50vh;
            overflow-y: auto; /* Enables vertical scrolling */
        }
        
        /* Optional: Add a max-height to the sidebar itself if needed */
        .main-sidebar {
            max-height: 50vh;
            overflow-y: auto;
        }
    </style>
    <?php include("header_css.php"); ?>
</head>
<body class="hold-transition skin-custom sidebar-mini sidebar-collapse" style="overflow-y:hidden;">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="<?php echo base_url(); ?>assets/index2.html" class="logo" style="background-color: <?=$_SESSION[NAMAPROGRAM]['TEMAWARNA']?>">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"> <img src="<?php echo base_url(); ?>assets/<?=$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN']?>/logo.png" class="user-image" alt="User Image" height="28"></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"> <img src="<?php echo base_url(); ?>assets/<?=$_SESSION[NAMAPROGRAM]['KODEPERUSAHAAN']?>/logo.png" class="user-image" alt="User Image" height="28"></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" >
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="padding-left:19.5px;padding-right:19.5px; color: <?=$_SESSION[NAMAPROGRAM]['WARNAFONT']?>
">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url(); ?>assets/foto-user/no_image.png" class="user-image" alt="User Image">
                                <span class="hidden-xs" style="color: <?=$_SESSION[NAMAPROGRAM]['WARNAFONT']?>"><?php echo $_SESSION[NAMAPROGRAM]['user'] ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo base_url(); ?>assets/foto-user/no_image.png" class="img-circle" alt="User Image">

                                    <p style="color: <?=$_SESSION[NAMAPROGRAM]['WARNAFONT']?>">
                                        <?php echo $_SESSION[NAMAPROGRAM]['USERNAME'] ?>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left" style="padding-top:7px;">
                                        <!--<a href="#" class="btn btn-default btn-flat">Profile</a>!-->
                                        <span style="color:black; ">&nbsp;<i class="fa fa-circle text-success"></i>&nbsp;&nbsp;&nbsp; Online</span>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url(); ?>auth/Login/logout" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->
        <div style="height:3px;">
         <hr style="margin-top:0px;margin-bottom:0px; height:3px; background-color: <?=$_SESSION[NAMAPROGRAM]['TEMAWARNA']?> !important; filter: brightness(95%)"></hr>
        </div>
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <!--<div class="user-panel">-->
                <!--    <div class="pull-left image">-->
                <!--        <img src="<?php echo base_url(); ?>assets/foto-user/no_image.png" class="img-circle" alt="User Image">-->
                <!--    </div>-->
                <!--    <div class="pull-left info">-->
                <!--        <?php echo $_SESSION[NAMAPROGRAM]['user'] ?>-->
                <!--        <br>-->
                <!--        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
                <!--    </div>-->
                <!--</div>-->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu " data-widget="tree">
                    <li class="header">Menu</li>
                    <li class="treeview" >
                        <?php include "nav-menu.php"; ?>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="overflow-y:auto;  height: calc(100vh - 135px); ">
        
        <div style="background:#90EE90; font-weight:bold; text-align:center;"> <?php echo $label ?></div>
        <div id="alert-container" style="position:absolute; top:55px; right:0px; z-index:9999999; "></div>    