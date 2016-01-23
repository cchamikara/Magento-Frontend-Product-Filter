<?php

class Muaw_ProductInRange_Block_Products extends Mage_Core_Block_Template
{
    protected $_products;

    public function setProducts($products)
    {
        $this->_products = $products;
    }

    public function getProducts()
    {
        return $this->_products;
    }

}