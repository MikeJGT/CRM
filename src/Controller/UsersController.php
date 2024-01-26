<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UpdateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersController extends AbstractController
{
    #[Route('/settings', name: 'settings')]
    public function changePassword(
        UserInterface $userInterface, UserRepository $userRepository,
        Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createFormBuilder()
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            // 'required' => true,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ])
        ->add('submit',SubmitType::class)
        ->getForm();

        $email=$userInterface->getUserIdentifier();
        $user = $userRepository->findOneByEmail($email);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

        $data=$form->getData();
        $password=$data['password'];
        $paswordHased = $passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($paswordHased);
        // dump($data['password'],$email);
        // dd($user);

        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('incident');
        }

        return $this->render('users/settings.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin', name: 'users')]
    public function index(UserRepository $userRepository): Response
    {
        $userList = $userRepository->getAllUsers();

        return $this->render('users/index.html.twig', [
            'user_list' => $userList,
        ]);
    }

    #[Route('/admin/register', name: 'register')]
    public function create(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $paswordHased = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($paswordHased);
            $em->persist($user);
            $em->flush($user);
            $this->addFlash('created','The user was created.');
            return $this->redirectToRoute('register');
        }

        return $this->render('users/register.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'delete_user')]
    public function deleteUser(EntityManagerInterface $em, User $user): Response
    {
        $em->remove($user);
        $em->flush();

        $this->addFlash('success','The user was delete.');
        return $this->redirectToRoute('users');
    }

    #[Route('/admin/update/{id}', name: 'update_user')]
    public function updateUser(EntityManagerInterface $em, User $user, Request $request): Response
    {
        // dd($user);
        $form = $this->createForm(UpdateUserType::class, $user);

        $form->handleRequest($request);

        $formData = $form->getData();
        $token=$request->get('token');
        // dd($token);

        if($form->isSubmitted()){
            $em->persist($user);
            $em->flush($user);
            
            $this->addFlash('success','The user was updated.');
            return $this->redirectToRoute('users');
        }

        return $this->render('/users/update.html.twig', [
            'form' => $form
        ]);
    }
}
