// import { ComponentFixture, TestBed, fakeAsync, tick } from '@angular/core/testing';
// import { LoginComponent } from './login.component';
// import { UntypedFormBuilder, ReactiveFormsModule } from '@angular/forms';
// import { Router } from '@angular/router';
// import { SecurityService } from '@core/security/security.service';
// import { ToastrService } from 'ngx-toastr';
// import { TranslationService } from '@core/services/translation.service';
// import { MatDialog } from '@angular/material/dialog';
// import { of, throwError } from 'rxjs';

// describe('LoginComponent', () => {
//   let component: LoginComponent;
//   let fixture: ComponentFixture<LoginComponent>;

//   beforeEach(async () => {
//     const routerSpy = jasmine.createSpyObj('Router', ['navigate']);
//     const securityServiceSpy = jasmine.createSpyObj('SecurityService', ['login', 'hasClaim'], {
//       companyProfile: of({ logoUrl: 'logo.png', bannerUrl: 'banner.png' })
//     });
//     const toastrSpy = jasmine.createSpyObj('ToastrService', ['success', 'error']);
//     const translationServiceStub = { lanDir$: of('ltr') };
//     const renderer2Stub = { addClass: jasmine.createSpy('addClass'), removeClass: jasmine.createSpy('removeClass') };
//     const documentStub = { body: { classList: { add: jasmine.createSpy('add'), remove: jasmine.createSpy('remove') } } };
//     const matDialogSpy = jasmine.createSpyObj('MatDialog', ['closeAll']);

//     await TestBed.configureTestingModule({
//       imports: [ReactiveFormsModule],
//       declarations: [LoginComponent],
//       providers: [
//         UntypedFormBuilder,
//         { provide: Router, useValue: routerSpy },
//         { provide: SecurityService, useValue: securityServiceSpy },
//         { provide: ToastrService, useValue: toastrSpy },
//         { provide: TranslationService, useValue: translationServiceStub },

//         { provide: MatDialog, useValue: matDialogSpy }
//       ]
//     }).compileComponents();

//     fixture = TestBed.createComponent(LoginComponent);
//     component = fixture.componentInstance;
//     // Provide a dummy sub$ to avoid errors (BaseComponent dependency)
//     (component as any).sub$ = { sink: { add: () => {} } };
//     fixture.detectChanges();
//   });

// });
