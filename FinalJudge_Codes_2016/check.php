<?php
include 'dbconnect.php';

$jobid=$argv[1];
$problem=$argv[2];
$id=$argv[3];
$user=$argv[4];

echo "User is " . $user + "\n";
echo "Jobid is " . $jobid + "\n";

exec("diff -bBEw /home/external/iitk/ankmahato/Judge_2016/outputs/".$problem.".out /home/external/iitk/ankmahato/Judge_2016/runtime_outputs/".$user."_".$id.".out > /home/external/iitk/ankmahato/Judge_2016/diffs/".$jobid);

$linecount = 0;

$handle = fopen("/home/external/iitk/ankmahato/err_".$jobid.".yc9.en.yuva.param", "r");
echo $handle;

$tle = 0;

echo "file opened\n";	
while(!feof($handle) && $linecount<100)
{
    $line = fgets($handle);
    echo $line;
    $linecount++;	
    if($linecount == 0 || $linecount == 1) {
        if($line[0] == '=') {
            $tle = 1;
        }
    }
}
echo "out of loop\b";
echo $linecount;
echo "\n";

fclose($handle);

if ($linecount == 1 || $linecount == 2 || $tle == 1)
{
    echo "TLE";
    $q = "UPDATE `queue` SET `status`='3' WHERE `id`='".$id."' and `techid1`='".$user."'";
    mysql_query($q);

}
elseif (0 == filesize("/home/external/iitk/ankmahato/Judge_2016/diffs/" . $jobid))
{
    echo "ACCEPTED\n";
    $q = "UPDATE `queue` SET `status`='1' WHERE `id`='".$id."' and `techid1`='".$user."'";
    mysql_query($q);
    $handle = fopen("/home/external/iitk/ankmahato/err_".$jobid.".yc9.en.yuva.param", "r");
    fgets($handle);
    $line = fgets($handle);
    $s = explode("\t",$line);
    $line = $s[1];
    $line = explode("m",$line);
    $min = $line[0];
    $line = explode("s",$line[1]);
    $sec = $line[0];
    echo "<br>".$q."<br>\n";
    $time = 60*$min + $sec;
    echo $time;
    echo "\n";
    fclose($handle);
    $q = "UPDATE `queue` SET `runtime`='". $time . "' WHERE `id`='".$id."' and `techid1`='".$user."'";
    mysql_query($q);

}
else
{
    echo "WRONG ANSWER\n";
    $q = "UPDATE `queue` SET `status`='2' WHERE `id`='".$id."' and `techid1`='".$user."'";
    mysql_query($q);

}

?>
