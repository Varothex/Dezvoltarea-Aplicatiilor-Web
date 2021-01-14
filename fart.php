<?php
session_start();
echo $_SESSION["id"];
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