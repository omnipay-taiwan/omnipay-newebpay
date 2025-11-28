<?php

namespace Omnipay\NewebPay\Message;

use Omnipay\NewebPay\Traits\HasDefaults;

class CompletePeriodRequest extends AbstractRequest
{
    use HasDefaults;

    public function getData()
    {
        $period = $this->httpRequest->request->get('Period');

        return $this->decodeResponse($this->decrypt($period));
    }

    public function sendData($data)
    {
        return $this->response = new CompletePeriodResponse($this, $data);
    }
}
