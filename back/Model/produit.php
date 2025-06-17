<?php
class Produit
{
    private $id_produit;
    private $titre_produit;
    private $prix_produit;
    private $stock_disponible_produit;
    private $image_produit;
    private $genre_produit;

    // Constructeur
    public function __construct($id_produit, $titre_produit, $prix_produit, $stock_disponible_produit, $image_produit, $genre_produit)
    {
        $this->id_produit = $id_produit;
        $this->titre_produit = $titre_produit;
        $this->prix_produit = $prix_produit;
        $this->stock_disponible_produit = $stock_disponible_produit;
        $this->image_produit = $image_produit;
        $this->genre_produit = $genre_produit;
    }

    // Getters
    public function getIdProduit()
    {
        return $this->id_produit;
    }

    public function getTitreProduit()
    {
        return $this->titre_produit;
    }

    public function getPrixProduit()
    {
        return $this->prix_produit;
    }

    public function getStockDisponibleProduit()
    {
        return $this->stock_disponible_produit;
    }

    public function getImageProduit()
    {
        return $this->image_produit;
    }

    public function getGenreProduit()
    {
        return $this->genre_produit;
    }

    // Setters
    public function setIdProduit($id_produit)
    {
        $this->id_produit = $id_produit;
    }

    public function setTitreProduit($titre_produit)
    {
        $this->titre_produit = $titre_produit;
    }

    public function setPrixProduit($prix_produit)
    {
        $this->prix_produit = $prix_produit;
    }

    public function setStockDisponibleProduit($stock_disponible_produit)
    {
        $this->stock_disponible_produit = $stock_disponible_produit;
    }

    public function setImageProduit($image_produit)
    {
        $this->image_produit = $image_produit;
    }

    public function setGenreProduit($genre_produit)
    {
        $this->genre_produit = $genre_produit;
    }
}
?>
