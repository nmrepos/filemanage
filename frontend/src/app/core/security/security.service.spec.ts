import { TestBed, fakeAsync, tick } from '@angular/core/testing';
import { SecurityService } from './security.service';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { ClonerService } from '../services/clone.service';
import { CommonHttpErrorService } from '../error-handler/common-http-error.service';
import { Router } from '@angular/router';
import { UserAuth } from '../domain-classes/user-auth';
import { CompanyProfile } from '../domain-classes/company-profile';
import { of } from 'rxjs';

describe('SecurityService Unit Test', () => {
  let service: SecurityService;
  let httpMock: HttpTestingController;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockCloner: jasmine.SpyObj<ClonerService>;
  let mockErrorHandler: jasmine.SpyObj<CommonHttpErrorService>;

  beforeEach(() => {
    mockRouter = jasmine.createSpyObj('Router', ['navigate']);
    mockCloner = jasmine.createSpyObj('ClonerService', ['deepClone']);
    mockErrorHandler = jasmine.createSpyObj('CommonHttpErrorService', ['handleError']);

    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [
        SecurityService,
        { provide: Router, useValue: mockRouter },
        { provide: ClonerService, useValue: mockCloner },
        { provide: CommonHttpErrorService, useValue: mockErrorHandler }
      ]
    });

    service = TestBed.inject(SecurityService);
    httpMock = TestBed.inject(HttpTestingController);
  });

  afterEach(() => httpMock.verify());

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should login and store auth data', () => {
    const mockUser: UserAuth = {
      isAuthenticated: true,
      user: { id: '1', firstName: '', lastName: '', email: '', userName: 'test', phoneNumber: '' },
      authorisation: { token: 'abc', type: 'bearer' },
      claims: ['dashboard_view_dashboard'],
      tokenTime: new Date(),
      status: ''
    };

    mockCloner.deepClone.and.returnValue(mockUser);

    service.login({ email: 'test@test.com', password: '123' }).subscribe(res => {
      if ('authorisation' in res) {
        expect(res).toEqual(mockUser);
        expect(localStorage.getItem('authObj')).toContain('test');
        expect(localStorage.getItem('bearerToken')).toBe('abc');
      }
    });

    const req = httpMock.expectOne('auth/login');
    expect(req.request.method).toBe('POST');
    req.flush(mockUser);
  });

  it('should refresh token and update auth data', () => {
    const mockUser: UserAuth = {
      isAuthenticated: true,
      user: { id: '1', firstName: '', lastName: '', email: '', userName: 'test', phoneNumber: '' },
      authorisation: { token: 'new-token', type: 'bearer' },
      claims: ['claim'],
      tokenTime: new Date(),
      status: ''
    };

    mockCloner.deepClone.and.returnValue(mockUser);

    service.refresh().subscribe(user => {
      if ('authorisation' in user) {
        expect(user.authorisation.token).toBe('new-token');
        expect(localStorage.getItem('authObj')).toContain('test');
      }
    });

    const req = httpMock.expectOne('auth/refresh');
    expect(req.request.method).toBe('POST');
    req.flush(mockUser);
  });

  it('should correctly check claims using hasClaim()', () => {
    service.securityObject = {
      claims: ['admin', 'view_dashboard']
    } as any;
    expect(service.hasClaim('admin')).toBeTrue();
    expect(service.hasClaim(['admin', 'editor'])).toBeTrue();
    expect(service.hasClaim('unknown')).toBeFalse();
  });

  it('should reset security object and clear localStorage', () => {
    localStorage.setItem('authObj', 'dummy');
    localStorage.setItem('bearerToken', 'dummy');

    service.resetSecurityObject();

    expect(localStorage.getItem('authObj')).toBeNull();
    expect(localStorage.getItem('bearerToken')).toBeNull();
    expect(mockRouter.navigate).toHaveBeenCalledWith(['/login']);
  });
});
