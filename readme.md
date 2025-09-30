# Proxy de Cache em PHP (via Docker)

Um **servidor proxy de cache** em PHP usando Docker. Utiliza **Redis** para cache e **Composer** para dependências, ambos dentro do container.

---

## Detalhes

- Proxy que intercepta requisições HTTP.
- Armazenamento em cache usando Redis.
- TTL configurável (tempo de expiração do cache).
- Inicia o proxy ao executar o container, definir porta, senha do redis e servidor de origem em **.env**.

---

## Pré-requisitos

- Docker
- Docker Compose

---

## Instalação

1. Clone o repositório:

```bash
git clone https://github.com/seu-usuario/proxy-cache-php.git
cd proxy-cache-php
```

2. Renomeie o **".env-example"** para **".env"**, altere ORIGIN(Opcional) e adicione sua senha do redis:
```
ORIGIN=http://dummyjson.com
REDIS_PASSWORD=sua_senha_aqui
```

3. Inicie o projeto:
```
docker-compose build
docker-compose up -d
```

4. Para limpar o cache:
```
php index.php --clear
```



