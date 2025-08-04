<style>
	.header-menu {
		font-size: 20px;
		color: white;
	}

	.sidenav {
		position: absolute;
		z-index: 0;
		background-color: #111;
		overflow-x: hidden;
		transition: 0.5s;
		width: 250px;
	}

	.sidenav a {
		text-decoration: none;
		color: #818181;
		display: block;
		transition: 0.3s;
	}

	.sidenav a:hover {
		color: #f1f1f1;
	}

	@media screen and (max-height: 450px) {
		.sidenav {
			padding-top: 15px;
		}

		.sidenav a {
			font-size: 18px;
		}
	}

	/*DROPDOWNMENU*/
	.dropdown-menu {
		-webkit-transition: all .5s ease-out;
		transition: all .5s ease-out;
		transform: rotateX(90deg);
		transform-origin: top;
		opacity: 1;
		display: block;
		width: 100%;
		border: none;
		background: #111111;
	}

	.open .dropdown-menu {
		opacity: 1;
		transform: rotateX(0deg);
		transform-origin: top;
		width: 100%;
		border: none;
	}

	.dropdown-menu li a:hover {
		color: white;
		background: #111111;
	}
	
	/* SIDEBAR MENU OPEN */
	    
        /* Hide the submenu toggle arrow */
        .menu-title .fa-angle-left {
            display: none;
        }
        
        .showing-menu{
            list-style:none;
            padding-left : 17px;
        }
        
        .laporan_pages{
            
            padding : 4px 0px 4px 0px;
            
        }
        
        .laporan_pages > .fa-circle-o{
            margin-right:10px;
        }
    /* SIDEBAR MENU OPEN */
</style>

<span id="mySidenav" class="sidenav">
	<ul class="sidebar-menu" data-widget="tree">
		<li class="header-menu" style="padding:10px 10px 10px 18px ; cursor:pointer; font-size:12pt; font-weight;bold;" onclick="toggleNav()">&#9776; &nbsp;&nbsp;&nbsp;<b style="font-size:12pt;">Laporan</b></li>
		<li class="active treeview report_menu">
			<?php include "nav-menu-laporan.php"; ?>
		</li>
	</ul>
</span>

<div id="mySidegrid" class="sidegrid " style="margin-left: 250px;transition: 0.5s; background-image:url('<?php echo base_url(); ?>assets/images/laporan.jpg');background-repeat: no-repeat; background-position: center; background-size: cover;">
	<!-- nav-tabs-custom -->
</div>

<script>
	counter = 1;

	$(document).ready(function() {
		$("#mySidenav").css("height", "calc(100vh - 135px)");
		$("#mySidegrid").css("height", "calc(100vh - 135px)");

		$(".menu-title").click(function() {
			var data = $(this).attr("id").split("_");

			var index = parseInt(data[1]);
			//RESET MENU
			for (var i = 1; i <= $(".space_menu").length; i++) {
				$("#space_" + i).css("margin-top", "0px");
			}
   /* SIDEBAR MENU OPEN */
// 			if ($("#space_" + index).css("margin-top") == "0px") {
// 				$("#space_" + index).css("margin-top", $("#dropdown_" + index).css("height"));
// 				$("#space_" + index).css("-webkit-transition", "all .5s ease-out");
// 				$("#space_" + index).css("transition", "all .5s ease-out");
// 				$("#icon_" + index).css("transform", "rotate(-90deg)");
// 			} else {
// 				$("#space_" + index).css("margin-top", "0px");
// 				$("#icon_" + index).css("transform", "rotate(0deg)");
// 			}
   /* SIDEBAR MENU OPEN */
		});

	});

	function toggleNav() {
		if (counter == 0) {
			document.getElementById("mySidenav").style.width = "250px";
			document.getElementById("mySidegrid").style.marginLeft = "250px";

			for (var i = 1; i <= $(".space_menu").length; i++) {
				$("#icon_" + i).css("opacity", "1");
			}
			counter = 1;
		} else {
			document.getElementById("mySidenav").style.width = "45px";
			document.getElementById("mySidegrid").style.marginLeft = "45px";

			for (var i = 1; i <= $(".space_menu").length; i++) {
				$("#icon_" + i).css("opacity", "0");
			}
			counter = 0;
		}
	}

	$(".menu-title").click(function() {
		document.getElementById("mySidenav").style.width = "250px";
		document.getElementById("mySidegrid").style.marginLeft = "250px";
	});

	$(".laporan_pages").click(function() {
		var link_url = $(this).attr("id");
		$("#mySidegrid").html("<iframe src='" + link_url + "' frameBorder='0' style='width:100%; height:100%;'></iframe>");
		document.getElementById("mySidegrid").style.marginLeft = "250px";
	});
</script>