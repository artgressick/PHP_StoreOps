<?php
	include('_controller.php');
	
	$q = "SELECT Stores.ID, Stores.chrStore, chrStoreNum,
		  GROUP_CONCAT(CONCAT(StoreHours.idDayOfWeek,'|',StoreHours.tOpening,'|',StoreHours.tClosing,'|',StoreHours.bClosed) ORDER BY idDayOfWeek SEPARATOR ',') AS txtTimes
		FROM Stores
		JOIN StoreHours ON StoreHours.idStore=Stores.ID
		WHERE !Stores.bDeleted
		GROUP BY StoreHours.idStore
		ORDER BY Stores.chrStore";
	$results = db_query($q,"getting Hours");
	
	$time = date('m-d-Y');
	
	$filename = str_replace(" ", "_", 'Store_Hours_('.$time.')');
	
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
	$worksheet =& $workbook->addWorksheet('Store Hours');
	$worksheet->setInputEncoding('utf-8');
	$worksheet->hideGridLines();
	
	/* This is the front page with the overall info */
	$column_num = 0;
	$row_num = 0;
	
	$worksheet->setColumn($column_num, $column_num, 30);
	$worksheet->write($row_num, $column_num, 'Store Name & Number', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Sunday', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Monday', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Tuesday', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Wednesday', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Thursday', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Friday', $format_column_header);
	$column_num++;
	$worksheet->setColumn($column_num, $column_num, 20);
	$worksheet->write($row_num, $column_num, 'Saturday', $format_column_header);
	$column_num++;
	
	$count=0;
	while($row = mysqli_fetch_array($results)) {
		$row_num++;
		$column_num = 0;
		$worksheet->write($row_num, $column_num, decode($row['chrStore'].' - '.$row['chrStoreNum']), $format_data);
		$column_num++;	
			$i=0;
			$allTimes = explode(',',$row['txtTimes']);
			while ($i < 7) {
				$thisDay = explode('|',$allTimes[$i]);
				$worksheet->write($row_num, $column_num, (!$thisDay[3]?date('H:i',strtotime($thisDay[1])).' - '.date('H:i',strtotime($thisDay[2])):'Closed'), $format_data);
				$column_num++;	
				$i++;
			}
	}

	$workbook->close();