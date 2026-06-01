# BasicE

```text
 basic/
│
├── 📁 app/                    		
│   ├── 📁 controllers/        		
│   │   ├── HomeController.php
│   │   └── ProductController.php
│   │
│   ├── 📁 models/             		# Tương tác với Database (như bảng sản phẩm, người dùng)
│   │   ├── Database.php       		# kết nối database
│   │   └── ProductModel.phpdatabase
│   │
│   └── 📁 views/              		# Nơi chứa toàn bộ giao diện
│       ├── 📁 inc/            		# Mảnh ghép Header, Footer
│       │   ├── header.php
│       │   └── footer.php
│       ├── home.php           	
│       ├── shop.php           		
│       └── contact.php
│       └── shop-single.php  		
│       └── shop.php  	
│
├── 📁 assets/                 		# css, js, images, webfonts
│   ├── 📁 css/
│   ├── 📁 js/
│   └── 📁 img/
│
└── index.php                  		#Cổng vào duy nhất của Website
```
