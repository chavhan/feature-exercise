<?php
ini_set('display_errors', 1);            // avoiding any random error on screen
include "calculateOrderPrice.php";
$calPrice = new CalculateOrderPrice( );
$totalItems = array( 'A'=>4,'B'=>2,'C'=>5,'D'=>5,'E'=>5 );  // input array of all item with their quantity
$totalItems = array( 'C'=>7 ); 
$result = $calPrice->calculateTotalPriceSku( $totalItems );  // calculating cost price of all items
unset($calPrice);    // free memory once process done

if( $result )
{
    echo $result;
}
else
    echo 'failed';

?>