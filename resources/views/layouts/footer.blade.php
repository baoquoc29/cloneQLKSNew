<footer class="footer">
    <div class="footer-content">
        <div class="footer-section about">
            <h3><i class="fas fa-bus"></i> Hải Minh Express</h3>
            <p>Chúng tôi cung cấp dịch vụ đặt vé xe trực tuyến tiện lợi, an toàn và đáng tin cậy cho mọi hành trình của
                bạn.</p>
            <div class="contact">
                <span><i class="fas fa-phone"></i> +84 394 621 961</span>
                <span><i class="fas fa-envelope"></i> info@haiminhexpress.vn</span>
            </div>
            <div class="socials">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
        <div class="footer-section links">
            <h3>Liên Kết Nhanh</h3>
            <ul>
                <li><a href="#"><i class="fas fa-angle-right"></i> Trang Chủ</a></li>
                <li><a href="#"><i class="fas fa-angle-right"></i> Đặt Vé</a></li>
                <li><a href="#"><i class="fas fa-angle-right"></i> Lịch Trình</a></li>
                <li><a href="#"><i class="fas fa-angle-right"></i> Về Chúng Tôi</a></li>
                <li><a href="#"><i class="fas fa-angle-right"></i> Liên Hệ</a></li>
            </ul>
        </div>
        <div class="footer-section contact-form">
            <h3>Liên Hệ Với Chúng Tôi</h3>
            <form action="#" method="post">
                <input type="email" name="email" class="text-input contact-input" placeholder="Email của bạn...">
                <textarea name="message" class="text-input contact-input" placeholder="Tin nhắn của bạn..."></textarea>
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-envelope"></i> Gửi</button>
            </form>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2023 Hải Minh Express | Thiết kế bởi Hải Minh
    </div>
</footer>

<style>
    .footer {
        background-color: #333;
        color: #fff;
        padding: 40px 0 20px;
        font-family: 'Roboto', sans-serif;
    }

    .footer-content {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-section {
        flex: 1;
        padding: 0 20px;
        min-width: 300px;
        margin-bottom: 20px;
    }

    .footer-section h3 {
        color: #FF6347;
        margin-bottom: 20px;
        font-size: 18px;
        font-weight: 700;
    }

    .about p {
        margin-bottom: 20px;
    }

    .contact span {
        display: block;
        margin-bottom: 10px;
    }

    .socials a {
        display: inline-block;
        width: 35px;
        height: 35px;
        background: #FF6347;
        color: #fff;
        border-radius: 50%;
        text-align: center;
        line-height: 35px;
        margin-right: 10px;
        transition: all 0.3s;
    }

    .socials a:hover {
        background: #fff;
        color: #FF6347;
    }

    .links ul {
        list-style-type: none;
        padding: 0;
    }

    .links ul li {
        margin-bottom: 10px;
    }

    .links ul li a {
        color: #ddd;
        text-decoration: none;
        transition: all 0.3s;
    }

    .links ul li a:hover {
        color: #FF6347;
        padding-left: 5px;
    }

    .contact-form .contact-input {
        background: #444;
        color: #fff;
        border: none;
        margin-bottom: 10px;
        padding: 10px;
        width: 100%;
    }

    .contact-form .contact-input::placeholder {
        color: #ddd;
    }

    .contact-form button {
        background: #FF6347;
        border: none;
    }

    .contact-form button:hover {
        background: #ff7f50;
    }

    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #444;
    }

    @media screen and (max-width: 768px) {
        .footer-section {
            flex: 100%;
        }
    }
</style>
