# Projeto com PHP 8.2.22 e PostgreSQL no Docker

## Requisitos

- PHP 8.2.22
- Docker instalado

## Configuração do PostgreSQL com Docker

1. **Baixar a imagem do PostgreSQL:**

```bash
docker pull postgres
```

2. **Criar e executar o contêiner:**

```bash
docker run --name postgres -p 5432:5432 -e POSTGRES_PASSWORD=root -d postgres
```

3. **Verificar se o PostgreSQL está rodando:**

```bash
docker ps
```

Você deverá ver uma saída semelhante a esta:

```
CONTAINER ID   IMAGE      COMMAND                  CREATED          STATUS          PORTS      NAMES
b2bed678ea60   postgres   "docker-entrypoint.s…"   19 seconds ago   Up 18 seconds   5432/tcp   some-postgres
```

**Obs:** Criar o banco de dados em algum SGBD, o nome deve ser `systock`.

## Back-end

### Configurando variáveis de ambiente

Crie uma cópia do arquivo `.env.example` com o nome de `.env` e edite as seguintes variáveis:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=systock
DB_USERNAME=postgres
DB_PASSWORD=root
```

### Configurando o back-end

Após configurar o `.env`, instale as dependências com o Composer:

```bash
composer install
```

Em seguida, execute os comandos:

```bash
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

## Front-end

- **Versão do Node:** 22.12.0
- Para instalar as dependências do projeto, execute:

```bash
npm install
```

e após as instalação das dependências, execute

```bash
npm run dev
```

caso queira uma porta especifica

```bash
npm run dev -- --port 8082
```
