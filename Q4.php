<?php
print ("<br>");
$repnum = isset($_POST['repnum']) ? $_POST['repnum'] : '';
$visited = isset($_POST['visited']) ? $_POST['visited'] : '';
$repnummsg = '';
if (! $repnum) {	  
    if ($visited) {
       $repnummsg = 'Please enter rep number';
    }

 // printing the form to enter the user input
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 <br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$repnummsg</font><br>
 Rep Number: <input type="text" name="repnum" size="15" value="$repnum">
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

  $querystring = "SELECT r.firstname as repFirstName, r.lastname as repLastName, c.customername as customerName, SUM(l.numordered * l.quotedprice) as billedPerCustomer "
		. "FROM customer as c, rep as r, orders as o, orderline as l "
		. "WHERE r.repnum = $repnum AND c.repnum = r.repnum AND o.customernum = c.customernum AND o.ordernum = l.ordernum "
		. "GROUP BY c.customername";

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
	
  //output
  $obj = @mysqli_fetch_object($result);
  $repfname = $obj->repFirstName;
  $replname = $obj->repLastName;
  $totalbilled = 0;
  print("Rep First Name: $repfname ------- Rep Last Name: $replname<br><br>");
  print("Customers:<br>");
  do {
	$custname = $obj->customerName;
	$customerbill = $obj->billedPerCustomer;
	print("Customer Name: $custname ------ ");
    	print("Customer Bill: $customerbill <br><br>");
	$totalbilled = $totalbilled + $customerbill;
  } while( $obj = @mysqli_fetch_object($result) );
 
  print("Total Amount Billed to All Customers: $$totalbilled <br>");
  
  mysqli_close($con);
}
?>