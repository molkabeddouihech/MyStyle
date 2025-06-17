<?php
session_start();

include '../Controller/produitc.php';

$controller = new ProduitController();
$produits = $controller->getAllProduits();



// Si un produit est en cours de modification (depuis GET)
$produitToEdit = null;
if (isset($_GET['id_produit'])) {
    foreach ($produits as $produit) {
        if ($produit['id_produit'] == $_GET['id_produit']) {
            $produitToEdit = $produit;
            break;
        }
    }
}

// Gestion de la recherche
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchType = isset($_GET['search_type']) ? $_GET['search_type'] : 'all';

if (!empty($searchTerm)) {
    $produits = array_filter($produits, function($produit) use ($searchTerm, $searchType) {
        switch ($searchType) {
            case 'id':
                return stripos($produit['id_produit'], $searchTerm) !== false;
            case 'titre':
                return stripos($produit['titre_produit'], $searchTerm) !== false;
            case 'stock':
                return stripos($produit['stock_disponible_produit'], $searchTerm) !== false;
            case 'prix':
                return stripos($produit['prix_produit'], $searchTerm) !== false;
            case 'genre':
                return stripos($produit['genre_produit'], $searchTerm) !== false;
            case 'all':
            default:
                return (stripos($produit['id_produit'], $searchTerm) !== false) ||
                       (stripos($produit['titre_produit'], $searchTerm) !== false) ||
                       (stripos($produit['stock_disponible_produit'], $searchTerm) !== false) ||
                       (stripos($produit['prix_produit'], $searchTerm) !== false) ||
                       (stripos($produit['genre_produit'], $searchTerm) !== false);
        }
    });
}

// Gestion du tri
$sort = isset($_GET['sort']) ? $_GET['sort'] : null;
$direction = isset($_GET['direction']) ? $_GET['direction'] : 'asc';

if ($sort === 'stock') {
    usort($produits, function($a, $b) use ($direction) {
        if ($direction === 'asc') {
            return $a['stock_disponible_produit'] - $b['stock_disponible_produit'];
        } else {
            return $b['stock_disponible_produit'] - $a['stock_disponible_produit'];
        }
    });
}

// Export Excel
if (isset($_GET['export_excel'])) {
    $controller->exportToExcel($produits);
}

// Récupération des données de formulaire temporairement stockées
if (!isset($produitToEdit) && isset($_SESSION['form_data'])) {
    $produitToEdit = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
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

    <title>MyStyle</title>
    <link rel="icon" type="image/png" href="../View/img/logoo.png">
   

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
        .icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 100%;
        }
        .bg-warning {
            background-color:rgb(0, 0, 0) !important;
        }
    </style>

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
   
    /* Nouveaux styles pour s'assurer que tous les fonds sont en mode sombre */
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
                <img src="../View/img/logoo.png" alt="logo" width="105" height="60">
            </a>

            

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

             
               
            <!-- Nav Item - Gestion des Produits et Dépôts -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGestion"
                    aria-expanded="true" aria-controls="collapseGestion">
                    <i class="fas fa-fw fa-box"></i>
                    <span><strong>Gestion de Produit</strong></span>
                </a>
                <div id="collapseGestion" class="collapse" aria-labelledby="headingGestion"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Gestion des Entités:</h6>
                        <a class="collapse-item" href="produit.php">Produits</a>
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

                   <h1 style="color: #5e17eb;"> Produit :</h1>
                   

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">


                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
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

<!-- Formulaire produit -->
<form id="formProduit" class="produit" action="../Controller/ajouterproduit.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">

    <div class="form-group">
        <input type="hidden" class="form-control form-control-user" name="id_produit"
               value="<?= isset($produitToEdit) ? htmlspecialchars($produitToEdit['id_produit']) : '' ?>">
    </div>

    <div class="form-group">
        <input type="text" class="form-control form-control-user" name="titre_produit" id="titre_produit" placeholder="Titre du Produit"
               value="<?= isset($produitToEdit) ? htmlspecialchars($produitToEdit['titre_produit']) : '' ?>">
        <div class="error-message" id="titre_produit_error" style="color: red;"></div>
    </div>

    <div class="form-group">
        <input type="text" class="form-control form-control-user" name="genre_produit" id="genre_produit" placeholder="Genre du Produit"
               value="<?= isset($produitToEdit) ? htmlspecialchars($produitToEdit['genre_produit']) : '' ?>">
        <div class="error-message" id="genre_produit_error" style="color: red;"></div>
    </div>

    <div class="form-group">
        <input type="number" class="form-control form-control-user" name="stock_disponible_produit" id="stock_disponible_produit" placeholder="Stock Disponible"
               value="<?= isset($produitToEdit) ? htmlspecialchars($produitToEdit['stock_disponible_produit']) : '' ?>">
        <div class="error-message" id="stock_disponible_produit_error" style="color: red;"></div>
    </div>

    <div class="form-group">
        <input type="number" step="0.01" class="form-control form-control-user" name="prix_produit" id="prix_produit" placeholder="Prix"
               value="<?= isset($produitToEdit) ? htmlspecialchars($produitToEdit['prix_produit']) : '' ?>">
        <div class="error-message" id="prix_produit_error" style="color: red;"></div>
    </div>

    <div class="form-group">
        <label for="image_produit">Image du produit:</label>
        <input type="file" class="form-control form-control-user" name="image_produit" id="image_produit" accept="image/*">
        <div class="error-message" id="image_produit_error" style="color: red;"></div>
        <?php if (isset($produitToEdit) && !empty($produitToEdit['image_produit'])): ?>
            <img src="../back/View/img/<?= htmlspecialchars($produitToEdit['image_produit']) ?>" width="100" class="mt-2">
        <?php endif; ?>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-success btn-user btn-block">
            <?= isset($produitToEdit) ? 'Modifier' : 'Ajouter' ?>
        </button>
    </div>
</form>


  <!-- Barre de recherche avancée -->
<h1 class="h4 text-gray-900 mb-4 text-left">
    <span style="color: #5e17eb;">Recherche Produit :</span>
</h1>
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="" class="form-inline">
            <div class="form-group mr-2">
                <select name="search_type" class="form-control">
                    <option value="all" <?= ($searchType === 'all') ? 'selected' : '' ?>>Tous les champs</option>
                    <option value="id" <?= ($searchType === 'id') ? 'selected' : '' ?>>ID Produit</option>
                    <option value="titre" <?= ($searchType === 'titre') ? 'selected' : '' ?>>Titre Produit</option>
                    <option value="stock" <?= ($searchType === 'stock') ? 'selected' : '' ?>>Stock Disponible</option>
                    <option value="prix" <?= ($searchType === 'prix') ? 'selected' : '' ?>>Prix</option>
                    <option value="genre" <?= ($searchType === 'genre') ? 'selected' : '' ?>>Genre</option>
                </select>
            </div>
            <div class="form-group mr-2 flex-grow-1">
                <input type="text" name="search" class="form-control w-100"
                       placeholder="Rechercher..." value="<?= htmlspecialchars($searchTerm) ?>">
            </div>
            <button type="submit" class="btn btn-primary mr-2">
                <i class="fas fa-search"></i> Rechercher
            </button>
            <a href="produit.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Réinitialiser
            </a>
        </form>
        <div class="search-results-count mt-2">
            <?= count($produits) ?> résultat(s) trouvé(s)
        </div>
    </div>
</div>



<div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h1 class="h4 text-gray-900 mb-4 text-left">
        <span style="color: #5e17eb;">Tables des Produit :</span>
    </h1>
    <div class="d-flex align-items-center">
        <a href="?export_excel=1" class="btn btn-success btn-sm mr-2">
            <i class="fas fa-file-excel"></i> Exporter en Excel
        </a>
        <button class="btn btn-info btn-sm" onclick="showStats()">
            Statistiques
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color: green;">Produits :</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" style="width: 100%;" id="dataTable">
                <thead>
                    <tr>
                        <th>ID Produit</th>
                        <th>Titre Produit</th>
                        <th>Genre</th>
                        <th>
                            <div class="d-flex align-items-center">
                                <span class="mr-2">Stock Disponible</span>
                                <div class="d-flex flex-column">
                                    <a href="?sort=stock&direction=asc" class="text-dark">
                                        <i class="fas fa-arrow-up <?= ($sort === 'stock' && $direction === 'asc') ? 'text-primary' : 'text-muted' ?>" style="font-size: 0.8em;"></i>
                                    </a>
                                    <a href="?sort=stock&direction=desc" class="text-dark">
                                        <i class="fas fa-arrow-down <?= ($sort === 'stock' && $direction === 'desc') ? 'text-primary' : 'text-muted' ?>" style="font-size: 0.8em;"></i>
                                    </a>
                                </div>
                            </div>
                        </th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($produits)): ?>
                        <?php foreach ($produits as $produit): ?>
                            <tr>
                                <td><?= htmlspecialchars($produit['id_produit']) ?></td>
                                <td><?= htmlspecialchars($produit['titre_produit']) ?></td>
                                <td><?= htmlspecialchars($produit['genre_produit']) ?></td>
                                <td><?= htmlspecialchars($produit['stock_disponible_produit']) ?></td>
                                <td><?= htmlspecialchars($produit['prix_produit']) ?> DT</td>
                                <td>
                                    <?php if (!empty($produit['image_produit'])): ?>
                                        <img src="../View/img/<?= htmlspecialchars($produit['image_produit']) ?>" width="50" height="50" style="object-fit: cover;">
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Aucune image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <a href="produit.php?id_produit=<?= $produit['id_produit'] ?>" class="btn btn-warning btn-sm w-100 text-center">
                                            Modifier
                                        </a>
                                        <br>
                                        <a href="../Controller/supprimerproduit.php?id_produit=<?= $produit['id_produit'] ?>" class="btn btn-danger btn-sm w-100 text-center"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                            Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">Aucun produit trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- ************** -->
</div>
<!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Acessoire 2025</span>
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
                    <a class="btn btn-primary" href="../frontoffice/acceuil.php">Déconnexion</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour les statistiques -->
<div class="modal fade" id="statsModal" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statsModalLabel">Statistiques des produits</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="chart-container" style="position: relative; height:400px; width:100%">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
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
    <script src="js/produit.js"></script>

    <!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
function showStats() {
    // Récupérer les données des produits
    <?php
    $chartData = [];
    foreach ($produits as $produit) {
        $chartData[] = [
            'label' => $produit['titre_produit'],
            'data' => $produit['stock_disponible_produit'],
            'backgroundColor' => sprintf('rgb(%d, %d, %d)', rand(0, 255), rand(0, 255), rand(0, 255))
        ];
    }
    ?>

    // Préparer les données pour le graphique
    const chartData = {
        labels: <?php echo json_encode(array_column($chartData, 'label')); ?>,
        datasets: [{
            data: <?php echo json_encode(array_column($chartData, 'data')); ?>,
            backgroundColor: <?php echo json_encode(array_column($chartData, 'backgroundColor')); ?>,
            borderWidth: 1
        }]
    };

    // Afficher le modal
    $('#statsModal').modal('show');

    // Créer le graphique après un petit délai
    setTimeout(() => {
        const ctx = document.getElementById('stockChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Répartition du stock par produit',
                        font: {
                            size: 18
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} unités (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }, 200);
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('themeToggleBtn');
    const icon = themeToggleBtn.querySelector('i');

    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    }

    themeToggleBtn.addEventListener('click', function() {
        if (document.body.classList.contains('dark-mode')) {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'disabled');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        } else {
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
