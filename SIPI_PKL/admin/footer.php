<body>
    <div class="wrapper">
        <!-- Konten utama admin -->
    </div>

    <footer class="footer">
        <div class="container text-center">
            <marquee>
                <p>&copy; <?php echo date('Y'); ?> SIPI PKL. All rights reserved.</p>
            </marquee>
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

/* Wrapper utama agar konten memenuhi sisa ruang */
.wrapper {
    flex: 1;
}

/* Footer tetap di bawah */
.footer {
    background: linear-gradient(45deg, #4facfe, rgb(43, 149, 241)); 
    color: white;
    padding: 15px 0;
    text-align: center;
    width: 100%;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
}

/* Styling tambahan untuk teks marquee */
.footer marquee {
    font-weight: bold;
    font-size: 14px;
    color: white;
}

</style>
