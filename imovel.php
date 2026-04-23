<?php
include 'conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM imoveis WHERE id = ? AND status != 'oculto' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    die("Imóvel não encontrado.");
}

$imovel = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($imovel['titulo']); ?> | Julia W. Hanse Imóveis</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .single-wrap {
      padding: 52px 0 90px;
      background: #f6f1e8;
      min-height: 100vh;
    }

    .single-grid {
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      gap: 28px;
      align-items: start;
    }

    .single-image {
      min-height: 520px;
      border-radius: 28px;
      background-size: cover;
      background-position: center;
      box-shadow: var(--shadow-lg);
    }

    .single-card {
      background: rgba(255,255,255,0.85);
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 24px;
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .single-card h1 {
      font-family: "Cormorant Garamond", serif;
      font-size: 3rem;
      line-height: .95;
      margin-bottom: 14px;
    }

    .single-card p {
      color: #5a554e;
      line-height: 1.8;
    }

    .single-meta {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin: 20px 0;
    }

    .single-meta div {
      background: #f4ecde;
      border-radius: 16px;
      padding: 14px;
    }

    @media (max-width: 920px) {
      .single-grid,
      .single-meta {
        grid-template-columns: 1fr;
      }

      .single-image {
        min-height: 360px;
      }
    }
  </style>
</head>
<body>
  <header class="site-header scrolled">
    <div class="container header-inner">
      <a href="index.html" class="brand">
        <span class="brand-title">Julia W. Hanse</span>
        <span class="brand-subtitle">Imóveis</span>
      </a>

      <nav class="desktop-nav">
        <a href="index.html">Início</a>
        <a href="imoveis.php" class="active">Imóveis</a>
        <a href="sobre.html">Sobre</a>
        <a href="contato.php">Contato</a>
      </nav>

      <div class="header-actions">
        <a href="contato.php" class="btn btn-gold">Fale comigo</a>
      </div>
    </div>
  </header>

  <main class="single-wrap">
    <div class="container single-grid">
      <div class="single-image" style="background-image:url('<?php echo !empty($imovel['capa']) ? htmlspecialchars($imovel['capa']) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80'; ?>')"></div>

      <div class="single-card">
        <span class="eyebrow" style="color:#7d6120;border-color:rgba(125,97,32,.18);">
          <?php echo htmlspecialchars($imovel['tipo']); ?>
        </span>

        <h1><?php echo htmlspecialchars($imovel['titulo']); ?></h1>

        <p>
          <?php echo htmlspecialchars($imovel['bairro']); ?> •
          <?php echo htmlspecialchars($imovel['cidade']); ?> -
          <?php echo htmlspecialchars($imovel['estado']); ?>
        </p>

        <div class="catalog-price" style="margin:18px 0;">
          R$ <?php echo number_format((float)$imovel['valor'], 2, ',', '.'); ?>
        </div>

        <div class="single-meta">
          <div><strong>Quartos</strong><br><?php echo (int)$imovel['quartos']; ?></div>
          <div><strong>Banheiros</strong><br><?php echo (int)$imovel['banheiros']; ?></div>
          <div><strong>Vagas</strong><br><?php echo (int)$imovel['vagas']; ?></div>
          <div><strong>Área</strong><br><?php echo $imovel['area'] ? number_format((float)$imovel['area'], 0, ',', '.') : 0; ?> m²</div>
        </div>

        <?php if (!empty($imovel['descricao_curta'])): ?>
          <p style="margin-bottom:14px;">
            <?php echo nl2br(htmlspecialchars($imovel['descricao_curta'])); ?>
          </p>
        <?php endif; ?>

        <?php if (!empty($imovel['descricao_longa'])): ?>
          <p>
            <?php echo nl2br(htmlspecialchars($imovel['descricao_longa'])); ?>
          </p>
        <?php endif; ?>

        <div style="margin-top:22px; display:flex; gap:12px; flex-wrap:wrap;">
          <a href="contato.php" class="btn btn-gold">Tenho interesse</a>
          <a href="imoveis.php" class="btn btn-dark">Voltar aos imóveis</a>
        </div>
      </div>
    </div>
  </main>
</body>
</html>