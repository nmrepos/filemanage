<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\SendEmails;

class SendEmailsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new SendEmails();
        $this->assertInstanceOf(SendEmails::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new SendEmails();
        $expected = ['subject', 'message', 'fromEmail', 'isSend', 'email', 'documentId', 'createdBy', 'modifiedBy', 'isDeleted'];
        $this->assertEquals($expected, $model->getFillable());
    }
    /** @test */
    public function it_calls_relationship_methods()
    {
        $model = new SendEmails();
        $model->documents();
        $this->assertTrue(true);
    }

}
