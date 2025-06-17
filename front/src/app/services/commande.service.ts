import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Commande {
  resume_articles: string;
  statut_commande: string;
  montant_total: number;
  date_commande?: string;  // si tu veux ajouter la date côté frontend
}

@Injectable({
  providedIn: 'root'
})
export class CommandeService {
  private apiUrl = 'http://localhost/projet_angular/back/save-commande.php'; // adapte si nécessaire

  constructor(private http: HttpClient) {}

  enregistrerCommande(commande: Commande): Observable<any> {
    return this.http.post(this.apiUrl, commande);
  }
}
