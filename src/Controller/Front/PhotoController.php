<?php

namespace App\Controller\Front;

use App\Entity\File;
use App\Entity\User;
use App\Form\Front\PhotoType;
use App\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Form\CropperType;

class PhotoController extends AbstractController
{
    /**
     *
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/photo/modifier/{id}", name="front_photo_update")
     */
    public function update(Request $request, User $user): Response
    {
        if (!$this->getUser() || (!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $this->getUser()->getId())) {
            throw $this->createAccessDeniedException();
        }
        $user->getPhoto() ?: $user->setPhoto(new File());
        $form = $this->createForm(PhotoType::class, $user->getPhoto());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setCroppedPhoto(null)
                ->setCroppedPhotoThumbnail(null);
            $user
                ->getPhoto()
                ->setUpdatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $user->getPhoto()->setFile();
            $msg = $this->translator->trans('photo.update.flash.success', [], 'front_messages');
            $this->addFlash('success', $msg);
            return $this->redirectToRoute('front_photo_crop', ['id' => $user->getId()]);
        }

        return $this->render('front/photo/update.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/photo/ajuster/{id}", name="front_photo_crop")
     */
    public function crop(CropperInterface $cropper, Request $request, Mailer $mailer, User $user): Response
    {
        if (!$this->getUser() || (!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $this->getUser()->getId())) {
            throw $this->createAccessDeniedException();
        }
        if (!$user->getPhoto() || !file_exists($user->getPhoto()->getPhpPath())) {
            return $this->redirectToRoute('front_photo_update', ['id' => $user->getId(), ]);
        }

        $crop = $cropper->createCrop($user->getPhoto()->getPhpPath());

        $crop->setCroppedMaxSize(300, 270);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => $user->getPhoto()->getWebPath(),
                'view_mode' => 1,
                'drag_mode' => 'move',
                'initial_aspect_ratio' => 2000 / 1800,
                'aspect_ratio' => 2000 / 1800,
                'responsive' => true,
                'restore' => true,
                'check_cross_origin' => true,
                'check_orientation' => true,
                'modal' => true,
                'guides' => true,
                'center' => true,
                'highlight' => true,
                'background' => true,
                'auto_crop' => true,
                'auto_crop_area' => 0.1,
                'movable' => true,
                'rotatable' => true,
                'scalable' => true,
                'zoomable' => true,
                'zoom_on_touch' => true,
                'zoom_on_wheel' => true,
                'wheel_zoom_ratio' => 0.2,
                'crop_box_movable' => true,
                'crop_box_resizable' => true,
                'toggle_drag_mode_on_dblclick' => true,
                'min_container_width' => 200,
                'min_container_height' => 100,
                'min_canvas_width' => 0,
                'min_canvas_height' => 0,
                'min_crop_box_width' => 320,
                'min_crop_box_height' => 0,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCroppedPhoto($crop->getCroppedImage());
            $user->setCroppedPhotoThumbnail($crop->getCroppedThumbnail(150, 135));
            if ($user === $this->getUser()) {
                $user->setValidated(false);
            }
            $this->getDoctrine()->getManager()->flush();
            if (!$user->isValidated()) {
                $mailer->agentUpdatePhotoNotification($user);
            }
            return $this->redirectToRoute('front_agent_read', ['id' => $user->getId(), ]);
        }

        return $this->render('front/photo/crop.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
