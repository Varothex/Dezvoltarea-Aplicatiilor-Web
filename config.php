<?php
<?php
define('DB_SERVER', 'sql206.epizy.com');
define('DB_USERNAME', 'epiz_27117785');
define('DB_PASSWORD', 'fiCNhIXlBS0cZ');
define('DB_NAME', 'epiz_27117785_doctor_doom');

/*define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'doctor doom');*/
 
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
if($link === true){
	die("Connection successful.");
}
?>