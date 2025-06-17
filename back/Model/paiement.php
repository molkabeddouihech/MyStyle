<?php
class Paiement
{
    private $id_paiement;
    private $methode_paiement;
    private $date_paiement;
    private $statut_paiement;
    private $id_livraison;

    // Constructeur
    public function __construct($id_paiement, $methode_paiement, $date_paiement, $statut_paiement, $id_livraison)
    {
        $this->id_paiement = $id_paiement;
        $this->methode_paiement = $methode_paiement;
        $this->date_paiement = $date_paiement;
        $this->statut_paiement = $statut_paiement;
        $this->id_livraison = $id_livraison;
    }

    // Méthode pour modifier tous les attributs
    public function modifierPaiement($id_paiement, $methode_paiement, $date_paiement, $statut_paiement, $id_livraison)
    {
        $this->id_paiement = $id_paiement;
        $this->methode_paiement = $methode_paiement;
        $this->date_paiement = $date_paiement;
        $this->statut_paiement = $statut_paiement;
        $this->id_livraison = $id_livraison;
    }

    // Getters
    public function getIdPaiement() { return $this->id_paiement; }
    public function getMethodePaiement() { return $this->methode_paiement; }
    public function getDatePaiement() { return $this->date_paiement; }
    public function getStatutPaiement() { return $this->statut_paiement; }
    public function getIdLivraison() { return $this->id_livraison; }

    // Setters
    public function setIdPaiement($id_paiement) { $this->id_paiement = $id_paiement; }
    public function setMethodePaiement($methode_paiement) { $this->methode_paiement = $methode_paiement; }
    public function setDatePaiement($date_paiement) { $this->date_paiement = $date_paiement; }
    public function setStatutPaiement($statut_paiement) { $this->statut_paiement = $statut_paiement; }
    public function setIdLivraison($id_livraison) { $this->id_livraison = $id_livraison; }
}
?>