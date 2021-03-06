<?php

namespace ProfilingBundle\Controller;

use ProfilingBundle\Entity\Album;
use ProfilingBundle\Entity\CommentairePost;
use ProfilingBundle\Entity\DemandeS;
use ProfilingBundle\Entity\Post;
use ProfilingBundle\Form\CommentairePostType;
use ProfilingBundle\Form\DemandeSType;
use ProfilingBundle\Form\PostType;
use ProfilingBundle\Repository\PostRepository;
use SebastianBergmann\CodeCoverage\Node\File;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use UserBundle\Entity\User;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProfileController extends Controller
{
    public function index_profileAction()
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        return $this->render('@Profiling/Profile.html.twig', array(
            'curr_user' => $u
        ));
    }

    public function albumAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $album = new Album();
        //----------------
        $form = $this->createFormBuilder($album)
            ->add('imageFile', VichImageType::class)
            ->add('user', HiddenType::class, array('data' => $u))
            ->add('Ajouter', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted()) && ($form->isValid())) {
            $album = $form->getData();
            $album->setUser($u);
            $taw = new \DateTime('now');
            $album->setDatePublication($taw);
            $em->persist($album);
            $em->flush();
            return $this->redirectToRoute('album');
        }
        //-------------------supprimer photo
        if($request->isMethod('POST')) {
            if ($request->request->has('idp')) {
                $p= $em->getRepository(Album::class)->find($request->get("idp"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("album");
            }
            return $this->redirectToRoute('album');
        }
        //----------------------------------

        $photos = $em->getRepository(Album::class)->findBy(array('user' => $u->getId()), array('datePublication' => 'ASC'));

        return $this->render('@Profiling/ProfileSettings.html.twig', array(
            'curr_user' => $u, 'form' => $form->createView(), 'photos' => $photos
        ));
    }

    public function createAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $id = $u->getId();
        $post = new Post();
        $post->setUser($this->getUser());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $post->setDatePublication(new \DateTime('now '));

            $file = $post->getImage();
            if ($file != "") {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('image_directory', $fileName),
                    $fileName
                );
                $post->setImage($fileName);
                $post = $form->getData();
                $em->persist($post);
                $em->flush();

                return $this->redirectToRoute("showPost");
            } else {
                $em = $this->getDoctrine()->getManager();
                $post->setDatePublication(new \DateTime('now '));
                $post->setImage("");
                $post = $form->getData();
                $em->persist($post);
                $em->flush();
                return $this->redirectToRoute("showPost");
            }
        }
        return $this->render('@Profiling/post.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Post::class,
        ));

    }

    public function readAction(Request $request)
    {

        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $comments=$em->getRepository(CommentairePost::class)->findAll();


        if ($tag = $request->query->get('tag')) {
            $recent = $em->getRepository(Post::class)->findByTag($tag)->getResult();
        } else {
            $recent = $em->getRepository(Post::class)->findBy(array('user' => $u), array('datePublication' => 'DESC'));
            return $this->render("@Profiling/showPost.html.twig", array("recent" => $recent,"comments"=>$comments));
        }

        return $this->render('@Profiling/showPost.html.twig', array(
            "recent" => $recent, "curr_user" => $u,"comments"=>$comments
        ));
    }

    public function updateAction(Request $request, $idp)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($idp);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $file = $post->getImage();

            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('image_directory', $fileName),
                $fileName
            );

            // updates the 'brochure' property to store the PDF file name
            // instead of its contents
            $post->setImage($fileName);
            $post->setDatePublication(new \DateTime('now '));
            $post = $form->getData();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute("showPost");
        }

        return $this->render('@Profiling/postupdate.html.twig', array(
            "form" => $form->createView()

        ));


    }

    public function paramInfoAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        //----------------
        $form = $this->createFormBuilder($u)
            ->add('Save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted()) && ($form->isValid()))

            //----------------
            $user = $em->getRepository(User::class)->find($u->getId());
        if ($request->isMethod('POST')) {
            $user->setNom($request->get('nom'));
            $user->setPrenom($request->get('prenom'));
            $user->setgender($request->get('gender'));
            $user->setadresse($request->get('adresse'));
            $user->setPhoneNumber($request->get('phoneNumber'));
            $user->setApropos($request->get('apropos'));
            $user->setOccupation($request->get('occupation'));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('paramInfo');
        }


        return $this->render('@Profiling/paraminfo.html.twig', array(
            'us' => $u, 'form' => $form->createView()
        ));

    }

    public function updatepicAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $formI = $this->createFormBuilder($u)->add('imageFile', VichImageType::class)->add('Update', SubmitType::class)
            ->getForm();
        $formI->handleRequest($request);
        //--------------------------
        if ($formI->isSubmitted()) {
            $u->setUrl($request->get('image'));
            $em->persist($u);
            $em->flush();
            return $this->redirectToRoute('paramInfo');
        }


        return $this->render('@Profiling/photoProfil.html.twig', array(
            'formI' => $formI->createView()
        ));
    }

    public function autreprofileAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $photos = $em->getRepository(Album::class)->findBy(array('user' => $id), null, 9, null);
        $posts = $em->getRepository(Post::class)->findBy(array('user' => $id), array('datePublication' => 'DESC'));

        return $this->render('@Profiling/autreprofile.html.twig', array(
            'autreuser' => $u[0], 'photos' => $photos, 'posts' => $posts
        ));
    }
    public function autrealbumAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $photos = $em->getRepository(Album::class)->findBy(array('user' => $id), null, 9, null);

        return $this->render('@Profiling/autrealbum.html.twig', array(
            'autreuser' => $u[0], 'photos' => $photos
        ));
    }

    public function demandesalarieAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $demande = new DemandeS();
        $demande->setUser($this->getUser());
        $form = $this->createForm(DemandeSType::class, $demande);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $demande->setDateEnvoi(new \DateTime('now '));
            $demande = $form->getData();
            $em->persist($demande);
            $em->flush();
            return $this->redirectToRoute("profilehomepage");
        }
        return $this->render('@Profiling/demandesalarie.html.twig', array(
            'form'=>$form->createView()));

    }
    public function AfficherDemandeSalarieAction(){
        $em=$this->getDoctrine()->getManager();
        $demandes = $em->getRepository(DemandeS::class)->findAll();
        return $this->render('@Profiling/AfficherDemandes.html.twig', array(
            'demandes' => $demandes
        ));
    }
    public function traiterdemandeAction($id){
        $em=$this->getDoctrine()->getManager();
        $demande = $em->getRepository(DemandeS::class)->find($id);
        return $this->render('@Profiling/TraiterDemande.html.twig', array(
            'demande' => $demande
        ));
    }
    public function AfficherSalarieAction($idu,$idd){
        $em=$this->getDoctrine()->getManager();
        $demande=$em->getRepository(DemandeS::class)->find($idd);
        $accepter=$em->getRepository(User::class)->find($idu);
        $em->remove($demande);
        $un=1;
        $accepter->setSalarie($un);
        $em->persist($accepter);
        $em->flush();
        $this->redirectToRoute('AfficherS');
    }
    public function RefuserDemandeAction($idd){
        $em=$this->getDoctrine()->getManager();
        $demande=$em->getRepository(DemandeS::class)->find($idd);
        $em->remove($demande);
        $em->flush();
        $this->redirectToRoute('AfficherS');
    }
    public function AfficherSaAction(){
        $em=$this->getDoctrine()->getManager();
        $salarie = $em->getRepository(User::class)->findsalarie();
        return $this->render('@Profiling/AfficherSalarie.html.twig', array(
            'salarie' => $salarie
        ));
    }
    public function GererSalarierAction(Request $request,$id){
        $em=$this->getDoctrine()->getManager();
        $salarier=$em->getRepository(User::class)->find($id);
        if($request->isMethod('POST')){
            $salarier->setSalaire($request->get('salaire'));
            $salarier->setJoursTravail($request->get('jours'));
            $salarier->setHDebut($request->get('nbhd'));
            $salarier->setHFin($request->get('nbhf'));
            $em->persist($salarier);
            $em->flush();
            return $this->redirectToRoute('AfficherS');
        }
        return $this->render('@Profiling/GererSalarier.html.twig', array(
            'salarier' => $salarier
        ));
    }




    public function PosterCommentaireAction(Request $request,$idp,$idu){
        $em=$this->getDoctrine()->getManager();
        $commentaire= new CommentairePost();
        $user=$em->getRepository(User::class)->find($idu);
        $post=$em->getRepository(Post::class)->find($idp);
        if($request->isMethod('POST')){
        $taw=new \DateTime('now');
            $commentaire->setDateCommentaire($taw);
        $commentaire->setUser($user);
        $commentaire->setPost($post);
        $commentaire->setContenu($request->get('contenu'));
        $em->persist($commentaire);
        $em->flush();
        }
        return $this->redirectToRoute('showPost');
    }
}