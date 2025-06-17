import { CommonModule } from '@angular/common';
import { ProduitService, Produit } from '../produit.service';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { PanierService } from '../panier/panier.service';

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

 constructor(
    private produitService: ProduitService,
    private panierService: PanierService
  ) {}

  ngOnInit() {
  this.produitService.getProduits().subscribe(data => {
    console.log('Produits reçus:', data); // Ajoute cette ligne
    this.produits = data;
  });
}

  filtrer(genre: string) {
    this.genreFiltre = genre;
  }

  

  getNombreTotalProduits() {
    return this.panier.reduce((total, item) => total + item.quantite, 0);
  }

 ajouterAuPanier(produit: any) {
  this.panierService.ajouterAuPanier(produit);
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
