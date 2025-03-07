import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Application {
  id: number;
  name: string;
}

@Injectable({
  providedIn: 'root'
})
export class ApplicationService {
  // For local development, point to your Laravel server running on 127.0.0.1:8000.
  private apiUrl = `${environment.apiUrl}/application`;

  constructor(private http: HttpClient) {}

  getApplication(): Observable<Application> {
    return this.http.get<Application>(this.apiUrl);
  }
}
