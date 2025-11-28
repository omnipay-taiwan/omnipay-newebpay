<?php

namespace Omnipay\NewebPay\Traits;

trait HasEWallet
{
    /**
     * 玉山 Wallet.
     * 1.設定是否啟用玉山 Wallet 支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return self
     */
    public function setESUNWALLET($value)
    {
        return $this->setParameter('ESUNWALLET', $value);
    }

    /**
     * @return ?int
     */
    public function getESUNWALLET()
    {
        return $this->getParameter('ESUNWALLET');
    }

    /**
     * 台灣 Pay.
     * 1.設定是否啟用台灣 Pay 支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     * 2.當該筆訂單金額超過 49,999 元時，即使此參數設定為啟用，MPG 付款頁面仍不會顯示此支付方式選項
     *
     * @param  int  $value
     * @return self
     */
    public function setTAIWANPAY($value)
    {
        return $this->setParameter('TAIWANPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getTAIWANPAY()
    {
        return $this->getParameter('TAIWANPAY');
    }

    /**
     * TWQR 台灣Pay付款啟用.
     * 1.設定是否啟用 TWQR 台灣Pay付款支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return self
     */
    public function setTWQR($value)
    {
        return $this->setParameter('TWQR', $value);
    }

    /**
     * @return ?int
     */
    public function getTWQR()
    {
        return $this->getParameter('TWQR');
    }

    /**
     * TWQR 付款有效秒數.
     * 1.於交易有效時間內未完成交易，則視為交易失敗
     * 2.秒數下限為 60 秒，小於 60 秒以 60 秒計算
     * 3.秒數上限為 900 秒，大於 900 秒以 900 秒計算
     *
     * @param  int  $value
     * @return self
     */
    public function setTWQR_LifeTime($value)
    {
        return $this->setParameter('TWQR_LifeTime', $value);
    }

    /**
     * Alias for setTWQR_LifeTime (for Omnipay parameter mapping).
     *
     * @param  int  $value
     * @return self
     */
    public function setTwqrLifetime($value)
    {
        return $this->setTWQR_LifeTime($value);
    }

    /**
     * @return ?int
     */
    public function getTWQR_LifeTime()
    {
        return $this->getParameter('TWQR_LifeTime');
    }

    /**
     * 簡單付微信支付.
     * 1.設定是否啟用簡單付微信支付支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return self
     */
    public function setEZPWECHAT($value)
    {
        return $this->setParameter('EZPWECHAT', $value);
    }

    /**
     * @return ?int
     */
    public function getEZPWECHAT()
    {
        return $this->getParameter('EZPWECHAT');
    }

    /**
     * 簡單付支付寶.
     * 1.設定是否啟用簡單付支付寶支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return self
     */
    public function setEZPALIPAY($value)
    {
        return $this->setParameter('EZPALIPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getEZPALIPAY()
    {
        return $this->getParameter('EZPALIPAY');
    }

    /**
     * BitoPay 幣託電子錢包啟用.
     * 1.設定是否啟用 BitoPay 幣託電子錢包支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return self
     */
    public function setBITOPAY($value)
    {
        return $this->setParameter('BITOPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getBITOPAY()
    {
        return $this->getParameter('BITOPAY');
    }

    /**
     * 電子錢包顯示方式.
     * 1.設定電子錢包在 MPG 頁面上的顯示方式
     *   1 = 自訂串接（依商店自行帶入的電子錢包參數顯示）
     *   2 = 全部顯示（顯示商店有開啟的所有電子錢包）
     * 2.若未帶此參數，則預設值為 1
     *
     * @param  int  $value
     * @return self
     */
    public function setWalletDisplayMode($value)
    {
        return $this->setParameter('WalletDisplayMode', $value);
    }

    /**
     * @return ?int
     */
    public function getWalletDisplayMode()
    {
        return $this->getParameter('WalletDisplayMode');
    }
}
