<?php
// src/Usuario.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class Usuario
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombre;

     /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $apellidos;

     /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $usuario;

/**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $email;

/**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $password;
/**
     * @ORM\Column(type="integer")
     * @var string
     */
    protected $edad;

    /**
    * @ORM\ManyToOne(targetEntity="Clan", inversedBy="miembros")
    * @ORM\JoinColumn(name="idclan", referencedColumnName="idclan")
    */
    protected $clanPerteneciente;

    /**
    * @ORM\OneToMany(targetEntity="Mensaje", mappedBy="usuario0")
    * @var Mensaje[] An ArrayCollection of Mensaje objects.
    */
    protected $mensajes;


    public function __construct()
    {
        $this->miembros = new ArrayCollection();
	$this->mensajes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($name)
    {
        $this->nombre = $name;
    }

    public function setApellidos($name)
    {
        $this->apellidos = $name;
    }
    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setUsuario($name)
    {
        $this->usuario = $name;
    }
    public function getUsuario()
    {
        return $this->usuario;
    }
    public function setEmail($name)
    {
        $this->email = $name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setPassword($name)
    {
        $this->password = $name;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setEdad($name)
    {
        $this->edad = $name;
    }
    public function getEdad()
    {
        return $this->edad;
    }
// relacion con clan

    public function unirAClan(Clan $clan)
    {
		$this->clanPerteneciente = $clan;
        $clan->joinClan($this);
    }

    public function getClan()
    {
        return $this->clanPerteneciente;
    }
    public function salirClan()
    {
    	$this->clanPerteneciente->sacarMiembro($this);
		$this->clanPerteneciente = NULL;
    }
// relacion con Mensaje
    public function addMensaje(Mensaje $mensaje){
	$this->mensajes[] = $mensaje;
    }
    public function getMensajes(){
	return $this->mensajes;
    }

}
