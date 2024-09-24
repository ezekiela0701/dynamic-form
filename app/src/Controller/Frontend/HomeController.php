<?php

namespace App\Controller\Frontend;

use App\Entity\PageGenerate;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends BaseController
{

    #[Route('/', name: 'home.index')]
    public function index(): Response
    {

        $pageGenerateLists = $this->getRepository(PageGenerate::class)->findBy([]) ;

        return $this->render('Frontend/Home/index.html.twig', [

            'page_lists'        => $pageGenerateLists , 
            'controller_name'   => 'FrontendIndexController'

        ]);

    }

    #[Route('/add-form', name: 'home.add.form', methods: ['POST'])]
    public function add(Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $title  = $data['title'] ?? null; 
        $fields = $data['formHtml'] ?? null; 
        $slug   = $title !=null ? $this->slugify($title): null ; 
        
        //verification si la page n'existe pas encore
        $verifSlug = $this->getRepository(PageGenerate::class)->findBy(['slug' => $slug]) ; 

        if (!$verifSlug) {
            # code...
            $pageGenerate = new PageGenerate() ; 
    
            $pageGenerate
                ->setTitle($title)
                ->setSlug($this->slugify($title))
                ->setFields($fields)
                ;
    
            $this->save($pageGenerate) ;
    
            return new JsonResponse(['success' => true]) ; 
        }

        return new JsonResponse((['success' => false])) ; 

    }

    #[Route('/show-pages/{slug}', name: 'home.show.page')]
    public function addFormPage(Request $request, string $slug) 
    {

        $pageGenerateList = $this->getRepository(PageGenerate::class)->findOneBy(["slug"=> $slug]) ;
      
        return $this->render('Frontend/Home/form_pages.html.twig', [

            'page_list'         => $pageGenerateList , 
            'controller_name'   => 'FrontendIndexController'

        ]);

    }

}
