<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasDefaults;

class CompletePurchaseRequest extends AbstractRequest
{
    use HasDefaults;

    /**
     * 判斷是否為定期定額回應.
     *
     * @return bool
     */
    protected function isPeriod(): bool
    {
        return $this->httpRequest->request->has('Period');
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        if ($this->isPeriod()) {
            // 定期定額：只有 Period 參數，無需驗證 TradeSha
            $period = $this->httpRequest->request->get('Period');

            return $this->decodeResponse($this->decrypt($period));
        }

        // 一般交易：需要驗證 TradeSha
        $tradeInfo = $this->httpRequest->request->get('TradeInfo');
        if (! hash_equals($this->tradeSha($tradeInfo), $this->httpRequest->get('TradeSha', ''))) {
            throw new InvalidRequestException('Incorrect TradeSha');
        }

        return $this->decodeResponse($this->decrypt($tradeInfo));
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
