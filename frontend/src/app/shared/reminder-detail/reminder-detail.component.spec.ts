import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ReminderDetailComponent } from './reminder-detail.component';
import { UntypedFormBuilder, ReactiveFormsModule } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { CommonService } from '@core/services/common.service';
import { of } from 'rxjs';
import { Reminder } from '@core/domain-classes/reminder';
import { ReminderFrequency } from '@core/domain-classes/reminder-frequency';
import { Quarter } from '@core/domain-classes/quarter.enum';
import { Frequency } from '@core/domain-classes/frequency.enum';
import { TranslateModule } from '@ngx-translate/core';

describe('ReminderDetailComponent Unit Test', () => {
  let component: ReminderDetailComponent;
  let fixture: ComponentFixture<ReminderDetailComponent>;
  let mockCommonService: jasmine.SpyObj<CommonService>;
  let mockDialogRef: jasmine.SpyObj<MatDialogRef<ReminderDetailComponent>>;

  beforeEach(async () => {
    mockCommonService = jasmine.createSpyObj('CommonService', ['getReminderFrequency', 'getMyReminder']);
    mockDialogRef = jasmine.createSpyObj('MatDialogRef', ['close']);

    await TestBed.configureTestingModule({
        imports: [
          TranslateModule.forRoot(),
          ReminderDetailComponent,
          ReactiveFormsModule
        ],
        providers: [
          UntypedFormBuilder,
          { provide: CommonService, useValue: mockCommonService },
          { provide: MAT_DIALOG_DATA, useValue: '123' },
          { provide: MatDialogRef, useValue: mockDialogRef }
        ]
      }).compileComponents();
    fixture = TestBed.createComponent(ReminderDetailComponent);
    component = fixture.componentInstance;
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should create form with expected controls', () => {
    component.createReminderForm();
    const controls = component.reminderForm.controls;
    expect(controls['subject']).toBeTruthy();
    expect(controls['message']).toBeTruthy();
    expect(controls['startDate']).toBeTruthy();
  });

  it('should fetch reminder and patch values', () => {
    const mockReminder: Reminder = {
      id: '123',
      subject: 'Test',
      message: 'Hello',
      startDate: new Date(),
      dailyReminders: [],
      quarterlyReminders: [],
      halfYearlyReminders: [],
      reminderUsers: [],
      isRepeated: false
    } as any;
    mockCommonService.getMyReminder.and.returnValue(of(mockReminder));
    component.createReminderForm();
    component.getReminder();

    expect(component.reminder.subject).toBe('Test');
    expect(component.reminderForm.get('subject').value).toBe('Test');
  });

  it('should add daily reminders on frequency change', () => {
    component.createReminderForm();
    component.reminderForm.get('frequency').setValue(Frequency.Daily.toString());
    component.onFrequencyChange();
    expect(component.reminderForm.contains('dailyReminders')).toBeTrue();
  });

  it('should add quarterly reminders on frequency change', () => {
    component.createReminderForm();
    component.reminderForm.get('frequency').setValue(Frequency.Quarterly.toString());
    component.onFrequencyChange();
    expect(component.reminderForm.contains('quarterlyReminders')).toBeTrue();
  });

  it('should add half-yearly reminders on frequency change', () => {
    component.createReminderForm();
    component.reminderForm.get('frequency').setValue(Frequency.HalfYearly.toString());
    component.onFrequencyChange();
    expect(component.reminderForm.contains('halfYearlyReminders')).toBeTrue();
  });
  it('should close dialog on closeDialog()', () => {
    component.closeDialog();
    expect(mockDialogRef.close).toHaveBeenCalled();
  });
});
