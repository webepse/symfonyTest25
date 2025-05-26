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

    public function testProductPageDisplaysCorrectly(): void
    {
        // on utilise le client (créé dans setUp())
        $this->client->request('GET', '/product/' . $this->productId);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1','Test Product');
        $this->assertSelectorTextContains('p','Test description');
        $this->assertSelectorTextContains('.product-price','42.5€');
    }

}