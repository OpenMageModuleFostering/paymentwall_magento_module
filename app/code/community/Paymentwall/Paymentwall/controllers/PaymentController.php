<?php

/**
 * @author Paymentwall Inc. <devsupport@paymentwall.com>
 * @package Paymentwall\ThirdpartyIntegration\Magento
 */

/**
 * Class Paymentwall_Payment_PaymentController
 */
class Paymentwall_Paymentwall_PaymentController extends Mage_Core_Controller_Front_Action
{

    /**
     * Action that handles pingback call from paymentwall system
     * @return string
     */
    public function ipnAction()
    {
        $result = Mage::getModel('paymentwall/pingback')->handlePingback();
        die($result);
    }

    /**
     * Show Paymentwall widget
     * For Pw Local
     */
    public function pwlocalAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Handle ajax payment listener on Widget page
     * For Pw Local
     */
    public function ajaxPwlocalAction()
    {
        // Get current order id
        $curOrderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $response = array(
            'status' => 0,
            'url' => '',
            'message' => ''
        );

        if ($curOrderId) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($curOrderId);
            if ($order->getId()) {

                if ($response['status'] = $order->getStatus() == Mage_Sales_Model_Order::STATE_PROCESSING
                    || $order->getStatus() == Mage_Sales_Model_Order::STATE_CANCELED
                ) {
                    $response['status'] = 1;
                    $response['message'] = $order->getStatus() == Mage_Sales_Model_Order::STATE_CANCELED
                        ? "<h3>{$this->__("Payment Canceled")}</h3>"
                        : "<h3>{$this->__("Payment Processed")}</h3>";

                    // Clear shopping cart
                    Mage::getSingleton('checkout/cart')->truncate();

                } else {
                    $response['status'] = 0;
                }
                // Get success page redirect url
                $response['url'] = '';
            } else {
                $response['status'] = 2; // Error
                $response['message'] = 'Order Invalid';
            }
        }

        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
        $this->getResponse()->setBody(json_encode($response));
    }
}