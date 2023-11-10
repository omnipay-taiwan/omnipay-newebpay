<?php

namespace PHPSTORM_META {

    /** @noinspection PhpIllegalArrayKeyTypeInspection */
    /** @noinspection PhpUnusedLocalVariableInspection */
    $STATIC_METHOD_TYPES = [
      \Omnipay\Omnipay::create('') => [
        'NewebPay' instanceof \Omnipay\NewebPay\Gateway,
      ],
      \Omnipay\Common\GatewayFactory::create('') => [
        'NewebPay' instanceof \Omnipay\NewebPay\Gateway,
      ],
    ];
}
