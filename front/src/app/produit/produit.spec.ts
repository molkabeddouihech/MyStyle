import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ProduitComponent } from './produit.component';
import { PanierService } from '../panier/panier.service';
import { CommonModule } from '@angular/common';

describe('ProduitComponent', () => {
  let component: ProduitComponent;
  let fixture: ComponentFixture<ProduitComponent>;
  let panierService: jasmine.SpyObj<PanierService>;

  beforeEach(async () => {
    panierService = jasmine.createSpyObj('PanierService', ['ajouterAuPanier', 'getNombreTotalProduits']);
    
    await TestBed.configureTestingModule({
      imports: [CommonModule, ProduitComponent],
      providers: [
        { provide: PanierService, useValue: panierService }
      ]
    }).compileComponents();

    fixture = TestBed.createComponent(ProduitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should add product to cart', () => {
    const testProduct = { 
      id: 1, 
      titre: 'Test Product', 
      prix: 100, 
      image: 'test.jpg',
      genre: 'homme',
      couleurs: ['#000000']
    };
    component.ajouterAuPanier(testProduct);
    expect(panierService.ajouterAuPanier).toHaveBeenCalledWith({
      id: 1,
      titre: 'Test Product',
      prix: 100,
      image: 'test.jpg'
    });
  });

  it('should filter products by genre', () => {
    component.genreFiltre = 'femme';
    const filtered = component.produitsFiltres;
    expect(filtered.length).toBeGreaterThan(0);
    expect(filtered.every(p => p.genre === 'femme')).toBeTrue();
  });

  it('should display total products in cart', () => {
    panierService.getNombreTotalProduits.and.returnValue(3);
    expect(component.getNombreTotalProduits()).toBe(3);
  });
});