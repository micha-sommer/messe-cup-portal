<?php /** @noinspection PhpUnused */

namespace App\Entity;

use App\Repository\RegistrationRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: RegistrationRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Registration implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $club = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?DateTimeImmutable $timestamp = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\OneToMany(
        mappedBy: 'registration',
        targetEntity: EgaFemaleContestant::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true,
    )]
    private Collection $egaFemaleContestants;

    #[ORM\OneToMany(
        mappedBy: 'registration',
        targetEntity: EgaMaleContestant::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true,
    )]
    private Collection $egaMaleContestants;

    #[ORM\OneToMany
    (mappedBy: 'registration',
        targetEntity: MesseFemaleContestant::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true,
    )]
    private Collection $messeFemaleContestants;

    #[ORM\OneToMany(
        mappedBy: 'registration',
        targetEntity: MesseMaleContestant::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true,
    )]
    private Collection $messeMaleContestants;

    public function __construct()
    {
        $this->egaFemaleContestants = new ArrayCollection();
        $this->egaMaleContestants = new ArrayCollection();
        $this->messeFemaleContestants = new ArrayCollection();
        $this->messeMaleContestants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getClub(): ?string
    {
        return $this->club;
    }

    public function setClub(string $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getTimestamp(): ?DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function setTimestamp(DateTimeImmutable $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, EgaFemaleContestant>
     */
    public function getEgaFemaleContestants(): Collection
    {
        return $this->egaFemaleContestants;
    }

    public function addEgaFemaleContestant(EgaFemaleContestant $egaFemaleContestant): self
    {
        if (!$this->egaFemaleContestants->contains($egaFemaleContestant)) {
            $this->egaFemaleContestants->add($egaFemaleContestant);
            $egaFemaleContestant->setRegistration($this);
        }

        return $this;
    }

    public function removeEgaFemaleContestant(EgaFemaleContestant $egaFemaleContestant): self
    {
        if ($this->egaFemaleContestants->removeElement($egaFemaleContestant)) {
            // set the owning side to null (unless already changed)
            if ($egaFemaleContestant->getRegistration() === $this) {
                $egaFemaleContestant->setRegistration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EgaMaleContestant>
     */
    public function getEgaMaleContestants(): Collection
    {
        return $this->egaMaleContestants;
    }

    public function addEgaMaleContestant(EgaMaleContestant $egaMaleContestant): self
    {
        if (!$this->egaMaleContestants->contains($egaMaleContestant)) {
            $this->egaMaleContestants->add($egaMaleContestant);
            $egaMaleContestant->setRegistration($this);
        }

        return $this;
    }

    public function removeEgaMaleContestant(EgaMaleContestant $egaMaleContestant): self
    {
        if ($this->egaMaleContestants->removeElement($egaMaleContestant)) {
            // set the owning side to null (unless already changed)
            if ($egaMaleContestant->getRegistration() === $this) {
                $egaMaleContestant->setRegistration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MesseFemaleContestant>
     */
    public function getMesseFemaleContestants(): Collection
    {
        return $this->messeFemaleContestants;
    }

    public function addMesseFemaleContestant(MesseFemaleContestant $messeFemaleContestant): self
    {
        if (!$this->messeFemaleContestants->contains($messeFemaleContestant)) {
            $this->messeFemaleContestants->add($messeFemaleContestant);
            $messeFemaleContestant->setRegistration($this);
        }

        return $this;
    }

    public function removeMesseFemaleContestant(MesseFemaleContestant $messeFemaleContestant): self
    {
        if ($this->messeFemaleContestants->removeElement($messeFemaleContestant)) {
            // set the owning side to null (unless already changed)
            if ($messeFemaleContestant->getRegistration() === $this) {
                $messeFemaleContestant->setRegistration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MesseMaleContestant>
     */
    public function getMesseMaleContestants(): Collection
    {
        return $this->messeMaleContestants;
    }

    public function addMesseMaleContestant(MesseMaleContestant $messeMaleContestant): self
    {
        if (!$this->messeMaleContestants->contains($messeMaleContestant)) {
            $this->messeMaleContestants->add($messeMaleContestant);
            $messeMaleContestant->setRegistration($this);
        }

        return $this;
    }

    public function removeMesseMaleContestant(MesseMaleContestant $messeMaleContestant): self
    {
        if ($this->messeMaleContestants->removeElement($messeMaleContestant)) {
            // set the owning side to null (unless already changed)
            if ($messeMaleContestant->getRegistration() === $this) {
                $messeMaleContestant->setRegistration(null);
            }
        }

        return $this;
    }

    public function getContestants(): ArrayCollection
    {
        $all = new ArrayCollection();
        foreach ($this->getEgaFemaleContestants() as $contestant) {
            $all->add($contestant);
        }
        foreach ($this->getEgaMaleContestants() as $contestant) {
            $all->add($contestant);
        }
        foreach ($this->getMesseFemaleContestants() as $contestant) {
            $all->add($contestant);
        }
        foreach ($this->getMesseMaleContestants() as $contestant) {
            $all->add($contestant);
        }
        return $all;
    }
}
