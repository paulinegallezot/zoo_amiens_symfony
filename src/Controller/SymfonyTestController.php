<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SymfonyTestController extends AbstractController
{
    #[Route('/test-db', name: 'test-db')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        try {
            $connection = $entityManager->getConnection();
            $connection->connect();

            if ($connection->isConnected()) {
                $params = $connection->getParams(); // @internal Acceptable pour le test de connection
                $databaseName = $params['dbname'] ?? 'N/A';
                $host = $params['host'] ?? 'N/A';
                $port = $params['port'] ?? 'N/A';
                $username = $params['user'] ?? 'N/A';

                return new Response(
                    "Connected to database '{$databaseName}' on host '{$host}:{$port}' with username '{$username}' successfully."
                );
            } else {
                return new Response("Failed to connect to database.");
            }
        } catch (\Exception $e) {
            return new Response("Failed to connect to database: " . $e->getMessage());
        }
    }


}
