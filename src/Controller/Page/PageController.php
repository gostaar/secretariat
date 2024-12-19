<?php

namespace App\Controller\Page;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PageController extends AbstractController
{
    private CacheInterface $cache;

    // Injection du service de cache
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    #[Route('/', name: 'home_index')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $commonFragments = [
            'acceuil' => 'partials/Page/_intro.html.twig',
            'tarifs' => 'partials/Page/_tarif.html.twig',
            'service' => 'partials/Page/_service.html.twig',
            'contact' => 'partials/Page/_contact.html.twig',
        ];
        
        $sections = [
            'pro' => [
                'template' => 'partials/Page/index.html.twig',
                'subFragments' => $commonFragments, 
            ],
            'part' => [
                'template' => 'partials/Page/index.html.twig',
                'subFragments' => array_merge($commonFragments, [
                    'job' => 'partials/Page/_job.html.twig',
                ]),
            ],
            'partproChoice' => [
                'template' => 'pages/_partproChoice.html.twig',
            ],
        ];
        
        $fragment = $request->query->get('fragment', 'partproChoice');
        $subFragment = $request->query->get('subFragment', 'acceuil');
       
        $subFragmentTemplate = $sections[$fragment]['subFragments'][$subFragment] ?? null;
        if (!$subFragmentTemplate) {$subFragmentTemplate = 'partials/Page/_intro.html.twig';}
        
        $subFragmentContent = $this->renderView($subFragmentTemplate);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'fragmentContent' => $this->renderView($sections[$fragment]['template']),
                'subFragmentContent' => $subFragmentContent,
            ]);
        }

        return $this->render('pages/index.html.twig', [
            'sections' => $sections,
            'currentFragment' => $fragment,
            'currentSubFragment' => $subFragment,
            'currentSubFragmentTemplate' => $subFragmentTemplate,

        ]);
    }
}
