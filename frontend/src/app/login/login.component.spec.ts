import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LoginComponent } from './login.component';
import { UntypedFormBuilder, ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { SecurityService } from '@core/security/security.service';
import { ToastrService } from 'ngx-toastr';
import { TranslationService } from '@core/services/translation.service';
import { of, Subject, throwError } from 'rxjs';
import { Direction } from '@angular/cdk/bidi';
import { DOCUMENT } from '@angular/common';
import { Renderer2 } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { TranslateModule } from '@ngx-translate/core';
import { CUSTOM_ELEMENTS_SCHEMA, NO_ERRORS_SCHEMA } from '@angular/core';

describe('LoginComponent Unit Test', () => {
  let component: LoginComponent;
  let fixture: ComponentFixture<LoginComponent>;

  // Mocks
  let securityServiceMock: any;
  let toastrServiceMock: any;
  let routerMock: any;
  let translationServiceMock: any;
  let rendererMock: any;
  let dialogMock: any;
  let lanDirSubject: Subject<Direction>;

  beforeEach(async () => {
    lanDirSubject = new Subject<Direction>(); // 👈 initialize it

    securityServiceMock = {
      login: jasmine.createSpy(),
      companyProfile: of({ logoUrl: 'logo.png', bannerUrl: 'banner.png' }),
      hasClaim: () => true,
    };
    toastrServiceMock = jasmine.createSpyObj('ToastrService', ['success', 'error']);
    routerMock = jasmine.createSpyObj('Router', ['navigate']);
    translationServiceMock = {
      lanDir$: lanDirSubject
    };
    rendererMock = jasmine.createSpyObj('Renderer2', ['addClass', 'removeClass']);
    dialogMock = jasmine.createSpyObj('MatDialog', ['closeAll']);
    await TestBed.configureTestingModule({
      declarations: [LoginComponent],
      schemas:[
        CUSTOM_ELEMENTS_SCHEMA,NO_ERRORS_SCHEMA
      ],
      imports: [
        ReactiveFormsModule,
        TranslateModule.forRoot()
      ],
      providers: [
        UntypedFormBuilder,
        { provide: Router, useValue: routerMock },
        { provide: SecurityService, useValue: securityServiceMock },
        { provide: ToastrService, useValue: toastrServiceMock },
        { provide: TranslationService, useValue: translationServiceMock },
        { provide: Renderer2, useValue: rendererMock },
        { provide: MatDialog, useValue: dialogMock },
        { provide: DOCUMENT, useValue: document },
      ],
    }).compileComponents();

  });

  beforeEach(() => {
      fixture = TestBed.createComponent(LoginComponent);
      component = fixture.componentInstance;
      fixture.detectChanges();
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should initialize the form on init', () => {
    expect(component.loginFormGroup).toBeDefined();
    expect(component.loginFormGroup.controls['userName']).toBeDefined();
    expect(component.loginFormGroup.controls['password']).toBeDefined();
  });

  it('should populate logo and banner on company profile subscription', () => {
    component.companyProfileSubscription();
    expect(component.logoImage).toBe('logo.png');
    expect(component.bannerImage).toBe('banner.png');
  });
  it('should submit form and login user successfully', () => {
    const loginResponse$ = of({});
    securityServiceMock.login.and.returnValue(loginResponse$);
    spyOn(securityServiceMock, 'hasClaim').and.returnValue(true);

    component.loginFormGroup.setValue({
      userName: 'test@email.com',
      password: '123456'
    });

    component.onLoginSubmit();

    expect(component.isLoading).toBeFalse();
    expect(toastrServiceMock.success).toHaveBeenCalledWith('User login successfully.');
    expect(routerMock.navigate).toHaveBeenCalledWith(['/dashboard']);
  });

  it('should handle login error and show toast message', () => {
    const errorObj = { error: { message: 'Invalid credentials' } };
    securityServiceMock.login.and.returnValue(throwError(() => errorObj));

    component.loginFormGroup.setValue({
      userName: 'test@email.com',
      password: 'wrongpassword'
    });

    component.onLoginSubmit();

    expect(component.isLoading).toBeFalse();
    expect(toastrServiceMock.error).toHaveBeenCalledWith('Invalid credentials');
  });

  it('should mark form as touched if form is invalid', () => {
    spyOn(component.loginFormGroup, 'markAllAsTouched');
    component.loginFormGroup.setValue({
      userName: '',
      password: ''
    });

    component.onLoginSubmit();

    expect(component.loginFormGroup.markAllAsTouched).toHaveBeenCalled();
  });

  it('should navigate to root (/) if user does not have dashboard claim', () => {
    const loginResponse$ = of({});
    securityServiceMock.login.and.returnValue(loginResponse$);
    // Simulate no dashboard claim
    spyOn(securityServiceMock, 'hasClaim').and.returnValue(false);
    component.loginFormGroup.setValue({
      userName: 'test@email.com',
      password: '123456'
    });
    component.onLoginSubmit();
    expect(component.isLoading).toBeFalse();
    expect(toastrServiceMock.success).toHaveBeenCalledWith('User login successfully.');
    expect(routerMock.navigate).toHaveBeenCalledWith(['/']);
  });
  it('should close all dialogs, initialize form, and get geolocation on init', () => {
    // Arrange
    const mockPosition = {
      coords: {
        latitude: 50,
        longitude: 100,
        accuracy: 1,
        altitude: null,
        altitudeAccuracy: null,
        heading: null,
        speed: null
      },
      timestamp: Date.now()
    } as GeolocationPosition;
    spyOn(navigator.geolocation, 'getCurrentPosition').and.callFake((successCallback) => {
      successCallback(mockPosition);
    });
    const closeAllSpy = dialogMock.closeAll;
    component.ngOnInit();
    expect(closeAllSpy).toHaveBeenCalled();
    expect(component.loginFormGroup).toBeDefined();
    expect(component.lat).toBe(50);
    expect(component.lng).toBe(100);
  });
});