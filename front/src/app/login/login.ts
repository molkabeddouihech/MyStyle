import { Component } from '@angular/core';
import { NgIf,NgClass } from '@angular/common';
import { Inscription } from './inscription';
import { HttpClient } from '@angular/common/http';  // Pour faire les requêtes HTTP
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [Inscription, NgIf, FormsModule, NgClass],
  templateUrl: './login.html',
  styleUrls: ['./login.css']
})
export class LoginComponent {
  afficherInscription = false;
  afficherMotDePasse = false;

  email: string = '';
  password: string = '';

  message: string = '';
  messageType: 'error' | 'success' = 'error';
  loading: boolean = false;

  constructor(private http: HttpClient) {}

  togglePassword() {
    this.afficherMotDePasse = !this.afficherMotDePasse;
  }

  onSubmit() {
    if (!this.email || !this.password) {
      this.message = 'Veuillez remplir tous les champs';
      this.messageType = 'error';
      return;
    }
    
  // Ajoute ce log pour vérifier ce que tu envoies
  console.log('Données envoyées au backend : ', { email: this.email, password: this.password });

    this.loading = true;
    this.message = '';

    this.http.post<any>('http://localhost:8181/projet_angular/back/check-user.php', {
      email: this.email,
      password: this.password
    }).subscribe({
      next: (res) => {
        this.loading = false;

        if (res.success) {
          this.message = res.message || 'Connexion réussie';
          this.messageType = 'success';
          // Ici tu peux ajouter la redirection ou autre logique
        } else {
          this.message = res.message || 'Erreur lors de la connexion';

          if (res.message?.toLowerCase().includes('inscrire')) {
            this.afficherInscription = true; // Affiche la zone inscription si compte inexistant
          }

          this.messageType = 'error';
        }
      },
      error: (err) => {
        this.loading = false;
        this.message = 'Erreur serveur, réessayez plus tard.';
        this.messageType = 'error';
        console.error(err);
      }
    });
  }
}
