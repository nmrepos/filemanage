<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CompanyProfiles;

class CompanyProfilesModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $profile = new CompanyProfiles();
        $this->assertInstanceOf(CompanyProfiles::class, $profile);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $profile = new CompanyProfiles();
        $expected = [
            'title', 'logoUrl',
            'modifiedBy', 'isDeleted', 'bannerUrl', 'location'
        ];
        $this->assertEquals($expected, $profile->getFillable());
    }

    /** @test */
    public function it_has_custom_timestamp_constants()
    {
        $this->assertEquals('createdDate', CompanyProfiles::CREATED_AT);
        $this->assertEquals('modifiedDate', CompanyProfiles::UPDATED_AT);
    }

    /** @test */
    public function it_has_hidden_fields()
    {
        $profile = new CompanyProfiles();
        $hidden = [
            'createdBy', 'modifiedBy', 'deletedBy',
            'createdDate', 'modifiedDate', 'isDeleted', 'deleted_at'
        ];
        $this->assertEquals($hidden, $profile->getHidden());
    }
}
