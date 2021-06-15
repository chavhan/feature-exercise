<?php
include "Skus.php";
class CalculateOrderPrice extends Skus
{
    /******************************************** */
    // Function to calculate the price of individual item depending on quantity and offers 
    // input var item = item name @var aplphabet 
    // input var quantity  = quantity of items @numeric
    // input var $itemArray = inter order information needed for calculation if offer is in combination
    // with other item @array
    // @output var result = array having atribute like sucess,error msg, price value @array
    /******************************************** */
    public function calculatePriceSku( $item,$quantity,$itemArray )
    {
        if(! $this->verifySku( $item ) )            // condition to check, user input item is valid or not          
        {
            return false;                // raise error if user input item is not valid
        }
        $items = $this->getSkus( );  
        if( !$items )
        {
            return false;                  // Any error in retriving system sku list, return false 
        }
        //$items = $items['items'];
        if( $quantity < 1 || !is_int( $quantity ) )
        {
            return false;
        }
           
        if( $items[$item]['sprice'] == 0 )  // if item dosen't have offers simply return base price cost
        {
            $itemPrice = $this->calculateNoOfferPrice( $items,$item,$quantity );
            return $itemPrice;
        }
        else
        {
            $itemPrice = 0;
            $loopcount = 0;
            foreach( $items[$item]['sprice'] as  $offers ) // if item having offers 
            {
                $loopcount++;
                if( $quantity > 0 )
                {
                    if( $quantity == $offers['itemcount'] && $offers['combo'] == 'NA' )
                    {
                        $processdata =  $this->calculateOfferPriceEqualQuanity( $itemPrice,$quantity,$offers );
                        $itemPrice = $processdata['itemPrice'];
                        $quantity = $processdata['quantity'];
                        
                    }
                    
                    if( $quantity > $offers['itemcount'] && $offers['combo'] == 'NA' )
                    {
                        
                        $processdata =  $this->calculateOfferPriceGreaterQuantity( $itemPrice,$quantity,$offers );
                        $itemPrice = $processdata['itemPrice'];
                        $quantity = $processdata['quantity'];
                    }
                    if( $quantity == 2 && $loopcount == count( $items[$item]['sprice']) )
                    {
                        $processdata =  $this->calculateOfferPriceDoubleQuantity( $itemPrice,$quantity,$items,$item );
                        $itemPrice = $processdata['itemPrice'];
                        $quantity = $processdata['quantity'];
                    }
                    if( $quantity == 1 )
                    {
                        $processdata =  $this->calculateOfferPriceSingleQuantity( $itemPrice,$quantity,$items,$item );
                        $itemPrice = $processdata['itemPrice'];
                        $quantity = $processdata['quantity'];
                    }
                    if( $offers['combo'] != 'NA' )   // if offer is club with other items 
                    {
                        $itemPrice = $this->calculateComboOffer( $itemPrice,$items,$item,$quantity,$offers,$itemArray );
                    }
                }
                
            }
            return $itemPrice;
        
        }
            
    }

    /**************************************************************** */
    // Function to calculate all item price together
    // input array of all items with their quantity @array
    // @output var result = array having atribute like sucess,error msg, total price value @array
    /**************************************************************** */
    public function calculateTotalPriceSku( $itemArray )
    {
        $finalAmnt = 0;
        foreach( $itemArray as $item=>$quantity )
        {
            $result = $this->calculatePriceSku( $item,$quantity,$itemArray );
            //echo'<pre>';
            //print_r( $result );
            if( $result)
            {
                $finalAmnt = $finalAmnt+$result;
            }
            else
            {
                return false;
            }
        }
        return $finalAmnt;
    }
    /****************************************************************** */
    // Function to check whether given item exist in system or not.
    // input item name @var alphabet 
    // output boolean depending on item exist or not @boolean 
    /******************************************************************** */
    public function verifySku( $item )
    {
        $items = $this->getSkus( );
        if( !$items )
        {
            return false;
        }
        if( array_key_exists( $item,$items) )
        {
            return true;
        }
        else
            return false;
    }

    public function calculateNoOfferPrice( $skudata,$item,$quantity )
    {
        
        if( is_array( $skudata ) )
        {
            $itemPrice = $skudata[$item]['price'] * $quantity;
            //echo $itemPrice;
            return $itemPrice;
        }
        else
        {
            return false;
        }
    }

    public function calculateOfferPriceEqualQuanity( $itemPrice,$quantity,$offers )
    {
        $itemPrice = $itemPrice+$offers['totprice'];
        $tempquantity = $quantity - $offers['itemcount'];
        $quantity = $tempquantity; 
        $reviseddata = array('itemPrice'=>$itemPrice,'quantity'=>$quantity);
        return $reviseddata;
    }

    public function calculateOfferPriceGreaterQuantity( $itemPrice,$quantity,$offers )
    {
        $nprice = ( int )( $quantity/$offers['itemcount'] );
        $quantity = $quantity%$offers['itemcount'];
        $itemPrice = $offers['totprice']*$nprice;
        $reviseddata = array('itemPrice'=>$itemPrice,'quantity'=>$quantity);
        return $reviseddata;
    }

    public function calculateOfferPriceSingleQuantity( $itemPrice,$quantity,$items,$item )
    {
        if( $itemPrice > 0)
        {
            $itemPrice = $itemPrice + $items[$item]['price'];
        }
        else
        {
            $itemPrice = $items[$item]['price'];
        }
        $quantity = 0; 
        $reviseddata = array('itemPrice'=>$itemPrice,'quantity'=>$quantity);
        return $reviseddata;
    }

    public function calculateOfferPriceDoubleQuantity( $itemPrice,$quantity,$items,$item )
    {
        if( $itemPrice > 0 )
        {
            $itemPrice = $itemPrice + 2*$items[$item]['price'];
        }
        else
        {
            $itemPrice = $items[$item]['price'];
        }
        $quantity = 0; 
        $reviseddata = array('itemPrice'=>$itemPrice,'quantity'=>$quantity);
        return $reviseddata;
    }

    public function calculateComboOffer( $itemPrice,$items,$item,$quantity,$offers,$itemArray )
    {
        if( array_key_exists( $offers['combo'],$itemArray) )
        {
            if( $quantity > $itemArray[$offers['combo']] )
            {
                $itemPrice = $offers['totprice'] * $itemArray[$offers['combo']];
                $revisedQuantity = $quantity-$itemArray[$offers['combo']];
                $restPrice = $items[$item]['price'] * $revisedQuantity;
                $itemPrice = $itemPrice+$restPrice;
            }
            else
            {
                $itemPrice = $offers['totprice'] * $quantity;
            }
        }
        else
        {
            $itemPrice = $items[$item]['price'] * $quantity;
        }
        return $itemPrice;
    }
}
?>