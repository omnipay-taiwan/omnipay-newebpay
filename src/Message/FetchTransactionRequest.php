<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Encryptor;
use Omnipay\NewebPay\Traits\HasDefaults;

class FetchTransactionRequest extends AbstractRequest
{
    use HasDefaults;

    protected $liveEndpoint = 'https://core.newebpay.com/API/QueryTradeInfo';

    protected $testEndpoint = 'https://ccore.newebpay.com/API/QueryTradeInfo';

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * 資料來源.
     * 預設為空值。
     *   1.若為複合式商店(MS5 開頭) ，此欄位為必填
     *   2.複合式商店查詢請固定填入：Composite設定此參數會查詢 複合式商店旗下對應商店的訂單。
     *   3.若沒有帶[Gateway]或是帶入其他參數值，則查詢一般商店代號。
     *
     * @param  string  $value
     * @return static
     */
    public function setGateway($value)
    {
        return $this->setParameter('Gateway', $value);
    }

    /**
     * @return ?string
     */
    public function getGateway()
    {
        return $this->getParameter('Gateway');
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());

        return array_filter([
            'MerchantID' => $this->getMerchantID(),
            'Version' => $this->getVersion() ?: '1.3',
            'RespondType' => $this->getRespondType(),
            'CheckValue' => $encryptor->checkValue([
                'Amt' => (int) $this->getAmount(),
                'MerchantID' => $this->getMerchantID(),
                'MerchantOrderNo' => $this->getTransactionId(),
            ]),
            'TimeStamp' => $this->getTimeStamp(),
            'MerchantOrderNo' => $this->getTransactionId(),
            'Amt' => (int) $this->getAmount(),
            'Gateway' => $this->getGateway(),
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
        ], http_build_query($data));
        $result = json_decode((string) $response->getBody(), true);

        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());

        if (! hash_equals($result['Result']['CheckCode'], $encryptor->checkCode([
            'MerchantID' => $result['Result']['MerchantID'],
            'Amt' => $result['Result']['Amt'],
            'MerchantOrderNo' => $result['Result']['MerchantOrderNo'],
            'TradeNo' => $result['Result']['TradeNo'],
        ]))) {
            throw new InvalidResponseException('Incorrect CheckCode');
        }

        return $this->response = new FetchTransactionResponse($this, $result);
    }
}
