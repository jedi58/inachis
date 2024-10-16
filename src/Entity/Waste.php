<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use App\Exception\InvalidTimezoneException;

/**
 * Object for handling {@link Waste} contents
 * @ORM\Entity(repositoryClass="App\Repository\WasteRepository")
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"sourceType", "user_id"})})
 */
class Waste
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $sourceType;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"detach"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var User The author for the {@link Page}
     */
    private $user;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var string The date the item was added to the bin
     */
    protected $modDate;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSourceType(): ?string
    {
        return $this->sourceType;
    }

    public function setSourceType(string $sourceType): self
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    /**
     * Returns the value of {@link modDate}.
     *
     * @return string The date the {@link Page} was last modified
     */
    public function getModDate()
    {
        return $this->modDate;
    }

    /**
     * Sets the value of {@link modDate}.
     *
     * @param \DateTime $value The date to set
     * @return Revision
     */
    public function setModDate(\DateTime $value = null): self
    {
        $this->modDate = $value;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets the value of {@link author}.
     *
     * @param User $value The UUID of user adding the {@link Waste}
     * @return Revision
     */
    public function setUser(User $value = null): self
    {
        $this->user = $value;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
