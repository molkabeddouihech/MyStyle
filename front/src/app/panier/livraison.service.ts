import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Livraison {
  date_livraison: string;
  statut_livraison: string;
  adresse_livraison: string;
  id_commande: number;
  ville?: string;
  code_postal?: string;
}

@Injectable({
  providedIn: 'root'
})
export class LivraisonService {
  private apiUrl = 'http://localhost/projet_angular/back/save-livraison.php';

  constructor(private http: HttpClient) {}

  enregistrerLivraison(livraison: Livraison): Observable<any> {
    return this.http.post(this.apiUrl, livraison);
  }
}