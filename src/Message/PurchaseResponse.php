<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\NewebPay\Encryptor;

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
        $encryptor = new Encryptor($this->request->getHashKey(), $this->request->getHashIv());

        $tradeInfo = $encryptor->encrypt($this->data);

        return [
            'MerchantID' => $this->data['MerchantID'],
            'TradeInfo' => $tradeInfo,
            'TradeSha' => $encryptor->makeHash($tradeInfo, true),
            'Version' => $this->request->getVersion(),
        ];
    }
}
