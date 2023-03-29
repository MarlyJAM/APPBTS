<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Form\ContactType;
use App\Entity\Users;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(Request $request,EntityManagerInterface $manager,MailerInterface $mailer): Response
    {
        
        $contact = new Contact;
        if ($this->getUser()) {
            $contact->setFullname($this->getUser()->getLastname() .$this->getUser()->getFirstname());
            $contact->setEmail($this->getUser()->getEmail());
        }
        $form=$this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact=$form->getData();
            $manager->persist($contact);
            $manager->flush();
    
            $email = (new TemplatedEmail())
            ->from($contact->getEmail())
            ->to('deskeileen@gmail.com')
            ->subject($contact->getSubject())
            // path of the Twig template to render
            ->htmlTemplate('emails/contact.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'contact' =>$contact,
               
            ]);

            $mailer->send($email);

            return $this->redirectToRoute('app_about');
            
        }
        return $this->render('about/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
