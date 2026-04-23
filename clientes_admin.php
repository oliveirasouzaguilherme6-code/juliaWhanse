<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cliente_id"], $_POST["novo_status"])) {
    $cliente_id = (int) $_POST["cliente_id"];
    $novo_status = trim($_POST["novo_status"]);

    $status_validos = ['novo', 'em_atendimento', 'convertido', 'arquivado'];

    if (in_array($novo_status, $status_validos, true)) {
        $sql_update = "UPDATE clientes SET status = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $novo_status, $cliente_id);
        $stmt_update->execute();
    }

    header("Location: clientes_admin.php");
    exit;
}

$filtro = trim($_GET["status"] ?? '');

if ($filtro && in_array($filtro, ['novo', 'em_atendimento', 'convertido', 'arquivado'], true)) {
    $sql = "SELECT * FROM clientes WHERE status = ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $filtro);
    $stmt->execute();
    $clientes = $stmt->get_result();
} else {
    $clientes = $conn->query("SELECT * FROM clientes ORDER BY id DESC");
}

$total_clientes = $conn->query("SELECT COUNT(*) AS total FROM clientes")->fetch_assoc()["total"];
$total_novos = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE status = 'novo'")->fetch_assoc()["total"];
$total_atendimento = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE status = 'em_atendimento'")->fetch_assoc()["total"];
$total_convertidos = $conn->query("SELECT COUNT(*) AS total FROM clientes WHERE status = 'convertido'")->fetch_assoc()["total"];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes | Área da imobiliária</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .admin-wrapper {
      padding: 48px 0 80px;
    }

    .admin-topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 18px;
      flex-wrap: wrap;
      margin-bottom: 28px;
    }

    .admin-nav {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
    }

    .admin-cards {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 18px;
      margin-bottom: 24px;
    }

    .admin-card {
      background: rgba(255,255,255,0.8);
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 22px;
      padding: 22px;
      box-shadow: var(--shadow);
    }

    .admin-card span {
      display: block;
      color: #7c6a49;
      margin-bottom: 10px;
      font-weight: 600;
      font-size: .92rem;
    }

    .admin-card strong {
      font-size: 1.8rem;
    }

    .admin-panel {
      background: rgba(255,255,255,0.82);
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 24px;
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .filter-row {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 18px;
    }

    .filter-chip {
      padding: 10px 14px;
      border-radius: 999px;
      background: #f1e6d0;
      color: #5f4e30;
      font-weight: 600;
      font-size: .9rem;
      transition: .3s ease;
    }

    .filter-chip:hover,
    .filter-chip.active {
      background: #dbc292;
      color: #2a2113;
    }

    .clientes-grid {
      display: grid;
      gap: 16px;
    }

    .cliente-card {
      background: #fff;
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 20px;
      padding: 20px;
      display: grid;
      grid-template-columns: 1.15fr .85fr;
      gap: 18px;
      align-items: start;
    }

    .cliente-main h3 {
      margin-bottom: 8px;
      font-size: 1.15rem;
    }

    .cliente-meta {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin: 10px 0 12px;
    }

    .cliente-meta span {
      padding: 7px 10px;
      background: #f4ecde;
      border-radius: 10px;
      font-size: .85rem;
      color: #554c3f;
    }

    .cliente-main p {
      color: #5d5851;
      line-height: 1.7;
    }

    .cliente-side {
      background: #fbf7f0;
      border: 1px solid rgba(23,23,23,0.05);
      border-radius: 16px;
      padding: 16px;
    }

    .cliente-side small {
      display: block;
      color: #7b7368;
      margin-bottom: 12px;
    }

    .status-badge {
      display: inline-flex;
      padding: 8px 12px;
      border-radius: 999px;
      font-size: .82rem;
      font-weight: 700;
      margin-bottom: 12px;
    }

    .status-novo { background: #e8f1ff; color: #2f5fa8; }
    .status-em_atendimento { background: #fff3dc; color: #9b6a00; }
    .status-convertido { background: #e5f7ea; color: #237144; }
    .status-arquivado { background: #ececec; color: #555; }

    .status-form {
      display: grid;
      gap: 10px;
    }

    .status-form select {
      min-height: 48px;
      border-radius: 14px;
      border: 1px solid rgba(23,23,23,0.08);
      padding: 0 12px;
      background: white;
    }

    .empty-box {
      padding: 28px;
      text-align: center;
      border: 2px dashed #d7c5a0;
      border-radius: 20px;
      color: #6a6257;
      background: #fffdf9;
    }

    @media (max-width: 1024px) {
      .admin-cards {
        grid-template-columns: repeat(2, 1fr);
      }

      .cliente-card {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 640px) {
      .admin-cards {
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
        <a href="dashboard.php" class="btn btn-dark">Dashboard</a>
        <a href="imoveis_admin.php" class="btn btn-dark">Imóveis</a>
        <a href="logout.php" class="btn btn-gold">Sair</a>
      </div>
    </div>
  </header>

  <main class="admin-wrapper">
    <div class="container">
      <div class="admin-topbar">
        <div>
          <span class="eyebrow" style="color:#7d6120;border-color:rgba(125,97,32,.18);">Relacionamento</span>
          <h1 class="section-title">Painel de clientes</h1>
        </div>

        <div class="admin-nav">
          <a href="clientes_admin.php" class="btn btn-gold">Atualizar lista</a>
        </div>
      </div>

      <section class="admin-cards">
        <article class="admin-card">
          <span>Total de clientes</span>
          <strong><?php echo $total_clientes; ?></strong>
        </article>

        <article class="admin-card">
          <span>Novos</span>
          <strong><?php echo $total_novos; ?></strong>
        </article>

        <article class="admin-card">
          <span>Em atendimento</span>
          <strong><?php echo $total_atendimento; ?></strong>
        </article>

        <article class="admin-card">
          <span>Convertidos</span>
          <strong><?php echo $total_convertidos; ?></strong>
        </article>
      </section>

      <section class="admin-panel">
        <div class="filter-row">
          <a href="clientes_admin.php" class="filter-chip <?php echo $filtro === '' ? 'active' : ''; ?>">Todos</a>
          <a href="clientes_admin.php?status=novo" class="filter-chip <?php echo $filtro === 'novo' ? 'active' : ''; ?>">Novos</a>
          <a href="clientes_admin.php?status=em_atendimento" class="filter-chip <?php echo $filtro === 'em_atendimento' ? 'active' : ''; ?>">Em atendimento</a>
          <a href="clientes_admin.php?status=convertido" class="filter-chip <?php echo $filtro === 'convertido' ? 'active' : ''; ?>">Convertidos</a>
          <a href="clientes_admin.php?status=arquivado" class="filter-chip <?php echo $filtro === 'arquivado' ? 'active' : ''; ?>">Arquivados</a>
        </div>

        <div class="clientes-grid">
          <?php if ($clientes && $clientes->num_rows > 0): ?>
            <?php while($cliente = $clientes->fetch_assoc()): ?>
              <article class="cliente-card">
                <div class="cliente-main">
                  <h3><?php echo htmlspecialchars($cliente["nome"]); ?></h3>

                  <div class="cliente-meta">
                    <span><?php echo htmlspecialchars($cliente["telefone"]); ?></span>
                    <span><?php echo htmlspecialchars($cliente["email"]); ?></span>
                    <span><?php echo htmlspecialchars($cliente["interesse"]); ?></span>
                    <?php if (!empty($cliente["faixa_preco"])): ?>
                      <span><?php echo htmlspecialchars($cliente["faixa_preco"]); ?></span>
                    <?php endif; ?>
                  </div>

                  <p>
                    <?php echo !empty($cliente["mensagem"]) ? nl2br(htmlspecialchars($cliente["mensagem"])) : 'Sem mensagem enviada.'; ?>
                  </p>
                </div>

                <div class="cliente-side">
                  <div class="status-badge status-<?php echo htmlspecialchars($cliente["status"]); ?>">
                    <?php echo htmlspecialchars($cliente["status"]); ?>
                  </div>

                  <small>Cadastrado em: <?php echo htmlspecialchars($cliente["criado_em"]); ?></small>

                  <form method="POST" class="status-form">
                    <input type="hidden" name="cliente_id" value="<?php echo $cliente["id"]; ?>">

                    <select name="novo_status" required>
                      <option value="novo" <?php echo $cliente["status"] === "novo" ? "selected" : ""; ?>>Novo</option>
                      <option value="em_atendimento" <?php echo $cliente["status"] === "em_atendimento" ? "selected" : ""; ?>>Em atendimento</option>
                      <option value="convertido" <?php echo $cliente["status"] === "convertido" ? "selected" : ""; ?>>Convertido</option>
                      <option value="arquivado" <?php echo $cliente["status"] === "arquivado" ? "selected" : ""; ?>>Arquivado</option>
                    </select>

                    <button type="submit" class="btn btn-gold">Salvar status</button>
                  </form>
                </div>
              </article>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="empty-box">
              Nenhum cliente encontrado nesse filtro.
            </div>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </main>
</body>
</html>