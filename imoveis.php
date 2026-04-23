<?php
include 'conexao.php';

$tipo = trim($_GET['tipo'] ?? '');
$bairro = trim($_GET['bairro'] ?? '');
$valor = trim($_GET['valor'] ?? '');

$sql = "SELECT * FROM imoveis WHERE status != 'oculto'";
$tipos = [];
$valores = [];

if (!empty($tipo)) {
    $sql .= " AND tipo = ?";
    $tipos[] = "s";
    $valores[] = $tipo;
}

if (!empty($bairro)) {
    $sql .= " AND bairro LIKE ?";
    $tipos[] = "s";
    $valores[] = "%" . $bairro . "%";
}

if (!empty($valor) && is_numeric($valor)) {
    $sql .= " AND valor <= ?";
    $tipos[] = "d";
    $valores[] = (float)$valor;
}

$sql .= " ORDER BY destaque DESC, id DESC";

$stmt = $conn->prepare($sql);

if (!empty($valores)) {
    $stmt->bind_param(implode("", $tipos), ...$valores);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Imóveis | Julia W. Hanse Imóveis</title>
  <meta name="description" content="Veja casas, apartamentos e oportunidades com um catálogo refinado e organizado." />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>
  <div class="cursor-glow"></div>

  <header class="site-header" id="siteHeader">
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
        <button class="menu-btn" id="menuBtn" aria-label="Abrir menu">
          <span></span><span></span>
        </button>
      </div>
    </div>

    <div class="mobile-menu" id="mobileMenu">
      <a href="index.html">Início</a>
      <a href="imoveis.php">Imóveis</a>
      <a href="sobre.html">Sobre</a>
      <a href="contato.php">Contato</a>
    </div>
  </header>

  <main>
    <section class="page-hero page-hero-imoveis">
      <div class="container reveal show">
        <span class="eyebrow">Catálogo</span>
        <h1>Casas, apartamentos e oportunidades</h1>
        <p>Uma navegação mais clara, com filtros e visual mais sofisticado para cada imóvel.</p>
      </div>
    </section>

    <section class="section soft-section">
      <div class="container">
        <form class="filter-bar reveal show" method="GET" action="imoveis.php">
          <div class="filter-field">
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo">
              <option value="">Todos</option>
              <option value="casa" <?php echo $tipo === 'casa' ? 'selected' : ''; ?>>Casa</option>
              <option value="apartamento" <?php echo $tipo === 'apartamento' ? 'selected' : ''; ?>>Apartamento</option>
              <option value="sobrado" <?php echo $tipo === 'sobrado' ? 'selected' : ''; ?>>Sobrado</option>
              <option value="comercial" <?php echo $tipo === 'comercial' ? 'selected' : ''; ?>>Comercial</option>
              <option value="outro" <?php echo $tipo === 'outro' ? 'selected' : ''; ?>>Outro</option>
            </select>
          </div>

          <div class="filter-field">
            <label for="bairro">Bairro</label>
            <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>" placeholder="Ex.: Centro" />
          </div>

          <div class="filter-field">
            <label for="valor">Valor máximo</label>
            <input type="number" id="valor" name="valor" value="<?php echo htmlspecialchars($valor); ?>" placeholder="500000" />
          </div>

          <button class="btn btn-gold filter-btn" type="submit">Filtrar</button>
        </form>

        <div class="catalog-grid">
          <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while($imovel = $resultado->fetch_assoc()): ?>
              <article class="catalog-card reveal show">
                <div class="catalog-image" style="background-image: url('<?php echo !empty($imovel['capa']) ? htmlspecialchars($imovel['capa']) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80'; ?>')">
                  <span class="catalog-badge"><?php echo htmlspecialchars($imovel['tipo']); ?></span>
                </div>

                <div class="catalog-body">
                  <h3><?php echo htmlspecialchars($imovel['titulo']); ?></h3>
                  <p><?php echo htmlspecialchars($imovel['bairro']); ?> • <?php echo htmlspecialchars($imovel['cidade']); ?></p>

                  <div class="catalog-meta">
                    <span><?php echo (int)$imovel['quartos']; ?> quartos</span>
                    <span><?php echo (int)$imovel['banheiros']; ?> banheiros</span>
                    <span><?php echo (int)$imovel['vagas']; ?> vagas</span>
                    <span><?php echo $imovel['area'] ? number_format((float)$imovel['area'], 0, ',', '.') : 0; ?> m²</span>
                  </div>

                  <div class="catalog-price">
                    R$ <?php echo number_format((float)$imovel['valor'], 2, ',', '.'); ?>
                  </div>

                  <div class="catalog-actions">
                    <a href="imovel.php?id=<?php echo $imovel['id']; ?>" class="small-btn small-btn-dark">Ver imóvel</a>
                    <a href="contato.php" class="small-btn small-btn-light">Contato</a>
                  </div>
                </div>
              </article>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="empty-box">
              Nenhum imóvel encontrado com esses filtros.
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-bottom">
      <p>© 2026 Julia W. Hanse Imóveis</p>
      <a href="login.php" class="area-link">Área da imobiliária</a>
    </div>
  </footer>

  <script src="js/main.js"></script>
</body>
</html>