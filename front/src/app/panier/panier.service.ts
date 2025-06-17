import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';



interface ProduitPanier {
  id: number;
  nom: string;
  prix: number;
  image: string;
  quantite: number;
}

@Injectable({
  providedIn: 'root'
})
export class PanierService {
  private panierItems: ProduitPanier[] = [];
  private panierSubject = new BehaviorSubject<ProduitPanier[]>([]);
  panier$ = this.panierSubject.asObservable();

  ajouterAuPanier(produit: any) {
    const existingItem = this.panierItems.find(item => item.id === produit.id);
    
    if (existingItem) {
      existingItem.quantite++;
    } else {
      this.panierItems.push({
        id: produit.id,
        nom: produit.nom || produit.titre,
        prix: typeof produit.prix === 'string' 
              ? parseFloat(produit.prix.replace(/[^\d.,]/g, '').replace(',', '.'))
              : produit.prix,
        image: produit.image,
        quantite: 1
      });
    }
    
    this.panierSubject.next([...this.panierItems]);
    this.saveToLocalStorage();
  }

  retirerDuPanier(produitId: number) {
    this.panierItems = this.panierItems.filter(item => item.id !== produitId);
    this.panierSubject.next([...this.panierItems]);
    this.saveToLocalStorage();
  }

  modifierQuantite(produitId: number, changement: number) {
    const item = this.panierItems.find(i => i.id === produitId);
    if (item) {
      item.quantite += changement;
      if (item.quantite <= 0) {
        this.retirerDuPanier(produitId);
      } else {
        this.panierSubject.next([...this.panierItems]);
        this.saveToLocalStorage();
      }
    }
  }

  private saveToLocalStorage() {
    localStorage.setItem('panier', JSON.stringify(this.panierItems));
  }

  private loadFromLocalStorage() {
    const saved = localStorage.getItem('panier');
    if (saved) {
      this.panierItems = JSON.parse(saved);
      this.panierSubject.next([...this.panierItems]);
    }
  }

  clearPanier() {
  this.panierItems = [];
  this.panierSubject.next([]);
  localStorage.removeItem('panier');
}


  getPanier(): ProduitPanier[] {
    return [...this.panierItems];
  }

  getNombreTotalProduits(): number {
    return this.panierItems.reduce((total, item) => total + item.quantite, 0);
  }

  getTotalPanier(): number {
    return this.panierItems.reduce((total, item) => total + (item.prix * item.quantite), 0);
  }

  

  constructor() {
    this.loadFromLocalStorage();
  }
}