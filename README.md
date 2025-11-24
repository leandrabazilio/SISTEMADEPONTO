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
## ⚙️ Funcionalidades e Regras de Negócio: Fluxo de Acesso 🚦

O sistema de Controle de Pontos opera com um fluxo de acesso estrito. As permissões e redirecionamentos são definidos pelo *Perfil de Usuário* (tipo) registrado na tabela usuarios.

### 1. Módulos de Acesso Comum (Para Todos)

* *Login e Autenticação:*
    * *Funcionalidade:* O usuário insere E-mail e Senha no login.php. Se os dados estiverem corretos, uma sessão é iniciada.
    * *Regra de Controle de Acesso:* Imediatamente após o login, o sistema verifica o tipo de usuário e o redireciona automaticamente para a página correta (admin.php ou ponto.php).

### 2. Perfis de Usuário e Permissões Específicas

As ações permitidas dentro do sistema dependem do perfil registrado:

#### A. Perfil: Empregado (tipo = empregador)

* *Área de Destino:* ponto.php
* *Funcionalidade Principal (Registro de Ponto):* Permite ao empregado registrar sua marcação de *Entrada* ou *Saída* de forma simples.
* *Regra de Negócio Chave (Alternância de Ponto):* O sistema impõe a lógica da jornada: *não é permitido* registrar duas Entradas consecutivas, nem duas Saídas consecutivas.

#### B. Perfil: RH / Gestão (tipo = rh)

* *Área de Destino:* admin.php
* *Funcionalidade Principal (Gestão/Relatórios):* Permite o acesso a painéis administrativos e à visualização de *todos* os registros de ponto de *todos* os empregados.
* *Regra de Permissão:* Este é o único perfil que possui acesso total e irrestrito aos dados de controle de ponto.

---
---
e  é focado na marcação de **Entrada** ou **Saída** de forma simples.
