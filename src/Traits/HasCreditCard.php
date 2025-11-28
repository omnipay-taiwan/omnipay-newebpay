<?php

namespace Omnipay\NewebPay\Traits;

trait HasCreditCard
{
    /**
     * 信用卡一次付清啟用.
     * 1.設定是否啟用信用卡一次付清支付方式
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setCREDIT($value)
    {
        return $this->setParameter('CREDIT', $value);
    }

    /**
     * @return ?int
     */
    public function getCREDIT()
    {
        return $this->getParameter('CREDIT');
    }

    /**
     * 信用卡分期付款啟用.
     * 1.此欄位值=1 時，即代表開啟所有分期期別，且不可帶入其他期別參數
     * 2.此欄位值為下列數值時，即代表開啟該分期期別
     *   3=分 3 期功能
     *   6=分 6 期功能
     *   8=分 8 期功能 (僅限閘道商店)
     *   12=分 12 期功能
     *   18=分 18 期功能
     *   24=分 24 期功能
     *   30=分 30 期功能
     * 3.同時開啟多期別時，將此參數用"，"(半形)分隔，例如：3,6,12，代表開啟 分 3、6、12期的功能
     * 4.此欄位值=０或無值時，即代表不開啟分期
     *
     * @param  string  $value
     * @return self
     */
    public function setInstFlag($value)
    {
        return $this->setParameter('InstFlag', $value);
    }

    /**
     * @return ?string
     */
    public function getInstFlag()
    {
        return $this->getParameter('InstFlag');
    }

    /**
     * 信用卡紅利啟用.
     * 1.設定是否啟用信用卡紅利支付方式
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setCreditRed($value)
    {
        return $this->setParameter('CreditRed', $value);
    }

    /**
     * @return ?int
     */
    public function getCreditRed()
    {
        return $this->getParameter('CreditRed');
    }

    /**
     * 信用卡銀聯卡啟用.
     * 1.設定是否啟用銀聯卡支付方式
     *  1=啟用
     *  0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setUNIONPAY($value)
    {
        return $this->setParameter('UNIONPAY', $value);
    }

    /**
     * @return int
     */
    public function getUNIONPAY()
    {
        return $this->getParameter('UNIONPAY');
    }

    /**
     * 信用卡美國運通卡啟用
     * 1.設定是否啟用美國運通卡支付方式。
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return self
     */
    public function setCREDITAE($value)
    {
        return $this->setParameter('CREDITAE', $value);
    }

    /**
     * @return ?int
     */
    public function getCREDITAE()
    {
        return $this->getParameter('CREDITAE');
    }

    /**
     * 付款人綁定資料.
     * 1.可對應付款人之資料，用於綁定付款人與信用卡卡號時使用，例：會員編號、Email。
     * 2.限英、數字，「.」、「_」、「@」、「-」格式。
     *
     * @param  string  $value
     * @return self
     */
    public function setTokenTerm($value)
    {
        return $this->setParameter('TokenTerm', $value);
    }

    /**
     * @return ?string
     */
    public function getTokenTerm()
    {
        return $this->getParameter('TokenTerm');
    }

    /**
     * 指定付款人信用卡快速結帳必填欄位.
     * 可指定付款人需填寫的信用卡資訊，不同的參數值對應填寫不同的資訊，參數說明如下：
     *   1 = 必填信用卡到期日與背面末三碼
     *   2 = 必填信用卡到期日
     *   3 = 必填背面末三碼
     *   4 = 不需填寫任何資訊
     * 未有此參數或帶入其他無效參數，系統預設為參數 1。
     *
     * @param  int  $value
     * @return self
     */
    public function setTokenTermDemand($value)
    {
        return $this->setParameter('TokenTermDemand', $value);
    }

    /**
     * @return ?int
     */
    public function getTokenTermDemand()
    {
        return $this->getParameter('TokenTermDemand');
    }

    /**
     * 信用卡國民旅遊卡交易註記.
     * 1.註記此筆交易是否為國民旅遊卡交易。
     * 1=國民旅遊卡交易
     *   0 或者未有此參數=非國民旅遊卡交易
     *
     * @param  int  $value
     * @return self
     */
    public function setNTCB($value)
    {
        return $this->setParameter('NTCB', $value);
    }

    /**
     * @return int
     */
    public function getNTCB()
    {
        return $this->getParameter('NTCB');
    }

    /**
     * 旅遊地區代號.
     * 旅遊地區代號，請參考旅遊地區代號對照表。
     * 例：如旅遊地區為台北市則此欄位為 001。
     *
     * @param  string  $value
     * @return self
     */
    public function setNTCBLocate($value)
    {
        return $this->setParameter('NTCBLocate', $value);
    }

    /**
     * @return ?string
     */
    public function getNTCBLocate()
    {
        return $this->getParameter('NTCBLocate');
    }

    /**
     * 國民旅遊卡起始日期.
     * 格式為：YYYY-MM-DD 例：2015-01-01
     *
     * @param  string  $value
     * @return self
     */
    public function setNTCBStartDate($value)
    {
        return $this->setParameter('NTCBStartDate', $value);
    }

    /**
     * @return ?string
     */
    public function getNTCBStartDate()
    {
        return $this->getParameter('NTCBStartDate');
    }

    /**
     * 國民旅遊卡結束日期.
     * 格式為：YYYY-MM-DD 例：2015-01-01
     *
     * @param  string  $value
     * @return self
     */
    public function setNTCBEndDate($value)
    {
        return $this->setParameter('NTCBEndDate', $value);
    }

    /**
     * @return ?string
     */
    public function getNTCBEndDate()
    {
        return $this->getParameter('NTCBEndDate');
    }
}
