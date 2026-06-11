<?php
class ProductImage {
    private int $id;
    private int $productId;
    private string $imageUrl;
    private int $sortOrder;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->id = (int) ($data['id'] ?? 0);
            $this->productId = (int) ($data['product_id'] ?? 0);
            $this->imageUrl = ($data['image_url'] ?? '');
            $this->sortOrder = (int) ($data['sort_order'] ?? 0);
        }
    }

    //getter
    public function getId(): int {
        return $this->id;
    }
    public function getProductId(): int {
        return $this->productId;
    }
    public function getImageUrl(): string {
        return $this->imageUrl;
    }
    public function getSortOrder(): int {
        return $this->sortOrder;
    }

    //setter
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setProductId(int $productId): void {
        $this->productId = $productId;
    }
    public function setImageUrl(string $imageUrl): void {
        $this->imageUrl = $imageUrl;
    }
    public function setSortOrder(int $sortOrder): void {
        $this->sortOrder = $sortOrder;
    }
}