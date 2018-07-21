<?php
  $host="localhost";
  $user="root";
  $password="";
  $dbname="premiere";
  $con=mysqli_connect($host, $user, $password, $dbname);
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MariaDB: " . mysqli_connect_error();
    exit;
  }

  print (" Successfully connected to " . $dbname . " database.<br>");
  print (" Fetching Pair of customers associated with the same rep: <br><br>");


  $querystring = "SELECT c.customername as customerName1, e.customername as customerName2 "
		. "FROM customer as c, customer as e "
		. "WHERE c.repnum = e.repnum AND c.customername < e.customername "
		. "GROUP BY c.customername, e.customername";

  $result = mysqli_query($con, $querystring);
  if (!$result) {
    print ( "Could not successfully run query ($querystring) from DB: " . mysqli_error($con) . "<br>");
    exit;
  }

  if (mysqli_num_rows($result) == 0) {
    print ("No rows found, nothing to print so am exiting<br>");
    exit;
  }

  print("Result:<br>");
  while ( $row = @mysqli_fetch_object($result) ) {
    //output
    print ($row->customerName1 . " --- " . $row->customerName2);
    print "<br>";
  }
  mysqli_close($con)
?>