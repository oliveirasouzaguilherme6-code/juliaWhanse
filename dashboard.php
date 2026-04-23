<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'conexao.php';

$total_imoveis = 0;
$total_clientes = 0;
$total_contatos = 0;

$q1 = $conn->query("SELECT COUNT(*) AS total FROM imoveis");
if ($q1) $total_imoveis = $q1->fetch_assoc()['total'];

$q2 = $conn->query("SELECT COUNT(*) AS total FROM clientes");
if ($q2) $total_clientes = $q2->fetch_assoc()['total'];

$q3 = $conn->query("SELECT COUNT(*) AS total FROM contatos");
if ($q3) $total_contatos = $q3->fetch_assoc()['total'];

$clientes = $conn->query("SELECT * FROM clientes ORDER BY id DESC LIMIT 8");
$imoveis = $conn->query("SELECT * FROM imoveis ORDER BY id DESC LIMIT 6");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | Área da imobiliária</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .admin-wrapper { padding: 48px 0 80px; }
    .admin-topbar {
      display:flex; justify-content:space-between; align-items:center; gap:18px; flex-wrap:wrap;
      margin-bottom: 28px;
    }
    .admin-grid-cards {
      display:grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 28px;
    }
    .admin-card {
      background: rgba(255,255,255,0.75);
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 24px;
      padding: 24px;
      box-shadow: var(--shadow);
    }
    .admin-card span {
      display:block;
      color:#7c6a49;
      margin-bottom:10px;
      font-weight:600;
    }
    .admin-card strong {
      font-size: 2rem;
    }
    .admin-sections {
      display:grid;
      grid-template-columns: 1.2fr .8fr;
      gap: 24px;
    }
    .admin-panel {
      background: rgba(255,255,255,0.78);
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 24px;
      padding: 24px;
      box-shadow: var(--shadow);
    }
    .admin-panel h3 {
      margin-bottom: 18px;
      font-size: 1.2rem;
    }
    .admin-table {
      width:100%;
      border-collapse: collapse;
    }
    .admin-table th,
    .admin-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      font-size: .95rem;
    }
    .admin-table th {
      background: #efe2c7;
    }
    .admin-list {
      display:grid;
      gap: 14px;
    }
    .admin-list-item {
      padding: 16px;
      border-radius: 18px;
      background: #f8f2e7;
      border: 1px solid rgba(23,23,23,0.05);
    }
    .admin-list-item strong {
      display:block;
      margin-bottom:6px;
    }
    .admin-nav {
      display:flex;
      gap:12px;
      flex-wrap:wrap;
      
    }
    @media (max-width: 920px) {
      .admin-grid-cards,
      .admin-sections {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body style="background:#f6f1e8;">
  <header class="site-header scrolled">
    <div class="container header-inner">
      <a href="dashboard.php" class="brand">
        <span class="brand-title">Área da</span>
        <span class="brand-subtitle">Imobiliária</span>
      </a>

      <div class="header-actions">
        <span style="color:#fff;">Olá, <?php echo htmlspecialchars($_SESSION['admin']); ?></span>
        <a href="logout.php" class="btn btn-gold">Sair</a>
      </div>
    </div>
  </header>

  <main class="admin-wrapper">
    <div class="container">
      <div class="admin-topbar">
        <div>
          <span class="eyebrow" style="color:#7d6120;border-color:rgba(125,97,32,.18);">Painel interno</span>
          <h1 class="section-title">Visão geral da imobiliária</h1>
        </div>

        <div class="admin-nav">
          <div class="admin-nav">
  <a href="dashboard.php" class="btn btn-dark">Dashboard</a>
  <a href="imoveis_admin.php" class="btn btn-dark">Gerenciar imóveis</a>
  <a href="clientes_admin.php" class="btn btn-gold">Clientes</a>
</div>
        </div>
      </div>

      <section class="admin-grid-cards">
        <article class="admin-card">
          <span>Total de imóveis</span>
          <strong><?php echo $total_imoveis; ?></strong>
        </article>

        <article class="admin-card">
          <span>Total de clientes</span>
          <strong><?php echo $total_clientes; ?></strong>
        </article>

        <article class="admin-card">
          <span>Total de contatos</span>
          <strong><?php echo $total_contatos; ?></strong>
        </article>
      </section>

      <section class="admin-sections">
        <div class="admin-panel">
          <h3>Últimos clientes cadastrados</h3>

          <div style="overflow:auto;">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Telefone</th>
                  <th>E-mail</th>
                  <th>Interesse</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($clientes && $clientes->num_rows > 0): ?>
                  <?php while($cliente = $clientes->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                      <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                      <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                      <td><?php echo htmlspecialchars($cliente['interesse']); ?></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4">Nenhum cliente cadastrado ainda.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="admin-panel">
          <h3>Últimos imóveis</h3>

          <div class="admin-list">
            <?php if ($imoveis && $imoveis->num_rows > 0): ?>
              <?php while($imovel = $imoveis->fetch_assoc()): ?>
                <div class="admin-list-item">
                  <strong><?php echo htmlspecialchars($imovel['titulo']); ?></strong>
                  <span><?php echo htmlspecialchars($imovel['tipo']); ?> • <?php echo htmlspecialchars($imovel['bairro']); ?></span>
                  <small>R$ <?php echo number_format($imovel['valor'], 2, ',', '.'); ?></small>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="admin-list-item">
                <strong>Nenhum imóvel cadastrado</strong>
                <span>Cadastre o primeiro no painel de imóveis.</span>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </section>
    </div>
  </main>
</body>
</html>