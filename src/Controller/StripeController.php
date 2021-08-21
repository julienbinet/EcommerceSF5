<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{

    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference): Response
    {

        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order) {
            return new JsonResponse(['error' => 'order']);
        }


        foreach ($order->getOrderDetails()->getValues() as $product) {
            /**
             * @var $product OrderDetails
             */

            //Remplace le findone by name par un findone by Id
            $product_ogject = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());

            $products_for_stripe[] = [

                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => $product->getPrice(),
                    'product_data' => [
                        'name'   => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN . "/uploads/products/" . $product_ogject->getIllustration()],
                    ],
                ],
                'quantity'   => $product->getQuantity(),
            ];
        }

        $products_for_stripe[] = [

            'price_data' => [
                'currency'     => 'eur',
                'unit_amount'  => $order->getCarrierPrice() ,
                'product_data' => [
                    'name'   => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN], // rajouter une image associé au transporteur
                ],
            ],
            'quantity'   => 1,
        ];


        Stripe::setApiKey('sk_test_WKCbwZ7hZNPTPsaQoIdq5HZa');


        $checkout_session = Session::create([
            'customer_email'       =>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items'           => [
                $products_for_stripe,
            ],
            'mode'                 => 'payment',
            'success_url'          => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url'           => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
