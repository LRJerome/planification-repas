<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'app_forgot_password_request')]
    public function request(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                try {
                    $token = $tokenGenerator->generateToken();
                    $user->setResetToken($token);
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $resetEmail = (new Email())
                        ->from('noreply@planification-repas.fr')
                        ->to($user->getEmail())
                        ->subject('Réinitialisation de votre mot de passe')
                        ->html(
                            $this->renderView(
                                'reset_password/email.html.twig',
                                ['token' => $token]
                            )
                        );

                    $logger->info('Tentative d\'envoi d\'email à : ' . $user->getEmail());
                    $mailer->send($resetEmail);
                    $logger->info('Email envoyé avec succès');

                    $this->addFlash('success', 'Un email de réinitialisation vous a été envoyé');
                    return $this->redirectToRoute('app_login');
                } catch (\Exception $e) {
                    $logger->error('Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
                    $this->addFlash('error', 'Une erreur est survenue lors de l\'envoi de l\'email');
                }
            } else {
                $logger->info('Tentative de réinitialisation pour un email non trouvé : ' . $email);
            }

            $this->addFlash('error', 'Cette adresse email n\'est pas enregistrée');
        }

        return $this->render('reset_password/request.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(
        string $token,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ): Response {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $logger->warning('Tentative de réinitialisation avec un token invalide : ' . $token);
            $this->addFlash('error', 'Token invalide');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $user->setResetToken(null);
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $request->request->get('password')
                )
            );
            
            $entityManager->persist($user);
            $entityManager->flush();

            $logger->info('Mot de passe réinitialisé avec succès pour l\'utilisateur : ' . $user->getEmail());
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/reset.html.twig', ['token' => $token]);
    }
} 