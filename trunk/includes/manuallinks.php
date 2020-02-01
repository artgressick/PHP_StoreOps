<?php
	$tmpCat = "";	//dtn: Temporary Category place holder to see if the object is part of the last category.
	$oldCat = "";	//dtn: Old Category place holder for the javascript that needs to know when we are out of the category while still remembering what the old category was.
	$cnt = 0;		//dtn: Basic counter to make sure we don't stick the javascript into nothing on the first run through.
	
	if(!isset($_SESSION['chrSearch'.$info['ID']]) || $_SESSION['chrSearch'.$info['ID']] == '') { 
		$_SESSION['chrSearch'.$info['ID']] = '';
		$_SESSION['intSearch'.$info['ID']] = 0;
	} else if($_SESSION['chrSearch'.$info['ID']] != '') {
		$search_results = 0;
		//$search_words = explode(' ',encode($_SESSION['chrSearch'.$info['ID']]));
		$manual_check = db_query("SELECT chrKEY FROM Manuals WHERE ID='".$info['ID']."' AND !bDeleted AND bShow AND (lower(txtPage) LIKE lower('%".encode($_SESSION['chrSearch'.$info['ID']])."%') OR lower(chrManual) LIKE lower('%".encode($_SESSION['chrSearch'.$info['ID']])."%'))","Checking Manual for search words",1);
		$pages_search = db_query("SELECT GROUP_CONCAT(DISTINCT chrKEY SEPARATOR ',') AS chrResults FROM Pages WHERE !bDeleted AND bShow AND idManual='".$info['ID']."' AND (lower(txtPage) LIKE lower('%".encode($_SESSION['chrSearch'.$info['ID']])."%') OR lower(chrTitle) LIKE lower('%".encode($_SESSION['chrSearch'.$info['ID']])."%')) GROUP BY idManual ORDER BY idParent, dOrder","Getting Page Results",1);
		if($_SESSION['intSearch'.$info['ID']]++ == 0) {
			if($manual_check['chrKEY'] != '') {
				header("Location: index.php?key=".$manual_check['chrKEY']);
				die();	
			} else {
				if($pages_search['chrResults'] != '') {
					$search_temp = explode(',',$pages_search['chrResults']);
					header("Location: page.php?key=".$search_temp[0]);
					die();	
				}
			}
		} else {
			if($pages_search['chrResults'] != '') {
				$search_temp = explode(',',$pages_search['chrResults']);
				$search_results = count($search_temp);
			}
			if($manual_check['chrKEY'] != '') { $search_results++; } 
		}
	}
	
	
	
	$page_results = db_query("SELECT ID, chrKEY, chrTitle, idParent, dOrder, 
									(SELECT GROUP_CONCAT(LPAD(P.ID,8,'0') ORDER BY dOrder SEPARATOR ',') 
										FROM Pages AS P 
										WHERE P.idParent=Pages.ID AND P.bShow AND !P.bDeleted GROUP BY idParent) as chrChildren 
							FROM Pages 
							WHERE !bDeleted AND bShow AND idManual='".$info['ID']."' ORDER BY idParent,dOrder, chrTitle","Getting Pages");
	$pages = array();
	$temp_pages = array();
	$nav = array();
	$temp = array();
	while($row = mysqli_fetch_assoc($page_results)) {
			$row['ID'] = str_pad($row['ID'],8,0,'STR_PAD_LEFT');
			$row['idParent'] = str_pad($row['idParent'],8,0,'STR_PAD_LEFT');
		if(isset($temp_pages[$row['idParent']]) && $row['idParent'] != 0) { 
			$dOrder = $temp_pages[$row['idParent']]['order'].':'.$row['dOrder'].','.$row['ID'];
		} else { $dOrder = $row['dOrder']; }
		if(($row['idParent'] == 0) || ($row['idParent'] != 0 && isset($temp_pages[$row['idParent']]))) {
			$temp_pages[$row['ID']] = array('order'=>$dOrder,'ID'=>$row['ID'],'chrKEY'=>$row['chrKEY'],'chrTitle'=>$row['chrTitle'],'idParent'=>$row['idParent'],'chrChildren'=>$row['chrChildren']);
		}
	}
	
	foreach($temp_pages AS $k => $data) {
		$temp[$data['order']] = $data;
	}
	unset($temp_pages); 

	uksort($temp, "strnatcasecmp");
	//ksort($temp);
	foreach($temp AS $k => $data) {
		$pages[$data['ID']]	= $data; 
	}	
	
	foreach($pages AS $id => $row) {
		$leftpadding = (substr_count($row['order'],':')*13);
		$cnt++;
		$nav_class = "";
		if (isset($current_man_page) && $row['chrKEY'] == $current_man_page) {
			$nav_class = " nav_highlight";
		} else if(isset($pages_search['chrResults']) && in_csv($row['chrKEY'], $pages_search['chrResults'])) {
			$nav_class = " nav_highlight2";
		}
		
		if($row['chrChildren'] == '') {
	
			$nav[$row['ID']] = "
				<div class='".($row['idParent'] == 0 ? "nav_cat" : "nav_cat2")."'>
					<table cellpadding='0' cellspacing='0' style='width:100%;'>
						<tr>
							<td class='icon' style='padding-left:".($leftpadding==0 ? '0' : $leftpadding."px").";'><img src='".$BF."images/blank.png' /></td>
							<td class='category".$nav_class."' onclick=\"location.href='page.php?key=".$row['chrKEY']."';\">".$row['chrTitle']."</td>
						</tr>
					</table>
				</div>";
						
		} else if($row['chrChildren'] != '') {
			if(isset($_COOKIE['P'.$row['chrKEY']]) && $_COOKIE['P'.$row['chrKEY']] == 'open') {
				$toggle_icon = 'open';
			} else {
				$toggle_icon = 'closed';
			}
			$nav[$row['ID']] = "
				<div class='".($row['idParent'] == 0 ? "nav_cat" : "nav_cat2")."'>
					<table cellpadding='0' cellspacing='0' style='width:100%;'>
						<tr>
							<td class='icon' style='padding-left:".($leftpadding==0 ? '0' : $leftpadding."px").";' onclick=\"toggle('P".$row['chrKEY']."');\"><img id='img_P".$row['chrKEY']."' src='".$BF."images/".$toggle_icon.".png' /></td>
							<td class='category".$nav_class."' onclick=\"location.href='page.php?key=".$row['chrKEY']."';\">".$row['chrTitle']."</td>
						</tr>
					</table>
					<div id='P".$row['chrKEY']."' class='".$toggle_icon."'>";
			$children = explode(',',$row['chrChildren']); 
			foreach ($children AS $k => $ID) {
				$nav[$row['ID']] .= '
						$CHILD_'.$ID;
			}
			$nav[$row['ID']] .= "
					</div>
				</div>
			";
		}
	}
	
	$nav_class = "";
	if (!isset($current_man_page)) {
		$nav_class = " nav_highlight";
	} else if(isset($manual_check['chrKEY']) && $manual_check['chrKEY'] == $info['chrKEY']) {
		$nav_class = " nav_highlight2";
	}
	
	$navigation = "
		<div id='applesearch'>
			<form action='' method='post' id='idSearch' style='padding:0; margin:0;'>	
				<span class='sbox_l'></span><span class='sbox'><input type='text' id='srch_fld' style='width:115px; padding-top:2px' class='sbox_field' placeholder='".$_SESSION['chrLanguage']['search']."' title='".$_SESSION['chrLanguage']['search']."' autosave='storeops_srch' value='".$_SESSION['chrSearch'.$info['ID']]."' name='chrSearch".$info['ID']."' results='5' onkeyup='applesearch.onChange(\"srch_fld\",\"srch_clear\")' /></span><span class='sbox_r' id='srch_clear'></span>
			</form>
		</div>
		<div style='clear:both;'></div>
		".($_SESSION['chrSearch'.$info['ID']] != '' ? "<div style='color:blue; font-weight:bold;'>".$search_results." ".$_SESSION['chrLanguage']['articles_found']."</div>":"")."
		<div class='nav_cat'>
			<table cellpadding='0' cellspacing='0' style='width:100%;'>
				<tr>
					<td class='category".$nav_class."' onclick=\"location.href='index.php?key=".$info['chrKEY']."';\">".$info['chrManual']." ".$_SESSION['chrLanguage']['home']."</td>
				</tr>
				<tr>
					<td class='category' onclick=\"location.href='export_book.php?key=".$info['chrKEY']."';\">".$_SESSION['chrLanguage']['print_articles']."</td>
				</tr>
			</table>
		</div>";
	
	$cnt = 0;
	foreach($pages AS $id => $row) {
		if(preg_match('/\$CHILD_'. $row['ID'] .'/',$navigation)) {
			$navigation = preg_replace('/\$CHILD_'. $row['ID'] .'/',$nav[$row['ID']],$navigation);
			//$navigation = str_replace('$CHILD_'.$row['ID'],$nav[$row['ID']],$navigation);
		} else {
			$navigation .= $nav[$row['ID']];
		}
		unset($pages[$id]);
	}
	
	if($cnt == 0) {
		$navigation .= "&nbsp;";
	}
?>