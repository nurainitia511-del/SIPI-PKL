<body>
    <div class="wrapper">
        <!-- Isi halaman -->
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p class="animated-text">&copy; <?php echo date('Y'); ?> SIPI PKL. All rights reserved.</p>
        </div>
    </footer>
</body>


<style>
    /* Pastikan body dan html memiliki tinggi penuh */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
}

/* Pastikan wrapper utama mengambil sisa ruang */
.wrapper {
    flex: 1;
}

/* Footer tetap di bawah */
.footer {
    background: linear-gradient(45deg, #4facfe, rgb(43, 149, 241)); 
    color: white;
    padding: 15px 0;
    text-align: center;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
    font-weight: bold;
}

    /* Animasi teks menggantikan marquee */
    .animated-text {
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        animation: slideText 10s linear infinite;
    }

    @keyframes slideText {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>
