<?php

namespace Omnipay\NewebPay\Traits;

trait HasATM
{
    /**
     * WEBATM 啟用.
     * 1.設定是否啟用 WEBATM 支付方式
     *   1=啟用
     *   0 或者未有此參數，即代表不開啟
     * 2.當訂單金額超過 49,999 元或消費者使用手機裝置付款時，即使啟用 WebATM，MPG 支付頁仍不會顯示此支付方式
     * 3.若只有啟用 WebATM，消費者於 MPG 支付頁無法點選此支付方式
     *
     * @param  int  $value
     * @return self
     */
    public function setWEBATM($value)
    {
        return $this->setParameter('WEBATM', $value);
    }

    /**
     * @return ?int
     */
    public function getWEBATM()
    {
        return $this->getParameter('WEBATM');
    }

    /**
     * ATM 轉帳啟用.
     * 1.設定是否啟用 ATM 轉帳支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     * 2.當該筆訂單金額超過 49,999 元時，即使此參數設定為啟用，MPG 付款頁面仍不會顯示此支付方式選項
     *
     * @param  int  $value
     * @return self
     */
    public function setVACC($value)
    {
        return $this->setParameter('VACC', $value);
    }

    /**
     * @return ?int
     */
    public function getVACC()
    {
        return $this->getParameter('VACC');
    }

    /**
     * 金融機構.
     * 1.指定銀行對應參數值如下：
     *   BOT=台灣銀行
     *   HNCB=華南銀行
     *   KGI=凱基銀行
     * 2. 若未帶值，則預設值為支援所有指定銀行
     * 3.此為[WEBATM]與[ATM 轉帳]可供付款人選擇轉帳銀行，將顯示於 MPG 頁上。為共用此參數值，無法個別分開指定
     * 4.可指定 1 個以上的銀行，若指定 1 個以上，則用半形［,］分隔，例如：BOT, HNCB
     *
     * @param  string  $value
     * @return self
     */
    public function setBankType($value)
    {
        return $this->setParameter('BankType', $value);
    }

    /**
     * @return ?string
     */
    public function getBankType()
    {
        return $this->getParameter('BankType');
    }

    /**
     * 智慧ATM2.0 交易來源.
     * 1.此欄位值為「1」表示來源為帳戶支付APP
     * 2.若無此參數或參數值不為「1」，則視為一般交易
     *
     * @param  int  $value
     * @return self
     */
    public function setSourceType($value)
    {
        return $this->setParameter('SourceType', $value);
    }

    /**
     * @return ?int
     */
    public function getSourceType()
    {
        return $this->getParameter('SourceType');
    }

    /**
     * 智慧ATM2.0 來源銀行代碼.
     * 1.當 SourceType=1 時，此欄位必填
     * 2.銀行代碼為 3 碼數字
     *
     * @param  string  $value
     * @return self
     */
    public function setSourceBankId($value)
    {
        return $this->setParameter('SourceBankId', $value);
    }

    /**
     * @return ?string
     */
    public function getSourceBankId()
    {
        return $this->getParameter('SourceBankId');
    }

    /**
     * 智慧ATM2.0 來源銀行帳號.
     * 1.當 SourceType=1 時，此欄位必填
     * 2.銀行帳號長度依各銀行規定
     *
     * @param  string  $value
     * @return self
     */
    public function setSourceAccountNo($value)
    {
        return $this->setParameter('SourceAccountNo', $value);
    }

    /**
     * @return ?string
     */
    public function getSourceAccountNo()
    {
        return $this->getParameter('SourceAccountNo');
    }
}
