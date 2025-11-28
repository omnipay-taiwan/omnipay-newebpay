<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasDefaults;
use Omnipay\NewebPay\Traits\HasPeriod;

class AlterPeriodAmtRequest extends AbstractRequest
{
    use HasDefaults;
    use HasPeriod;

    public function getEndpoint()
    {
        return parent::getEndpoint().'MPG/period/AlterAmt';
    }

    /**
     * 委託金額.
     *
     * @param  int  $value
     * @return self
     */
    public function setAlterAmt($value)
    {
        return $this->setParameter('AlterAmt', $value);
    }

    /**
     * @return ?int
     */
    public function getAlterAmt()
    {
        return $this->getParameter('AlterAmt');
    }

    /**
     * 信用卡到期日.
     * 格式為年月，例：2021年5月則填入『2105』
     *
     * @param  string  $value
     * @return self
     */
    public function setExtday($value)
    {
        return $this->setParameter('Extday', $value);
    }

    /**
     * @return ?string
     */
    public function getExtday()
    {
        return $this->getParameter('Extday');
    }

    /**
     * 每期授權結果通知網址.
     *
     * @param  string  $value
     * @return self
     */
    public function setNotifyURL($value)
    {
        return $this->setParameter('NotifyURL', $value);
    }

    /**
     * @return ?string
     */
    public function getNotifyURL()
    {
        return $this->getParameter('NotifyURL');
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionId', 'PeriodNo', 'AlterAmt');

        return array_filter([
            'RespondType' => $this->getRespondType(),
            'Version' => $this->getVersion() ?: '1.2',
            'TimeStamp' => $this->getTimeStamp(),
            'MerOrderNo' => $this->getTransactionId(),
            'PeriodNo' => $this->getPeriodNo(),
            'AlterAmt' => $this->getAlterAmt(),
            'PeriodType' => $this->getPeriodType(),
            'PeriodPoint' => $this->getPeriodPoint(),
            'PeriodTimes' => $this->getPeriodTimes(),
            'Extday' => $this->getExtday(),
            'NotifyURL' => $this->getNotifyURL(),
        ], static function ($value) {
            return $value !== null && $value !== '';
        });
    }

    public function sendData($data)
    {
        $response = $this->sendEncryptedRequest(
            $this->httpClient,
            $this->getEndpoint(),
            $this->getMerchantID(),
            $data
        );

        return $this->response = new AlterPeriodAmtResponse($this, $this->decodePeriodResponse($response));
    }
}
