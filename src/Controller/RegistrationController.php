<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="registration")
     */
    public function index()
    {

        return $this->render('registration/register.html.twig', [
            ''
        ]);

    }

    /**
     * @Route ("/register", name="registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        // Here, we passing UserTypeForm and new instance of user
        $form = $this->createForm(UserType::class, $user);
        // process the submitted data
        $form->handleRequest($request);
        // check if form is submitted and data is valid
        if ($form->isSubmitted() && $form->isValid()) {

            /*
             * In User entity we added set and get for plain password
             * So, here to encode the password we can pass user instance and password */
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $user->setRoles('ROLE_USER');

            $created_at = new \DateTime("NOW");
            $user->setCreatedAt($created_at);

            $updated_at = new \DateTime("NOW");
            $user->setUpdatedAt($updated_at);

            // saving the user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            /*
             *
             * Redirect to 'somewhere' */
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/login.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
