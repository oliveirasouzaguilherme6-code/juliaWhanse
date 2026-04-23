<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include 'conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM imoveis WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows !== 1) {
    die("Imóvel não encontrado.");
}

$imovel = $resultado->fetch_assoc();

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

    $sqlUpdate = "UPDATE imoveis SET
        titulo = ?, slug = ?, tipo = ?, finalidade = ?, bairro = ?, cidade = ?, estado = ?, endereco = ?,
        valor = ?, quartos = ?, banheiros = ?, vagas = ?, area = ?, destaque = ?, status = ?,
        descricao_curta = ?, descricao_longa = ?, capa = ?
        WHERE id = ?";

    $stmtUpdate = $conn->prepare($sqlUpdate);

    if ($stmtUpdate) {
        $stmtUpdate->bind_param(
            "ssssssssdiiidissssi",
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
            $capa,
            $id
        );

        if ($stmtUpdate->execute()) {
            header("Location: imoveis_admin.php");
            exit;
        } else {
            $erro = "Erro ao atualizar imóvel: " . $stmtUpdate->error;
        }
    } else {
        $erro = "Erro ao preparar atualização: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar imóvel</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .edit-wrap {
      padding: 48px 0 80px;
      background: #f6f1e8;
      min-height: 100vh;
    }
    .edit-box {
      max-width: 1000px;
      margin: 0 auto;
      background: rgba(255,255,255,0.82);
      border: 1px solid rgba(23,23,23,0.06);
      border-radius: 24px;
      padding: 24px;
      box-shadow: var(--shadow);
    }
    .edit-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }
    .full {
      grid-column: 1 / -1;
    }
    @media (max-width: 900px) {
      .edit-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <main class="edit-wrap">
    <div class="edit-box">
      <h1 class="section-title" style="margin-bottom:18px;">Editar imóvel</h1>

      <?php if (!empty($erro)): ?>
        <p style="color:#a33; font-weight:600; margin-bottom:12px;"><?php echo $erro; ?></p>
      <?php endif; ?>

      <form method="POST" class="edit-grid">
        <div class="form-field">
          <label>Título</label>
          <input type="text" name="titulo" value="<?php echo htmlspecialchars($imovel['titulo']); ?>" required>
        </div>

        <div class="form-field">
          <label>Slug</label>
          <input type="text" name="slug" value="<?php echo htmlspecialchars($imovel['slug']); ?>" required>
        </div>

        <div class="form-field">
          <label>Tipo</label>
          <select name="tipo" required>
            <option value="casa" <?php echo $imovel['tipo'] === 'casa' ? 'selected' : ''; ?>>Casa</option>
            <option value="apartamento" <?php echo $imovel['tipo'] === 'apartamento' ? 'selected' : ''; ?>>Apartamento</option>
            <option value="sobrado" <?php echo $imovel['tipo'] === 'sobrado' ? 'selected' : ''; ?>>Sobrado</option>
            <option value="comercial" <?php echo $imovel['tipo'] === 'comercial' ? 'selected' : ''; ?>>Comercial</option>
            <option value="outro" <?php echo $imovel['tipo'] === 'outro' ? 'selected' : ''; ?>>Outro</option>
          </select>
        </div>

        <div class="form-field">
          <label>Finalidade</label>
          <select name="finalidade" required>
            <option value="venda" <?php echo $imovel['finalidade'] === 'venda' ? 'selected' : ''; ?>>Venda</option>
            <option value="aluguel" <?php echo $imovel['finalidade'] === 'aluguel' ? 'selected' : ''; ?>>Aluguel</option>
          </select>
        </div>

        <div class="form-field">
          <label>Bairro</label>
          <input type="text" name="bairro" value="<?php echo htmlspecialchars($imovel['bairro']); ?>" required>
        </div>

        <div class="form-field">
          <label>Cidade</label>
          <input type="text" name="cidade" value="<?php echo htmlspecialchars($imovel['cidade']); ?>">
        </div>

        <div class="form-field">
          <label>Estado</label>
          <input type="text" name="estado" value="<?php echo htmlspecialchars($imovel['estado']); ?>">
        </div>

        <div class="form-field">
          <label>Endereço</label>
          <input type="text" name="endereco" value="<?php echo htmlspecialchars($imovel['endereco']); ?>">
        </div>

        <div class="form-field">
          <label>Valor</label>
          <input type="number" step="0.01" name="valor" value="<?php echo htmlspecialchars($imovel['valor']); ?>">
        </div>

        <div class="form-field">
          <label>Quartos</label>
          <input type="number" name="quartos" value="<?php echo htmlspecialchars($imovel['quartos']); ?>">
        </div>

        <div class="form-field">
          <label>Banheiros</label>
          <input type="number" name="banheiros" value="<?php echo htmlspecialchars($imovel['banheiros']); ?>">
        </div>

        <div class="form-field">
          <label>Vagas</label>
          <input type="number" name="vagas" value="<?php echo htmlspecialchars($imovel['vagas']); ?>">
        </div>

        <div class="form-field">
          <label>Área</label>
          <input type="number" step="0.01" name="area" value="<?php echo htmlspecialchars($imovel['area']); ?>">
        </div>

        <div class="form-field">
          <label>Status</label>
          <select name="status">
            <option value="disponivel" <?php echo $imovel['status'] === 'disponivel' ? 'selected' : ''; ?>>Disponível</option>
            <option value="vendido" <?php echo $imovel['status'] === 'vendido' ? 'selected' : ''; ?>>Vendido</option>
            <option value="alugado" <?php echo $imovel['status'] === 'alugado' ? 'selected' : ''; ?>>Alugado</option>
            <option value="oculto" <?php echo $imovel['status'] === 'oculto' ? 'selected' : ''; ?>>Oculto</option>
          </select>
        </div>

        <div class="form-field full">
          <label>URL da capa</label>
          <input type="text" name="capa" value="<?php echo htmlspecialchars($imovel['capa']); ?>">
        </div>

        <div class="form-field full">
          <label>Descrição curta</label>
          <input type="text" name="descricao_curta" value="<?php echo htmlspecialchars($imovel['descricao_curta']); ?>">
        </div>

        <div class="form-field full">
          <label>Descrição longa</label>
          <textarea name="descricao_longa"><?php echo htmlspecialchars($imovel['descricao_longa']); ?></textarea>
        </div>

        <div class="form-field full" style="display:flex; align-items:center; gap:10px;">
          <input type="checkbox" name="destaque" id="destaque" style="min-height:auto; width:auto;" <?php echo $imovel['destaque'] ? 'checked' : ''; ?>>
          <label for="destaque">Marcar como destaque</label>
        </div>

        <div class="full">
          <button type="submit" class="btn btn-gold">Salvar alterações</button>
        </div>
      </form>
    </div>
  </main>
</body>
</html>