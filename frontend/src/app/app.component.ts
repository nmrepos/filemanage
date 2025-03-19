import { Component } from '@angular/core';
import { Router, NavigationStart } from '@angular/router';
import { Title } from '@angular/platform-browser';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'app-root',
  template: '<router-outlet></router-outlet>',
  // Optionally, remove styleUrls if no styling is needed.
})
export class AppComponent {
  currentUrl: string = '';

  constructor(
    private router: Router,
    private translate: TranslateService,
    private titleService: Title
  ) {
    // Set up basic translation defaults (remove if translation is not required)
    this.translate.addLangs(['en']);
    this.translate.setDefaultLang('en');

    // Subscribe to router events to update currentUrl and scroll to top on navigation
    this.router.events.subscribe(event => {
      if (event instanceof NavigationStart) {
        this.currentUrl = event.url.substring(event.url.lastIndexOf('/') + 1);
      }
      window.scrollTo(0, 0);
    });
  }
}
