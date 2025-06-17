<?php
require_once __DIR__ . '/../Model/produit.php';

require_once __DIR__ . '/../config.php';

class ProduitController
{
    // Ajouter un produit
    public function ajouterProduit($produit)
    {
        try {
            $pdo = config::getConnexion();

            $query = $pdo->prepare(
                'INSERT INTO produits (titre_produit, genre_produit, stock_disponible_produit, prix_produit, image_produit) 
                 VALUES (:titre_produit, :genre_produit, :stock_disponible_produit, :prix_produit, :image_produit)'
            );

            $query->execute([
                'titre_produit' => $produit->getTitreProduit(),
                'genre_produit' => $produit->getGenreProduit(),
                'stock_disponible_produit' => $produit->getStockDisponibleProduit(),
                'prix_produit' => $produit->getPrixProduit(),
                'image_produit' => $produit->getImageProduit()
            ]);

            echo "Produit ajouté avec succès !";

        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Obtenir le dernier ID produit
    public function getLastIdProduit()
    {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->query('SELECT MAX(id_produit) AS last_id FROM produits');
            $result = $query->fetch();
            return $result['last_id'] ?? 0;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Obtenir un produit par ID
    public function getProduitById($id_produit)
    {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('SELECT * FROM produits WHERE id_produit = :id_produit');
            $query->execute(['id_produit' => $id_produit]);
            return $query->fetch();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }


    // Obtenir tous les produits
    public function getAllProduits()
    {
        $pdo = config::getConnexion();
        $sql = "SELECT * FROM produits";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprimer un produit
    public function supprimerProduit($id_produit)
    {
        try {
            $pdo = config::getConnexion();
            $query = $pdo->prepare('DELETE FROM produits WHERE id_produit = :id_produit');
            $query->execute(['id_produit' => $id_produit]);
            echo "Produit supprimé avec succès !";
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    // Modifier un produit
    public function modifierProduit($produit)
    {
        try {
            $pdo = config::getConnexion();

            $existingProduct = $this->getProduitById($produit->getIdProduit());
            if (!$existingProduct) {
                throw new Exception("Le produit à modifier n'existe pas.");
            }

            $query = $pdo->prepare(
                'UPDATE produits SET 
                 titre_produit = :titre_produit, 
                 genre_produit = :genre_produit, 
                 stock_disponible_produit = :stock_disponible_produit,
                 prix_produit = :prix_produit,
                 image_produit = :image_produit
                 WHERE id_produit = :id_produit'
            );

            $query->execute([
                'id_produit' => $produit->getIdProduit(),
                'titre_produit' => $produit->getTitreProduit(),
                'genre_produit' => $produit->getGenreProduit(),
                'stock_disponible_produit' => $produit->getStockDisponibleProduit(),
                'prix_produit' => $produit->getPrixProduit(),
                'image_produit' => $produit->getImageProduit()
            ]);
            echo "Produit modifié avec succès !";

        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());        }
    }

    public function exportToExcel($produits) {
    header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
    header("Content-Disposition: attachment; filename=produits_export_" . date('Y-m-d_H-i') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Ajouter le BOM UTF-8
    echo "\xEF\xBB\xBF";

    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">
    <head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Produits</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .high-stock {
            background-color: #d4edda;
            color: #155724;
            font-weight: bold;
        }
        .low-stock {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }
        .price-cell {
            background-color: #e2e3e5;
            color: #383d41;
            font-weight: bold;
        }
        .header-row {
            background-color: #343a40 !important;
            color: white;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            padding: 10px;
        }
    </style>
    </head>
    <body>';

    echo '<table>';
    echo '<tr><td colspan="6" class="title">Liste des Produits - ' . date('d/m/Y') . '</td></tr>';
    echo '<tr>
            <th style="width:5%;">ID</th>
            <th style="width:25%;">Titre</th>
            <th style="width:15%;">Genre</th>
            <th style="width:15%;">Stock</th>
            <th style="width:15%;">Prix</th>
            <th style="width:25%;">Image</th>
          </tr>';

    foreach ($produits as $produit) {
        $stockClass = ($produit['stock_disponible_produit'] > 10) ? 'high-stock' : 'low-stock';
        $stockText = ($produit['stock_disponible_produit'] > 10) ? '✓ Bon stock' : '! Stock faible';

        echo '<tr>';
        echo '<td style="text-align:center;">' . htmlspecialchars($produit['id_produit'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($produit['titre_produit'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($produit['genre_produit'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td class="' . $stockClass . '" style="text-align:center;">' . $stockText . '<br><small>(' . htmlspecialchars($produit['stock_disponible_produit'], ENT_QUOTES, 'UTF-8') . ' unités)</small></td>';
        echo '<td class="price-cell" style="text-align:right;">' . number_format($produit['prix_produit'], 2, ',', ' ') . ' DT</td>';
        echo '<td><img src="../../assets/img/' . htmlspecialchars($produit['image_produit'], ENT_QUOTES, 'UTF-8') . '" alt="image" width="50"></td>';
        echo '</tr>';
    }

    $totalProduits = count($produits);
    $totalStock = array_sum(array_column($produits, 'stock_disponible_produit'));
    $totalValeur = array_sum(array_map(function ($p) {
        return $p['stock_disponible_produit'] * $p['prix_produit'];
    }, $produits));

    echo '<tr class="header-row"><th colspan="6">Récapitulatif</th></tr>';
    echo '<tr>
            <td colspan="3" style="text-align:right;font-weight:bold;">Total Produits:</td>
            <td style="text-align:center;font-weight:bold;">' . $totalProduits . '</td>
            <td colspan="2"></td>
          </tr>';
    echo '<tr>
            <td colspan="3" style="text-align:right;font-weight:bold;">Total Stock:</td>
            <td style="text-align:center;font-weight:bold;">' . $totalStock . ' unités</td>
            <td colspan="2"></td>
          </tr>';
    echo '<tr>
            <td colspan="3" style="text-align:right;font-weight:bold;">Valeur Totale:</td>
            <td colspan="3" style="text-align:left;font-weight:bold;">' . number_format($totalValeur, 2, ',', ' ') . ' DT</td>
          </tr>';

    echo '</table></body></html>';
    exit;
}


}
