<?php

$conn =
new mysqli(
"localhost",
"root",
"",
"inventorysystem_db"
);

if($conn->connect_error){
die("Connection Failed");
}

?>