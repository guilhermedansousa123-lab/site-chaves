## Controle de Chaves

Aplicação web para cadastrar chaves, registrar empréstimos e acompanhar devoluções. A interface usa Blade e Bootstrap 5; os registros ficam no banco configurado pelo Laravel.

### Tecnologias e requisitos

- PHP 8.3 ou superior e Composer.
- Laravel 13, Sanctum 4 e banco SQL compatível (SQLite ou MySQL).
- Node.js e npm são necessários somente para compilar os assets Vite existentes no projeto.
- Bootstrap 5.3 e Bootstrap Icons são carregados por CDN.

### Instalação e banco

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Configure `.env` para o banco local. Para MySQL, ajuste `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`. Crie o banco antes de executar as migrations. Para SQLite, defina `DB_CONNECTION=sqlite` e `DB_DATABASE` com o caminho do arquivo.

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

O seed atual cria a conta de desenvolvimento `test@example.com` com senha `password`, herdada da factory do projeto. Use-a apenas em ambiente local; substitua/remova essa conta antes de publicar a aplicação. Para compilar os assets existentes, execute `npm install` e `npm run build`.

As migrations novas são aditivas: `keys` armazena `name` único e `place`; `loans` referencia `keys.id`, com responsável, observação opcional e datas de empréstimo/devolução. A chave estrangeira impede excluir chaves com histórico. Não há migrations de alteração ou remoção das tabelas existentes.

### Interface

- `/login`: autenticação por sessão Laravel.
- `/`: painel com totais e movimentações recentes.
- `/keys`: listagem paginada, pesquisa, cadastro, edição e exclusão confirmada.
- `/loans`: histórico paginado, registro de empréstimo e ação para registrar devolução.

As páginas de operação exigem autenticação. A tela não aceita mais credenciais arbitrárias nem mantém os cadastros no `localStorage`.

### API REST v1

Obtenha um token com as credenciais de uma conta existente. Envie `Accept: application/json`; para JSON, envie também `Content-Type: application/json`.

```http
POST /api/v1/token
Content-Type: application/json
Accept: application/json

{"email":"test@example.com","password":"password","device_name":"Thunder Client"}
```

A resposta `201` inclui `data.access_token`. Nas demais rotas, use `Authorization: Bearer <token>`. `DELETE /api/v1/token` revoga o token atual.

| Método | Endpoint | Descrição |
| --- | --- | --- |
| GET | `/api/v1/keys?search=recepcao&status=available&per_page=15` | Lista chaves; filtros opcionais `search`, `status=available` ou `loaned` e `per_page` (máx. 100). |
| GET | `/api/v1/keys/{id}` | Consulta chave. |
| POST | `/api/v1/keys` | Cria chave: `name`, `place`. |
| PUT/PATCH | `/api/v1/keys/{id}` | Atualiza chave; PUT exige os dois campos, PATCH aceita campos parciais. |
| DELETE | `/api/v1/keys/{id}` | Exclui chave sem movimentações; caso contrário retorna 409. |
| GET | `/api/v1/loans?status=open&search=ana&per_page=15` | Lista empréstimos; filtros `status=open` ou `returned`, `search` pelo responsável e `per_page`. |
| GET | `/api/v1/loans/{id}` | Consulta empréstimo e chave associada. |
| POST | `/api/v1/loans` | Cria empréstimo: `key_id`, `person`, `note` opcional. |
| PUT/PATCH | `/api/v1/loans/{id}` | Atualiza empréstimo em aberto. PUT exige `key_id`, `person` e `note`; PATCH aceita campos parciais. |
| POST | `/api/v1/loans/{id}/return` | Registra devolução e mantém o histórico. |
| DELETE | `/api/v1/loans/{id}` | Cancela empréstimo aberto; histórico concluído não pode ser excluído (409). |

Exemplo de empréstimo:

```json
{
  "key_id": 1,
  "person": "Ana Silva",
  "note": "Reunião na sala 2"
}
```

Respostas de recurso seguem o envelope `{"data": {...}}`; listas paginadas incluem `data`, `links` e `meta`. O POST retorna 201, leitura/atualização 200 e exclusão 204. Erros comuns: 401 sem token, 404 para ID ausente, 409 para conflito de negócio e 422 para validação. Usuários e tabelas internas do Laravel não são recursos CRUD da API.

Exemplo de erro de validação (422):

```json
{
  "message": "The name field is required.",
  "errors": { "name": ["The name field is required."] }
}
```

Conflito de negócio (409) retorna uma mensagem como `A chave já está emprestada.`.

### Thunder Client

Importe `thunder-tests/thunder-collection_Controle-de-Chaves.json` no Thunder Client. Importe `thunder-tests/guizin.local.env.example` como ambiente e selecione-o. A coleção inclui autenticação, CRUD, IDs ausentes, campos obrigatórios e referência inválida. Depois de criar token, copie `data.access_token` para `token`; atualize `keyId` e `loanId` com IDs reais. Execute exclusões somente em registros de teste. A extensão Thunder Client não foi executada neste ambiente.

### Verificação

Execute `php artisan test --compact`. Os testes usam SQLite em memória conforme `phpunit.xml`, não o banco local configurado em `.env`.
