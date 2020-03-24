/*
Códigos LOV para o diagrama
01 - Tipo de Unidade
02 - Usa Contrato
03 - Função
04 - Esfera
05 - Setor
06 - Status Circuito
07 - Tipo de Acesso (antigo Enlace)
08 - Tipo de Email
09 - Tipo de Cliente (PF/PJ)
10 - Sexo
11 - Tipo de Endereço
12 - Tipo de Telefone
13 - Tipo de Contato (Cargo Empresa)
14 - Cluster
15 - Tipo de Equipamento
16 - Tipo de Movimento de Circuitos
17 - Tipo de Banda
18 - Tipo de Conectividade
19 - Tipo de Link
20 - Tipo de Anexo
21 - Tipo de Componente de Set de Segurança
22 - Tipo de Torre
23 - Tipo de Proposta
24 - Localização Proposta
25 - Status Proposta
26 - Tipo de Contrato
27 - Tipo de Processo de Contratação
28 - Tipo de Movimentos de Contrato
29 - Tipo de Modalidade de Contratação de Garantia
30 - Status Contrato (Vigente, Bloqueado, Suspenso, Encerrado)
31 - Tipo de Fiscal de Contrato
32 - Tipo de Estação Telecom
33 - Tipo de Serviço na Penalidade
34 - Tipo de Movimento de Penalidades
35 -
36 -
37 -
38 -
39 -
40 -
*/

INSERT INTO bd_circuitosnavega.lov (tipo, descricao, codigoespecifico, valor, duracao) VALUES
-- Tipo Unidade
(1, 'Gerência', null, null, null),
(1, 'Acesso', null, null, null),
-- Usa Contrato
(2, 'Sim', 'SYS', null, null),
(2, 'Não', 'SYS', null, null),
-- Função
(3, 'PTP', null, null, null),
(3, 'Cluster', null, null, null),
(3, 'Repetição', null, null, null),
(3, 'Hotzone', null, null, null),
(3, 'Cliente', null, null, null),
(3, 'Infocentro', null, null, null),
-- Esfera
(4, 'Federal', 'SYS', null, null),
(4, 'Estadual', 'SYS', null, null),
(4, 'Municipal', 'SYS', null, null),
(4, 'Privado', 'SYS', null, null),
(4, 'Terceiro Setor', 'SYS', null, null),
-- Setor
(5, 'Administração', null, null, null),
(5, 'Agropecuária', null, null, null),
(5, 'Mineração', null, null, null),
(5, 'Educação', null, null, null),
(5, 'Saúde', null, null, null),
(5, 'Tecnologia', null, null, null),
(5, 'Transporte', null, null, null),
(5, 'Turismo', null, null, null),
(5, 'Social', null, null, null),
(5, 'Cultura', null, null, null),
(5, 'Segurança', null, null, null),
(5, 'Habitação', null, null, null),
(5, 'Judiciário', null, null, null),
(5, 'Energia', null, null, null),
(5, 'Saneamento', null, null, null),
-- Status Circuito
(6, 'Ativo', 'SYS', null, null),
(6, 'Desativado', 'SYS', null, null),
(6, 'Pendente', 'SYS', null, null),
(6, 'Bloqueado', 'SYS', null, null),
(6, 'Suspenso', 'SYS', null, null),
-- Tipo Acesso
(7, 'Híbrido', null, null, null),
(7, 'Carona', null, null, null),
(7, 'Padrão', null, null, null),
(7, 'POP', null, null, null),
(7, 'Estrela', null, null, null),
-- Tipo E-mail
(8, 'Pessoal', 'SYS', null, null),
(8, 'Comericial', 'SYS', null, null),
-- Tipo Cliente
(9, 'Jurídica', 'SYS', 'PJ', null),
(9, 'Física', 'SYS', 'PF', null),
-- Sexo
(10, 'Feminino', 'SYS', null, null),
(10, 'Masculino', 'SYS', null, null),
-- Tipo Endereço
(11, 'Residencial', 'SYS', null, null),
(11, 'Correspondência', 'SYS', null, null),
(11, 'Comercial', 'SYS', null, null),
-- Tipo Telefone
(12, 'Fixo', 'SYS', null, null),
(12, 'Celular', 'SYS', null, null),
-- Tipo Cargo
(13, 'Diretor', null, null, null),
(13, 'Operador', null, null, null),
(13, 'Executivo', null, null, null),
-- Cluster
(14, 'Anel', null, null, null),
(14, 'Estrela', null, null, null),
-- Tipo Equipamento
(15, 'Switch', null, null, null),
(15, 'Radio', null, null, null),
(15, 'CMM', null, null, null),
-- Tipo Movimento
(16, 'Criação do Circuito', 'SYS', 1, null),
(16, 'Exclusão do Circuito', 'SYS', 1, null),
(16, 'Atualização do Circuito', 'SYS', 1, null),
(16, 'Transferência do Circuito', 'SYS', 1, null),
(16, 'Alteração de Banda', 'SYS', null, null),
(16, 'Mudança de Status do Circuito', 'SYS', null, null),
(16, 'Alteração de IP Gerencial', 'SYS', null, null),
(16, 'Alteração de IP Local', 'SYS', null, null),
(16, 'Alteração de Equipamento', 'SYS', null, null),
-- Banda
(17, '1 Mbps', null, null, null),
(17, '2 Mbps', null, null, null),
(17, '3 Mbps', null, null, null),
(17, '4 Mbps', null, null, null),
(17, '5 Mbps', null, null, null),
(17, '6 Mbps', null, null, null),
(17, '7 Mbps', null, null, null),
(17, '8 Mbps', null, null, null),
(17, '10 Mbps', null, null, null),
(17, '12 Mbps', null, null, null),
(17, '13 Mbps', null, null, null),
(17, '14 Mbps', null, null, null),
(17, '15 Mbps', null, null, null),
(17, '16 Mbps', null, null, null),
(17, '20 Mbps', null, null, null),
(17, '25 Mbps', null, null, null),
(17, '30 Mbps', null, null, null),
(17, '40 Mbps', null, null, null),
(17, '50 Mbps', null, null, null),
(17, '100 Mbps', null, null, null),
(17, '150 Mbps', null, null, null),
(17, 'Outros', null, null, null),
-- Tipo Conectividade
(18, 'Cluster', 'SYS', null, null),
(18, 'Porta de Switch', 'SYS', null, null),
(18, 'Repetição', 'SYS', null, null),
(18, 'Servidor', 'SYS', null, null),
-- Tipo Link
(19, 'Fibra', 'SYS', null, null),
(19, 'Rádio', 'SYS', null, null),
-- Tipo Anexo
(20, 'Documento', 'SYS', null, null),
(20, 'Vídeo', 'SYS', null, null),
(20, 'Imagem', 'SYS', null, null);