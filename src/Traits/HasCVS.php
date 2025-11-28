<?php

namespace Omnipay\NewebPay\Traits;

trait HasCVS
{
    /**
     * 超商代碼繳費啟用.
     * 1.設定是否啟用超商代碼繳費支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     * 2.當該筆訂單金額小於 30 元或超過 2 萬元
     * 時，即使此參數設定為啟用，MPG 付款頁面
     * 仍不會顯示此支付方式選項
     *
     * @param  int  $value
     * @return self
     */
    public function setCVS($value)
    {
        return $this->setParameter('CVS', $value);
    }

    /**
     * @return ?int
     */
    public function getCVS()
    {
        return $this->getParameter('CVS');
    }

    /**
     * 超商條碼繳費啟用.
     * 1.設定是否啟用超商條碼繳費支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟。
     * 2.當該筆訂單金額小於 20 元或超過 4 萬元時，即使此參數設定為啟用，MPG 付款頁面仍不會顯示此支付方式選項
     *
     * @param  int  $value
     * @return self
     */
    public function setBARCODE($value)
    {
        return $this->setParameter('BARCODE', $value);
    }

    /**
     * @return ?int
     */
    public function getBARCODE()
    {
        return $this->getParameter('BARCODE');
    }

    /**
     * 繳費有效期限.
     * 1.僅適用於非即時支付
     * 2.格式為 date('Ymd') ，例：20140620
     * 3.此參數若為空值，系統預設為 7 天。自取號時間起算至第 7 天 23:59:59
     *   例：2014-06-23 14:35:51 完成取號，則繳費有效期限為 2014-06-29 23:59:59
     * 4.可接受最大值為 180 天
     *
     * @param  string  $value
     * @return self
     */
    public function setExpireDate($value)
    {
        return $this->setParameter('ExpireDate', $value);
    }

    /**
     * @return string
     */
    public function getExpireDate()
    {
        return $this->getParameter('ExpireDate');
    }

    /**
     * 繳費有效截止時間.
     * 1.僅適用於超商代碼繳費、超商條碼繳費
     * 2.格式為 YYYYMMDD，例：20240620
     * 3.此參數可與 ExpireDate 擇一使用，若同時帶入則以 ExpireTime 為主
     *
     * @param  string  $value
     * @return self
     */
    public function setExpireTime($value)
    {
        return $this->setParameter('ExpireTime', $value);
    }

    /**
     * @return ?string
     */
    public function getExpireTime()
    {
        return $this->getParameter('ExpireTime');
    }
}
