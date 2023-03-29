<?php

namespace App\Controller;

use DateTime;
use App\Entity\Answer;
use App\Form\AnswerType;
use App\Form\SearchType;
use App\Entity\Questions;
use App\Model\SearchData;
use App\Form\EditQuestionsType;
use Doctrine\ORM\Mapping\OrderBy;
use App\Repository\AnswerRepository;
use App\Repository\QuestionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;


class QuestionsController extends AbstractController
{
    /**
     * Creation d'une nouvelle question
     *
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/questions', name: 'app_questions',methods:['GET','POST'])]
    #[isGranted('ROLE_USER')]
    public function index(Request $request , ManagerRegistry $doctrine,FlashyNotifier $flashy): Response
    {
        $question = new Questions();

        $form = $this->createForm(EditQuestionsType::class, $question);

        $form->handleRequest($request);
        $em = $doctrine->getManager();

        if($form -> isSubmitted()  && $form->isValid()){
            $question=$form->getData();
            $question ->setAuthor($this->getUser());
            $em ->persist($question);
            $em -> flush();
            $flashy->success('Votre question est en vérification');

            return $this-> redirectToRoute('app_myquestions');
        }
        return $this->render('questions/editquestion.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * Affichage de toutes les questions
     *
     * @param QuestionsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/allquestions',name:'app_allquestions')]
    public function showall(QuestionsRepository $repository, PaginatorInterface $paginator,Request $request):Response
    {
        
         $searchData = new SearchData();
         $form= $this->createForm(SearchType::class , $searchData);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page=$request->query->getInt('page',1);
            $questions=$repository->findBySearch($searchData);
            return $this->render('questions/index.html.twig',[
                'form'=>$form->createView(),
                'questions' => $questions
            ]);
         }

        $questions = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('questions/index.html.twig',[
            'form'=>$form->createView(),
            'questions' => $questions
        ]);

    }
    /**
     * Affichages des questions pour un utilisateur
     *
     * @param QuestionsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/myquestions',name:'app_myquestions')]
    #[isGranted('ROLE_USER')]
    public function showmyquestions(QuestionsRepository $repository, PaginatorInterface $paginator,Request $request):Response
    {
        
        $questions = $paginator->paginate(
            $repository->findBy(['author' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('questions/showMyQuestion.html.twig',[
            'questions' => $questions
        ]);

    }

    
    #[Route('/{id}/content/', name: 'app_content')]
    public function showcontent(Request $request, Questions $question,QuestionsRepository $questionRepository,ManagerRegistry $doctrine,FlashyNotifier $flashy): Response
     {
        $answer = new Answer();

        $answer_form = $this->createForm(AnswerType::class, $answer);
        $em = $doctrine->getManager();

        $answer_form->handleRequest($request);
        if( $answer_form-> isSubmitted()  &&  $answer_form->isValid()){
            $answer= $answer_form->getData();
            $answer->setAuth($this->getUser());
            #$answer->setCreatedAt(new DateTime());
            $answer->setQuestion($question);
            $em ->persist($answer);
            $em -> flush();
            $flashy->success('Votre réponse est en vérification');

            return $this-> redirectToRoute('app_myanswers');

        }


         return $this->render('questions/contentquestion.html.twig',[
            'question' =>$question,
            'answer_form' => $answer_form->createView()
        ]);

    }
    
    /**
     * Mise à jour d'une question par l'auteur
     */
    #[Route('/{id}/updatequestion',name:'app_updatequestion',methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === q.getAuthor()")]
    public function updatequeqtions(Questions $q,Request $request,ManagerRegistry $doctrine,FlashyNotifier $flashy) : Response
    {
        $form = $this->createForm(EditQuestionsType::class, $q);

        $form->handleRequest($request);
        $em = $doctrine->getManager();

        if($form -> isSubmitted()  && $form->isValid()){
            $q=$form->getData();
            $q ->setAuthor($this->getUser());
            $em ->persist($q);
            $em -> flush();
            $flashy->success('Votre question a bien été modifiée');

            return $this-> redirectToRoute('app_myquestions');
        }
        return $this->render('questions/updatequestion.html.twig',[
            'form' => $form->createView(),
        ]);

    }
    
/**
 * Supprimer une question
 */
    #[Route('/{id}/deletequestion',name:'app_deletequestion',methods:['GET'])]
    #[isGranted('ROLE_USER')]
    #[Security("is_granted('ROLE_USER') and user === question.getAuthor()")]
    public function delete(EntityManagerInterface $manager,Questions $question,FlashyNotifier $flashy) : Response
    {
            $manager->remove($question);
            $manager ->flush();
            $flashy->success('Votre question a bien été supprimée');

            return $this-> redirectToRoute('app_myquestions');
    }
   
}
