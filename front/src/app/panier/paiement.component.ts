import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { PaiementService, Paiement } from './paiement.service';

@Component({
  selector: 'app-paiement',
  standalone: true,
  imports: [CommonModule, FormsModule],
  template: `
    <!-- Interface de sélection/saisie de paiement -->
    <div *ngIf="!paiementReussi" class="paiement-container">
      <div class="paiement-card">
        <!-- En-tête -->
        <div class="header">
          <div class="card-icon">💳</div>
          <span>Paiement sécurisé</span>
        </div>

        <div class="subtitle">
          Finalisez votre commande en choisissant votre méthode de paiement
        </div>

        <div class="content">
          <h3>Méthodes de paiement</h3>
          
          <!-- Option Carte bancaire -->
          <div class="payment-option" [class.selected]="methodePaiement === 'carte'" (click)="selectionnerMethode('carte')">
            <div class="option-header">
              <div class="radio-btn" [class.checked]="methodePaiement === 'carte'">
                <div class="radio-dot" *ngIf="methodePaiement === 'carte'"></div>
              </div>
              <div class="card-icon-small">💳</div>
              <span>Carte bancaire</span>
            </div>

            <!-- Formulaire carte bancaire (affiché si sélectionné) -->
            <div *ngIf="methodePaiement === 'carte'" class="card-form">
              <div class="form-group">
                <label>Numéro de carte</label>
                <input 
                  type="text" 
                  [(ngModel)]="numeroCarte" 
                  placeholder="1234 5678 9012 3456"
                  maxlength="19"
                  (input)="formatCardNumber($event)"
                  [class.input-invalid]="formSubmitted && !numeroCarteValide"
                  class="form-input">
                <div class="error-message" *ngIf="formSubmitted && !numeroCarteValide">
                  Numéro de carte invalide
                </div>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label>Nom du titulaire</label>
                  <input 
                    type="text" 
                    [(ngModel)]="nomTitulaire" 
                    placeholder="Nom Prénom"
                    [class.input-invalid]="formSubmitted && !nomTitulaireValide"
                    class="form-input">
                  <div class="error-message" *ngIf="formSubmitted && !nomTitulaireValide">
                    Nom requis
                  </div>
                </div>

                <div class="form-group">
                  <label>Expiration</label>
                  <input 
                    type="text" 
                    [(ngModel)]="expiration" 
                    placeholder="MM/AA"
                    maxlength="5"
                    (input)="formatExpiration($event)"
                    [class.input-invalid]="formSubmitted && !expirationValide"
                    class="form-input">
                  <div class="error-message" *ngIf="formSubmitted && !expirationValide">
                    Format MM/AA requis
                  </div>
                </div>

                <div class="form-group">
                  <label>CVV</label>
                  <input 
                    type="text" 
                    [(ngModel)]="cvv" 
                    placeholder="123"
                    maxlength="3"
                    [class.input-invalid]="formSubmitted && !cvvValide"
                    class="form-input">
                  <div class="error-message" *ngIf="formSubmitted && !cvvValide">
                    CVV requis
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Option Cash -->
          <div class="payment-option" [class.selected]="methodePaiement === 'cash'" (click)="selectionnerMethode('cash')">
            <div class="option-header">
              <div class="radio-btn" [class.checked]="methodePaiement === 'cash'">
                <div class="radio-dot" *ngIf="methodePaiement === 'cash'"></div>
              </div>
              <div class="cash-icon">💵</div>
              <span>Cash (Espèces)</span>
            </div>
          </div>
        </div>

        <!-- Bouton OK -->
        <div class="button-container">
          <button 
            class="ok-btn" 
            (click)="confirmerPaiement()"
            [disabled]="enregistrementEnCours || !methodePaiement">
            <span class="lock-icon">🔒</span>
            {{ enregistrementEnCours ? 'Traitement...' : 'OK' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Message de succès -->
    <div *ngIf="paiementReussi" class="success-container">
      <div class="success-card">
        <div class="success-icon">✅</div>
        <h2>Paiement réussi !</h2>
        <p>Merci pour votre confiance</p>
        <p class="order-info">Votre commande a été traitée avec succès.</p>
        
        <button class="home-btn" (click)="retourAccueil()">
          🏠 Retour à l'accueil
        </button>
      </div>
    </div>
  `,
  styles: [`
    .paiement-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
      background-color: #f8f9fa;
      padding: 20px;
    }

    .paiement-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      max-width: 600px;
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

    .card-icon {
      font-size: 24px;
    }

    .subtitle {
      padding: 20px;
      text-align: center;
      color: #666;
      font-size: 16px;
      border-bottom: 1px solid #eee;
    }

    .content {
      padding: 30px;
    }

    .content h3 {
      color: #28a745;
      margin-bottom: 25px;
      font-size: 18px;
      font-weight: 600;
    }

    .payment-option {
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      margin-bottom: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .payment-option:hover {
      border-color: #28a745;
    }

    .payment-option.selected {
      border-color: #28a745;
      background-color: #f8fff9;
    }

    .option-header {
      padding: 15px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 500;
    }

    .radio-btn {
      width: 20px;
      height: 20px;
      border: 2px solid #ddd;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: border-color 0.3s ease;
    }

    .radio-btn.checked {
      border-color: #28a745;
    }

    .radio-dot {
      width: 10px;
      height: 10px;
      background: #28a745;
      border-radius: 50%;
    }

    .card-icon-small, .cash-icon {
      font-size: 18px;
    }

    .card-form {
      padding: 0 20px 20px;
      border-top: 1px solid #eee;
      margin-top: 15px;
      padding-top: 20px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: 500;
    }

    .form-input {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e0e0e0;
      border-radius: 6px;
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

    .ok-btn {
      background: #28a745;
      color: white;
      border: none;
      padding: 15px 60px;
      border-radius: 8px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 150px;
      justify-content: center;
    }

    .ok-btn:hover:not(:disabled) {
      background: #218838;
    }

    .ok-btn:disabled {
      background: #6c757d;
      cursor: not-allowed;
    }

    .lock-icon {
      font-size: 16px;
    }

    .success-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
      background-color: #f8f9fa;
      padding: 20px;
    }

    .success-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 50px;
      text-align: center;
      max-width: 500px;
      width: 100%;
    }

    .success-icon {
      font-size: 80px;
      margin-bottom: 20px;
    }

    .success-card h2 {
      color: #28a745;
      margin-bottom: 15px;
      font-size: 28px;
    }

    .success-card p {
      color: #666;
      font-size: 18px;
      margin-bottom: 10px;
    }

    .order-info {
      font-size: 16px;
      margin-bottom: 30px;
    }

    .home-btn {
      background: #5e17eb;
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .home-btn:hover {
      background: #4a12c4;
    }

    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .paiement-container, .success-container {
        padding: 10px;
      }
    }
  `]
})
export class PaiementComponent {
  @Input() idLivraison!: number;
  @Output() paiementTermine = new EventEmitter<void>();
  @Output() retourAccueilDemande = new EventEmitter<void>();

  methodePaiement = '';
  numeroCarte = '';
  nomTitulaire = '';
  expiration = '';
  cvv = '';

  formSubmitted = false;
  enregistrementEnCours = false;
  paiementReussi = false;

  constructor(private paiementService: PaiementService) {}

  selectionnerMethode(methode: string) {
    this.methodePaiement = methode;
    this.formSubmitted = false;
  }

  formatCardNumber(event: any) {
    let value = event.target.value.replace(/\s/g, '');
    let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
    this.numeroCarte = formattedValue;
  }

  formatExpiration(event: any) {
    let value = event.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
      value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    this.expiration = value;
  }

  get numeroCarteValide(): boolean {
    return this.numeroCarte.replace(/\s/g, '').length >= 16;
  }

  get nomTitulaireValide(): boolean {
    return this.nomTitulaire.trim().length >= 3;
  }

  get expirationValide(): boolean {
    return /^\d{2}\/\d{2}$/.test(this.expiration);
  }

  get cvvValide(): boolean {
    return this.cvv.length === 3;
  }

  confirmerPaiement() {
    this.formSubmitted = true;

    if (this.methodePaiement === 'carte') {
      if (!this.numeroCarteValide || !this.nomTitulaireValide || !this.expirationValide || !this.cvvValide) {
        alert('Veuillez remplir correctement tous les champs de la carte');
        return;
      }
    }

    const nouveauPaiement: Paiement = {
      methode_paiement: this.methodePaiement === 'carte' ? 'Carte bancaire' : 'Cash (Espèces)',
      statut_paiement: 'Réussi',
      id_livraison: this.idLivraison
    };

    if (this.methodePaiement === 'carte') {
      nouveauPaiement.numero_carte = '**** **** **** ' + this.numeroCarte.slice(-4);
      nouveauPaiement.nom_titulaire = this.nomTitulaire;
    }

    this.enregistrementEnCours = true;

    this.paiementService.enregistrerPaiement(nouveauPaiement).subscribe({
      next: (response: any) => {
        this.enregistrementEnCours = false;
        this.paiementReussi = true;
      },
      error: (err: any) => {
        this.enregistrementEnCours = false;
        alert('Erreur lors du traitement du paiement. Veuillez réessayer.');
        console.error('Erreur API paiement:', err);
      }
    });
  }

  retourAccueil() {
    console.log('Paiement - Retour accueil demandé'); // Debug
    this.retourAccueilDemande.emit();
  }
}