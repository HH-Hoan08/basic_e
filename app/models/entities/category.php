<?php
class Category {
    private int $id;
    private ?int $parentId;
    private string $name;
    private string $slug;
    private string $gender;
    private ?string $image;
    private ?string $icon;
    private bool $isActive;
    private int $sortOrder;

    public function __construct(array $data = []) {
        if (!empty($data))
            $this->hydrate($data);
    }

    public function hydrate(array $data) : void {
        $this->id = (int) ($data['id'] ?? 0);
        $this->parentId = isset($data['parent_id']) && $data['parent_id'] !== null ? (int) $data['parent_id'] : null;
        $this->name = ($data['name'] ?? '');
        $this->slug = ($data['slug'] ?? '');
        $this->gender = ($data['gender'] ?? 'all');
        $this->image = ($data['image'] ?? null);
        $this->icon = ($data['icon'] ?? null);
        $this->isActive = (bool) ($data['is_active'] ?? true);
        $this->sortOrder = (int) ($data['sort_order'] ?? 0);
    }

    //getter
    public function getId(): int { 
        return $this->id; 
    }
    public function getParentId(): ?int { 
        return $this->parentId; 
    }
    public function getName(): string { 
        return $this->name; 
    }
    public function getSlug(): string { 
        return $this->slug; 
    }
    public function getGender(): string { 
        return $this->gender;
    }
    public function getImage(): ?string{ 
        return $this->image; 
    }
    public function getIcon(): ?string { 
        return $this->icon; 
    }
    public function isActive(): bool { 
        return $this->isActive; 
    }
    public function getSortOrder(): int { 
        return $this->sortOrder; 
    }
    public function isParent(): bool { 
        return $this->parentId === null; 
    }

    //setter
    public function setId(int $v): void { 
        $this->id = $v; 
    }
    public function setParentId(?int $v): void { 
        $this->parentId = $v; 
    }
    public function setName(string $v): void { 
        $this->name = $v; 
    }
    public function setSlug(string $v): void { 
        $this->slug = $v; 
    }
    public function setGender(string $v): void { 
        $this->gender = $v; 
    }
    public function setImage(?string $v): void { 
        $this->image = $v; 
    }
    public function setIcon(?string $v): void { 
        $this->icon = $v; 
    }
    public function setIsActive(bool $v): void { 
        $this->isActive = $v; 
    }
    public function setSortOrder(int $v): void { 
        $this->sortOrder = $v; 
    }
}