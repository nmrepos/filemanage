import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { ApplicationComponent } from './application/application.component';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, ApplicationComponent],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'filemanage-frontend';
}
