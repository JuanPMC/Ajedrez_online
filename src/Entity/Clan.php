<?php
// src/Usuario.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="clanes")
 */
class Clan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $idclan;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $nombre;

     /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $logo;

     /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $siglas;

/**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $publico;

/**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $userAdmin;

     /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="clanPerteneciente")
     * @var Usuario[] An ArrayCollection of Usuario objects.
     */
    protected $miembros;

     /**
     * @ORM\OneToMany(targetEntity="Mensaje", mappedBy="clan0")
     * @var Mensaje[] An ArrayCollection of Mensaje objects.
     */
    protected $mensajes;

    public function __construct()
    {
        $this->miembros = new ArrayCollection();
	$this->mensajes = new ArrayCollection();
    }


    public function getIdclan()
    {
        return $this->idclan;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($name)
    {
        $this->nombre = $name;
    }

    public function setLogo($name)
    {
        $this->logo = $name;
    }
    public function getLogo()
    {
        return $this->logo;
    }

    public function setSiglas($name)
    {
        $this->siglas = $name;
    }
    public function getSiglas()
    {
        return $this->siglas;
    }
    public function setPublico($name)
    {
        $this->publico = $name;
    }
    public function getPublico()
    {
        return $this->publico;
    }
    public function setUserAdmin($name)
    {
        $this->userAdmin = $name;
    }
    public function getUserAdmin()
    {
        return $this->userAdmin;
    }

// Relacion de miembros
    
    public function joinClan(Usuario $miembro)
    {
        $this->miembros[] = $miembro;
    }

    public function getMiembros()
    {
        return $this->miembros;
    }
    public function sacarMiembro(Usuario $element){
	$this->miembros->removeElement($element);
    }

// Relacion de mensajes

    public function registrarMensaje(Mensaje $mensaje)
    {
        $this->mensajes[] = $mensaje;
    }

    public function getMensajes()
    {
        return $this->mensajes;
    }

}
