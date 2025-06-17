import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LivraisonService, Livraison } from './livraison.service';
import { PaiementComponent } from './paiement.component';

@Component({
  selector: 'app-livraison',
  standalone: true,
  imports: [CommonModule, FormsModule, PaiementComponent],
  template: `
    <!-- Interface de livraison -->
    <div *ngIf="!afficherPaiement" class="livraison-container">
      <div class="livraison-card">
        <!-- En-tête -->
        <div class="header">
          <div class="truck-icon">🚚</div>
          <span>Détails de Livraison</span>
        </div>

        <div class="content">
          <h3><span class="info-icon">ℹ️</span> Vos informations de livraison</h3>
          
          <div class="form-grid">
            <!-- Statut -->
            <div class="form-group">
              <label><span class="icon">📋</span>Statut</label>
              <div class="status-badge">
                {{ statutLivraison }}
              </div>
            </div>

            <!-- Adresse -->
            <div class="form-group">
              <label><span class="icon">📍</span>Adresse</label>
              <input 
                type="text" 
                [(ngModel)]="adresseLivraison" 
                placeholder="Entrez votre adresse complète"
                [class.input-invalid]="formSubmitted && !adresseValide"
                class="form-input">
              <div class="error-message" *ngIf="formSubmitted && !adresseValide">
                L'adresse est obligatoire
              </div>
            </div>

            <!-- Ville -->
            <div class="form-group">
              <label><span class="icon">🏙️</span>Ville</label>
              <input 
                type="text" 
                [(ngModel)]="villeLivraison" 
                placeholder="Entrez votre ville"
                [class.input-invalid]="formSubmitted && !villeValide"
                class="form-input">
              <div class="error-message" *ngIf="formSubmitted && !villeValide">
                La ville est obligatoire
              </div>
            </div>

            <!-- Code postal -->
            <div class="form-group">
              <label><span class="icon">📮</span>Code postal</label>
              <input 
                type="text" 
                [(ngModel)]="codePostalLivraison" 
                placeholder="Code postal"
                [class.input-invalid]="formSubmitted && !codePostalValide"
                class="form-input">
              <div class="error-message" *ngIf="formSubmitted && !codePostalValide">
                Le code postal est obligatoire
              </div>
            </div>

            <!-- Date de livraison -->
            <div class="form-group">
              <label><span class="icon">📅</span>Date de Livraison</label>
              <input 
                type="date" 
                [(ngModel)]="dateLivraison" 
                [min]="dateMin"
                [class.input-invalid]="formSubmitted && !dateValide"
                class="form-input">
              <div class="error-message" *ngIf="formSubmitted && !dateValide">
                Veuillez sélectionner une date de livraison
              </div>
            </div>
          </div>
        </div>

        <!-- Bouton -->
        <div class="button-container">
          <button 
            class="confirm-btn" 
            (click)="confirmerCommande()"
            [disabled]="enregistrementEnCours">
            <span class="check-icon">✓</span>
            {{ enregistrementEnCours ? 'Enregistrement...' : 'Confirmer la Commande' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Interface de paiement -->
    <div *ngIf="afficherPaiement">
      <app-paiement 
        [idLivraison]="idLivraisonCree"
        (paiementTermine)="onPaiementTermine()"
        (retourAccueilDemande)="onRetourAccueil()">
      </app-paiement>
    </div>
  `,
  styles: [`
    .livraison-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
      background-color: #f8f9fa;
      padding: 20px;
    }

    .livraison-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 900px;
      width: 100%;
      border: 2px solid #e0e0e0;
    }

    .header {
      background: #5e17eb;
      color: white;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
      font-weight: 600;
      font-size: 20px;
    }

    .truck-icon {
      font-size: 24px;
    }

    .content {
      padding: 30px;
    }

    .content h3 {
      color: #333;
      margin-bottom: 30px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      font-weight: 600;
    }

    .info-icon {
      font-size: 20px;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 25px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      color: #555;
      font-weight: 600;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .icon {
      font-size: 16px;
    }

    .form-input {
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }

    .form-input:focus {
      outline: none;
      border-color: #5e17eb;
    }

    .form-input.input-invalid {
      border-color: #dc3545;
    }

    .status-badge {
      background: #fff3cd;
      color: #856404;
      padding: 12px 15px;
      border-radius: 8px;
      border: 2px solid #ffeaa7;
      font-weight: 600;
      text-align: center;
    }

    .error-message {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }

    .button-container {
      padding: 20px 30px 30px;
      display: flex;
      justify-content: center;
    }

    .confirm-btn {
      background: #5e17eb;
      color: white;
      border: none;
      padding: 15px 40px;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 250px;
      justify-content: center;
    }

    .confirm-btn:hover:not(:disabled) {
      background: #4a12c4;
    }

    .confirm-btn:disabled {
      background: #6c757d;
      cursor: not-allowed;
    }

    .check-icon {
      font-size: 20px;
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      
      .livraison-container {
        padding: 10px;
      }
    }
  `]
})
export class LivraisonComponent {
  @Input() idCommande!: number;
  @Output() livraisonConfirmee = new EventEmitter<void>();
  @Output() paiementAffiche = new EventEmitter<boolean>();
  @Output() naviguerVersAccueil = new EventEmitter<void>(); // NOUVEAU: EventEmitter pour la navigation

  statutLivraison = 'En attente';
  adresseLivraison = '';
  villeLivraison = '';
  codePostalLivraison = '';
  dateLivraison = '';
  dateMin = '';

  formSubmitted = false;
  enregistrementEnCours = false;

  // Propriétés pour le paiement
  afficherPaiement = false;
  idLivraisonCree = 0;

  constructor(private livraisonService: LivraisonService) {
    // Définir la date minimum (aujourd'hui + 1 jour)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    this.dateMin = tomorrow.toISOString().split('T')[0];
    
    // Date par défaut (dans 3 jours)
    const defaultDate = new Date();
    defaultDate.setDate(defaultDate.getDate() + 3);
    this.dateLivraison = defaultDate.toISOString().split('T')[0];
  }

  get adresseValide(): boolean {
    return this.adresseLivraison.trim().length >= 5;
  }

  get villeValide(): boolean {
    return this.villeLivraison.trim().length >= 2;
  }

  get codePostalValide(): boolean {
    return this.codePostalLivraison.trim().length >= 4;
  }

  get dateValide(): boolean {
    return this.dateLivraison !== '';
  }

  confirmerCommande(): void {
    this.formSubmitted = true;

    if (!this.adresseValide || !this.villeValide || !this.codePostalValide || !this.dateValide) {
      alert('Veuillez remplir correctement tous les champs de livraison');
      return;
    }

    const adresseComplete = `${this.adresseLivraison}, ${this.villeLivraison} ${this.codePostalLivraison}`;

    const nouvelleLivraison: Livraison = {
      date_livraison: this.dateLivraison,
      statut_livraison: this.statutLivraison,
      adresse_livraison: adresseComplete,
      id_commande: this.idCommande,
      ville: this.villeLivraison,
      code_postal: this.codePostalLivraison
    };

    this.enregistrementEnCours = true;

    this.livraisonService.enregistrerLivraison(nouvelleLivraison).subscribe({
      next: (response: any) => {
        this.enregistrementEnCours = false;
        this.idLivraisonCree = response.id_livraison || Math.floor(Math.random() * 1000000);
        this.afficherPaiement = true;
        
        console.log('Livraison - Passage au paiement, émission événement paiementAffiche:', true);
        this.paiementAffiche.emit(true);
      },
      error: (err: any) => {
        this.enregistrementEnCours = false;
        alert('Erreur lors de la confirmation de livraison. Veuillez réessayer.');
        console.error('Erreur API livraison:', err);
      }
    });
  }

  onPaiementTermine(): void {
    console.log('Livraison - Paiement terminé, émission événement paiementAffiche:', false);
    this.paiementAffiche.emit(false);
    this.livraisonConfirmee.emit();
  }

  onRetourAccueil(): void {
    console.log('Livraison - Retour accueil reçu, propagation vers parent');
    this.paiementAffiche.emit(false);
    this.naviguerVersAccueil.emit(); // NOUVEAU: Propager l'événement de navigation
  }
}