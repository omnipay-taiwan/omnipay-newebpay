<?php

namespace Omnipay\NewebPay\Traits;

trait HasDefaults
{
    public function setHashKey($value)
    {
        return $this->setParameter('HashKey', $value);
    }

    public function getHashKey()
    {
        return $this->getParameter('HashKey');
    }

    public function setHashIV($value)
    {
        return $this->setParameter('HashIV', $value);
    }

    public function getHashIV()
    {
        return $this->getParameter('HashIV');
    }

    public function setMerchantID($value)
    {
        return $this->setParameter('MerchantID', $value);
    }

    public function getMerchantID()
    {
        return $this->getParameter('MerchantID');
    }
}
