# 📦 Guia de Instalação - APP AUTO

## 🎯 Requisitos do Sistema

### Servidor
- **PHP:** >= 7.4
- **MySQL:** >= 5.7 ou MariaDB >= 10.3
- **Apache:** 2.4+ com mod_rewrite habilitado
- **Memória:** Mínimo 512MB RAM
- **Espaço em Disco:** Mínimo 1GB

### Extensões PHP Necessárias
```bash
php-mysql
php-pdo
php-json
php-mbstring
php-curl
php-gd
php-zip
```

---

## 🚀 Instalação no HostGator

### Passo 1: Fazer Upload dos Arquivos

#### Via FTP
1. Conecte-se ao FTP do HostGator
2. Navegue até `public_html/`
3. Faça upload do arquivo `appauto-v1.0.0.zip`
4. Extraia o arquivo no servidor

#### Via cPanel
1. Acesse o cPanel
2. Vá em **Gerenciador de Arquivos**
3. Navegue até `public_html/`
4. Clique em **Upload**
5. Selecione `appauto-v1.0.0.zip`
6. Clique com botão direito e **Extrair**

### Passo 2: Criar Banco de Dados

1. Acesse o cPanel
2. Vá em **MySQL® Databases**
3. Crie um novo banco de dados:
   - Nome: `inlaud99_appauto`
4. Crie um usuário:
   - Usuário: `inlaud99_admin`
   - Senha: `Admin259087@`
5. Adicione o usuário ao banco com **TODOS OS PRIVILÉGIOS**

### Passo 3: Importar Estrutura do Banco

1. Acesse **phpMyAdmin** no cPanel
2. Selecione o banco `inlaud99_appauto`
3. Clique na aba **Importar**
4. Selecione o arquivo `docs/database.sql`
5. Clique em **Executar**

### Passo 4: Configurar o Arquivo .env

1. Navegue até a raiz do projeto
2. Edite o arquivo `.env`:

```env
# Ambiente
APP_ENV=production
APP_DEBUG=false
APP_NAME="APP AUTO"
APP_URL=https://erp.appauto.com.br

# Banco de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=inlaud99_appauto
DB_USER=inlaud99_admin
DB_PASS=Admin259087@
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# Segurança
APP_KEY=GERE_UMA_CHAVE_ALEATORIA_AQUI_32_CARACTERES
SESSION_LIFETIME=3600
CSRF_TOKEN_LENGTH=32
```

**IMPORTANTE:** Gere uma chave aleatória para `APP_KEY`:
```bash
php -r "echo bin2hex(random_bytes(16));"
```

### Passo 5: Configurar Permissões

Via FTP ou cPanel, configure as permissões:

```bash
chmod 755 public/
chmod 777 storage/logs/
chmod 777 storage/cache/
chmod 777 public/uploads/
```

### Passo 6: Configurar Domínio

#### Opção A: Domínio Principal
Se `erp.appauto.com.br` é seu domínio principal:
1. Os arquivos já estão em `public_html/`
2. Configure o DocumentRoot para `public_html/appauto/public/`

#### Opção B: Subdomínio
1. Crie um subdomínio no cPanel
2. Aponte para `public_html/appauto/public/`

#### Opção C: Addon Domain
1. Adicione o domínio como Addon Domain
2. Aponte para `public_html/appauto/public/`

### Passo 7: Configurar .htaccess (se necessário)

O arquivo `.htaccess` já está configurado em `public/.htaccess`

Se precisar ajustar, edite:

```apache
RewriteEngine On
RewriteBase /

# HTTPS redirect
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Rotear para index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

### Passo 8: Testar a Instalação

1. Acesse: `https://erp.appauto.com.br`
2. Você deve ver a tela de login
3. Faça login com:
   - **Email:** admin@appauto.com.br
   - **Senha:** admin1234

---

## 🔧 Configurações Avançadas

### SSL/HTTPS

O HostGator oferece SSL gratuito via Let's Encrypt:

1. Acesse o cPanel
2. Vá em **SSL/TLS Status**
3. Ative o SSL para seu domínio
4. Aguarde alguns minutos

### PHP.ini Personalizado

Se precisar ajustar configurações PHP:

1. Crie um arquivo `php.ini` na raiz
2. Adicione:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
```

### Cron Jobs (Opcional)

Para tarefas agendadas:

1. Acesse **Cron Jobs** no cPanel
2. Adicione:

```bash
0 0 * * * php /home/usuario/public_html/appauto/cron/daily.php
```

---

## 🐛 Solução de Problemas

### Erro 500 - Internal Server Error

**Causa:** Permissões incorretas ou erro no .htaccess

**Solução:**
1. Verifique permissões dos diretórios
2. Verifique se mod_rewrite está habilitado
3. Verifique logs em `storage/logs/`

### Erro de Conexão com Banco

**Causa:** Credenciais incorretas

**Solução:**
1. Verifique o arquivo `.env`
2. Teste a conexão no phpMyAdmin
3. Verifique se o usuário tem permissões

### Página em Branco

**Causa:** Erro PHP não exibido

**Solução:**
1. Ative debug no `.env`:
   ```env
   APP_DEBUG=true
   ```
2. Verifique logs em `storage/logs/`
3. Verifique logs do Apache

### Upload de Arquivos Não Funciona

**Causa:** Permissões ou limite de tamanho

**Solução:**
1. Verifique permissões de `public/uploads/`
2. Ajuste `upload_max_filesize` no php.ini
3. Verifique espaço em disco

---

## 📞 Suporte

Se precisar de ajuda:

- **Email:** suporte@appauto.com.br
- **GitHub:** https://github.com/choppon24h-png/appauto/issues
- **Documentação:** https://github.com/choppon24h-png/appauto

---

## ✅ Checklist de Instalação

- [ ] Arquivos enviados para o servidor
- [ ] Banco de dados criado
- [ ] Usuário do banco criado
- [ ] Estrutura do banco importada
- [ ] Arquivo .env configurado
- [ ] Permissões configuradas
- [ ] Domínio apontado corretamente
- [ ] SSL ativado
- [ ] Login testado com sucesso

---

**Versão:** 1.0.0  
**Última Atualização:** Janeiro 2026
