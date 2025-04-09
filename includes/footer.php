</main>
    <footer class="py-5 mt-5" style="background: linear-gradient(to right, var(--primary-blue), var(--dark-blue));">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold mb-3"><?php echo SITE_TITLE; ?></h5>
                    <p class="text-white opacity-75">A simple blog website built with PHP and Bootstrap.</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="text-white fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>" class="text-white opacity-75 text-decoration-none hover-link">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/about.php" class="text-white opacity-75 text-decoration-none hover-link">About</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/contact.php" class="text-white opacity-75 text-decoration-none hover-link">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="text-white fw-bold mb-3">Follow Us</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white opacity-75 text-decoration-none hover-link"><i class="fab fa-facebook-f me-2"></i>Facebook</a></li>
                        <li><a href="#" class="text-white opacity-75 text-decoration-none hover-link"><i class="fab fa-twitter me-2"></i>Twitter</a></li>
                        <li><a href="#" class="text-white opacity-75 text-decoration-none hover-link"><i class="fab fa-instagram me-2"></i>Instagram</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.2);">
            <div class="text-center">
                <p class="text-white opacity-75 mb-0">© <?php echo date('Y'); ?> <?php echo SITE_TITLE; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome for social icons -->
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
    <!-- Custom JavaScript -->
    <script src="<?php echo SITE_URL; ?>/assets/js/script.js"></script>
</body>
</html>