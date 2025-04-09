<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentController;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Repositories\Contracts\DocumentMetaDataRepositoryInterface;
use App\Repositories\Contracts\UserNotificationRepositoryInterface;
use App\Repositories\Contracts\DocumentTokenRepositoryInterface;
use App\Repositories\Contracts\ArchiveDocumentRepositoryInterface;
use App\Repositories\Contracts\DocumentShareableLinkRepositoryInterface;

class DocumentControllerTest extends TestCase
{
    /**
     * Test getDocuments() returns JSON with expected content and headers.
     */
    public function testGetDocuments()
    {
        $queryParams = ['pageSize' => 20, 'skip' => 0, 'filter' => 'test'];
        $request = Request::create('/documents', 'GET', $queryParams);
        $queryObj = (object)$queryParams;
        $expectedDocs = [
            ['id' => 1, 'name' => 'Doc1'],
            ['id' => 2, 'name' => 'Doc2']
        ];
        $expectedCount = 100;

        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('getDocumentsCount')
            ->with($this->callback(function ($q) use ($queryObj) {
                return $q->pageSize == $queryObj->pageSize && $q->skip == $queryObj->skip;
            }))
            ->willReturn($expectedCount);
        $docRepo->expects($this->once())
            ->method('getDocuments')
            ->with($this->callback(function ($q) use ($queryObj) {
                return $q->pageSize == $queryObj->pageSize && $q->skip == $queryObj->skip;
            }))
            ->willReturn($expectedDocs);

        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );

        $response = $controller->getDocuments($request);

        $this->assertEquals(json_encode($expectedDocs), $response->getContent());
        $this->assertEquals($expectedCount, $response->headers->get('totalCount'));
        $this->assertEquals($queryParams['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryParams['skip'], $response->headers->get('skip'));
    }

    /**
     * Test officeviewer() returns 404 when document token is not available.
     */
    public function testOfficeviewerTokenNotAvailable()
    {
        $request = Request::create('/officeviewer', 'GET', []);
        $docTokenRepo = $this->createMock(DocumentTokenRepositoryInterface::class);
        $docTokenRepo->expects($this->once())
            ->method('getDocumentPathByToken')
            ->with(10, $request)
            ->willReturn(false);

        $controller = new DocumentController(
            $this->createMock(DocumentRepositoryInterface::class),
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $docTokenRepo,
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );

        $response = $controller->officeviewer($request, 10);
        $this->assertEquals(404, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Document Not Found.', $data['message']);
    }

    /**
     * Test officeviewer() calls downloadDocument() when token is available and isPublic is false.
     */
    public function testOfficeviewerNonPublic()
    {
        $request = Request::create('/officeviewer', 'GET', ['isPublic' => 'false', 'isVersion' => 'false']);
        $docTokenRepo = $this->createMock(DocumentTokenRepositoryInterface::class);
        $docTokenRepo->expects($this->once())
            ->method('getDocumentPathByToken')
            ->with(10, $request)
            ->willReturn(true);

        // Create a partial mock for DocumentController to override downloadDocument().
        $controller = $this->getMockBuilder(DocumentController::class)
            ->setConstructorArgs([
                $this->createMock(DocumentRepositoryInterface::class),
                $this->createMock(DocumentMetaDataRepositoryInterface::class),
                $this->createMock(UserNotificationRepositoryInterface::class),
                $docTokenRepo,
                $this->createMock(ArchiveDocumentRepositoryInterface::class),
                $this->createMock(DocumentShareableLinkRepositoryInterface::class)
            ])
            ->onlyMethods(['downloadDocument'])
            ->getMock();

        $controller->expects($this->once())
            ->method('downloadDocument')
            ->with(10, 'false')
            ->willReturn("dummy download");

        $response = $controller->officeviewer($request, 10);
        $this->assertEquals("dummy download", $response);
    }

    /**
     * Test archiveDocument() returns repository's result.
     */
    public function testArchiveDocument()
    {
        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('archiveDocument')
            ->with(5)
            ->willReturn('archived');

        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );

        $result = $controller->archiveDocument(5);
        $this->assertEquals('archived', $result);
    }

    /**
     * Test deleteDocument() returns repository's result.
     */
    public function testDeleteDocument()
    {
        $archiveRepo = $this->createMock(ArchiveDocumentRepositoryInterface::class);
        $archiveRepo->expects($this->once())
            ->method('deleteDocument')
            ->with(7)
            ->willReturn('deleted');

        $controller = new DocumentController(
            $this->createMock(DocumentRepositoryInterface::class),
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $archiveRepo,
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );

        $result = $controller->deleteDocument(7);
        $this->assertEquals('deleted', $result);
    }

    /**
     * Test getDocumentMetatags() returns JSON with expected content.
     */
    public function testGetDocumentMetatags()
    {
        $expected = ['tag1', 'tag2'];
        $metaRepo = $this->createMock(DocumentMetaDataRepositoryInterface::class);
        $metaRepo->expects($this->once())
            ->method('getDocumentMetadatas')
            ->with(3)
            ->willReturn($expected);

        $controller = new DocumentController(
            $this->createMock(DocumentRepositoryInterface::class),
            $metaRepo,
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );
        $response = $controller->getDocumentMetatags(3);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($expected), $response->getContent());
    }

    /**
     * Test assignedDocuments() returns JSON with expected content and headers.
     */
    public function testAssignedDocuments()
    {
        $queryParams = ['pageSize' => 10, 'skip' => 0];
        $request = Request::create('/assigned-documents', 'GET', $queryParams);
        $queryObj = (object)$queryParams;
        $expectedData = [['id' => 1], ['id' => 2]];
        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('assignedDocumentsCount')
            ->with($this->callback(function ($q) use ($queryObj) {
                return $q->pageSize == $queryObj->pageSize && $q->skip == $queryObj->skip;
            }))
            ->willReturn(50);
        $docRepo->expects($this->once())
            ->method('assignedDocuments')
            ->with($this->callback(function ($q) use ($queryObj) {
                return $q->pageSize == $queryObj->pageSize && $q->skip == $queryObj->skip;
            }))
            ->willReturn($expectedData);

        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );
        $response = $controller->assignedDocuments($request);
        $this->assertEquals(json_encode($expectedData), $response->getContent());
        $this->assertEquals(50, $response->headers->get('totalCount'));
        $this->assertEquals($queryParams['pageSize'], $response->headers->get('pageSize'));
        $this->assertEquals($queryParams['skip'], $response->headers->get('skip'));
    }

    /**
     * Test getDocumentsByCategoryQuery() returns JSON with expected content.
     */
    public function testGetDocumentsByCategoryQuery()
    {
        $expected = [['id' => 10, 'name' => 'CategoryDocs']];
        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('getDocumentByCategory')
            ->willReturn($expected);

        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );
        $response = $controller->getDocumentsByCategoryQuery();
        $this->assertEquals(json_encode($expected), $response->getContent());
    }

    /**
     * Test getDocumentbyId() marks notifications as read and returns document data.
     */
    public function testGetDocumentbyId()
    {
        $expected = ['id' => 3, 'name' => 'Doc3'];
        $notifRepo = $this->createMock(UserNotificationRepositoryInterface::class);
        $notifRepo->expects($this->once())
            ->method('markAsReadByDocumentId')
            ->with(3);
        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('getDocumentbyId')
            ->with(3)
            ->willReturn($expected);

        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $notifRepo,
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );
        $response = $controller->getDocumentbyId(3);
        $this->assertEquals(json_encode($expected), $response->getContent());
    }

    /**
     * Test getDeepSearchDocuments() returns JSON with expected content.
     */
    public function testGetDeepSearchDocuments()
    {
        $queryParams = ['term' => 'search'];
        $request = Request::create('/deep-search', 'GET', $queryParams);
        $queryObj = (object)$queryParams;
        $expected = [['id' => 5, 'name' => 'DeepDoc']];
        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('getDeepSearchDocuments')
            ->with($this->callback(function($q) use ($queryObj) {
                return $q->term == $queryObj->term;
            }))
            ->willReturn($expected);

        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );
        $response = $controller->getDeepSearchDocuments($request);
        $this->assertEquals(json_encode($expected), $response->getContent());
    }

    /**
     * Test addDOocumentToDeepSearch() returns JSON with expected result.
     */
    public function testAddDOocumentToDeepSearch()
    {
        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('addDOocumentToDeepSearch')
            ->with(8)
            ->willReturn('added');
        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );
        $response = $controller->addDOocumentToDeepSearch(8);
        $this->assertEquals(json_encode('added'), $response->getContent());
    }

    /**
     * Test removeDocumentFromDeepSearch() returns JSON with expected result.
     */
    public function testRemoveDocumentFromDeepSearch()
    {
        $docRepo = $this->createMock(DocumentRepositoryInterface::class);
        $docRepo->expects($this->once())
            ->method('removeDocumentFromDeepSearch')
            ->with(9)
            ->willReturn('removed');
        $controller = new DocumentController(
            $docRepo,
            $this->createMock(DocumentMetaDataRepositoryInterface::class),
            $this->createMock(UserNotificationRepositoryInterface::class),
            $this->createMock(DocumentTokenRepositoryInterface::class),
            $this->createMock(ArchiveDocumentRepositoryInterface::class),
            $this->createMock(DocumentShareableLinkRepositoryInterface::class)
        );
        $response = $controller->removeDocumentFromDeepSearch(9);
        $this->assertEquals(json_encode('removed'), $response->getContent());
    }
}
