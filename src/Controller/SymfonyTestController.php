<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/send-email', name: 'send_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {


        $email = (new Email())
            ->from('sender@example.com')
            ->to('recipient@example.com')
            ->subject('Test Email')
            ->text('This is a test email.')
            ->html('<p>This is a test email.</p>');

        try {
            $mailer->send($email);
            return new Response('Email sent successfully '.$_ENV['MAILER_DSN']);
        } catch (\Exception $e) {
            return new Response('Failed to send email: '.$e->getMessage());
        }
    }


}
