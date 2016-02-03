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

        $signature = $data['TIMESTAMP'].'.'.$data['MERCHANT_ID'].'.'.$data['ORDER_ID'].'.'.$data['RESULT'].'.'.$data['MESSAGE'].'.'.$data['PASREF'].'.'.$data['AUTHCODE'];
        $signature = strtolower(sha1($signature));
        $signature .= '.'.$this->getSecretKey();
        $signature = strtolower(sha1($signature));

        return $signature == strtolower($data['SHA1HASH']);
    }

    public function getData()
    {
        $query = $this->httpRequest->query;

        $data = array();

        foreach (array('MERCHANT_ID', 'ACCOUNT', 'ORDER_ID', 'TIMESTAMP', 'AMOUNT', 'AUTHCODE', 'RESULT', 'MESSAGE', 'CVNRESULT', 'PASREF', 'BATCHID', 'ECI', 'CAVV', 'XID', 'SHA1HASH', 'TSS') as $field) {
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
