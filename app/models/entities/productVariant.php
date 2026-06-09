<?php
class ProductVariant {
    private int     $id;
    private int     $productId;
    private ?string $size;
    private ?string $color;
    private ?string $colorHex;
    private int     $stock;
    private float   $extraPrice;
    private ?string $sku;

    public function __construct(array $data = []) {
        if (!empty($data)) 
            $this->hydrate($data);
    }

    public function hydrate(array $data): void {
        $this->id         = (int) ($data['id'] ?? 0);
        $this->productId  = (int) ($data['product_id'] ?? 0);
        $this->size       = ($data['size'] ?? null);
        $this->color      = ($data['color'] ?? null);
        $this->colorHex   = ($data['color_hex'] ?? null);
        $this->stock      = (int) ($data['stock'] ?? 0);
        $this->extraPrice = (float) ($data['extra_price'] ?? 0);
        $this->sku        = ($data['sku'] ?? null);
    }

    // getter
    public function getId(): int { 
        return $this->id; 
    }
    public function getProductId(): int { 
        return $this->productId; 
    }
    public function getSize(): ?string { 
        return $this->size; 
    }
    public function getColor(): ?string { 
        return $this->color; 
    }
    public function getColorHex(): ?string { 
        return $this->colorHex; 
    }
    public function getStock(): int { 
        return $this->stock; 
    }
    public function getExtraPrice(): float { 
        return $this->extraPrice; 
    }
    public function getSku(): ?string { 
        return $this->sku; 
    }
    public function inStock(): bool { 
        return $this->stock > 0; 
    }

    // setter
    public function setId(int $id): void { 
        $this->id = $id; 
    }
    public function setProductId(int $productId): void { 
        $this->productId = $productId; 
    }
    public function setSize(?string $size): void { 
        $this->size = $size; 
    }
    public function setColor(?string $color): void { 
        $this->color = $color; 
    }
    public function setColorHex(?string $colorHex): void { 
        $this->colorHex = $colorHex; 
    }
    public function setStock(int $stock): void { 
        $this->stock = $stock; 
    }
    public function setExtraPrice(float $extraPrice): void { 
        $this->extraPrice = $extraPrice; 
    }
    public function setSku(?string $sku): void { 
        $this->sku = $sku; 
    }
}