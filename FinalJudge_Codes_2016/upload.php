<?php
//define a maxim size for the uploaded images in Kb
 define ("MAX_SIZE","100"); 
 
//This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;	
         $ext = substr($str,$i+1,$l);
         return $ext;
 }

//This variable is used as a flag. The value is initialized with 0 (meaning no error  found)  
//and it will be changed to 1 if an errro occures.  
//If the error occures the file will not be uploaded.
 $errors=0;
//checks if the form has been submitted
 if(isset($_POST['submit'])) 
 {
	//database access module
	include 'validate.php';
	include 'dbconnect.php';
	//get the original name of the file from the clients machine
 	$filename = stripslashes($_FILES['codefile']['name']);
 	//get the extension of the file in a lower case format
  	$extension = getExtension($filename);
 	$extension = strtolower($extension);
	if(email_validate($_POST['emailID']))
	{
		$salt="2d235ace000a3ad85ryt0e321c89bb99";
		$query='SELECT * FROM users WHERE emailID ="'.$_POST['emailID'].'"';
		$result=mysql_query($query);
		if($result)
		{
			while ($row = mysql_fetch_assoc($result)) {
			if($row['password']==md5($_POST['password'].$salt))
			 {
				//if($row['approved']==1)
				//{ // approval checking condition.
				
						//echo 'This was the first codefile you uploaded. You have to upload one more codefile to complete your registration.<br/>';	
						//we will give an unique name UIDofBand_BandName_1_filename.fileext
						$image_name=$row['uuid'];
						$query = 'UPDATE users SET emailID ="'.$_POST['emailID'].'" WHERE emailID ="'.$_POST['emailID'].'" AND password ="'.md5($_POST['password'].$salt).'"';
						mysql_query($query);
					
				//}
				/*else
				{
					echo "Your account has not been approved by the coordis. Contact them ASAP. <br/>";
					exit();
				}*/ 
			 }
			}
		}
	}
 	//reads the name of the file the user submitted for uploading
 	$image=$_FILES['codefile']['name'];
	//var_dump($_FILES['codefile']);
 	//if it is not empty
 	if ($image) 
 	{

 	//if it is not a known extension, we will suppose it is an error and will not  upload the file,  
	//otherwise we will do more tests
 if (($extension != "c")) 
 		{
		//print error message
 			echo 'Error:Unknown extension! At present we are only allowing bands to upload .c files, if you need to use a different format please inform the coordinators';
 			$errors=1;
 		}
 		else
 		{
//get the size of the image in bytes
 //$_FILES['image']['tmp_name'] is the temporary filename of the file
 //in which the uploaded file was stored on the server
		$size=filesize($_FILES['codefile']['tmp_name']);

		//compare the size with the maxim size we defined and print error if bigger
		if ($size > MAX_SIZE*1024)
		{
			echo '<h1>You have exceeded the size limit!</h1>';
			$errors=1;
		}

		//the new name will be containing the full path where will be stored (images folder)
		 $dir = 'codefiles';
		 if ( !file_exists($dir) ) {
		  mkdir ($dir, 0777);
		 }

$newname='codefiles/'.$image_name.'.c';
//we verify if the image has been uploaded, and print error instead
$copied = move_uploaded_file($_FILES['codefile']['tmp_name'], $newname);
if (!$copied) 
{
	echo 'Internal Server Error:Copy unsuccessfull!, Please report this error immediately to grohit@outlook.com';
	$errors=1;
}}}

//If no errors registred, print the success message
 if(!$errors) 
 {
	echo '';}
 }
else{?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Upload codefiles</title>
<meta name="author" content="Rohit Gupta">
		<style>
		body{
			background-color: #2a6961;
			color:white;
			font-family: Sans-Serif;
			letter-spacing:1px;
			margin:0;
		}
		::-webkit-input-placeholder {
			color: grey;
			}
		:-moz-placeholder {  
		color: grey;  
		}
		</style>
</head>

<body>
	<div id="instruction" style="text-align:center; width:80%; height:100px; position:relative; left:10%; top:200px; ">
	<form name="codefile" method="post" enctype="multipart/form-data"  action="" style="font-family:sans-serif;">
		Code:(Max 100KB)&nbsp;<input type="file" name="codefile"> <br/>
		E-Mail:&nbsp;&nbsp;<input name="emailID" type="emailID" required/><br/>
		Password:<input type="password" name="password" required/><br/>
		<input name="submit" type="submit" value="Upload Code">
	</form>
	</div>
</body>
</html><?php }?>