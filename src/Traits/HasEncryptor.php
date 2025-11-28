<?php

namespace Omnipay\NewebPay\Traits;

use Omnipay\NewebPay\Encryptor;

trait HasEncryptor
{
    public function encrypt(array $data)
    {
        return $this->getEncryptor()->encrypt($data);
    }

    public function decrypt(string $plainText)
    {
        return $this->getEncryptor()->decrypt($plainText);
    }

    public function tradeSha($plainText)
    {
        return $this->getEncryptor()->tradeSha($plainText);
    }

    public function checkValue($data)
    {
        return $this->getEncryptor()->checkValue($data);
    }

    public function checkCode($data)
    {
        return $this->getEncryptor()->checkCode($data);
    }

    /**
     * 發送加密的 POST 請求.
     * 使用 MerchantID_ 和 PostData_ 格式
     *
     * @param  \Psr\Http\Message\ResponseInterface  $httpClient
     * @param  string  $endpoint
     * @param  string  $merchantId
     * @param  array  $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendEncryptedRequest($httpClient, $endpoint, $merchantId, array $data)
    {
        return $httpClient->request('POST', $endpoint, [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ], http_build_query([
            'MerchantID_' => $merchantId,
            'PostData_' => $this->encrypt($data),
        ]));
    }

    private function getEncryptor()
    {
        return new Encryptor($this->getHashKey(), $this->getHashIv());
    }
}
