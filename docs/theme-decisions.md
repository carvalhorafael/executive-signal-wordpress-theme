# Decisoes do tema

## 2026-05-30: Tema hibrido como base inicial

Decisao: iniciar como tema hibrido classico, com templates PHP, `theme.json`, block patterns e assets Vite.

Motivo: o Executive Signal precisa de controle fino de templates e distribuicao, mas tambem deve aproveitar o editor de blocos. Um block theme puro pode ser avaliado depois, quando os contratos de conteudo e patterns estiverem mais estaveis.

## 2026-05-30: Conteudo permanente fora do tema

Decisao: o tema nao deve ser a fonte de tipos de conteudo e regras de negocio duraveis.

Motivo: trocar tema nao deve apagar ou quebrar dominio de produto. Se o projeto precisar de CPTs, taxonomias ou metadados permanentes, criar plugin ou registrar uma excecao explicita aqui.
