import { TestBed } from '@angular/core/testing';
import { TranslationService } from './translation.service';
import { TranslateService } from '@ngx-translate/core';
import { of } from 'rxjs';

describe('TranslationService Unit Test', () => {
  let service: TranslationService;
  let translateMock: jasmine.SpyObj<TranslateService>;

  beforeEach(() => {
    translateMock = jasmine.createSpyObj('TranslateService', [
      'setTranslation',
      'addLangs',
      'use',
      'getDefaultLang',
      'instant'
    ]);

    TestBed.configureTestingModule({
      providers: [
        TranslationService,
        { provide: TranslateService, useValue: translateMock }
      ]
    });

    service = TestBed.inject(TranslationService);
  });

  afterEach(() => {
    localStorage.clear();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should load translations and add langs', () => {
    const mockLocale = { lang: 'en', data: { HELLO: 'Hello' } };
    service.loadTranslations(mockLocale);

    expect(translateMock.setTranslation).toHaveBeenCalledWith('en', { HELLO: 'Hello' }, true);
    expect(translateMock.addLangs).toHaveBeenCalledWith(['en']);
  });

  it('should set language and direction to LTR', () => {
    const lang = {
        code: 'en',
        name: 'English',
        isRTL: false,
        imageUrl: 'assets/images/flags/en.png'
      };
    translateMock.use.and.returnValue(of('en'));

    service.setLanguage(lang).subscribe((res) => {
      expect(localStorage.getItem('language')).toBe('en');

      service.lanDir$.subscribe((dir) => {
        expect(dir).toBe('ltr');
      });

      expect(translateMock.use).toHaveBeenCalledWith('en');
    });
  });

  it('should remove language from localStorage', () => {
    localStorage.setItem('language', 'en');
    service.removeLanguage();
    expect(localStorage.getItem('language')).toBeNull();
  });

  it('should return selected language from localStorage', () => {
    localStorage.setItem('language', 'fr');
    const lang = service.getSelectedLanguage();
    expect(lang).toBe('fr');
  });

  it('should fallback to default language if not in localStorage', () => {
    translateMock.getDefaultLang.and.returnValue('en');
    const lang = service.getSelectedLanguage();
    expect(lang).toBe('en');
  });

  it('should call translate.instant() for getValue()', () => {
    translateMock.instant.and.returnValue('Hello');
    const val = service.getValue('HELLO');
    expect(translateMock.instant).toHaveBeenCalledWith('HELLO');
    expect(val).toBe('Hello');
  });
});
