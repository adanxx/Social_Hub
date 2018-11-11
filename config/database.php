<?php
  ob_start(); // Turning on output buffering
  session_start();


  $timezone = date_default_timezone_set("Europe/London"); //set the defualt timezone To London/EU

  $conn = mysqli_connect('localhost','root','','social');

  if(mysqli_connect_errno()){
      echo "failed to connect: " . mysqli_connect_errno();
  }  
?>