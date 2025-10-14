<?php
include '../includes/config.php';
$profissionais = $conn->query("SELECT nome, funcao, foto_url, descricao FROM profissionais");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nossos Profissionais | Bookstyle Salão</title>
    <style>
        /* CSS Básico reutilizado da Home */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-color: #f8f8f8; color: #333; }
        header { background-color: #DDA0DD; color: white; padding: 15px 50px; text-align: center;}
        .container { width: 80%; max-width: 1100px; margin: 40px auto; }
        .section-title { text-align: center; padding: 20px 0; color: #C71585; border-bottom: 1px solid #eee; margin-bottom: 40px; }
        .cards-grid { display: flex; flex-wrap: wrap; gap: 30px; justify-content: center; }
        .card-atendente { background-color: #ffffff; width: 300px; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
        .card-atendente img { width: 100%; height: 250px; object-fit: cover; border-radius: 5px; margin-bottom: 15px; }
        .card-atendente h3 { color: #DDA0DD; margin-top: 0; }
        .card-atendente p.funcao { font-weight: bold; color: #555; margin-top: -10px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <header>
        <h1>Equipe Bookstyle</h1>
        <p><a href="../index.php" style="color: white; text-decoration: none;">&larr; Voltar para Home</a></p>
    </header>

    <div class="container">
        <h2 class="section-title">Nossos Talentos</h2>
        
        <div class="cards-grid">
            <?php while ($pro = $profissionais->fetch_assoc()): ?>
            <div class="card-atendente">
                <img src="../assets/img/<?php echo $pro['foto_url']; ?>" alt="Foto de <?php echo $pro['nome']; ?>">
                <h3><?php echo $pro['nome']; ?></h3>
                <p class="funcao"><?php echo $pro['funcao']; ?></p>
                <p><?php echo $pro['descricao']; ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>