import { RouteInfo } from './sidebar.metadata';

export const ROUTES: RouteInfo[] = [
  {
    path: 'dashboard',
    title: 'DASHBOARD',
    icon: 'layers',
    class: '',
    groupTitle: false,
    claims: ['DASHBOARD_VIEW_DASHBOARD'],
    submenu: [],
  },
  {
    path: 'roles',
    title: 'ROLES',
    icon: 'users',
    class: '',
    groupTitle: false,
    claims: ['ROLE_VIEW_ROLES'],
    submenu: [],
  },

];
