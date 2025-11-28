<?php

namespace Omnipay\NewebPay\Traits;

trait HasLogistics
{
    /**
     * 物流啟用.
     * 1.使用前，須先登入藍新金流會員專區啟用物流並設定退貨門市與取貨人相關資訊
     *   1 = 啟用超商取貨不付款
     *   2 = 啟用超商取貨付款
     *   3 = 啟用超商取貨不付款及超商取貨付款
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return self
     */
    public function setCVSCOM($value)
    {
        return $this->setParameter('CVSCOM', $value);
    }

    /**
     * @return ?int
     */
    public function getCVSCOM()
    {
        return $this->getParameter('CVSCOM');
    }

    /**
     * 物流型態.
     * 1.帶入參數值說明：
     *   B2C＝大宗寄倉(目前僅支援 7-ELEVEN)
     *   C2C＝店到店(支援 7-ELEVEN、全家、萊爾富、OK mart)
     * 2.若商店未帶入此參數，則系統預設值說明如下：
     *   a.系統優先啟用［B2C 大宗寄倉］
     *   b.若商店設定中未啟用［B2C 大宗寄倉］則系統將會啟用［C2C 店到店］
     *   c.若商店設定中，［B2C 大宗寄倉］與［C2C 店到店］皆未啟用，則支付頁面中將不會出現物流選項
     *
     * @param  string  $value
     * @return self
     */
    public function setLgsType($value)
    {
        return $this->setParameter('LgsType', $value);
    }

    /**
     * @return ?string
     */
    public function getLgsType()
    {
        return $this->getParameter('LgsType');
    }
}
