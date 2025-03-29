import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DocumentByCategoryChartComponent } from './document-by-category-chart.component';
import { DashboradService } from '../dashboard.service';
import { of } from 'rxjs';
import { CUSTOM_ELEMENTS_SCHEMA, NO_ERRORS_SCHEMA } from '@angular/core';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { TranslateModule } from '@ngx-translate/core';

describe('DocumentByCategoryChartComponent Unit Test', () => {
  let component: DocumentByCategoryChartComponent;
  let fixture: ComponentFixture<DocumentByCategoryChartComponent>;
  let mockDashboardService: jasmine.SpyObj<DashboradService>;

  beforeEach(async () => {
    mockDashboardService = jasmine.createSpyObj('DashboradService', ['getDocumentByCategory']);

    await TestBed.configureTestingModule({
      declarations: [DocumentByCategoryChartComponent],
      schemas: [CUSTOM_ELEMENTS_SCHEMA,NO_ERRORS_SCHEMA],
      providers: [
        provideHttpClient(),
        provideHttpClientTesting(),
        { provide: DashboradService, useValue: mockDashboardService }
      ],
      imports:[
        TranslateModule.forRoot()
      ]
    }).compileComponents();

  });

      beforeEach(() => {
          mockDashboardService.getDocumentByCategory.and.returnValue(of());
          fixture = TestBed.createComponent(DocumentByCategoryChartComponent);
          component = fixture.componentInstance;
          fixture.detectChanges();
      });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should transform and assign chart data from service', () => {
    const mockResponse = [
      { categoryName: 'Reports', documentCount: 5 },
      { categoryName: 'Invoices', documentCount: 8 }
    ];
    mockDashboardService.getDocumentByCategory.and.returnValue(of(mockResponse));

    component.getDocumentCategoryChartData();

    expect(component.single.length).toBe(2);
    expect(component.single).toEqual([
      { name: 'Reports', value: 5 },
      { name: 'Invoices', value: 8 }
    ]);
  });
});
