<?
	$BF = '../../../';
	$NON_HTML_PAGE=1;
	require($BF.'_lib.php');
	require_once "Spreadsheet/Excel/Writer.php";
	
	$time = date('m-d-Y');
		
	$info = db_query("SELECT ID, chrKEY, chrTitle, idLanguage
						FROM EscalatorTemplates
						WHERE !bDeleted AND chrKEY='".$_REQUEST['key']."'","getting Template",1); // Get Info
	
	$filename = str_replace(" ", "_", decode($info['chrTitle'].'_('.$time.')'));
	
	$tmp_questions = db_query("SELECT ID, idFieldType, chrQuestion FROM EscalatorQuestions WHERE !bDeleted AND idFieldType != 6 AND idTemplate='".$info['ID']."' ORDER BY dOrder, chrQuestion","Getting Questions");

	while($row = mysqli_fetch_assoc($tmp_questions)) {
		$questions[$row['ID']] = $row['chrQuestion'];
	}
	
	// create workbook
	$workbook = new Spreadsheet_Excel_Writer();

	// send the headers with this name
	$workbook->send(decode($filename).'.xls');
	$workbook->setVersion(8);
	// create format for column headers
	$format_column_header =& $workbook->addFormat();
	$format_column_header->setBold();
	$format_column_header->setSize(10);
	$format_column_header->setAlign('left');

	// create format for column headers
	$format_column_header2 =& $workbook->addFormat();
	$format_column_header2->setBold();
	$format_column_header2->setSize(12);
	$format_column_header2->setAlign('left');
	
	// create data format
	$format_data =& $workbook->addFormat();
	$format_data->setSize(10);
	$format_data->setAlign('left');
	$format_data->setTextWrap();
	
	$curDate = date('m/d/Y H:i:s',strtotime('now'));
	
	// Create worksheet
	$worksheet =& $workbook->addWorksheet('Sheet1');
	$worksheet->setInputEncoding('utf-8');
	$worksheet->hideGridLines();
	
	/* This is the front page with the overall info */
	$column_num = 0;
	$row_num = 0;
	
	$worksheet->setColumn($column_num, $column_num, 25);
	$worksheet->write($row_num, $column_num, 'Store Name (Number)', $format_column_header2);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 24);
	$worksheet->write($row_num, $column_num, 'Date Submitted', $format_column_header2);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 10);
	$worksheet->write($row_num, $column_num, 'Status', $format_column_header2);
	$column_num++;
	
	foreach($questions AS $id => $chrQuestion) {
		$worksheet->setColumn($column_num, $column_num, strlen($chrQuestion)+10);
		$worksheet->write($row_num, $column_num, decode($chrQuestion), $format_column_header2);
		$column_num++;
	}

	$worksheet->setColumn($column_num, $column_num, 25);
	$worksheet->write($row_num, $column_num, 'Attachment(s)', $format_column_header2);
	$column_num++;

/*	$q = "SELECT E.ID, CONCAT(S.chrStore,' (',S.chrStoreNum,')') AS chrStore, IF(idStatus=1,'Open','Closed') AS chrStatus, DATE_FORMAT(E.dtCreated,'%c/%e/%Y - %l:%i %p (PST)') AS dtCreated2,
			(SELECT GROUP_CONCAT(CONCAT(A.idQuestion,':::',A.txtAnswer) ORDER BY A.idQuestion SEPARATOR '|||') FROM EscAnswers AS A WHERE A.idEscalation=E.ID GROUP BY A.idEscalation) AS txtAnswers,
			(SELECT GROUP_CONCAT(CONCAT(' ',F.chrFileName) ORDER BY F.chrFileName SEPARATOR ',') FROM EscFiles AS F WHERE F.idEscalation=E.ID GROUP BY F.idEscalation) AS txtFiles
			FROM Escalations AS E
			JOIN Stores AS S ON E.idStore=S.ID
			WHERE !E.bDeleted AND E.idTemplate='".$info['ID']."'";
*/

	$q = "SELECT E.ID, CONCAT(S.chrStore,' (',S.chrStoreNum,')') AS chrStore, IF(idStatus=1,'Open','Closed') AS chrStatus, DATE_FORMAT(E.dtCreated,'%c/%e/%Y - %l:%i %p (PST)') AS dtCreated2,
			(SELECT GROUP_CONCAT(CONCAT(' ',F.chrFileName) ORDER BY F.chrFileName SEPARATOR ',') FROM EscFiles AS F WHERE F.idEscalation=E.ID GROUP BY F.idEscalation) AS txtFiles
			FROM Escalations AS E
			JOIN Stores AS S ON E.idStore=S.ID
			WHERE !E.bDeleted AND E.idTemplate='".$info['ID']."'";

	
	if($_REQUEST['chrSearch'] != '') {
		$q .= " AND E.ID IN(SELECT EA.idEscalation 
							FROM EscAnswers AS EA 
							JOIN Escalations AS ES ON EA.idEscalation=ES.ID
							WHERE lcase(EA.txtAnswer) LIKE lcase('%".encode($_REQUEST['chrSearch'])."%')
							GROUP BY EA.idEscalation)";
	}
	
	if($_REQUEST['idStatus'] != '0') {
		$q .= " AND E.idStatus='".$_REQUEST['idStatus']."'";
	}

	if(is_numeric($_REQUEST['idStoreFilter'])) {
		$q .= " AND E.idStore='".$_REQUEST['idStoreFilter']."'";
	}
	
	$q .= " ORDER BY dtCreated, chrStore";
	
	$answers = db_query($q,"Getting Answers");

	$temp = db_query("SELECT EscAnswers.idEscalation, EscAnswers.idQuestion, EscAnswers.txtAnswer 
						FROM EscAnswers 
						JOIN Escalations ON EscAnswers.idEscalation=Escalations.ID
						WHERE Escalations.idTemplate=".$info['ID']."
						ORDER BY idEscalation","Getting Answers");
	$new_answers = array();
	while($row2 = mysqli_fetch_assoc($temp)) {
		if(!isset($new_answers[$row2['idEscalation']])) { $new_answers[$row2['idEscalation']] = array(); }
		$new_answers[$row2['idEscalation']][$row2['idQuestion']] = $row2['txtAnswer'];
	}


	
	while($row = mysqli_fetch_assoc($answers)) {
		$row_num++;
		$column_num = 0;
		$worksheet->write($row_num, $column_num, decode($row['chrStore']), $format_data);
		$column_num++;	
		$worksheet->write($row_num, $column_num, decode($row['dtCreated2']), $format_data);
		$column_num++;	
		$worksheet->write($row_num, $column_num, decode($row['chrStatus']), $format_data);
		$column_num++;
		

/*		$tmp_ans = explode('|||',$row['txtAnswers']);
		foreach($tmp_ans AS $k => $v) {
			$tmp = explode(':::',$v);
			$this_answers[$tmp[0]] = $tmp[1]; 
		}
*/		
		foreach($questions AS $id => $chrQuestion) {
			$worksheet->write($row_num, $column_num, ' '.decode($new_answers[$row['ID']][$id]), $format_data);
			$column_num++;
		}
		$worksheet->write($row_num, $column_num, decode($row['txtFiles']), $format_data);
		$column_num++;
	}
	$workbook->close();
	
	