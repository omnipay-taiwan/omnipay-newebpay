<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasDefaults;

class AlterPeriodStatusRequest extends AbstractRequest
{
    use HasDefaults;

    public function getEndpoint()
    {
        return parent::getEndpoint().'MPG/period/AlterStatus';
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
        $response = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'MerchantID_' => $this->getMerchantID(),
            'PostData_' => $this->encrypt($data),
        ]));

        return $this->response = new AlterPeriodStatusResponse($this, $this->decodeResponse($response));
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
