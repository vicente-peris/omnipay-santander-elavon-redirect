<?php

namespace Omnipay\SantanderElavonRedirect\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Santander Elavon Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl() {
        return $this->request->getEndpoint();
    }

    public function getRedirectData() {
        return $this->getData();
    }

    public function getRedirectMethod() {
        return 'POST';
    }
}
