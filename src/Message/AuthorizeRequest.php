<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\NewebPay\Traits\HasDefaults;

/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    use HasDefaults;

    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

        return $this->getBaseData();
    }
}
