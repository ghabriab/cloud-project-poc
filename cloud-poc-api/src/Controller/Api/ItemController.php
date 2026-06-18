<?php

namespace App\Controller\Api;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/items', name: 'api_items_')]
class ItemController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse {
        $items = $em->getRepository(Item::class)->findAll();
        $data = array_map(fn($i) => [
            'id' => $i->getId(),
            'name' => $i->getName(),
            'description' => $i->getDescription()
        ], $items);
        return $this->json($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse {
        $payload = json_decode($request->getContent(), true);

        $item = new Item();
        $item->setName($payload['name'] ?? '');
        $item->setDescription($payload['description'] ?? '');

        $em->persist($item);
        $em->flush();

        return $this->json(['id' => $item->getId(), 'name' => $item->getName()], 201);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse {
        $item = $em->getRepository(Item::class)->find($id);
        if (!$item) {
            return $this->json(['message' => 'Not found'], 404);
        }
        $em->remove($item);
        $em->flush();
        return $this->json(['success' => true]);
    }
}
