<?php

namespace Omnipay\Skeleton;

use Omnipay\Common\AbstractGateway;
use Omnipay\Skeleton\Message\AuthorizeRequest;

/**
 * Skeleton Gateway
 */
class SkeletonGateway extends AbstractGateway
{
    public function getName()
    {
        return 'Skeleton';
    }

    public function getDefaultParameters()
    {
        return [
            'key' => '',
            'testMode' => false,
        ];
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }

    /**
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $options);
    }
}
