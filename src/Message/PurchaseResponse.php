<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
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
        if ($this->isPeriod()) {
            return [
                'MerchantID_' => $this->data['MerchantID'],
                'PostData_' => $this->request->encrypt($this->data),
            ];
        }

        $tradeInfo = $this->request->encrypt($this->data);

        return [
            'MerchantID' => $this->data['MerchantID'],
            'TradeInfo' => $tradeInfo,
            'TradeSha' => $this->request->tradeSha($tradeInfo),
            'Version' => $this->data['Version'],
        ];
    }

    protected function isPeriod(): bool
    {
        return $this->request->isPeriod();
    }
}
