<?php

namespace App\Controller;

use App\Enum\TransactionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Entity\Item;
use App\Entity\StockTransaction;
use App\Repository\StockTransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;

final class StockController extends AbstractController
{
    #[Route('/item/{id}/stock', name: 'app_item_stock')]
    public function move(Item $item, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('type', EnumType::class, [
                'class' => TransactionType::class,
                'choice_label' => fn (TransactionType $t) => $t->label(),
            ])
            ->add('quantity', IntegerType::class, [
                'constraints' => [new Assert\Positive()],
            ])
            ->add('note', TextType::class, [
                'required' => false,
                'constraints' => [new Assert\Length(max: 255)],
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $qty = $data['quantity'];

            if ($data['type'] === TransactionType::Out && $qty > $item->getQuantity()) {
                $form->get('quantity')->addError(
                    new FormError('Not enough stock. Only ' . $item->getQuantity() . ' available.')
                );
            } else {
                $em->wrapInTransaction(function () use ($em, $item, $data, $qty) {
                    $tx = new StockTransaction();
                    $tx->setItem($item);
                    $tx->setUser($this->getUser());
                    $tx->setType($data['type']);
                    $tx->setQuantity($qty);
                    $tx->setNote($data['note']);

                    $item->setQuantity(
                        $data['type'] === TransactionType::In
                            ? $item->getQuantity() + $qty
                            : $item->getQuantity() - $qty
                    );

                    $em->persist($tx);
                });
                $this->addFlash('success', 'Stock updated.');

                return $this->redirectToRoute('app_item_show', ['id' => $item->getId()]);
            }
        }

        return $this->render('stock/move.html.twig', ['item' => $item, 'form' => $form]);
    }

    #[Route('/transactions', name: 'app_transactions')]
    public function history(StockTransactionRepository $repo): Response
    {
        return $this->render('stock/history.html.twig', [
            'transactions' => $repo->findBy([], ['createdAt' => 'DESC'], 200),
        ]);
    }
}