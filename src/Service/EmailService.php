<?php

// src/Service/EmailService.php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Twig\Environment;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer, private readonly Environment $twig, private Security $security, private ?string $senderEmailAdmin = null
    ) {}

    public function sendEmail(string $to, array $context, string $template): void
    {
        $user = $this->security->getUser();
        $senderEmail = $this->senderEmailAdmin ?? $user->getEmail();

        $email = (new TemplatedEmail())
            ->from($senderEmail)
            ->to($to)
            ->subject($context['subject'])
            ->htmlTemplate("emails/email_{$template}.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }
}
