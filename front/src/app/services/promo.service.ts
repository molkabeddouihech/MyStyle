

import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

export interface Promo {
  id_promo: number;
  code_promo: string;
  reduction: number; // par exemple 0.1 pour 10%
}

@Injectable({
  providedIn: 'root'
})
export class PromoService {
  private apiUrl = 'http://localhost/projet_angular/back/get-promos.php';  // URL de ton API promo

  constructor(private http: HttpClient) {}

  getCodePromo(code: string): Observable<Promo | null> {
    return this.http.get<Promo[]>(this.apiUrl).pipe(
      map(promos => promos.find(p => p.code_promo.toUpperCase() === code.toUpperCase()) || null)
    );
  }
}

