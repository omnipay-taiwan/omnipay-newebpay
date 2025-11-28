<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasDefaults;
use Omnipay\NewebPay\Traits\HasPeriod;

class AlterPeriodStatusRequest extends AbstractRequest
{
    use HasDefaults;
    use HasPeriod;

    public function getEndpoint()
    {
        return parent::getEndpoint().'MPG/period/AlterStatus';
    }

    /**
     * 委託狀態.
     * suspend=暫停
     * terminate=終止
     * restart=啟用
     *
     * @param  string  $value
     * @return self
     */
    public function setAlterType($value)
    {
        return $this->setParameter('AlterType', $value);
    }

    /**
     * @return ?string
     */
    public function getAlterType()
    {
        return $this->getParameter('AlterType');
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionId', 'PeriodNo', 'AlterType');

        return [
            'RespondType' => $this->getRespondType(),
            'Version' => $this->getVersion() ?: '1.0',
            'MerOrderNo' => $this->getTransactionId(),
            'PeriodNo' => $this->getPeriodNo(),
            'AlterType' => $this->getAlterType(),
            'TimeStamp' => $this->getTimeStamp(),
        ];
    }

    public function sendData($data)
    {
        $response = $this->sendEncryptedRequest(
            $this->httpClient,
            $this->getEndpoint(),
            $this->getMerchantID(),
            $data
        );

        return $this->response = new AlterPeriodStatusResponse($this, $this->decodePeriodResponse($response));
    }
}
