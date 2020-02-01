<?php
$BF = '../../../';
include($BF .'_lib.php');

$info = db_query("SELECT * FROM Audit WHERE ID=380","get page",1);

/*
?>
<pre>
<?
 
 require_once($BF."components/ContentDiff.class.php");

            $diff = new ContentDiff(encode($info['txtOldValue'],'tags'),encode($info['txtNewValue'],'tags'));             
            if($diff->showDifference())
            {
                print $diff->newText;
            }     
            else
            {
            	print $diff->messages;
            }    

?>
</pre>
<hr /><br /><br />
<?
require_once($BF."components/ClassToCompareFiles.inc.php");
$myFile = "./temp/newFile.html";
$fh = fopen($myFile, 'w') or die("can't open file");
$stringData = decode($info['txtNewValue']);
fwrite($fh, $stringData);
fclose($fh);
$myFile = "./temp/oldFile.html";
$fh = fopen($myFile, 'w') or die("can't open file");
$stringData = decode($info['txtOldValue']);
fwrite($fh, $stringData);
fclose($fh);

	$compareFiles = new ClassToCompareFiles;

	// File paths of the two files
	$file1 = "./temp/newFile.html";
	$file2 = "./temp/oldFile.html";

	$file1Contents = file($file1);
	$file2Contents = file($file2);

	$compareFiles->compareFiles($file1, $file2);
?>
 <center><font face="verdana" size="6" ><B> Comparison Result </b></font> </center> <br />
<?php
	echo "<center><font face='verdana' size='3' color='green'><b>Number of Similar line(s): ". $compareFiles->cnt1."</font><br />";
	echo "<BR /><font face='verdana' size='3' color='red'>Number of Different line(s): ". $compareFiles->cnt2."</font></center></b><br />";
?>
	<table border="1" style="width:100%;height:400px" cellspacing="0" cellpadding="0">
		<tr>
			<td bgcolor="#ccddff" style="width:50%" >
				<iframe src="file1.html" width="100%" height="400" frameborder='0'  ></iframe>
			</td>
			<td bgcolor="#ffccdd" style="width:50%" >
				<iframe src="file2.html" width="100%" height="400" frameborder='0' ></iframe>
			</td>
		</tr>
	</table>
<?


    include_once "Text/Diff.php"; 
    include_once "Text/Diff/Renderer.php"; 
    include_once "Text/Diff/Renderer/unified.php"; 
 
    // define files to compare 
    $file1 = "file1.html"; 
    $file2 = "file2.html"; 
 
    // perform diff, print output 
    $diff = &new Text_Diff(file($file1), file($file2)); 
    $renderer = &new Text_Diff_Renderer_unified(); 
    echo $renderer->render($diff); 
    ?>
*/

	include_once 'inline_function.php';

	$text1=nl2br(encode($info['txtOldValue'],'tags'));
	$text2=nl2br(encode($info['txtNewValue'],'tags'));


//	$text1 = file_get_contents('a.html');
//	$text2 = file_get_contents('b.html');
	$nl = '#**!)@#';
	$diff = inline_diff($text1, $text2, $nl);
//	echo str_replace($nl,"\n",$diff)."\n";
echo '<pre><style>del{background:#fcc}ins{background:#cfc}</style>'.$diff."\n</pre>";


?>