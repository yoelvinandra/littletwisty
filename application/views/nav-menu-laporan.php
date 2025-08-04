<?php
$aMenu = isset($_SESSION[NAMAPROGRAM]['array_menu']) ? json_decode($_SESSION[NAMAPROGRAM]['array_menu']) : array();

	if (count($aMenu)>0) {
		$str1 = $str2 =
		$str3 = '';
		$lv1 = $lv2 = $lv3 = 0;
		$i = 0;
		$indexMenu = 1;
		foreach($aMenu as $menuLv1) {
			$lv2 = 0;
			$str2 = '';

			foreach($menuLv1->children as $menuLv2) {
				$lv3 = 0;
				$str3 = '';

				foreach($menuLv2->children as $item) {
					//index.php?kode='.$item->idmenu.'
					$menuutama = str_replace(' ', '', $menuLv1->namamenu);
					$link = base_url().$menuutama.'/'.$menuLv2->namamenu.'/'.$item->namaclass."?kode=".$item->kodemenu;
					$str3 .='<li style="padding:3px;"><a class="laporan_pages" id="'.$link.'" href="#"><i class="fa fa-circle-o"></i>&nbsp;'.$item->namamenu.'</a></li>';

					$lv3++;
				}
 
				if ($lv3 > 0) {
					if($menuLv2->namamenu == "Laporan")
					{
					$str2 .= $str3;
					$lv2++;
					}
				}
			}

			if ($lv2 > 0){
			     /* SIDEBAR MENU OPEN */
			   //showing-menu changes dropdown-menu
				$str1 .= '
				  <li class="dropdown menu-title" id="menu_'.$indexMenu.'">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown"  style="background:#26282A; padding-top:8px; padding-bottom:8px;">
						<div style="margin:0;padding:0;">
							<i class="fa '.$menuLv1->icon.'"></i> 
							<span style="padding-left:10px;"> '.$menuLv1->namamenu.' 
								
							</span>
						</div>
						<i id="icon_'.$indexMenu.'" class="fa fa-angle-left"></i>
					  </a>
					  <ul class="showing-menu" id="dropdown_'.$indexMenu.'" >'.$str2.'</ul>
					  <div class="space_menu" id="space_'.$indexMenu.'">
					  </div>
				  </li>
				  	  ';
				 /* SIDEBAR MENU OPEN */	  
				 $indexMenu++;
			}

			$i++;
		}
		
	}

	echo $str1;
?>