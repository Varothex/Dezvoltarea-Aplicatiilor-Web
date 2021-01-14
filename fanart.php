<?php
    session_start();
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
			<form action="fart.php" method="post" enctype="multipart/form-data">

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