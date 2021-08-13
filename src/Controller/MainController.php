<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Usuario;
use App\Entity\Clan;
use App\Entity\Mensaje;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;

class MainController extends AbstractController
{

    /**
    * @Route("/landing", name="landing")
    */
    public function landing(RequestStack $requestStack): Response
    {
	$session = $requestStack->getSession();
	$session->clear();

        return $this->render('landing.html.twig');
    }

	/**
    * @Route("/clanChat", name="clanChat")
    */
    public function clanChat(RequestStack $requestStack): Response
    {
    	$session = $requestStack->getSession();
    	$em = $this->getDoctrine()->getManager();

	$session = $requestStack->getSession();

		if(($session->get('id'))){

			$user=$em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));
		    	$clan=$user->getClan();

			return $this->render('clanChat.html.twig', [
			    'clan' => $clan,
			]);
		}
	header("Location: landing");
	exit;
    }

	/**
    * @Route("/ClanCrear", name="ClanCrear")
    */
    public function ClanCrear(): Response
    {
        return $this->render('ClanCrear.html.twig');
    }

    /**
    * @Route("/ClanUnir", name="ClanUnir")
    */
    public function ClanUnir(RequestStack $requestStack): Response
    {
    	$session = $requestStack->getSession();
    	$em = $this->getDoctrine()->getManager();

    	$clanes = $em->getRepository('App:Clan')->findAll();
		$user=$em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));

        return $this->render('ClanUnir.html.twig',[
        	'NombreUsuario' => $user->getUsuario(),
        	'clanes' => $clanes,
        ]);
    }

	/**
     * @Route("/creadorClanes", name="creadorClanes", methods={"POST"})
     */
	public function creadorClanes(Request $request,RequestStack $requestStack): Response
    {
    	$session = $requestStack->getSession();
    	$em = $this->getDoctrine()->getManager();

		$user=$em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));

		$clan = new Clan();

		$clan->setNombre($_POST['nombre']);
		$clan->setUserAdmin($session->get('id'));
		$clan->setSiglas($_POST['alias']);
		$clan->setPublico(true);
		$clan->setLogo("nada");

		$user->unirAClan($clan);

		$em->persist($user);
		$em->persist($clan);

		$em->flush();

		$session->set('clanId',$clan->getIdClan());

		header("Location: perfil");
		exit;
    }

	/**
     * @Route("/enviarMensaje", name="enviarMensaje", methods={"POST"})
     */
	public function enviarMensaje(Request $request,RequestStack $requestStack): Response
    {
    	$session = $requestStack->getSession();
    	$em = $this->getDoctrine()->getManager();

		$user=$em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));

		$mensaje = new Mensaje();

		$mensaje->setTexto($_POST['mensaje']);
		$mensaje->setUsuario($user);
		$mensaje->setClan($user->getClan());

		$em->persist($mensaje);
		$em->flush();

		$session->set('clanId',$user->getClan()->getIdClan());

		header("Location: clanChat");
		exit;
    }


     /**
     * @Route("/editarPerfil", name="editarPerfil", methods={"POST"})
     */
	public function editarPerfil(Request $request,RequestStack $requestStack): Response
    {
    	$session = $requestStack->getSession();
    	$em = $this->getDoctrine()->getManager();

	$usuario = $em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));

	$usuario->setNombre($_POST['nombre']);

	$em->persist($usuario);
	$em->flush();

        header("Location: perfil");
        exit;
    }


	/**
     * @Route("/salirClan", name="salirClan", methods={"GET"})
     */
	public function salirClan(Request $request,RequestStack $requestStack): Response
    {
    	$session = $requestStack->getSession();
    	$em = $this->getDoctrine()->getManager();

		$user=$em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));

		$user->salirClan();

		$em->persist($user);

		$em->flush();

		$session->set('clanId',NULL);

		header("Location: perfil");
		exit;
    }

	/**
     * @Route("/unidorClanes", name="unidorClanes", methods={"GET"})
     */
	public function unidorClanes(Request $request,RequestStack $requestStack): Response
    {
    	$session = $requestStack->getSession();
    	$em = $this->getDoctrine()->getManager();

    	$clan = $em->getRepository('App:Clan')->findOneBy(array('idclan' => $_GET['id']));
		$user=$em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));

		$user->unirAClan($clan);

		$em->persist($user);

		$em->flush();

		$session->set('clanId',$clan->getIdClan());

		header("Location: perfil");
		exit;
    }

    /**
    * @Route("/perfil", name="perfil")
    */
    public function perfil(RequestStack $requestStack): Response
    {
		$session = $requestStack->getSession();

		if(($session->get('id'))){

			$em = $this->getDoctrine()->getManager();
			$userR=$em->getRepository('App:Usuario')->findOneBy(array('id' => $session->get('id')));
			$clan=$userR->getClan();

			$clanId = NULL;
			$clanName = NULL;

			if ($clan){
				$clanId = $clan->getIdClan();
				$clanName = $clan->getNombre();
			}

			return $this->render('perfil.html.twig', [
			    'NombreUsuario' => $userR->getUsuario(),
					'clanName' => $clanName,
					'clanId' => $clanId,
			]);
		}
	header("Location: landing");
	exit;
    }

    /**
    * @Route("/Registro", name="Registro")
    */
    public function Registro(): Response
    {
        return $this->render('Registro.html.twig');
    }

	/**
     * @Route("/registrarUsuario", name="registrarUsuario", methods={"POST"})
     */
    // Un ejemplo de como insertar datos con doctrine
	public function registrarUsuario(Request $request,RequestStack $requestStack): Response
    {
		$em = $this->getDoctrine()->getManager();

		$repo = $em->getRepository('App:Usuario');
    	$users = $repo->findAll();

		//Recogida de datos
        $nombre=$_POST['nombre'];
        $apellido=$_POST['apellido'];
        $email=$_POST['email'];
        $usuarioNombre=$_POST['usuarioNombre'];
        $password=$_POST['password'];
        $password1=$_POST['password1'];
        $edad=$_POST['edad'];
        $elo=$_POST['elo'];

        if($nombre==''){
            header("Location: landing?error=nombre");
            exit;
        }
        if($apellido==''){
            header("Location: landing?error=apellidos");
            exit;
        }
        if($email==''){
            header("Location: landing?error=email");
            exit;
        }
        if($usuarioNombre==''){
            header("Location: landing?error=usuario");
            exit;
        }
        if($password==''){
            header("Location: landing?error=password");
            exit;
        }
        if($password!=$password1){
            header("Location: landing?error=passwordR");
            exit;
        }
        if($edad!='' && !is_numeric($edad)){
            header("Location: landing?error=edad");
            exit;
        }
        if($edad!='' && $edad<10){
            header("Location: landing?error=edad10'");
            exit;
        }
        if($elo!='' && !is_numeric($elo)){
            header("Location: ".FRONT_SCRIPT."?error=elo");
            exit;
        }

        //Guardar datos en bd
        $userR=$em->getRepository('App:Usuario')->findOneBy(array('email' => $email));
        //echo($userR->getEmail());
        if($userR==NULL){

			$userN = new Usuario();

			$userN->setNombre($nombre);
			$userN->setApellidos($apellido);
			$userN->setUsuario($usuarioNombre);
			$userN->setEmail($email);
			$userN->setPassword($password);
			$userN->setEdad($edad);

			$em->persist($userN);
			$em->flush();

        }else{
            header("Location: landing?error=emailR");
            exit;
        }

        $session = $requestStack->getSession();

        $session->set('id', $userN->getId());
        $session->set('usuarioNombre', $userN->getUsuario());
        $session->set('nombre', $userN->getNombre());
        $session->set('apellido', $userN->getApellidos());
        $session->set('edad', $userN->getEdad());
        $session->set('email', $userN->getEmail());
        $session->set('password', $userN->getPassword());

        header("Location: perfil");
        exit;
	}

    /**
     * @Route("/iniciarSesion", name="iniciarSesion", methods={"POST"})
     */
	public function iniciarSesion(Request $request): Response
    	{
	$em = $this->getDoctrine()->getManager();

	$repo = $em->getRepository('App:Usuario');
    	$users = $repo->findAll();

		$email=$_POST['email'];
        $password=$_POST['password'];
        $encontrado=false;
        foreach($users as $user) {
            if($email==$user->getEmail()) {
                if($password==$user->getPassword()) {
                    $encontrado=true;
                    break;
                }
            }
        }
        if($email==''){
            header("Location: landing?error=emailI");
            exit;
        }
        if($password==''){
            header("Location: landing?error=passwordI");
            exit;
        }

        if(!$encontrado){
            header("Location: landing?error=errorperfil");
            exit;
        }

        $user = $em->getRepository('App:Usuario')->findOneBy(array('email' => $email));
        if($user!=NULL){
            if(($user->getPassword()==$password) ) {
				$session = $request->getSession();

				$session->set('id', $user->getId());
				$session->set('usuarioNombre', $user->getUsuario());
				$session->set('nombre', $user->getNombre());
				$session->set('apellido', $user->getApellidos());
				$session->set('edad', $user->getEdad());
				$session->set('email', $user->getEmail());
				$session->set('password', $user->getPassword());

                $elclan = $user->getClan();
		if($elclan){
                	$session->set('clan',$elclan->getIdclan());
		}
            }else{
                header("Location: landing");
                exit;
            }
        }
        header("Location: perfil");
        exit;

        if($email!="admin@admin.com"){
            session_start();
            $_SESSION['email'] = base64_encode("tkssessId-"."email");
            header("Location: perfil");
            exit;
        }

        header("Location: landing");
        exit;
    }
}
