# 🕒 Sistema de Controle de Pontos

## 📘 Descrição

O Sistema de Controle de Pontos tem como objetivo facilitar o gerenciamento da jornada de trabalho dos colaboradores.
A aplicação permite cadastrar funcionários, registrar horários de entrada, saída e pausas, e visualizar relatórios completos de pontos registrados.
Todos os dados são armazenados em um banco de dados MySQL, garantindo controle, organização e segurança das informações.

---

## Projeto da disciplina de Programação Web, ministrada pelo professor Daniel Brandão.

---
## Equipe e Responsabilidades 

O projeto foi dividido em três frentes de trabalho:

Jayane Ellen Dias Freire |Front-end| 
Alana Kelly Reis Da Silva |Back-end|
Leandra Gleyce Cavalcante Bazilio |Infraestrutura/Banco de Dados|

---
## Tecnologias Utilizadas 

* **Linguagem de Back-end:** PHP 8.x
* **Banco de Dados:** MySQL
* **Interface:** HTML5 e CSS3
*  **Servidor Local:** XAMPP

---
## Funcionalidades e Regras de Negócio: Fluxo de Acesso

O sistema de Controle de Pontos funciona com um fluxo de acesso restrito. Todas as permissões e redirecionamentos são definidos 
pelo **Perfil de Usuário** (`tipo`) registrado na tabela `usuarios`.

### 1. Módulos de Acesso Comum (Para Todos)

Estes módulos são cruciais para a entrada no sistema:

* **Login e Autenticação:**
    * **Funcionalidade:** O usuário insere E-mail e Senha no `login.php`.
Se os dados estiverem corretos, uma sessão é iniciada.
    * **Regra de Controle de Acesso:** Imediatamente após o login, o sistema verifica o `tipo` de usuário e o redireciona automaticamente
para a página correta (`admin.php` ou `ponto.php`).

## 2. Perfis de Usuário e Permissões Específicas

As ações permitidas dentro do sistema dependem do que está resgistrado no banco de dados para o usuário:

### A. Perfil: Empregado (`tipo = empregador`)

Este perfil acessa a área de `ponto.php` e  é focado na marcação de **Entrada** ou **Saída** de forma simples.
