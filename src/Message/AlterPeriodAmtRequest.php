<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasDefaults;

class AlterPeriodAmtRequest extends AbstractRequest
{
    use HasDefaults;

    public function getEndpoint()
    {
        return parent::getEndpoint().'MPG/period/AlterAmt';
    }

    /**
     * 商店訂單編號.
     *
     * @param  string  $value
     * @return self
     */
    public function setMerOrderNo($value)
    {
        return $this->setTransactionId($value);
    }

    /**
     * @return ?string
     */
    public function getMerOrderNo()
    {
        return $this->getTransactionId();
    }

    /**
     * 委託單號.
     *
     * @param  string  $value
     * @return self
     */
    public function setPeriodNo($value)
    {
        return $this->setParameter('PeriodNo', $value);
    }

    /**
     * @return ?string
     */
    public function getPeriodNo()
    {
        return $this->getParameter('PeriodNo');
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
     * 週期類別.
     * D=固定天期制
     * W=每週
     * M=每月
     * Y=每年
     *
     * @param  string  $value
     * @return self
     */
    public function setPeriodType($value)
    {
        return $this->setParameter('PeriodType', $value);
    }

    /**
     * @return ?string
     */
    public function getPeriodType()
    {
        return $this->getParameter('PeriodType');
    }

    /**
     * 交易週期授權時間.
     *
     * @param  string  $value
     * @return self
     */
    public function setPeriodPoint($value)
    {
        return $this->setParameter('PeriodPoint', $value);
    }

    /**
     * @return ?string
     */
    public function getPeriodPoint()
    {
        return $this->getParameter('PeriodPoint');
    }

    /**
     * 授權期數.
     *
     * @param  int  $value
     * @return self
     */
    public function setPeriodTimes($value)
    {
        return $this->setParameter('PeriodTimes', $value);
    }

    /**
     * @return ?int
     */
    public function getPeriodTimes()
    {
        return $this->getParameter('PeriodTimes');
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
        $response = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'MerchantID_' => $this->getMerchantID(),
            'PostData_' => $this->encrypt($data),
        ]));

        return $this->response = new AlterPeriodAmtResponse($this, $this->decodeResponse($response));
    }

    /**
     * @return array
     */
    protected function decodeResponse($response)
    {
        $responseText = trim((string) $response->getBody());
        parse_str($responseText, $result);

        if (isset($result['period'])) {
            $decrypted = $this->decrypt($result['period']);
            $data = json_decode($decrypted, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return array_merge(
                    ['Status' => $data['Status'], 'Message' => $data['Message']],
                    $data['Result'] ?? []
                );
            }
        }

        return $result;
    }
}
