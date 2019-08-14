<?php

/**
 * @author  Paymentwall Inc <devsupport@paymentwall.com>
 * @package Paymentwall\ThirdpartyIntegration\Magento\Model\Method
 */
class Paymentwall_Paymentwall_Model_Method_Pwlocal extends Paymentwall_Paymentwall_Model_Method_Abstract {
    protected $_isGateway = true;
    protected $_canUseInternal = false;
    protected $_canUseForMultishipping = false;

    /**
     * Constructor method.
     * Set some internal properties
     */
    public function __construct() {
        parent::__construct('pwlocal');
    }

    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('paymentwall/payment/pwlocal', array('_secure' => true));
    }

    /**
     * Generate Paymentwall Widget
     * @param $order
     * @return Paymentwall_Widget
     */
    public function getPaymentWidget(Mage_Sales_Model_Order $order) {
        $this->initPaymentwallConfig();

        $customerId = $_SERVER['REMOTE_ADDR'];

        if(Mage::getSingleton('customer/session')->isLoggedIn()){
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerId = $customer->getId();
        }

        $widget = new Paymentwall_Widget(
            $customerId,
            $this->getConfigData('paymentwall_widget_code'),
            array(
                new Paymentwall_Product(
                    $order->getIncrementId(),
                    $order->getGrandTotal(),
                    $order->getOrderCurrencyCode(),
                    'Order id #' . $order->getIncrementId(),
                    Paymentwall_Product::TYPE_FIXED
                )
            ),
            array_merge(
                array(
                    'email' => $order->getCustomerEmail(),
                    'success_url' => $this->getConfigData('paymentwall_url'),
                    'test_mode' => (int)$this->getConfigData('paymentwall_istest'),
                    'integration_module' => 'magento'
                ),
                $this->prepareUserProfile($order) // for User Profile API
            )
        );

        return $widget;
    }
}