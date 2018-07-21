<?php
print ("<br>");
$ordernum = isset($_POST['ordernum']) ? $_POST['ordernum'] : '';
$visited = isset($_POST['visited']) ? $_POST['visited'] : '';
$ordernummsg = '';
if (! $ordernum) {	  
    if ($visited) {
       $ordernummsg = 'Please enter order number';
    }

 // printing the form to enter the user input
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 <br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$ordernummsg</font><br>
 Order Number: <input type="text" name="ordernum" size="15" value="$ordernum">
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

  $querystring = "SELECT c.customername as customerName, COUNT(*) as orderLineItems, SUM(l.numordered * l.quotedprice) as totalPrice "
		. "FROM customer as c, orders as o, orderline as l "
		. "WHERE o.ordernum ='$ordernum' AND o.customernum = c.customernum AND o.ordernum = l.ordernum";

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
    //output
    $custname = $obj->customerName;
    $orderlitems = $obj->orderLineItems;
    $totalprice = $obj->totalPrice;
    print("Customer Name: $custname <br>");
    print("Number of Line Items in Order: $orderlitems <br>");
    print("Total Amount Billed: $$totalprice <br>");
  }
  else {
    // Query not successful
    die("Sorry, Query has some error.<br>");
  }
  mysqli_close($con);
}
?>