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
            'offerSaveCard' => false
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

    public function getPayerRef()
    {
        return $this->getParameter('payerRef');
    }

    public function setPayerRef($value)
    {
        return $this->setParameter('payerRef', $value);
    }

    public function getPmtRef()
    {
        return $this->getParameter('pmtRef');
    }

    public function setPmtRef($value)
    {
        return $this->setParameter('pmtRef', $value);
    }

    public function getOfferSaveCard()
    {
        return $this->getParameter('offerSaveCard');
    }

    public function setOfferSaveCard($value)
    {
        return $this->setParameter('offerSaveCard', $value);
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
