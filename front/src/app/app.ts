import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProduitComponent } from './produit/produit';
import { PanierComponent } from './panier/panier';
import { PanierService } from './panier/panier.service';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { PromoService, Promo } from './services/promo.service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    CommonModule,
    ProduitComponent,
    PanierComponent,
    HttpClientModule
  ],
  providers: [PanierService, PromoService],
  templateUrl: './app.html',
  styleUrls: ['./app.css']
})
export class AppComponent {
  darkMode = false;
  currentPage = 'home';

  codePromoSaisi: string = '';
  reduction: number = 0;
  montantTotalAvecReduction: number = 0;
  message: string = '';

  constructor(
    public panierService: PanierService,
    private promoService: PromoService,
    private http: HttpClient  // injection HttpClient ici
  ) {}

  ngOnInit() {
    // Vérifier si le mode sombre était activé précédemment
    const savedDarkMode = localStorage.getItem('darkMode');
    if (savedDarkMode === 'true') {
      this.darkMode = true;
      this.applyDarkMode();
    }
  }

  toggleDarkMode() {
    this.darkMode = !this.darkMode;
    
    // Sauvegarder la préférence dans localStorage
    localStorage.setItem('darkMode', this.darkMode.toString());
    
    this.applyDarkMode();
  }

  private applyDarkMode() {
    if (this.darkMode) {
      document.body.classList.add('dark-mode');
      // Force l'application du mode sombre sur tous les éléments
      document.documentElement.style.setProperty('--text-color', '#e0e0e0');
      document.documentElement.style.setProperty('--bg-color', '#121212');
    } else {
      document.body.classList.remove('dark-mode');
      document.documentElement.style.setProperty('--text-color', '#333');
      document.documentElement.style.setProperty('--bg-color', '#f9f9f9');
    }
  }

  changePage(page: string) {
    this.currentPage = page;
    window.scrollTo(0, 0);
  }
  getNombreTotalProduits(): number {
    return this.panierService.getNombreTotalProduits();
  }

 

  
}
