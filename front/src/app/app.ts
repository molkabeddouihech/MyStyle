import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProduitComponent } from './produit/produit';
import { PanierComponent } from './panier/panier';
import { PanierService } from './panier/panier.service';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { PromoService, Promo } from './services/promo.service';
import { LoginComponent } from './login/login'; 

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    CommonModule,
    ProduitComponent,
    PanierComponent,
    HttpClientModule,
    LoginComponent
   
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

  changePage(page: string) {
    this.currentPage = page;
  }

  toggleDarkMode() {
    this.darkMode = !this.darkMode;
  }

  getNombreTotalProduits(): number {
    return this.panierService.getNombreTotalProduits();
  }

 

  
}
