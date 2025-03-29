import { TestBed } from '@angular/core/testing';
import { CommonHttpErrorService } from './common-http-error.service';
import { HttpErrorResponse } from '@angular/common/http';
import { CommonError } from './common-error';

describe('CommonHttpErrorService Unit Test', () => {
  let service: CommonHttpErrorService;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CommonHttpErrorService]
    });

    service = TestBed.inject(CommonHttpErrorService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should transform HttpErrorResponse into CommonError', (done) => {
    spyOn(console, 'error'); // suppress console output

    const mockErrorResponse = new HttpErrorResponse({
      error: { email: 'Invalid email', password: 'Required' },
      status: 400,
      statusText: 'Bad Request'
    });

    service.handleError(mockErrorResponse).subscribe({
      next: () => fail('Expected error to be thrown'),
      error: (err: CommonError) => {
        expect(err.code).toBe(400);
        expect(err.statusText).toBe('Bad Request');
        expect(err.messages).toContain('Invalid email');
        expect(err.messages).toContain('Required');
        expect(err.friendlyMessage).toBe('Error from service');
        expect(console.error).toHaveBeenCalledWith(mockErrorResponse);
        done();
      }
    });
  });
});
