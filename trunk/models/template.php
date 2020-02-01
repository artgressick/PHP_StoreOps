<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?=(isset($title) && $title != '' ? $title .' - ' : '')?><?=$_SESSION['chrLanguage']['store_operations']?></title>
		<link href="<?=$BF?>includes/global.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript' language="JavaScript" src="<?=$BF?>includes/nav.js"></script>
		<script type='text/javascript'>var BF = '<?=$BF?>';</script>
<?		# If the "Stuff in the Header" function exists, then call it
		if(function_exists('sith')) { sith(); } 
?>
	</head>
	<body onload="<?=(isset($bodyParams) ? $bodyParams : '')?><? if(isset($_SESSION['bodyParams'])) { echo $_SESSION['bodyParams']; unset($_SESSION['bodyParams']); } ?>" style='background:<?=(isset($page_background) && $page_background != '' ? $page_background : '#FFF')?>;'>
		<table cellpadding="0" cellspacing="0" height='100%' width='1000' align='center'>
	    <!-- #Begin top part -->
			<tr>
		    	<td style='height:85px; vertical-align:top;'>
		    		<table cellpadding='0' cellspacing='0' class='banner'>
		    			<tr>
		    				<td class='left'><!-- BLANK --></td>
		    				<td class='middle'>
		    					<table cellpadding='0' cellspacing='0' class='text'>
		    						<tr>
		    							<td class='text-left'><div><?=$_SESSION['chrLanguage']['myrnn']?></div><? if(isset($banner_title) && $banner_title != '') { ?><div class='section'><?=$banner_title?></div><? } ?></td>
<?
									if(isset($banner_instructions) && $banner_instructions != '') { 
?>
		    							<td class='text-middle'><?=$banner_instructions?></td>
<?
									}
?>
		    							<td class='text-right'>
<?
										if(isset($_SESSION['idUser']) || isset($_COOKIE['idStore'])) {
											if(isset($_SESSION['idUser'])) {
?>
												<div><?=$_SESSION['chrLanguage']['welcome']?> <?=$_SESSION['chrFirst'].' '.$_SESSION['chrLast']?>. (<a href="?logout=1"><?=$_SESSION['chrLanguage']['logout_text']?></a>)<div>
<?
											}
											if(isset($_COOKIE['idStore'])) {
?>
												<div><?=$_SESSION['chrLanguage']['logged_into_store']?> <?=$_SESSION['chrStore']?>. (<a href='<?=$BF?>stores.php'><?=$_SESSION['chrLanguage']['change_store']?></a>)<div>
<?	
											}
										} else {
											echo '<!-- BLANK -->';
										}
										if(isset($_SESSION['LangIcons']) && count($_SESSION['LangIcons']) > 0) {
?>		    							
											<table cellpadding='3' cellspacing='0' style='padding-top:3px; width:100%;'>
												<tr>
													<td style='width:100%;'>&nbsp;</td>
<?
											foreach($_SESSION['LangIcons'] AS $id => $icon) {
?>
													<td style='width:10px; white-space:nowrap; text-align:center; vertical-align:top; cursor:pointer;' onclick='location.href="?idSetLanguage=<?=$id?>";'><img src='<?=$BF?>images/geoflags/<?=$icon['icon']?>' alt='<?=$icon['chrLang']?>' title='<?=$icon['chrLang']?>' style='width:20px; height:20px; cursor:pointer;' /><br /><span style='font-size:8px;'><?=$icon['chrLang']?></span></td>
<?												
											}
?>
												</tr>
											</table>
<?
										}
?>
		    							</td>
		    						</tr>
		    					</table>
		    				</td>
		    				<td class='right'><!-- BLANK --></td>
		    			</tr>
		    		</table>
<?
//	echo "<pre>"; print_r($_SESSION); echo "</pre>"; // This is to display the SESSION variables, unrem to use
//	echo "<pre>"; print_r($_COOKIE); echo "</pre>"; // This is to display the SESSION variables, unrem to use
?>
		    	</td>
		    </tr>
		    <tr>
		    	<td style='vertical-align:top; text-align:center;'>
<?
	# These are the links at the top on page
?>
					<table cellpadding='5' cellspacing='5' class='headerlinks' align='center'>
						<tr>
							<td><a href='<?=$BF?>'<?=($section=='home'?" class='linkselected'":'')?> style='color:<?=$headerlink_color?>;'><?=$_SESSION['chrLanguage']['home']?></a></td>
<?
					if(isset($_COOKIE['idStore']) && is_numeric($_COOKIE['idStore'])) {
?>
							<td><a href='<?=$BF?>escalator/'<?=($section=='escalator'?" class='linkselected'":'')?> style='color:<?=$headerlink_color?>;'><?=$_SESSION['chrLanguage']['escalator']?></a></td>
<?
						$manuals = db_query("SELECT ID, chrKEY, chrManual FROM Manuals WHERE bShow AND !bResource AND !bDeleted AND idLanguage='".$_COOKIE['StoreOpsLanguage']."' ORDER BY dOrder, chrManual","Getting Manuals");
						while($manual = mysqli_fetch_assoc($manuals)) {
?>
							<td><a href='<?=$BF?>manuals/?key=<?=$manual['chrKEY']?>'<?=($section=='MAN'.$manual['chrKEY']?" class='linkselected'":'')?> style='color:<?=$headerlink_color?>;'><?=$manual['chrManual']?></a></td>
<?							
						}
						$manuals2 = db_query("SELECT ID, chrKEY, chrManual FROM Manuals WHERE bShow AND bResource AND !bDeleted AND idLanguage='".$_COOKIE['StoreOpsLanguage']."' ORDER BY dOrder, chrManual","Getting Resource Manuals");
						if(mysqli_num_rows($manuals2)) {
?>
<td>
<ul id="sddm">
    <li><a href="#" 
        onmouseover="mopen('m1')" 
        onmouseout="mclosetime()" style='color:<?=$headerlink_color?>;'><?=$_SESSION['chrLanguage']['resources']?></a>
        <div id="m1" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
<?
							while($manual = mysqli_fetch_assoc($manuals2)) {
?>
									<?=linkto(array('address'=>$BF.'manuals/?key='.$manual['chrKEY'],'display'=>$manual['chrManual']))?>
<?
							}
?>
        </div>
    </li>
 </ul>
<div style="clear:both"></div>
</td>
<?
						}
?>

							<td><a href='<?=$BF?>storehours/'<?=($section=='storehours'?" class='linkselected'":'')?> style='color:<?=$headerlink_color?>;'><?=$_SESSION['chrLanguage']['store_hours']?></a></td>
<?
					}
?>
							<td><a href='<?=$BF?>admin/'<?=($section=='admin'?" class='linkselected'":'')?> style='color:<?=$headerlink_color?>;'><?=$_SESSION['chrLanguage']['admin']?></a></td>
						</tr>
					</table>
		    	
		    	</td>
		    </tr>
		    <tr>
		    	<td style='height:100%; vertical-align:top; padding:0px 7px;'>
					<table cellpadding='0' cellspacing='0' style='width:100%'>
						<tr>
							<td style='width:188px; vertical-align:top;'>
<?
	if(!isset($navigation) && isset($toolbar_links)) {
		$navigation = "
						<div>
							".(isset($toolbar_title) && $toolbar_title!=''?"<div class='toolbar_title'>".$toolbar_title."</div>" : "");
		$lastcat = '';
		foreach($toolbar_links AS $k => $data) {
			if(isset($data['cat']) && $data['cat'] != $lastcat) {
				$navigation .= "
							<div class='toolbar_cat''>".$data['cat']."</div>";
				$lastcat = $data['cat'];
			}
			$navigation .= "
							<div class='toolbar_link'".(isset($data['style']) && $data['style'] != '' ? " style='".$data['style']."'" : '')."'>".$data['link']."</div>";
		}
		
		$navigation .= "
						</div>
					";

	}
	if(isset($_SESSION['intAttempts'])) {
		if($_SESSION['intAttempts'] != 0) { 
			$_SESSION['errorMessages'][] = "There has been ".$_SESSION['intAttempts']." failed login attempts on this account since your last login!";
		}
		unset($_SESSION['intAttempts']);
	}
	
?>
								<?=framebox($navigation)?>
							</td>
							<td style='vertical-align:top;'>
								<table cellpadding='0' cellspacing='0' class='framebox' style='width:100%;'>
									<tr>
										<td class='fbtl'><!-- BLANK --></td>
										<td class='fbtm'><!-- BLANK --></td>
										<td class='fbtr'><!-- BLANK --></td>
									</tr>
									<tr>
										<td class='fblm' style='background: url(<?=$BF?>images/frame-lm-white.png) repeat-y;'><!-- BLANK --></td>
										<td class='fbm' style='background: url(<?=$BF?>images/frame-m-white.png);'>
											<!-- Begin code -->
											<table cellpadding='0' cellspacing='0' style='width:100%;'>
												<tr>
													<td style='text-align:left; vertical-align:middle;'>
<?
										if(isset($page_title) && $page_title != '') {
?>
				 							<div class='header2' style='padding-bottom:5px;'><?=$page_title?></div>
<?
										}
?>
													</td>
<?
										if(isset($filter) && $filter != '') {
?>
													<td style='text-align:right; vertical-align:middle;'>
														<form method='get' action='' id='FormFilter'>
														<?=$filter?>
															<input type='submit' value='<?=$_SESSION['chrLanguage']['filter']?>' />
														</form>
													</td>
<?
										}
?>
												</tr>
											</table>
												
				 							<?=messages();?>
<?
										if(isset($page_instructions) && $page_instructions != '') {
?>
											<table cellpadding='0' cellspacing='0' style='width:100%;' class='instructions'>
												<tr>
													<td class='left'><?=$page_instructions?></td>
													<? if(isset($page_instructions2) && $page_instructions2 != '') { ?><td class='right'><?=$page_instructions2?></td><? } ?>
												</tr>
											</table>
<?
										}
?>
											<?=(isset($sitm) && $sitm != '' && function_exists($sitm) ? $sitm() : sitm())?>
											<!-- End code -->
										</td>
										<td class='fbrm' style='background: url(<?=$BF?>images/frame-rm-white.png) repeat-y;'><!-- BLANK --></td>
									</tr>
									<tr>
										<td class='fbbl'><!-- BLANK --></td>
										<td class='fbbm'><!-- BLANK --></td>
										<td class='fbbr'><!-- BLANK --></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
		    	</td>
		    </tr>
		    <tr>
		    	<td style='height:25px; vertical-align:bottom;'>
		    		<div style='text-align:center;'>
		    			<div style='font-weight:bold;'>Confidential: Apple Internal Use Only</div>
						<div>Copyright &copy; <?=date('Y')?> Apple Inc.</div>
					</div>
		    	</td>
		    </tr>
		</table>
<?
	# Any aditional things can go down here including javascript or hidden variables
	# "Stuff on the Bottom"
	if(function_exists('sotb')) { sotb(); } 
?>
	</body>
</html>
