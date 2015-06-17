<?php

namespace Omnipay\SantanderElavonRedirect\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Santander Elavon Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{

    public function checkSignature($data) {
        if (!isset($data['SHA1HASH'])) {
            return false;
        }

        $signature = $data['TIMESTAMP'].'.'.$data['MERCHANT_ID'].'.'.$data['ORDER_ID'].'.'.$data['AMOUNT'].'.'.$data['CURRENCY'];
        $signature = strtolower(sha1($signature));
        $signature .= '.'.$this->getSecretKey();
        $signature = strtolower(sha1($signature));

        return $signature == strtolower($data['SHA1HASH']);
    }

    public function getData()
    {
        $query = $this->httpRequest->request;

        $data = array();

        foreach (array('MERCHANT_ID', 'ACCOUNT', 'ORDER_ID', 'AMOUNT', 'CURRENCY', 'TIMESTAMP', 'SHA1HASH', 'AUTO_SETTLE_FLAG', 'COMMENT1', 'COMMENT2', 'RETURN_TSS', 'SHIPPING_CODE', 'SHIPPING_CO', 'BILLING_CODE', 'BILLING_CO', 'CUST_NUM', 'VAR_REF', 'PROD_ID', 'ANYTHING ELSE', 'RESULT') as $field) {
            $data[$field] = $query->get($field);
        }

        if (!$this->checkSignature($data)) {
            throw new InvalidResponseException('Invalid signature, Order:' . $data['ORDER_ID']);
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
