<?php
	include('_controller.php');
	
	$q = "SELECT RS.ID, RS.chrStore, RS.chrStoreNum, 
			GROUP_CONCAT(CONCAT(SHS.dDate,'|', tOpening,'|', tClosing,'|', bClosed) ORDER BY dDate SEPARATOR ',') AS txtTimes
			FROM Holidays
			JOIN HolidayStoreHours AS SHS ON SHS.idHoliday=Holidays.ID 
			JOIN Stores AS RS ON SHS.idStore=RS.ID
			WHERE Holidays.ID = ".$info['ID']."
			GROUP BY RS.ID
			ORDER BY chrStore";
	
	$results = db_query($q,"Getting Results");
	
	$time = date('m-d-Y');
	
	$filename = str_replace(" ", "_", decode($info['chrHoliday'].'_('.$time.')'));
	
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
	$worksheet =& $workbook->addWorksheet(decode($info['chrHoliday']));
	$worksheet->setInputEncoding('utf-8');
	$worksheet->hideGridLines();
	
	/* This is the front page with the overall info */
	$column_num = 0;
	$row_num = 0;
	
	$worksheet->setColumn($column_num, $column_num, 30);
	$worksheet->write($row_num, $column_num, decode($info['chrHoliday']).' Hours of Operation', $format_column_header);
	$column_num++;
	$totalDays = (strtotime($info['dEnd']) - strtotime($info['dBegin']))/60/60/24;
	
	$i=0;
	$dCurrent = $info['dBegin'];
	while($i <= $totalDays) {	
	$worksheet->setColumn($column_num, $column_num, 25);
		$worksheet->write($row_num, $column_num, date('m/d/Y', strtotime($dCurrent)), $format_column_header);
		$column_num++;
		
		$dCurrent = date('Y-m-d',strtotime($info['dBegin']." + ".($i++ + 1)." days"));
	}


	$row_num++;
	$column_num = 0;
	$worksheet->setColumn($column_num, $column_num, 30);
	$worksheet->write($row_num, $column_num, 'Store Name - Number', $format_column_header);
	$column_num++;
	$i=0;
	while($i <= $totalDays) {	
		$worksheet->write($row_num, $column_num, 'Open - Close', $format_column_header);
		$column_num++;
		$i++;
	}
		
	$count=0;
	while($row = mysqli_fetch_array($results)) {
		$row_num++;
		$column_num = 0;
		$worksheet->write($row_num, $column_num, decode($row['chrStore'].' - '.$row['chrStoreNum']), $format_data);
		$column_num++;	
		$allTimes = explode(',',$row['txtTimes']);
		foreach($allTimes as $k => $v) {
			$thisDay = explode('|',$v);
			$worksheet->write($row_num, $column_num, (!$thisDay[3]?date('H:i',strtotime($thisDay[1])).' - '.date('H:i',strtotime($thisDay[2])):'Closed'), $format_data);
			$column_num++;	
		}
		$count++;		
	}
	$workbook->close();