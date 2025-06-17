<?php
require_once __DIR__ . '/../../config.php'; // adapte le chemin selon ta structure
session_start();
$db = Config::getConnexion();

// Récupérer toutes les commandes
try {
    $sql = "SELECT * FROM commande ORDER BY date_commande DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
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

    <title>My Style</title>
    
    

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
        /* Ajoutez ceci dans votre balise <style> */
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
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers"
            aria-expanded="true" aria-controls="collapseUsers">
            <i class="fas fa-fw fa-users"></i>
            <span>Gestion des Utilisateurs</span>
        </a>
        <div id="collapseUsers" class="collapse" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des Utilisateurs:</h6>
                <a class="collapse-item" href="utilisateur.php">Utilisateurs</a>
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
                    <!-- Table des commandes -->
    <h1 class="h3 mb-2" style="color: #5e17eb;">Table des Commandes :</h1>

                

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
    

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold" style="color: #5e17eb;">Commandes :</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Commande</th>
                            <th>Date Commande</th>
                            <th>Resumés</th>
                            <th>Statut Commande</th>
                            <th>Montant Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= htmlspecialchars($commande['id_commande']) ?></td>
                            <td><?= htmlspecialchars($commande['date_commande']) ?></td>
                            <td><?= htmlspecialchars($commande['resume_articles']) ?></td>
                            <td>
    <span class="badge fs-5 py-2 px-3 <?= (strtolower($commande['statut_commande']) == 'en attente') ? 'bg-warning text-dark' : 
                                      ($commande['statut_commande'] === 'livrée' ? 'bg-success' : 'bg-info') ?>">
        <?= htmlspecialchars($commande['statut_commande']) ?>
    </span>
</td>
                            <td><?= number_format($commande['montant_total'], 2, '.', ' ') ?> DT</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les articles -->
    <div class="modal fade" id="articlesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails des articles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalArticlesContent">
                    <!-- Contenu chargé dynamiquement -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<!-- Scripts JavaScript -->
<script>
    // Fonction pour afficher les articles dans un modal
    function afficherArticles(articles) {
        const modalContent = document.getElementById('modalArticlesContent');
        const articlesList = articles.split('\n').filter(item => item.trim() !== '');
        
        let html = '<div class="list-group">';
        articlesList.forEach(item => {
            html += `<div class="list-group-item">${item}</div>`;
        });
        html += '</div>';
        
        modalContent.innerHTML = html;
        
        // Afficher le modal
        const modal = new bootstrap.Modal(document.getElementById('articlesModal'));
        modal.show();
    }
</script>

                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2025</span>
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
                <div class="modal-body">Sélectionnez « Déconnexion » ci-dessous si vous êtes prêt à mettre fin à votre session en cours.</div>
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
    <!-- Script pour le mode sombre -->
    <script>
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
    </script>

</body>

</html>