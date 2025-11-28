<?php

namespace Omnipay\NewebPay\Traits;

trait HasMobilePay
{
    /**
     * Google Pay啟用.
     * 1.設定是否啟用 Google Pay 支付方式
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setANDROIDPAY($value)
    {
        return $this->setParameter('ANDROIDPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getANDROIDPAY()
    {
        return $this->getParameter('ANDROIDPAY');
    }

    /**
     * Samsung Pay啟用.
     * 1.設定是否啟用 Samsung Pay 支付方式
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setSAMSUNGPAY($value)
    {
        return $this->setParameter('SAMSUNGPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getSAMSUNGPAY()
    {
        return $this->getParameter('SAMSUNGPAY');
    }

    /**
     * LINE Pay啟用.
     * 1.設定是否啟用 LINE Pay 支付方式
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setLINEPAY($value)
    {
        return $this->setParameter('LINEPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getLINEPAY()
    {
        return $this->getParameter('LINEPAY');
    }

    /**
     * LINE Pay產品圖檔連結網址
     * 1. LINE Pay[啟用]時，會員(商店)視需求傳遞此參數
     * 2.此連結的圖檔將顯示於 LINE Pay 付款前的產品圖片區，若無產品圖檔連結網址，會使用藍新系統預設圖檔。
     * 3. 圖片建議使用 84*84 像素(若大於或小於該尺寸有可能造成破圖或變形)
     * 4. 檔案類型僅支援 jpg 或 png
     *
     * @param  string  $value
     * @return self
     */
    public function setImageUrl($value)
    {
        return $this->setParameter('ImageUrl', $value);
    }

    /**
     * @return ?string
     */
    public function getImageUrl()
    {
        return $this->getParameter('ImageUrl');
    }

    /**
     * Apple Pay 啟用.
     * 1.設定是否啟用 Apple Pay 支付方式
     *   1 = 啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setAPPLEPAY($value)
    {
        return $this->setParameter('APPLEPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getAPPLEPAY()
    {
        return $this->getParameter('APPLEPAY');
    }
}
