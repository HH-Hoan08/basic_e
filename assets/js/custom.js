/*
 * Custom JS - Thêm mã tùy chỉnh của bạn vào đây
 */


/**
 * Hiệu ứng Typewriter cho banner trang chủ
 */
const carousel = document.getElementById('template-mo-zay-hero-carousel');
if (carousel) { // Chỉ chạy mã typewriter nếu có carousel
    const allSlides = carousel.querySelectorAll('.carousel-item');

    // Lưu trữ văn bản gốc của các tiêu đề
    allSlides.forEach(slide => {
        const h1 = slide.querySelector('h1');
        const h3 = slide.querySelector('h3');
        if (h1) h1.dataset.originalText = h1.innerHTML;
        if (h3) h3.dataset.originalText = h3.innerHTML;
    });

    // Hàm gõ chữ
    function typeWriter(element, i, onComplete) {
        if (!element || !element.dataset.originalText) {
            if (onComplete) onComplete();
            return;
        }
        const text = element.dataset.originalText;
        if (i < text.length) {
            element.innerHTML = text.substring(0, i + 1) + '<span class="cursor"></span>';
            setTimeout(() => typeWriter(element, i + 1, onComplete), 80); // Tốc độ gõ
        } else {
            element.innerHTML = text; // Xóa con trỏ khi gõ xong
            if (onComplete) onComplete();
        }
    }

    // Hàm kích hoạt hiệu ứng cho một slide
    function animateSlide(slide) {
        const h1 = slide.querySelector('h1');
        const h3 = slide.querySelector('h3');
        const p = slide.querySelector('p');

        // Xóa nội dung để chuẩn bị gõ lại
        if (h1) h1.innerHTML = '&nbsp;'; // Giữ chiều cao
        if (h3) h3.innerHTML = '&nbsp;';
        if (p) p.style.opacity = 0;

        // Bắt đầu hiệu ứng sau một khoảng trễ nhỏ để slide chuyển động mượt mà
        setTimeout(() => {
            if (h1) {
                typeWriter(h1, 0, () => {
                    // Gõ xong h1 thì gõ h3
                    if (h3) {
                        typeWriter(h3, 0, () => {
                            // Gõ xong h3 thì hiện đoạn văn
                            if (p) {
                                p.style.transition = 'opacity 0.5s';
                                p.style.opacity = 1;
                            }
                        });
                    }
                });
            }
        }, 500); // Trễ 500ms
    }

    // Hàm khôi phục văn bản gốc cho các slide không hoạt động
    function resetSlide(slide) {
        const h1 = slide.querySelector('h1');
        const h3 = slide.querySelector('h3');
        const p = slide.querySelector('p');
        if (h1 && h1.dataset.originalText) h1.innerHTML = h1.dataset.originalText;
        if (h3 && h3.dataset.originalText) h3.innerHTML = h3.dataset.originalText;
        if (p) p.style.opacity = 1;
    }

    // Kích hoạt hiệu ứng cho slide đầu tiên khi tải trang
    const firstSlide = carousel.querySelector('.carousel-item.active');
    if (firstSlide) animateSlide(firstSlide);

    // Lắng nghe sự kiện khi slide thay đổi
    carousel.addEventListener('slid.bs.carousel', function(event) {
        const activeSlide = event.relatedTarget;
        allSlides.forEach(slide => { if (slide !== activeSlide) resetSlide(slide); });
        if (activeSlide) animateSlide(activeSlide);
    });
}

/**
 * Hiệu ứng xuất hiện ngẫu nhiên khi cuộn trang
 */
const animationClasses = ['fade-in-up', 'fade-in-left', 'fade-in-right', 'zoom-in'];
// Nhắm vào các cột chứa thương hiệu và sản phẩm nổi bật
const elementsToAnimate = document.querySelectorAll('#categories-of-month .col-md-4, section.bg-light .col-md-4.mb-4');

if ('IntersectionObserver' in window && elementsToAnimate.length > 0) {
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Chọn một hiệu ứng ngẫu nhiên từ danh sách
                const randomAnimation = animationClasses[Math.floor(Math.random() * animationClasses.length)];
                
                // Thêm các lớp CSS để kích hoạt hiệu ứng
                entry.target.classList.add(randomAnimation, 'is-visible');

                // Dừng theo dõi phần tử sau khi đã kích hoạt hiệu ứng
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1 // Kích hoạt khi 10% phần tử hiển thị
    });

    elementsToAnimate.forEach(el => {
        // Thêm lớp ban đầu để ẩn phần tử và chuẩn bị cho hiệu ứng
        el.classList.add('animate-on-scroll');
        observer.observe(el);
    });
}

/**
 * Nút "Xem thêm" cho bộ lọc danh mục
 */
const showMoreCatsBtn = document.getElementById('show-more-cats');
if (showMoreCatsBtn) {
    showMoreCatsBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const extraCats = document.querySelectorAll('#category-filter-list .extra-category');
        extraCats.forEach(cat => {
            cat.classList.toggle('d-none');
        });

        if (this.innerHTML.includes('Xem thêm')) {
            this.innerHTML = 'Thu gọn <i class="fa fa-angle-up"></i>';
        } else {
            this.innerHTML = 'Xem thêm <i class="fa fa-angle-down"></i>';
        }
    });
}