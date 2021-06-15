<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
include 'C:\wamp64\www\supermarket\CalculateOrderPrice.php';

class calculateOrderPricetest extends TestCase
{
    
    // Test1 for checking system generating right result.
    // Testing random SkUs with multiple offers 
    public function testtotalPrice()
    {
        echo __DIR__;
        $calprice = new CalculateOrderPrice();
        $totalItems = array('A'=>13,'B'=>2,'C'=>5,'D'=>5,'E'=>5); 
        $this->assertEquals('753', $calprice->calculateTotalPriceSku($totalItems));

    }
    
    // Test2 for check system will execcept wrong Items 
    public function testvalidItem()
    {
        $calprice1 = new CalculateOrderPrice();
        $totalItems1 = array('R'=>13,'B'=>2,'C'=>5,'D'=>5,'E'=>5); 
        $this->assertFalse($calprice1->calculateTotalPriceSku($totalItems1));

    }

    // Test3 for checking invalid quantity of item
    public function testvalidQuantity()
    {
        $calprice2 = new CalculateOrderPrice();
        $totalItems2 = array('A'=>-13,'B'=>2,'C'=>5,'D'=>5,'E'=>5); 
        $this->assertFalse($calprice2->calculateTotalPriceSku($totalItems2));

    }

    // Testing SKU A with 3 count in offer and 1 without offer
    public function testSkuA()
    {
        
        $calprice3 = new CalculateOrderPrice();
        $totalItems3 = array('A'=>4); 
        $this->assertEquals('180', $calprice3->calculateTotalPriceSku($totalItems3));

    }

    // Testing SKU B with offer 
    public function testSkuB()
    {
        
        $calprice4 = new CalculateOrderPrice();
        $totalItems4 = array('B'=>2); 
        $this->assertEquals('45', $calprice4->calculateTotalPriceSku($totalItems4));

    }

    // Testing SKU C with offer 
    public function testSkuC()
    {
        
        $calprice5 = new CalculateOrderPrice();
        $totalItems5 = array('C'=>5); 
        $this->assertEquals('88', $calprice5->calculateTotalPriceSku($totalItems5));

    }

     // Testing SKU D with combo offer 
     public function testSkuD()
     {
         
         $calprice6 = new CalculateOrderPrice();
         $totalItems6 = array('D'=>5); 
         $this->assertEquals('75', $calprice6->calculateTotalPriceSku($totalItems6));
 
     }

    // Testing SKU E with no offer 
      public function testSkuE()
      {
          
          $calprice7 = new CalculateOrderPrice();
          $totalItems7 = array('E'=>5); 
          $this->assertEquals('25', $calprice7->calculateTotalPriceSku($totalItems7));
  
      }

} 

?>