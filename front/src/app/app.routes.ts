import { Routes } from '@angular/router';
import { ProduitComponent } from './produit/produit';
import { PanierComponent } from './panier/panier';

export const routes: Routes = [
  { path: '', component: ProduitComponent },
  { path: 'panier', component: PanierComponent }
];
