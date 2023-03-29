<?php

namespace App\Controller;

use App\Entity\Questions;
use App\Form\EditQuestionsType;
use App\Repository\QuestionsRepository;
use App\Entity\Answer;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AnswerController extends AbstractController
{
    #[Route('/answer', name: 'app_answer')]
    #public function index(): Response
    #{
        
    #}


     /**
     * Affichages des réponses pour un utilisateur
     *
     * @param QuestionsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/myanswers',name:'app_myanswers')]
    #[isGranted('ROLE_USER')]
    public function showmyanswer(AnswerRepository $repository, PaginatorInterface $paginator,Request $request):Response
    {
        
        $answers = $paginator->paginate(
            $repository->findBy(['auth' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('answer/index.html.twig',[
            'answers' => $answers
        ]);

    }


    /**
     * Mise à jour d'une réponse par l'auteur
     */
    #[Route('/{id}/updateanswer',name:'app_updateanswer',methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === a.getAuth()")]
    public function updateanswer(Answer $a,Request $request,ManagerRegistry $doctrine,FlashyNotifier $flashy) : Response
    {   
        $question = $a->getQuestion();
        $form = $this->createForm(AnswerType::class, $a);

        $form->handleRequest($request);
        $em = $doctrine->getManager();

        if($form -> isSubmitted()  && $form->isValid()){
            $a=$form->getData();
            $a->setUpdatedAt(new DateTime());
            $a ->setAuth($this->getUser());
            $a->setQuestion($question);
            $em ->persist($a);
            $em -> flush();
            $flashy->success('Votre réponse a bien été modifiée');

            return $this-> redirectToRoute('app_myanswers');
            
        }
        return $this->render('answer/update_answer.html.twig',[
            'form' => $form->createView(),
        ]);

    }

        
    /**
     * Supprimer une réponse
     */
    #[Route('/{id}/deleteanswer',name:'app_deleteanswer',methods:['GET'])]
    #[isGranted('ROLE_USER')]
    #[Security("is_granted('ROLE_USER') and user === a.getAuth()")]
    public function deleteanswer(EntityManagerInterface $manager,Answer $a,FlashyNotifier $flashy) : Response
    {
            $manager->remove($a);
            $manager ->flush();
            $flashy->success('Votre réponse a bien été supprimée');

            return $this-> redirectToRoute('app_myanswers');
    }

}
