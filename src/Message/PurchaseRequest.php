<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Traits\HasATM;
use Omnipay\NewebPay\Traits\HasCreditCard;
use Omnipay\NewebPay\Traits\HasCVS;
use Omnipay\NewebPay\Traits\HasDefaults;
use Omnipay\NewebPay\Traits\HasEWallet;
use Omnipay\NewebPay\Traits\HasLogistics;
use Omnipay\NewebPay\Traits\HasMobilePay;
use Omnipay\NewebPay\Traits\HasOrderDetail;
use Omnipay\NewebPay\Traits\HasPaymentInfo;

class PurchaseRequest extends AbstractRequest
{
    use HasDefaults;
    use HasPaymentInfo;
    use HasCreditCard;
    use HasATM;
    use HasCVS;
    use HasMobilePay;
    use HasEWallet;
    use HasLogistics;
    use HasOrderDetail;

    public function getEndpoint()
    {
        return parent::getEndpoint().'MPG/mpg_gateway';
    }

    /**
     * 語系.
     * 1.設定 MPG 頁面顯示的文字語系：
     *     - 英文版 = en
     *     - 繁體中文版 = zh-tw
     *     -日文版 = jp
     * 2.當未提供此參數或此參數數值錯誤時，將預設為繁體中文版。
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
     * 商品資訊.
     * 1.限制長度為 50 字元
     * 2.編碼為 Utf-8 格式
     * 3.請勿使用斷行符號、單引號等特殊符號，避免無法顯示完整付款頁面
     * 4.若使用特殊符號，系統將自動過濾
     *
     * @param  string  $value
     * @return self
     */
    public function setItemDesc($value)
    {
        return $this->setDescription($value);
    }

    /**
     * @return string
     */
    public function getItemDesc()
    {
        return $this->getDescription();
    }

    /**
     * 交易有效時間.
     * 1.於交易有效時間內未完成交易，則視為交易失敗
     * 2.僅可接受數字格式
     * 3.秒數下限為 60 秒，小於 60 秒以 60 秒計算
     * 4.秒數上限為 900 秒，大於 900 秒以 900 秒計算
     * 5.若未帶此參數，或是為 0 時，會視作為不啟用交易限制秒數
     *
     * @param  int  $value
     * @return self
     */
    public function setTradeLimit($value)
    {
        return $this->setParameter('TradeLimit', $value);
    }

    /**
     * @return ?int
     */
    public function getTradeLimit()
    {
        return $this->getParameter('TradeLimit');
    }

    /**
     * 商店取號網址.
     * 1.系統取號後以 form post 方式將結果導回商店指定的網址，請參考 4.2.3 回應參數-取號完成
     * 2.此參數若為空值，則會顯示取號結果在藍新金流頁面
     *
     * @param  string  $value
     * @return self
     */
    public function setCustomerURL($value)
    {
        return $this->setPaymentInfoUrl($value);
    }

    /**
     * @return ?string
     */
    public function getCustomerURL()
    {
        return $this->getPaymentInfoUrl();
    }

    /**
     * 返回商店網址.
     * 1.在藍新支付頁或藍新交易結果頁面上所呈現之返回鈕，我方將依據此參數之設定值進行設定，引導商店消費者依以此參數網址返回商店指定的頁面
     * 2.此參數若為空值時，則無返回鈕
     *
     * @param  string  $value
     * @return self
     */
    public function setClientBackURL($value)
    {
        return $this->setCancelUrl($value);
    }

    /**
     * @return ?string
     */
    public function getClientBackURL()
    {
        return $this->getCancelUrl();
    }

    /**
     * 付款人電子信箱.
     * 於交易完成或付款完成時，通知付款人使用
     *
     * @param  string  $value
     * @return self
     */
    public function setEmail($value)
    {
        return $this->setParameter('Email', $value);
    }

    /**
     * @return ?string
     */
    public function getEmail()
    {
        return $this->getParameter('Email');
    }

    /**
     * 付款人電子信箱是否開放修改.
     * 1.設定於 MPG 頁面，付款人電子信箱欄位是否開放讓付款人修改
     *   1=可修改
     *   0=不可修改
     * 2.當未提供此參數時，將預設為可修改
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
     * 商店備註.
     * 1.限制長度為 300 字
     * 2.若有提供此參數，將會於 MPG 頁面呈現商店備註內容
     *
     * @param  string  $value
     * @return self
     */
    public function setOrderComment($value)
    {
        return $this->setParameter('OrderComment', $value);
    }

    /**
     * @return ?string
     */
    public function getOrderComment()
    {
        return $this->getParameter('OrderComment');
    }

    /**
     * @throws InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('transactionId', 'amount', 'description');

        return array_filter([
            // 基本參數
            'MerchantID' => $this->getMerchantID(),
            'RespondType' => $this->getRespondType(),
            'TimeStamp' => $this->getTimeStamp(),
            'Version' => $this->getVersion() ?: '2.3',
            'LangType' => $this->getLangType(),
            'MerchantOrderNo' => $this->getTransactionId(),
            'Amt' => (int) $this->getAmount(),
            'ItemDesc' => $this->getDescription(),
            'TradeLimit' => $this->getTradeLimit(),
            'ExpireDate' => $this->getExpireDate(),
            'ExpireTime' => $this->getExpireTime(),
            'ReturnURL' => $this->getReturnUrl(),
            'NotifyURL' => $this->getNotifyUrl(),
            'CustomerURL' => $this->getPaymentInfoUrl(),
            'ClientBackURL' => $this->getCancelUrl(),
            'Email' => $this->getEmail(),
            'EmailModify' => $this->getEmailModify(),
            'OrderComment' => $this->getOrderComment(),
            'OrderDetail' => $this->getOrderDetail(),

            // 信用卡相關 (HasCreditCard)
            'CREDIT' => $this->getCREDIT(),
            'InstFlag' => $this->getInstFlag(),
            'CreditRed' => $this->getCreditRed(),
            'UNIONPAY' => $this->getUNIONPAY(),
            'CREDITAE' => $this->getCREDITAE(),
            'TokenTerm' => $this->getTokenTerm(),
            'TokenTermDemand' => $this->getTokenTermDemand(),
            'NTCB' => $this->getNTCB(),
            'NTCBLocate' => $this->getNTCBLocate(),
            'NTCBStartDate' => $this->getNTCBStartDate(),
            'NTCBEndDate' => $this->getNTCBEndDate(),

            // ATM 相關 (HasATM)
            'WEBATM' => $this->getWEBATM(),
            'VACC' => $this->getVACC(),
            'BankType' => $this->getBankType(),
            'SourceType' => $this->getSourceType(),
            'SourceBankId' => $this->getSourceBankId(),
            'SourceAccountNo' => $this->getSourceAccountNo(),

            // 超商相關 (HasCVS)
            'CVS' => $this->getCVS(),
            'BARCODE' => $this->getBARCODE(),

            // 行動支付 (HasMobilePay)
            'ANDROIDPAY' => $this->getANDROIDPAY(),
            'SAMSUNGPAY' => $this->getSAMSUNGPAY(),
            'LINEPAY' => $this->getLINEPAY(),
            'ImageUrl' => $this->getImageUrl(),
            'APPLEPAY' => $this->getAPPLEPAY(),

            // 電子錢包 (HasEWallet)
            'ESUNWALLET' => $this->getESUNWALLET(),
            'TAIWANPAY' => $this->getTAIWANPAY(),
            'TWQR' => $this->getTWQR(),
            'TWQR_LifeTime' => $this->getTWQR_LifeTime(),
            'EZPWECHAT' => $this->getEZPWECHAT(),
            'EZPALIPAY' => $this->getEZPALIPAY(),
            'BITOPAY' => $this->getBITOPAY(),
            'WalletDisplayMode' => $this->getWalletDisplayMode(),

            // 物流 (HasLogistics)
            'CVSCOM' => $this->getCVSCOM(),
            'LgsType' => $this->getLgsType(),
        ], static function ($value) {
            return $value !== null && $value !== '';
        });
    }

    public function sendData($data): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
