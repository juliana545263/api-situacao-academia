# API REST - Situação Acadêmica

Este projeto consiste em uma API REST desenvolvida para gerenciar informações relacionadas à situação acadêmica de estudantes em unidades curriculares. A API permite realizar o cadastro, consulta, atualização e remoção de registros.

##  Autora
*   **Juliana da Silva Bastos**
*   **Curso:** 2º Ano do Ensino Médio Técnico em Informática

##  Endpoints da API

A API possui os seguintes endpoints obrigatórios:

### 1. Cadastrar Situação Acadêmica
*   **Método:** `POST`
*   **Rota:** `/situacao`
*   **Descrição:** Cadastra uma nova situação acadêmica.
*   **Corpo da Requisição (JSON):**
    ```json
    {
      "nome": "Juliana da Silva Bastos",
      "unidadeCurricular": "Desenvolvimento Web",
      "aprovado": true
    }
