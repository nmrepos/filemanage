<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\DocumentComments;

class DocumentCommentsModelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $comment = new DocumentComments();
        $this->assertInstanceOf(DocumentComments::class, $comment);
    }

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $comment = new DocumentComments();
        $expected = [
            'documentId', 'comment', 'createdBy',
            'modifiedBy', 'isDeleted'
        ];
        $this->assertEquals($expected, $comment->getFillable());
    }

    /** @test */
    public function it_has_custom_timestamps()
    {
        $this->assertEquals('createdDate', DocumentComments::CREATED_AT);
        $this->assertEquals('modifiedDate', DocumentComments::UPDATED_AT);
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $comment = new DocumentComments();
        $this->assertFalse($comment->incrementing);
    }

    /** @test */
    public function it_has_relationship_methods()
    {
        $comment = new DocumentComments();

        $this->assertTrue(method_exists($comment, 'user'));
        $this->assertTrue(method_exists($comment, 'document'));
    }
}
