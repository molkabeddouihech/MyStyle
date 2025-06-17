import { Component } from '@angular/core';
import { NgIf, NgClass } from '@angular/common';
import { Inscription } from './inscription';
import { HttpClient } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router'; // <-- Ajoute ceci

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

  constructor(private http: HttpClient, private router: Router) {} // <-- Ajoute Router ici

  togglePassword() {
    this.afficherMotDePasse = !this.afficherMotDePasse;
  }
  goToHome() {
  window.location.href = './app.html'; // Redirige directement vers app.html
}


 onSubmit() {
  if (!this.email || !this.password) {
    this.message = 'Veuillez remplir tous les champs';
    this.messageType = 'error';
    return;
  }

  this.loading = true;
  this.message = '';

  this.http.post<any>('http://localhost/projet_angular/back/check-user.php', {
    email: this.email,
    password: this.password
  }).subscribe({
    next: (res) => {
      this.loading = false;
      if (res.success) {
        // **Redirection simple vers l'accueil**
        window.location.href = './app.html'; // Charge directement app.html
      } else {
        this.message = res.message || 'Email ou mot de passe incorrect, veuillez inscrire';
        this.messageType = 'error';
      }
    },
    error: (err) => {
      this.loading = false;
      this.message = 'Erreur serveur, réessayez plus tard.';
      this.messageType = 'error';
      console.error('Erreur HTTP:', err);
    }
  });
}

}