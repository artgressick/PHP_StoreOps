<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?=(isset($title) && $title != '' ? $title .' - ' : '')?><?=$_SESSION['chrLanguage']['store_operations']?></title>
		<link href="<?=$BF?>includes/global.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript'>var BF = '<?=$BF?>';</script>
<?		# If the "Stuff in the Header" function exists, then call it
		if(function_exists('sith')) { sith(); } 
?>
	</head>
	<body onload="<?=(isset($bodyParams) ? $bodyParams : '')?>" style='background:<?=(isset($page_background) && $page_background != '' ? $page_background : '#FFF')?>;'>
		<table cellpadding="0" cellspacing="0" height='100%' width='100%' align='center'>
	    <!-- #Begin top part -->
		    <tr>
		    	<td style='height:100%; vertical-align:top; padding:0px 7px;'>
					<table cellpadding='0' cellspacing='0' style='width:100%'>
						<tr>
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
													<td style='text-align:right; vertical-align:middle;'>
														<input type='button' value='Close Window' onclick='javascript:window.close();' />
													</td>
												</tr>
											</table>
												
				 							<?=messages();?>
<?
										if(isset($page_instructions) && $page_instructions != '') {
?>
				 							<div class='instructions'><?=$page_instructions?></div>
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
		</table>
<?
	# Any aditional things can go down here including javascript or hidden variables
	# "Stuff on the Bottom"
	if(function_exists('sotb')) { sotb(); } 
?>
	</body>
</html>
