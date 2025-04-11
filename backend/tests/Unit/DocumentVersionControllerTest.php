<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\DocumentVersionController;
use App\Repositories\Contracts\DocumentVersionsRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Exception;

class DocumentVersionControllerTest extends TestCase
{
    protected $repository;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of the DocumentVersionsRepositoryInterface.
        $this->repository = $this->createMock(DocumentVersionsRepositoryInterface::class);
        // Inject the repository mock into the controller.
        $this->controller = new DocumentVersionController($this->repository);
    }

    public function testIndexReturnsJsonResponse()
    {
        $id = 1;
        $fakeDocumentVersions = [
            ['version' => 1, 'name' => 'v1.doc'],
            ['version' => 2, 'name' => 'v2.doc']
        ];

        // Expect the repository's getDocumentversion method to be called with the given ID.
        $this->repository->expects($this->once())
            ->method('getDocumentversion')
            ->with($id)
            ->willReturn($fakeDocumentVersions);

        $response = $this->controller->index($id);

        // Assert that the response is a JSON response with our fake data.
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($fakeDocumentVersions, $response->getData(true));
    }

    public function testSaveNewVersionDocumentSuccess()
    {
        // Create a dummy file stub.
        $dummyFile = $this->createMock(UploadedFile::class);
        // Simulate that getClientOriginalExtension returns 'pdf'
        $dummyFile->expects($this->once())
            ->method('getClientOriginalExtension')
            ->willReturn('pdf');
        // Simulate that storeAs returns a predetermined path.
        $dummyFile->expects($this->once())
            ->method('storeAs')
            ->with(
                'documents',
                $this->callback(function ($filename) {
                    // Check that filename ends with .pdf (we ignore the random part)
                    return substr($filename, -4) === '.pdf';
                }),
                'local'
            )
            ->willReturn('documents/dummy.pdf');

        // Create a Request instance with a file and an optional location.
        $request = new Request();
        // Manually bind our fake file into the request.
        $request->files->set('uploadFile', $dummyFile);
        // Optional: you can also set a custom location; otherwise default is 'local'
        $request->merge(['location' => 'local']);

        // Prepare an expectation for the repository call.
        $repositoryResponse = ['success' => true, 'path' => 'documents/dummy.pdf'];

        $this->repository->expects($this->once())
            ->method('saveNewDocumentVersion')
            ->with($request, 'documents/dummy.pdf')
            ->willReturn($repositoryResponse);

        $response = $this->controller->saveNewVersionDocument($request);

        // Expect HTTP 201 Created with the repository's response in JSON.
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($repositoryResponse, $response->getData(true));
    }

    public function testSaveNewVersionDocumentFailureWhenPathIsEmpty()
    {
        // Create a dummy file stub.
        $dummyFile = $this->createMock(UploadedFile::class);
        $dummyFile->expects($this->once())
            ->method('getClientOriginalExtension')
            ->willReturn('pdf');
        // Simulate failure on storing the file by returning empty string.
        $dummyFile->expects($this->once())
            ->method('storeAs')
            ->with(
                'documents',
                $this->callback(function ($filename) {
                    return substr($filename, -4) === '.pdf';
                }),
                'local'
            )
            ->willReturn('');

        $request = new Request();
        $request->files->set('uploadFile', $dummyFile);
        $request->merge(['location' => 'local']);

        $response = $this->controller->saveNewVersionDocument($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        // The controller returns a 409 conflict if file storage fails.
        $this->assertEquals(409, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertArrayHasKey('message', $data);
        $this->assertStringContainsString('Error in storing document in local', $data['message']);
    }

    public function testSaveNewVersionDocumentFailureWhenExceptionThrown()
    {
        // Create a dummy file stub that throws an exception when storeAs is called.
        $dummyFile = $this->createMock(UploadedFile::class);
        $dummyFile->expects($this->once())
            ->method('getClientOriginalExtension')
            ->willReturn('pdf');
        $dummyFile->expects($this->once())
            ->method('storeAs')
            ->will($this->throwException(new Exception('Storage failed')));

        $request = new Request();
        $request->files->set('uploadFile', $dummyFile);
        $request->merge(['location' => 'local']);

        $response = $this->controller->saveNewVersionDocument($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(409, $response->getStatusCode());
        $data = $response->getData(true);
        $this->assertArrayHasKey('message', $data);
        $this->assertStringContainsString('Error in storing document in local', $data['message']);
    }

    public function testRestoreDocumentVersionReturnsJsonResponse()
    {
        $id = 10;
        $versionId = 5;
        $repositoryResponse = ['restored' => true];

        $this->repository->expects($this->once())
            ->method('restoreDocumentVersion')
            ->with($id, $versionId)
            ->willReturn($repositoryResponse);

        $response = $this->controller->restoreDocumentVersion($id, $versionId);

        // The controller returns a JSON response with HTTP 201 Created.
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($repositoryResponse, $response->getData(true));
    }
}
