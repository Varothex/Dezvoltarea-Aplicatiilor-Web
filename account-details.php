<?php
session_start();
if (!isset($_SESSION['username']))
{ 
    $_SESSION['msg'] = "You have to log in first"; 
    header('location: login.php'); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User details</title>
	<img class="ddpic" src="/img/Doctor Doom.png" alt="Doctor Doom">
	<meta charset="UTF-8">
	
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
			background-image: url('/img/wallCRUD.png');
			background-repeat: no-repeat;
			background-attachment: fixed;
			background-size: cover;
			text-align: center;
		}
	
		*
		{
			box-sizing: border-box;
		}
		
		.table
		{
			background-color: green;
			border: none;
			padding: 32px;
			font-size: 16px;
			margin-left: auto;
			margin-right: auto;
			opacity:0.9;
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

		.menubutton:hover
		{
			background-color: lightgreen;
			color: black;
		}
		
	</style>
</head>

<body id="wall">

	<br>
	<br>
	<br>
    <br>

	<?php $id = $_SESSION['id']; ?> 

    <div>
        <div>
            <div>
                <div>
                    <div>
                        <h2 style="color: green; margin-top: 10%">Account Details</h2>
                    </div>
                    <?php
                    require_once "config.php";

					$sql = "SELECT * FROM users WHERE id=$id";
                    if($result = mysqli_query($link, $sql))
                    {
                        if(mysqli_num_rows($result) > 0)
                        {
                            echo "<table class='table'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Username</th>";
                                        echo "<th>Creation Date</th>";
										echo "<th>Rank</th>";
                                        echo "<th>Actions</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result))
                                {
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . htmlspecialchars($row['username'], ENT_XHTML | ENT_QUOTES) . "</td>";
                                        echo "<td>" . $row['created_at'] . "</td>";
										echo "<td>" . $row['rank'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='reset-username.php?id=". $row['id'] ."' title='Reset Username' data-toggle='tooltip'><button class='menubutton'>Reset Username</button></a>";
											echo "<a href='reset-password.php?id=". $row['id'] ."' title='Reset Password' data-toggle='tooltip'><button class='menubutton'>Reset Password</button></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Account' data-toggle='tooltip'><button class='menubutton'>Delete Account</button></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            mysqli_free_result($result);
                        } 
                        else
                        {
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } 
                    else
                    {
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
					
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
	<p style="margin-bottom: 10%;"><a href='profile-logged.php' title='Back' data-toggle='tooltip'><button class='menubutton'>Back</button></a></p>
</body>
</html>