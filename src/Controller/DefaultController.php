<?php

    namespace App\Controller;

    use App\Entity\Product;
    use App\Entity\User;
    use App\Form\LoginType;
    use App\Form\RegisterType;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

    class DefaultController extends Controller {
        private $TempDir = 'default/';

        /**
         * @Route("/", name="homepage")
         */
        public function index(){
            $arr = $this->getDoctrine()->getRepository(Product::class)->findAll();
            return $this->render($this->TempDir.'index.html.twig',array('product' => $arr));
        }

        /**
         * @Route("/search", name="search")
         */
        public function search(Request $request){
            $search = $request->query->get('q');
            $arr = $this->getDoctrine()->getRepository(Product::class)->findSearch($search);
            return $this->render($this->TempDir.'index.html.twig',array('product' => $arr));
        }

        /**
         * @Route("/signin", name="signin")
         */
        public function signin(Request $request) {
            $user = new User();

            $form = $this->createForm(LoginType::class,$user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

               //$user = $form->getData();
               //$entityManager = $this->getDoctrine()->getManager();
               //$entityManager->persist($user);
               //$entityManager->flush();
               return $this->redirectToRoute('homepage');
            }

            return $this->render($this->TempDir.'user/login.html.twig', array('form' => $form->createView()));
        }

        /**
         * @Route("/signup", name="signup")
         */
        public function signup(Request $request, UserPasswordEncoderInterface $encoder) {
            $user = new User();

            $form = $this->createForm(RegisterType::class,$user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();

                $user->setRoles(['ROLE_USER']);
                $user->setEnabled(true);
                $user->setPassword(
                    $encoder->encodePassword($user, $user->getPassword())
                );

                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('homepage');
            }

            return $this->render($this->TempDir.'user/signup.html.twig', array('form' => $form->createView()));
        }

        /**
         * @Route("/getJson", name="products_json")
         */
        public function getJson(){
            $arr = $this->getDoctrine()->getRepository(Product::class)->findAll();
            $tmp = array();
            foreach ($arr as $a):
                $tmp[] = array(
                    'id' => $a->getId(),
                    'name' => $a->getName(),
                    'price' => $a->getPrice(),
                    'quantity' => $a->getQuantity(),
                    'description' => $a->getDescription(),
                    'discount' => $a->getDescription(),
                    'category' => $a->getCategory()->getName(),
                    'category_id' => $a->getCategory()->getId(),
                    'brand' => $a->getBrand()->getName(),
                    'brand_id' => $a->getBrand()->getId()
                );
            endforeach;
            return $this->json($tmp);
            //return $this->render($this->TempDir.'index.html.twig',array('product' => $arr));
        }

    }