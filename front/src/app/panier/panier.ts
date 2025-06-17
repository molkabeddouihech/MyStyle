import { Component } from '@angular/core';
import { PanierService } from './panier.service';
import { PromoService } from '../services/promo.service';
import { CommandeService, Commande } from '../services/commande.service';
import { LivraisonService } from './livraison.service';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { RecapitulatifCommandeComponent } from './recapitulatif-commande.component';

@Component({
  selector: 'app-panier',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule, HttpClientModule, RecapitulatifCommandeComponent],
  templateUrl: './panier.html',
  styleUrls: ['./panier.css']
})
export class PanierComponent {
  today = new Date();
  commandeId = Math.floor(Math.random() * 1000000);
  reduction = 0;
  darkMode = false;

  nomClient = '';
  prenomClient = '';
  emailClient = '';
  formSubmitted = false;

  codePromoSaisi = '';
  promoValide = false;
  promoMessage = '';
  pourcentageReduction = 0;

  // Indicateur d'enregistrement en cours
  enregistrementEnCours = false;
  
  // Propriétés pour la gestion des états d'affichage
  afficherRecapitulatif = false;
  afficherLivraison = false;
  afficherPaiement = false;
  infosCommandeRecap: any = null;

  constructor(
    public panierService: PanierService,
    private promoService: PromoService,
    private commandeService: CommandeService,
    private livraisonService: LivraisonService
  ) {}

  get panier() {
    return this.panierService.getPanier();
  }

  getTotalPanier(): number {
    return this.panierService.getTotalPanier() - this.reduction;
  }

  appliquerCodePromo(): void {
    const code = this.codePromoSaisi.trim().toUpperCase();
    if (!code) {
      alert('Veuillez saisir un code promo.');
      return;
    }

    this.promoService.getCodePromo(code).subscribe({
      next: (code_promo) => {
        if (code_promo) {
          this.reduction = this.panierService.getTotalPanier() * (code_promo.reduction / 100);
          this.pourcentageReduction = code_promo.reduction;
          this.promoValide = true;
          this.promoMessage = `Code promo valide : réduction de ${code_promo.reduction}% appliquée !`;
        } else {
          this.reduction = 0;
          this.pourcentageReduction = 0;
          this.promoValide = false;
          this.promoMessage = 'Code promo invalide.';
          alert(this.promoMessage);
        }
      },
      error: () => {
        this.reduction = 0;
        this.pourcentageReduction = 0;
        this.promoValide = false;
        this.promoMessage = 'Erreur lors de la validation du code promo.';
        alert(this.promoMessage);
      }
    });
  }

  ajouterUnite(id: number): void {
    this.panierService.modifierQuantite(id, 1);
  }

  retirerUnite(id: number): void {
    this.panierService.modifierQuantite(id, -1);
  }

  supprimerDuPanier(id: number): void {
    this.panierService.retirerDuPanier(id);
  }

  continuerCommande(): void {
    this.formSubmitted = true;

    if (!this.nomValide || !this.prenomValide || !this.emailValide) {
      alert('Veuillez remplir correctement tous les champs des coordonnées');
      return;
    }

    const fraisLivraison = 7;
    const sousTotal = this.panierService.getTotalPanier() - this.reduction;
    const totalFinal = sousTotal + fraisLivraison;

    const nouvelleCommande: Commande = {
      resume_articles: JSON.stringify(this.panier),
      statut_commande: 'En cours',
      montant_total: totalFinal
    };

    this.enregistrementEnCours = true;

    this.commandeService.enregistrerCommande(nouvelleCommande).subscribe({
      next: (response) => {
        this.enregistrementEnCours = false;
        
        this.infosCommandeRecap = {
          nom: this.nomClient,
          prenom: this.prenomClient,
          email: this.emailClient,
          numeroCommande: this.commandeId,
          produits: this.panier.map(item => ({
            id: item.id,
            nom: item.nom,
            prix: item.prix,
            quantite: item.quantite
          })),
          reduction: this.reduction,
          pourcentageReduction: this.pourcentageReduction,
          sousTotal: sousTotal,
          fraisLivraison: fraisLivraison,
          total: totalFinal
        };

        this.afficherRecapitulatif = true;
      },
      error: (err) => {
        this.enregistrementEnCours = false;
        alert('Erreur lors de l\'enregistrement de la commande. Veuillez réessayer.');
        console.error('Erreur API commande:', err);
      }
    });
  }

  retourAuPanier(): void {
    this.afficherRecapitulatif = false;
  }

  onLivraisonConfirmee(): void {
    this.finaliserCommande();
  }

  finaliserCommande(): void {
    this.panierService.clearPanier();
    
    this.nomClient = '';
    this.prenomClient = '';
    this.emailClient = '';
    this.formSubmitted = false;
    this.reduction = 0;
    this.pourcentageReduction = 0;
    this.promoValide = false;
    this.promoMessage = '';
    this.afficherRecapitulatif = false;
    this.afficherLivraison = false;
    this.afficherPaiement = false;
    
    alert('Commande et livraison finalisées avec succès !');
  }

  toggleDarkMode(): void {
    this.darkMode = !this.darkMode;
  }

  // Méthodes de validation
  get nomValide(): boolean {
    const len = this.nomClient.trim().length;
    return len >= 2 && len <= 50;
  }

  get prenomValide(): boolean {
    const len = this.prenomClient.trim().length;
    return len >= 2 && len <= 50;
  }

  get emailValide(): boolean {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(this.emailClient.trim());
  }

  // Méthodes pour gérer les événements des composants enfants
  onLivraisonAffichee(affichage: boolean): void {
    this.afficherLivraison = affichage;
  }

  onPaiementAffiche(affichage: boolean): void {
    console.log('Panier - Événement paiementAffiche reçu:', affichage);
    this.afficherPaiement = affichage;
  }

  // NOUVEAU: Méthode pour naviguer vers l'accueil
  changePage(page: string): void {
    console.log('Panier - Navigation vers:', page);
    if (page === 'home') {
      // Réinitialiser l'état du panier
      this.panierService.clearPanier();
      this.nomClient = '';
      this.prenomClient = '';
      this.emailClient = '';
      this.formSubmitted = false;
      this.reduction = 0;
      this.pourcentageReduction = 0;
      this.promoValide = false;
      this.promoMessage = '';
      this.afficherRecapitulatif = false;
      this.afficherLivraison = false;
      this.afficherPaiement = false;
      
      // Rediriger vers la page d'accueil
      window.location.href = '/'; // Ou utilisez le Router Angular si disponible
    }
  }

  // Méthodes pour les titres dynamiques
  getTitrePageActuelle(): string {
    if (this.afficherRecapitulatif) {
      if (this.afficherPaiement) {
        return 'Paiement Sécurisé';
      }
      if (this.afficherLivraison) {
        return 'Détails de Livraison';
      }
      return 'Récapitulatif de Commande';
    }
    return 'Mon Panier';
  }

  getSousTitrePageActuelle(): string {
    if (this.afficherRecapitulatif) {
      if (this.afficherPaiement) {
        return 'Finalisez votre commande en choisissant votre méthode de paiement.';
      }
      if (this.afficherLivraison) {
        return 'Complétez vos informations de livraison.';
      }
      return 'Vérifiez les détails de votre commande.';
    }
    return 'Commandez dès maintenant et savourez la différence.';
  }
}