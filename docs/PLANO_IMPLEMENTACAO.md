# 📋 Plano de Implementação - APP AUTO SaaS

## 🎯 Estrutura MVC Existente

```
appauto/
├── app/
│   ├── Controllers/  ✅ Base criada
│   ├── Models/       ✅ 7 modelos prontos
│   ├── Views/        ⏳ A implementar
│   └── Routes/       ✅ Rotas definidas
├── core/             ✅ Framework MVC completo
├── config/           ✅ Configurações prontas
└── docs/             ✅ Banco de dados (12 tabelas)
```

---

## 📊 Ordem de Implementação por Módulos

### **FASE 1: AUTENTICAÇÃO E ACESSO** 🔐
**Prioridade:** CRÍTICA  
**Tempo Estimado:** 2-3 horas

#### Módulo 1.1: Sistema de Login
**Arquivos a criar:**
- `app/Views/auth/login.php` - Tela de login responsiva
- `public/assets/css/auth.css` - Estilos de autenticação
- `public/assets/js/auth.js` - Lógica de login

**Controlador:** ✅ `AuthController` (já existe)  
**Modelo:** ✅ `User` (já existe)  
**Rotas:** ✅ `/login` (já definida)

**Funcionalidades:**
- [x] Validação de email e senha
- [x] CSRF protection
- [x] Redirecionamento por role
- [ ] Interface responsiva
- [ ] Mensagens de erro

**Dependências:** Nenhuma

---

#### Módulo 1.2: Sistema de Registro
**Arquivos a criar:**
- `app/Views/auth/register.php` - Tela de cadastro
- Reutilizar CSS/JS do login

**Controlador:** ✅ `AuthController` (já existe)  
**Modelo:** ✅ `User`, `Provider` (já existem)  
**Rotas:** ✅ `/register` (já definida)

**Funcionalidades:**
- [x] Cadastro de cliente
- [x] Cadastro de fornecedor com segmento
- [x] Validação de CPF/CNPJ
- [ ] Interface com seleção de perfil
- [ ] Upload de logo (fornecedor)

**Dependências:** Módulo 1.1

---

### **FASE 2: PERFIL CLIENTE** 👤
**Prioridade:** ALTA  
**Tempo Estimado:** 8-10 horas

#### Módulo 2.1: Dashboard Cliente
**Arquivos a criar:**
- `app/Views/cliente/dashboard.php` - Dashboard principal
- `app/Views/layouts/cliente.php` - Layout base cliente
- `app/Controllers/ClientController.php` - Controlador dashboard
- `public/assets/css/cliente.css` - Estilos cliente
- `public/assets/js/cliente.js` - Lógica cliente

**Controlador:** ⏳ A criar  
**Modelos:** ✅ `Vehicle`, `Maintenance`, `ServiceOrder` (já existem)  
**Rotas:** ✅ `/cliente/dashboard` (já definida)

**Funcionalidades:**
- [ ] Cards com estatísticas
- [ ] Últimos veículos cadastrados
- [ ] Próximas manutenções
- [ ] Últimas O.S
- [ ] Menu lateral responsivo

**Dependências:** Módulo 1.1

---

#### Módulo 2.2: Meus Veículos (CRUD Completo)
**Arquivos a criar:**
- `app/Views/cliente/veiculos/index.php` - Listagem
- `app/Views/cliente/veiculos/create.php` - Cadastro
- `app/Views/cliente/veiculos/edit.php` - Edição
- `app/Views/cliente/veiculos/show.php` - Detalhes
- `app/Controllers/ClientVehicleController.php` - Controlador

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `Vehicle` (já existe)  
**Rotas:** ✅ Todas definidas

**Funcionalidades:**
- [ ] Listar veículos (tabela responsiva)
- [ ] Cadastrar novo veículo (formulário)
- [ ] Editar veículo (modal ou página)
- [ ] Deletar veículo (confirmação)
- [ ] Visualizar detalhes e histórico
- [ ] Validação de placa

**Dependências:** Módulo 2.1

---

#### Módulo 2.3: Carteira Digital
**Arquivos a criar:**
- `app/Views/cliente/carteira/index.php` - Listagem de documentos
- `app/Controllers/ClientWalletController.php` - Controlador
- `public/assets/js/upload.js` - Upload de arquivos

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `Wallet` (já existe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Listar documentos por veículo
- [ ] Upload de CRLV/Licenciamento
- [ ] Upload de fotos do veículo
- [ ] Upload de CNH
- [ ] Visualizar documentos (PDF/imagem)
- [ ] Deletar documentos
- [ ] Validação de tipo/tamanho

**Dependências:** Módulo 2.2

---

#### Módulo 2.4: Manutenção Manual
**Arquivos a criar:**
- `app/Views/cliente/manutencao/index.php` - Histórico
- `app/Views/cliente/manutencao/create.php` - Nova manutenção
- `app/Controllers/ClientMaintenanceController.php` - Controlador
- `public/assets/js/manutencao.js` - Lógica de campos dinâmicos

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `Maintenance` (já existe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Listar histórico de manutenções
- [ ] Selecionar veículo
- [ ] Selecionar tipo de manutenção
- [ ] Campos dinâmicos por tipo (óleo, filtros, etc)
- [ ] Informar KM atual
- [ ] Salvar como "Lançamento Manual"
- [ ] Filtros por veículo/tipo/data

**Dependências:** Módulo 2.2

---

#### Módulo 2.5: Autenticação de Fornecedores
**Arquivos a criar:**
- `app/Views/cliente/autenticacao/index.php` - Lista de solicitações
- `app/Controllers/ClientAuthenticationController.php` - Controlador
- `public/assets/js/autenticacao.js` - Lógica de tokens

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `ProviderAuthentication` (já existe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Listar solicitações pendentes
- [ ] Aprovar solicitação (gerar token 6 dígitos)
- [ ] Negar solicitação
- [ ] Exibir token gerado
- [ ] Histórico de autenticações
- [ ] Status (pendente/aprovado/negado/expirado)

**Dependências:** Módulo 2.1

---

#### Módulo 2.6: Histórico de O.S (Cliente)
**Arquivos a criar:**
- `app/Views/cliente/os/index.php` - Lista de O.S
- `app/Views/cliente/os/show.php` - Detalhes da O.S
- `app/Controllers/ClientServiceOrderController.php` - Controlador

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `ServiceOrder` (já existe)  
**Rotas:** ⏳ A definir

**Funcionalidades:**
- [ ] Listar todas as O.S
- [ ] Filtrar por veículo/fornecedor/status
- [ ] Visualizar detalhes da O.S
- [ ] Ver certificado APP AUTO
- [ ] Download de certificado (PDF)
- [ ] Timeline de status

**Dependências:** Módulo 2.2

---

### **FASE 3: PERFIL FORNECEDOR** 🏢
**Prioridade:** ALTA  
**Tempo Estimado:** 10-12 horas

#### Módulo 3.1: Dashboard Fornecedor
**Arquivos a criar:**
- `app/Views/fornecedor/dashboard.php` - Dashboard principal
- `app/Views/layouts/fornecedor.php` - Layout base fornecedor
- `app/Controllers/ProviderController.php` - Controlador dashboard
- `public/assets/css/fornecedor.css` - Estilos fornecedor
- `public/assets/js/fornecedor.js` - Lógica fornecedor

**Controlador:** ⏳ A criar  
**Modelos:** ✅ Todos existem  
**Rotas:** ✅ `/fornecedor/dashboard` (já definida)

**Funcionalidades:**
- [ ] Cards com estatísticas
- [ ] Total de clientes
- [ ] O.S em andamento
- [ ] O.S aguardando retirada
- [ ] Faturamento do mês
- [ ] Menu lateral responsivo

**Dependências:** Módulo 1.1

---

#### Módulo 3.2: Gestão de Clientes (Multi-Tenant)
**Arquivos a criar:**
- `app/Views/fornecedor/clientes/index.php` - Lista e cadastro
- `app/Views/fornecedor/clientes/show.php` - Detalhes
- `app/Controllers/ProviderClientController.php` - Controlador
- `app/Models/ClientProvider.php` - Modelo (se necessário)

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `User` (base-mãe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Buscar por CPF/CNPJ
- [ ] Verificar se existe na base-mãe
- [ ] Solicitar autenticação (se existir)
- [ ] Cadastrar localmente (se não existir)
- [ ] Criar na base-mãe automaticamente
- [ ] Listar clientes do fornecedor
- [ ] Visualizar histórico do cliente

**Dependências:** Módulo 3.1

---

#### Módulo 3.3: Consulta de Veículos por Placa
**Arquivos a criar:**
- `app/Views/fornecedor/veiculos/search.php` - Busca por placa
- `app/Views/fornecedor/veiculos/show.php` - Dados do veículo
- `app/Controllers/ProviderVehicleController.php` - Controlador

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `Vehicle`, `ProviderAuthentication` (já existem)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Buscar por placa
- [ ] Validar token de autenticação
- [ ] Importar dados do veículo
- [ ] Exibir marca, modelo, ano, cor
- [ ] Informar KM atual (obrigatório)
- [ ] Iniciar atendimento

**Dependências:** Módulo 3.2

---

#### Módulo 3.4: Ordens de Serviço (Criar e Gerenciar)
**Arquivos a criar:**
- `app/Views/fornecedor/os/index.php` - Lista de O.S
- `app/Views/fornecedor/os/create.php` - Nova O.S
- `app/Views/fornecedor/os/show.php` - Detalhes da O.S
- `app/Views/fornecedor/os/finalize.php` - Finalizar O.S
- `app/Controllers/ProviderServiceOrderController.php` - Controlador
- `public/assets/js/os.js` - Lógica de O.S

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `ServiceOrder` (já existe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Criar nova O.S
- [ ] Selecionar cliente
- [ ] Selecionar veículo
- [ ] Informar KM de entrada
- [ ] Selecionar tipo de serviço
- [ ] Atualizar status (em execução/reagendado)
- [ ] Listar todas as O.S
- [ ] Filtrar por status

**Dependências:** Módulo 3.3

---

#### Módulo 3.5: Finalização de Serviço
**Arquivos a criar:**
- `app/Views/fornecedor/os/finalize-form.php` - Formulário detalhado
- `app/Controllers/ProviderServiceOrderController.php` - Método finalizar
- `public/assets/js/finalize.js` - Campos dinâmicos por tipo

**Controlador:** ⏳ Estender existente  
**Modelo:** ✅ `ServiceOrder` (já existe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Abrir formulário conforme tipo de serviço
- [ ] Checkboxes de itens (óleo, filtros, etc)
- [ ] Campos de marca e quantidade
- [ ] Informar valor total
- [ ] Informar KM de saída
- [ ] Gerar certificado APP AUTO
- [ ] Mudar status para "Aguardando Retirada"

**Dependências:** Módulo 3.4

---

#### Módulo 3.6: Retirada de Veículo
**Arquivos a criar:**
- `app/Views/fornecedor/os/retirada.php` - Tela de retirada
- `app/Controllers/ProviderServiceOrderController.php` - Método retirada
- `public/assets/js/retirada.js` - Validação de token

**Controlador:** ⏳ Estender existente  
**Modelo:** ✅ `ServiceOrder`, `Token` (já existem)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Gerar token de 6 dígitos
- [ ] Exibir token para o fornecedor
- [ ] Cliente informa token
- [ ] Validar token
- [ ] Liberar retirada
- [ ] Mudar status para "Concluído"
- [ ] Registrar data/hora de retirada

**Dependências:** Módulo 3.5

---

### **FASE 4: CERTIFICAÇÃO DIGITAL** ✅
**Prioridade:** MÉDIA  
**Tempo Estimado:** 4-6 horas

#### Módulo 4.1: Geração de Certificados
**Arquivos a criar:**
- `app/Services/CertificateService.php` - Serviço de certificação
- `app/Views/certificados/template.php` - Template HTML
- `public/assets/css/certificado.css` - Estilos para impressão

**Controlador:** ⏳ Criar serviço  
**Modelo:** ✅ Tabela `certificados_appauto` (já existe)  
**Rotas:** ⏳ A definir

**Funcionalidades:**
- [ ] Gerar número único de certificado
- [ ] Criar hash SHA256 para validação
- [ ] Vincular cliente, fornecedor, veículo, O.S
- [ ] Salvar itens do serviço (JSON)
- [ ] Gerar PDF do certificado
- [ ] QR Code para validação

**Dependências:** Módulo 3.5

---

#### Módulo 4.2: Validação de Certificados
**Arquivos a criar:**
- `app/Views/certificados/validar.php` - Página pública
- `app/Controllers/CertificateController.php` - Controlador
- `public/assets/js/validar-certificado.js` - Lógica de validação

**Controlador:** ⏳ A criar  
**Modelo:** ✅ Tabela `certificados_appauto` (já existe)  
**Rotas:** ⏳ A definir

**Funcionalidades:**
- [ ] Buscar por número de certificado
- [ ] Validar hash
- [ ] Exibir dados do certificado
- [ ] Verificar autenticidade
- [ ] Página pública (sem login)

**Dependências:** Módulo 4.1

---

### **FASE 5: ADMINISTRAÇÃO** 👨‍💼
**Prioridade:** BAIXA  
**Tempo Estimado:** 6-8 horas

#### Módulo 5.1: Dashboard Admin
**Arquivos a criar:**
- `app/Views/admin/dashboard.php` - Dashboard admin
- `app/Views/layouts/admin.php` - Layout base admin
- `app/Controllers/AdminController.php` - Controlador
- `public/assets/css/admin.css` - Estilos admin

**Controlador:** ⏳ A criar  
**Modelos:** ✅ Todos existem  
**Rotas:** ✅ `/admin/dashboard` (já definida)

**Funcionalidades:**
- [ ] Estatísticas gerais
- [ ] Total de usuários
- [ ] Total de fornecedores
- [ ] Total de veículos
- [ ] Total de O.S
- [ ] Gráficos e relatórios

**Dependências:** Módulo 1.1

---

#### Módulo 5.2: Gestão de Usuários
**Arquivos a criar:**
- `app/Views/admin/usuarios/index.php` - Lista
- `app/Views/admin/usuarios/show.php` - Detalhes
- `app/Controllers/AdminUserController.php` - Controlador

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `User` (já existe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Listar todos os usuários
- [ ] Filtrar por role/status
- [ ] Visualizar detalhes
- [ ] Ativar/desativar usuário
- [ ] Redefinir senha

**Dependências:** Módulo 5.1

---

#### Módulo 5.3: Gestão de Fornecedores
**Arquivos a criar:**
- `app/Views/admin/fornecedores/index.php` - Lista
- `app/Views/admin/fornecedores/show.php` - Detalhes
- `app/Controllers/AdminProviderController.php` - Controlador

**Controlador:** ⏳ A criar  
**Modelo:** ✅ `Provider` (já existe)  
**Rotas:** ✅ Definidas

**Funcionalidades:**
- [ ] Listar todos os fornecedores
- [ ] Filtrar por segmento/status
- [ ] Aprovar/rejeitar fornecedor
- [ ] Visualizar estatísticas
- [ ] Desativar fornecedor

**Dependências:** Módulo 5.1

---

### **FASE 6: INTEGRAÇÕES E MELHORIAS** 🚀
**Prioridade:** BAIXA  
**Tempo Estimado:** 8-10 horas

#### Módulo 6.1: API REST Completa
**Arquivos a criar:**
- `app/Controllers/Api/` - Controladores API
- `app/Middleware/JwtMiddleware.php` - Middleware JWT
- `docs/API_COMPLETA.md` - Documentação

**Funcionalidades:**
- [ ] Autenticação JWT
- [ ] Endpoints para mobile
- [ ] Versionamento de API
- [ ] Rate limiting
- [ ] Documentação Swagger

**Dependências:** Todos os módulos anteriores

---

#### Módulo 6.2: Notificações
**Arquivos a criar:**
- `app/Services/NotificationService.php` - Serviço
- `app/Views/notificacoes/index.php` - Central
- `public/assets/js/notifications.js` - Real-time

**Funcionalidades:**
- [ ] Notificações in-app
- [ ] Email notifications
- [ ] SMS (opcional)
- [ ] Push notifications (mobile)

**Dependências:** Módulo 6.1

---

#### Módulo 6.3: Relatórios
**Arquivos a criar:**
- `app/Controllers/ReportController.php` - Controlador
- `app/Views/relatorios/` - Telas de relatórios
- `app/Services/PdfService.php` - Geração de PDF

**Funcionalidades:**
- [ ] Relatório de manutenções
- [ ] Relatório de custos
- [ ] Relatório de fornecedores
- [ ] Exportar para PDF/Excel
- [ ] Gráficos interativos

**Dependências:** Todos os módulos de cliente/fornecedor

---

## 📊 Resumo de Implementação

### Legenda
- ✅ **Pronto** - Já implementado
- ⏳ **A fazer** - Precisa ser criado
- 🔄 **Em progresso** - Sendo desenvolvido

### Estatísticas

| Fase | Módulos | Arquivos | Status |
|------|---------|----------|--------|
| Fase 1 | 2 | ~6 arquivos | ⏳ Próxima |
| Fase 2 | 6 | ~20 arquivos | ⏳ A fazer |
| Fase 3 | 6 | ~25 arquivos | ⏳ A fazer |
| Fase 4 | 2 | ~8 arquivos | ⏳ A fazer |
| Fase 5 | 3 | ~12 arquivos | ⏳ A fazer |
| Fase 6 | 3 | ~15 arquivos | ⏳ A fazer |
| **Total** | **22** | **~86 arquivos** | **0% completo** |

### Já Implementado
- ✅ Estrutura MVC completa
- ✅ Banco de dados (12 tabelas)
- ✅ 7 Modelos
- ✅ 3 Controladores base
- ✅ Rotas definidas
- ✅ Segurança (CSRF, SQL Injection)
- ✅ Documentação

---

## 🎯 Próximos Passos Recomendados

### 1. Começar pela Fase 1 (Autenticação)
Sem login funcional, nada mais pode ser testado.

### 2. Depois Fase 2 (Cliente)
Cliente é o core do sistema.

### 3. Então Fase 3 (Fornecedor)
Fornecedor depende de clientes e veículos.

### 4. Implementar Certificação (Fase 4)
Diferencial do sistema.

### 5. Admin e Melhorias (Fases 5 e 6)
Últimas funcionalidades.

---

## 📝 Notas de Implementação

### Padrão de Nomenclatura
- **Controllers:** `NomePerfil` + `NomeRecurso` + `Controller`
  - Exemplo: `ClientVehicleController`, `ProviderServiceOrderController`

- **Views:** `perfil/recurso/acao.php`
  - Exemplo: `cliente/veiculos/index.php`, `fornecedor/os/create.php`

- **Models:** Nome no singular
  - Exemplo: `Vehicle`, `ServiceOrder`

- **Rotas:** `/perfil/recurso/acao`
  - Exemplo: `/cliente/veiculos`, `/fornecedor/os/finalizar`

### Boas Práticas
- Sempre validar entrada
- Sempre sanitizar dados
- Sempre usar prepared statements
- Sempre logar ações importantes
- Sempre verificar autenticação
- Sempre verificar autorização

---

**Versão:** 1.0.0  
**Data:** Janeiro 2026  
**Status:** Plano Completo - Pronto para Execução
