/* istanbul ignore next @preserve */
import { Injectable } from '@angular/core';
import {
  HttpClient,
  HttpEvent,
  HttpParams,
  HttpResponse,
} from '@angular/common/http';
import { CommonError } from '@core/error-handler/common-error';
import { CommonHttpErrorService } from '@core/error-handler/common-http-error.service';
import { Observable, of } from 'rxjs';
import { User } from '@core/domain-classes/user';
import { catchError } from 'rxjs/operators';
import { Role } from '@core/domain-classes/role';
import { DocumentAuditTrail } from '@core/domain-classes/document-audit-trail';
import {
  reminderFrequencies,
  ReminderFrequency,
} from '@core/domain-classes/reminder-frequency';
import { ReminderResourceParameter } from '@core/domain-classes/reminder-resource-parameter';
import { Reminder } from '@core/domain-classes/reminder';
import { DocumentView } from '@core/domain-classes/document-view';
import { PageHelper } from '@core/domain-classes/page-helper';

@Injectable({ providedIn: 'root' })
export class CommonService {
  constructor(
    private httpClient: HttpClient,
    private commonHttpErrorService: CommonHttpErrorService
  ) {}

  getUsers(): Observable<User[] | CommonError> {
    const url = `user`;
    return this.httpClient
      .get<User[]>(url)
      .pipe(catchError(this.commonHttpErrorService.handleError));
  }
  /* istanbul ignore next @preserve */
  getUsersForDropdown(): Observable<User[] | CommonError> {
    const url = `user-dropdown`;
    return this.httpClient
      .get<User[]>(url)
      .pipe(catchError(this.commonHttpErrorService.handleError));
  }

  getRoles(): Observable<Role[] | CommonError> {
    const url = `role`;
    return this.httpClient
      .get<Role[]>(url)
      .pipe(catchError(this.commonHttpErrorService.handleError));
  }
  /* istanbul ignore next @preserve */
  getRolesForDropdown(): Observable<Role[] | CommonError> {
    const url = 'role-dropdown';
    return this.httpClient
      .get<Role[]>(url)
      .pipe(catchError(this.commonHttpErrorService.handleError));
  }

  getMyReminder(id: string): Observable<Reminder | CommonError> {
    const url = `reminder/${id}/myreminder`;
    return this.httpClient
      .get<Reminder>(url)
      .pipe(catchError(this.commonHttpErrorService.handleError));
  }
  /* istanbul ignore next @preserve */
  getReminder(id: string): Observable<Reminder | CommonError> {
    const url = `reminder/${id}`;
    return this.httpClient
      .get<Reminder>(url)
      .pipe(catchError(this.commonHttpErrorService.handleError));
  }

  addDocumentAuditTrail(
    documentAuditTrail: DocumentAuditTrail
  ): Observable<DocumentAuditTrail | CommonError> {
    const url = `documentAuditTrail`;
    return this.httpClient
      .post<DocumentAuditTrail>(url, documentAuditTrail)
      .pipe(catchError(this.commonHttpErrorService.handleError));
    //return this.httpClient.post<DocumentAuditTrail>('documentAuditTrail',documentAuditTrail);
  }

  downloadDocument(
    documentView: DocumentView
  ): Observable<HttpEvent<Blob> | CommonError> {
    let url = '';
    if (documentView.isFromPublicPreview) {
      url = `document-sharable-link/${
        documentView.documentId
      }/download?password=${documentView.linkPassword ?? ''}`;
    } else {
      url = `document/${documentView.documentId}/download/${documentView.isVersion}`;
    }
    return this.httpClient
      .get(url, {
        reportProgress: true,
        observe: 'events',
        responseType: 'blob',
      })
      .pipe(
        catchError((error) =>
          this.commonHttpErrorService.handleError(
            this.blobToString(error.error)
          )
        )
      );
  }
  /* istanbul ignore next @preserve */
  isDownloadFlag(documentId: string): Observable<boolean> {
    const url = `document/${documentId}/isDownloadFlag`;
    return this.httpClient.get<boolean>(url);
  }
  getDocumentToken(
    documentView: DocumentView
  ): Observable<{ [key: string]: string }> {
    let url = '';
    if (documentView.isFromPublicPreview) {
      url = `document-sharable-link/${documentView.documentId}/token`;
    } else {
      url = `documentToken/${documentView.documentId}/token`;
    }
    return this.httpClient.get<{ [key: string]: string }>(url);
  }

  deleteDocumentToken(token: string): Observable<boolean> {
    const url = `documentToken/${token}`;
    return this.httpClient.delete<boolean>(url);
  }

  readDocument(
    documentView: DocumentView
  ): Observable<{ [key: string]: string[] } | CommonError> {
    let url = '';
    if (documentView.isFromPublicPreview) {
      url = `document-sharable-link/${
        documentView.documentId
      }/readText?password=${documentView.linkPassword ?? ''}`;
      return this.httpClient.post<{ [key: string]: string[] }>(url, {
        password: documentView.linkPassword,
      });
    } else {
      url = `document/${documentView.documentId}/readText/${documentView.isVersion}`;
      return this.httpClient.get<{ [key: string]: string[] }>(url);
    }
  }

  getReminderFrequency(): Observable<ReminderFrequency[]> {
    return of(reminderFrequencies);
  }
  /* istanbul ignore next @preserve */
  getAllRemindersForCurrentUser(
    resourceParams: ReminderResourceParameter
  ): Observable<HttpResponse<Reminder[]>> {
    const url = 'reminder/all/currentuser';
    const customParams = new HttpParams()
      .set('fields', resourceParams.fields ? resourceParams.fields : '')
      .set('orderBy', resourceParams.orderBy ? resourceParams.orderBy : '')
      .set('pageSize', resourceParams.pageSize.toString())
      .set('skip', resourceParams.skip.toString())
      .set(
        'searchQuery',
        resourceParams.searchQuery ? resourceParams.searchQuery : ''
      )
      .set('subject', resourceParams.subject ? resourceParams.subject : '')
      .set('message', resourceParams.message ? resourceParams.message : '')
      .set(
        'frequency',
        resourceParams.frequency ? resourceParams.frequency : ''
      );

    return this.httpClient.get<Reminder[]>(url, {
      params: customParams,
      observe: 'response',
    });
  }
  /* istanbul ignore next @preserve */
  deleteReminderCurrentUser(reminderId: string): Observable<boolean> {
    const url = `reminder/currentuser/${reminderId}`;
    return this.httpClient.delete<boolean>(url);
  }

  getPageHelperText(code: string): Observable<PageHelper | CommonError> {
    const url = `page-helper/${code}/code`;
    return this.httpClient.get<PageHelper>(url);
  }
  /* istanbul ignore next @preserve */
  private blobToString(blob) {
    const url = URL.createObjectURL(blob);
    const xmlRequest = new XMLHttpRequest();
    xmlRequest.open('GET', url, false);
    xmlRequest.send();
    URL.revokeObjectURL(url);
    return JSON.parse(xmlRequest.responseText);
  }
}
