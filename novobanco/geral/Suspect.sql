-- para rodar arquivo sql execute source C:/xampp/htdocs/Suspect/novobanco/geral/Suspect.sql;

create database suspect;
use suspect;

CREATE TABLE usuario (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(50) NOT NULL UNIQUE,
  nome VARCHAR(50) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  telefone VARCHAR(20),
  tipo VARCHAR(3) NOT NULL,
  endereco VARCHAR(100) NOT NULL
);

CREATE TABLE administrador (
  id INT PRIMARY KEY AUTO_INCREMENT,
  matricula INT,
  cpf CHAR(11) NOT NULL
);

-- criado a tabela voluntario, era antes usuario.
CREATE TABLE voluntario (
  id INT PRIMARY KEY,
  cpf CHAR(11) NOT NULL,
  id_validador INT,
  data_nascimento char(8),

  -- teste para poder deletar conta
  FOREIGN KEY (id) REFERENCES usuario(id) ON DELETE CASCADE 
);

create table ong (
id INT PRIMARY KEY,
cnpj CHAR(14) NOT NULL UNIQUE,
id_validador INT,
descricao varchar(3000),
logo LONGBLOB,
financeiro varchar(100),
link varchar(500),
nome_link varchar(20),
-- teste para poder deletar conta
FOREIGN KEY (id) references usuario(id) ON DELETE CASCADE
);

create table animal_encontrado (
id INT AUTO_INCREMENT PRIMARY KEY,
nome varchar(50) not null,
raca varchar(50) not null,
idade int,
peso decimal(5, 2),
localizacao varchar(100),
altura decimal(5, 2),
estado varchar(50) not null,
sexo char(1) not null,
estado_saude varchar(50),
vacinas VARCHAR(500),  -- tem q ter essa merda aqui!
observacoes varchar(3000),
id_ong int null, -- pode ser null caso o animal nao tenha ong especifica
foto varchar (600),
foreign key (id_ong) references ong(id) ON DELETE CASCADE
);



create table animal_adocao (
id int auto_increment primary key,
nome varchar(50) not null,
raca varchar(50) not null,
idade int,
peso decimal(5, 2),
localizacao varchar(100),
altura decimal(5, 2),
estado varchar(50) not null,
sexo char(1) not null,
vacinas varchar(500),
castracao char(1),
observacoes varchar(3000),
foto varchar (255)
);

create table doacao (
id INT AUTO_INCREMENT PRIMARY KEY,
id_usuario int,
id_animal_encont int,
id_ong int,
data_doacao datetime not null,
foreign key (id_usuario) references usuario(id),
foreign key (id_ong) references ong(id),
foreign key (id_animal_encont) references animal_encontrado(id)
);

create table adocao (
id int auto_increment primary key,
id_usuario int,
id_ong int,
data_adocao datetime not null,
foreign key (id_usuario) references usuario(id),
foreign key (id_ong) references ong(id)
);

create table campanha (
id int not null auto_increment primary key,
id_animal_adoc int,
id_ong int,
data_publicacao datetime not null,
foreign key (id_animal_adoc) references animal_adocao(id),
foreign key (id_ong) references ONG(id)
);



-- de Acordo com o chat, é assim que fica os inserts para o novo banco...

-- ADM
insert into usuario (id, email, nome, senha, telefone, tipo, endereco) values (1, 'marcelo.abreu1@pucpr.edu.br', 'Marcelo Abreu', '$2y$10$8SJWd6AeFn2qPkQy3mOM6OUOQ2LgBAm8grNkGnHaebB2hmJjPgF3W', '41995777497', 'adm', 'PUCPR');
-- senha = 1234
insert into administrador (id, matricula, cpf) values (1, '4444', '12345678901');

--adm 2

INSERT INTO usuario (id, email, nome, senha, telefone, tipo, endereco) VALUES (5, 'eduardoggv@gmail.com', 'Eduardo Guilherme Gonçalves Vieira', '$2y$10$8SJWd6AeFn2qPkQy3mOM6OUOQ2LgBAm8grNkGnHaebB2hmJjPgF3W', '999764978', 'adm', 'Rua casa do caralho, 999');
insert into administrador (id, matricula, cpf) values (5, '3333', '9999999999');








-- senha = 123
INSERT INTO usuario (id, email, nome, senha, telefone, tipo, endereco) VALUES (2, 'dudu@gmail.com', 'Eduardo GGV', '$2y$10$qBxWcz8hkPQTpO2/fx/.COkWrnfgUFlsgvDjXXMZVPYKI.TD289vm', '999764978', 'vol', 'Rua casa do caralho, 999');
INSERT INTO voluntario (id, cpf, data_nascimento) VALUES (2, '99988877766', '11112001');


-- ongs para sempre rodar com o banco, somente pra aparecer lá

INSERT INTO usuario (id, email, nome, senha, telefone, tipo, endereco) 
VALUES (3, '4patas@gmail.com', '4Patas', '1234', '41999999999', 'ong', 'Rua Aqui Pertinho, 100');

INSERT INTO ong (id, cnpj, id_validador, descricao, financeiro, link, nome_link, logo) 
VALUES (3, '123456789', 1, 'Aqui na 4 patas, cuidamos do melhor amigo do homem dando abrigo, alimento, segurança e muito amor. Juntem-se a nós para tornar a vida destes pequeninos mas feliz!','41995777497', 'www.google.com.br','Google','https://mir-s3-cdn-cf.behance.net/project_modules/hd_webp/2a563069788407.5b8ddabd93fdc.png');


INSERT INTO usuario (id, email, nome, senha, telefone, tipo, endereco) 
VALUES (4, 'outra_ong@gmail.com', 'Outra ONG', '1234', '41999988999', 'ong', 'Rua Aqui Pertinho, 111');

INSERT INTO ong (id, cnpj, id_validador, descricao, financeiro, link, nome_link) 
VALUES (4, '987456321', 1,'Aqui na outra ong, cuidamos do melhor amigo do homem dando abrigo, alimento, segurança e muito amor. Juntem-se a nós para tornar a vida destes pequeninos mas feliz!', '0800 666 999', 'www.google.com.br', 'Google');


-- animais para sempre rodar com o banco, somente pra aparecer lá
INSERT INTO animal_encontrado (nome, raca, idade, peso, localizacao, altura, estado, sexo, estado_saude, observacoes, foto, id_ong, vacinas) 
VALUES ('Princesinha', 'Pit-Bull', 3, 5.00, 'São José dos Pinhais', 0.35, 'saudavel', 'F', 'Cão amavel', 'não castrado','https://tse3.mm.bing.net/th/id/OIP.y70ibdagkVuPw8uWG-t_twHaJw?rs=1&pid=ImgDetMain&o=7&rm=3', 3, 'nenhuma');

INSERT INTO animal_encontrado (nome, raca, idade, peso, localizacao, altura, estado, sexo, estado_saude, observacoes, foto, id_ong, vacinas) 
VALUES ('Horse only speak enlgish', 'Horse', 10, 125.00, 'United States of America', 2.30, 'A little Hurt', 'M', 'A lot to say, "life is snake"', 'não castrado','https://s1.static.brasilescola.uol.com.br/be/conteudo/images/cavalo.jpg', 4, 'todas');
