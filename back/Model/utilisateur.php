<?php
require_once __DIR__ . '/../../config.php'; // adapte le chemin selon ta structure
session_start();
$db = Config::getConnexion();

// Récupérer tous les utilisateurs
try {
    $sql = "SELECT * FROM utilisateur ORDER BY nom, prenom";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Code de débogage - à supprimer après vérification
echo "<pre>";
print_r($utilisateurs[0] ?? 'Aucun utilisateur trouvé');
echo "</pre>";

// Traitement des actions (suppression, modification, etc.)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete':
                if (isset($_POST['id'])) {
                    try {
                        $sql = "DELETE FROM utilisateur WHERE id = :id";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':id', $_POST['id']);
                        $stmt->execute();
                        header('Location: utilisateur.php?success=deleted');
                        exit;
                    } catch (PDOException $e) {
                        $error = "Erreur lors de la suppression : " . $e->getMessage();
                    }
                }
                break;
                
            case 'add':
                try {
                    $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, date) 
                            VALUES (:nom, :prenom, :email, :mdp, :date)";
                    $stmt = $db->prepare($sql);
                    $nom = $_POST['nom'];
                    $prenom = $_POST['prenom'];
                    $email = $_POST['email'];
                    $mdp_hash = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
                    $date = $_POST['date'];

                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':prenom', $prenom);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':mdp', $mdp_hash);
                    $stmt->bindParam(':date', $date);
                    $stmt->execute();
                    header('Location: utilisateur.php?success=added');
                    exit;
                } catch (PDOException $e) {
                    $error = "Erreur lors de l'ajout : " . $e->getMessage();
                }
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>My Style - Gestion des Utilisateurs</title>
    
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .bg-gradient-primary {
            background-color: rgb(0, 0, 0) !important;
            background-image: none !important;
        }
        .table-responsive {
            margin: 20px 0;
        }
        .action-buttons {
            min-width: 120px;
        }
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            animation: slideIn 0.5s forwards;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .btn-spinner {
            position: relative;
        }

        .btn-spinner::after {
            content: '';
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-radius: 50%;
            border-top-color: currentColor;
            animation: spin 1s linear infinite;
            margin-left: 0.5rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5e17eb, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
    <!-- Styles pour le mode sombre -->
    <style>
        /* Styles pour le mode sombre */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        
        body.dark-mode .bg-white {
            background-color: #1e1e1e !important;
        }
        
        body.dark-mode .card {
            background-color: #2d2d2d;
            border-color: #444;
        }
        
        body.dark-mode .card-header {
            background-color: #333;
            border-color: #444;
        }
        
        body.dark-mode .table {
            color: #e0e0e0;
        }
        
        body.dark-mode .table-bordered {
            border-color: #444;
        }
        
        body.dark-mode .table-bordered td, 
        body.dark-mode .table-bordered th {
            border-color: #444;
        }
        
        body.dark-mode .form-control {
            background-color: #333;
            border-color: #555;
            color: #e0e0e0;
        }
        
        body.dark-mode .text-gray-900 {
            color: #e0e0e0 !important;
        }
        
        body.dark-mode .text-gray-600 {
            color: #bbb !important;
        }
        
        body.dark-mode .shadow {
            box-shadow: 0 .15rem 1.75rem 0 rgba(0, 0, 0, .5) !important;
        }
        
        body.dark-mode .dropdown-menu {
            background-color: #2d2d2d;
            border-color: #444;
        }
        
        body.dark-mode .dropdown-item {
            color: #e0e0e0;
        }
        
        body.dark-mode .dropdown-item:hover {
            background-color: #3d3d3d;
        }
        
        body.dark-mode .modal-content {
            background-color: #2d2d2d;
            color: #e0e0e0;
        }
        
        /* Styles pour s'assurer que tous les fonds sont en mode sombre */
        body.dark-mode #content {
            background-color: #121212;
        }
        
        body.dark-mode #content-wrapper {
            background-color: #121212;
        }
        
        body.dark-mode .container-fluid {
            background-color: #121212;
        }
        
        body.dark-mode .topbar {
            background-color: #1e1e1e !important;
        }
        
        body.dark-mode .navbar-light {
            background-color: #1e1e1e !important;
        }
        
        body.dark-mode .navbar-light .navbar-nav .nav-link {
            color: #e0e0e0;
        }
        
        body.dark-mode .sticky-footer {
            background-color: #1e1e1e !important;
        }
        
        body.dark-mode .bg-white {
            background-color: #1e1e1e !important;
        }
        
        body.dark-mode .bg-light {
            background-color: #333 !important;
        }
        
        body.dark-mode .btn-light {
            background-color: #444;
            border-color: #555;
            color: #e0e0e0;
        }
        
        body.dark-mode .btn-outline-secondary {
            color: #e0e0e0;
            border-color: #666;
        }
        
        body.dark-mode .btn-outline-secondary:hover {
            background-color: #444;
            color: #fff;
        }
        
        /* Transition pour un changement fluide */
        body, .card, .table, .form-control, .bg-white, .dropdown-menu, #content, #content-wrapper, .container-fluid, .topbar, .navbar-light, .sticky-footer {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <img src="../View/img/logo.png" alt="logo" width="150" height="60">
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Gestion des Utilisateurs -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers"
                    aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Gestion des Utilisateurs</span>
                </a>
                <div id="collapseUsers" class="collapse show" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Gestion des Utilisateurs:</h6>
                        <a class="collapse-item active" href="utilisateur.php">Utilisateurs</a>
                        <a class="collapse-item" href="fidelite.php">Fidélité</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Gestion des Produits et Dépôts -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGestion"
                aria-expanded="true" aria-controls="collapseGestion">
                 <i class="fas fa-fw fa-box"></i>
                 <span>Gestion de Produit</span>
               </a>
               <div id="collapseGestion" class="collapse" aria-labelledby="headingGestion"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Gestion des Entités:</h6>
                    <a class="collapse-item" href="produit.html">Produits</a>
                    <a class="collapse-item" href="depot.html">Dépôts</a>
                </div>
              </div>
          </li>

            <!-- Nav Item - Gestion des Livraisons et Commandes -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLivraison"
                    aria-expanded="true" aria-controls="collapseLivraison">
                    <i class="fas fa-fw fa-box"></i>
                    <span><strong>Gestion de Livraison</strong></span>
                </a>
                <div id="collapseLivraison" class="collapse" aria-labelledby="headingLivraison" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header" style="color: #5e17eb;">Les commandes:</h6>
                        <a class="collapse-item" href="commande.php">Commande</a>
                        <a class="collapse-item" href="livraison.php">Livraison</a>
                        <a class="collapse-item" href="paiement.php">Paiements</a>
                        <a class="collapse-item" href="promo.php">code promo</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Table des utilisateurs -->
                    <h1 class="h3 mb-2" style="color: #5e17eb;">Gestion des Utilisateurs :</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Bouton Mode Sombre -->
                        <li class="nav-item no-arrow mx-1">
                            <button id="themeToggleBtn" class="btn btn-link nav-link">
                                <i class="fas fa-moon fa-fw"></i>
                            </button>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">utilisateur</span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Paramètres
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Journal d'activité
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Déconnexion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Messages d'alerte -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php 
                            switch($_GET['success']) {
                                case 'added': echo 'Utilisateur ajouté avec succès !'; break;
                                case 'deleted': echo 'Utilisateur supprimé avec succès !'; break;
                                case 'updated': echo 'Utilisateur modifié avec succès !'; break;
                            }
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Bouton d'ajout -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal" style="background-color: #5e17eb; border-color: #5e17eb;">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Ajouter un utilisateur
                        </button>
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold" style="color: #5e17eb;">Liste des Utilisateurs :</h6>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Avatar</th>
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Email</th>
                                            <th>Date de naissance</th>
                                            <th>mot de passe </th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($utilisateurs as $utilisateur): ?>
                                        <tr>
                                            <td>
                                                <div class="user-avatar">
                                                    <?= strtoupper(substr($utilisateur['prenom'], 0, 1) . substr($utilisateur['nom'], 0, 1)) ?>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($utilisateur['nom'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($utilisateur['prenom'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($utilisateur['email'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($utilisateur['date']) ?></td>
                                            <td class="action-buttons">
                                                <button class="btn btn-sm btn-info" onclick="editUser(<?= $utilisateur['id'] ?>)" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteUser(<?= $utilisateur['id'] ?>)" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal d'ajout d'utilisateur -->
                    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addUserModalLabel">Ajouter un utilisateur</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="add">
                                        
                                        <div class="form-group">
                                            <label for="nom">Nom</label>
                                            <input type="text" class="form-control" id="nom" name="nom" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="prenom">Prénom</label>
                                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="date">Date de naissance</label>
                                            <input type="date" class="form-control" id="date" name="date" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="mdp">Mot de passe</label>
                                            <input type="password" class="form-control" id="mdp" name="mdp" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary" style="background-color: #5e17eb; border-color: #5e17eb;">Ajouter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; My Style 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Prêt à sortir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Sélectionnez « Déconnexion » ci-dessous si vous êtes prêt à mettre fin à votre session en cours.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Déconnexion</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Scripts JavaScript -->
    <script>
        // Fonction pour supprimer un utilisateur
        function deleteUser(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Fonction pour modifier un utilisateur (à implémenter)
        function editUser(id) {
            // Ici vous pouvez implémenter la logique de modification
            alert('Fonctionnalité de modification à implémenter pour l\'utilisateur ID: ' + id);
        }

        // Script pour le mode sombre
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const icon = themeToggleBtn.querySelector('i');
            
            // Vérifier si le mode sombre est déjà activé dans localStorage
            if(localStorage.getItem('darkMode') === 'enabled') {
                document.body.classList.add('dark-mode');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
            
            // Fonction pour basculer le mode sombre
            themeToggleBtn.addEventListener('click', function() {
                if(document.body.classList.contains('dark-mode')) {
                    // Désactiver le mode sombre
                    document.body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'disabled');
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                } else {
                    // Activer le mode sombre
                    document.body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'enabled');
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

</body>

</html>
