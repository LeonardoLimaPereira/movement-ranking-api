# Movement Ranking API

API REST construída em PHP puro (sem frameworks) para gerar ranking de movimentos com base nos recordes pessoais dos usuários.

---

## Tecnologias

* PHP 8.2
* MySQL 8
* Docker + Docker Compose
* Nginx

---

## Como executar

### 1. Clonar o repositório

```bash
git clone https://github.com/LeonardoLimaPereira/movement-ranking-api.git
cd movement-ranking-api
```

---

### 2. Subir os containers

```bash
docker-compose up -d --build
```

---

### 3. Acessar a API

```
http://localhost:8000/api/ranking?movement=Deadlift
```

---

## Endpoint

### GET /api/ranking

Retorna o ranking de um movimento.

#### Parâmetros

| Parâmetro | Tipo   | Descrição               |
| --------- | ------ | ----------------------- |
| movement  | string | Nome ou ID do movimento |

#### Exemplo

```bash
curl "http://localhost:8000/api/ranking?movement=Back Squat"
```

---

## 🧾 Exemplo de resposta

```json
{
    "movement": "Back Squat",
    "ranking": [
        {
            "name": "Joao",
            "record": 130,
            "position": 1,
            "date": "2021-01-03 00:00:00"
        },
        {
            "name": "Jose",
            "record": 130,
            "position": 1,
            "date": "2021-01-03 00:00:00"
        },
        {
            "name": "Paulo",
            "record": 125,
            "position": 2,
            "date": "2021-01-03 00:00:00"
        }
    ]
}
```

---

## Regras de Negócio

* O ranking considera o **maior recorde pessoal de cada usuário**.
* A ordenação é **decrescente** pelo valor do recorde.
* Usuários com o mesmo valor compartilham a **mesma posição (empate)**.

---

## Banco de Dados

O banco de dados é criado automaticamente ao subir o container, utilizando os scripts SQL.

### Estrutura

* `user`
* `movement`
* `personal_record`

### Inicialização automática

Os scripts estão em:

```
/database
```

E são executados automaticamente pelo Docker através de:

```
/docker-entrypoint-initdb.d/
```

---

## Estrutura do Projeto

```
/bootstrap
  app.php

/src
  /Cache
    FileCache.php
  /Controller
    RankingController.php
  /Core
    Container.php
    Request.php
    Response.php
    Router.php
  /Repository
    MovementRepository.php
    PersonalRecordRepository.php
  /Service
    RankingService.php
  Database.php

/public
  index.php

/routes
  api.php

/database
  2026_04_16_000001_migration.sql
  2026_04_16_000002_seed.sql
```

---

## Cache

Foi implementado um cache simples baseado em arquivos para reduzir o número de consultas ao banco.

* Chave baseada no ID do Moviment
* Tempo de duração de 60 segundos

Essa abordagem permite fácil substituição por Redis ou Memcached.

---

## Arquitetura

A aplicação segue uma arquitetura em camadas:

* **Core / Rotas** → O roteamento da aplicação é gerenciado pelo `Router` e as rotas são definidas de forma isolada no arquivo `api.php`.
* **Controller** → recebe a requisição e retorna a resposta
* **Service** → contém as regras de negócio
* **Repository** → responsável pelo acesso ao banco
* **Cache** → melhora a performance

---

## Boas práticas aplicadas

* Separação de responsabilidades
* Injeção de dependência manual
* Uso de prepared statements (PDO)
* Roteamento dedicado e estruturado (utilizando `api.php`)
* Tratamento de erros com status HTTP
* Código limpo e organizado

---

## Possíveis melhorias

* Adicionar Redis como cache
* Implementar testes com PHPUnit
* Adicionar paginação
* Versionamento de API (`/api/v1`)

---

## Autor

Leonardo de Lima Pereira