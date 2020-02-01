<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info;
?>
		<div class='index'>
			<table cellpadding='0' cellspacing='0' class='manuals_twoCol' style=''>
				<tr>
					<td class='tcright'><a href='export_book.php?key=<?=$_REQUEST['key']?>'><img src='<?=$BF?>images/i_print.png' title='<?=$_SESSION['chrLanguage']['print_articles']?>' alt='<?=$_SESSION['chrLanguage']['print_articles']?>' /></a></td>
				</tr>
			</table>
			<?=decode($info['txtPage'])?>
			<table cellpadding='0' cellspacing='0' class='manuals_twoCol' style=''>
				<tr>
					<td class='tcright'><a href='export_book.php?key=<?=$_REQUEST['key']?>'><img src='<?=$BF?>images/i_print.png' title='<?=$_SESSION['chrLanguage']['print_articles']?>' alt='<?=$_SESSION['chrLanguage']['print_articles']?>' /></a></td>
				</tr>
			</table>
		</div>
<?	} ?>