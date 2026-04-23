<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contato | Julia W. Hanse Imóveis</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
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
        <a href="imoveis.html">Imóveis</a>
        <a href="sobre.html">Sobre</a>
        <a href="contato.php" class="active">Contato</a>
      </nav>

      <div class="header-actions">
        <a href="contato.php" class="btn btn-gold">Fale comigo</a>
      </div>
    </div>
  </header>

  <main>
    <section class="page-hero page-hero-contact">
      <div class="container reveal show">
        <span class="eyebrow">Contato</span>
        <h1>Vamos conversar com mais proximidade e clareza.</h1>
        <p>Atendimento direto e uma experiência pensada para facilitar o primeiro contato.</p>
      </div>
    </section>

    <section class="section soft-section">
      <div class="container contact-grid">
        <div class="contact-info reveal show">
          <h2>Informações</h2>

          <div class="info-box">
            <strong>Campo Mourão - PR</strong>
            <span>Atendimento com foco em experiência e organização.</span>
          </div>

          <div class="info-box">
            <strong>(44) 99999-9999</strong>
            <span>WhatsApp para atendimento rápido.</span>
          </div>

          <div class="info-box">
            <strong>contato@juliawhanse.com</strong>
            <span>Canal para solicitações e informações.</span>
          </div>
        </div>

        <form class="contact-form reveal show" action="cadastrar_cliente.php" method="POST">
          <div class="form-row">
            <div class="form-field">
              <label>Nome</label>
              <input type="text" name="nome" placeholder="Seu nome" required>
            </div>

            <div class="form-field">
              <label>Telefone</label>
              <input type="text" name="telefone" placeholder="(44) 99999-9999" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-field">
              <label>E-mail</label>
              <input type="email" name="email" placeholder="seuemail@email.com" required>
            </div>

            <div class="form-field">
              <label>Faixa de preço</label>
              <input type="text" name="faixa_preco" placeholder="Ex.: até R$ 400 mil">
            </div>
          </div>

          <div class="form-field">
            <label>Tipo de interesse</label>
            <select name="interesse" required>
              <option value="">Selecione</option>
              <option value="casa">Casa</option>
              <option value="apartamento">Apartamento</option>
              <option value="sobrado">Sobrado</option>
              <option value="comercial">Comercial</option>
              <option value="outro">Outro</option>
            </select>
          </div>

          <div class="form-field">
            <label>Mensagem</label>
            <textarea name="mensagem" placeholder="Me conte o que você procura"></textarea>
          </div>

          <button type="submit" class="btn btn-gold">Enviar cadastro</button>
        </form>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container footer-bottom">
      <p>© 2026 Julia W. Hanse Imóveis</p>
      <a href="login.php" class="area-link">Área da imobiliária</a>
    </div>
  </footer>
</body>
</html>