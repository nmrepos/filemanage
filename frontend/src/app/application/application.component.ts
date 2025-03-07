import { Component, OnInit } from '@angular/core';
import { ApplicationService, Application } from '../services/application.service';

@Component({
  selector: 'app-application',
  template: `
    <h1>Application Name: {{ app?.name }}</h1>
  `,
  styles: []
})
export class ApplicationComponent implements OnInit {
  app: Application | undefined;

  constructor(private applicationService: ApplicationService) { }

  ngOnInit(): void {
    this.applicationService.getApplication().subscribe(data => {
      this.app = data;
    });
  }
}
