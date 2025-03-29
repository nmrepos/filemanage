import { TestBed } from '@angular/core/testing';
import { CommonService } from './common.service';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { CommonHttpErrorService } from '../error-handler/common-http-error.service';
import { DocumentView } from '../domain-classes/document-view';
import { reminderFrequencies } from '../domain-classes/reminder-frequency';

describe('CommonService Unit Test', () => {
  let service: CommonService;
  let httpMock: HttpTestingController;
  let errorHandlerMock: jasmine.SpyObj<CommonHttpErrorService>;

  beforeEach(() => {
    errorHandlerMock = jasmine.createSpyObj('CommonHttpErrorService', ['handleError']);

    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [
        CommonService,
        { provide: CommonHttpErrorService, useValue: errorHandlerMock }
      ]
    });

    service = TestBed.inject(CommonService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => httpMock.verify());

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should GET users from /user', () => {
    service.getUsers().subscribe();
    const req = httpMock.expectOne('user');
    expect(req.request.method).toBe('GET');
    req.flush([]);
  });

  it('should GET roles from /role', () => {
    service.getRoles().subscribe();
    const req = httpMock.expectOne('role');
    expect(req.request.method).toBe('GET');
    req.flush([]);
  });

  it('should GET my reminder by id', () => {
    service.getMyReminder('123').subscribe();
    const req = httpMock.expectOne('reminder/123/myreminder');
    expect(req.request.method).toBe('GET');
    req.flush({});
  });

  it('should GET reminder frequencies as static array', () => {
    service.getReminderFrequency().subscribe(data => {
      expect(data).toEqual(reminderFrequencies);
    });
  });

  it('should POST document audit trail', () => {
    const mockAudit = { documentId: 'abc' } as any;
    service.addDocumentAuditTrail(mockAudit).subscribe();
    const req = httpMock.expectOne('documentAuditTrail');
    expect(req.request.method).toBe('POST');
    req.flush({});
  });

  it('should call downloadDocument with public link', () => {
    const view: DocumentView = {
      documentId: 'abc',
      isFromPublicPreview: true,
      linkPassword: 'pass'
    } as any;

    service.downloadDocument(view).subscribe();
    const req = httpMock.expectOne(`document-sharable-link/abc/download?password=pass`);
    expect(req.request.method).toBe('GET');
    req.flush(new Blob());
  });

  it('should call downloadDocument with internal version', () => {
    const view: DocumentView = {
      documentId: 'abc',
      isFromPublicPreview: false,
      isVersion: 1
    } as any;

    service.downloadDocument(view).subscribe();
    const req = httpMock.expectOne(`document/abc/download/1`);
    expect(req.request.method).toBe('GET');
    req.flush(new Blob());
  });

  it('should POST readDocument with public preview', () => {
    const view: DocumentView = {
      documentId: 'abc',
      isFromPublicPreview: true,
      linkPassword: '123'
    } as any;

    service.readDocument(view).subscribe();
    const req = httpMock.expectOne(`document-sharable-link/abc/readText?password=123`);
    expect(req.request.method).toBe('POST');
    req.flush({});
  });

  it('should GET readDocument with private preview', () => {
    const view: DocumentView = {
      documentId: 'abc',
      isFromPublicPreview: false,
      isVersion: 1
    } as any;

    service.readDocument(view).subscribe();
    const req = httpMock.expectOne(`document/abc/readText/1`);
    expect(req.request.method).toBe('GET');
    req.flush({});
  });

  it('should GET document token', () => {
    const view: DocumentView = {
      documentId: 'abc',
      isFromPublicPreview: false
    } as any;

    service.getDocumentToken(view).subscribe();
    const req = httpMock.expectOne(`documentToken/abc/token`);
    expect(req.request.method).toBe('GET');
    req.flush({});
  });

  it('should DELETE token by id', () => {
    service.deleteDocumentToken('xyz').subscribe();
    const req = httpMock.expectOne('documentToken/xyz');
    expect(req.request.method).toBe('DELETE');
    req.flush(true);
  });

  it('should GET helper text by code', () => {
    service.getPageHelperText('abc').subscribe();
    const req = httpMock.expectOne('page-helper/abc/code');
    expect(req.request.method).toBe('GET');
    req.flush({});
  });
});
