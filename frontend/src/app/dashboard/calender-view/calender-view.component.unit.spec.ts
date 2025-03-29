import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CalenderViewComponent } from './calender-view.component';
import { CUSTOM_ELEMENTS_SCHEMA, NO_ERRORS_SCHEMA } from '@angular/core';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { TranslateModule } from '@ngx-translate/core';
import { CalendarModule, DateAdapter,CalendarEvent } from 'angular-calendar';
import { adapterFactory } from 'angular-calendar/date-adapters/date-fns';
import { MatDialog } from '@angular/material/dialog';
import { DashboradService } from '../dashboard.service';
import { of } from 'rxjs';
import { throwError } from 'rxjs';

describe('CalenderViewComponent Unit Test', () => {
  let component: CalenderViewComponent;
  let fixture: ComponentFixture<CalenderViewComponent>;
  let mockDashboardService: jasmine.SpyObj<DashboradService>;
  let mockDialog: jasmine.SpyObj<MatDialog>;

  beforeEach(async () => {
    mockDashboardService = jasmine.createSpyObj('DashboradService', ['getReminders']);
    mockDialog = jasmine.createSpyObj('MatDialog', ['open']);
    await TestBed.configureTestingModule({
      declarations: [CalenderViewComponent],
      schemas: [CUSTOM_ELEMENTS_SCHEMA,NO_ERRORS_SCHEMA],
      providers: [
        provideHttpClient(),
        provideHttpClientTesting(),
        { provide: DashboradService, useValue: mockDashboardService },
        { provide: MatDialog, useValue: mockDialog }
      ],
      imports:[
        TranslateModule.forRoot(),
        CalendarModule.forRoot({ provide: DateAdapter, useFactory: adapterFactory })
        ]
        }).compileComponents();
    });

    beforeEach(() => {
        mockDashboardService.getReminders.and.returnValue(of([])); // ✅ mock default empty observable
        fixture = TestBed.createComponent(CalenderViewComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should change view date and fetch reminders', () => {
    spyOn(component, 'gerReminders');
    const date = new Date(2025, 2, 15);
    component.viewDateChange(date);
    expect(component.activeDayIsOpen).toBeFalse();
    expect(component.gerReminders).toHaveBeenCalledWith(3, 2025);
  });

  it('should fetch reminders and call addEvent()', () => {
    const mockData = [{ id: 1, start: '2025-03-28T10:00:00', end: '2025-03-28T11:00:00' }];
    spyOn(component, 'addEvent');
    mockDashboardService.getReminders.and.returnValue(of(mockData as any));
    component.gerReminders(3, 2025);
    expect(component.isProcessing).toBeFalse();
    expect(component.addEvent).toHaveBeenCalledWith(mockData as any);
  });

  it('should parse dates and add events correctly', () => {
    const rawData = [
      { id: 1, start: '2025-03-28T10:00:00', end: '2025-03-28T11:00:00' }
    ];
    component.addEvent(rawData as any);
    expect(component.events.length).toBe(1);
    expect(component.events[0].start instanceof Date).toBeTrue();
  });

  it('should open dialog on handleEvent()', () => {
    const mockEvent: CalendarEvent = { id: 1, title: 'Test Event', start: new Date() };
    component.handleEvent('Clicked', mockEvent);
    expect(mockDialog.open).toHaveBeenCalled();
  });
  it('should close day if same day and activeDayIsOpen is true', () => {
    const today = new Date();
    component.viewDate = today;
    component.activeDayIsOpen = true;
    component.dayClicked({ date: today, events: [{ start: today, title: 'Test' }] });
    expect(component.activeDayIsOpen).toBeFalse();
  });

  it('should open day if not same day and there are events', () => {
    const today = new Date();
    const nextDay = new Date(today);
    nextDay.setDate(today.getDate() + 1);
    component.viewDate = today;
    component.activeDayIsOpen = false;
    component.dayClicked({ date: nextDay, events: [{ start: nextDay, title: 'Event' }] });
    expect(component.activeDayIsOpen).toBeTrue();
    expect(component.viewDate).toEqual(nextDay);
  });

  it('should set isProcessing to false if getReminders fails', () => {
    mockDashboardService.getReminders.and.returnValue(throwError(() => new Error('Test error')));
    component.gerReminders(3, 2025);
    expect(component.isProcessing).toBeFalse();
  });
});
