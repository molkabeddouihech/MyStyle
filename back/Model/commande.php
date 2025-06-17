<?php
class Commande
{
    private $id_commande;
    private $date_commande;
    private $resume_articles;
    private $statut_commande;
    private $montant_total;

    // Constructeur
    public function __construct($id_commande, $date_commande, $resume_articles, $statut_commande, $montant_total)
    {
        $this->id_commande = $id_commande;
        $this->date_commande = $date_commande;
        $this->resume_articles = $resume_articles;
        $this->statut_commande = $statut_commande;
        $this->montant_total = $montant_total;
    }

    // Méthode pour modifier tous les attributs
    public function modifierCommande($id_commande, $date_commande, $resume_articles, $statut_commande, $montant_total)
    {
        $this->id_commande = $id_commande;
        $this->date_commande = $date_commande;
        $this->resume_articles = $resume_articles;
        $this->statut_commande = $statut_commande;
        $this->montant_total = $montant_total;
    }

    // Getters
    public function getIdCommande() { return $this->id_commande; }
    public function getDateCommande() { return $this->date_commande; }
    public function getResumeArticles() { return $this->resume_articles; }
    public function getStatutCommande() { return $this->statut_commande; }
    public function getMontantTotal() { return $this->montant_total; }

    // Setters
    public function setIdCommande($id_commande) { $this->id_commande = $id_commande; }
    public function setDateCommande($date_commande) { $this->date_commande = $date_commande; }
    public function setResumeArticles($resume_articles) { $this->resume_articles = $resume_articles; }
    public function setStatutCommande($statut_commande) { $this->statut_commande = $statut_commande; }
    public function setMontantTotal($montant_total) { $this->montant_total = $montant_total; }
}
?>
