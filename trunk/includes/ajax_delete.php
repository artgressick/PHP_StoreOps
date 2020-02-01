<?
	$BF = "../";
	require($BF.'_lib.php');
	if($_REQUEST['postType'] == "delete") {
		$total = 0;
		$q = "UPDATE ". $_REQUEST['tbl'] ." SET bDeleted=1 WHERE ID=".$_REQUEST['id'];
		if(db_query($q,"update bDeleted")) { 
			if($_REQUEST['tbl'] == 'Pages') {
				$q = "SELECT ID FROM ". $_REQUEST['tbl'] ." WHERE idParent=".$_REQUEST['id'];
				$ids = db_query($q,"Getting all ids");
				$q = "UPDATE ". $_REQUEST['tbl'] ." SET bDeleted=1 WHERE idParent=".$_REQUEST['id'];
				db_query($q,"delete Children pages");
				if(mysqli_num_rows($ids) > 0) {
					while($row = mysqli_fetch_assoc($ids)) {
						$q = "UPDATE ". $_REQUEST['tbl'] ." SET bDeleted=1 WHERE idParent=".$row['ID'];
						db_query($q,"delete Children pages");
					}
				}
			}
			$total++;
			$q = "INSERT INTO Audit SET idUser=".$_REQUEST['idUser'].", idRecord=".$_REQUEST['id'].", chrTableName='". $_REQUEST['tbl'] ."', chrColumnName='bDeleted', dtDatetime=now(), 
					txtOldValue='0', txtNewValue='1', idType=3"; 
			if(db_query($q,"insert into audit")) { $total += 2; }
		}
  		echo $total;
	} else if(@$_REQUEST['postType'] == "permDelete") {
		$total = 0;
		$q = "DELETE FROM ". $_REQUEST['tbl'] ." WHERE ID=".$_REQUEST['id'];
		if(db_query($q,"perm delete")) { 
			$total++;
			$q = "INSERT INTO Audit SET idUser=".$_REQUEST['idUser'].", idRecord=".$_REQUEST['id'].", chrTableName='". $_REQUEST['tbl'] ."', chrColumnName='', dtDatetime=now(), 
					txtOldValue='', txtNewValue='', idType=4"; 
			if(db_query($q,"insert into audit table")) { $total += 2; }
		}
  		echo $total;
  	} 
?>