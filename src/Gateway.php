<?php

namespace Omnipay\SantanderElavonRedirect;

use Omnipay\Common\AbstractGateway;

/**
 * Santander Elavon Redirect Gateway
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'SantanderElavonRedirect';
    }

    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'secretKey' => '',
            'testMode' => false,
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('secretKey', $value);
    }

    public function getAccount()
    {
        return $this->getParameter('account');
    }

    public function setAccount($value)
    {
        return $this->setParameter('account', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SantanderElavonRedirect\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\SantanderElavonRedirect\Message\CompletePurchaseRequest', $parameters);
    }
}
