<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentAuditTrailController;
use App\Repositories\Contracts\DocumentAuditTrailRepositoryInterface;

class DocumentAuditTrailControllerTest extends TestCase
{
    /**
     * Test that getDocumentAuditTrails() returns the expected JSON response
     * with the proper headers.
     */
    public function testGetDocumentAuditTrails()
    {
        // Define test request query parameters.
        $queryParams = [
            'pageSize' => 10,
            'skip'     => 0,
            'filter'   => 'example'
        ];
        $request = Request::create('/audit-trails', 'GET', $queryParams);
        
        // The repository will receive an object-casted version of the request data.
        $expectedQueryObject = (object) $queryParams;

        // Define expected results from the repository.
        $expectedAuditTrails = [
            ['id' => 1, 'action' => 'create', 'documentId' => 101],
            ['id' => 2, 'action' => 'update', 'documentId' => 102],
        ];
        $expectedCount = 50;

        // Create a mock of the repository.
        $repositoryMock = $this->createMock(DocumentAuditTrailRepositoryInterface::class);
        // Set expectation for the getDocumentAuditTrailsCount() method.
        $repositoryMock->expects($this->once())
            ->method('getDocumentAuditTrailsCount')
            ->with($this->callback(function ($query) use ($expectedQueryObject) {
                return isset($query->pageSize, $query->skip) &&
                       $query->pageSize == $expectedQueryObject->pageSize &&
                       $query->skip == $expectedQueryObject->skip;
            }))
            ->willReturn($expectedCount);
        // Set expectation for the getDocumentAuditTrails() method.
        $repositoryMock->expects($this->once())
            ->method('getDocumentAuditTrails')
            ->with($this->callback(function ($query) use ($expectedQueryObject) {
                return isset($query->pageSize, $query->skip) &&
                       $query->pageSize == $expectedQueryObject->pageSize &&
                       $query->skip == $expectedQueryObject->skip;
            }))
            ->willReturn($expectedAuditTrails);

        // Instantiate controller with the mock.
        $controller = new DocumentAuditTrailController($repositoryMock);

        // Call the controller method.
        $response = $controller->getDocumentAuditTrails($request);

        // Verify that the JSON response content is as expected.
        $this->assertEquals(json_encode($expectedAuditTrails), $response->getContent());
        // And that the headers are properly added.
        $this->assertEquals($expectedCount, $response->headers->get('totalCount'));
        $this->assertEquals($queryParams['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryParams['skip'], $response->headers->get('skip'));
    }

    /**
     * Test that saveDocumentAuditTrail() returns the repository's output as JSON.
     */
    public function testSaveDocumentAuditTrail()
    {
        // Prepare sample input data.
        $inputData = [
            'documentId' => 101,
            'action' => 'create',
            'userId' => 5,
            'timestamp' => '2023-07-01 10:00:00'
        ];
        $request = Request::create('/audit-trails/save', 'POST', $inputData);
        
        // Expected output from the repository.
        $expectedResult = ['id' => 20, 'documentId' => 101, 'action' => 'create', 'userId' => 5];
        
        // Create a repository mock.
        $repositoryMock = $this->createMock(DocumentAuditTrailRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('saveDocumentAuditTrail')
            ->with($request)
            ->willReturn($expectedResult);
        
        // Instantiate the controller.
        $controller = new DocumentAuditTrailController($repositoryMock);

        // Call the controller method.
        $response = $controller->saveDocumentAuditTrail($request);

        // Assert that a JSON response is returned with the expected result.
        $this->assertEquals(json_encode($expectedResult), $response->getContent());
    }
}
