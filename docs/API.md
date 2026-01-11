# 📡 Documentação da API - APP AUTO

## 🔐 Autenticação

Todas as rotas protegidas requerem autenticação via JWT.

### Headers Necessários
```http
Authorization: Bearer {token}
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
```

---

## 🔑 Endpoints de Autenticação

### POST `/api/auth/login`
Fazer login no sistema.

**Request:**
```json
{
  "email": "usuario@example.com",
  "password": "senha123"
}
```

**Response (200):**
```json
{
  "sucesso": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "usuario": {
    "id": 1,
    "nome": "João Silva",
    "email": "usuario@example.com",
    "role": "cliente"
  },
  "redirect": "/cliente/dashboard"
}
```

**Response (401):**
```json
{
  "sucesso": false,
  "mensagem": "Email ou senha inválidos"
}
```

### POST `/api/auth/register`
Registrar novo usuário.

**Request:**
```json
{
  "nome": "João Silva",
  "email": "joao@example.com",
  "cpf_cnpj": "12345678900",
  "password": "senha123",
  "password_confirm": "senha123",
  "role": "cliente",
  "telefone": "(11) 98765-4321"
}
```

**Response (201):**
```json
{
  "sucesso": true,
  "mensagem": "Usuário registrado com sucesso",
  "usuario_id": 15
}
```

### POST `/api/auth/logout`
Fazer logout.

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "Logout realizado com sucesso"
}
```

---

## 🚗 Endpoints de Veículos (Cliente)

### GET `/api/veiculos`
Listar todos os veículos do usuário autenticado.

**Response (200):**
```json
{
  "sucesso": true,
  "veiculos": [
    {
      "id": 1,
      "placa": "ABC-1234",
      "marca": "Toyota",
      "modelo": "Corolla",
      "ano": 2023,
      "cor": "Preto",
      "quilometragem": 15000,
      "combustivel": "gasolina",
      "status": "ativo"
    }
  ],
  "total": 1
}
```

### GET `/api/veiculos/{id}`
Obter detalhes de um veículo específico.

**Response (200):**
```json
{
  "sucesso": true,
  "veiculo": {
    "id": 1,
    "placa": "ABC-1234",
    "marca": "Toyota",
    "modelo": "Corolla",
    "ano": 2023,
    "cor": "Preto",
    "quilometragem": 15000,
    "combustivel": "gasolina",
    "renavam": "12345678901",
    "chassi": "9BWZZZ377VT004251",
    "status": "ativo",
    "data_criacao": "2026-01-01 10:00:00"
  }
}
```

### POST `/api/veiculos`
Cadastrar novo veículo.

**Request:**
```json
{
  "placa": "ABC-1234",
  "marca": "Toyota",
  "modelo": "Corolla",
  "ano": 2023,
  "cor": "Preto",
  "quilometragem": 15000,
  "combustivel": "gasolina",
  "renavam": "12345678901",
  "chassi": "9BWZZZ377VT004251"
}
```

**Response (201):**
```json
{
  "sucesso": true,
  "mensagem": "Veículo cadastrado com sucesso",
  "veiculo_id": 1
}
```

### PUT `/api/veiculos/{id}`
Atualizar dados do veículo.

**Request:**
```json
{
  "quilometragem": 16000,
  "cor": "Branco"
}
```

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "Veículo atualizado com sucesso"
}
```

### DELETE `/api/veiculos/{id}`
Deletar veículo.

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "Veículo deletado com sucesso"
}
```

---

## 💼 Endpoints de Carteira (Documentos)

### GET `/api/carteira`
Listar todos os documentos do usuário.

**Response (200):**
```json
{
  "sucesso": true,
  "documentos": [
    {
      "id": 1,
      "veiculo_id": 1,
      "tipo": "crlv",
      "nome_arquivo": "crlv_abc1234.pdf",
      "tamanho": 245678,
      "data_upload": "2026-01-01 10:00:00"
    }
  ]
}
```

### POST `/api/carteira/upload`
Fazer upload de documento.

**Request (multipart/form-data):**
```
veiculo_id: 1
tipo: crlv
arquivo: [file]
descricao: "CRLV 2026"
```

**Response (201):**
```json
{
  "sucesso": true,
  "mensagem": "Documento enviado com sucesso",
  "documento_id": 1,
  "url": "/uploads/documentos/crlv_abc1234.pdf"
}
```

### DELETE `/api/carteira/{id}`
Deletar documento.

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "Documento deletado com sucesso"
}
```

---

## 🔧 Endpoints de Manutenção (Cliente)

### GET `/api/manutencoes`
Listar todas as manutenções do usuário.

**Response (200):**
```json
{
  "sucesso": true,
  "manutencoes": [
    {
      "id": 1,
      "veiculo_id": 1,
      "tipo": "oleo",
      "descricao": "Troca de óleo e filtros",
      "quilometragem": 15000,
      "valor": 250.00,
      "data_manutencao": "2026-01-01",
      "tipo_lancamento": "manual",
      "certificado_appauto": false,
      "status": "concluido"
    }
  ]
}
```

### POST `/api/manutencoes`
Cadastrar nova manutenção manual.

**Request:**
```json
{
  "veiculo_id": 1,
  "tipo": "oleo",
  "descricao": "Troca de óleo e filtros",
  "quilometragem": 15000,
  "valor": 250.00,
  "data_manutencao": "2026-01-01",
  "itens": {
    "oleo_motor": true,
    "filtro_oleo": true,
    "filtro_ar": false
  }
}
```

**Response (201):**
```json
{
  "sucesso": true,
  "mensagem": "Manutenção cadastrada com sucesso",
  "manutencao_id": 1
}
```

---

## 🔐 Endpoints de Autenticação de Fornecedor

### GET `/api/autenticacao`
Listar solicitações de autenticação.

**Response (200):**
```json
{
  "sucesso": true,
  "solicitacoes": [
    {
      "id": 1,
      "fornecedor_id": 5,
      "fornecedor_nome": "Oficina XYZ",
      "status": "pendente",
      "data_solicitacao": "2026-01-01 10:00:00"
    }
  ]
}
```

### POST `/api/autenticacao/aprovar/{id}`
Aprovar solicitação e gerar token.

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "Solicitação aprovada",
  "token": "123456",
  "data_expiracao": "2026-01-01 11:00:00"
}
```

### POST `/api/autenticacao/negar/{id}`
Negar solicitação.

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "Solicitação negada"
}
```

---

## 📜 Endpoints de Ordens de Serviço (Cliente)

### GET `/api/os`
Listar todas as O.S do cliente.

**Response (200):**
```json
{
  "sucesso": true,
  "ordens_servico": [
    {
      "id": 1,
      "numero_os": "OS-2026-0001",
      "fornecedor_nome": "Oficina XYZ",
      "veiculo": "Toyota Corolla - ABC-1234",
      "tipo_servico": "Troca de óleo",
      "valor_total": 350.00,
      "status": "concluido",
      "certificado_appauto": true,
      "data_criacao": "2026-01-01 10:00:00",
      "data_finalizacao": "2026-01-01 12:00:00"
    }
  ]
}
```

---

## 🏢 Endpoints de Fornecedor

### POST `/api/fornecedor/clientes`
Cadastrar novo cliente (multi-tenant).

**Request:**
```json
{
  "nome": "Maria Silva",
  "cpf_cnpj": "98765432100",
  "email": "maria@example.com",
  "telefone": "(11) 98765-4321"
}
```

**Response (201):**
```json
{
  "sucesso": true,
  "mensagem": "Cliente cadastrado",
  "cliente_id": 10,
  "tipo": "base_mae",
  "solicitacao_enviada": true
}
```

### POST `/api/fornecedor/veiculos/buscar`
Buscar veículo por placa e token.

**Request:**
```json
{
  "placa": "ABC-1234",
  "token": "123456"
}
```

**Response (200):**
```json
{
  "sucesso": true,
  "veiculo": {
    "id": 1,
    "placa": "ABC-1234",
    "marca": "Toyota",
    "modelo": "Corolla",
    "ano": 2023,
    "cor": "Preto",
    "quilometragem": 15000
  },
  "cliente": {
    "nome": "João Silva",
    "telefone": "(11) 98765-4321"
  }
}
```

### POST `/api/fornecedor/os`
Criar nova ordem de serviço.

**Request:**
```json
{
  "cliente_id": 1,
  "veiculo_id": 1,
  "tipo_servico": "Troca de óleo",
  "descricao": "Troca de óleo e filtros",
  "quilometragem_entrada": 15000
}
```

**Response (201):**
```json
{
  "sucesso": true,
  "mensagem": "O.S criada com sucesso",
  "os_id": 1,
  "numero_os": "OS-2026-0001"
}
```

### POST `/api/fornecedor/os/{id}/finalizar`
Finalizar ordem de serviço.

**Request:**
```json
{
  "quilometragem_saida": 15005,
  "valor_total": 350.00,
  "itens_servico": {
    "oleo_motor": {
      "marca": "Castrol",
      "quantidade": 4,
      "valor": 200.00
    },
    "filtro_oleo": {
      "marca": "Mann",
      "quantidade": 1,
      "valor": 50.00
    }
  }
}
```

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "O.S finalizada com sucesso",
  "status": "aguardando_retirada",
  "certificado_numero": "CERT-2026-0001",
  "token_retirada": "654321"
}
```

### POST `/api/fornecedor/os/{id}/retirada`
Liberar retirada do veículo.

**Request:**
```json
{
  "token": "654321"
}
```

**Response (200):**
```json
{
  "sucesso": true,
  "mensagem": "Veículo liberado para retirada",
  "status": "concluido"
}
```

---

## 📊 Códigos de Status HTTP

- **200** - OK
- **201** - Criado
- **400** - Requisição inválida
- **401** - Não autenticado
- **403** - Não autorizado
- **404** - Não encontrado
- **409** - Conflito
- **422** - Erro de validação
- **500** - Erro interno do servidor

---

## 🔒 Segurança

### Rate Limiting
- **100 requisições** por hora por IP
- Headers de resposta:
  ```
  X-RateLimit-Limit: 100
  X-RateLimit-Remaining: 95
  X-RateLimit-Reset: 1609459200
  ```

### CORS
Apenas origens permitidas:
- `https://erp.appauto.com.br`

---

**Versão da API:** 1.0.0  
**Última Atualização:** Janeiro 2026
