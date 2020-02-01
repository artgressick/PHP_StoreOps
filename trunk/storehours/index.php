<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info,$hours,$storeinfo;
		
		list($intMonth,$intYear,$firstDisplayDay,$daysThisMonth,$daysLastMonth) = get_dates($_REQUEST['d']);

		$newhours = db_query("SELECT * FROM HolidayStoreHours WHERE dDate BETWEEN '".$intYear.'-'.$intMonth."-01' AND '".$intYear.'-'.$intMonth.'-'.$daysThisMonth."' AND idStore='".$storeinfo['ID']."'","Getting Holiday Hours");
		while($row = mysqli_fetch_assoc($newhours)) {
			$holidaystorehours[$row['dDate']] = $row;	
		}
		
		$pMonth = $_SESSION['chrLanguage'][date('F',strtotime($intYear.'-'.$intMonth.'-01 -1 month'))].' '.date('Y',strtotime($intYear.'-'.$intMonth.'-01 -1 month'));
		$pMLink = date('mY',strtotime($intYear.'-'.$intMonth.'-01 -1 month'));
		$tMonth = $_SESSION['chrLanguage'][date('F',strtotime($intYear.'-'.$intMonth.'-01'))].' '.date('Y',strtotime($intYear.'-'.$intMonth.'-01'));
		$nMonth = $_SESSION['chrLanguage'][date('F',strtotime($intYear.'-'.$intMonth.'-01 +1 month'))].' '.date('Y',strtotime($intYear.'-'.$intMonth.'-01 +1 month'));
		$nMlink = date('mY',strtotime($intYear.'-'.$intMonth.'-01 +1 month'));
		
		$pRange = date('Y-m-d',strtotime($intYear.'-'.$intMonth.'-01 -1 day'));
		$nRange = date('Y-m-d',strtotime($intYear.'-'.$intMonth.'-01 +1 month'));
		
		$tmpholidays = db_query("SELECT ID, chrHoliday, dBegin, dEnd, chrText, chrBack FROM Holidays WHERE !bDeleted AND bShow AND idCountry='".$storeinfo['idCountry']."' AND dBegin BETWEEN '".$pRange."' AND '".$nRange."' AND dEnd BETWEEN '".$pRange."' AND '".$nRange."'","Getting any holiday info");
		$holidays = array();
		if(mysqli_num_rows($tmpholidays) > 0) {
			while($row = mysqli_fetch_assoc($tmpholidays)) {
				$totalDays = (strtotime($row['dEnd']) - strtotime($row['dBegin']))/60/60/24;
				$i=0;
				$dCurrent = $row['dBegin'];
				while($i <= $totalDays) {
					$holidays[$dCurrent] = $row;
					$dCurrent = date('Y-m-d',strtotime($row['dBegin']." + ".($i++ + 1)." days"));
				}
			}
		}
		
		//Do we need to fill out any holiday hours?
		$holidayhours = db_query("SELECT ID,chrKEY,chrHoliday 
									FROM Holidays
									WHERE !bDeleted AND bShow AND idCountry='".$storeinfo['idCountry']."' AND ID NOT IN (SELECT idHoliday FROM HolidayStoreHours WHERE idStore='".$storeinfo['ID']."' AND !bDeleted GROUP BY idHoliday)","Checking for missing holidays");
		
?>
		<div class='index'>
			<table cellpadding='0' cellspacing='0' style='width:100%;' class='SH'>
				<tr>
					<td class='al' onclick='location.href="index.php?d=<?=$pMLink?>";'><img src='<?=$BF?>images/arrow_left.png' title='<?=$pMonth?>' /></td>
					<td class='pm' onclick='location.href="index.php?d=<?=$pMLink?>";'><?=$pMonth?></td>
					<td class='tm'><?=$storeinfo['chrStore']?> - <?=$tMonth?></td>
					<td class='nm' onclick='location.href="index.php?d=<?=$nMlink?>";'><?=$nMonth?></td>
					<td class='ar' onclick='location.href="index.php?d=<?=$nMlink?>";'><img src='<?=$BF?>images/arrow_right.png' title='<?=$nMonth?>' /></td>
				</tr>
			</table>
<?
		if(mysqli_num_rows($holidayhours) > 0) {
?>
		<div style='padding-bottom:10px;'>
			<div style='padding:3px; background:lightgrey; color:red; font-weight:bold; font-size:13px; border:1px solid #999;'><?=$_SESSION['chrLanguage']['please_fill_holiday_hours']?></div>
<?	
			while($row = mysqli_fetch_assoc($holidayhours)) {
?>
			<div style='padding:3px; border:1px solid #999; border-top:none; font-weight:bold; cursor:pointer;' onclick='location.href="holidayhours.php?key=<?=$row['chrKEY']?>";'><?=$row['chrHoliday']?></div>
<?				
			}
?>
		</div>
<?
		}
?>

			<table cellpadding='0' cellspacing='0' class='calmonth'>
				<tr class="days">
					<th><?=$_SESSION['chrLanguage']['Sunday']?></th>
					<th><?=$_SESSION['chrLanguage']['Monday']?></th>
					<th><?=$_SESSION['chrLanguage']['Tuesday']?></th>
					<th><?=$_SESSION['chrLanguage']['Wednesday']?></th>
					<th><?=$_SESSION['chrLanguage']['Thursday']?></th>
					<th><?=$_SESSION['chrLanguage']['Friday']?></th>
					<th><?=$_SESSION['chrLanguage']['Saturday']?></th>
				</tr>
				<tr>
<?	
	$weekDayInt = 0;
	$intMonthDay = 0;
	while($firstDisplayDay != 1) { ?>
					<td class='diffmonth'><div class='dom'><?=($daysLastMonth + $firstDisplayDay)?></div></td>
<?			$weekDayInt++;
			$firstDisplayDay += 1;
	}

	while($intMonthDay < $daysThisMonth) { 
				
		if($weekDayInt == 7) { $weekDayInt = 0;
?>
				</tr>
				<tr>
<?
		} 
			$weekDayInt++; 
			$intMonthDay++; 
			$dThis = $intYear.'-'.$intMonth.'-'.($intMonthDay < 10 ? '0'.$intMonthDay : $intMonthDay);
			$yThis = date('Y-m-d',strtotime($dThis.' -1 day'));
			$tThis = date('Y-m-d',strtotime($dThis.' +1 day'));
			$dow = date('w',strtotime($dThis));
			$cw = date('w',strtotime($dThis))==0 ? date('W',strtotime($dThis.' +1 day')) : date('W',strtotime($dThis));
			if(isset($holidaystorehours[$dThis])) {
				if($holidaystorehours[$dThis]['bClosed']==1) {
					$hourhead = '';
					$displayhours = "<span style='font-style: italic; font-weight:bold;'>".$_SESSION['chrLanguage']['closed']."</span>";
					if(date('Y-m-d') == $dThis) { $cellclass = 'todaybox'; } else { $cellclass = 'dayclosed'; }
				} else {
					$hourhead = "<div style='text-align:center; font-weight:bold;'>".$_SESSION['chrLanguage']['store_hours']."</div>";
					$displayhours = "<span style='font-size:9px;'>".date($_SESSION['chrLanguage']['php_hours'],strtotime($holidaystorehours[$dThis]['tOpening']))." - ".date($_SESSION['chrLanguage']['php_hours'],strtotime($holidaystorehours[$dThis]['tClosing']))."</span>";
					if(date('Y-m-d') == $dThis) { $cellclass = 'todaybox'; } else { $cellclass = 'daybox'; }
				}
			} else if($hours[$dow]['bClosed']==1) {
				$hourhead = '';
				$displayhours = "<span style='font-style: italic; font-weight:bold;'>".$_SESSION['chrLanguage']['closed']."</span>";
				if(date('Y-m-d') == $dThis) { $cellclass = 'todaybox'; } else { $cellclass = 'dayclosed'; }
			} else {
				$hourhead = "<div style='text-align:center; font-weight:bold;'>".$_SESSION['chrLanguage']['store_hours']."</div>";
				$displayhours = "<span style='font-size:9px;'>".$hours[$dow]['tOpening']." - ".$hours[$dow]['tClosing']."</span>";
				if(date('Y-m-d') == $dThis) { $cellclass = 'todaybox'; } else { $cellclass = 'daybox'; }
			}
?>
					<td class='<?=$cellclass?>'>
						<table cellspacing='0' cellpadding='0' style='width:100%; height:100%; table-layout: fixed;'>
							<tr><td class='dom'><?=$intMonthDay?></td></tr>
							<tr><td style='height:100%;' class=''><!-- Holiday Section -->
<?
							if(isset($holidays[$dThis]) && $holidays[$dThis]['chrHoliday'] != '') {
?>
								<table cellspacing='0' cellpadding='0' style='width:100%;'>
									<tr> 
<?
								//did this not happen yesterday?
								if(!isset($holidays[$yThis]) || $holidays[$yThis]['chrHoliday'] != $holidays[$dThis]['chrHoliday']) {
?>
										<td style='height:17px;width:7px; background:<?=$holidays[$dThis]['chrBack']?>;' title='<?=$holidays[$dThis]['chrHoliday']?>'><img src='<?=$BF?>images/calendar-cap-left.png' /></td>
<?
									$leftcap=true;
								} else { $leftcap=false; }
								$displaytext = '&nbsp;';
								if(!isset($displayed[$cw][$holidays[$dThis]['chrHoliday']])) {
									$displayed[$cw][$holidays[$dThis]['chrHoliday']]=true;
									$displaytext = $holidays[$dThis]['chrHoliday'];
									
									$ebd = $holidays[$dThis]['dBegin'];
									$eed = $holidays[$dThis]['dEnd'];
									$ebw = date('w',strtotime($ebd))==0 ? date('W',strtotime($ebd.' +1 day')) : date('W',strtotime($ebd));
									$eew = date('w',strtotime($eed))==0 ? date('W',strtotime($eed.' +1 day')) : date('W',strtotime($eed));
									
									if($ebw == $eew) {  // Event begins and ends on same week
										if (ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1) < 7) {
											$days = ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1);
											$maxtextlength = 15 * $days;
										} else {
											$days = (strtotime($eed) - strtotime($ebd)) / (60 * 60 * 24) +1;
											$maxtextlength = 15 * $days;
										}
										//echo 1;
									} else if ($cw == $eew) { // Event ends on the current week
										$days = ceil((strtotime($eed) - strtotime($dThis)) / (60 * 60 * 24) +1);
										$maxtextlength = 15 * $days;
										//echo 2; 
									} else if (ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1) < 7) {
										$days = ceil((strtotime($intYear.'-'.$intMonth.'-'.$daysThisMonth) - strtotime($dThis)) / (60 * 60 * 24) +1);
										$maxtextlength = 15 * $days;
										//echo 3;
									} else { // Event doesn't end until future week
										$maxtextlength = 109 - ((date('w',strtotime($dThis))) * 15);
										//echo 4;
									}
									$displaytext = (strlen($displaytext) > $maxtextlength ? substr($displaytext,0,$maxtextlength).'..' : $displaytext);
								}	
								
								
?>
										<td style='<?=($leftcap==false && $displaytext!='&nbsp;'?'padding-left:7px; ': '')?>white-space:nowrap; vertical-align:middle; height:17px; width:auto; color:<?=$holidays[$dThis]['chrText']?>; background: <?=$holidays[$dThis]['chrBack']?> url(<?=$BF?>images/calendar-cap-middle.png);' title='<?=$holidays[$dThis]['chrHoliday']?>'><?=$displaytext?></td>
<?
								if(!isset($holidays[$tThis]) || $holidays[$tThis]['chrHoliday'] != $holidays[$dThis]['chrHoliday']) {
?>
										<td style='height:17px;width:7px; background:<?=$holidays[$dThis]['chrBack']?>;' title='<?=$holidays[$dThis]['chrHoliday']?>'><img src='<?=$BF?>images/calendar-cap-right.png' /></td>
<?
								}
?>
									</tr>
								</table>
<?
								
							}
?>							
							</td></tr>
							<tr><td style='text-align:center;' class=''><?=$hourhead.$displayhours?></td></tr>
						</table>
					</td>
<? 	
	}
			
	$extraCnt = 1;
	while($weekDayInt++ < 7) { ?>
					<td class='diffmonth'><div class='dom'><?=$extraCnt++?></div></td>
<?	} ?>
					</td>
				</tr>
			</table>
		</div>
<pre>
<?

?></pre><?
	} ?>