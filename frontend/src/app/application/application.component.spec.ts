import { TestBed, ComponentFixture } from '@angular/core/testing';
import { HttpClientTestingModule } from '@angular/common/http/testing';
import { ApplicationComponent } from './application.component';

describe('ApplicationComponent', () => {
  let fixture: ComponentFixture<ApplicationComponent>;
  let component: ApplicationComponent;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      // Since ApplicationComponent is standalone, import it here.
      imports: [
        ApplicationComponent,
        HttpClientTestingModule
      ],
      // No declarations array needed because it's a standalone component.
      // No providers needed unless you want to override something specifically.
    }).compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ApplicationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
