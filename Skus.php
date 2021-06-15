<?php
class Skus {
    /**
     * The Stock Keeping Units sku.
     *
     * @var array
     */
    private $sku = array(
        'A'=>array('price'=>50,'sprice'=>array(array('itemcount'=>3,'totprice'=>130,'combo'=>'NA'))),
        'B'=>array('price'=>30,'sprice'=>array(array('itemcount'=>2,'totprice'=>45,'combo'=>'NA'))),
        'C'=>array('price'=>20,'sprice'=>array(array('itemcount'=>3,'totprice'=>50,'combo'=>'NA'),array('itemcount'=>2,'totprice'=>38,'combo'=>'NA'))),
        'D'=>array('price'=>15,'sprice'=>array(array('itemcount'=>0,'totprice'=>5,'combo'=>'A'))),
        'E'=>array('price'=>5,'sprice'=>0)
    );
    
    
    /******************************************** */
    // Fucnction for retrieving all existing items in system 
    // input not required 
    // output multidimentional array of all items 
    // One item can have multiple offers and can be club with other item 
    /******************************************** */
    protected function getSkus()
    {
        if(is_array($this->sku))
        {
            return $this->sku;
        }
        else
        {
            return false;
        }

    }

}
?>