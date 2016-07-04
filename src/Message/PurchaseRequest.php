<?php

namespace Omnipay\SantanderElavonRedirect\Message;

use DateTime;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Santander Elavon Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://hpp.santanderelavontpvvirtual.es/pay';

    protected $testEndpoint = 'https://hpp.prueba.santanderelavontpvvirtual.es/pay';

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

    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
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

    public function generateSignature($data) {
        $signature = $data['TIMESTAMP'].'.'.$data['MERCHANT_ID'].'.'.$data['ORDER_ID'].'.'.$data['AMOUNT'].'.'.$data['CURRENCY'];
        if($this->getOfferSaveCard()) $signature .= '.'.$this->getPayerRef().'.'.$this->getPmtRef();
        $signature = strtolower(sha1($signature));
        $signature .= '.'.$this->getSecretKey();
        $signature = strtolower(sha1($signature));

        return $signature;
    }

    public function getData()
    {
        $this->validate('amount', 'currency', 'merchantId');
        $amount = str_replace('.', '', $this->getAmount());
        $amount = str_replace(',', '', $amount);
        $now = new DateTime('NOW');
        $timestamp = $now->format('YmdHis');
        $order = (!is_null($this->getTransactionId())) ? $this->getTransactionId() : $timestamp;

        $data = array(
            'MERCHANT_ID' => $this->getMerchantId(),
            'ACCOUNT' => $this->getAccount(),
            'ORDER_ID' => $order,
            'AMOUNT' => $amount,
            'CURRENCY' => $this->getCurrency(),
            'COMMENT1' => $this->getDescription(),
            'TIMESTAMP' => $timestamp,
            'AUTO_SETTLE_FLAG' => 1
        );

        if($this->getOfferSaveCard()){
          $data['OFFER_SAVE_CARD'] = 1;
          $data['PAYER_REF'] = $this->getPayerRef();
          $data['PMT_REF'] = $this->getPmtRef();
          $data['PAYER_EXIST'] = 1;
          $data['CARD_STORAGE_ENABLE'] = 1;
        }

        $data['SHA1HASH'] = $this->generateSignature($data);

        if (is_array($this->getMetadata())) {
            foreach ($this->getMetadata() as $k => $v) {
                $data[$k] = $v;
            }
        }

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

}
