<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Encryptor;
use Omnipay\NewebPay\Message\GetPaymentInfoRequest;
use Omnipay\Tests\TestCase;

class GetPaymentInfoRequestTest extends TestCase
{
    private $options = [
        'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
        'HashIV' => 'C6AcmfqJILwgnhIP',
        'MerchantID' => 'MS127874575',
        'testMode' => true,
    ];

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testGetATM()
    {
        $timestamp = 1695795668;
        $this->getHttpRequest()->request->add($this->givenPostData([
            'Status' => 'SUCCESS',
            'Message' => '取號成功',
            'MerchantID' => $this->options['MerchantID'],
            'Amt' => '1000',
            'TradeNo' => '00000000000000001',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'PaymentType' => 'VACC',
            'RespondType' => 'JSON',
            'ExpireDate' => '2020-01-01',
            'ExpireTime' => '23:59:59',
            'BankCode' => '050',
            'CodeNo' => '2592600213085401',
        ]));
        $request = new GetPaymentInfoRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($this->options);
        $response = $request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEquals('取號成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_'.$timestamp, $response->getTransactionId());
        self::assertEquals('00000000000000001', $response->getTransactionReference());
    }

    public function testGetBarcode()
    {
        $timestamp = 1695795668;
        $this->getHttpRequest()->request->add($this->givenPostData([
            'Status' => 'SUCCESS',
            'Message' => '條碼取號成功',
            'MerchantID' => $this->options['MerchantID'],
            'Amt' => '1000',
            'TradeNo' => '00000000000000001',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'PaymentType' => 'BARCODE',
            'RespondType' => 'JSON',
            'ExpireDate' => '2020-01-01',
            'ExpireTime' => '23:59:59',
            'Barcode_1' => 'TEST1',
            'Barcode_2' => 'TEST2',
            'Barcode_3' => 'TEST3',
        ]));
        $request = new GetPaymentInfoRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($this->options);
        $response = $request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEquals('條碼取號成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_'.$timestamp, $response->getTransactionId());
        self::assertEquals('00000000000000001', $response->getTransactionReference());
    }

    public function testGetCVS()
    {
        $timestamp = 1695795668;
        $this->getHttpRequest()->request->add($this->givenPostData([
            'Status' => 'SUCCESS',
            'Message' => '代碼取號成功',
            'MerchantID' => $this->options['MerchantID'],
            'Amt' => '1000',
            'TradeNo' => '00000000000000001',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'PaymentType' => 'CVS',
            'RespondType' => 'JSON',
            'ExpireDate' => '2020-01-01',
            'ExpireTime' => '23:59:59',
            'CodeNo' => 'CVS12345678900',
        ]));
        $request = new GetPaymentInfoRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($this->options);
        $response = $request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEquals('代碼取號成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_'.$timestamp, $response->getTransactionId());
        self::assertEquals('00000000000000001', $response->getTransactionReference());
    }

    public function testGetCVSCOM()
    {
        $timestamp = 1695795668;
        $this->getHttpRequest()->request->add($this->givenPostData([
            'Status' => 'SUCCESS',
            'Message' => '條碼取號成功',
            'MerchantID' => $this->options['MerchantID'],
            'Amt' => '1000',
            'TradeNo' => '00000000000000001',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'PaymentType' => 'CVSCOM',
            'RespondType' => 'JSON',
            'StoreCode' => '019666',
            'StoreName' => '全家台灣大道店',
            'TradeType' => '1',
            'StoreType' => '全家',
            'CVSCOMName' => 'Lucas Yang',
            'CVSCOMPhone' => '0900111222',
            'StoreAddr' => '台中市中區台灣大道一段531號',
            'LgsType' => 'C2C',
            'LgsNo' => '-',
        ]));
        $request = new GetPaymentInfoRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize($this->options);
        $response = $request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEquals('條碼取號成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_'.$timestamp, $response->getTransactionId());
        self::assertEquals('00000000000000001', $response->getTransactionReference());
    }

    private function givenPostData(array $data): array
    {
        $encryptor = new Encryptor($this->options['HashKey'], $this->options['HashIV']);
        $tradeInfo = $encryptor->encrypt($data);

        return [
            'Status' => $data['Status'],
            'MerchantID' => $data['MerchantID'],
            'Version' => '2.0',
            'TradeInfo' => $tradeInfo,
            'TradeSha' => $encryptor->tradeSha($tradeInfo),
        ];
    }
}
