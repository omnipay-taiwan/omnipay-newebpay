<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Traits\HasDefaults;

class VoidRequest extends AbstractRequest
{
    use HasDefaults;

    public function getEndpoint()
    {
        return parent::getEndpoint().'API/CreditCard/Cancel';
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

    /**
     * @throws InvalidRequestException
     * @throws InvalidResponseException
     */
    public function getData()
    {
        $postData = array_filter([
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

        $response = $this->sendEncryptedRequest(
            $this->httpClient,
            $this->getEndpoint(),
            $this->getMerchantID(),
            $postData
        );

        $decode = $this->decodeResponse($response);
        if (! hash_equals($this->checkCode($decode), $decode['CheckCode'] ?? '')) {
            throw new InvalidResponseException('Incorrect CheckCode');
        }

        return $decode;
    }

    public function sendData($data)
    {
        return $this->response = new VoidOrRefundResponse($this, $data);
    }
}
