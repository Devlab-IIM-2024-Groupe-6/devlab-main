<?php

namespace App\Service\Client;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

class ClientService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Récupère un client par son email.
     * S'il n'existe pas, le crée avec les infos fournies (firstname, lastname, etc.).
     * Ne met à jour que les champs fournis (évite d'écraser des valeurs existantes avec null).
     */
    public function getOrCreateClient(?string $email, ?string $firstname, ?string $lastname): ?Client
    {
        // Vérifie si l'email est valide (nécessaire pour identifier un client)
        if (empty($email)) {
            return null; // Retourne null si aucun email n'est fourni
        }

        $clientRepository = $this->entityManager->getRepository(Client::class);
        $client = $clientRepository->findOneBy(['email' => $email]);

        // Si le client n'existe pas, on le crée
        if (!$client) {
            $client = new Client();
            $client->setEmail($email);
        }

        // Mise à jour seulement si les valeurs sont non nulles et non vides
        if (!empty($firstname) && $client->getFirstname() !== $firstname) {
            $client->setFirstname($firstname);
        }

        if (!empty($lastname) && $client->getLastname() !== $lastname) {
            $client->setLastname($lastname);
        }

        return $client;
    }
}
