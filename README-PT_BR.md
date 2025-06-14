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

### Seeder de exemplo
Caso tenha executado o seeder "ChatExampleSeeder", você pode usar o usuário de exemplo:
- usuário: user1@example.com
- senha: password

Acesse a tela [http://localhost/chat/room/1](http://localhost/chat/room/1) para ver o chat em ação.
OBS: Você pode logar com o 'user2@example.com' para visualizar melhor o chat em tempo real.

---
## Em desenvolvimento
**TODO:**
### Backend
- [x] Corrigir autenticação em `routes/channels.php`
- [ ] Criar novas rotas:
    - [ ] pessoas (lista de usuários para iniciar uma nova conversa/sala)
        - [ ] permitir ver quem está online/offline
        - [ ] exibir total de usuários online
    - [ ] conversas/salas (lista de conversas já iniciadas)
- [ ] permitir anexar imagens e enviar emojis nas mensagens
- [ ] ...?

### Frontend
- [ ] Refatorar:
    - [ ] Criar service para AppChat/sendMessage
    - [ ] Criar service para AppChat/onMounted get messages
- [x] Acrescentar tipagem no componente do chat
- [ ] Melhorar visual do chat:
    - [ ] scroll apenas no container do chat não na tela inteira
    - [ ] disposição do componente 'digitando';
    - [ ] mostrar se usuário está online/offline;
    - [ ] permitir criar grupos de conversa (adicionar mais de um usuário na conversa);
    - [ ] permitir anexar imagens e enviar emojis nas mensagens;
- [ ] Adicionar i18n
- [ ] Adicionar novos itens de menu:
    - [ ] pessoas (lista de usuários)
    - [ ] mensagens (lista de conversas já iniciadas)
- [ ] Novas telas:
    - [ ] dashboard (refazer)
    - [ ] usuários (iniciar novas conversas)
        -  [ ] permitir criar novas conversas/salas (1:1 ou grupo)
        -  [ ] permitir ver quem está online/offline
    - [ ] conversas/salas (lista de conversas já iniciadas)
- [ ] ...?
