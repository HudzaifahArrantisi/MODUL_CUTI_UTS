<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Pengajuan Cuti Karyawan | PT DeepSeek</title>
  
  <!-- Favicon -->
  <link rel="icon" href="img/images.png" type="image/png">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  
  <!-- Animate on Scroll -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
  
  <!-- Glide.js -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/css/glide.core.min.css">

  <style>
    :root {
      --primary-blue: #0033a0;  
      --primary-orange: #ff6a00; 
      --secondary-blue: #0056b3;
      --light-gray: #f8f9fa;
      --dark-gray: #343a40;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-gray);
      color: #333;
      overflow-x: hidden;
    }
    
    /* Navbar Styles */
    .navbar {
      background: rgba(0, 51, 160, 0.9);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
      padding: 15px 0;
      transition: all 0.4s ease;
    }
    
    .navbar.scrolled {
      padding: 10px 0;
      background: var(--primary-blue) !important;
    }
    
    .navbar-brand {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 1.8rem;
      display: flex;
      align-items: center;
    }
    
    .navbar-brand img {
      height: 40px;
      margin-right: 10px;
    }
    
    .nav-link {
      font-weight: 500;
      margin: 0 10px;
      position: relative;
    }
    
    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      background: white;
      bottom: 0;
      left: 0;
      transition: width 0.3s ease;
    }
    
    .nav-link:hover::after {
      width: 100%;
    }
    
    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, rgba(0, 51, 160, 0.85) 0%, rgba(0, 86, 179, 0.9) 100%), 
                  url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .hero-section::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 100px;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="%23f8f9fa"></path><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="%23f8f9fa"></path><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%23f8f9fa"></path></svg>');
      background-size: cover;
      z-index: 1;
    }
    
    .hero-content {
      position: relative;
      z-index: 2;
    }
    
    .hero-title {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      font-size: 3.5rem;
      line-height: 1.2;
      margin-bottom: 1.5rem;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .hero-subtitle {
      font-size: 1.3rem;
      max-width: 700px;
      margin: 0 auto 2.5rem;
      opacity: 0.9;
    }
    
    /* Button Styles */
    .btn-astra {
      background-color: var(--primary-orange);
      border: none;
      color: white;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(255, 106, 0, 0.3);
    }
    
    .btn-astra:hover {
      background-color: #e05d00;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 106, 0, 0.4);
      color: white;
    }
    
    .btn-outline-light {
      border: 2px solid white;
      padding: 10px 28px;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
    }
    
    .btn-outline-light:hover {
      background: white;
      color: var(--primary-blue);
      transform: translateY(-3px);
    }
    
    /* Features Section */
    .features-section {
      padding: 6rem 0;
      position: relative;
    }
    
    .section-title {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      color: var(--primary-blue);
      margin-bottom: 1.5rem;
      position: relative;
      display: inline-block;
    }
    
    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--primary-orange);
      border-radius: 2px;
    }
    
    .section-subtitle {
      color: #6c757d;
      max-width: 700px;
      margin: 0 auto 4rem;
    }
    
    .feature-card {
      background: white;
      border-radius: 15px;
      padding: 2.5rem 2rem;
      text-align: center;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
      transition: all 0.4s ease;
      height: 100%;
      border-bottom: 4px solid transparent;
      margin-bottom: 20px;
    }
    
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      border-bottom-color: var(--primary-orange);
    }
    
    .feature-icon {
      font-size: 3rem;
      color: var(--primary-blue);
      margin-bottom: 1.5rem;
      display: inline-flex;
      width: 80px;
      height: 80px;
      align-items: center;
      justify-content: center;
      background: rgba(0, 51, 160, 0.1);
      border-radius: 50%;
      transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
      background: var(--primary-blue);
      color: white;
      transform: rotateY(180deg);
    }
    
    /* Stats Section */
    .stats-section {
      padding: 5rem 0;
      background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
      color: white;
      position: relative;
    }
    
    .stat-item {
      text-align: center;
      padding: 2rem;
      position: relative;
      z-index: 2;
    }
    
    .stat-number {
      font-size: 3.5rem;
      font-weight: 800;
      font-family: 'Montserrat', sans-serif;
      margin-bottom: 0.5rem;
    }
    
    /* Testimonials */
    .testimonials-section {
      padding: 6rem 0;
      background-color: white;
    }
    
    .testimonial-card {
      background: white;
      border-radius: 15px;
      padding: 2.5rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      margin: 1rem;
      transition: all 0.3s ease;
      border-left: 4px solid var(--light-gray);
    }
    
    .testimonial-card:hover {
      border-left-color: var(--primary-orange);
      transform: translateY(-5px);
    }
    
    .testimonial-text {
      font-style: italic;
      margin-bottom: 1.5rem;
      color: #555;
      position: relative;
    }
    
    .testimonial-text::before {
      content: '"';
      font-size: 4rem;
      color: rgba(0, 51, 160, 0.1);
      position: absolute;
      top: -20px;
      left: -15px;
      font-family: serif;
      line-height: 1;
    }
    
    .testimonial-author {
      display: flex;
      align-items: center;
    }
    
    .testimonial-author img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      margin-right: 15px;
      object-fit: cover;
      border: 3px solid var(--primary-blue);
    }
    
    .author-info h6 {
      font-weight: 700;
      margin-bottom: 0.2rem;
      color: var(--primary-blue);
    }
    
    /* CTA Section */
    .cta-section {
      padding: 6rem 0;
      background: linear-gradient(rgba(0, 51, 160, 0.9), rgba(0, 51, 160, 0.9)), 
                  url('https://images.unsplash.com/photo-1521791055366-0d553872125f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') no-repeat center center fixed;
      background-size: cover;
      color: white;
      position: relative;
    }
    
    .cta-title {
      font-family: 'Montserrat', sans-serif;
      font-weight: 800;
      margin-bottom: 1.5rem;
    }
    
    /* Footer */
    footer {
      background-color: var(--primary-blue);
      color: white;
      padding: 4rem 0 2rem;
    }
    .navbar {
  min-height: 60px; /* Sesuaikan dengan tinggi logo */
}

.navbar-brand {
  padding: 0;
  margin: 0;
}

.navbar-brand img {
  max-height: %;
  width: auto;
}
    
    .footer-logo {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
      display: inline-block;
    }
    
    .footer-links h5 {
      font-weight: 700;
      margin-bottom: 1.5rem;
      font-size: 1.2rem;
    }
    
    .footer-links ul {
      list-style: none;
      padding-left: 0;
    }
    
    .footer-links li {
      margin-bottom: 0.8rem;
    }
    
    .footer-links a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s ease;
    }
    
    .footer-links a:hover {
      color: white;
      padding-left: 5px;
    }
    
    .social-links a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      color: white;
      margin-right: 10px;
      transition: all 0.3s ease;
    }
    
    .social-links a:hover {
      background: var(--primary-orange);
      transform: translateY(-3px);
    }
    
    .copyright {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 2rem;
      margin-top: 3rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.7);
    }
    
    /* Back to Top Button */
    .back-to-top {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--primary-orange);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      z-index: 99;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    
    .back-to-top.active {
      opacity: 1;
      visibility: visible;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 992px) {
      .hero-title {
        font-size: 2.8rem;
      }
      
      .section-title {
        font-size: 2rem;
      }
    }
    
    @media (max-width: 768px) {
      .hero-section {
        height: auto;
        padding: 8rem 0;
      }
      
      .hero-title {
        font-size: 2.2rem;
      }
      
      .hero-subtitle {
        font-size: 1.1rem;
      }
      
      .stat-number {
        font-size: 2.5rem;
      }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
  <a class="navbar-brand d-flex align-items-center" href="#" style="height: 60px;"> <!-- Tinggi disesuaikan dengan navbar -->
  <img src="img/deepseek.png" alt="PT DeepSeek Logo" class="h-100 py-1" style="object-fit: contain;"> 
</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#features">Fitur</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#testimonials">Testimoni</a>
        </li>
        <li class="nav-item ms-lg-3">
          <a class="btn btn-sm btn-outline-light rounded-pill px-3" href="login.php">Login</a>
        </li>
        <li class="nav-item ms-lg-2">
          <a class="btn btn-sm btn-astra rounded-pill px-3" href="register.php">Register</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 text-center">
        <h1 class="hero-title" data-aos="fade-up">Sistem Pengajuan Cuti Karyawan PT DeepSeek</h1>
        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
          Platform digital modern untuk mengelola pengajuan cuti karyawan dengan efisien dan transparan
        </p>
        <div data-aos="fade-up" data-aos-delay="200">
          <a href="login.php" class="btn btn-astra btn-lg mx-2 my-2">Login Sekarang</a>
          <a href="register.php" class="btn btn-outline-light btn-lg mx-2 my-2">Daftar Akun</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="section-title">Fitur Unggulan Sistem Kami</h2>
      <p class="section-subtitle">
        Solusi lengkap untuk manajemen cuti karyawan yang lebih baik dan terintegrasi
      </p>
    </div>
    
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-send-check"></i>
          </div>
          <h4 class="fw-bold">Pengajuan Online</h4>
          <p class="text-muted">
            Ajukan cuti kapan saja dan di mana saja melalui platform digital yang mudah digunakan
          </p>
        </div>
      </div>
      
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-clipboard-check"></i>
          </div>
          <h4 class="fw-bold">Persetujuan Cepat</h4>
          <p class="text-muted">
            Proses persetujuan yang efisien dengan notifikasi real-time untuk atasan dan karyawan
          </p>
        </div>
      </div>
      
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-calendar2-range"></i>
          </div>
          <h4 class="fw-bold">Kalender Terpadu</h4>
          <p class="text-muted">
            Pantau jadwal cuti tim Anda dengan kalender visual yang terintegrasi
          </p>
        </div>
      </div>
      
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-clock-history"></i>
          </div>
          <h4 class="fw-bold">Riwayat Lengkap</h4>
          <p class="text-muted">
            Akses riwayat cuti pribadi dan tim dengan mudah untuk perencanaan yang lebih baik
          </p>
        </div>
      </div>
      
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-graph-up"></i>
          </div>
          <h4 class="fw-bold">Analitik Cuti</h4>
          <p class="text-muted">
            Laporan dan analisis data cuti untuk pengambilan keputusan yang lebih baik
          </p>
        </div>
      </div>
      
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="bi bi-shield-lock"></i>
          </div>
          <h4 class="fw-bold">Keamanan Data</h4>
          <p class="text-muted">
            Sistem terenkripsi dengan otentikasi multi-faktor untuk keamanan data karyawan
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-number" data-count="2500">0</div>
        <p>Karyawan Aktif</p>
      </div>
      <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-number" data-count="98.7">0</div>
        <p>Kepuasan Pengguna</p>
      </div>
      <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-number" data-count="99.9">0</div>
        <p>Uptime Sistem</p>
      </div>
      <div class="col-md-3 col-6 stat-item" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-number" data-count="24">0</div>
        <p>Jam Pengolahan</p>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section" id="testimonials">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="section-title">Apa Kata Mereka?</h2>
      <p class="section-subtitle">Testimoni dari karyawan dan manajemen PT DeepSeek</p>
    </div>
    
    <div class="glide" data-aos="fade-up">
      <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
          <li class="glide__slide">
            <div class="testimonial-card">
              <p class="testimonial-text">
                Sistem ini sangat memudahkan saya sebagai HRD dalam mengelola cuti karyawan. Proses yang dulunya memakan waktu berhari-hari sekarang bisa diselesaikan dalam hitungan menit.
              </p>
              <div class="testimonial-author">
                <img src="img/padil.jpg" alt="Sarah Wijaya">
                <div class="author-info">
                  <h6>Muhammad Fadil</h6>
                  <p>HR Manager PT DeepSeek</p>
                </div>
              </div>
            </div>
          </li>
          
          <li class="glide__slide">
            <div class="testimonial-card">
              <p class="testimonial-text">
                Sebagai manajer, saya sangat menghargai kemudahan menyetujui cuti tim saya melalui smartphone. Fitur notifikasi dan kalender integrasinya sangat membantu.
              </p>
              <div class="testimonial-author">
                <img src="img/rapa 2.jpg" alt="Budi Santoso">
                <div class="author-info">
                  <h6>Rafa Al-Razzak</h6>
                  <p>Developer </p>
                </div>
              </div>
            </div>
          </li>
          
          <li class="glide__slide">
            <div class="testimonial-card">
              <p class="testimonial-text">
                Pengajuan cuti sekarang sangat mudah dan cepat. Saya bisa langsung tahu status pengajuan saya dan mendapatkan notifikasi ketika disetujui.
              </p>
              <div class="testimonial-author">
                <img src="img/ucup1.jpg" alt="Dewi Lestari">
                <div class="author-info">
                  <h6>Muhammad Yusus Walidain</h6>
                  <p>Marketing Executive</p>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="glide__arrows" data-glide-el="controls">
        <button class="glide__arrow glide__arrow--left" data-glide-dir="<"><i class="bi bi-chevron-left"></i></button>
        <button class="glide__arrow glide__arrow--right" data-glide-dir=">"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center" data-aos="zoom-in">
        <h2 class="cta-title">Siap Menggunakan Sistem Cuti Digital?</h2>
        <p class="mb-4">
          Bergabunglah dengan ribuan karyawan PT DeepSeek yang sudah merasakan kemudahan mengajukan cuti secara digital
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-4">
        <a href="#" class="footer-logo d-block mb-3">
          <img src="img/deepseek.png" alt="PT DeepSeek Logo" style="height: 40px;">
        </a>
        <p>
          Sistem Pengajuan Cuti Karyawan PT DeepSeek - Solusi digital untuk manajemen cuti yang lebih efisien dan transparan.
        </p>
        <div class="social-links mt-3">
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-twitter"></i></a>
          <a href="#"><i class="bi bi-linkedin"></i></a>
          <a href="#"><i class="bi bi-instagram"></i></a>
        </div>
      </div>
      
      <div class="col-lg-2 col-md-6 mb-4">
        <h5>Navigasi</h5>
        <ul class="footer-links">
          <li><a href="#">Beranda</a></li>
          <li><a href="#features">Fitur</a></li>
          <li><a href="#testimonials">Testimoni</a></li>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Register</a></li>
        </ul>
      </div>
      
      <div class="col-lg-2 col-md-6 mb-4">
        <h5>Dokumen</h5>
        <ul class="footer-links">
          <li><a href="#">Kebijakan Cuti</a></li>
          <li><a href="#">Panduan Pengguna</a></li>
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Syarat & Ketentuan</a></li>
        </ul>
      </div>
      
      <div class="col-lg-4 mb-4">
        <h5>Kontak Kami</h5>
        <ul class="footer-links">
          <li><i class="bi bi-geo-alt me-2"></i> Jl. Gaya Motor Raya No.8, Jakarta 14310</li>
          <li><i class="bi bi-telephone me-2"></i> (021) 6510999</li>
          <li><i class="bi bi-envelope me-2"></i> hr@deepseek.com</li>
          <li><i class="bi bi-clock me-2"></i> Senin-Jumat, 08:00 - 17:00</li>
        </ul>
      </div>
    </div>
    
    <div class="copyright">
      <p class="mb-0">© <?php echo date('Y'); ?> PT DeepSeek International</p>
    </div>
  </div>
</footer>

<!-- Back to Top Button -->
<a href="#" class="back-to-top"><i class="bi bi-arrow-up"></i></a>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/glide.min.js"></script>

<script>
  // Initialize AOS
  AOS.init({
    duration: 800,
    once: true,
    offset: 120
  });
  
  // Initialize Glide.js
  new Glide('.glide', {
    type: 'carousel',
    perView: 1,
    focusAt: 'center',
    gap: 30,
    autoplay: 4000,
    breakpoints: {
      768: {
        perView: 1
      }
    }
  }).mount();
  
  // Counter animation
  function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;
    
    counters.forEach(counter => {
      const target = +counter.getAttribute('data-count');
      const count = +counter.innerText;
      const increment = target / speed;
      
      if(count < target) {
        counter.innerText = Math.ceil(count + increment);
        setTimeout(animateCounters, 1);
      } else {
        counter.innerText = target;
      }
    });
  }
  
  // Start counting when element is in viewport
  const observer = new IntersectionObserver((entries) => {
    if(entries[0].isIntersecting) {
      animateCounters();
    }
  }, {threshold: 0.5});
  
  document.querySelectorAll('.stat-item').forEach(item => {
    observer.observe(item);
  });
  
  // Navbar scroll effect
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
  
  // Back to top button
  window.addEventListener('scroll', function() {
    const backToTop = document.querySelector('.back-to-top');
    if (window.scrollY > 300) {
      backToTop.classList.add('active');
    } else {
      backToTop.classList.remove('active');
    }
  });
  
  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      if(targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if(targetElement) {
        targetElement.scrollIntoView({
          behavior: 'smooth'
        });
      }
    });
  });
</script>
</body>
</html>