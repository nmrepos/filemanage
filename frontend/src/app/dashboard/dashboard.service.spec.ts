import { TestBed } from '@angular/core/testing';
import { DashboradService } from './dashboard.service';
import { HttpTestingController } from '@angular/common/http/testing';
import { CommonHttpErrorService } from '@core/error-handler/common-http-error.service';
import { CalenderReminderDto } from '@core/domain-classes/calender-reminder';
import { DocumentByCategory } from '@core/domain-classes/document-by-category';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';

describe('DashboradService Unit Test', () => {
  let service: DashboradService;
  let httpMock: HttpTestingController;
  let mockErrorHandler: jasmine.SpyObj<CommonHttpErrorService>;

  beforeEach(() => {
    mockErrorHandler = jasmine.createSpyObj('CommonHttpErrorService', ['handleError']);

    TestBed.configureTestingModule({
      imports: [],
      providers: [
        provideHttpClient(),
        provideHttpClientTesting(),
        DashboradService,
        { provide: CommonHttpErrorService, useValue: mockErrorHandler }
      ]
    });

    service = TestBed.inject(DashboradService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => {
    httpMock.verify(); // ensures no unmatched requests
  });

  it('should fetch document by category (GET)', () => {
    const mockData: DocumentByCategory[] = [
      { categoryName: 'Reports', documentCount: 5 }
    ];
    service.getDocumentByCategory().subscribe(data => {
      expect(data).toEqual(mockData);
    });
    const req = httpMock.expectOne('Dashboard/GetDocumentByCategory');
    expect(req.request.method).toBe('GET');
    req.flush(mockData);
  });

  it('should fetch reminders for given month and year (GET)', () => {
    const mockData: CalenderReminderDto[] = [
      { title: 'Meeting', start: new Date(), end: new Date() }
    ];

    service.getReminders(3, 2025).subscribe(data => {
      expect(data).toEqual(mockData);
    });

    const req = httpMock.expectOne('dashboard/reminders/3/2025');
    expect(req.request.method).toBe('GET');
    req.flush(mockData);
  });

});
