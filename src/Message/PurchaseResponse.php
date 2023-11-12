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
        $tradeInfo = $this->request->encrypt($this->data);

        return [
            'MerchantID' => $this->data['MerchantID'],
            'TradeInfo' => $tradeInfo,
            'TradeSha' => $this->request->tradeSha($tradeInfo),
            'Version' => $this->data['Version'],
        ];
    }
}
