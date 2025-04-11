<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\LoginAudit;

class LoginAuditModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $loginAudit = new LoginAudit();
        $this->assertInstanceOf(LoginAudit::class, $loginAudit);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $loginAudit = new LoginAudit();
        $expected = [
            'userName', 'loginTime', 'remoteIP', 'status', 'provider',
            'latitude', 'longitude'
        ];
        $this->assertEquals($expected, $loginAudit->getFillable());
    }

    /** @test */
    public function it_allows_setting_attributes()
    {
        $loginAudit = new LoginAudit();
        $loginAudit->userName = 'noobuser';
        $loginAudit->status = 'success';

        $this->assertEquals('noobuser', $loginAudit->userName);
        $this->assertEquals('success', $loginAudit->status);
    }

    /** @test */
    public function it_has_login_time_as_date()
    {
        $loginAudit = new LoginAudit();
        $this->assertContains('loginTime', $loginAudit->getDates());
    }
}
