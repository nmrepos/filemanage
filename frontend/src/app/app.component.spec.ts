import { ComponentFixture, TestBed } from '@angular/core/testing';
import { AppComponent } from './app.component';
import { Router, NavigationStart, Event as RouterEvent } from '@angular/router';
import { Subject } from 'rxjs';
import { Title } from '@angular/platform-browser';
import { TranslateService } from '@ngx-translate/core';
import { RouterTestingModule } from '@angular/router/testing';

describe('AppComponent', () => {
  let component: AppComponent;
  let fixture: ComponentFixture<AppComponent>;
  let routerEvents$: Subject<RouterEvent>;
  let routerSpy: Partial<Router>;
  let translateServiceSpy: Partial<TranslateService>;
  let titleServiceSpy: Partial<Title>;

  beforeEach(async () => {
    // Create a subject to simulate router events.
    routerEvents$ = new Subject<RouterEvent>();

    // Create mock implementations
    routerSpy = {
      events: routerEvents$.asObservable(),
      navigate: jasmine.createSpy('navigate')
    };

    translateServiceSpy = {
      addLangs: jasmine.createSpy('addLangs'),
      setDefaultLang: jasmine.createSpy('setDefaultLang')
    };

    titleServiceSpy = {
      setTitle: jasmine.createSpy('setTitle')
    };

    await TestBed.configureTestingModule({
      declarations: [AppComponent],
      providers: [
        { provide: Router, useValue: routerSpy },
        { provide: TranslateService, useValue: translateServiceSpy },
        { provide: Title, useValue: titleServiceSpy }
      ],
      imports: [RouterTestingModule]
    }).compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(AppComponent);
    component = fixture.componentInstance;
    fixture.detectChanges(); // This triggers the component constructor and subscription
  });

  it('should create the app component', () => {
    expect(component).toBeTruthy();
  });

  it('should set translation defaults on initialization', () => {
    expect(translateServiceSpy.addLangs).toHaveBeenCalledWith(['en']);
    expect(translateServiceSpy.setDefaultLang).toHaveBeenCalledWith('en');
  });

  it('should update currentUrl on NavigationStart event', () => {
    const testUrl = '/some/path';
    // Simulate a NavigationStart event.
    routerEvents$.next(new NavigationStart(1, testUrl));
    expect(component.currentUrl).toEqual(testUrl.substring(testUrl.lastIndexOf('/') + 1));
  });

  it('should call window.scrollTo when a router event occurs', () => {
    // Spy on window.scrollTo
    spyOn(window, 'scrollTo');
    routerEvents$.next(new NavigationStart(1, '/another/path'));
    expect(window.scrollTo).toHaveBeenCalledWith(0, 0);
  });
});
