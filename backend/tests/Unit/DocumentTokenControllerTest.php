<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DocumentTokenController;
use App\Repositories\Contracts\DocumentTokenRepositoryInterface;
use Illuminate\Http\Response;

class DocumentTokenControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a mock of the DocumentTokenRepositoryInterface
        $this->repository = $this->createMock(DocumentTokenRepositoryInterface::class);
        // Inject the mock into the controller
        $this->controller = new DocumentTokenController($this->repository);
    }

    public function testGetDocumentTokenReturnsExpectedData()
    {
        $id = 1;
        $expectedToken = 'abc123';

        // Expect the getDocumentToken method to be called with $id and return $expectedToken
        $this->repository->expects($this->once())
            ->method('getDocumentToken')
            ->with($id)
            ->willReturn($expectedToken);

        $result = $this->controller->getDocumentToken($id);
        $this->assertEquals(['result' => $expectedToken], $result);
    }


    public function testGetSharedDocumentTokenReturnsExpectedDataWhenFound()
    {
        $id = 2;
        $expectedToken = 'sharedToken123';

        // Simulate repository returning a valid token.
        $this->repository->expects($this->once())
            ->method('getSharedDocumentToken')
            ->with($id)
            ->willReturn($expectedToken);

        $result = $this->controller->getSharedDocumentToken($id);

        $this->assertEquals(['result' => $expectedToken], $result);
    }

    public function testGetSharedDocumentTokenReturns404WhenNotFound()
    {
        $id = 3;

        // Simulate repository returning null which indicates no shared token found.
        $this->repository->expects($this->once())
            ->method('getSharedDocumentToken')
            ->with($id)
            ->willReturn(null);

        $response = $this->controller->getSharedDocumentToken($id);

        // When no token is found, the controller should return a 404 response with "Document not found".
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Document not found", $response->getContent());
    }
}
