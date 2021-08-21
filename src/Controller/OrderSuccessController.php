<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{


    private $entityManager;

    /**
     * OrderSuccessController constructor.
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        /**
         * @var $order Order
         */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getState() == 0) {
            $cart->remove();
            $order->setState(1);
            $this->entityManager->flush();

            // Envoie mail pour confirmer la commande

            $mail = new Mail();

            $content = "Bonjour " . $order->getUser()->getFullName() . "</br>";
            $content .= "<p>Merci pour votre commande <br> <br>
tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo <br>
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse <br> <br>
cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>";

            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFullName(), "Votre commande a bien été validée", $content);


            $this->addFlash('success', 'Votre commande a bien été validée. Un mail de confirmation vous a été envoyé');

        }


        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
