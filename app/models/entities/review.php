<?php
class Review {
    private int $id;
    private int $userId;
    private int $productId;
    private ?int $moderatedBy;
    private int $rating;
    private ?string $comment;
    private bool $isVisible;
    private string $createdAt;

    private ?string $userFullname = null;

    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->id = (int) ($data['id'] ?? 0);
            $this->userId = (int) ($data['user_id'] ?? 0);
            $this->productId = (int) ($data['product_id'] ?? 0);
            $this->moderatedBy = isset($data['moderated_by']) && $data['moderated_by'] !== null ? (int) $data['moderated_by'] : null;
            $this->rating = (int) ($data['rating'] ?? 0);
            $this->comment = $data['comment'] ?? null;
            $this->isVisible = (bool) ($data['is_visible'] ?? false);
            $this->createdAt = $data['created_at'] ?? '';
            $this->userFullname = $data['user_fullname'] ?? null;
        }
    }

    //getter 
    public function getId(): int {
        return $this->id;
    }
    public function getUserId(): int {
        return $this->userId;
    }
    public function getProductId(): int {
        return $this->productId;
    }
    public function getModeratedBy(): ?int {
        return $this->moderatedBy;
    }
    public function getRating(): int {
        return $this->rating;
    }
    public function getComment(): ?string {
        return $this->comment;
    }
    public function isVisible(): bool {
        return $this->isVisible;
    }
    public function getCreatedAt(): string {
        return $this->createdAt;
    }
    public function getUserFullname(): ?string {
        return $this->userFullname;
    }

    public function getFormattedDate(): string {
        $date = new DateTime($this->createdAt);
        return $date->format('d M Y');
    }
    
    //setter
    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setUserId(int $userId): void {
        $this->userId = $userId;
    }
    public function setProductId(int $productId): void {
        $this->productId = $productId;
    }
    public function setModeratedBy(?int $moderatedBy): void {
        $this->moderatedBy = $moderatedBy;
    }
    public function setRating(int $rating): void {
        $this->rating = $rating;
    }
    public function setComment(?string $comment): void {
        $this->comment = $comment;
    }
    public function setIsVisible(bool $isVisible): void {
        $this->isVisible = $isVisible;
    }
    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }
    public function setUserFullname(?string $userFullname): void {
        $this->userFullname = $userFullname;
    }
}