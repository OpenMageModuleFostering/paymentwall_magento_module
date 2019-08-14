<?php

/**
 * @author Paymentwall Inc. <devsupport@paymentwall.com>
 * @package Paymentwall\ThirdpartyIntegration\Magento
 *
 * Class Paymentwall_Paymentwall_Block_Checkout_Info_Method_Pwbrick
 */
class Paymentwall_Paymentwall_Block_Checkout_Info_Method_Pwbrick extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentwall/checkout/info/method/pwbrick.phtml');
    }

    public function setInfo($info)
    {
        $this->setData('info', $info);
        $this->setData('method', $info->getMethodInstance());
        return $this;
    }
}