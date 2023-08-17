# Projeto Contact App

O Contact App é uma aplicação Laravel para gerenciar contatos. Este guia ajudará a configurar o ambiente e a executar testes usando Docker.

## Pré-requisitos

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/)

## Configuração

### Subindo os contêineres:

```bash
docker-compose up -d
```

### Configurando o Ambiente Laravel:

Depois de subir os contêineres:

1. Instalar as dependências do Composer:

```bash
docker exec -it chalenge-contacts-app composer install
```

2. Gerar a chave de aplicação:

```bash
docker exec -it chalenge-contacts-app php artisan key:generate
```

3. Rodar as migrações:

```bash
docker exec -it chalenge-contacts-app php artisan migrate
```

## Testes

Para executar os testes:

```bash
docker exec -it chalenge-contacts-app php artisan test
```

## Tarefas Pendentes

- [ ] Finalizar teste de fila para o método update.
