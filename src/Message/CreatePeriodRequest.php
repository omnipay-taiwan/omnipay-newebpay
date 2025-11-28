<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasDefaults;

class CreatePeriodRequest extends AbstractRequest
{
    use HasDefaults;

    public function getEndpoint()
    {
        return parent::getEndpoint().'MPG/period';
    }

    /**
     * 語系.
     * 1.設定委託頁面顯示語系
     *   英文版= en
     *   繁體中文版= zh-Tw
     * 2.當未提供此參數時，將預設為繁體中文版。
     *
     * @param  string  $value
     * @return self
     */
    public function setLangType($value)
    {
        return $this->setParameter('LangType', $value);
    }

    /**
     * @return ?string
     */
    public function getLangType()
    {
        return $this->getParameter('LangType');
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
     * 產品名稱.
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
     * 委託金額.
     *
     * @param  int  $value
     * @return self
     */
    public function setPeriodAmt($value)
    {
        return $this->setAmount($value);
    }

    /**
     * @return int
     *
     * @throws InvalidRequestException
     */
    public function getPeriodAmt()
    {
        return (int) $this->getAmount();
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
     * 付款人電子信箱是否開放修改.
     * 1=可修改
     * 0=不可修改
     *
     * @param  int  $value
     * @return self
     */
    public function setEmailModify($value)
    {
        return $this->setParameter('EmailModify', $value);
    }

    /**
     * @return ?int
     */
    public function getEmailModify()
    {
        return $this->getParameter('EmailModify');
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
     * 返回商店網址 (BackURL).
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
     * 信用卡銀聯卡啟用.
     * 1=啟用
     * 0 或者未有此參數=不啟
     *
     * @param  int  $value
     * @return self
     */
    public function setUNIONPAY($value)
    {
        return $this->setParameter('UNIONPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getUNIONPAY()
    {
        return $this->getParameter('UNIONPAY');
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionId', 'amount', 'description', 'PeriodType', 'PeriodPoint', 'PeriodStartType', 'PeriodTimes', 'PayerEmail');

        return array_filter([
            'MerchantID' => $this->getMerchantID(),
            'RespondType' => $this->getRespondType(),
            'TimeStamp' => $this->getTimeStamp(),
            'Version' => $this->getVersion() ?: '1.5',
            'LangType' => $this->getLangType(),
            'MerOrderNo' => $this->getTransactionId(),
            'ProdDesc' => $this->getDescription(),
            'PeriodAmt' => (int) $this->getAmount(),
            'PeriodType' => $this->getPeriodType(),
            'PeriodPoint' => $this->getPeriodPoint(),
            'PeriodStartType' => $this->getPeriodStartType(),
            'PeriodTimes' => $this->getPeriodTimes(),
            'PeriodFirstdate' => $this->getPeriodFirstdate(),
            'ReturnURL' => $this->getReturnUrl(),
            'PeriodMemo' => $this->getPeriodMemo(),
            'PayerEmail' => $this->getPayerEmail(),
            'EmailModify' => $this->getEmailModify(),
            'PaymentInfo' => $this->getPaymentInfo(),
            'OrderInfo' => $this->getOrderInfo(),
            'NotifyURL' => $this->getNotifyUrl(),
            'BackURL' => $this->getCancelUrl(),
            'UNIONPAY' => $this->getUNIONPAY(),
        ], static function ($value) {
            return $value !== null && $value !== '';
        });
    }

    public function sendData($data): CreatePeriodResponse
    {
        return $this->response = new CreatePeriodResponse($this, $data);
    }
}
