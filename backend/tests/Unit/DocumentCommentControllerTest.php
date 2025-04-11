<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentCommentController;
use App\Repositories\Contracts\DocumentCommentRepositoryInterface;

class DocumentCommentControllerTest extends TestCase
{
    /**
     * Test that index() returns the expected JSON response.
     */
    public function testIndexReturnsJsonResponse()
    {
        $id = 123;
        // Note: Use "documentId" as the key to match the actual repository return value.
        $expectedData = [
            ['id' => 1, 'comment' => 'Test comment', 'documentId' => $id],
            ['id' => 2, 'comment' => 'Another comment', 'documentId' => $id],
        ];

        // Create a repository mock.
        $repositoryMock = $this->createMock(DocumentCommentRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('getDocumentComment')
            ->with($id)
            ->willReturn($expectedData);

        // Instantiate the controller.
        $controller = new DocumentCommentController($repositoryMock);

        // Call the index method.
        $response = $controller->index($id);

        // Assert that the JSON response content matches the expected data.
        $this->assertEquals(json_encode($expectedData), $response->getContent());
    }

    /**
     * Test that destroy() returns a 204 response.
     */
    public function testDestroyReturns204Response()
    {
        $id = 456;
        $expectedOutput = ''; // Repository returns empty output.

        $repositoryMock = $this->createMock(DocumentCommentRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('deleteDocumentComments')
            ->with($id)
            ->willReturn($expectedOutput);

        $controller = new DocumentCommentController($repositoryMock);
        $response = $controller->destroy($id);

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals($expectedOutput, $response->getContent());
    }

    /**
     * Test that saveDocumentComment() returns a 201 response with the expected result.
     */
    public function testSaveDocumentCommentReturns201Response()
    {
        $inputData = [
            'document_id' => 789,  // or use the desired key format; just be consistent in both controller & test.
            'comment' => 'This is a comment'
        ];
        $request = Request::create('/document-comments', 'POST', $inputData);

        $expectedResult = ['id' => 10, 'document_id' => 789, 'comment' => 'This is a comment'];

        $repositoryMock = $this->createMock(DocumentCommentRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('create')
            ->with($inputData)
            ->willReturn($expectedResult);

        $controller = new DocumentCommentController($repositoryMock);
        $response = $controller->saveDocumentComment($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $response->getContent());
    }
}
