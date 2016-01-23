<?php

class Muaw_ProductInRange_IndexController extends Mage_Core_Controller_Front_Action
{
    public function IndexAction()
    {
        $this->loadLayout();
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home Page"),
            "title" => $this->__("Home Page"),
            "link" => Mage::getBaseUrl()
        ));

        $breadcrumbs->addCrumb("product in range", array(
            "label" => $this->__("Product In Range"),
            "title" => $this->__("Product In Range")
        ));

        $this->renderLayout();

    }

        public function requestAction()
    {
        $data = $this->getRequest()->getPost();
        if ($data['min-price'] == '' && $data['max-price'] == '') {
            $response = $this->__('Please fill the required fields');
        } elseif ($data['min-price'] < 0 || $data['min-price'] > $data['max-price'] || $data['max-price'] > 5 * $data['min-price']) {
            $response = $this->__('Please input correct data');
        } else {
            $products = Mage::getModel('catalog/product')->getCollection();
            $products->addAttributeToFilter('price', array('lt' => $data['max-price']));
            $products->addAttributeToFilter('price', array('gt' => $data['min-price']));
            $products->addAttributeToSort('price', $data['sort']);
            $products->addAttributeToFilter('status', 1); // enabled
            $products->addAttributeToSelect('*');
            Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($products);
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
            $products->setPageSize(10);
            if (count($products) == 0) {
                $response = $this->__("There are no product matching with range!");
            } else {
                $block = $this->getLayout()->createBlock('productinrange/products')->setTemplate('productinrange/products.phtml');
                $block->setProducts($products);
                $response = $block->toHtml();
            }
        }
        $this->getResponse()->setBody($response);
    }
}