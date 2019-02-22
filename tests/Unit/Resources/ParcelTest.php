<?php

namespace Mvdnbrk\DhlParcel\Tests\Unit\Resources;

use Mvdnbrk\DhlParcel\Tests\TestCase;
use Mvdnbrk\DhlParcel\Resources\Parcel;
use Mvdnbrk\DhlParcel\Resources\Recipient;

class ParcelTest extends TestCase
{
    /** @test */
    public function it_has_a_recipient()
    {
        $parcel = new Parcel;

        $this->assertInstanceOf(Recipient::class, $parcel->recipient);
    }

    /** @test */
    public function it_has_a_sender()
    {
        $parcel = new Parcel;

        $this->assertInstanceOf(Recipient::class, $parcel->sender);
    }

    /** @test */
    public function create_a_new_parcel()
    {
        $parcel = new Parcel([
            'reference_identifier' => 'test-123',
            'recipient' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
            ],
            'sender' => [
                'company' => 'Test Company B.V.',
            ],
            'options' => [
                'description' => 'Test 123',
                'only_recipient' => true,
                'signature' => true,
            ],
        ]);

        $this->assertEquals('test-123', $parcel->reference_identifier);
        $this->assertEquals('test-123', $parcel->reference);
        $this->assertEquals('John', $parcel->recipient->first_name);
        $this->assertEquals('Doe', $parcel->recipient->last_name);
        $this->assertEquals('Test Company B.V.', $parcel->sender->company);
        $this->assertEquals('Test 123', $parcel->options->label_description);
        $this->assertSame(true, $parcel->options->only_recipient);
        $this->assertSame(true, $parcel->options->signature);
    }

    /** @test */
    public function it_can_set_the_recipient_by_passing_a_recipient_object()
    {
        $recipient = new Recipient([
            'company' => 'Test Company B.V.',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $parcel = new Parcel([
            'recipient' => $recipient,
        ]);

        $this->assertEquals('Test Company B.V.', $parcel->recipient->company);
        $this->assertEquals('John', $parcel->recipient->first_name);
        $this->assertEquals('Doe', $parcel->recipient->last_name);
    }

    /** @test */
    public function it_can_set_a_label_description()
    {
        $parcel = new Parcel();
        $this->assertNull($parcel->options->label_description);

        $parcel->labelDescription('Test 123');
        $this->assertEquals('Test 123', $parcel->options->label_description);
        $this->assertEquals('Test 123', $parcel->options->description);
    }

    /** @test */
    public function calling_the_label_description_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->labelDescription('Test 123'));
    }

    /** @test */
    public function it_can_require_a_signature_from_the_recipient_of_the_parcel()
    {
        $parcel = new Parcel();
        $this->assertFalse($parcel->options->signature);

        $parcel->signature();

        $this->assertTrue($parcel->options->signature);
    }

    /** @test */
    public function calling_the_signature_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->signature());
    }


    /** @test */
    public function it_can_set_a_parcel_to_be_only_delivered_to_the_recipient()
    {
        $parcel = new Parcel();

        $this->assertFalse($parcel->options->only_recipient);

        $parcel->onlyRecipient();

        $this->assertTrue($parcel->options->only_recipient);
    }

    /** @test */
    public function calling_the_only_recipient_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->onlyRecipient());
    }

    /** @test */
    public function it_can_set_a_parcel_to_be_a_mailbox_package()
    {
        $parcel = new Parcel();

        $parcel->mailboxpackage();

        $this->assertEquals(['key' => 'BP'], $parcel->options->toArray()[0]);
    }

    /** @test */
    public function calling_the_mailboxpackage_method_returns_the_same_parcel_instance()
    {
        $parcel = new Parcel();

        $this->assertSame($parcel, $parcel->mailboxpackage());
    }

    /** @test */
    public function to_array()
    {
        $parcel = new Parcel;

        $array = $parcel->toArray();

        $this->assertInternalType('array', $array);

        $this->assertArrayHasKey('receiver', $array);
        $this->assertArrayHasKey('shipper', $array);
    }
}