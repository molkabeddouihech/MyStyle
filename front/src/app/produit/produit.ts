import { CommonModule } from '@angular/common';
import { ProduitService, Produit } from '../produit.service';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
@Component({
  selector: 'app-produit',
  standalone: true,
  imports: [CommonModule, FormsModule,],
  templateUrl: './produit.html',
  styleUrls: ['./produit.css']
})
export class ProduitComponent implements OnInit {
  produits: Produit[] = [];
  genreFiltre: string | null = null;


  searchTerm: string = '';
  panier: { nom: string, prix: number | string, quantite: number }[] = [];

  constructor(private produitService: ProduitService) {}

  ngOnInit() {
    this.produitService.getProduits().subscribe(data => {
      this.produits = data;
    });
  }

  filtrer(genre: string) {
    this.genreFiltre = genre;
  }

  

  getNombreTotalProduits() {
    return this.panier.reduce((total, item) => total + item.quantite, 0);
  }

  ajouterAuPanier(produit: Produit) {
    const existant = this.panier.find(item => item.nom === produit.titre);
    if (existant) {
      existant.quantite += 1;
    } else {
      this.panier.push({ nom: produit.titre, prix: produit.prix, quantite: 1 });
    }
  }

  retirerDuPanier(item: { nom: string, prix: number | string, quantite: number }) {
    const index = this.panier.findIndex(p => p.nom === item.nom);
    if (index > -1) {
      if (this.panier[index].quantite > 1) {
        this.panier[index].quantite -= 1;
      } else {
        this.panier.splice(index, 1);
      }
    }
  }

  augmenterQuantite(item: { nom: string, prix: number | string, quantite: number }) {
    item.quantite += 1;
  }

  diminuerQuantite(item: { nom: string, prix: number | string, quantite: number }) {
    if (item.quantite > 1) {
      item.quantite -= 1;
    }
  }

  supprimerDuPanier(item: { nom: string, prix: number | string, quantite: number }) {
    const index = this.panier.findIndex(p => p.nom === item.nom);
    if (index > -1) {
      this.panier.splice(index, 1);
    }
  }

  getTotalPanier() {
    return this.panier.reduce((total, item) => {
      let prixNum = typeof item.prix === 'string'
        ? parseFloat((item.prix as string).replace(/[^\d.,]/g, '').replace(',', '.'))
        : item.prix;
      return total + prixNum * item.quantite;
    }, 0);
  }
  

get produitsFiltres() {
  let produits = this.produits;
  if (typeof this.genreFiltre === 'string' && this.genreFiltre !== 'all') {
    produits = produits.filter(
    p => p.genre && this.genreFiltre && p.genre.toLowerCase() === this.genreFiltre.toLowerCase()
  
    );
  }
  if (this.searchTerm) {
    produits = produits.filter(
      p => p.titre && p.titre.toLowerCase().includes(this.searchTerm.toLowerCase())
    );
  }
  return produits;
}
}