<?php
print ("<br>");
$custnum = isset($_POST['custnum']) ? $_POST['custnum'] : '';
$visited = isset($_POST['visited']) ? $_POST['visited'] : '';
$custnummsg = '';
if (! $custnum) {	  
    if ($visited) {
       $custnummsg = 'Please enter customer number';
    }

 // printing the form to enter the user input
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 <br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$custnummsg</font><br>
 Customer Number: <input type="text" name="custnum" size="15" value="$custnum">
 <br/>
 <br>
 <INPUT type="submit" value=" Submit ">
 <INPUT type="hidden" name="visited" value="true">
 </FORM>
_HTML_;
 
}
else {
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

  $querystring = "SELECT c.customername as customerName, r.firstname as repFirstName, r.lastname as repLastName, COUNT(*) as numberOfOrders "
		. "FROM customer as c, rep as r, orders as o "
		. "WHERE c.customernum ='$custnum' AND c.repnum = r.repnum AND c.customernum = o.customernum";

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
  if ( $obj = @mysqli_fetch_object($result) ) {
    // Login good, create session variables
    $custname = $obj->customerName;
    $replname = $obj->repLastName;
    $repfname = $obj->repFirstName;
    $numorders = $obj->numberOfOrders;
    print("Customer Name: $custname <br>");
    print("Rep First Name: $repfname <br>");
    print("Rep Last Name: $replname <br>");
    print("Total Number Of Orders: $numorders <br>");
  }
  else {
    // Query not successful
    die("Sorry, Query has some error.<br>");
  }
  mysqli_close($con);
}
?>