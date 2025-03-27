import { ComponentFixture, TestBed } from '@angular/core/testing';
import { provideHttpClient } from '@angular/common/http';
import { provideHttpClientTesting } from '@angular/common/http/testing';
import { LoginComponent } from './login.component';
import { ToastrModule } from 'ngx-toastr';
import { TranslateModule } from '@ngx-translate/core';
import { Router, Event as RouterEvent } from '@angular/router';
import { Subject } from 'rxjs';
import { Title } from '@angular/platform-browser';
import { TranslateService } from '@ngx-translate/core';
import { of } from 'rxjs';
import { NO_ERRORS_SCHEMA } from '@angular/core';
  class FakeTranslationService {
    lanDir$ = of('ltr');
    get(key: string | string[]): any {
      // Simply return the key as a string inside an Observable.
      return of(key);
    }
    instant(key: string | string[]): any {
      return key;
    }
    onLangChange = of({ lang: 'en', translations: {} });
    onTranslationChange = of({ translations: {} });
    onDefaultLangChange = of({ lang: 'en' });
  }

describe('LoginComponent', () => {
  let component: LoginComponent;
  let fixture: ComponentFixture<LoginComponent>;
  let routerEvents$: Subject<RouterEvent>;
  let routerSpy: Partial<Router>;
  let titleServiceSpy: Partial<Title>;

    beforeEach(async () => {
        // Create a subject to simulate router events.
        routerEvents$ = new Subject<RouterEvent>();
        routerSpy = {
            events: routerEvents$.asObservable(),
            navigate: jasmine.createSpy('navigate')
        };

        titleServiceSpy = {
            setTitle: jasmine.createSpy('setTitle')
        };

        await TestBed.configureTestingModule({
            declarations: [LoginComponent],
            providers: [
            provideHttpClient(),
            provideHttpClientTesting(),
            { provide: Router,useValue: routerSpy },
            { provide: TranslateService,useClass: FakeTranslationService },
            { provide: Title,useValue: titleServiceSpy }
            ],
            imports: [
                ToastrModule.forRoot({
                    positionClass: 'toast-bottom-right'
                  }),
                  TranslateModule.forRoot()
            ],
            schemas: [NO_ERRORS_SCHEMA]

        }).compileComponents();
    });

  beforeEach(() => {
    fixture = TestBed.createComponent(LoginComponent);
    component = fixture.componentInstance;
  });

  it('should create the component', () => {
    expect(component).toBeTruthy();
  });

  it('should create the login form on init', () => {
    component.ngOnInit();
    expect(component.loginFormGroup).toBeDefined();
    expect(component.loginFormGroup.controls['userName']).toBeDefined();
    expect(component.loginFormGroup.controls['password']).toBeDefined();
  });

  it('should validate email input correctly', () => {
    // Ensure ngOnInit has been called to initialize the form.
    component.ngOnInit();
    fixture.detectChanges();
    const emailControl = component.loginFormGroup.get('userName');
    // Check required error.
    emailControl.setValue('');
    emailControl.markAsTouched();
    fixture.detectChanges();
    let errorEl = fixture.nativeElement.querySelector('.text-danger');
    expect(errorEl.textContent).toContain('EMAIL_IS_REQUIRED');

    // Check email format error.
    emailControl.setValue('invalid-email');
    emailControl.markAsTouched();
    fixture.detectChanges();
    errorEl = fixture.nativeElement.querySelector('.text-danger');
    expect(errorEl.textContent).toContain('PLEASE_ENTER_VALID_EMAIL');
    // Check valid email input; error element should not be present.
    emailControl.setValue('test@example.com');
    emailControl.markAsUntouched();
    fixture.detectChanges();
    errorEl = fixture.nativeElement.querySelector('.text-danger');
    expect(errorEl).toBeNull();
  });
});