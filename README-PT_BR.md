# Laravel chat reverb
Este repositório contém um exemplo de como criar um chat em tempo real usando Laravel + Reverb com Vue.js.

---
## Instalação (com Docker + Laravel Sail)
Primeiro instale as dependências do projeto:
```bash
composer install
```

Após isso configure os containers da aplicação:
```bash
sail up -d
```

Instale as dependências do frontend:
```bash
sail npm install
```

Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente conforme necessário.

Execute as migrações do banco de dados:
```bash
sail artisan migrate
```

**Opcional:** Se você quiser popular o banco de dados com alguns dados iniciais, execute:
```bash
sail artisan db:seed --class=ChatExampleSeeder
```

**Opcional:** Se você quiser popular apenas usuários, execute:
```bash
sail artisan ti --execute "User::factory(20)->create()"
```

Configure a chave da aplicação:
```bash
sail artisan key:generate
```

Compile os assets do frontend:
```bash
sail npm run build
```

Execute o reverb server:
```bash
sail artisan reverb:start
```

Acesse a aplicação no navegador na url [http://localhost/login](http://localhost/login).

### Seeder de exemplo ou 'user factory' Tinker
Caso tenha executado o seeder "ChatExampleSeeder", você pode usar o usuário de exemplo:
- usuário: user1@example.com
- senha: password

Ou se você executou a 'user factory' Tinker, você pode obter qualquer usuário criado no banco de dados e ir para
[chat room](http://localhost/chat/room) e pesquisar por qualquer nome para iniciar uma nova sala de chat.

Acesse a tela [http://localhost/chat/room/1](http://localhost/chat/room/1) para ver o chat em ação.
OBS: Você pode logar com o 'user2@example.com' para visualizar melhor o chat em tempo real.

---
## Em desenvolvimento
[TODO](https://github.com/chsjr1996/brain.md/blob/main/Computa%C3%A7%C3%A3o%20geral/Dev/Projetos/Laravel%20reverb%20chat/TODO.md)
