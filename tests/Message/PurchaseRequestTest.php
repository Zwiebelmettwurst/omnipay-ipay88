<?php

namespace Omnipay\IPay88\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp(): void
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize([
            'card' => [
                'firstName' => 'Xu',
                'lastName' => 'Ding',
                'email' => 'xuding@spacebib.com',
                'number' => '93804194',
                'phone' => '93804194',
            ],
            'amount' => '1.00',
            'currency' => 'MYR',
            'description' => 'Marina Run 2016',
            'transactionId' => 'A00000001',
            'returnUrl' => 'https://www.example.com/return',
        ]);

        $this->request->setMerchantKey('apple');

        $this->request->setMerchantCode('M00003');

        $this->request->setBackendUrl('https://www.example.com/backend');
    }

    public function testGetData()
    {
        $result = $this->request->getData();

        $expected = [
            'MerchantCode' => 'M00003',
            'PaymentId' => '',
            'RefNo' => 'A00000001',
            'Amount' => number_format('1', 2),
            'Currency' => 'MYR',
            'ProdDesc' => 'Marina Run 2016',
            'UserName' => 'Xu Ding',
            'UserEmail' => 'xuding@spacebib.com',
            'UserContact' => '93804194',
            'Remark' => '',
            'Lang' => '',
            'SignatureType' => 'SHA256',
            'Signature' => '110f0be755ccfa9373aa38104bafbc5c6e5462344e44bcfbb70439c82b4b07fa',
            'ResponseURL' => 'https://www.example.com/return',
            'BackendURL' => 'https://www.example.com/backend',
        ];

        $this->assertEquals($expected, $result);
    }

    public function testGetData_Signature()
    {
        $signature = '110f0be755ccfa9373aa38104bafbc5c6e5462344e44bcfbb70439c82b4b07fa';

        $result = $this->request->getData()['Signature'];
        $this->assertEquals($signature, $result);

        $this->request->setMerchantKey('orange');
        $result = $this->request->getData()['Signature'];
        $this->assertNotEquals($signature, $result);
    }
}
