<?php

namespace Omnipay\NewebPay\Traits;

trait HasPaymentInfo
{
    /**
     * @param  string  $value
     * @return self
     */
    public function setPaymentInfoUrl($value)
    {
        return $this->setParameter('paymentInfoUrl', $value);
    }

    /**
     * @return string
     */
    public function getPaymentInfoUrl()
    {
        return $this->getParameter('paymentInfoUrl');
    }
}
