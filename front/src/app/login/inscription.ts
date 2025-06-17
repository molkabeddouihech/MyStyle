import { HttpClient } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms'; // <-- important
import { CommonModule } from '@angular/common'; // si tu utilises des directives Angular
@Component({
  selector: 'app-inscription',
  standalone: true,
  templateUrl: './inscription.html',
  styleUrls: ['./inscription.css'],
  imports: [CommonModule, FormsModule] // si nécessaire
})

export class Inscription {
  apiUrl: string = 'http://localhost/projet_angular/back/Controller/add_userc.php';

  utilisateur = {
    nom: '',
    prenom: '',
    email: '',
    date: '',
    mdp: ''
  };

  utilisateurs: any[] = [];
  isLoading: boolean = false; // ✅ Ajout de la variable ici

  // Ajoutez ces deux propriétés pour corriger l'erreur Angular
  errorMessage: string = '';
  successMessage: string = '';


  constructor(private http: HttpClient) {}

  ajouterUtilisateur() {
   console.log({
  nom: this.utilisateur.nom,
  prenom: this.utilisateur.prenom,
  email: this.utilisateur.email,
  date: this.utilisateur.date,
  mdp: this.utilisateur.mdp
});

    if (
      !this.utilisateur.nom ||
      !this.utilisateur.prenom ||
      !this.utilisateur.email ||
      !this.utilisateur.date ||
      !this.utilisateur.mdp
    ) {
      alert('Veuillez remplir tous les champs');
      return;
    }

    if (this.utilisateur.mdp.length < 6) {
      alert('Le mot de passe doit contenir au moins 6 caractères');
      return;
    }

    const existant = this.utilisateurs.find(
      u => u.email === this.utilisateur.email
    );

    if (existant) {
      alert('Cet utilisateur existe déjà');
      return;
    }

    // ✅ Affiche un indicateur de chargement
    this.isLoading = true;

    this.http
  .post(this.apiUrl, this.utilisateur)
      .subscribe({
        next: () => {
          this.utilisateurs.push({ ...this.utilisateur });
          alert('Inscription réussie !');
          this.utilisateur = { nom: '', prenom: '', email: '', date: '', mdp: '' };
          this.isLoading = false; // ✅ Arrêt du chargement
        },
        error: err => {
          console.error('Erreur HTTP :', err);
          alert('Une erreur est survenue lors de l\'inscription');
          this.isLoading = false; // ✅ Même en cas d’erreur
        },
      });
  }
}
