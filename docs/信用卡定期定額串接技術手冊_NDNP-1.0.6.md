# 信用卡定期定額技術串接手冊

**藍新金流服務平台**

文件版本號：**NDNP-1.0.6**

*文件為藍新科技股份有限公司版權所有*

---

## 版本異動表

| 文件版本號 | 修改內容 | 日期 |
|-----------|--------|------|
| NDNP-1.0.0 | 初版 | 2022/10/18 |
| NDNP-1.0.1 | 修改三種定期定額驗證方式流程圖、修改參數 Version、修改參數 NotifyURL 中文參數名稱、修改程式範例 PeriodStartType 說明文字 | 2023/8/17 |
| NDNP-1.0.2 | 新增參數 NotifyURL 每期授權結果通知網址、修改參數 Extday 說明文字、新增建立委託[NPA-B05]之常見問題 | 2023/11/17 |
| NDNP-1.0.3 | 更新範例程式、新增參數 AuthBank 收單機構英文代碼：[LINEBank] | 2024/01/18 |
| NDNP-1.0.4 | 調整參數[Extday 信用卡到期日]參數格式為[年月] | 2024/05/15 |
| NDNP-1.0.5 | 新增參數 AuthBank 收單機構英文代碼：[SinoPac] | 2025/1/3 |
| NDNP-1.0.6 | 修改參數 LangType 說明、修改參數 PeriodFirstdate 說明、新增錯誤代碼：[TRA10043] | 2025/08/27 |

---

## 目錄

1. [簡介](#簡介)
2. [詞彙一覽表](#詞彙一覽表)
3. [交易流程](#交易流程)
4. [APIs](#apis)
   - 4.1 [AES256 加密](#41-aes256-加密)
   - 4.2 [AES256 解密](#42-aes256-解密)
   - 4.3 [建立委託 [NPA-B05]](#43-建立委託-npa-b05)
   - 4.4 [修改委託狀態 [NPA-B051]](#44-修改委託狀態-npa-b051)
   - 4.5 [修改委託內容 [NPA-B052]](#45-修改委託內容-npa-b052)
5. [錯誤代碼表](#5-錯誤代碼表)
6. [常見問題](#6-常見問題)

---

## 簡介

信用卡定期定額服務(Credit Card Periodic Payment)具有數位內容訂閱服務或會員月費、年費等服務類型的商店，提供便捷收款模式。商店可依需求建立定期定額委託，消費者只需完成一次結帳，藍新金流將於商店及消費者所約定之扣款週期，由系統自動執行信用卡扣款，解決商店每期收款需求。中間可以修改約定的金額或是週期，也可執行暫停、繼續、或終止扣款。

---

## 詞彙一覽表

| 名稱 | 說明 |
|------|------|
| 藍新金流 | 藍新金流提供金流服務給商店，並提供付款頁面給消費者進行付款 |
| 會員 | 使用藍新金流服務之會員，一個會員可以建立多間商店 |
| 商店 | 會員建立使用藍新金流服務之網路商店 |
| 消費者 | 向商店購買商品或服務之付款方，可能為商店之會員 |
| 委託 | 商店與消費者約定一筆『固定扣款的週期及金額』，稱之為一筆委託，是定期定額型交易的單位 |
| P1 | 定期定額委託之扣款首期 |
| Pn | 定期定額委託之後續扣款期數 |
| 會員專區 | 涵蓋藍新會員的各項商店、銷售紀錄、金流等功能，登入網址為 https://www.newebpay.com/ |
| 收單機構 | 提供商店信用卡交易清算服務之銀行 |

---

## 交易流程

本章節介紹信用卡定期定額之交易流程及相關規則。

一筆信用卡定期定額，起於建立委託 (NPA-B05)。建立成功後，系統會自動按時扣款，直到排定期數完成為止。過程中，亦可進行修改委託狀態 (NPA-B051) 及修改委託內容 (NPA-B052) 修改委託。

### API 交易流程

1. 消費者購買訂閱型服務或商品，並開始結帳
2. 商店向藍新金流執行建立委託 API (參照 4.3 建立委託[NPA-B05])
3. 藍新金流將商店網頁轉導至定期定額付款頁面，提供消費者進行付款流程
4. 消費者於定期定額支付頁輸入卡號等必要資訊
5. 首期授權方式依驗證情境共分為三種

#### 三種驗證方式

**• 立即十元驗證**
- 委託建立當下，立即執行一筆十元信用卡授權
- 十元授權成功：立即取消十元授權，並委託成立
- 十元授權失敗：委託不成立

**• 立即首期(P1)驗證**
- 委託建立當下，立即執行委託金額授權
- 授權成功：委託成立
- 授權失敗：委託不成立

**• 指定首期(P1)驗證**
- 委託自動成立，於指定首期授權日再執行授權
- 授權失敗：委託不成立

### 修改委託狀態

6. 商店向藍新金流發動修改委託狀態 API (參照 4.4 修改委託狀態[NPA-B051])，可將委託狀態由【啟用】改為【暫停】或【終止】
   - 委託狀態若為【終止】，則無法執行此 API
   - 如有首期授權日之委託，於授權日隔日前，僅能執行【終止】；授權日隔日起，方能執行【啟用】及【暫停】

### 修改委託內容

7. 商店向藍新金流發動修改委託內容 API (參照 4.5 修改委託內容[NPA-B052])，僅可調整委託之每期授權金額、執行週期、信用卡到期日、授權總期數及 NotifyURL
   - 委託狀態若為【終止】或【暫停】，則無法執行此 API
   - 如有首期授權日之委託，於授權日隔日前，無法執行此 API

> **備註：** 修改委託狀態(NPA-B051)及修改委託內容(NPA-B052)為申請制，需向藍新金流提出申請，待設定完成後，方能執行

---

## APIs

本章節將依序介紹發動交易前的加/解密方式，以及建立委託 (NPA-B05)、修改委託狀態 (NPA-B051) 及修改委託內容 (NPA-B052)，共計三支 API 介接規格，以及發動時機。

### 4.1 AES256 加密

#### Step 0: 準備基本要素

1. 於藍新金流平台已建立商店，並已啟用定期定額支付工具
2. 將商店之 API 串接金鑰(Hash Key, Hash IV)及商店代號(Merchant ID)複製至原始碼

**PHP 範例：**
```php
$key="IaWudQJsuOT994cpHRWzv7Ge67yC1cE3";
$iv="C1dLm3nxZRVlmBSP";
$mid="TEK1682407426";
```

#### Step 1: 生成請求字串

參考 4.3.1 請求參數，帶入必要參數，並生成 URL 字串

**PHP 範例：**
```php
$data1=http_build_query(array(
'RespondType'=>'JSON',
'TimeStamp'=>time(),
'Version'=>'1.5',
'LangType'=>'zh-Tw',
'MerOrderNo'=>'myorder'.time(),
'ProdDesc'=>'Test commssion',
'PeriodAmt'=>'10',
'PeriodType'=>'M',
'PeriodPoint'=>'05',
'PeriodStartType'=>'2',
'PeriodTimes'=>'12',
'PayerEmail'=>'test@neweb.com.tw',
'PaymentInfo'=>'Y',
'OrderInfo'=>'N',
'EmailModify'=>'1',
'NotifyURL'=>'https://webhook.site/b728e917-1bf7-478b-b0f973b56aeb44e0',
));
```

#### Step 2: 將請求字串加密

為防止信用卡號等重要交易訊息洩漏，需於加密前使用商店之 Hash Key 及 Hash IV 對上述字串執行 AES-256-CBC (使用 PKCS7 填充)，並將結果轉換至十六進制。

**PHP 範例：**
```php
$edata1=bin2hex(openssl_encrypt($data1, "AES-256-CBC", $key, OPENSSL_RAW_DATA, $iv));
```

#### Step 3: 發布請求

參考 4.3.1 請求參數，將必要參數以 HTML form 形式組合，並發佈於 API URL。

**HTML 範例：**
```html
<form method=post action="https://ccore.newebpay.com/MPG/period">
MI: <input name="MerchantID_" value="<?=$mid?>" readonly><br>
PostData_: <input name="PostData_" value="<?=$edata1?>" readonly type="hidden"><?=$edata1?><br>
<input type=submit value='Submit'>
</form>
```

#### Step 4: 結果

消費者完成支付後，藍新金流即透過 NotifyURL 回傳已加密字串給商店。

### 4.2 AES256 解密

#### Step 5: 將加密字串進行解密

使用商店之 API 串接金鑰(Hash Key, Hash IV)進行解密。

**PHP 範例：**
```php
<?php
$key="IaWudQJsuOT994cpHRWzv7Ge67yC1cE3";
$iv="C1dLm3nxZRVlmBSP";

function strippadding($string) {
    $slast = ord(substr($string, -1));
    $slastc = chr($slast);
    $pcheck = substr($string, -$slast);
    if (preg_match("/$slastc{" . $slast . "}/", $string)) {
        $string = substr($string, 0, strlen($string) - $slast);
        return $string;
    } else {
        return false;
    }
}

$edata1=strippadding(openssl_decrypt(hex2bin($data1), "AES256-CBC", $key, OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $iv));
echo "解密後資料=[".$edata1."]<br>";
?>
```

### 4.3 建立委託 [NPA-B05]

**測試串接網址：** https://ccore.newebpay.com/MPG/period

**正式串接網址：** https://core.newebpay.com/MPG/period

#### 4.3.1 請求參數

| 參數名稱 | 必填 | 型態 | 備註 |
|---------|-----|------|------|
| MerchantID_ | V | String(15) | 商店代號 |
| PostData_ | V | Text | AES 加密後的資料 |
| RespondType | V | String(5) | JSON 或是 String |
| TimeStamp | V | String(30) | Unix 時間戳 |
| Version | V | String(5) | 1.5 |
| LangType | | String(5) | 英文版= en, 繁體中文版= zh-Tw |
| MerOrderNo | V | String(30) | 商店訂單編號 |
| ProdDesc | V | String(100) | 產品名稱 |
| PeriodAmt | V | Int(6) | 委託金額 |
| PeriodType | V | String(1) | D=天, W=週, M=月, Y=年 |
| PeriodPoint | V | String(4) | 授權時間點 |
| PeriodStartType | V | Int(1) | 1=十元, 2=首期金額, 3=不授權 |
| PeriodTimes | V | String(2) | 授權期數 |
| PeriodFirstdate | | String(10) | 首期授權日期 |
| ReturnURL | | String(100) | 返回商店網址 |
| PeriodMemo | | String(255) | 備註說明 |
| PayerEmail | V | String(50) | 付款人電子信箱 |
| EmailModify | | Int(1) | 1=可修改, 0=不可修改 |
| PaymentInfo | | String(1) | Y=是, N=否 |
| OrderInfo | | String(1) | Y=是, N=否 |
| NotifyURL | | String(100) | 授權結果通知網址 |
| BackURL | | String(100) | 取消交易返回網址 |
| UNIONPAY | | Int(1) | 銀聯卡啟用 |

#### 4.3.2 回應參數-建立完成

| 參數名稱 | 備註 |
|---------|------|
| Period | AES 加密後的回傳結果 |
| Status | 回傳狀態：SUCCESS=成功 |
| Message | 回傳訊息 |
| Result | 回傳資料 |

#### 4.3.3 回應參數-每期授權完成[NPA-N050]

---

### 4.4 修改委託狀態 [NPA-B051]

**測試環境串接：** https://ccore.newebpay.com/MPG/period/AlterStatus

**正式環境串接：** https://core.newebpay.com/MPG/period/AlterStatus

#### 4.4.1 請求參數

| 參數名稱 | 必填 | 型態 | 備註 |
|---------|-----|------|------|
| MerchantID_ | V | String(15) | 商店代號 |
| PostData_ | V | Text | AES 加密後的資料 |
| RespondType | V | String(5) | JSON 或是 String |
| Version | V | String(5) | 1.0 |
| TimeStamp | V | String(30) | Unix 時間戳 |
| MerOrderNo | V | String(30) | 商店訂單編號 |
| PeriodNo | V | String(20) | 委託單號 |
| AlterType | V | String(20) | suspend=暫停, terminate=終止, restart=啟用 |

#### 4.4.2 回應參數

| 參數名稱 | 備註 |
|---------|------|
| Status | SUCCESS=成功 |
| Message | 交易訊息 |
| Result | 交易結果 |

---

### 4.5 修改委託內容 [NPA-B052]

**測試串接網址：** https://ccore.newebpay.com/MPG/period/AlterAmt

**正式串接網址：** https://core.newebpay.com/MPG/period/AlterAmt

#### 4.5.1 請求參數

| 參數名稱 | 必填 | 型態 | 備註 |
|---------|-----|------|------|
| MerchantID_ | V | String(15) | 商店代號 |
| PostData_ | V | Text | AES 加密後的資料 |
| RespondType | V | String(5) | JSON 或是 String |
| Version | V | String(5) | 1.2 |
| TimeStamp | V | String(30) | Unix 時間戳 |
| MerOrderNo | V | String(30) | 商店訂單編號 |
| PeriodNo | V | String(20) | 委託單號 |
| AlterAmt | + | Int(6) | 委託金額 |
| PeriodType | + | String(1) | D, W, M, Y |
| PeriodPoint | + | String(4) | 授權時間點 |
| PeriodTimes | + | String(2) | 授權期數 |
| Extday | | String(4) | 信用卡到期日 (YYMM) |
| NotifyURL | | String(100) | 授權結果通知網址 |

#### 4.5.2 回應參數

| 參數名稱 | 備註 |
|---------|------|
| Status | SUCCESS=成功 |
| Message | 交易訊息 |
| Result | 交易結果 |

---

## 5. 錯誤代碼表

| 錯誤代碼 | 錯誤說明 |
|---------|--------|
| ACC10005 | 會員已被暫時停權/永久停權，請洽藍新金流客服中心查詢 |
| NOR10001 | 連線異常 |
| PER10001 | 商店資料取得失敗 |
| PER10002 | 資料解密錯誤 |
| PER10003 | POST 資料傳遞錯誤 |
| PER10004 | OOO 資料不齊全 (OOO 帶入缺少參數) |
| PER10005 | 資料不可空白 |
| PER10006 | 商品名稱不得含有 JavaScript 語法、CSS 語法 |
| PER10007 | 委託金額格式不對，金額必須為數字 |
| PER10008 | 委託金額超過單筆金額上限 |
| PER10009 | 週期設定錯誤! (W=週,M=月,Y=年) |
| PER10010 | 商店訂單編號錯誤，只允許英數與底線 |
| PER10011 | 商店訂單編號長度限制為 30 字 |
| PER10012 | 回傳格式錯誤，只接受 JSON 或 String |
| PER10013 | 日期授權時間資料不正確，日期格式為 2 到 364 |
| PER10014 | 週期授權時間資料不正確，日期格式為 1 到 7(長度不符) |
| PER10015 | 月期授權時間資料不正確，日期格式為 01 到 31 |
| PER10016 | 月期授權時間資料不正確，日期格式為 01 到 31(長度不符) |
| PER10017 | 年期授權時間資料不正確，日期格式為 01 到 12 |
| PER10018 | 年期授權時間資料不正確，日期格式為 01 到 31 |
| PER10019 | 定期授權時間資料不正確，無該日期 |
| PER10020 | 首期授權模式設定錯誤(1-3)，請檢查 |
| PER10021 | 備註說明不得含有 JavaScript 語法、CSS 語法 |
| PER10022 | 授權期數格式不對，必須為數字 |
| PER10023 | 授權期數不能為零 |
| PER10024 | 授權期數不能多於 99 次 |
| PER10025 | 返回商店網址格式錯誤 |
| PER10026 | 每期授權通知網址格式錯誤 |
| PER10027 | 是否開啟付款人資訊設定錯誤付款人電子信箱格式錯誤 |
| PER10028 | 商品名稱僅限制使用中文、英文、數字、空格及底線，請勿使用其他符號字 |
| PER10029 | 商店代號停用 |
| PER10030 | 商店信用卡資格停用 |
| PER10031 | 商店定期定額資格停用 |
| PER10032 | 該訂單編號已重覆 |
| PER10033 | 寫入委託單失敗 |
| PER10034 | 授權失敗，委託單建立失敗 |
| PER10035 | 委託單更新授權結果失敗 |
| PER10036 | 驗證資料錯誤(來源不合法) |
| PER10037 | 付款頁參數不足 |
| PER10038 | 商品名稱僅限制使用中文、英文、數字、空格及底線，請勿使用其他符號字元 |
| PER10041 | 第一期發動日日期不正確 |
| PER10043 | 銀聯卡參數錯誤 |
| PER10044 | 商店銀聯卡資格停用 |
| PER10061 | 該定期定額委託單為暫停狀態，無法重複暫停 |
| PER10062 | 該定期定額委託單為終止狀態，無法暫停 |
| PER10063 | 該定期定額委託單為啟用狀態，無法重複啟用 |
| PER10064 | 該定期定額委託單為終止狀態，無法啟用 |
| PER10065 | 該定期定額委託單為終止狀態，無法重複終止 |
| PER10066 | Version 參數錯誤 |
| PER10067 | 查無委託單資料 |
| PER10068 | 委託單狀態更新失敗 |
| PER10071 | 該定期定額委託單已暫停無法修改 |
| PER10072 | 定期定額委託單為終止狀態無法修改 |
| PER10073 | 此 IP 不允許執行變更該委託單狀態 |
| PER10074 | 本 API 需由藍新金流審核通過後才得以使用，若有串接需求請聯繫客戶服務中心或商務經理 |
| PER10075 | 該委託單已到期 |
| PER10076 | 信用卡到期日參數錯誤 |
| PER10078 | 警示交易 |
| TRA10043 | 卡片到期日格式錯誤 |

---

## 6. 常見問題

### 建立委託[NPA-B05]

**Q: 委託單建立後沒有自動進行授權的可能原因？**

A1: 若首期授權成功，但第二期授權因其他原因授權失敗(額度不足等原因)，則系統仍會自動執行第二期之後期數授權，直到委託單終止。

A2: 若委託單執行扣款時，該信用卡狀態為非正常卡，系統將限制該卡無法進行授權交易，故使用該卡扣款的委託單於限制期間內將不會進行授權，直到限制期間結束。

**Q: 接收回傳 Notify 無法正常解析的可能原因？**

A1: 若您使用的程式語言對於 Content-type 格式要求規範較嚴謹，如 JSP 等，可參考相應的程式範例。

---

*文件為藍新科技股份有限公司版權所有*
