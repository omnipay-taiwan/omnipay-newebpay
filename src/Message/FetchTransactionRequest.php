<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\NewebPay\Traits\HasDefaults;

class FetchTransactionRequest extends AbstractRequest
{
    use HasDefaults;

    public function getEndpoint()
    {
        return parent::getEndpoint().'API/QueryTradeInfo';
    }

    /**
     * 資料來源.
     * 預設為空值。
     *   1.若為複合式商店(MS5 開頭) ，此欄位為必填
     *   2.複合式商店查詢請固定填入：Composite設定此參數會查詢 複合式商店旗下對應商店的訂單。
     *   3.若沒有帶[Gateway]或是帶入其他參數值，則查詢一般商店代號。
     *
     * @param  string  $value
     * @return self
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
     * @throws InvalidResponseException
     */
    public function getData()
    {
        $postData = array_filter([
            'MerchantID' => $this->getMerchantID(),
            'Version' => $this->getVersion() ?: '1.3',
            'RespondType' => $this->getRespondType(),
            'TimeStamp' => $this->getTimeStamp(),
            'MerchantOrderNo' => $this->getTransactionId(),
            'Amt' => (int) $this->getAmount(),
            'Gateway' => $this->getGateway(),
        ], static function ($value) {
            return $value !== null && $value !== '';
        });

        $postData['CheckValue'] = $this->checkValue($postData);

        $response = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query($postData));

        $decode = $this->decodeResponse($response);
        if (! hash_equals($this->checkCode($decode), $decode['CheckCode'] ?: '')) {
            throw new InvalidResponseException('Incorrect CheckCode');
        }

        return $decode;
    }

    public function sendData($data)
    {
        return $this->response = new FetchTransactionResponse($this, $data);
    }
}
