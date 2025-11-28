<?php

namespace Omnipay\NewebPay\Traits;

trait HasPeriod
{
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
     * 交易模式.
     * 1=立即執行十元授權
     * 2=立即執行委託金額授權
     * 3=不檢查信用卡資訊，不授權
     *
     * @param  int  $value
     * @return self
     */
    public function setPeriodStartType($value)
    {
        return $this->setParameter('PeriodStartType', $value);
    }

    /**
     * @return ?int
     */
    public function getPeriodStartType()
    {
        return $this->getParameter('PeriodStartType');
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
     * 首期授權日.
     * 格式為『YYYY/mm/dd』
     *
     * @param  string  $value
     * @return self
     */
    public function setPeriodFirstdate($value)
    {
        return $this->setParameter('PeriodFirstdate', $value);
    }

    /**
     * @return ?string
     */
    public function getPeriodFirstdate()
    {
        return $this->getParameter('PeriodFirstdate');
    }

    /**
     * 付款人電子信箱.
     * (定期定額使用 PayerEmail，一般交易使用 Email)
     *
     * @param  string  $value
     * @return self
     */
    public function setPayerEmail($value)
    {
        return $this->setParameter('PayerEmail', $value);
    }

    /**
     * @return ?string
     */
    public function getPayerEmail()
    {
        return $this->getParameter('PayerEmail');
    }

    /**
     * 是否開啟付款人資訊.
     * Y=是
     * N=否
     *
     * @param  string  $value
     * @return self
     */
    public function setPaymentInfo($value)
    {
        return $this->setParameter('PaymentInfo', $value);
    }

    /**
     * @return ?string
     */
    public function getPaymentInfo()
    {
        return $this->getParameter('PaymentInfo');
    }

    /**
     * 是否開啟收件人資訊.
     * Y=是
     * N=否
     *
     * @param  string  $value
     * @return self
     */
    public function setOrderInfo($value)
    {
        return $this->setParameter('OrderInfo', $value);
    }

    /**
     * @return ?string
     */
    public function getOrderInfo()
    {
        return $this->getParameter('OrderInfo');
    }

    /**
     * 備註說明.
     *
     * @param  string  $value
     * @return self
     */
    public function setPeriodMemo($value)
    {
        return $this->setParameter('PeriodMemo', $value);
    }

    /**
     * @return ?string
     */
    public function getPeriodMemo()
    {
        return $this->getParameter('PeriodMemo');
    }

    /**
     * 商店訂單編號 (定期定額使用).
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
     * 產品名稱 (定期定額使用).
     *
     * @param  string  $value
     * @return self
     */
    public function setProdDesc($value)
    {
        return $this->setDescription($value);
    }

    /**
     * @return ?string
     */
    public function getProdDesc()
    {
        return $this->getDescription();
    }

    /**
     * 委託金額 (定期定額使用).
     *
     * @param  int  $value
     * @return self
     */
    public function setPeriodAmt($value)
    {
        return $this->setAmount($value);
    }

    /**
     * @return ?int
     */
    public function getPeriodAmt()
    {
        return $this->getAmount() !== null ? (int) $this->getAmount() : null;
    }

    /**
     * 返回商店網址 (定期定額的 BackURL).
     *
     * @param  string  $value
     * @return self
     */
    public function setBackURL($value)
    {
        return $this->setCancelUrl($value);
    }

    /**
     * @return ?string
     */
    public function getBackURL()
    {
        return $this->getCancelUrl();
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
     * 解密定期定額 API 回應.
     * Response 中的 period 欄位包含加密的 JSON 資料
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return array
     */
    protected function decodePeriodResponse($response)
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
