<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\ArticleBlog;
use App\Form\ArticleBlogType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleBlogRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    #[Route('/myarticles',name:'app_article_blog_myarticles')]
    #[isGranted('ROLE_USER')]
    public function showmyquestions(ArticleBlogRepository $repository, PaginatorInterface $paginator,Request $request):Response
    {
        
        $article_blogs = $paginator->paginate(
            $repository->findBy(['article_auth' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('article_blog/showMyAricles.html.twig',[
            'article_blogs' => $article_blogs
        ]);

    }
    #[Route('/new', name: 'app_article_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArticleBlogRepository $articleBlogRepository,PictureService $pictureService,ManagerRegistry $doctrine,FlashyNotifier $flashy): Response
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
            $flashy->success('Votre article a bien été posté');
            
            return $this->redirectToRoute('app_article_blog_myarticles', [], Response::HTTP_SEE_OTHER);
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
    #[Security("is_granted('ROLE_USER') and user === articleBlog.getArticle_Auth()")]
    public function edit(Request $request, ArticleBlog $articleBlog, ArticleBlogRepository $articleBlogRepository,PictureService $pictureService,ManagerRegistry $doctrine,FlashyNotifier $flashy): Response
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
            
            $flashy->success('Votre article a bien été modifié');
            
            return $this->redirectToRoute('app_article_blog_myarticles', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_blog/edit.html.twig', [
            'article_blog' => $articleBlog,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}', name: 'app_article_blog_delete', methods: ['POST'])]
    #[isGranted('ROLE_USER')]
    #[Security("is_granted('ROLE_USER') and user === articleBlog.getArticle_Auth()")]
    public function delete(Request $request, ArticleBlog $articleBlog, ArticleBlogRepository $articleBlogRepository,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleBlog->getId(), $request->request->get('_token'))) {
            $articleBlogRepository->remove($articleBlog, true);
        }

        $flashy->success('Votre article a bien été supprimé');
            
        return $this->redirectToRoute('app_article_blog_myarticles', [], Response::HTTP_SEE_OTHER);
    }

    
    #[Route('/image/{id}', name: 'app_article_delete_image',methods:['DELETE'])]
    #[isGranted('ROLE_USER')]
    #[Security("is_granted('ROLE_USER') and user === articleBlog.getArticle_Auth()")]
    public function deleteImage(Request $request, Image $image, ArticleBlogRepository $articleBlogRepository,PictureService $pictureService,EntityManagerInterface $emi,FlashyNotifier $flashy): JsonResponse
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
            $flashy->success('Votre image a bien été supprimée');
            
            return $this->redirectToRoute('app_article_blog_myarticles', [], Response::HTTP_SEE_OTHER);
          }
          //La supression a échoué
          return new JsonResponse(['error'=>'Erreur de supression'],400);
        }
        return new JsonResponse(['error'=>'Token Invalide'],400);
    }
}
