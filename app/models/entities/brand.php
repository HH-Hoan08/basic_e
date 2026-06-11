<?php
class Brand {
    private int $id;
    private string $name;
    private string $slug;
    private ?string $logo;
    private ?string $website;
    private bool $isActive;
    private int $sortOrder;

    public function __construct(array $data = []) {
        if (!empty($data))
            $this->hydrate($data);
    }

    public function hydrate(array $data) : void {
        $this->id = (int) ($data['id'] ?? 0);
        $this->name = ($data['name'] ?? '');
        $this->slug = ($data['slug'] ?? '');
        $this->logo = ($data['logo'] ?? null);
        $this->website = ($data['website'] ?? null);
        $this->isActive = (bool) ($data['is_active'] ?? true);
        $this->sortOrder = (int) ($data['sort_order'] ?? 0);
    }

    // getter
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSlug(): string {
        return $this->slug;
    }

    public function getLogo(): ?string {
        return $this->logo;
    }

    public function getWebsite(): ?string {
        return $this->website;
    }

    public function isActive(): bool {
        return $this->isActive;
    }

    public function getSortOrder(): int {
        return $this->sortOrder;
    }

    // setter
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setSlug(string $slug): void {
        $this->slug = $slug;
    }

    public function setLogo(?string $logo): void {
        $this->logo = $logo;
    }

    public function setWebsite(?string $website): void {
        $this->website = $website;
    }

    public function setIsActive(bool $isActive): void {
        $this->isActive = $isActive;
    }

    public function setSortOrder(int $sortOrder): void {
        $this->sortOrder = $sortOrder;
    }
}