<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		include 'dbconnect.php';

		$query = "SELECT * FROM `user` WHERE `techid1` ='".$_POST['techid1']."'";
		$result = mysql_query($query);
		if ($result)
		{
			$result = mysql_fetch_assoc($result);
			//var_dump($result);
			if (md5($_POST['password']) == $result['password'])
			{
				session_start();
				$_SESSION["logged_in"] = 1;
				$_SESSION["team"] = $result["team"];
				$_SESSION["techid1"] = $result["techid1"];
				header("location:index.php");
			}
			else
				echo "Wrong Techkriti ID or password!";
		}
	}
	else
		header("location:index.php");
?>
