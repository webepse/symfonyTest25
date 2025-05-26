<?php

namespace App\tests\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /**
     * Cette méthode est exécutée avant chaque test:
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();

        // pour récup l'entitymanagerinterface
        $this->manager = $container->get('doctrine')->getManager();

        // pour le besoin de mes test, je veux un nouveau Product
        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice(42.5);
        $product->setDescription('Test description');
        $product->setDate(new \DateTime());
        $this->manager->persist($product);
        $this->manager->flush();

        $this->productId = $product->getId();
    }

    /**
     * s'exécute après chaque test
     * @return void
     */
    protected function tearDown(): void
    {
        $product = $this->manager->getRepository(Product::class)->find($this->productId);
        if($product){
            $this->manager->remove($product);
            $this->manager->flush();
        }
        /* pcq quand tu redéfinis une méthode héritée (tearDown), le code du parent ne s'exécute plus automatiquement, donc il faut lui demande l'exécuter avec parent::tearDown() */
        parent::tearDown();
    }

    /**
     * vérifie si la page show s'affiche et que la structure est correcte
     * @return void
     *
     */
    public function testProductPageDisplaysCorrectly(): void
    {
        // on utilise le client (créé dans setUp())
        $this->client->request('GET', '/product/' . $this->productId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1','Test Product');
        $this->assertSelectorTextContains('p','Test description');
        $this->assertSelectorTextContains('.product-price','42.5€');
    }

    /**
     * test si je peux obtenir une page 404 avec un faux id
     * @return void
     */
    public function testProductNotFoundReturns404(): void
    {
        $this->client->request('GET', '/product/99999'); // ID supposé inexistant
        $this->assertResponseStatusCodeSame(404);
    }

    public function testSubmitNewProductForm(): void
    {
        $crawler = $this->client->request('GET', '/product/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            "product_form[name]" => "Test Product formulaire",
            "product_form[description]" => "Produit ajouté via test",
            'product_form[price]' => 19.99,
            "product_form[date]" => (new \DateTime())->format('Y-m-d'),
        ]);

        $this->client->submit($form);

        // redirection après succès
        $this->assertResponseRedirects();

        // suivre la redirection pour voir la page d'arrivée
        $this->client->followRedirect();

        $this->assertSelectorTextContains('h1','Les produits');
    }

}