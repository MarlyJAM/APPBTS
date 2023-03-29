<?php

namespace App\Controller;
use App\Entity\Users;
use App\Form\UpdateUsersType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(Request $request): Response
    {   $session = $request->getSession();
        if($session->has( name :'nbrvisite')){
            $nbrvisite= $session->get(name :'nbrvisite')+1;
        }else{
            $nbrvisite=1;
        }
        $session->set('nbrvisite', $nbrvisite);
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
    #[Security("is_granted('ROLE_USER') and user === u")]
    #[Route('/{id}/edit_users', name: 'app_editusers',methods:['GET','POST'])]
    public function UpdateUsers(Users $u ,Request $request , ManagerRegistry $doctrine,EntityManagerInterface $manager ,UserPasswordHasherInterface $hasher,FlashyNotifier $flashy ) : Response{

        $form = $this->createForm(UpdateUsersType::class, $u);

        $form->handleRequest($request);
        $emi = $doctrine->getManager();
       
        if($form -> isSubmitted()  && $form->isValid()){
            if ($hasher->isPasswordValid($u,$form->getData()->getPlainPassword())) {
                $emi ->persist($u);
                $emi -> flush();
                $flashy->success('Vos informations ont bien été modifiées');
                return $this-> redirectToRoute('app_users');

            }else {
                $flashy->error("Vos informations n'ont pas pu etre modifiées");
                return $this-> redirectToRoute('app_users');
            }
           
        }
        return $this->render('users/updateuser.html.twig', [
            'form' => $form->createView(),
        ]);
    
    }
    #[Route('/{id}/editpassword', name: 'app_edipassword',methods:['GET','POST'])]
    #[Security("is_granted('ROLE_USER') and user === us")]
    public function editPassword(Users $us ,Request $request , ManagerRegistry $doctrine,EntityManagerInterface $manager ,UserPasswordHasherInterface $hasher,FlashyNotifier $flashy) : Response
    {
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
          $emi = $doctrine->getManager();
      
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid( $us,$form->getData()['plainPassword'])) {
                
                $us->setPassword(
                    $hasher->hashPassword(
                        $us ,
                        $form->getData()['newPassword']
                    )
                )
                ;
                $emi->persist($us);
                $emi->flush();
                $flashy->success('Vos informations ont bien été modifiées');
                return $this-> redirectToRoute('app_users');
            } else {
                $flashy->error("Vos informations n'ont pas pu etre modifiées");
                return $this-> redirectToRoute('app_users');
            }
        }

        return $this->render('users/editPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
