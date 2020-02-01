<?php

	$tmpCat = "";	//dtn: Temporary Category place holder to see if the object is part of the last category.
	$oldCat = "";	//dtn: Old Category place holder for the javascript that needs to know when we are out of the category while still remembering what the old category was.
	$cnt = 0;		//dtn: Basic counter to make sure we don't stick the javascript into nothing on the first run through.
	if(isset($_COOKIE['idStore']) && !isset($storeinfo)) { 
		$storeinfo = check_idStore();
	}
	$templates = db_query("SELECT T.ID, T.chrKEY, T.chrTitle, C.chrCategory, C.chrKEY AS chrCatKEY
		FROM EscalatorTemplates AS T
		JOIN EscalatorCats AS C ON T.idCategory=C.ID
		WHERE !T.bDeleted AND !C.bDeleted AND C.idLanguage='".$_COOKIE['StoreOpsLanguage']."' AND T.idLanguage='".$_COOKIE['StoreOpsLanguage']."' AND T.bShow
		ORDER BY C.dOrder, C.chrCategory, T.dOrder, T.chrTitle
	","getting templates");
	
	$prevcat='';
	$navigation = '';
	
	$navigation = "<div class='leftlinks".(isset($leftlink) && $leftlink=='view_escalation'?" left_highlight":"")."' onclick=\"location.href='escalations.php';\">&bull; ".$_SESSION['chrLanguage']['view_escalation']."</div>";
	
	while($row = mysqli_fetch_assoc($templates)) { 
		if(isset($_COOKIE['E'.$row['chrCatKEY']]) && $_COOKIE['E'.$row['chrCatKEY']] == 'open') {
			$toggle_icon = 'open';
		} else {
			$toggle_icon = 'closed';
		}
		if($tmpCat != $row['chrCatKEY']) {
			if($cnt++ > 0) { 
				$navigation .= "
					</div>
				</div>";
			} 
			$oldCat = $tmpCat = $row['chrCatKEY'];

			$navigation .= "
				<div class='nav_cat'>
					<table cellpadding='0' cellspacing='0' style='width:100%;'>
						<tr onclick=\"toggle('E".$row['chrCatKEY']."');\">
							<td class='icon'><img id='img_E".$row['chrCatKEY']."' src='".$BF."images/".$toggle_icon.".png' /></td>
							<td class='category'>".$row['chrCategory']."</td>
						</tr>
					</table>
					<div id='E".$row['chrCatKEY']."' class='".$toggle_icon."'>";	
		}
		
		$nav_class = "";
		if(isset($_SESSION['Search_Result_Keys']) && in_cvs($row['chrKEY'], $_SESSION['Search_Result_Keys'])) {
			$nav_class = " nav_highlight";
		} else if (isset($current_esc_page) && $row['chrKEY'] == $current_esc_page) {
			$nav_class = " nav_highlight";
		}
		
		$navigation .= "
					<div class='nav_item".$nav_class."' onclick=\"location.href='template.php?key=".$row['chrKEY']."';\">".$row['chrTitle']."</div>
		";
	}
	if($cnt > 0) { 
		$navigation .= "
			</div>
		</div>";
	} else {
		$navigation .= "&nbsp;";
	}
?>