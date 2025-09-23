# Registro de Alterações

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/), e este projeto adere ao [Versionamento Semântico](https://semver.org/lang/pt-BR/).

---

# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) e este projeto adere ao [Versionamento Semântico](https://semver.org/spec/v2.0.0.html).

---

## [1.2.3] - 2025-09-23

### Adicionado

-   **Aprimoramentos na validação do odômetro:**
    -   Implementadas duas novas etapas de verificação para valores atipicamente altos.
    -   Adicionado um alerta visual para o usuário caso o valor inserido exceda o limite.
-   Melhorada a exibição de mensagens de erro, tornando-as mais claras e fáceis de identificar.
-   Valores numéricos em mensagens de erro agora são exibidos no formato brasileiro.

### Corrigido

-   Prevenção de registro de odômetro com valor excessivo, que agora é validado corretamente antes de ser salvo.

---

## [1.2.2] - 2025-09-19

### Adicionado

-   Adicionado um novo modelo de veículo às visualizações de tabelas do sistema.

---

## [1.2.1] - 2025-09-19

### Corrigido

-   Ajustada a exibição da versão do sistema no rodapé das páginas.

---

## [1.2.0] - 2025-09-18

### Adicionado

-   Exibição da versão atual do sistema no rodapé de todas as páginas.

### Corrigido

-   Corrigida a mensagem de validação no campo "estimativa de retorno".

---

## [1.1.1] - 2025-09-17

### Alterado

-   Adicionada a pasta `public/build` ao `.gitignore` para não ser incluída no versionamento.

### Corrigido

-   Corrigido erro que impedia o salvamento do registro de quilometragem do veículo no banco de dados.

---

## [1.1.0] - 2025-09-16

### Adicionado

-   Implementados novos avisos de validação e proteções de entrada nos formulários.
-   Adicionado o botão "Voltar à Página Inicial" na tela de registro de retorno.

---

## [1.0.0] - 2025-09-11

### Adicionado

-   Lançamento inicial do projeto em ambiente de produção.

---

## [Não lançado]

### Adicionado (Added)

-

### Alterado (Changed)

-

### Obsoleto (Deprecated)

-

### Removido (Removed)

-

### Corrigido (Fixed)

-

### Segurança (Security)

-
