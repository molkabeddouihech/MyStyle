import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProduitComponent } from './produit/produit';
import { HttpClientModule } from '@angular/common/http';


@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, ProduitComponent, HttpClientModule],
 templateUrl: './app.html',
  styleUrls: ['./app.css']
})
export class AppComponent {

  darkMode = false;
  
  genreFiltre: string | null = null;

  currentPage = 'produits';


  panier: any[] = [];

  changePage(page: string) {
    this.currentPage = page;
    this.genreFiltre = null;
  }

  filtrer(genre: string) {
    this.genreFiltre = genre;
  }

  

  getNombreTotalProduits() {
  return this.panier.reduce((total, item) => total + item.quantite, 0);
}

ajouterAuPanier(produit: any) {
  const existant = this.panier.find((item: any) => item.nom === produit.titre || item.nom === produit.nom);
  if (existant) {
    existant.quantite += 1;
  } else {
    this.panier.push({
      nom: produit.titre || produit.nom,
      prix: produit.prix,
      quantite: 1
    });
  }
}
/*//////*/
retirerDuPanier(item: any) {
  const index = this.panier.findIndex((p: any) => p.nom === item.nom);
  if (index > -1) {
    if (this.panier[index].quantite > 1) {
      this.panier[index].quantite -= 1;
    } else {
      this.panier.splice(index, 1);
    }
  }
}



augmenterQuantite(item: any) {
  item.quantite += 1;
}

diminuerQuantite(item: any) {
  if (item.quantite > 1) {
    item.quantite -= 1;
  }
}

supprimerDuPanier(item: any) {
  const index = this.panier.findIndex((p: any) => p.nom === item.nom);
  if (index > -1) {
    this.panier.splice(index, 1);
  }
}

getTotalPanier() {
  return this.panier.reduce((total, item) => {
    // Si prix est une chaîne (ex: '169,90 د.ت'), extraire le nombre
    let prixNum = typeof item.prix === 'string'
      ? parseFloat(item.prix.replace(/[^\d.,]/g, '').replace(',', '.'))
      : item.prix;
    return total + prixNum * item.quantite;
  }, 0);
}

  toggleDarkMode() {
  this.darkMode = !this.darkMode;
  if (this.darkMode) {
    document.body.classList.add('dark');
  } else {
    document.body.classList.remove('dark');
  }
}

}
