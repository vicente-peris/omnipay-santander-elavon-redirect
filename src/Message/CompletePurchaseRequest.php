<?php

namespace Omnipay\SantanderElavonRedirect\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Santander Elavon Complete Purchase Request
 */
class CompletePurchaseRequest extends PurchaseRequest
{

    public function checkSignature($generatedSignature, $requestSignature) {
        return $generatedSignature == strtolower($requestSignature);
    }

    public function getData()
    {
        $query = $this->httpRequest->request;

        $data = array();

        foreach (array('MERCHANT_ID', 'ACCOUNT', 'ORDER_ID', 'TIMESTAMP', 'AMOUNT', 'AUTHCODE', 'RESULT', 'MESSAGE', 'CVNRESULT', 'PASREF', 'BATCHID', 'ECI', 'CAVV', 'XID', 'SHA1HASH', 'TSS') as $field) {
            $data[$field] = $query->get($field);
        }

        $data['CURRENCY'] = $this->getCurrency();
        $generatedSignature = $this->generateSignature($data);
        $requestSignature = $data['SHA1HASH'];

        if (!$this->checkSignature($generatedSignature, $requestSignature)) {
            throw new InvalidResponseException('Invalid signature, Order:' . $data['ORDER_ID']);
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
