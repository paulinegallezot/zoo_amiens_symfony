<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Entity\User;
use App\Form\UserType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[IsGranted('ROLE_ADMIN')]
class UserController extends AdminLayoutController
{

    protected string $entityName = 'User';
    protected string $entityTitle = 'Utilisateurs';
    protected string $entityTitleSingular = 'Utilisateur';
    protected string $gender = 'm';
    protected string $render = 'global';


    #[Route('/admin/user', name: 'app_admin_user_index')]
    public function index(): Response
    {

        return parent::indexCRUD();
    }

    #[Route('/admin/user/new/{type}', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher,ValidatorInterface $validator, string $type): Response
    {
        $autorizedType=['employe','veto'];
        if (!in_array($type, $autorizedType)) {
            throw $this->createNotFoundException('Type d\'utilisateur non valide');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->setUserRole($user, $type);
            $this->handlePassword($form, $user, $passwordHasher);
            $this->validateUser($user, $validator);
            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash('success', 'Utilisateur ajouté avec succès.');

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);

            }else{
                return $this->redirectToRoute('app_admin_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
            }


        }

        return $this->render('admin/global/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'form_template' => "admin/user/_form.html.twig",
            'page_title' => "Utilisateur / Ajouter un utilisateur",
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator,EmailService $emailService): Response
    {
        $form = $this->createForm(UserType::class, $user,['password_required' => false,'password_edit_or_new'=>'Laissez vide pour conserver le mot de passe']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->handlePassword($form, $user, $passwordHasher);
            $this->validateUser($user, $validator);
            $entityManager->flush();

            $this->handleEmailNotification($form, $user, $emailService);

            $this->addFlash('success', 'Utilisateur modifié avec succès.');


            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_user', [], Response::HTTP_SEE_OTHER);
            }
            // On redirige dans ce cas pour eviter l'affiche du mot de passe.
            return $this->redirectToRoute('app_admin_user_edit', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);

        }


        return $this->render('admin/global/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'form_template' => "admin/user/_form.html.twig",
            'page_title' => "Utilisateurs / Editer un utilisateur",
        ]);
    }

    #[Route('/admin/user/login-as/{id}', name: 'app_admin_user_login_as')]
    public function loginAs(User $user, Request $request, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage,EventDispatcherInterface $eventDispatcher ): Response
    {
        // Nom du pare-feu
        $firewallName = 'main';

        // Création d'un jeton d'authentification pour l'utilisateur
        $token = new UsernamePasswordToken($user, $firewallName, $user->getRoles());

        // Mise à jour du token dans le contexte de sécurité
        $tokenStorage->setToken($token);

        // Déclenchement de l'événement de login interactif pour mettre à jour le contexte de la session
        $event = new InteractiveLoginEvent($request, $token);
        $eventDispatcher->dispatch($event, 'security.interactive_login');

        // Redirection vers la page d'accueil de l'admin ou une autre page après la connexion
        return $this->redirectToRoute('app_admin');
    }


    #[Route('/admin/user/ajax_delete', name: 'ajax_user_delete', methods: ['DELETE'])]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/user/ajax_datatable', name: 'ajax_user_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }


    private function setUserRole(User $user, string $type): void
    {
        if ($type === 'veto') {
            $user->setRoles(['ROLE_VETO']);
        } elseif ($type === 'employe') {
            $user->setRoles(['ROLE_EMPLOYE']);
        }
    }

    private function handlePassword($form, User $user, UserPasswordHasherInterface $passwordHasher): void
    {
        $newPassword = $form->get('password')->getData();
        if ($newPassword) {
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            $user->setPassword($hashedPassword);
        }
    }

    private function validateUser(User $user, ValidatorInterface $validator): void
    {
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            throw $this->createNotFoundException('Erreur dans la validation du formulaire');
        }
    }

    private function handleEmailNotification($form, User $user, EmailService $emailService): void
    {
        $mustSendEmail = $form->get('send_email')->getData();
        if ($mustSendEmail) {
            $context = [
                'subject' => 'Administration de Arcadia Zoo',
                'title' => null,
                'user' => $user,
            ];
            $emailService->sendEmail($user->getEmail(), $context, 'signup');
        }
    }

}
