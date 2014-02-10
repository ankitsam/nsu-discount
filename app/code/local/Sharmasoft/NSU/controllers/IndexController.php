<?php
class Sharmasoft_NSU_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/nsu?id=15 
    	 *  or
    	 * http://site.com/nsu/id/15 	
    	 */
    	/* 
		$nsu_id = $this->getRequest()->getParam('id');

  		if($nsu_id != null && $nsu_id != '')	{
			$nsu = Mage::getModel('nsu/nsu')->load($nsu_id)->getData();
		} else {
			$nsu = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($nsu == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$nsuTable = $resource->getTableName('nsu');
			
			$select = $read->select()
			   ->from($nsuTable,array('nsu_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$nsu = $read->fetchRow($select);
		}
		Mage::register('nsu', $nsu);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array('add');

    /**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    /**
     * Set back redirect url to response
     *
     * @return Mage_Checkout_CartController
     */
    protected function _goBack()
    {
        if (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')
            && !$this->getRequest()->getParam('in_cart')
            && $backUrl = $this->_getRefererUrl()) {

            $this->getResponse()->setRedirect($backUrl);
        } else {
            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
            }
            $this->_redirect('checkout/cart');
        }
        return $this;
    }

    /**
     * Initialize product instance from request data
     *
     * @return Mage_Catalog_Model_Product || false
     */
    protected function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('product');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }

	public function couponPostAction()
	{
		
		$nsu_id = $this->getRequest()->getParam('coupon_code_nsu');
		$couponCode = $nsu_id;
		$resource = Mage::getSingleton('core/resource');
		$read= $resource->getConnection('core_read');
		$nsuTable = $resource->getTableName('nsu');
			
		$select = $read->select()
			->from($nsuTable,array('nsu_id'))
			//->where('status',1);
			->where("stud_id='{$nsu_id}'");
			   
		$nsu = $read->fetchRow($select);
		Mage::register('nsu', $nsu);
		
		//echo $nsu_id;
		//echo $select;
		//print_r($nsu);
		
		if ($nsu == null){
			$this->_getSession()->addError(
					$this->__('NSU ID "%s" is not valid.', Mage::helper('core')->htmlEscape($nsu_id))
					);
			$this->_goBack();
			return;
		}
		else{
			$checkCoupon = Mage::getModel('salesrule/rule')->load($couponCode, 'name');
			if (!$checkCoupon || ! $checkCoupon->getId()){
				$discountprice = 20;
				$model = Mage::getModel('salesrule/rule');
				$couponCode=$nsu_id;
				$model->setName($couponCode);
				$model->setDescription('Discount coupon NSU ID.');
				$model->setUsesPerCoupon(1);
				$model->setUsesPerCustomer(1);
				$model->setCustomerGroupIds('0,1');
				$model->setIsActive(1);
				// $model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
				//$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
				$model->setStopRulesProcessing(0);
				$model->setIsAdvanced(1);
				// $model->setProductIds($productId);
				$model->setSortOrder('0');
				$model->setSimpleAction('by_percent');
				$model->setDiscountAmount($discountprice);
				$model->setDiscountStep(0);
				$model->setSimpleFreeShipping(0);
				$model->setCouponType(2);
				$model->setCouponCode($couponCode);
				$model->setUsesPerCoupon(1);
				$model->setTimesUsed(0);
				$model->setIsRss(0);
				$model->setWebsiteIds('1');
				$model->save();
			}
		}
		//return;
		/**
		 * No reason continue with empty shopping cart
		 */
		if (!$this->_getCart()->getQuote()->getItemsCount()) {
			$this->_goBack();
			return;
		}
		
		/*$couponCode = (string) $this->getRequest()->getParam('coupon_code');
		if ($this->getRequest()->getParam('remove') == 1) {
			$couponCode = '';
		}*/
		$oldCouponCode = $this->_getQuote()->getCouponCode();
		
		if (!strlen($couponCode) && !strlen($oldCouponCode)) {
			$this->_goBack();
			return;
		}
		
		try {
			$this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
			$this->_getQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
				->collectTotals()
				->save();
			
			if ($couponCode) {
				if ($couponCode == $this->_getQuote()->getCouponCode()) {
					$this->_getSession()->addSuccess(
							$this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->htmlEscape($couponCode))
							);
				}
				else {
					$this->_getSession()->addError(
							$this->__('Coupon code "%s" is not valid.', Mage::helper('core')->htmlEscape($couponCode))
							);
				}
			} else {
				$this->_getSession()->addSuccess($this->__('Coupon code was canceled successfully.'));
			}
			
		}
		catch (Mage_Core_Exception $e) {
			$this->_getSession()->addError($e->getMessage());
		}
		catch (Exception $e) {
			$this->_getSession()->addError($this->__('Can not apply coupon code.'));
		}
		
		$this->_goBack();
	}
}