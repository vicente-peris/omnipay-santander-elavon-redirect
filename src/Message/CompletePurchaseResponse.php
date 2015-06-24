<?php

namespace Omnipay\SantanderElavonRedirect\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Santander Elavon Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        if ($this->data['RESULT'] == '00') {
            return true;
        }
        return false;
    }

    public function isRedirect()
    {
        return false;
    }

    public function getOrderId() {
        return $this->data['ORDER_ID'];
    }

    public function getResult()
    {
        return $this->data['RESULT'];
    }
}
