<?php

namespace App\Controller\User;
use App\Entity\Repertoire;
use App\Form\RepertoireType;
use App\Service\RepertoireService;
use App\Service\DossierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RouteDataService;    

class RepertoireController extends AbstractController
{
    private RouteDataService $routeDataService;
    private EntityManagerInterface $em;
    private RepertoireService $repertoireService;
    private DossierService $dossierService;
   
    public function __construct(
        EntityManagerInterface $em, 
        DossierService $dossierService,
        RepertoireService $repertoireService,
        RouteDataService $routeDataService
    ){
        $this->em = $em;
        $this->dossierService = $dossierService;
        $this->repertoireService = $repertoireService;
        $this->routeDataService = $routeDataService;
    }

    private function getUserData(): array
    {
        $user = $this->getUser();
        return [
            'services' => $user->getServices(),
            'repertoires' => $user->getRepertoires(),
        ];
    }

    #[Route('/repertoire/{id}', name: 'repertoire', methods: ['GET', 'POST'])]
    public function getRepertoire($id, Request $request)
    {
        $fragment = $request->query->get('fragment', "link-Acceuil");
        $formData = $this->routeDataService->getFormData($fragment);

        // $dossier = $this->dossierService->getDossier($id);
        // $userData = $this->getUserData();

        // // Créer un objet Repertoire et définir son dossier
        // $repertoire = new Repertoire();
        // $repertoire->setDossier($dossier);  // Associer le Dossier à Repertoire

        // $repertoireForm = $this->createForm(RepertoireType::class, $repertoire);

        // $repertoireForm->handleRequest($request);

        // if ($repertoireForm->isSubmitted() && $repertoireForm->isValid()) {
        //     // Traitement après soumission du formulaire, par exemple, persister les données
        // }

        // // return $this->render('partials/user/Profile/repertoire.html.twig', array_merge($userData, [
        // //     'dossier' => $dossier,
        // //     'addRepertoire' => $repertoireForm->createView(),
        // // ]));
        
        return $this->json([
            'fragmentContent' => $this->renderView('userPage/_fragmentContent.html.twig', $formData),
        ]);
    }


    #[Route('/add_repertoire', name: 'add_repertoire', methods: ['POST'])]
    public function addRepertoire(Request $request): Response
    {
        $user = $this->getUser();

        $repertoire = new Repertoire();
        $repertoireForm = $this->createForm(RepertoireType::class, $repertoire);
        $repertoireForm->handleRequest($request);

        if ($repertoireForm->isSubmitted() && $repertoireForm->isValid()) {
            $this->repertoireService->addRepertoire($repertoire, $user);
    
            $url = $this->generateUrl('user') . '?fragment=Repertoire';
            return new RedirectResponse($url);
        }
        return new Response();
    }

    #[Route('/update_repertoire/{id}', name: 'update_repertoire', methods: ['POST'])]
    public function updateRepertoire(int $id, Request $request) : JsonResponse
    {
        $nom = $request->get('nom');
        $adresse = $request->get('adresse');
        $code_postal = $request->get('code_postal');
        $ville = $request->get('ville');
        $pays = $request->get('pays');
        $telephone = $request->get('telephone');
        $mobile = $request->get('mobile');
        $email = $request->get('email');
        $siret = $request->get('siret');
        $nom_entreprise = $request->get('nom_entreprise');
        $dossier = $request->get('dossier');
        $contact = $request->get('contact');
        
        $user = $this->getUser();
        $this->repertoireService->updateRepertoire($id, $user, $nom, $adresse, $code_postal, $ville, $pays, $telephone, $mobile, $email, $siret, $nom_entreprise, $dossier, $contact);
        return new JsonResponse(['success' => true], 200);
    }

    #[Route('/delete_repertoire/{id}', name: 'delete_repertoire', methods: ['DELETE'])]
    public function deleteRepertoire(int $id) : JsonResponse
    {
        $user = $this->getUser();
        $this->repertoireService->deleteRepertoire($id, $user);
        return new JsonResponse(['success' => true], 200);
    }
}