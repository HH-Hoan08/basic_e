<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

class MailService {
    private PHPMailer $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configure();
    }

    private function configure() {
        // Server settings - BẠN CẦN THAY ĐỔI CÁC THÔNG TIN NÀY
        // Bạn nên lưu các thông tin này trong file config, không nên hardcode
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.gmail.com'; // VD: smtp.gmail.com
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'basicadmin4@gmail.com'; // Email của bạn
        $this->mailer->Password   = 'bgzc spxd tcjq qwom';                                                                                                                                                                                                                                                            // Mật khẩu ứng dụng Gmail
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port       = 465;
        $this->mailer->CharSet    = 'UTF-8';

        // Recipients
        $this->mailer->setFrom('no-reply@basicshop.com', 'Basic Shop');
    }

    public function sendPasswordResetEmail(string $recipientEmail, string $token): bool {
        try {
            $this->mailer->addAddress($recipientEmail);

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Yêu cầu đặt lại mật khẩu cho Basic Shop';
            $this->mailer->Body    = "
                <p>Chào bạn,</p>
                <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
                <p>Mã OTP của bạn là: <strong>{$token}</strong></p>
                <p>Mã này sẽ hết hạn sau 15 phút.</p>
                <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
                <p>Trân trọng,<br>Đội ngũ Basic Shop</p>
            ";
            $this->mailer->AltBody = "Mã OTP của bạn là: {$token}. Mã sẽ hết hạn sau 15 phút.";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // Ghi log lỗi để debug, không hiển thị cho người dùng
            error_log("Mailer Error: {$this->mailer->ErrorInfo}");
            return false;
        }
    }
}