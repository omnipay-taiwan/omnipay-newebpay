<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\NewebPay\Traits\HasDefaults;

class PurchaseRequest extends AbstractRequest
{
    use HasDefaults;

    /**
     * 回傳格式.
     * JSON 或是 String
     *
     * @param  string  $value
     * @return static
     */
    public function setRespondType($value)
    {
        return $this->setParameter('RespondType', $value);
    }

    /**
     * @return string
     */
    public function getRespondType(): string
    {
        return $this->getParameter('RespondType') ?: "JSON";
    }

    /**
     * 時間戳記.
     * 自從 Unix 纪元（格林威治時間 1970 年 1 月 1 日 00:00:00）到當前時間的秒數，
     * 若以 PHP 程式語言為例，即為呼叫 time()函式所回傳值
     * 例：2014-05-15 15:00:00 這個時間的時間戳記為 1400137200
     * * 須確實帶入自 Unix 紀元到當前時間的秒數以避免交易失敗。(容許誤差值 120 秒)
     *
     * @param  int  $value
     * @return static
     */
    public function setTimeStamp($value)
    {
        return $this->setParameter('TimeStamp', $value);
    }

    /**
     * @return int
     */
    public function getTimeStamp()
    {
        return $this->getParameter('TimeStamp') ?: time();
    }

    /**
     * 串接程式版本.
     *
     * @param  string  $value
     * @return static
     */
    public function setVersion($value)
    {
        return $this->setParameter('Version', $value);
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->getParameter('Version') ?: '2.0';
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
     * @return static
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
     * 1.商店自訂訂單編號，限英、數字、”_ ”格式 例：201406010001
     * 2.長度限制為 30 字元
     * 3.同一商店中此編號不可重覆
     *
     * @param  string  $value
     * @return static
     */
    public function setMerchantOrderNo($value)
    {
        return $this->setParameter('MerchantOrderNo', $value);
    }

    /**
     * @return string
     */
    public function getMerchantOrderNo()
    {
        return $this->getParameter('MerchantOrderNo');
    }

    /**
     * 訂單金額.
     * 1.純數字不含符號，例：1000
     * 2.幣別：新台幣
     *
     * @param  int  $value
     * @return static
     */
    public function setAmt($value)
    {
        return $this->setParameter('Amt', $value);
    }

    /**
     * @return int
     */
    public function getAmt()
    {
        return $this->getParameter('Amt');
    }

    /**
     * 商品資訊.
     * 1.限制長度為 50 字元
     * 2.編碼為 Utf-8 格式
     * 3.請勿使用斷行符號、單引號等特殊符號，避免無法顯示完整付款頁面
     * 4.若使用特殊符號，系統將自動過濾
     *
     * @param  string  $value
     * @return PurchaseRequest
     */
    public function setItemDesc($value)
    {
        return $this->setParameter('ItemDesc', $value);
    }

    /**
     * @return string
     */
    public function getItemDesc()
    {
        return $this->getParameter('ItemDesc');
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
     * @return static
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
     * 繳費有效期限.
     * 1.僅適用於非即時支付
     * 2.格式為 date('Ymd') ，例：20140620
     * 3.此參數若為空值，系統預設為 7 天。自取號時間起算至第 7 天 23:59:59
     *   例：2014-06-23 14:35:51 完成取號，則繳費有效期限為 2014-06-29 23:59:59
     * 4.可接受最大值為 180 天
     *
     * @param  string  $value
     * @return static
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
     * 支付完成返回商店網址.
     * 1.交易完成後，以 Form Post 方式導回商店頁面
     * 2.若支付工具為玉山 Wallet、台灣 Pay 或本欄位為空值，
     *   於交易完成後，消費者將停留在藍新金流付款或取號結果頁面
     * 3.只接受 80 與 443 Port
     *
     * @param  string  $value
     * @return static
     */
    public function setReturnURL($value)
    {
        return $this->setParameter('ReturnURL', $value);
    }

    /**
     * @return ?string
     */
    public function getReturnURL()
    {
        return $this->getParameter('ReturnURL');
    }

    /**
     * 支付通知網址.
     * 1.以幕後方式回傳給商店相關支付結果資料；請參考 4.2.2 回應參數-支付完成
     * 2. 只接受 80 與 443 Port
     *
     * @param  string  $value
     * @return static
     */
    public function setNotifyURL($value)
    {
        return $this->setParameter('NotifyURL', $value);
    }

    /**
     * @return ?string
     */
    public function getNotifyURL()
    {
        return $this->getParameter('NotifyURL');
    }

    /**
     * 商店取號網址.
     * 1.系統取號後以 form post 方式將結果導回商店指定的網址，請參考 4.2.3 回應參數-取號完成
     * 2.此參數若為空值，則會顯示取號結果在藍新金流頁面
     *
     * @param  string  $value
     * @return static
     */
    public function setCustomerURL($value)
    {
        return $this->setParameter('CustomerURL', $value);
    }

    /**
     * @return ?string
     */
    public function getCustomerURL()
    {
        return $this->getParameter('CustomerURL');
    }

    /**
     * 返回商店網址.
     * 1.在藍新支付頁或藍新交易結果頁面上所呈現之返回鈕，我方將依據此參數之設定值進行設定，引導商店消費者依以此參數網址返回商店指定的頁面
     * 2.此參數若為空值時，則無返回鈕
     *
     * @param  string  $value
     * @return static
     */
    public function setClientBackURL($value)
    {
        return $this->setParameter('ClientBackURL', $value);
    }

    /**
     * @return ?string
     */
    public function getClientBackURL()
    {
        return $this->getParameter('ClientBackURL');
    }

    /**
     * 付款人電子信箱.
     * 於交易完成或付款完成時，通知付款人使用
     *
     * @param  string  $value
     * @return static
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
     * @return static
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
     * 藍新金流會員.
     *   1 = 須要登入藍新金流會員
     *   0 = 不須登入藍新金流會員
     *
     * @param  int  $value
     * @return static
     */
    public function setLoginType($value)
    {
        return $this->setParameter('LoginType', $value);
    }

    /**
     * @return ?int
     */
    public function getLoginType()
    {
        return $this->getParameter('LoginType');
    }

    /**
     * 商店備註.
     * 1.限制長度為 300 字
     * 2.若有提供此參數，將會於 MPG 頁面呈現商店備註內容
     *
     * @param  string  $value
     * @return static
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
     * 信用卡一次付清啟用.
     * 1.設定是否啟用信用卡一次付清支付方式
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return static
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
     * Google Pay啟用.
     * 1.設定是否啟用 Google Pay 支付方式
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return static
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
     * @return static
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
     * @return static
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
     * @return static
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
     * 信用卡分期付款啟用.
     * 1.此欄位值=1 時，即代表開啟所有分期期別，且不可帶入其他期別參數
     * 2.此欄位值為下列數值時，即代表開啟該分期期別
     *   3=分 3 期功能
     *   6=分 6 期功能
     *   12=分 12 期功能
     *   18=分 18 期功能
     *   24=分 24 期功能
     *   30=分 30 期功能
     * 3.同時開啟多期別時，將此參數用”，”(半形)分隔，例如：3,6,12，代表開啟 分 3、6、12期的功能
     * 4.此欄位值=０或無值時，即代表不開啟分期
     *
     * @param  string  $value
     * @return PurchaseRequest
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
     * @return static
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
     * @return static
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
     * @return static
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
     * ALIPAY 啟用
     * 1.設定是否啟用ALIPAY支付方式。
     *   1 =啟用
     *   0 或者未有此參數=不啟用
     *
     * @param  int  $value
     * @return static
     */
    public function setALIPAY($value)
    {
        return $this->setParameter('ALIPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getALIPAY()
    {
        return $this->getParameter('ALIPAY');
    }

    /**
     * WEBATM 啟用.
     * 1.設定是否啟用 WEBATM 支付方式
     *   1=啟用
     *   0 或者未有此參數，即代表不開啟
     * 2.當訂單金額超過 49,999 元或消費者使用手機裝置付款時，即使啟用 WebATM，MPG 支付頁仍不會顯示此支付方式
     * 3.若只有啟用 WebATM，消費者於 MPG 支付頁無法點選此支付方式
     *
     * @param  int  $value
     * @return static
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
     * @return static
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
     * 2. 若未帶值，則預設值為支援所有指定銀行
     * 3.此為[WEBATM]與[ATM 轉帳]可供付款人選擇轉帳銀行，將顯示於 MPG 頁上。為共用此參數值，無法個別分開指定
     * 4.可指定 1 個以上的銀行，若指定 1 個以上，則用半形［,］分隔，例如：BOT, HNCB
     *
     * @param  string  $value
     * @return static
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
     * 超商代碼繳費啟用.
     * 1.設定是否啟用超商代碼繳費支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     * 2.當該筆訂單金額小於 30 元或超過 2 萬元
     * 時，即使此參數設定為啟用，MPG 付款頁面
     * 仍不會顯示此支付方式選項
     *
     * @param  int  $value
     * @return static
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
     * @return PurchaseRequest
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
     * 玉山 Wallet.
     * 1.設定是否啟用玉山 Wallet 支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return static
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
     * @return static
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
     * Fula 付啦.
     * 1.此欄位值=1 時，即代表開啟所有分期期別，且不可帶入其他期別參數
     * 2.此欄位值為下列數值時，即代表開啟該分期期別
     *   3=分 3 期功能
     *   6=分 6 期功能
     *   9=分 9 期功能
     *   12=分 12 期功能
     *   18=分 18 期功能
     *   24=分 24 期功能
     *   30=分 30 期功能
     *   36=分 36 期功能
     * 3.同時開啟多期別時，將此參數用”，”(半形)分隔，例如：3,6,12，代表開啟 分 3、6、12 期的功能
     * 4.此欄位值=０或無值時，即代表不開啟
     * 5.當該筆訂單金額超過 49,999 元時，即使此參數設定為啟用，MPG 付款頁面仍不會顯示此支付方式選項
     *
     * @param  string  $value
     * @return static
     */
    public function setFULA($value)
    {
        return $this->setParameter('FULA', $value);
    }

    /**
     * @return string
     */
    public function getFULA()
    {
        return $this->getParameter('FULA');
    }

    /**
     * 物流啟用.
     * 1.使用前，須先登入藍新金流會員專區啟用物流並設定退貨門市與取貨人相關資訊
     *   1 = 啟用超商取貨不付款
     *   2 = 啟用超商取貨付款
     *   3 = 啟用超商取貨不付款及超商取貨付款
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return static
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
     * 簡單付電子錢包.
     * 1.設定是否啟用簡單付電子錢包支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return static
     */
    public function setEZPAY($value)
    {
        return $this->setParameter('EZPAY', $value);
    }

    /**
     * @return ?int
     */
    public function getEZPAY()
    {
        return $this->getParameter('EZPAY');
    }

    /**
     * 簡單付微信支付.
     * 1.設定是否啟用簡單付微信支付支付方式
     *   1 = 啟用
     *   0 或者未有此參數，即代表不開啟
     *
     * @param  int  $value
     * @return static
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
     * @return static
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
     * @return static
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

    public function getData(): array
    {
        return [
            'MerchantID' => $this->getMerchantID(),
            'TimeStamp' => $this->getTimeStamp(),
            'Version' => $this->getVersion() ?: '2.0',
            'RespondType' => $this->getRespondType(),
            'MerchantOrderNo' => $this->getMerchantOrderNo(),
//            'Amt' => (int) $this->getAmount(),
            'Amt' => $this->getAmt(),
            'VACC' => $this->getVACC(),
            'ALIPAY' => $this->getALIPAY(),
            'WEBATM' => $this->getWEBATM(),
            'CVS' => $this->getCVS(),
            'CREDIT' => $this->getCREDIT(),
            'NotifyURL' => $this->getNotifyURL(),
            'LoginType' => $this->getLoginType(),
            'InstFlag' => $this->getInstFlag(),
            'ItemDesc' => $this->getItemDesc(),
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
