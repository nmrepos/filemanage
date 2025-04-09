<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentSharebaleLinkController;
use App\Repositories\Contracts\DocumentShareableLinkRepositoryInterface;

class DocumentSharebaleLinkControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a mock of the repository interface
        $this->repository = $this->createMock(DocumentShareableLinkRepositoryInterface::class);
        // Inject the mock into the controller
        $this->controller = new DocumentSharebaleLinkController($this->repository);
    }

    public function testGetReturnsRepositoryData()
    {
        $id = 123;
        $expectedOutput = ['link' => 'example_link'];

        // Expect that the getLink method is called with the given id and returns the expected output
        $this->repository->expects($this->once())
            ->method('getLink')
            ->with($id)
            ->willReturn($expectedOutput);

        // Call the controller method and assert the returned data
        $result = $this->controller->get($id);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testCreateOrUpdateValidationFails()
    {
        // Provide an empty request to trigger validation failure (documentId is required)
        $request = new Request([]);
        
        // Call the method that should trigger a validation error
        $response = $this->controller->createOrUpdate($request);

        // Get the JSON response data as an array
        $responseData = $response->getData(true);

        // Assert that the response status code is 409 (Conflict) and error messages are present
        $this->assertEquals(409, $response->getStatusCode());
        $this->assertNotEmpty($responseData);
    }

    public function testCreateOrUpdateValidationPasses()
    {
        $data = ['documentId' => 456, 'extra_field' => 'value'];
        $request = new Request($data);
        $expectedOutput = ['success' => true];

        // When validation passes, the repository's createOrUpdateLink method should be called with the request
        $this->repository->expects($this->once())
            ->method('createOrUpdateLink')
            ->with($request)
            ->willReturn($expectedOutput);

        // Call the controller method and assert that it returns the repository's output directly
        $response = $this->controller->createOrUpdate($request);
        // Note: Since the controller doesn't wrap the success case into a JSON response,
        // it directly returns the expected output.
        $this->assertEquals($expectedOutput, $response);
    }

    public function testDeleteReturnsRepositoryData()
    {
        $id = 789;
        $expectedOutput = ['deleted' => true];

        $this->repository->expects($this->once())
            ->method('deleteLink')
            ->with($id)
            ->willReturn($expectedOutput);

        $result = $this->controller->delete($id);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testGetLinkInfoByCodeReturnsRepositoryData()
    {
        $code = 'abc123';
        $expectedOutput = ['info' => 'sample_info'];

        $this->repository->expects($this->once())
            ->method('getLinkInfoByCode')
            ->with($code)
            ->willReturn($expectedOutput);

        $result = $this->controller->getLinkInfoByCode($code);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testGetDocumentByCodeReturnsRepositoryData()
    {
        $code = 'xyz789';
        $expectedOutput = ['document' => 'sample_document'];

        $this->repository->expects($this->once())
            ->method('getDocumentByCode')
            ->with($code)
            ->willReturn($expectedOutput);

        $result = $this->controller->getDocumentByCode($code);
        $this->assertEquals($expectedOutput, $result);
    }

    public function testValidatePasswordReturnsRepositoryData()
    {
        $code = 'abc123';
        $password = 'secret';
        $expectedOutput = ['valid' => true];

        $this->repository->expects($this->once())
            ->method('validatePassword')
            ->with($code, $password)
            ->willReturn($expectedOutput);

        $result = $this->controller->validatePassword($code, $password);
        $this->assertEquals($expectedOutput, $result);
    }
}
