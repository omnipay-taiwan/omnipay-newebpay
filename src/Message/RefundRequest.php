<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Traits\HasDefaults;

class RefundRequest extends AbstractRequest
{
    use HasDefaults;

    protected $liveEndpoint = 'https://ccore.newebpay.com/API/CreditCard/Close';

    protected $testEndpoint = 'https://ccore.newebpay.com/API/CreditCard/Close';

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
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
     * 藍新金流交易序號.
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
     * 請款或退款.
     *   請款 B031 / 取消請款 B033 時請填 1
     *   退款 B032 / 取消退款 B034 時請填 2
     *
     * @param  string  $value
     * @return self
     */
    public function setCloseType($value)
    {
        return $this->setParameter('CloseType', $value);
    }

    /**
     * @return ?string
     */
    public function getCloseType()
    {
        return $this->getParameter('CloseType');
    }

    /**
     * 取消請款或退款.
     * 配合 CloseType 欄位，欲發動取消請款 (B033)或取消退款(B034)時此欄請填 1
     *
     * @param  string  $value
     * @return self
     */
    public function setCancel($value)
    {
        return $this->setParameter('Cancel', $value);
    }

    /**
     * @return ?string
     */
    public function getCancel()
    {
        return $this->getParameter('Cancel');
    }

    /**
     * @throws InvalidResponseException
     */
    public function getData()
    {
        $postData = array_filter([
            'RespondType' => $this->getRespondType(),
            'Version' => $this->getVersion() ?: '1.1',
            'Amt' => (int) $this->getAmount(),
            'MerchantOrderNo' => $this->getTransactionId(),
            'TimeStamp' => $this->getTimeStamp(),
            'IndexType' => $this->getIndexType(),
            'TradeNo' => $this->getTransactionReference(),
            'CloseType' => $this->getCloseType() ?: 2,
            'Cancel' => $this->getCancel(),
        ], static function ($value) {
            return $value !== null && $value !== '';
        });
        $response = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'MerchantID_' => $this->getMerchantID(),
            'PostData_' => $this->encrypt($postData),
        ]));

        return $this->decodeResponse($response);
    }

    public function sendData($data)
    {
        return $this->response = new VoidOrRefundResponse($this, $data);
    }
}
