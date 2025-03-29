import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DashboardComponent } from './dashboard.component';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';

describe('DashboardComponent Integration Test', () => {
  let component: DashboardComponent;
  let fixture: ComponentFixture<DashboardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [DashboardComponent],
      schemas: [CUSTOM_ELEMENTS_SCHEMA]
    }).compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create the dashboard component', () => {
    expect(component).toBeTruthy();
  });

  it('should include the DocumentByCategoryChartComponent', () => {
    const chart = fixture.nativeElement.querySelector('app-document-by-category-chart');
    expect(chart).toBeTruthy();
  });

  it('should include the CalenderViewComponent', () => {
    const calendar = fixture.nativeElement.querySelector('app-calender-view');
    expect(calendar).toBeTruthy();
  });
});