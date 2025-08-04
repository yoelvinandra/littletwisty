<?php
$aMenu = isset($_SESSION[NAMAPROGRAM]['array_menu']) ? json_decode($_SESSION[NAMAPROGRAM]['array_menu']) : array();

	if (count($aMenu)>0) {
		$str1 = $str2 =
		$str3 = '';
		if($aMenu[0]->kodemenu == "D0001")
		{
    		$str1 .= '
    		 <li class="menu-header">
    		 <a href="'. base_url().'home/index/dashboard/">
    			<i class="fa '.$aMenu[0]->icon.'"></i> <span>'.$aMenu[0]->namamenu.'</span>
    			<span class="pull-right-container">
    			</span>
    		 </a>
    		 </li>';
		}
		$lv1 = $lv2 = $lv3 = 0;
		$i = 0;
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
					
					$str3 .='<li><a href="'.$link.'"><i class="fa fa-circle-o"></i>'.$item->namamenu.'</a></li>';
						
					$lv3++;
				}
 
				if ($lv3 > 0) {
					if($menuLv2->namamenu == "Transaksi" || $menuLv2->namamenu == "Data")
					{
						//$str2 .= '<li class="header" style="color:white;">'.$menuLv2->namamenu.'</li>'.$str3.'';
						$str2 .= $str3;
						$lv2++;
					}
				}
			}

			if ($lv2 > 0){
				$str1 .= '
				  <li class="treeview menu-header">
				  <a href="#">
					<i class="fa '.$menuLv1->icon.'"></i> <span>'.$menuLv1->namamenu.'</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">'.$str2.'</ul>
				  </li>';
			}

			$i++;
		}
		
			$str1 .= '
				  <li class="menu-header">
				  <a href="'. base_url().'home/index/laporan">
					<i class="fa fa-book"></i> <span>Laporan</span>
					<span class="pull-right-container">
					</span>
				  </a>
				  </li>';
	}

	echo $str1;
?>