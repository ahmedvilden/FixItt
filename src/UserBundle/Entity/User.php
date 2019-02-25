<?php
// src/AppBundle/Entity/User.php

namespace UserBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     *
     */
    private $age;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     */
    private $salarie;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $inscriptiondate;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $nom;
    /**
     * @ORM\Column(type="string")
     *
     */
    private $prenom;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $gender;

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    /**
     * @return mixed
     */
    public function getgender()
    {
        return $this->gender;
    }

    /**
     * @return mixed
     */
    public function getadresse()
    {
        return $this->adresse;
    }

    /*
    /**
     * @return mixed
     */
    /*public function getdate_inscri()
    {
        return $this->date_inscri;
    }*/
    /**
     * @return mixed
     */
    public function getage()
    {
        return $this->age;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }
    /**
     * @param mixed $adresse
     */
    public function setadresse($adresse)
    {
        $this->adresse = $adresse;
    }
    /**
     * @param mixed $age
     */
    public function setage($age)
    {
        $this->age = $age;
    }

    ///**
     //* @param mixed $date_inscri
     //*/
    /*public function setdate_inscri($date_inscri)
    {
        $time=new \DateTime('now');
        $this->date_inscri = $time;
    }*/
    /**
     * @param mixed $gender
     */
    public function setgender($gender)
    {
        $this->gender = $gender;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setApropos("");
    }

    /**
     * Set inscriptiondate
     *
     * @param string $inscriptiondate
     *
     * @return User
     */
    public function setInscriptiondate($inscriptiondate)
    {
        $this->inscriptiondate = $inscriptiondate;

        return $this;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string" , length=255,nullable=true)
     */
    public $occupation;

    /**
     * Get inscriptiondate
     *
     * @return string
     */
    public function getInscriptiondate()
    {
        return $this->inscriptiondate;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="apropos", type="string" , length=255,nullable=true)
     */
    private $apropos;
    /**
     * @return string
     */
    public function getApropos()
    {
        return $this->apropos;
    }
    /**
     * @param string $apropos
     */
    public function setApropos($apropos)
    {
        $this->apropos = $apropos;
    }

    /**
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }

    /**
     * @param string $occupation
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;
    }
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $url
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->datePublication = new \DateTime('now');
        }
    }


    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return User
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255,nullable=true)
     */
    private $url;

    /**
     * @Vich\UploadableField(mapping="profil_images", fileNameProperty="url")
     * @var File
     */
    private $imageFile;
    /**
     * Set salarie
     *
     * @param int $salarie
     *
     * @return User
     */
    public function setSalarie($salarie)
    {
        $this->salarie= $salarie;

        return $this;
    }

    /**
     * Get salarie
     *
     * @return string
     */
    public function getSalarie()
    {
        return $this->salarie;
    }
    /**
     * @var int
     *
     * @ORM\Column(name="salaire", type="integer",nullable=true)
     */
    private $salaire;

    /**
     * @var string
     *
     * @ORM\Column(name="JoursTravail", type="string", length=255,nullable=true)
     */
    private $joursTravail;

    /**
     * @var string
     *
     * @ORM\Column(name="HDebut", type="string", length=255,nullable=true)
     */
    private $hDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="HFin", type="string", length=255,nullable=true)
     */
    private $hFin;
    /**
     * Set salaire
     *
     * @param integer $salaire
     *
     * @return User
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire
     *
     * @return int
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set joursTravail
     *
     * @param string $joursTravail
     *
     * @return User
     */
    public function setJoursTravail($joursTravail)
    {
        $this->joursTravail = $joursTravail;

        return $this;
    }

    /**
     * Get joursTravail
     *
     * @return string
     */
    public function getJoursTravail()
    {
        return $this->joursTravail;
    }

    /**
     * Set hDebut
     *
     * @param string $hDebut
     *
     * @return User
     */
    public function setHDebut($hDebut)
    {
        $this->hDebut = $hDebut;

        return $this;
    }

    /**
     * Get hDebut
     *
     * @return string
     */
    public function getHDebut()
    {
        return $this->hDebut;
    }

    /**
     * Set hFin
     *
     * @param string $hFin
     *
     * @return User
     */
    public function setHFin($hFin)
    {
        $this->hFin = $hFin;

        return $this;
    }

    /**
     * Get hFin
     *
     * @return string
     */
    public function getHFin()
    {
        return $this->hFin;
    }
}
