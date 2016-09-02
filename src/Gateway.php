<?php
/**
 * iPay8 Gateway
 */
namespace Omnipay\IPay88;

use Omnipay\Common\AbstractGateway;

/**
 * iPay88 Gateway
 *
 * This driver is based on
 * Online Payment Switching Gateway Technical Specification Version 1.6.1
 * @link https://drive.google.com/file/d/0B4YUBYSgSzmAbGpjUXMyMWx6S2s/view?usp=sharing
 */
class Gateway extends AbstractGateway
{

    public function getDefaultParameters()
    {
        return [
            'merchantKey' => '',
            'merchantCode' => ''
        ];
    }

    public function getName()
    {
        return 'iPay88';
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchantKey');
    }

    public function setMerchantKey($merchantKey)
    {
        $this->setParameter('merchantKey', $merchantKey);
    }

    public function getMerchantCode()
    {
        return $this->getParameter('merchantCode');
    }

    public function setMerchantCode($merchantCode)
    {
        $this->setParameter('merchantCode', $merchantCode);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\IPay88\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\IPay88\Message\CompletePurchaseRequest', $parameters);
    }

}