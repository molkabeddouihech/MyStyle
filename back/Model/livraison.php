<?php
class Livraison
{
    private $id_livraison;
    private $date_livraison;
    private $statut_livraison;
    private $adresse_livraison;
    private $id_commande;  // Nouvelle propriété ajoutée

    // Constructeur mis à jour
    public function __construct($id_livraison, $date_livraison, $statut_livraison, $adresse_livraison, $id_commande)
    {
        $this->id_livraison = $id_livraison;
        $this->date_livraison = $date_livraison;
        $this->statut_livraison = $statut_livraison;
        $this->adresse_livraison = $adresse_livraison;
        $this->id_commande = $id_commande;
    }

    // Méthode modifierLivraison mise à jour
    public function modifierLivraison($id_livraison, $date_livraison, $statut_livraison, $adresse_livraison, $id_commande)
    {
        $this->id_livraison = $id_livraison;
        $this->date_livraison = $date_livraison;
        $this->statut_livraison = $statut_livraison;
        $this->adresse_livraison = $adresse_livraison;
        $this->id_commande = $id_commande;
    }

    // Getters existants...
    public function getIdLivraison() { return $this->id_livraison; }
    public function getDateLivraison() { return $this->date_livraison; }
    public function getStatutLivraison() { return $this->statut_livraison; }
    public function getAdresseLivraison() { return $this->adresse_livraison; }
    
    // Nouveau getter pour id_commande
    public function getIdCommande() { return $this->id_commande; }

    // Setters existants...
    public function setIdLivraison($id_livraison) { $this->id_livraison = $id_livraison; }
    public function setDateLivraison($date_livraison) { $this->date_livraison = $date_livraison; }
    public function setStatutLivraison($statut_livraison) { $this->statut_livraison = $statut_livraison; }
    public function setAdresseLivraison($adresse_livraison) { $this->adresse_livraison = $adresse_livraison; }
    
    // Nouveau setter pour id_commande
    public function setIdCommande($id_commande) { $this->id_commande = $id_commande; }
}
?>