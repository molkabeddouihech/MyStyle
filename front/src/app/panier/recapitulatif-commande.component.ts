import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LivraisonComponent } from './livraison.component';

@Component({
  selector: 'app-recapitulatif-commande',
  standalone: true,
  imports: [CommonModule, LivraisonComponent],
  template: `
    <!-- Récapitulatif existant -->
    <div *ngIf="!afficherLivraison" class="recapitulatif-container">
      <div class="recapitulatif-card">
        <!-- En-tête -->
        <div class="header">
          <div class="check-icon">✓</div>
          <span>Récapitulatif de commande</span>
        </div>

        <div class="content">
          <!-- Vos informations -->
          <div class="section">
            <h3><span class="icon">👤</span> Vos informations</h3>
            <div class="info-grid">
              <div class="info-row">
                <span class="label">Nom :</span>
                <span class="value">{{ infosCommande.nom }}</span>
              </div>
              <div class="info-row">
                <span class="label">Prénom :</span>
                <span class="value">{{ infosCommande.prenom }}</span>
              </div>
              <div class="info-row">
                <span class="label">Email :</span>
                <span class="value">{{ infosCommande.email }}</span>
              </div>
              <div class="info-row">
                <span class="label">N° Commande :</span>
                <span class="value">{{ infosCommande.numeroCommande }}</span>
              </div>
            </div>
          </div>

          <!-- Votre commande -->
          <div class="section">
            <h3><span class="icon">🛒</span> Votre commande</h3>
            <div class="commande-details">
              <div *ngFor="let produit of infosCommande.produits" class="produit-row">
                <span class="produit-nom">{{ produit.nom }}</span>
                <span class="produit-prix">{{ produit.quantite }} x {{ produit.prix | number:'1.2-2' }} DT</span>
              </div>

              <div class="separator"></div>

              <div class="total-section">
                <div *ngIf="infosCommande.reduction > 0" class="total-row reduction">
                  <span>Réduction ({{ infosCommande.pourcentageReduction }}%):</span>
                  <span>-{{ infosCommande.reduction | number:'1.2-2' }} DT</span>
                </div>
                <div class="total-row">
                  <span>Sous-total :</span>
                  <span>{{ infosCommande.sousTotal | number:'1.2-2' }} DT</span>
                </div>
                <div class="total-row">
                  <span>Livraison :</span>
                  <span>{{ infosCommande.fraisLivraison | number:'1.2-2' }} DT</span>
                </div>
                <div class="total-row final-total">
                  <span><strong>Total :</strong></span>
                  <span><strong>{{ infosCommande.total | number:'1.2-2' }} DT</strong></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bouton -->
        <div class="button-container">
          <button class="continue-btn" (click)="continuerVersLivraison()">
            → Continuer vers la livraison
          </button>
        </div>
      </div>
    </div>

    <!-- Interface de livraison -->
    <div *ngIf="afficherLivraison">
      <app-livraison 
        [idCommande]="infosCommande.numeroCommande"
        (livraisonConfirmee)="onLivraisonConfirmee()"
        (paiementAffiche)="onPaiementAffiche($event)"
        (naviguerVersAccueil)="onNaviguerVersAccueil()">
      </app-livraison>
    </div>
  `,
  styles: [`
    .recapitulatif-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: #f5f5f5;
      padding: 20px;
    }

    .recapitulatif-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 800px;
      width: 100%;
      border: 2px solid #e0e0e0;
    }

    .header {
      background: #5e17eb;
      color: white;
      padding: 15px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
      font-size: 16px;
    }

    .check-icon {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      width: 24px;
      height: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
    }

    .content {
      padding: 30px;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
    }

    .section h3 {
      color: #333;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 16px;
      font-weight: 600;
    }

    .icon {
      font-size: 18px;
    }

    .info-grid {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
    }

    .label {
      color: #666;
      font-weight: 500;
    }

    .value {
      color: #333;
      font-weight: 600;
    }

    .commande-details {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .produit-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 0;
    }

    .produit-nom {
      color: #333;
      font-weight: 500;
    }

    .produit-prix {
      color: #666;
      font-weight: 600;
    }

    .separator {
      height: 1px;
      background: #e0e0e0;
      margin: 15px 0;
    }

    .total-section {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 5px 0;
    }

    .total-row.reduction {
      color: #e74c3c;
      font-weight: 600;
    }

    .total-row.final-total {
      border-top: 2px solid #5e17eb;
      padding-top: 10px;
      margin-top: 10px;
      font-size: 18px;
    }

    .button-container {
      padding: 20px 30px 30px;
      display: flex;
      justify-content: center;
    }

    .continue-btn {
      background: #5e17eb;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .continue-btn:hover {
      background: #4a12c4;
    }

    @media (max-width: 768px) {
      .content {
        grid-template-columns: 1fr;
        gap: 30px;
      }
      
      .recapitulatif-container {
        padding: 10px;
      }
    }
  `]
})
export class RecapitulatifCommandeComponent {
  @Input() infosCommande!: any;
  @Output() livraisonAffichee = new EventEmitter<boolean>();
  @Output() paiementAffiche = new EventEmitter<boolean>();
  @Output() naviguerVersAccueil = new EventEmitter<void>(); // NOUVEAU: EventEmitter pour la navigation

  afficherLivraison = false;

  continuerVersLivraison() {
    this.afficherLivraison = true;
    this.livraisonAffichee.emit(true);
  }

  onLivraisonConfirmee() {
    this.livraisonAffichee.emit(false);
  }

  onPaiementAffiche(affichage: boolean): void {
    console.log('Récapitulatif - Événement paiementAffiche reçu:', affichage);
    this.paiementAffiche.emit(affichage);
  }

  // NOUVEAU: Méthode pour propager l'événement de navigation
  onNaviguerVersAccueil(): void {
    console.log('Récapitulatif - Navigation vers accueil reçue, propagation vers parent');
    this.naviguerVersAccueil.emit();
  }
}