<?php
	include('_controller.php');
	
	function sitm() { 
		global $BF,$results,$info;
?>
		<form action="" method="post" id="idForm">
<?
		$tableHeaders = array(
			'opt_other'			=> 'checkboxes',
			'chrStore'			=> array('displayName' => 'Store Name'),
			'chrStoreNum'		=> array('displayName' => 'Store Number'),
			'dtCreated'			=> array('displayName' => 'Date Submitted'),
			'intFiles'			=> array('displayName' => 'Attachments'),
			'intComments'		=> array('displayName' => 'Comments'),
			'chrStatus'			=> array('displayName' => 'Status'),
		);
		
		if(access_check(12,4)) {
			$tableHeaders['opt_del'] = 'chrStore,dtCreated2';
		}
		
		sortList('Escalations',		# Table Name
			$tableHeaders,			# Table Name
			$results,				# Query results
			'view.php?key=',		# The linkto page when you click on the row
			'width: 100%; border-top: 0;', 			# Additional header CSS here
			'Escalations'
		);

			$status = array('1'=>'Opened','2'=>'Closed');
?>
			<div style='padding-top:10px;'>
				<div class='FormName'>Change Status for Checked Escalations</div>
				<?=form_select($status,array('caption'=>'- Status -','nocaption'=>'true','name'=>'idNewStatus'))?>
			</div>
			<div class='FormButtons'>
				<?=form_button(array('type'=>'submit','value'=>'Update Status'))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'key','value'=>$info['chrKEY']))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'idStatus','value'=>$_REQUEST['idStatus']))?>
				<?=form_text(array('type'=>'hidden','nocaption'=>'true','name'=>'chrSearch','value'=>$_REQUEST['chrSearch']))?>
			</div>
		</form>
<?
	}
?>