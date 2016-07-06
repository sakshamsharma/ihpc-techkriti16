<?php
 include "accesscontrol.php";
?>

<!DOCTYPE html>
	<head>
		<meta charset="UTF-8"> 
		<title>
		High Performance Computing Contest
		</title>
		<style>
		body{
			background-image: url("images/problembg.jpg");
			color:white;
			font-family: Sans-Serif;
			letter-spacing:1px;
		}
		a{
			color:white;
			font-weight:bold;
		}
		.nav
		{
			/*background-color:;*/
			width:30%;
			height:50px;
			/*box-shadow: 0px 1px 50px #5E5E5E;*/
			position:fixed; left:35%;
			top:5px;
		}
		#submissions {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    width: 100%;
    border-collapse: collapse;
		}
		#submissions tr.alt td {
    color: #000000;
    background-color: #EAF2D3;
		}
		#submissions td, #submissions th {
		    font-size: 1em;
		    border: 1px solid white;
		    padding: 3px 7px 2px 7px;
		    color : black;
		}
		#submissions th {
    font-size: 1.1em;
    text-align: left;
    padding-top: 5px;
    padding-bottom: 4px;
    /*background-color: white;*/
    color: black;
		}

			ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
		}

		li {
	/*	    float: right; */
		    padding-right: 20%;
		}

		a:link, a:visited {
		    display: block;
		    width: 100%;
		    font-weight: bold;
		    color: #FFFFFF;
		    background-color: black;
		    text-align: center;
		    padding: 4px;
		    text-decoration: none;
		    text-transform: uppercase;
		}

		a:hover, a:active {
		    background-color: gray;
		}	
		</style>
	</head>

	<body>
		<ul>
		<li><a href="logout.php">Logout</a></li>
		<li><a href="allsubmissions.php">All Submissions</a></li>
		<li><a href="mysubmissions.php">My Submissions</a></li>
		<li><a href="problems.php">Problems</a></li>
		<!-- <li><a href="#about">About</a></li> -->
	</ul>
<?php
		
	include "dbconnect.php";
	$techid = $_SESSION["techid1"];
	$query = "SELECT * FROM `queue` WHERE `techid1`='".$techid."'";
    if($techid == '9876') {
        $query = "SELECT * FROM `queue` WHERE id>120 and status=1";
    }
	$result = mysql_query($query);

	if ($result)
	{
		echo '<table id="submissions">';
		echo '<tr> <th> Time Stamp </th> <th>ID</th> <th> Problem </th> <th> Techkriti ID </th> <th> Status </th> <th> Runtime </th> </tr>';
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			// var_dump($row);
			echo '<tr>';
			echo '<td> '.$row["time_stamp"]." </td>";
			echo '<td> '.$row["id"]." </td>";
            echo '<td>' .$row["problem"]. " </td>";
			echo '<td> '.$row["techid1"]."</td>";

			if ($row["status"] == -1)
				echo '<td> To be compiled</td>';
			else if ($row["status"] == 0)
				echo '<td> Compilation Error </td>';
			else if ($row["status"] == 1 && $row["runtime"] == 0)
				echo '<td> Resource Usage Exceeded </td>';
			else if ($row["status"] == 1)
				echo '<td> Accepted </td>';
			else if ($row["status"] == 2)
				echo '<td> Wrong Answer </td>';
            else if ($row["status"] == 3)
                echo '<td> Time Limit Exceeded </td>';
            else if ($row["status"] == 4)
                echo '<td> Done compiling. Would be run soon. </td>';
            else if ($row["status"] == 5)
                echo '<td> Runtime error. Error with MPI.</td>';

			if ($row["runtime"] == -1)
				echo '<td> 0.00 sec </td>';
			else echo '<td>'.$row["runtime"].' sec </td>';
		}

	}
	else
	{
		echo "<h1> You don't have any submissions </h1>";
	}

?>

