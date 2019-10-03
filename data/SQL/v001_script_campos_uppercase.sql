-- Ajuste dos campos em UPPERCASE nas tabelas
update cidade_digital set descricao = upper(descricao);
update circuitos set observacao = upper(observacao), chamado = upper(chamado), tag = upper(tag), cidade = upper(cidade), uf = upper(uf), designacao_anterior = upper(designacao_anterior);
update equipamento set descricao = upper(descricao), numpatrimonio = upper(numpatrimonio), numserie = upper(numserie), nome = upper(nome);
update lov set valor = upper(valor), codigoespecifico = upper(codigoespecifico), descricao = upper(descricao);
update modelo set descricao = upper(descricao), modelo = upper(modelo);
update movimentos set osocomon = upper(osocomon), observacao = upper(observacao);
update pessoa set nome = upper(nome);
update pessoa_contato set nome = upper(nome);
update pessoa_endereco set sigla_estado = upper(sigla_estado), estado = upper(estado), cidade = upper(cidade), complemento = upper(complemento), bairro = upper(bairro), numero = upper(numero), endereco = upper(endereco);
update pessoa_juridica set razaosocial = upper(razaosocial);
update empresa_departamento set descricao = upper(descricao);
update end_cidade set cidade = upper(cidade);
update end_bairro set bairro = upper(bairro);
update end_endereco set logradouro = upper(logradouro),local = upper(local),complemento = upper(complemento),tipo_logradouro = upper(tipo_logradouro);
update end_estado set estado = upper(estado);
update conectividade set endereco = upper(endereco),descricao = upper(descricao);