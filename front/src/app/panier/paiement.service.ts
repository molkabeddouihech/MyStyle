import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Paiement {
  methode_paiement: string;
  date_paiement?: string;
  statut_paiement: string;
  id_livraison: number;
  numero_carte?: string;
  nom_titulaire?: string;
  expiration?: string;
  cvv?: string;
}

@Injectable({
  providedIn: 'root'
})
export class PaiementService {
  private apiUrl = 'http://localhost/projet_angular/back/save-paiement.php';

  constructor(private http: HttpClient) {}

  enregistrerPaiement(paiement: Paiement): Observable<any> {
    return this.http.post(this.apiUrl, paiement);
  }
}