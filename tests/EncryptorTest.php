<?php

namespace Omnipay\NewebPay\Tests;

use Omnipay\NewebPay\Encryptor;
use PHPUnit\Framework\TestCase;

class EncryptorTest extends TestCase
{
    private $key = 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn';

    private $iv = 'C6AcmfqJILwgnhIP';

    private $mid = 'MS127874575';

    private $timestamp = '1695795410';

    public function testEncrypt(): void
    {
        $encryptor = $this->givenEncryptor();

        $tradeInfo = $encryptor->encrypt([
            'MerchantID' => $this->mid,
            'RespondType' => 'String',
            'TimeStamp' => $this->timestamp,
            'Version' => '2.0',
            'MerchantOrderNo' => 'Vanespl_ec_'.$this->timestamp,
            'Amt' => '30',
            'ItemDesc' => 'test',
            'NotifyURL' => 'https://webhook.site/d4db5ad1-2278-466a-9d66-78585c0dbadb',
        ]);

        self::assertEquals($this->opensslEncrypt(), $tradeInfo);

        $hashed = strtoupper(hash('sha256', 'HashKey='.$this->key.'&'.$tradeInfo.'&HashIV='.$this->iv));
        self::assertEquals('84E4D9F96537E029F8450BE1E759080F9AF6995921B7F6F9AAFDDD2C36E7B287', $hashed);

        self::assertEquals($hashed, $encryptor->tradeSha($tradeInfo));
    }

    /**
     * @dataProvider decryptProvider
     */
    public function testDecrypt($data): void
    {
        $responseText = http_build_query($data);

        parse_str($responseText, $response);
        $encryptor = $this->givenEncryptor();
        $encryptor->decrypt($response['TradeInfo']);

        self::assertEquals(
            $this->toArray($this->opensslDecrypt($response['TradeInfo'])),
            $this->toArray($encryptor->decrypt($response['TradeInfo']))
        );
    }

    public static function decryptProvider()
    {
        return [
            [
                [
                    'Status' => 'SUCCESS',
                    'MerchantID' => 'MS127874575',
                    'Version' => '2.0',
                    'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449ebe644d2075fdfbc10150b1c40e7d24cb215febefdb85b16a5cde449f6b06c58a5510d31e8d34c95284d459ae4b52afc1509c2800976a5c0b99ef24cfd28a2dfc8004215a0c98a1d3c77707773c2f2132f9a9a4ce3475cb888c2ad372485971876f8e2fec0589927544c3463d30c785c2d3bd947c06c8c33cf43e131f57939e1f7e3b3d8c3f08a84f34ef1a67a08efe177f1e663ecc6bedc7f82640a1ced807b548633cfa72d060864271ec79854ee2f5a170aa902000e7c61d1269165de330fce7d10663d1668c711571776365bfdcd7ddc915dcb90d31a9f27af9b79a443ca8302e508b0dbaac817d44cfc44247ae613075dde4ac960f1bdff4173b915e4344bc4567bd32e86be7d796e6d9b9cf20476e4996e98ccc315f1ed03a34139f936797d971f2a3f90bc18f8a155a290bcbcf04f4277171c305bf554f5cba243154b30082748a81f2e5aa432ef9950cc9668cd4330ef7c37537a6dcb5e6ef01b4eca9705e4b097cf6913ee96e81d0389e5f775',
                    'TradeSha' => 'C80876AEBAC0036268C0E240E5BFF69C0470DE9606EEE083C5C8DD64FDB3347A',
                ],
            ],
            [
                [
                    // ATM String
                    'Status' => 'SUCCESS',
                    'MerchantID' => 'MS127874575',
                    'Version' => '2.0',
                    'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d3ddf4b9b2979c8cbc003b33773f4a42652778add4ad80b3ebe0e6c293f79577716d34853fd2ae158887d97582cd9a73450bdf5108ae9bec17f7a2c8eef557ce8118c32cace7e6e3568f9228323e4a71bbc740a393a7d4377f1a0b15d7cf1f14c229a3b2fdd05661ff91e0e1d0302e30093d3b40acd491d213627d9fb151c5c7e98ae87399d75a23dae24540124eab5e9c92ba41176c412febb7427199be5a6c417edbc334bffd406db146c3b3451b3727a37735267e4085b8bc2bd55b73c35428574674513b43e99a97124ea402271252774677c90c21203cc83e6f730c78e89525e0d31deb07ab1976a47ad4c5a3214710465c12110b66bf1025f9fab9a91e8',
                    'TradeSha' => 'A35958D8B0416CC18C65F10BE5C9494F4BD23B3ED8646F3E3F99E8AF9605F08A',
                ],
            ],
            [
                [
                    // ATM JSON
                    'Status' => 'SUCCESS',
                    'MerchantID' => 'MS127874575',
                    'Version' => '2.0',
                    'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d3ddf4b9b2979c8cbc003b33773f4a42652778add4ad80b3ebe0e6c293f7957777357e0788bfa4894bac1c6ce96f521ebb4a1921a6d19ea826bbbb1567b6b5f599ce2c41d826e095a4af2f1329b2c1d155ec4ebbceb5d3da3d345267bdc2ad990aca311a1a38fff9c4f32463e004cea3d31f9389d99ca3add6fbf2aa874ca373e150641f3900d06bca20700963db99d98e7ce3d3ee9d834ca47c3461dbf9dcce841456738d4c041e6d4332738530fe32849069a27e465425cc24b40b047865c8581fc152d27399b8872389e44408c0ceacf8e14e0964d57751e2ee0d136a58cd07e955ad0d6344005e2edf86c2d0f191d0267a9c1fc85e7c2be4db9f12021fd9a7bac0578cfdf1cdf0df8c813aaedf8c42d6ea02d4f985d3899990238c474f9fb703e07a0092c5e2667279ce128c9ecdb9526087a2683e9131bff85d64b7e035f65dd251c44cb4c2ee0390f0ba949744edd5c43ae45f48b08435212dae17e35836ac9e1ef545ad78bd46af45823c52beef68bb92e5a700414654effd0e897862c6475e7f7e1a765d0cff10a51f345320d',
                    'TradeSha' => '9C97BF5C1B444B46A0D781CBF0EE46716C9F1653BACABB610EA782D67D24C789',
                ],
            ],
            [
                [
                    // BarCode String
                    'Status' => 'SUCCESS',
                    'MerchantID' => 'MS127874575',
                    'Version' => '2.0',
                    'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343da4dfee38d59e4ffa21c95a031c2783c413319c0f877a1a90806c9d9f656bd78f8c7ecf2114386b0e166ddd6781f50ecc1c61dde9a8b3fecc2435552852b23ab677315ed7d35cda5fcb02a5403c853dd2ccbb74a07be26fa2d0ee3faf1b9db6037374bfd90456aea70489d57195299b4ce27063d04ea0b829aafb26f0d147b36ef2fc7ca652b068987c415fc3770964726af3572bbc6216f9a426b79fc77ad4dfee3315f238837786f6313caccf5e11eebff2527b274599d3ccba95dd201caf1a64c17e0fc582ab1991aa9d019efa8d49bb9951e3aceb3a97f9816062846568312db4e6e5ee1a4254f7dcd0f62fcc42df87b236705b1523c4ac0c52596afaa04f10602ed6c4a327834d6d278f46eaa01578d4de8d0fcb3fe9ec8c7714420589dc',
                    'TradeSha' => '65C98B826BB55C1C1D63236710ED5EECDA762B861345D207B07A43FB2FC5D268',
                ],
            ],
            [
                [
                    // BarCode JSON
                    'Status' => 'SUCCESS',
                    'MerchantID' => 'MS127874575',
                    'Version' => '2.0',
                    'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343da4dfee38d59e4ffa21c95a031c2783c413319c0f877a1a90806c9d9f656bd78f8c7ecf2114386b0e166ddd6781f50eccd68154a78a79081ecb178da3abf1b7aea7b9dea16ccce9bd22157ab86c5b91529b67f01bbc1d618bb58ed4d5c4077620254f161b829e3dfb159ad62e1c0fcdf352c4c46d8928923ef383fe2a33c03b20ff0b2fd26788d58771e22e23e69c584829e7937eb3010e98a48015af455023b605bcc0bef90fe1902270c3de8c1cc62975148a410b72e58b165808f4c1f6991389913307b5360a4c0f51bcbbf4fcef887b4a572d431c4be34d4d95c5039d2454a3f6b2acd450fd6a05daf4e32b701354fba83c65e53596bda208fcba49dcc4a9a0fbbe14638f94a311b9bce840138d296636125a920986e9719cd64d20513172fa02391ad44e2e557c47b1d26e7b31339925f83815ecc151b74748c96bd1cf341adc2b7875a85bf17531e816f09c90724256ef06e659df38b23b69ccf3e245f60b3b09b3acf2d17af0dd37e2fc0ddbc33ed5197181eaa3ec430c66192fbd6c9c85bf79a4acaed2bd36e738ed5dc919a9a455a84a94df73d2ca1feb12b6c912d96e2d544fa1d5c5b1f553ccce1e6e30e4',
                    'TradeSha' => 'BC7F4BBC45C57158A0C941DDC890AE1A0EA920055D24AE24B050B81B517809D4',
                ],
            ],
            [
                [
                    // CVS String
                    'Status' => 'SUCCESS',
                    'MerchantID' => 'MS127874575',
                    'Version' => '2.0',
                    'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d16c305574fe469f39bbfc2d1222d8acded5cab50a65749117cc6a72a9bc39d904efa18bd1710142e6ae872a1c5f9b34171b1e5aa94d94bc1c49bfe5b463dc87a481aa448ce63202425b09c29c4b1da0b9ec29b1d1d31410647905aebf4aa1101a090973d5d660fdda3d4888f33c4412f6df45e88b1890bb1f6410f5805dcf661db74d6a39d63882b8d57671472823bba747a4d4405fcbe8137ea98b84981576003713643250a5c0d6679be300cc559a4daad58a6adbbdd85773f14c621317ab4e2496f2c1c1a6fb2d6c9697b7afe6e74295acac87c468f40d8442818b7b749205387dbd142c0a5af2cd6111b5897d54358d1a668026ddb20b617e690027b80bc',
                    'TradeSha' => '505CC11A81ECBCE1ACF8FBCD6D39141DB3DADAACB2DC8240C90B4AF540E2A887',
                ],
            ],
            [
                [
                    // CVS JSON
                    'Status' => 'SUCCESS',
                    'MerchantID' => 'MS127874575',
                    'Version' => '2.0',
                    'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d16c305574fe469f39bbfc2d1222d8acded5cab50a65749117cc6a72a9bc39d904efa18bd1710142e6ae872a1c5f9b341e7223a46dea3c68e6aa0cecbfc8ba155bbe63d80b6e682bedb77f93dd8cebfd8cf43dde5ac345b2f37cd28df23c3a0ffc31c8ae83dc91991a85b0f91a4d8ba3d05cd999b3c0f85a0d2260e481a5a5c59ee2f8d0ea417bfc9480407e26720be225b88b2f8a5fdb024678f558da3ac0f1230237a88430f0cdac8a774e3954a1fb0ba97c6f6a0648d323fefcd001b9f1367ed123fd9fcc742e1d70f9baf799cb5469b2c4ee6225aca623016f4445b2b308f78519871f8d272eeaebdd4be0c2b961cc4e6b604e66e0b413446dc9308e3cdcb591f2e5a836f2cf511bc947e27b75aee049e2e70d31c1658a8cdd1984c6da0ed738d110aaab34b6592b1318623676ac8e10b416189d0834fa7e97a8ca324b2c7c3f32834969f61235e964e38771a61c4ba7b7811492831c02c7dfed54345aa314a7ae6ecfcc1e268ea79c0b4f10413d93c79a2619e0ee8c1d96de3006c42a840',
                    'TradeSha' => 'F28DFCFA8BC6F9B52A5463EF388BD9AF89C2303A94B469D13435264FB4152C39',
                ],
            ],
        ];
    }

    public function testCheckCode(): void
    {
        $encryptor = $this->givenEncryptor();

        self::assertEquals(
            '3BBBD18B6C2446E35764D587168997BF719FA72E00C569D2A55E45D67149EC71',
            $encryptor->checkCode([
                'MerchantID' => $this->mid,
                'Amt' => '10',
                'MerchantOrderNo' => 'MyCompanyOrder_'.$this->timestamp,
                'TradeNo' => '21120214151152468',
            ])
        );
    }

    public function testCheckValue(): void
    {
        $encryptor = $this->givenEncryptor();

        self::assertEquals(
            '59D3DEBBE4973B3AC2B102603DB97F0F9BE55F46B0F29FE84EF56FBCA3EBBC16',
            $encryptor->checkValue([
                'MerchantID' => $this->mid,
                'Amt' => '10',
                'MerchantOrderNo' => 'MyCompanyOrder_'.$this->timestamp,
            ])
        );
    }

    private function givenEncryptor(): Encryptor
    {
        return new Encryptor($this->key, $this->iv);
    }

    private function opensslEncrypt(): string
    {
        $data = http_build_query([
            'MerchantID' => $this->mid,
            'RespondType' => 'String',
            'TimeStamp' => $this->timestamp,
            'Version' => '2.0',
            'MerchantOrderNo' => 'Vanespl_ec_'.$this->timestamp,
            'Amt' => '30',
            'ItemDesc' => 'test',
            'NotifyURL' => 'https://webhook.site/d4db5ad1-2278-466a-9d66-78585c0dbadb',
        ]);

        $edata = bin2hex(openssl_encrypt($data, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv));

        self::assertEquals(
            'f79eac33c4f3245d58f17b544c5d38b09457a6d77e77bae6f10fcc7236fe153ccef1a80001c0746afc063a7570f80ad970d8a32c72332c9ec5547410188007876bdca2bafa52d07d31b6b183f2204d6e4feee6d245e286ab198cf95422ad5843c7696fc943cbb65979ad207607d4b5d97dac4a90ccd5e7a37adb7d7062e838be09d94e8c5dfa145c048e17feabe58c2e310792f0f50f5af32961ffb07ff6649ae1021ad558242551de5f09316e3182e198775e5d1ad5b66a70be290004de750fa85d86b0c2f087b40005d89e048be2ab6fd83f1c522494c093426a10a1f73fe4',
            $edata
        );

        return $edata;
    }

    private function opensslDecrypt($data): string
    {
        return $this->strippadding(openssl_decrypt(
            hex2bin($data),
            'AES-256-CBC',
            $this->key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $this->iv
        ));
    }

    private function strippadding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{".$slast.'}/', $string)) {
            return substr($string, 0, strlen($string) - $slast);
        }

        return false;
    }

    private function toArray($plainText)
    {
        $result = json_decode($plainText, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }

        $result = [];
        parse_str($plainText, $result);

        return $result;
    }
}
