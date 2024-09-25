<?php

namespace App\Controller\Frontend;

use App\Entity\PageGenerate;
use App\Controller\BaseController;
use App\Entity\SubmittedComment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PageController extends BaseController
{

    //affichage par page elements html
    #[Route('/show-pages/{slug}', name: 'page.show')]
    public function addFormPage(Request $request, string $slug) 
    {

        $pageGenerateList = $this->getRepository(PageGenerate::class)->findOneBy(["slug"=> $slug]) ;

        return $this->render('Frontend/Pages/form_pages.html.twig', [

            'slug'              => $slug ,
            'page_list'         => $pageGenerateList , 
            'controller_name'   => 'FrontendIndexController'

        ]);

    }

    //rajout des commentaires pour chaques pages
    #[Route('/add-comment-pages', name: 'page.add.comment', methods: ['POST'])]
    public function addComment(Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $page = $this->getRepository(PageGenerate::class)->findOneBy(['slug' => $data['slug']]) ;

        if ($page) {
            # code...
            $submittedComment = new SubmittedComment() ;
    
            $submittedComment
                ->setCreated(new \DateTime())
                ->setComment($data)
                ->setPageGenerate($page)
                ;

            $this->save($submittedComment) ;
    
            return new JsonResponse(['success' => true]) ; 
        }

        return new JsonResponse((["success" => false ])) ;
       
    }

}
