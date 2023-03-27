<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\ArticleBlog;
use App\Form\ArticleBlogType;
use App\Service\PictureService;
use App\Repository\ArticleBlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/blog/article')]
class ArticleBlogController extends AbstractController
{
    #[Route('/', name: 'app_article_blog_index', methods: ['GET'])]
    public function index(ArticleBlogRepository $articleBlogRepository): Response
    {
        return $this->render('article_blog/index.html.twig', [
            'article_blogs' => $articleBlogRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleBlogRepository $articleBlogRepository,PictureService $pictureService,ManagerRegistry $doctrine): Response
    {
        $articleBlog = new ArticleBlog();
        $form = $this->createForm(ArticleBlogType::class, $articleBlog);
        $form->handleRequest($request);
        $em = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('image')->getData();
            //dd($images);
            foreach ($images as $image) {
                #On définit le dossier de destination
                $folder='article';
                //On appelle le service d'ajout
                $fichier= $pictureService->add($image,$folder,300,300);
                $img=new Image;
                $img->setName($fichier);
                $articleBlog->addImage($img);

            }
            $articleBlog=$form->getData();
            $articleBlog ->setArticle_Auth($this->getUser());
            $em ->persist($articleBlog);
            $em -> flush();
            
            return $this->redirectToRoute('app_article_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_blog/new.html.twig', [
            'article_blog' => $articleBlog,
            'form' =>  $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_article_blog_show', methods: ['GET'])]
    public function show(ArticleBlog $articleBlog): Response
    {
        return $this->render('article_blog/show.html.twig', [
            'article_blog' => $articleBlog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ArticleBlog $articleBlog, ArticleBlogRepository $articleBlogRepository,PictureService $pictureService,ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ArticleBlogType::class, $articleBlog);
        $form->handleRequest($request);
        $em = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('image')->getData();
            //dd($images);
            foreach ($images as $image) {
                #On définit le dossier de destination
                $folder='article';
                //On appelle le service d'ajout
                $fichier= $pictureService->add($image,$folder,300,300);
                $img=new Image;
                $img->setName($fichier);
                $articleBlog->addImage($img);

            }
            $articleBlog=$form->getData();
            $articleBlog ->setArticle_Auth($this->getUser());
            $em ->persist($articleBlog);
            $em -> flush();
            return $this->redirectToRoute('app_article_blog_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_blog/edit.html.twig', [
            'article_blog' => $articleBlog,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_article_blog_delete', methods: ['POST'])]
    public function delete(Request $request, ArticleBlog $articleBlog, ArticleBlogRepository $articleBlogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleBlog->getId(), $request->request->get('_token'))) {
            $articleBlogRepository->remove($articleBlog, true);
        }

        return $this->redirectToRoute('app_article_blog_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/image/{id}', name: 'app_article_delete_image',methods:['DELETE'])]
    public function deleteImage(Request $request, Image $image, ArticleBlogRepository $articleBlogRepository,PictureService $pictureService,EntityManagerInterface $emi): JsonResponse
    {
        // Récupération de la charge utile de la requête
        $payload = $request->getContent();
        var_dump($payload);

        // Décodage du JSON
        $data = json_decode($payload, true);

        // Récupération du token
        $token = $data['_token'];
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $token)) {
          //le token csrf est valide
          //On récupère le nom de l'image
          $nom = $image->getName();
          if ($pictureService->delete($nom ,'article',300,300)) {
            //on supprime l'image de la base de données
            $emi->remove($image);
            $emi->flush();
            return new JsonResponse(['success'=>true],200);
          }
          //La supression a échoué
          return new JsonResponse(['error'=>'Erreur de supression'],400);
        }
        return new JsonResponse(['error'=>'Token Invalide'],400);
    }
}
