import { TestBed, ComponentFixture } from '@angular/core/testing';
import { LoginComponent } from './login.component';
import { HttpClientModule } from '@angular/common/http';  // ✅ Add this
import { FormsModule } from '@angular/forms';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';
import { of, throwError } from 'rxjs';

describe('LoginComponent', () => {
  let component: LoginComponent;
  let fixture: ComponentFixture<LoginComponent>;
  let authServiceSpy: jasmine.SpyObj<AuthService>;
  let routerSpy: jasmine.SpyObj<Router>;

  beforeEach(async () => {
    authServiceSpy = jasmine.createSpyObj('AuthService', ['login']);
    routerSpy = jasmine.createSpyObj('Router', ['navigate']);

    await TestBed.configureTestingModule({
      declarations: [LoginComponent],
      imports: [FormsModule],
      providers: [
        { provide: AuthService, useValue: authServiceSpy },
        { provide: Router, useValue: routerSpy }
      ]
    }).compileComponents();

  });

  beforeEach(() => {
    fixture = TestBed.createComponent(LoginComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });
  
  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should initialize email and password as empty strings', () => {
    expect(component.email).toBe('');
    expect(component.password).toBe('');
  });

  it('should handle successful login', () => {
    authServiceSpy.login.and.returnValue(of({}));
    component.email = 'user@example.com';
    component.password = 'secret';
    component.onSubmit();
    expect(authServiceSpy.login).toHaveBeenCalledWith('user@example.com', 'secret', '', '');
    expect(routerSpy.navigate).toHaveBeenCalledWith(['/dashboard']);
  });

  it('should handle login failure and set errorMessage', () => {
    const errorResponse = { error: { message: 'Invalid credentials' } };
    authServiceSpy.login.and.returnValue(throwError(() => errorResponse));
    component.email = 'user@example.com';
    component.password = 'wrongpassword';
    component.onSubmit();
    expect(component.errorMessage).toBe('Invalid credentials');
  });

});
