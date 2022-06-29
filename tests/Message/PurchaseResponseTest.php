<?php

namespace Omnipay\IPay88\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{

    private $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'card' => [
                'firstName' => 'Manas',
                'lastName' => 'Atabaev',
                'email' => 'manas@compumed.com.my',
                'number' => '93804194',
                'phone' => '93804194',
            ],
            'amount' => '1.00',
            'currency' => 'MYR',
            'description' => 'Invoice #333',
            'transactionId' => 'A00000001',
            'returnUrl' => 'https://www.example.com/return',
        ]);

        $this->request->setMerchantKey('apple');

        $this->request->setMerchantCode('M00003');

        $this->request->setBackendUrl('https://www.example.com/backend');
    }

    public function testSuccess()
    {
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertTrue($response->isTransparentRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('A00000001', $response->getTransactionId());
        $this->assertSame('https://payment.ipay88.com.my/epayment/entry.asp', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertEquals([
            'MerchantCode' => 'M00003',
            'PaymentId' => '',
            'RefNo' => 'A00000001',
            'Amount' => '1.00',
            'Currency' => 'MYR',
            'ProdDesc' => 'Invoice #333',
            'UserName' => 'Manas Atabaev',
            'UserEmail' => 'manas@compumed.com.my',
            'UserContact' => '93804194',
            'Remark' => '',
            'Lang' => '',
            'SignatureType' => 'SHA256',
            'Signature' => '110f0be755ccfa9373aa38104bafbc5c6e5462344e44bcfbb70439c82b4b07fa',
            'ResponseURL' => 'https://www.example.com/return',
            'BackendURL' => 'https://www.example.com/backend',
        ], $response->getRedirectData());
    }
}
