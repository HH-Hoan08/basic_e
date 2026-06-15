<?php
class Product {
    private int $id;
    private int $categoryId;
    private ?int $brandId;
    private int $createdBy;
    private string $gender;
    private string $name;
    private ?string $brand;
    private string $slug;
    private ?string $description;
    private float $price;
    private ?float $salePrice;
    private int $stock;
    private ?string $image;
    private bool $isActive;
    private bool $isFeatured;
    private float $avgRating;
    private int $soldCount;
    private string $createdAt;
    private string $updateAt;
    private int $reviewCount;

    private ?string $brandName;
    private ?string $categoryName;
    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    public function hydrate(array $data) : void {
        $this->id = (int) ($data['id'] ?? 0);
        $this->categoryId = (int) ($data['category_id'] ?? 0);
        $this->brandId = isset($data['brand_id']) ? (int) $data['brand_id'] : null;
        $this->createdBy = (int) ($data['created_by'] ?? 0);
        $this->gender = ($data['gender'] ?? 'unisex');
        $this->name = ($data['name'] ?? '');
        $this->brand = ($data['brand'] ?? null);
        $this->slug = ($data['slug'] ?? '');
        $this->description = ($data['description'] ?? null);
        $this->price = (float) ($data['price'] ?? 0);
        $this->salePrice = isset($data['sale_price']) ? (float) $data['sale_price'] : null;
        $this->stock = (int) ($data['stock'] ?? 0);
        $this->image = ($data['image'] ?? null);
        $this->isActive = (bool) ($data['is_active'] ?? true);
        $this->isFeatured = (bool) ($data['is_featured'] ?? false);
        $this->avgRating = (float) ($data['avg_rating'] ?? 0.0);
        $this->soldCount = (int) ($data['sold_count'] ?? 0);
        $this->createdAt = ($data['created_at'] ?? '');
        $this->updateAt = ($data['updated_at'] ?? '');
        $this->brandName = ($data['brand_name'] ?? null);
        $this->categoryName = ($data['category_name'] ?? null);
        $this->reviewCount = (int) ($data['review_count'] ?? 0);
    }

    //getter
    public function getId() : int { 
        return $this->id; 
    }
    public function getCategoryId() : int { 
        return $this->categoryId; 
    }
    public function getBrandId() : ?int { 
        return $this->brandId; 
    }
    public function getCreatedBy() : int { 
        return $this->createdBy; 
    }
    public function getGender() : string { 
        return $this->gender; 
    }
    public function getName() : string { 
        return $this->name; 
    }
    public function getBrand() : ?string { 
        return $this->brand; 
    }
    public function getSlug() : string { 
        return $this->slug; 
    }
    public function getDescription() : ?string { 
        return $this->description;
    }
    public function getPrice() : float { 
        return $this->price; 
    }
    public function getSalePrice() : ?float { 
        return $this->salePrice; 
    }
    public function getStock() : int { 
        return $this->stock; 
    }
    public function getImage() : ?string { 
        return $this->image;
    }
    public function getIsActive() : bool { 
        return $this->isActive; 
    }
    public function getIsFeatured() : bool { 
        return $this->isFeatured; 
    }
    public function getAvgRating() : float {
        return $this->avgRating; 
    }
    public function getSoldCount() : int { 
        return $this->soldCount;
    }
    public function getCreatedAt() : string { 
        return $this->createdAt; 
    }
    public function getUpdatedAt() : string { 
        return $this->updateAt; 
    }
    public function getBrandName() : ?string { 
        return $this->brandName; 
    }
    public function getCategoryName() : ?string { 
        return $this->categoryName;
    }
    public function getReviewCount(): int {
        return $this->reviewCount;
    }

    //setter
    public function setId(int $id) : void { 
        $this->id = $id; 
    }
    public function setCategoryId(int $categoryId) : void { 
        $this->categoryId = $categoryId; 
    }
    public function setBrandId(?int $brandId) : void { 
        $this->brandId = $brandId; 
    }
    public function setCreatedBy(int $createdBy) : void { 
        $this->createdBy = $createdBy; 
    }
    public function setGender(string $gender) : void { 
        $this->gender = $gender; 
    }
    public function setName(string $name) : void  { 
        $this->name = $name ;
    }
    public function setBrand(?string $brand) : void { 
        $this->brand = $brand; 
    }
    public function setSlug(string $slug) : void  { 
        $this->slug = $slug; 
    }
    public function setDescription(?string $description) : void { 
        $this->description = $description; 
    }
    public function setPrice(float $price) : void { 
        $this->price = $price; 
    }
    public function setSalePrice(?float $salePrice) : void { 
        $this->salePrice = $salePrice; 
    }
    public function setStock(int $stock) : void { 
        $this->stock = $stock; 
    }
    public function setImage(?string $image) : void  { 
        $this->image = $image; 
    }
    public function setIsActive(bool $isActive) : void { 
        $this->isActive = $isActive; 
    }
    public function setIsFeatured(bool $isFeatured) : void { 
        $this->isFeatured = $isFeatured; 
    }
    public function setAvgRating(float $avgRating) : void { 
        $this->avgRating = $avgRating; 
    }
    public function setSoldCount(int $soldCount) : void { 
        $this->soldCount = $soldCount; 
    }
    public function setCreatedAt(string $createdAt) : void { 
        $this->createdAt = $createdAt; 
    
    }
    public function setUpdatedAt(string $updatedAt) : void { 
        $this->updateAt = $updatedAt; 
    }
    public function setBrandName(?string $brandName) : void { 
        $this->brandName = $brandName; 
    }
    public function setCategoryName(?string $categoryName) : void { 
        $this->categoryName = $categoryName; 
    }

    // giá hiển thị sale_price nếu có, ngược lại thì hiển thị mỗi giá gốc
    public function getDisplayPrice(): float {
        return $this->salePrice ?? $this->price;
    }

    // phần trăm giảm giá, trả về 0 nếu không có giảm giá
    public function getDiscountPercent(): int {
        if ($this->salePrice === null || $this->price <= 0) return 0;
        return (int)round((1 - $this->salePrice / $this->price) * 100);
    }

    // kiếm tra xem có giảm giá không
    public function hasDiscount(): bool {
        return $this->salePrice !== null && $this->salePrice < $this->price;
    }

    // kiểm tra xem sản phẩm còn hàng không 
    public function inStock(): bool {
        return $this->stock > 0;
    }

    // định dạng giá hiển thị 
    public function formatPrice(float $price): string {
        return number_format($price, 0, ',', '.') . 'đ';
    }

    // lấy rating làm tròn để hiển thị sao
    public function getRatingRounded(): int {
        return (int)round($this->avgRating);
    }

    // chuyển object thành mảng để dễ dàng trả về json hoặc các định dạng khác
    public function toArray(): array {
        return [
            'id'           => $this->id,
            'category_id'  => $this->categoryId,
            'brand_id'     => $this->brandId,
            'created_by'   => $this->createdBy,
            'gender'       => $this->gender,
            'name'         => $this->name,
            'brand'        => $this->brand,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'price'        => $this->price,
            'sale_price'   => $this->salePrice,
            'stock'        => $this->stock,
            'image'        => $this->image,
            'is_active'    => (int)$this->isActive,
            'is_featured'  => (int)$this->isFeatured,
            'avg_rating'   => $this->avgRating,
            'sold_count'   => $this->soldCount,
        ];
    }
}
