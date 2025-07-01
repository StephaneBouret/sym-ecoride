<?php

namespace App\Controller\TripWizard;

use App\Form\Trip\TripSeatsFormType;
use App\Service\TripCreationStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/trip/create/seats', name: 'app_trip_wizard_seats')]
#[IsGranted('ROLE_USER')]
final class SeatsController extends AbstractController
{
    use WizardRedirectTrait;

    public function __invoke(Request $request, TripCreationStorage $storage, UrlGeneratorInterface $urlGenerator): Response
    {
        $data = $storage->getData();
        $form = $this->createForm(TripSeatsFormType::class, [
            'seatsAvailable' => $data['seatsAvailable'] ?? 1,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seatsData = $form->getData();
            $storage->saveStepData(['seatsAvailable' => $seatsData['seatsAvailable']]);

            return $this->redirectAfterStep('app_trip_wizard_price', $request, $urlGenerator);
        }

        return $this->render('trip_wizard/seats.html.twig', [
            'form' => $form,
        ]);
    }
}