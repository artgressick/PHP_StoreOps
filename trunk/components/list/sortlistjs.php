<?	
	function sortList($table, $hash, $query, $linkto, $style='', $db_table='') { 
		global $BF;
		if($db_table=='') { $db_table = $table; }
		
?>
	<table id='<?=$table?>' class='List sortable' style='<?=(isset($style) ? $style : 'width: 100%;')?>' cellpadding="0" cellspacing="0">
		<thead>
		<tr>
<?	foreach($hash as $k => $v) { 
		if(is_array($v) && !preg_match('/^opt_/',$k)) { 
			if(isset($hash[$k]['default'])) { 
				$sortDir = strtolower($hash[$k]['default']);
?>			<th class='ListHeadSortOn <?=($sortDir != 'desc' ? 'sorttable_sorted' : 'sorttable_sorted_reverse')?><?=(isset($hash[$k]['sorttype']) ? ' sorttable_'. $hash[$k]['sorttype'] : '')?>' <?=(isset($hash[$k]['style']) ? ' style="'. $hash[$k]['style'] .'"' : '')?>><?=$hash[$k]['displayName']?>&nbsp;<img src='<?=$BF?>components/list/column_sorted_<?=($sortDir != 'desc' ? 'asc' : 'desc')?>.gif' alt='sorted' style='vertical-align: bottom;' /><span id='<?=($sortDir != 'desc' ? 'sorttable_sortfwdind' : 'sorttable_sortrevind')?>'></span></th>
<?			} else {
?>			<th<?=(isset($hash[$k]['sorttype']) ? ' class="sorttable_'. $hash[$k]['sorttype'] .'"' : '')?><?=(isset($hash[$k]['style']) ? ' style="'. $hash[$k]['style'] .'"' : '')?>><?=$hash[$k]['displayName']?>&nbsp;<img src='<?=$BF?>components/list/column_unsorted.gif' alt='default sort' style='vertical-align: bottom;' /></th>
<?			}
		} else { 
			if(preg_match('/^opt_other/',$k) && $v == 'checkboxes') {
?>			<th class="options sorttable_nosort"><input type=checkbox name="chkbutton" id="chkbutton" title="Check all" onClick="togglecheckboxes()"></th>
<?
			} else {			
?>			<th<?=(preg_match('/^opt_/',$k) ? ' class="options sorttable_nosort"' : '')?>><img src='<?=$BF?>images/options.gif' alt='options' /></th>
<?			}
		}
	} ?>
		</tr>
		</thead>
		<tbody>
<?		$count = 0;
		if(mysqli_num_rows($query)) { 
			
			$linktype = (preg_match('/(\?|\&)key\=/',$linkto) ? 'chrKEY' : 'ID');
			
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
<?			}
		} else {
?>
			<tr>
				<td colspan='<?=count($hash)?>' style='text-align:center;height:20px;vertical-align:middle;'>No records found in the database.</td>
			</tr> 	
<?		} ?>
		</tbody>
	</table>

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
		.List th { font-size: 10px; background: url(<?=$BF?>components/list/list_head.gif) repeat-x; height: 13px; border-bottom: 1px solid #999; padding: 3px 5px; font-weight: bold; text-align: left; white-space: nowrap; }
		.List td { padding: 0 5px; font-size: 11px; cursor: pointer; }
		.List .nocursor { cursor:auto; }
		.List th a { color: #333; text-decoration: none; }
		.List td a { color: black; text-decoration: none; }
		.List th.ListHeadSortOn { font-size: 10px; background: url(<?=$BF?>components/list/list_head_sortedby.gif); height: 13px; border-bottom: 1px solid #999; padding: 3px 5px; font-weight: bold; }
		.List .ListOdd { font-size: 10px; background-color: #FFF; line-height: 20px; height: 20px; padding-left: 5px; }
		.List .ListEven { font-size: 10px; background-color: #EEE; line-height: 20px; height: 20px; padding-left: 5px; }
		.List .options { width: 10px; white-space: nowrap; text-align: center; vertical-align:middle; }
		.List .options a { text-decoration: underline; color: green; } 
		.List .category { font-size: 10px; background-color: #555; color:white; line-height: 20px; height: 20px; padding-left: 5px; border-bottom:1px solid #999; font-weight:bold; cursor:auto; }		
	</style>
<?

# This is the Listing section, all Javascript that affect Listing pages go in the area.

?>
	<script type='text/javascript' src='<?=$BF?>components/list/_sorttable.js'></script>
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
