<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST['titulo'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $finalidade = trim($_POST['finalidade'] ?? '');
    $bairro = trim($_POST['bairro'] ?? '');
    $cidade = trim($_POST['cidade'] ?? 'Campo Mourão');
    $estado = trim($_POST['estado'] ?? 'PR');
    $endereco = trim($_POST['endereco'] ?? '');
    $valor = (float)($_POST['valor'] ?? 0);
    $quartos = (int)($_POST['quartos'] ?? 0);
    $banheiros = (int)($_POST['banheiros'] ?? 0);
    $vagas = (int)($_POST['vagas'] ?? 0);
    $area = (float)($_POST['area'] ?? 0);
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    $status = trim($_POST['status'] ?? 'disponivel');
    $descricao_curta = trim($_POST['descricao_curta'] ?? '');
    $descricao_longa = trim($_POST['descricao_longa'] ?? '');
    $capa = trim($_POST['capa'] ?? '');

    if (!empty($titulo) && !empty($slug) && !empty($tipo) && !empty($bairro)) {
        $sql = "INSERT INTO imoveis
        (titulo, slug, tipo, finalidade, bairro, cidade, estado, endereco, valor, quartos, banheiros, vagas, area, destaque, status, descricao_curta, descricao_longa, capa, criado_em)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "ssssssssdiiidissss",
                $titulo,
                $slug,
                $tipo,
                $finalidade,
                $bairro,
                $cidade,
                $estado,
                $endereco,
                $valor,
                $quartos,
                $banheiros,
                $vagas,
                $area,
                $destaque,
                $status,
                $descricao_curta,
                $descricao_longa,
                $capa
            );

            if ($stmt->execute()) {
                $sucesso = "Imóvel cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar imóvel: " . $stmt->error;
            }
        } else {
            $erro = "Erro ao preparar cadastro: " . $conn->error;
        }
    } else {
        $erro = "Preencha os campos obrigatórios.";
    }
}

$lista = $conn->query("SELECT * FROM imoveis ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gerenciar imóveis</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .admin-form-wrap {
      display: grid;
      grid-template-columns: 1.05fr .95fr;
      gap: 24px;
      padding: 48px 0 80px;
    }

    .admin-panel-box {
      background: rgba(255,255,255,0.78);
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 24px;
      padding: 24px;
      box-shadow: var(--shadow);
    }

    .admin-form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    .full {
      grid-column: 1 / -1;
    }

    .admin-table {
      width: 100%;
      border-collapse: collapse;
    }

    .admin-table th,
    .admin-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid rgba(0,0,0,0.06);
      font-size: .94rem;
      vertical-align: top;
    }

    .admin-table th {
      background: #efe2c7;
    }

    .action-buttons {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .mini-btn {
      min-height: 38px;
      padding: 0 14px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-weight: 600;
      transition: .3s ease;
      border: none;
      cursor: pointer;
    }

    .mini-btn-dark {
      background: #171717;
      color: #fff;
    }

    .mini-btn-dark:hover {
      background: #2a2a2a;
      transform: translateY(-1px);
    }

    .mini-btn-gold {
      background: linear-gradient(135deg, var(--gold), var(--gold-light));
      color: #161616;
    }

    .mini-btn-gold:hover {
      transform: translateY(-1px);
    }

    @media (max-width: 980px) {
      .admin-form-wrap,
      .admin-form-grid {
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
        <a href="clientes_admin.php" class="btn btn-dark">Clientes</a>
        <a href="logout.php" class="btn btn-gold">Sair</a>
      </div>
    </div>
  </header>

  <main>
    <div class="container admin-form-wrap">
      <section class="admin-panel-box">
        <span class="eyebrow" style="color:#7d6120;border-color:rgba(125,97,32,.18);">Cadastro</span>
        <h1 class="section-title" style="margin-bottom:18px;">Novo imóvel</h1>

        <?php if (!empty($sucesso)): ?>
          <p style="color: green; font-weight: 600; margin-bottom: 12px;"><?php echo $sucesso; ?></p>
        <?php endif; ?>

        <?php if (!empty($erro)): ?>
          <p style="color: #a33; font-weight: 600; margin-bottom: 12px;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" class="admin-form-grid">
          <div class="form-field">
            <label>Título</label>
            <input type="text" name="titulo" required>
          </div>

          <div class="form-field">
            <label>Slug</label>
            <input type="text" name="slug" required>
          </div>

          <div class="form-field">
            <label>Tipo</label>
            <select name="tipo" required>
              <option value="casa">Casa</option>
              <option value="apartamento">Apartamento</option>
              <option value="sobrado">Sobrado</option>
              <option value="comercial">Comercial</option>
              <option value="outro">Outro</option>
            </select>
          </div>

          <div class="form-field">
            <label>Finalidade</label>
            <select name="finalidade" required>
              <option value="venda">Venda</option>
              <option value="aluguel">Aluguel</option>
            </select>
          </div>

          <div class="form-field">
            <label>Bairro</label>
            <input type="text" name="bairro" required>
          </div>

          <div class="form-field">
            <label>Cidade</label>
            <input type="text" name="cidade" value="Campo Mourão">
          </div>

          <div class="form-field">
            <label>Estado</label>
            <input type="text" name="estado" value="PR">
          </div>

          <div class="form-field">
            <label>Endereço</label>
            <input type="text" name="endereco">
          </div>

          <div class="form-field">
            <label>Valor</label>
            <input type="number" step="0.01" name="valor">
          </div>

          <div class="form-field">
            <label>Quartos</label>
            <input type="number" name="quartos" value="0">
          </div>

          <div class="form-field">
            <label>Banheiros</label>
            <input type="number" name="banheiros" value="0">
          </div>

          <div class="form-field">
            <label>Vagas</label>
            <input type="number" name="vagas" value="0">
          </div>

          <div class="form-field">
            <label>Área</label>
            <input type="number" step="0.01" name="area">
          </div>

          <div class="form-field">
            <label>Status</label>
            <select name="status">
              <option value="disponivel">Disponível</option>
              <option value="vendido">Vendido</option>
              <option value="alugado">Alugado</option>
              <option value="oculto">Oculto</option>
            </select>
          </div>

          <div class="form-field full">
            <label>URL da capa</label>
            <input type="text" name="capa" placeholder="https://...">
          </div>

          <div class="form-field full">
            <label>Descrição curta</label>
            <input type="text" name="descricao_curta">
          </div>

          <div class="form-field full">
            <label>Descrição longa</label>
            <textarea name="descricao_longa"></textarea>
          </div>

          <div class="form-field full" style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" name="destaque" id="destaque" style="min-height:auto; width:auto;">
            <label for="destaque">Marcar como destaque</label>
          </div>

          <div class="full">
            <button type="submit" class="btn btn-gold">Salvar imóvel</button>
          </div>
        </form>
      </section>

      <section class="admin-panel-box">
        <h2 style="margin-bottom:18px;">Imóveis cadastrados</h2>

        <div style="overflow:auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Título</th>
                <th>Tipo</th>
                <th>Bairro</th>
                <th>Valor</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($lista && $lista->num_rows > 0): ?>
                <?php while($item = $lista->fetch_assoc()): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($item['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($item['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($item['bairro']); ?></td>
                    <td>R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?></td>
                    <td>
                      <div class="action-buttons">
                        <a href="editar_imovel.php?id=<?php echo $item['id']; ?>" class="mini-btn mini-btn-dark">Editar</a>
                        <a href="excluir_imovel.php?id=<?php echo $item['id']; ?>" class="mini-btn mini-btn-gold" onclick="return confirm('Tem certeza que deseja excluir este imóvel?')">Excluir</a>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5">Nenhum imóvel cadastrado.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main>
</body>
</html>