<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Traits\HasDefaults;

class VoidRequest extends AbstractRequest
{
    use HasDefaults;

    protected $liveEndpoint = 'https://core.newebpay.com/API/CreditCard/Cancel';

    protected $testEndpoint = 'https://ccore.newebpay.com/API/CreditCard/Cancel';

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * 藍新金流交易序號.
     * 與商店訂單編號二擇一填入
     *
     * @param  string  $value
     * @return self
     */
    public function setTradeNo($value)
    {
        return $this->setTransactionReference($value);
    }

    /**
     * @return ?string
     */
    public function getTradeNo()
    {
        return $this->getTransactionReference();
    }

    /**
     * 單號類別.
     * 只限定填數字 1 或 2
     *   1 表示使用商店訂單編號
     *   2 表示使用藍新金流交易單號
     *
     * @param  int  $value
     * @return self
     */
    public function setIndexType($value)
    {
        return $this->setParameter('IndexType', $value);
    }

    /**
     * @return int
     */
    public function getIndexType()
    {
        return $this->getParameter('IndexType') ?: 1;
    }

    public function getData()
    {
        return array_filter([
            'RespondType' => $this->getRespondType(),
            'Version' => $this->getVersion() ?: '1.0',
            'Amt' => (int) $this->getAmount(),
            'MerchantOrderNo' => $this->getTransactionId(),
            'TradeNo' => $this->getTransactionReference(),
            'IndexType' => $this->getIndexType(),
            'TimeStamp' => $this->getTimeStamp(),
        ], static function ($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * @throws InvalidResponseException
     */
    public function sendData($data)
    {
        $response = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'MerchantID_' => $this->getMerchantID(),
            'PostData_' => $this->encrypt($data),
        ]));

        $result = [];
        parse_str(trim((string) $response->getBody()), $result);

        if (! hash_equals($result['CheckCode'], $this->checkCode($result))) {
            throw new InvalidResponseException('Incorrect CheckCode');
        }

        return $this->response = new VoidResponse($this, $result);
    }
}
