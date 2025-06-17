import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PanierService } from '../panier/panier.service';

@Component({
  selector: 'app-produit',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './produit.html',
  styleUrls: ['./produit.css']
})
export class ProduitComponent {
  genreFiltre: string | null = null;

  produits = [
    { 
      id: 1, 
      titre: 'SAC À MAIN EFFET PAILLE AVEC BAMBOU', 
      prix: 169.90 , 
      couleurs: ['#7c2d2d', '#c2a256'], 
      image: 'assets/images/sac1.jpg', 
      genre: 'femme' 
    },
    { 
      id: 2, 
      titre: 'SAC DE FÊTE ENVELOPPÉ EN JUTE', 
      prix: 99.90, 
      couleurs: ['#d1bb8b', '#eae6d9'], 
      image: 'assets/images/sac2.jpg', 
      genre: 'femme' 
    },
    { 
      id: 3, 
      titre: 'SAC EN CUIR HOMME CLASSIQUE', 
      prix: 199.00, 
      couleurs: ['#2f2f2f', '#444444'], 
      image: 'assets/images/sac3.jpg', 
      genre: 'homme' 
    },
    { 
      id: 4, 
      titre: "LUNETTES DE SOLEIL FEMME", 
      prix: 89.90, 
      couleurs: ["#000000", "#8B4513"], 
      image: "assets/images/lunettes1.jpg", 
      genre: "femme" 
    },
    { 
      id: 5, 
      titre: "MONTRE HOMME ÉLÉGANTE", 
      prix: 299.00, 
      couleurs: ["#C0C0C0", "#FFD700"], 
      image: "assets/images/montre1.jpg", 
      genre: "homme" 
    },
    { 
      id: 6, 
      titre: "BIJOUX FANTAISIE FEMME", 
      prix: 49.90, 
      couleurs: ["#FFD700", "#C0C0C0"], 
      image: "assets/images/bijoux1.jpg", 
      genre: "femme" 
    }
  ];

 constructor(private panierService: PanierService) {
  console.log('PanierService injecté:', !!panierService);
}

  filtrer(genre: string) {
    this.genreFiltre = genre;
  }

  get produitsFiltres() {
    if (!this.genreFiltre) return this.produits;
    return this.produits.filter(p => p.genre === this.genreFiltre);
  }

  getNombreTotalProduits() {
    return this.panierService.getNombreTotalProduits();
  }

  ajouterAuPanier(produit: any) {
  this.panierService.ajouterAuPanier({
    id: produit.id,
    nom: produit.titre,  // Utilisez 'titre' comme dans vos données
    prix: produit.prix,  // Doit être un nombre
    image: produit.image
  });
}
}