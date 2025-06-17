import { bootstrapApplication } from '@angular/platform-browser';
import { AppComponent } from './app/app';
import 'zone.js';  // Assure que zone.js est chargé avant Angular

import { importProvidersFrom } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';

bootstrapApplication(AppComponent, {
  providers: [
    importProvidersFrom(HttpClientModule),
    // autres providers si besoin
  ]
});
