<?php
session_start();
//echo $_SESSION["id"];
require_once "config.php";
$statusMsg = '';

//file upload path
$targetDir = "fanart/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) 
{
    //allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif'); //pdf, txt mai tarziu
    if(in_array($fileType, $allowTypes))
    {
        //upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
        {
            $sql = "INSERT INTO fanart (user_id, file_name, path)
                              VALUES(?, ?, ?);";
            if($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "iss", $p_user, $p_name, $p_pdf);

                // Set parameters
                $p_user = $_SESSION["id"];
                $p_name = $fileName;
                $p_pdf = $targetFilePath;

                //echo $p_user . ' ' . $p_name . ' ' . $p_pdf;

                if(mysqli_stmt_execute($stmt))
                {
                    $statusMsg = "The file ".$fileName. " has been uploaded!";
                    echo "The file ".$fileName. " has been uploaded!";
                }
                else
                {
                    echo "Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        else
        {
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }
    else
    {
        $statusMsg = 'Sorry, only jpg, png, jpeg and gif files are allowed to upload.';
    }
}
else
{
    $statusMsg = 'Please select a file to upload.';
}
?>

<!DOCTYPE html>
<html lang="en-UK">

<head>
	<title>Doctor Doom</title>
	<img class="ddpic" src="/img/Doctor Doom.png" alt="Doctor Doom">
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<style>
	
		.ddpic
		{
			float: left;
			position:-webkit-sticky;
			position: sticky;
			top: 0;
			height: 5%;
			width: 5%;
		}

		#wall
		{
			background-image: url('/img/Arcane Energy.png');
			background-repeat: no-repeat;
			background-attachment: fixed;
			background-size: cover;
		}
	
		*
		{
			box-sizing: border-box;
		}
		
		.menu
		{
			background-color: darkgreen;
			font-weight: bold;
			text-align: center;
			padding: 10px;
		}

		.menubutton
		{
			background-color: darkgreen;
			border: none;
			color: white;
			padding: 16px;
			font-size: 16px;
			transition-duration: 0.4s;
			cursor: pointer;
		}
		
		.menubuttonon
		{
			
			background-color: lightgreen;
			color: black;
			border: none;
			padding: 16px;
			font-size: 16px;
		}

		.menubutton:hover
		{
			background-color: lightgreen;
			color: black;
		}

        li
        {
            padding: 2%;
        }

		#column1
		{
			background-image: url('/img/Doctor Doom c1.png');
			background-repeat: no-repeat;
			background-size: 100%;
            float: left;
            display: inline-block;
			width: 20%;
			height: 500px;
        }
		
		article
		{
			text-align: center;
			background-color: green;
            display: inline-block;
			padding: 3%;
			width: 60%;
            height: 100%;
		}
		
		#column2
		{
			background-image: url('/img/Doctor Doom c2.png');
			background-repeat: no-repeat;
            background-size: 100%;
			float: right;
			width: 20%;
			height: 500px;
		}
		
		section:after
		{
			content: "";
			display: table;
			clear: both;
		}
		
		footer
		{
			color: white;
			background-color: darkgreen;
			text-align: center;
			text-decoration: underline;
			padding: 25px;
			height: 70px;
		}

	</style>
	
</head>

<body id="wall">

	<br>
	<br>
	<br>

	<h1 style="text-align: center">Doctor Doom</h1>

	<div class="menu">
		<a href="menu-logged.html" title='Menu' data-toggle='tooltip'><button class="menubutton">Menu</button></a>
		<a href="character-details.html" title='Character Details' data-toggle='tooltip'><button class="menubutton">Character Details</button></a>
		<a href="comics.html" title='Comics' data-toggle='tooltip'><button class="menubutton">Comics</button></a>
		<a href="games.html" title='Games' data-toggle='tooltip'><button class="menubutton">Games</button></a>
		<button class="menubutton">Movies</button>
		<button class="menubutton">Gallery</button>
		<button class="menubutton">Merch</button>
		<button class="menubutton">Forum</button>
		<button class="menubuttonon">Fanart</button>
		<a href="profile-logged.php" title='Profile' data-toggle='tooltip'><button class="menubutton">Profile</button></a>
	</div>

	<section>

		<div id="column1"></div>
	
		<article>
			<form action="fanart.php" method="post" enctype="multipart/form-data">

                <input type="file" name="file" size="100">

                <br />

                <input type="submit" name="submit" value="Upload">

            </form>
		</article>

		<div id="column2"></div>

	</section>

	<footer>
		<p>Text</p>
	</footer>

</body>
</html>
