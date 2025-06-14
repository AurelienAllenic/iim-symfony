<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{
    #[Route('/locale/{locale}', name: 'app_locale')]
    public function locale(string $locale, Request $request): Response
    {
        /** @var array<string> $appLocales */
        $appLocales = $this->getParameter('app.locales');
        if (!in_array($locale, $appLocales)) {
            throw new NotFoundHttpException();
        }

        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);

        if ($request->headers->get('referer')) {
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirectToRoute('app_homepage');
    }
}