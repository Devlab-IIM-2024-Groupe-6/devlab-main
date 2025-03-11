<?php

namespace App\Service\Client;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Récupère un client par son email.
     * S'il n'existe pas, le crée avec les infos fournies (firstname, lastname, etc.).
     */
    public function getOrCreateClient(
        string $email,
        string $firstname,
        string $lastname
    ): Client {
        // On suppose que l'email est unique pour identifier un client.
        $clientRepository = $this->entityManager->getRepository(Client::class);

        // Cherche un client existant
        $existingClient = $clientRepository->findOneBy(['email' => $email]);

        if ($existingClient) {
            return $existingClient;
        }

        // Sinon, création d'un nouveau Client
        $client = new Client();
        $client->setEmail($email);
        $client->setFirstname($firstname);
        $client->setLastname($lastname);

        $this->entityManager->persist($client);
        // Selon la logique de votre application, vous pouvez flush ici,
        // ou laisser le flush se faire plus tard dans le contrôleur.
        $this->entityManager->flush();

        return $client;
    }
}
