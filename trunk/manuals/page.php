<?php
	include('_controller.php');
	
	function sitm() {
		global $BF,$info,$page;
?>
		<div class='index'>
			<table cellpadding='0' cellspacing='0' class='manuals_twoCol' style='border-bottom:1px solid #999; padding-bottom:2px; margin-bottom:5px;'>
				<tr>
					<td class='tcleft'><?=$_SESSION['chrLanguage']['article_num'].': '.date('Y',strtotime($page['dtCreated'])).'-'.$page['chrVersion']?></td>
					<td class='tcright'><a href='email_page.php?key=<?=$_REQUEST['key']?>'><img src='<?=$BF?>images/i_email.png' title='<?=$_SESSION['chrLanguage']['email_this_article']?>' alt='<?=$_SESSION['chrLanguage']['email_this_article']?>' /></a> <a href='export-pdf-page.php?key=<?=$_REQUEST['key']?>' target='_blank'><img src='<?=$BF?>images/i_print.png' title='<?=$_SESSION['chrLanguage']['print_this_article']?>' alt='<?=$_SESSION['chrLanguage']['print_this_article']?>' /></a></td>
					
				</tr>
			</table>
			<?=decode($page['txtPage'])?>
			<table cellpadding='0' cellspacing='0' class='manuals_twoCol' style='border-top:1px solid #999; padding-top:2px; margin-top:5px;'>
				<tr>
					<td class='tcleft'><?=$_SESSION['chrLanguage']['article_num'].': '.date('Y',strtotime($page['dtCreated'])).'-'.$page['chrVersion']?></td>
					<td class='tcright'><a href='email_page.php?key=<?=$_REQUEST['key']?>'><img src='<?=$BF?>images/i_email.png' title='<?=$_SESSION['chrLanguage']['email_this_article']?>' alt='<?=$_SESSION['chrLanguage']['email_this_article']?>' /></a> <a href='export-pdf-page.php?key=<?=$_REQUEST['key']?>' target='_blank'><img src='<?=$BF?>images/i_print.png' title='<?=$_SESSION['chrLanguage']['print_this_article']?>' alt='<?=$_SESSION['chrLanguage']['print_this_article']?>' /></a></td>
				</tr>
			</table>
		</div>
<?	} ?>