<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class CreatePeriodResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->request->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return [
            'MerchantID_' => $this->data['MerchantID'],
            'PostData_' => $this->request->encrypt($this->data),
        ];
    }
}
