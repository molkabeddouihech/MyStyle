import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Produit {
  id: number;
  titre: string;
  prix: number;
  stock: number;
  image: string;
  genre: string;
}

@Injectable({ providedIn: 'root' })
export class ProduitService {
  private apiUrl = 'http://localhost/project_angular/back/produits.php';

  constructor(private http: HttpClient) {}

  getProduits(): Observable<Produit[]> {
    return this.http.get<Produit[]>(this.apiUrl);
  }
}