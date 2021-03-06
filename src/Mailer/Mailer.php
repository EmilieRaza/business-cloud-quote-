<?php

namespace App\Mailer;

use App\Entity\ContactMessage;
use App\Entity\QuotationRequest;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class Mailer
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var ParameterBagInterface
     */
    protected $parameterBag;

    /**
     * Mailer constructor.
     *
     */
    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, Environment $templating, TranslatorInterface $translator, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->parameterBag = $parameterBag;
    }

    public function sendRegistration(User $user, string $locale)
    {
        $url = $this->router->generate(
            'app_registration_confirm',
            [
                '_locale' => $locale,
                'token' => $user->getConfirmationToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $email = (new TemplatedEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($user->getEmail())
            ->subject($this->translator->trans('registration.email.subject', ['%user%' => $user], 'security'))
            ->htmlTemplate('front/email/register.html.twig')
            ->context([
                'user' => $user,
                'website_name' => $this->parameterBag->get('app.name'),
                'confirmation_url' => $url,
            ]);
        $this->mailer->send($email);
    }

    public function sendForgetPassword(User $user, string $locale)
    {
        $url = $this->router->generate(
            'app_reset_password',
            [
                '_locale' => $locale,
                'token' => $user->getConfirmationToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $email = (new TemplatedEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($user->getEmail())
            ->subject($this->translator->trans('forget_password.email.subject', [], 'security'))
            ->htmlTemplate('security/email/forget_password.html.twig')
            ->context([
                'user' => $user,
                'website_name' => $this->parameterBag->get('app.name'),
                'confirmation_url' => $url,
            ]);
        $this->mailer->send($email);
    }

    public function sendResetEmailCheck(User $user, string $newEmail, string $locale)
    {
        $url = $this->router->generate(
            'app_reset_email',
            [
                'token' => $user->getConfirmationToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $email = (new TemplatedEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($newEmail)
            ->subject($this->translator->trans('reset_email.email.subject', [], 'security'))
            ->htmlTemplate('security/email/reset_email.html.twig')
            ->context([
                'user' => $user,
                'new_email' => $newEmail,
                'website_name' => $this->parameterBag->get('app.name'),
                'confirmation_url' => $url,
            ]);
        $this->mailer->send($email);
    }

    public function sendInvitation(User $user, string $password, string $locale)
    {
        $url = $this->router->generate(
            'app_registration_confirm',
            [
                'token' => $user->getConfirmationToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($user->getEmail())
            ->subject(
                $this->translator->trans('invitation.email.subject', [
                    '%user%' => $user,
                    '%website_name%' => $this->parameterBag->get('configuration')['name'],
                ], 'back_messages')
            )
            ->htmlTemplate('back/email/invite.html.twig')
            ->context([
                'user' => $user,
                'password' => $password,
                'website_name' => $this->parameterBag->get('app.name'),
                'footer_text' => $this->parameterBag->get('app.name'),
                'footer_url' => $this->router->generate(
                    'front_home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action('Cliquer ici pour valider votre e-mail', $url);
        $this->mailer->send($email);
    }

    public function enableAccountNotification(User $user, string $password)
    {
        $url = $this->router->generate(
            'app_registration_confirm',
            [
                'token' => $user->getConfirmationToken(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($user->getEmail())
            ->subject('🔔 Notification - Compte validé')
            ->htmlTemplate('back/email/enable.html.twig')
            ->context([
                'user' => $user,
                'user_email' => $user->getEmail(),
                'password' => $password,
                'website_name' => $this->parameterBag->get('app.name'),
                'footer_text' => $this->parameterBag->get('app.name'),
                'footer_url' => $this->router->generate(
                    'front_home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action("Cliquer ici pour valider l'invitation", $url)
            ->importance(NotificationEmail::IMPORTANCE_MEDIUM);
        $this->mailer->send($email);
    }

    public function validateAccountNotification(User $user)
    {
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($user->getEmail())
            ->subject('🔔 Notification - Modification validée')
            ->htmlTemplate('back/email/validate.html.twig')
            ->context([
                'user' => $user,
                'website_name' => $this->parameterBag->get('app.name'),
                'footer_text' => $this->parameterBag->get('app.name'),
                'footer_url' => $this->router->generate(
                    'front_home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->importance(NotificationEmail::IMPORTANCE_MEDIUM);
        $this->mailer->send($email);
    }

    public function agentRegisterNotification(User $user)
    {
        
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($this->parameterBag->get('app.to_email'))
            ->subject("🔔 Notification - Nouvelle inscription d'un agent")
            ->markdown(
                <<<EOF
                $user vient de **s'inscrire** en tant qu'agent.
                Vous devez le valider.
                **Vous devez être connecté pour avoir accès au lien de validation.**
                EOF
            )
            ->context([
                'footer_text' => 'Se connecter',
                'footer_url' => $this->router->generate(
                    'app_login',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action('Voir les informations du candidat', $this->router->generate(
                'back_user_read',
                ['id' => $user->getId(), ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ))
            ->importance(NotificationEmail::IMPORTANCE_HIGH);

        $this->mailer->send($email);
    }
    public function userRegisterNotification(User $user )
    {
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($user->getEmail())
            ->subject("🔔 Notification - Confirmation de votre candidature")
            ->markdown(<<<EOF
Bonjour $user,

Votre demande de renseignements a été pris en compte.  

Nous revenons vers vous au plus vite.  

![POURQUOI REJOINDRE RESEAU FUNERAIRE](https://www.reseaufuneraire.com/build/images/pourquoiRejoindreRF.jpg)

- Pour l’accompagnement et la formation dédiés à nos Agents Funéraires
- Pour les outils et le réseau mis à disposition de ses Agents Funéraires
- Pour la liberté de travail et la gestion de son temps
- Pour l’évolution professionnelle
- Pour sa rémunération attractive

#### La force d’un réseau, la proximité au service des familles 
-  J’ai besoin de plus d’indépendance tout en bénéficiant d’un accompagnement
-  Je souhaite créer et développer mon entreprise
-  Je veux être rémunéré à la hauteur de mon investissement
- Je souhaite un métier plus humain et épanouissant  

#### Devenir Agent Funéraire Indépendant – Réseau Funéraire, c’est faire décoller sa carrière !

![POURQUOI REJOINDRE RESEAU FUNERAIRE](https://www.reseaufuneraire.com/build/images/img_RF_email_2.png)
![POURQUOI REJOINDRE RESEAU FUNERAIRE](https://www.reseaufuneraire.com/build/images/img_RF_email_3.png)
![POURQUOI REJOINDRE RESEAU FUNERAIRE](https://www.reseaufuneraire.com/build/images/img_RF_email_4.jpg)


# Une nouvelle façon d’envisager le funérairer

En devenant **Agent Funéraire Indépendant**, vous travaillez en tant que **travailleur indépendant**.
Vous êtes votre propre patron et gérez votre emploi du temps en toute liberté et flexibilité.
L’activité est donc conciliable avec une **vie de famille épanouie**.  

**Des valeurs fortes :**
Le partage est un pilier fondamental. **Réseau Funéraire** favorisera le partage d’expérience entre chacun.  

- **Professionnalisme**: 		Notre but est d'offrir le plus haut niveau de qualité de service aux familles.
- **Confiance**:			Elle est à la base d’une relation avec les familles.
- **Partage**: 			Nous croyons au partage des compétences et connaissances pour bâtir tous ensemble notre succès.
- **Courage**:		Pour tous ceux qui ont choisi de changer de vie avec Réseau Funéraire et se dépassent chaque jour.
- **Loyauté**: 		Nous agissons tous ensemble dans l'intérêt des Agents Funéraires Indépendants pour le succès du réseau.  

![POURQUOI REJOINDRE RESEAU FUNERAIRE](https://www.reseaufuneraire.com/build/images/img_RF_email_5.jpg)
EOF
            )
            ->context([
                'footer_text' => 'Revenir au site',
                'footer_url' => $this->router->generate(
                    'app_login',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            // ->action('Pour valider le compte', $this->router->generate(
            //     'front_agent_read',
            //     ['id' => $user->getId(), ],
            //     UrlGeneratorInterface::ABSOLUTE_URL
            // ))
            ->importance(NotificationEmail::IMPORTANCE_HIGH);

        $this->mailer->send($email);
    }

    public function agentUpdateNotification(User $user)
    {
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($this->parameterBag->get('app.to_email'))
            ->subject("🔔 Notification - Modification d'un agent")
            ->markdown(
                <<<EOF
                $user vient de **modifier** son profil.
                Vous devez le valider.
                **Vous devez être connecté pour avoir accès au lien de validation.**
                EOF
            )
            ->context([
                'footer_text' => 'Se connecter',
                'footer_url' => $this->router->generate(
                    'app_login',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action('Pour valider le compte', $this->router->generate(
                'front_agent_read',
                ['id' => $user->getId(), ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ))
            ->importance(NotificationEmail::IMPORTANCE_HIGH);

        $this->mailer->send($email);
    }

    public function agentUpdatePhotoNotification(User $user)
    {
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($this->parameterBag->get('app.to_email'))
            ->subject('🔔 Notification - Un agent a ajouté une photo')
            ->markdown(
                <<<EOF
                $user vient d'ajouter une **photo**.
                Vous devez la valider.
                **Vous devez être connecté pour avoir accès au lien de validation.**
                EOF
            )
            ->context([
                'footer_text' => 'Se connecter',
                'footer_url' => $this->router->generate(
                    'app_login',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action('Pour la valider', $this->router->generate(
                'front_agent_read',
                ['id' => $user->getId(), ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ))
            ->importance(NotificationEmail::IMPORTANCE_HIGH);

        $this->mailer->send($email);
    }

    public function newQuotationNotification(QuotationRequest $quotationRequest)
    {
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($this->parameterBag->get('app.to_email'))
            ->subject('🔔 Notification - Nouvelle demande')
            ->markdown(
                <<<EOF
                Une nouvelle demande de devis est en attente sur votre site web.                EOF
                EOF
            )
            ->context([
                'footer_text' => $this->parameterBag->get('app.name'),
                'footer_url' => $this->router->generate(
                    'front_home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action('Pour voir la demande', $this->router->generate(
                'back_quotation_request_read',
                ['id' => $quotationRequest->getId(), ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ))
            ->importance(NotificationEmail::IMPORTANCE_HIGH);

        $this->mailer->send($email);
    }

    public function agentAllotQuotationNotification(QuotationRequest $quotationRequest)
    {
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($quotationRequest->getAgent()->getEmail())
            ->subject('🔔 Notification - Nouvelle demande')
            ->markdown(
                <<<EOF
                Vous avez une nouvelle demande de devis.
                EOF
            )
            ->context([
                'footer_text' => $this->parameterBag->get('app.name'),
                'footer_url' => $this->router->generate(
                    'front_home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action('Pour voir la demande', $this->router->generate(
                'back_quotation_request_read',
                ['id' => $quotationRequest->getId(), ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ))
            ->importance(NotificationEmail::IMPORTANCE_HIGH);

        $this->mailer->send($email);
    }

    public function contactMessageNotification(ContactMessage $contactMessage)
    {
        $email = (new NotificationEmail())
            ->from(new Address(
                $this->parameterBag->get('app.from_email'),
                $this->parameterBag->get('app.name')
            ))
            ->to($this->parameterBag->get('app.to_email'))
            ->subject('🔔Notification - Message')
            ->htmlTemplate('back/email/contact_message.html.twig')
            ->context([
                'contact_message' => $contactMessage,
                'website_name' => $this->parameterBag->get('app.name'),
                'footer_text' => $this->parameterBag->get('app.name'),
                'footer_url' => $this->router->generate(
                    'front_home',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ])
            ->action("Cliquer ici pour l'ouvrir dans l'application", $this->router->generate(
                'back_contact_message_read',
                ['id' => $contactMessage->getId(), ],
                UrlGeneratorInterface::ABSOLUTE_URL
            ))
            ->importance(NotificationEmail::IMPORTANCE_MEDIUM)
            ->replyTo($contactMessage->getEmail());
        $this->mailer->send($email);
    }
}
