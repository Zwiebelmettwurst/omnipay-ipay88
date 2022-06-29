<?php

namespace Omnipay\IPay88\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    private $successData = [
        'MerchantCode' => '123',
        'PaymentId' => 1,
        'RefNo' => 'order_id',
        'Amount' => '123',
        'Currency' => 'MYR',
        'Remark' => '100',
        'TransId' => '54321',
        'AuthCode' => '',
        'Status' => 1,
        'ErrDesc' => '',
        'Signature' => 'sig123',
        'ComputedSignature' => 'sig123',
        'ReQueryStatus' => '00',
    ];

    public function testConstruct()
    {
        $response = new CompletePurchaseResponse($this->getMockRequest(), $this->successData);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('Successful payment', $response->getMessage());
        $this->assertSame('54321', $response->getTransactionReference());
        $this->assertSame('order_id', $response->getTransactionId());
    }

    public function testConstruct_invalid_status()
    {
        $data = $this->successData;
        $data['Status'] = 0;
        $data['ErrDesc'] = 'Duplicate reference number';

        $response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame('Duplicate reference number', $response->getMessage());
        $this->assertSame('54321', $response->getTransactionReference());
        $this->assertSame('order_id', $response->getTransactionId());
    }

    public function testConstruct_invalid_signature()
    {
        $data = $this->successData;
        $data['ComputedSignature'] = 'not_matching_signature';

        $response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertSame('Invalid signature returned from iPay88', $response->getMessage());
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('54321', $response->getTransactionReference());
        $this->assertSame('order_id', $response->getTransactionId());
    }

    public function testConstruct_invalid_re_query_status()
    {
        $data = $this->successData;
        $data['ReQueryStatus'] = 'Incorrect amount';

        $response = new CompletePurchaseResponse($this->getMockRequest(), $data);

        $this->assertSame('Amount different', $response->getMessage());
        $this->assertFalse($response->isSuccessful());
        $this->assertSame('54321', $response->getTransactionReference());
        $this->assertSame('order_id', $response->getTransactionId());
    }
}
