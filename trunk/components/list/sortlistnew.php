<?	
	function sortList($table, $hash, $query, $linkto, $style='', $db_table='') { 
		global $BF, $total_results, $pstart, $LISTPERPAGE;
		if($db_table=='') { $db_table = $table; }
		if(!isset($_REQUEST['ordCol'])) { $_REQUEST['ordCol'] = ""; }
		
		if(!isset($_REQUEST['pPage']) || !is_numeric($_REQUEST['pPage']) || $_REQUEST['pPage'] <= 1) { $_REQUEST['pPage'] = "1"; $pstart=0; } else { $pstart = ($LISTPERPAGE*($_REQUEST['pPage']-1))+1; }
		
//		echo "<pre>";print_r($_SERVER);echo "</pre>";
//		echo $total_results;
		
		preg_match('/(\w)+\.php$/',$_SERVER['SCRIPT_NAME'],$file_name);
	    $this_file = $file_name[0];
		
		$query_str = explode('&',$_SERVER['QUERY_STRING']);
		// Lets get any and all request variables to put into a variable
		if(isset($_REQUEST['key'])) {
			$requestoptions = '?key='.$_REQUEST['key'];
		} else {
			$requestoptions = '';
		}
		
		if(isset($query_str) && count($query_str) > 0) {
			foreach($query_str AS $K => $value) {
				$temp = explode('=',$value);
				if($temp[0] != 'key' && $temp[0] != 'ordCol' && $temp[0] != 'sortCol' && $temp[0] != 'pPage' && $temp[1] != '') {
					if($requestoptions == "") {$requestoptions .= "?"; } else { $requestoptions .= "&"; }
					$requestoptions .= $temp[0].'='.$temp[1];
				}
			}
		}		
//		echo $requestoptions;
		$firstsortcol = explode(',',$_REQUEST['sortCol']);
		$total_results = mysqli_num_rows($query);
		$pages_shown=3;
		$pagi_html = "";
		if($total_results > $LISTPERPAGE) {
			$total_pages = ceil($total_results / $LISTPERPAGE);
			$link = $this_file.$requestoptions.($requestoptions!=''?'&':'?')."sortCol=".$_REQUEST['sortCol']."&ordCol=".$_REQUEST['ordCol']."&pPage=";
			if(($_REQUEST['pPage']*$LISTPERPAGE) > $total_results) { $pend = $total_results; } else { $pend = ($_REQUEST['pPage']*$LISTPERPAGE); }
			
			$pagi_html .= "
				<table style='border: 1px solid #999; ".(isset($style) ? $style : 'width: 100%;')."' cellpadding='0' cellspacing='0'>
					<tr>
						<td style='padding:5px; text-align:left; font-size:10px;'>
						".$total_results." results. Showing ".($pstart==0?'1':$pstart)." to ".$pend."
						</td>
						<td style='padding:5px;text-align:right; font-size:10px;'>
							<table cellpadding='0' cellspacing='0' align='right' class='pagination'>
								<tr>";

								if($_REQUEST['pPage'] > 1) {
							$pagi_html .= "
									<td><div class='button'>".linkto(array('address'=>$link.'1','display'=>'<<','title'=>'Goto First Page'))."</div></td>
									<td><div class='button'>".linkto(array('address'=>$link.($_REQUEST['pPage']-1),'display'=>'<','title'=>'Previous Page'))."</div></td>";
								}

								if($_REQUEST['pPage'] <= $pages_shown+1) {
									$start = 1; 
									if ($total_pages < ($_REQUEST['pPage'] + $pages_shown)) {
										$end = $total_pages;
									} else {
										$end = $_REQUEST['pPage'] + $pages_shown;
									}
								} else if ($_REQUEST['pPage'] >= $total_pages - $pages_shown) {
									$start = $_REQUEST['pPage'] - $pages_shown; 
									$end = $total_pages;
								} else {
									$start = $_REQUEST['pPage'] - $pages_shown; 
									$end = $_REQUEST['pPage'] + $pages_shown;
								}
					
								if ($start > 1) {
									$pagi_html .= "
									<td style='padding:0;margin:0;'>..</td>";
								}
		
								while ($start <= $end) {
									$pagi_html .= "
									<td><div class='button".($_REQUEST['pPage']==$start?'active':'')."'>".linkto(array('address'=>$link.$start,'display'=>$start,'title'=>'Goto Page '.$start))."</div></td>";
									$start++;					
								}				
								if ($end < $total_pages ) {
									$pagi_html .= "
									<td style='padding:0;margin:0;'>..</td>";
								}
	
								if($_REQUEST['pPage'] < $total_pages) {
									$pagi_html .= "
									<td><div class='button'>".linkto(array('address'=>$link.($_REQUEST['pPage']+1),'display'=>'>','title'=>'Next Page'))."</div></td>
									<td><div class='button'>".linkto(array('address'=>$link.$total_pages,'display'=>'>>','title'=>'Goto Last Page (Page '.$total_pages.')'))."</div></td>";
								}
							$pagi_html .= "									
								</tr>
							</table>
						</td>
						<td style='padding:5px;text-align:right; font-size:10px; width:20px;'>
							<select name='jumptopage' onchange='location.href=\"".$link."\"+this.value' title='Jump to Page #'>
								<option value=''>-Goto Page-</option>";

							$i = 0;	
							while($i++ < $total_pages) {
									
							$pagi_html .= "
								<option value='".$i."'>Page ".$i."</option>";
							}
						$pagi_html .= "
							</select>
						</td>
					</tr>
				</table>";
		}
?>
		<?=$pagi_html?>
		<table id='<?=$table?>' class='List' style='<?=(isset($style) ? $style : 'width: 100%;')?>' cellpadding="0" cellspacing="0">
			<thead>
				<tr>
<?
					foreach($hash as $k => $v) { 
						if(is_array($v) && !preg_match('/^opt_/',$k)) { 
							if($firstsortcol[0] == $k) { 
								$sortDir = strtolower($_REQUEST['ordCol']);
								$link = $this_file.$requestoptions.($requestoptions!=''?'&':'?')."sortCol=".$k.($sortDir != 'desc' ? '&ordCol=DESC':'&ordCol=ASC').(isset($_REQUEST['pPage']) && $_REQUEST['pPage'] > 1 ?'&pPage='.$_REQUEST['pPage']:'');
?>
								<th onclick='location.href="<?=$link?>"' class='ListHeadSortOn' <?=(isset($hash[$k]['style']) ? ' style="'. $hash[$k]['style'] .'"' : '')?>><?=$hash[$k]['displayName']?>&nbsp;<img src='<?=$BF?>components/list/column_sorted_<?=($sortDir != 'desc' ? 'asc' : 'desc')?>.gif' alt='sorted' style='vertical-align: bottom;' /></th>
<?
							} else {
								$link = $this_file.$requestoptions.($requestoptions!=''?'&':'?')."sortCol=".$k.'&ordCol=ASC'.(isset($_REQUEST['pPage']) && $_REQUEST['pPage'] > 1 ?'&pPage='.$_REQUEST['pPage']:'');
?>
								<th onclick='location.href="<?=$link?>"' <?=(isset($hash[$k]['sorttype']) ? ' class="sorttable_'. $hash[$k]['sorttype'] .'"' : '')?><?=(isset($hash[$k]['style']) ? ' style="'. $hash[$k]['style'] .'"' : '')?>><?=$hash[$k]['displayName']?>&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
<?
							}
						} else { 
							if(preg_match('/^opt_other/',$k) && $v == 'checkboxes') {
?>
								<th class="options sorttable_nosort"><input type=checkbox name="chkbutton" id="chkbutton" title="Check all" onClick="togglecheckboxes()"></th>
<?
							} else {			
?>
								<th<?=(preg_match('/^opt_/',$k) ? ' class="options sorttable_nosort"' : '')?>><img src='<?=$BF?>images/options.gif' alt='options' /></th>
<?
							}
						}
					} 
?>
			</tr>
		</thead>
		<tbody>
<?		$count = 0;
		if(mysqli_num_rows($query)) { 
			
			$linktype = (preg_match('/(\?|\&)key\=/',$linkto) ? 'chrKEY' : 'ID');
			mysqli_data_seek($query, $pstart);
			while($row = mysqli_fetch_assoc($query)) { 
?>			<tr id='<?=$table?>tr<?=$row['ID']?>' class='<?=($count++%2 ? 'ListOdd' : 'ListEven')?>' 
			onmouseover='RowHighlight("<?=$table?>tr<?=$row['ID']?>");' onmouseout='UnRowHighlight("<?=$table?>tr<?=$row['ID']?>");'>
<?	foreach($hash as $k => $v) { 
		if(is_array($v) && !preg_match('/^opt_/',$k)) {
			if($linkto != '') { 
?>			<td onclick='window.location.href="<?=$linkto?><?=$row[$linktype]?>"'><?=($k=='idEscStatus'?$_SESSION['chrLanguage']['esc_status_'.$row[$k]]:$row[$k])?></td>
<?			} else {
?>			<td class='nocursor'><?=($k=='idEscStatus'?$_SESSION['chrLanguage']['esc_status_'.$row[$k]]:$row[$k])?></td>
<?			}			
		} else { 
			if(preg_match('/^opt_del$/',$k)) { 
				if(preg_match('/,/',$v)) { 
					$tmpVal = explode(',',$v);
					$displayVal = "";
					foreach($tmpVal as $val) { $displayVal .= $row[$val]." "; }
					$displayVal = substr($displayVal,0,-1);
				} else {
					$displayVal = $row[$v];
				}
			?>			<td class='options'><? deleteButton($row['ID'],$displayVal,$row['chrKEY'],$table); ?></td> 		<?
			} else if (preg_match('/^opt_other/',$k)) { 
				if ($v == 'dOrder') {
?>					<td><?=orderBoxes($row['ID'],$row['dOrder'])?></td>
<?
				} else if($v == 'bShow') {
?>					<td><?=bShowicon($row['ID'],$row['bShow'],$db_table)?></td>
<?
				} else if($v == 'checkboxes') {
?>					<td><input type='checkbox' name='listids[]' id='<?=$table?>id<?=$row['ID']?>' value='<?=$row['ID']?>' onClick="multiselect(event,'<?=$table?>id<?=$row['ID']?>');"/></td>
<?
				} else if($v == 'version') {
?>					<td><?=$row['chrVersion']?></td>
<?
				}
				
			} else if (preg_match('/^opt_link$/',$k)) { 
				$v['address'].=(preg_match('/(\?|\&)key\=/',$linkto) ? $row['chrKEY'] : $row['ID']);
?>				<td><?=linkto($v)?></td>
<?	
			} else { 
?>				<td><?=$v?></td>
<?			}
		}
	} 
?>		
			</tr>
<?			
				if($count >= $LISTPERPAGE) { break; } 
			}
		} else {
?>
			<tr>
				<td colspan='<?=count($hash)?>' style='text-align:center;height:20px;vertical-align:middle;'>No records found in the database.</td>
			</tr> 	
<?		} ?>
		</tbody>
	</table>
	<?=$pagi_html?>
<?	} 



function deleteButton($id,$message,$chrKEY,$table) {
	global $BF;
	?><span class='deleteImage'><a href="javascript:warning(<?=$id?>, '<?=str_replace("&","&amp;",$message)?>','<?=$chrKEY?>','<?=$table?>');" title="Delete: <?=$message?>"><img id='deleteButton<?=$id?>' src='<?=$BF?>images/button_delete.png' alt='delete button' onmouseover='this.src="<?=$BF?>images/button_delete_on.png"' onmouseout='this.src="<?=$BF?>images/button_delete.png"' /></a></span><?
}

function orderBoxes($id,$value) {
	?><input type="text" size="3" name="dOrder<?=$id?>" id="dOrder<?=$id?>" value="<?=$value?>" /><?
}
function bShowicon($id,$value,$db_table) {
	global $BF;
	if($value==0) { 
		$icon = 'icon_off';
		$extra = 'onclick="update_bShow(\''.$db_table.'\',\''.$id.'\');"';
		$title = 'De-Active (Click to make Active)';
	} else if($value == 1) {
		$icon = 'icon_on';
		$extra = 'onclick="update_bShow(\''.$db_table.'\',\''.$id.'\');"';
		$title = 'Active (Click to make De-Active)';
	} else {
		$icon = 'icon_disabled';
		$extra = '';
		$title = 'Disabled (Parent is De-Active)';
	}
	?><img src='<?=$BF?>images/<?=$icon?>.png' id='bShow<?=$id?>' title='<?=$title?>' alt='<?=$title?>' <?=$extra?> /><input type='hidden' id='bShowValue<?=$id?>' value='<?=$value?>' /><?
}



global $BF;
?>
	<style type='text/css'>
	
		.List { border: 1px solid #999; padding: 0; margin: 0; }
		.List th { cursor:pointer; font-size: 10px; background: url(<?=$BF?>components/list/list_head.gif) repeat-x; height: 13px; border-bottom: 1px solid #999; padding: 3px 5px; font-weight: bold; text-align: left; white-space: nowrap; }
		.List td { padding: 0 5px; font-size: 11px; cursor: pointer; }
		.List .nocursor { cursor:auto; }
		.List th a { color: #333; text-decoration: none; }
		.List td a { color: black; text-decoration: none; }
		.List th.ListHeadSortOn { font-size: 10px; background: url(<?=$BF?>components/list/list_head_sortedby.gif); height: 13px; border-bottom: 1px solid #999; padding: 3px 5px; font-weight: bold; }
		.List .ListOdd { font-size: 10px; background-color: #FFF; line-height: 20px; height: 20px; padding-left: 5px; }
		.List .ListEven { font-size: 10px; background-color: #EEE; line-height: 20px; height: 20px; padding-left: 5px; }
		.List .options { cursor:auto; width: 10px; white-space: nowrap; text-align: center; vertical-align:middle; }
		.List .options a { text-decoration: underline; color: green; } 
		.List .category { font-size: 10px; background-color: #555; color:white; line-height: 20px; height: 20px; padding-left: 5px; border-bottom:1px solid #999; font-weight:bold; cursor:auto; }
		.pagination { margin:0; padding:0; }
		.pagination a { font-size:10px; font-weight:normal; text-decoration:none; }
		.pagination td { padding:0 2px; margin:0 2px; }
		.pagination .button { border:1px solid #666; padding:0 2px; }
		.pagination .buttonactive { border:1px solid #666; padding:0 2px; background-color:#666; }
		.pagination .buttonactive a { font-size:12px; font-weight:bold; text-decoration:none; color:#FFF; }
	</style>
<?

# This is the Listing section, all Javascript that affect Listing pages go in the area.

?>
	<script type="text/javascript">
		var checkflag = false;
		function init(){
			document.onkeydown = register;
			document.onkeyup = register;
			document.onclick = register;
			if (document.body.scrollTop == 0)
			document.searchform.search.focus();
		}

		function register(e){
			if (!e) e = window.event;
			var skey = 'shiftKey';
			var ckey = 'crtlKey';
			shiftpressed = e[skey];
			controlpressed = e[ckey];
		}
		function multiselect(e,v) {
			if(!e)e=window.event;
			var skey='shiftKey';
			var ckey='ctrlKey';
			shiftpressed = e[skey];
			controlpressed = e[ckey];
			if(shiftpressed == false) {
				firstselected = v;
				if(controlpressed == false) {
				} else {
					chk = document.getElementsByTagName('input');
					for(i=0;i<chk.length;i++) {
						if(chk[i].name.indexOf('listids')>-1) {
							if(chk[i].id != v) {
								chk[i].checked = false;
							}
						}
					}
				}
			} else {
				lastselected = v;
				start = false;
				chk = document.getElementsByTagName('input');
				for(i=0;i<chk.length;i++) {
					if(chk[i].name.indexOf('listids')>-1) {
						if(start == false && chk[i].id == firstselected) {
							start = true;
						}
						if(start == true) {
							chk[i].checked = true;
						}
						if(chk[i].id == lastselected){
							break;
						}
					}
				}
			}
		}
		function togglecheckboxes() {
			if(checkflag == false){
				val=true;
				checkflag=true;
				title="Uncheck All";
			} else {
				val=false;
				checkflag=false;
				title="Check All";
			}
			chk = document.getElementsByTagName('input');
				for(i=0;i<chk.length;i++){
					if(chk[i].name.indexOf('listids')>-1) {
						chk[i].checked = val;
					}
				}
			document.getElementById('chkbutton').title = title;
		}
		
		var highlightTmp = "";
		function RowHighlight(row) {
			highlightTmp = (document.getElementById(row).style.backgroundColor != "" ? document.getElementById(row).style.backgroundColor : '');
			document.getElementById(row).style.backgroundColor = '#AFCCFF';
		}
		function UnRowHighlight(row) {
			document.getElementById(row).style.backgroundColor = (highlightTmp == '' ? '' : highlightTmp);
		}
		// This function re-paints the list tables
		function repaint(tblName) {
			var menuitems = document.getElementById(tblName).getElementsByTagName("tr");
			var j = 0;
			var menulen = menuitems.length;
			for (var i=1; i < menulen; i++) {
				if(menuitems[i].style.display != "none") {
					((j%2) == 0 ? menuitems[i].className = "ListEven" : menuitems[i].className = "ListOdd");
					j += 1;
				}		
			}
		}
	</script>
