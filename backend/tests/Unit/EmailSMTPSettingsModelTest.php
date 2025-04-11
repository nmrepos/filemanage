<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\EmailSMTPSettings;

class EmailSMTPSettingsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $model = new EmailSMTPSettings();
        $this->assertInstanceOf(EmailSMTPSettings::class, $model);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $model = new EmailSMTPSettings();
        $expected = ['host', 'userName', 'password', 'port', 'isDefault', 'createdBy', 'modifiedBy', 'isDeleted', 'from_address', 'from_name', 'encryption', 'fromName'];
        $this->assertEquals($expected, $model->getFillable());
    }
}
