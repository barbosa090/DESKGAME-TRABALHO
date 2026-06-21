<?php
session_start();
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DESKGAME - Catálogo</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="main-wrapper">
        
        <?php include 'includes/header.php'; ?>

        <h1 style="margin-top: 40px; color: #66fcf1 ;">Bem-vindo ao DESKGAME!</h1>
        <p style="color: #c5c6c7; font-size: 18px; margin-top: 20px;">
            Explore nosso catálogo de máquinas gamer, notebooks e PCs personalizados. Encontre o setup perfeito para suas necessidades e leve sua experiência de jogo para o próximo nível!
        </p>

        <h2 style="border-bottom: 1px solid #1f2833; padding-bottom: 10px; margin-bottom: 20px; color: #fff;">
            Nossas Máquinas
        </h2>

        <div class="products-grid">
            
            <div class="pc-card">
                <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=400&q=80" alt="PC Gamer Ryzen Ultimate">
                <h3>PC Gamer Ryzen Ultimate</h3>
                <div class="pc-specs">
                    🔹 <strong>CPU:</strong> AMD Ryzen 7 5700X<br>
                    🔹 <strong>GPU:</strong> RTX 4060 Ti<br>
                    🔹 <strong>RAM:</strong> 16GB DDR4
                </div>
                <div class="pc-price">R$ 5.499,00</div>
            </div>

            <div class="pc-card">
                <img src="https://images.unsplash.com/photo-1547082299-de196ea013d6?auto=format&fit=crop&w=400&q=80" alt="PC Gamer Intel Core Force">
                <h3>PC Gamer Intel Core Force</h3>
                <div class="pc-specs">
                    🔹 <strong>CPU:</strong> Intel Core i5-13400F<br>
                    🔹 <strong>GPU:</strong> RTX 3060<br>
                    🔹 <strong>RAM:</strong> 16GB DDR4
                </div>
                <div class="pc-price">R$ 4.299,00</div>
            </div>

            <div class="pc-card">
                <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=400&q=80" alt="PC Gamer Streamer Elite">
                <h3>PC Gamer Streamer Elite</h3>
                <div class="pc-specs">
                    🔹 <strong>CPU:</strong> Intel Core i7-14700K<br>
                    🔹 <strong>GPU:</strong> RTX 4070 Super<br>
                    🔹 <strong>RAM:</strong> 32GB DDR5
                </div>
                <div class="pc-price">R$ 8.999,00</div>
            </div>

        </div>

        <?php include 'includes/footer.php'; ?>

    </div>

</body>
</html>