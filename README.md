# APP AUTO - Sistema SaaS Automotivo

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-8892BF.svg)](https://php.net/)
[![Status](https://img.shields.io/badge/status-active-success.svg)]()

## 📋 Sobre o Projeto

**APP AUTO** é um ecossistema SaaS automotivo completo que conecta clientes finais e empresas do setor automobilístico (oficinas mecânicas, autopeças, lava-jato, funilaria, elétrica automotiva, pneus, concessionárias, entre outros).

O sistema funciona como uma **carteira digital automotiva**, centralizando:
- 🚗 Saúde do veículo em tempo real
- 🔧 Manutenção e histórico
- 📄 Documentos digitais
- ✅ Certificação digital APP AUTO

---

## 🎯 Características Principais

### Arquitetura
- ✅ **SaaS Multi-Tenant**
- ✅ **API REST** completa
- ✅ **Autenticação JWT** + Tokens temporários
- ✅ **Banco Relacional** (MySQL)
- ✅ **MVC Profissional**
- ✅ **Responsivo** (Web + Mobile-ready)

### Perfis
- 👤 **Cliente** (Pessoa Física ou Jurídica)
- 🏢 **Fornecedor** (Empresa Automotiva)
- 👨‍💼 **Administrador** Geral

### Funcionalidades

#### Cliente
- 🚗 Cadastro de múltiplos veículos
- 💼 Carteira digital (documentos, fotos, CNH)
- 🔧 Registro de manutenção manual
- 🔐 Autenticação de fornecedores (tokens)
- 📜 Histórico de O.S com certificação

#### Fornecedor
- 👥 Gestão de clientes
- 🚗 Consulta de veículos por placa
- 🛠️ Criação de ordens de serviço
- ✅ Finalização com certificação APP AUTO
- 🔐 Retirada de veículo com token

---

## 🏗️ Estrutura do Projeto

```
appauto/
├── .env                          # Variáveis de ambiente
├── .gitignore                    # Arquivos ignorados
├── README.md                     # Este arquivo
├── LICENSE                       # Licença MIT
├── public/                       # Raiz pública
│   ├── .htaccess                # Rewrite rules
│   ├── index.php                # Front controller
│   ├── assets/
│   │   ├── css/                 # Estilos
│   │   ├── js/                  # Scripts
│   │   └── img/                 # Imagens
│   └── uploads/                 # Arquivos enviados
├── app/
│   ├── Controllers/             # Controladores
│   ├── Models/                  # Modelos
│   ├── Views/                   # Visualizações
│   ├── Middleware/              # Middlewares
│   └── Routes/                  # Rotas
├── config/
│   ├── app.php                  # Configurações
│   ├── database.php             # Banco de dados
│   └── constants.php            # Constantes
├── core/
│   ├── Router.php               # Roteador
│   ├── Controller.php           # Controlador base
│   ├── Model.php                # Modelo base
│   └── Database.php             # Conexão BD
├── storage/
│   ├── logs/                    # Logs
│   └── cache/                   # Cache
├── docs/                        # Documentação
└── tests/                       # Testes
```

---

## 🚀 Instalação

### Requisitos
- PHP >= 7.4
- MySQL >= 5.7
- Apache/Nginx com mod_rewrite
- Composer (opcional)

### Passo a Passo

#### 1. Clone o repositório
```bash
git clone https://github.com/choppon24h-png/appauto.git
cd appauto
```

#### 2. Configure o arquivo .env
```bash
cp .env.example .env
nano .env
```

Edite as variáveis:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://erp.appauto.com.br

DB_HOST=localhost
DB_NAME=inlaud99_appauto
DB_USER=inlaud99_admin
DB_PASS=Admin259087@
```

#### 3. Crie o banco de dados
```bash
mysql -u root -p < docs/database.sql
```

#### 4. Configure permissões
```bash
chmod 755 public/
chmod 777 storage/logs/
chmod 777 storage/cache/
chmod 777 public/uploads/
```

#### 5. Configure o Apache/Nginx

**Apache (.htaccess já configurado)**
```apache
DocumentRoot /var/www/html/appauto/public
```

**Nginx**
```nginx
root /var/www/html/appauto/public;
index index.php;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
}
```

#### 6. Acesse o sistema
```
https://erp.appauto.com.br
```

---

## 📚 Documentação da API

### Autenticação

#### POST `/api/auth/login`
```json
{
  "email": "usuario@example.com",
  "password": "senha123"
}
```

**Resposta:**
```json
{
  "sucesso": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "usuario": {
    "id": 1,
    "nome": "João Silva",
    "role": "cliente"
  }
}
```

### Veículos

#### GET `/api/veiculos`
Lista todos os veículos do usuário autenticado.

#### POST `/api/veiculos`
```json
{
  "marca": "Toyota",
  "modelo": "Corolla",
  "placa": "ABC-1234",
  "ano": 2023,
  "cor": "Preto",
  "quilometragem": 15000
}
```

### Ordens de Serviço

#### POST `/api/os`
```json
{
  "cliente_id": 1,
  "veiculo_id": 5,
  "tipo_servico": "troca_oleo",
  "quilometragem": 15500
}
```

---

## 🔐 Segurança

- ✅ **HTTPS** obrigatório em produção
- ✅ **JWT** para autenticação
- ✅ **CSRF Protection**
- ✅ **Prepared Statements** (SQL Injection)
- ✅ **Sanitização** de entrada
- ✅ **Hash bcrypt** para senhas
- ✅ **Headers HTTP** de segurança
- ✅ **Rate Limiting**
- ✅ **Auditoria** de ações

---

## 🧪 Testes

```bash
# Executar todos os testes
php tests/run.php

# Executar testes específicos
php tests/AuthTest.php
php tests/VehicleTest.php
```

---

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

## 👥 Equipe

- **Desenvolvedor Principal:** APP AUTO Team
- **Repositório:** https://github.com/choppon24h-png/appauto
- **Website:** https://erp.appauto.com.br

---

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

---

## 📞 Suporte

Para suporte, envie um email para suporte@appauto.com.br ou abra uma issue no GitHub.

---

## 🗺️ Roadmap

- [x] Estrutura MVC
- [x] Autenticação JWT
- [x] CRUD de veículos
- [x] Sistema de tokens
- [ ] App Mobile (React Native)
- [ ] Integração com WhatsApp
- [ ] Notificações Push
- [ ] Relatórios PDF
- [ ] Dashboard Analytics

---

**Versão:** 1.0.0  
**Status:** ✅ Em Produção  
**Última Atualização:** Janeiro 2026
