<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\ArchiveDocumentController;
use App\Repositories\Contracts\ArchiveDocumentRepositoryInterface;

class ArchiveDocumentControllerTest extends TestCase
{
    /**
     * Test that getDocuments returns a JSON response with expected headers.
     *
     * @return void
     */
    public function testGetDocumentsReturnsJsonWithHeaders()
    {
        // Define sample query parameters for the request.
        $queryParams = [
            'pageSize' => 10,
            'skip'     => 5,
            'other'    => 'value'
        ];

        // Create a Request instance with the parameters.
        $request = Request::create('/documents', 'GET', $queryParams);

        // The query object is created in the controller by casting the request data to an object.
        $expectedQueryObject = (object) $queryParams;

        // Define expected result values.
        $expectedDocuments = [
            ['id' => 1, 'name' => 'Document 1'],
            ['id' => 2, 'name' => 'Document 2']
        ];
        $expectedCount = 100;

        // Create a mock for the ArchiveDocumentRepositoryInterface.
        $repositoryMock = $this->createMock(ArchiveDocumentRepositoryInterface::class);

        // Set expectations for getArchiveDocumentsCount.
        $repositoryMock->expects($this->once())
            ->method('getArchiveDocumentsCount')
            ->with($this->callback(function ($query) use ($expectedQueryObject) {
                return isset($query->pageSize, $query->skip) &&
                       $query->pageSize == $expectedQueryObject->pageSize &&
                       $query->skip == $expectedQueryObject->skip;
            }))
            ->willReturn($expectedCount);

        // Set expectations for getArchiveDocuments.
        $repositoryMock->expects($this->once())
            ->method('getArchiveDocuments')
            ->with($this->callback(function ($query) use ($expectedQueryObject) {
                return isset($query->pageSize, $query->skip) &&
                       $query->pageSize == $expectedQueryObject->pageSize &&
                       $query->skip == $expectedQueryObject->skip;
            }))
            ->willReturn($expectedDocuments);

        // Instantiate the controller with the mocked repository.
        $controller = new ArchiveDocumentController($repositoryMock);

        // Call the getDocuments method.
        $response = $controller->getDocuments($request);

        // Assert that the response is JSON and matches the expected content.
        $this->assertEquals(json_encode($expectedDocuments), $response->getContent());
        $this->assertEquals(200, $response->getStatusCode());

        // Verify that the headers are correctly set.
        $this->assertEquals($expectedCount, $response->headers->get('totalCount'));
        $this->assertEquals($queryParams['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryParams['skip'], $response->headers->get('skip'));
    }

    /**
     * Test that restoreDocument calls the repository and returns the expected value.
     *
     * @return void
     */
    public function testRestoreDocumentCallsRepositoryAndReturnsValue()
    {
        $id = 123;
        $expectedResult = "restored"; // You can change this to the expected value as needed.

        // Create a mock for the ArchiveDocumentRepositoryInterface.
        $repositoryMock = $this->createMock(ArchiveDocumentRepositoryInterface::class);

        // Expect restoreDocument to be called with the provided ID.
        $repositoryMock->expects($this->once())
            ->method('restoreDocument')
            ->with($id)
            ->willReturn($expectedResult);

        // Instantiate the controller with the mocked repository.
        $controller = new ArchiveDocumentController($repositoryMock);

        // Call the restoreDocument method.
        $response = $controller->restoreDocument(new Request(), $id);

        // Assert that the response equals the expected result.
        $this->assertEquals($expectedResult, $response);
    }
}
