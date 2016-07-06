<?php
include 'accesscontrol.php';
include 'dbconnect.php';
die("Contest had ended!");
if(!isset($_FILES['file']))
{
	echo ("<h1>Please upload a file!</h1>");
	exit();
}

//var_dump($_SESSION);

$name = $_FILES['file']["name"];
//echo $name;
$ext = explode(".",$name);
//$ext = explode(".","ihpc.cc");
//echo "ext : ", $ext;
//echo $ext[0];
$ext = explode(".",$name);
if ($ext[1] != "cc" && $ext[1] != "cpp")
{
	echo "Please upload a c++ file!";
	exit();
}

$lib = "MPI";
$techid1 = $_SESSION["techid1"];
$team = $_SESSION["team"];
$size = $_FILES['file']['size'];
$prob = $_GET["problem"];
$lib = $_POST["lib"];

if ($size > 50000)
{
	echo ("File size too large");
	exit();
}

$tmpname = $_FILES['file']['tmp_name'];

$d = date_create();
$ts = date_timestamp_get($d);

if (!move_uploaded_file($tmpname,getcwd().'/codefiles/'.$prob."/".$techid1."_".$ts.".cc"))
{
	echo "Unable to upload";
	//echo getcwd();
	//echo $prob;
	//echo $techid1;
	exit();
}
else
{
	$query = "INSERT INTO `queue` (`techid1`, `problem`, `library`, `time_stamp`, `status`, `runtime`, `memory`, `team`) VALUES ('".$techid1."', '".$prob."', '".$lib."', '".$ts."', '-1', '-1', '-1', '".$team."')";
	$result = mysql_query($query);
	if (!$result) {
		echo ("This failed");
	} else {
		echo ("Problem Submitted. It will be judged soon.");
	}
}

?>
