import { ComponentFixture, TestBed } from '@angular/core/testing';
import { DashboardComponent } from './dashboard.component';
import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';

describe('DashboardComponent', () => {
  let component: DashboardComponent;
  let fixture: ComponentFixture<DashboardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [DashboardComponent],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    }).compileComponents();
});

  beforeEach(() => {
    fixture = TestBed.createComponent(DashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should contain the document by category element', () => {
    const compiled = fixture.nativeElement as HTMLElement;
    const chartElement = compiled.querySelector('app-document-by-category-chart');
    expect(chartElement).toBeTruthy();
  });

  it('should contain the reminders element', () => {
    const compiled = fixture.nativeElement as HTMLElement;
    const chartElement = compiled.querySelector('app-calender-view');
    expect(chartElement).toBeTruthy();
  });
});
