<?php

namespace Omnipay\NewebPay\Traits;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Encryptor;

trait HasDefaults
{
    /**
     * 藍新金流商店代號
     *
     * @param  string  $value
     * @return self
     */
    public function setHashKey(string $value)
    {
        return $this->setParameter('HashKey', $value);
    }

    public function getHashKey()
    {
        return $this->getParameter('HashKey');
    }

    public function setHashIV($value)
    {
        return $this->setParameter('HashIV', $value);
    }

    public function getHashIV()
    {
        return $this->getParameter('HashIV');
    }

    /**
     * 商店代號.
     * 藍新金流商店代號。
     *  1.若要用複合式商店代號(MS5 開頭)，則[Gateway]參數為必填。
     *  2.若沒有帶[Gateway]，則查詢一般商店代號。
     *
     * @param  string  $value
     * @return self
     */
    public function setMerchantID($value)
    {
        return $this->setParameter('MerchantID', $value);
    }

    public function getMerchantID()
    {
        return $this->getParameter('MerchantID');
    }

    /**
     * 串接程式版本.
     *
     * @param  string  $value
     * @return self
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
        return $this->getParameter('Version');
    }

    /**
     * 回傳格式.
     * JSON 或是 String
     *
     * @param  string  $value
     * @return self
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
     * @return self
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
     * 商店訂單編號.
     * 1.商店自訂訂單編號，限英、數字、”_ ”格式 例：201406010001
     * 2.長度限制為 30 字元
     * 3.同一商店中此編號不可重覆
     *
     * @param  string  $value
     * @return self
     */
    public function setMerchantOrderNo($value)
    {
        return $this->setTransactionId($value);
    }

    /**
     * @return string
     */
    public function getMerchantOrderNo()
    {
        return $this->getTransactionId();
    }

    /**
     * 訂單金額.
     * 1.純數字不含符號，例：1000
     * 2.幣別：新台幣
     *
     * @param  int  $value
     * @return self
     */
    public function setAmt($value)
    {
        return $this->setAmount($value);
    }

    /**
     * @return int
     * @throws InvalidRequestException
     */
    public function getAmt()
    {
        return (int) $this->getAmount();
    }

    public function encrypt(array $data)
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());

        return $encryptor->encrypt($data);
    }

    public function decrypt(string $plainText)
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());
        $result = $encryptor->decrypt($plainText);

        $data = json_decode($result, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }

        $data = [];
        parse_str($result, $data);

        return $data;
    }

    public function tradeSha($plainText)
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());

        return $encryptor->tradeSha($plainText);
    }

    public function checkValue($data)
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());

        return $encryptor->checkValue($data);
    }

    public function checkCode($data)
    {
        $encryptor = new Encryptor($this->getHashKey(), $this->getHashIv());

        return $encryptor->checkCode($data);
    }
}
