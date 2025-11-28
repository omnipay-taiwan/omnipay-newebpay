<?php

namespace Omnipay\NewebPay\Traits;

trait HasOrderDetail
{
    /**
     * 訂購明細商品資料.
     * 1.商品資料以 JSON 格式傳送
     * 2.JSON 格式範例：
     *   [{"name":"商品A","count":1,"unit":"個","price":100}]
     * 3.每筆商品資料包含以下欄位：
     *   - name: 商品名稱
     *   - count: 商品數量
     *   - unit: 商品單位
     *   - price: 商品單價
     *
     * @param  string|array  $value
     * @return self
     */
    public function setOrderDetail($value)
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return $this->setParameter('OrderDetail', $value);
    }

    /**
     * @return ?string
     */
    public function getOrderDetail()
    {
        return $this->getParameter('OrderDetail');
    }
}
