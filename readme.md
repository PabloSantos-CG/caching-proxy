# Proxy de Cache em PHP (via Docker)

Um **servidor proxy de cache** em PHP usando Docker. Utiliza **Redis** para cache e **Composer** para dependências, ambos dentro do container.

---

## Funcionalidades

- Proxy que intercepta requisições HTTP.
- Armazenamento em cache usando Redis.
- TTL configurável (tempo de expiração do cache).
- CLI para iniciar o proxy, definir porta, definir servidor de origem e limpar cache.

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
