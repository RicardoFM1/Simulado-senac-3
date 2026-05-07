# Simulado-senac-3

* O intuito do sistema é poder fazer inscrições de alunos nas oficinas, podendo escolher qual quer cursar.


--- 

# Ferramentas utilizadas:

- Mysql Workbench;
- Mysql Server;
- Insomnia;
- Vs code;

## Bibliotecas utilizadas:
```
 "vlucas/phpdotenv": "^5.6",
"respect/validation": "^2.4"
```

--- 


# Como rodar:

Primeiro, baixe todas as dependências e ferramentas necessárias, depois execute no terminal:
```
cd backend
composer i

```


```
cd backend/routes
php -S localhost:3000

```

## Inicie o workbench:

* Crie no workbench uma instância e execute o seguinte comando:

```sql
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema sistema_oficinas
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sistema_oficinas
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sistema_oficinas` DEFAULT CHARACTER SET utf8 ;
USE `sistema_oficinas` ;

-- -----------------------------------------------------
-- Table `sistema_oficinas`.`participante`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_oficinas`.`participante` (
  `id_participante` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `data_nascimento` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id_participante`),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_oficinas`.`instrutor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_oficinas`.`instrutor` (
  `id_instrutor` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `area_atuacao` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_instrutor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_oficinas`.`oficina`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_oficinas`.`oficina` (
  `id_oficina` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(45) NOT NULL,
  `categoria` VARCHAR(45) NOT NULL,
  `carga_horaria` INT NOT NULL,
  `data_oficina` TIMESTAMP NOT NULL,
  `horario` TIME NOT NULL,
  `total_vagas` INT NOT NULL,
  `instrutor_idinstrutor` INT NOT NULL,
  PRIMARY KEY (`id_oficina`),
  INDEX `fk_oficina_instrutor_idx` (`instrutor_idinstrutor` ASC) VISIBLE,
  CONSTRAINT `fk_oficina_instrutor`
    FOREIGN KEY (`instrutor_idinstrutor`)
    REFERENCES `sistema_oficinas`.`instrutor` (`id_instrutor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sistema_oficinas`.`inscricao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sistema_oficinas`.`inscricao` (
  `id_inscricao` INT NOT NULL AUTO_INCREMENT,
  `data_inscricao` TIMESTAMP NOT NULL,
  `status` VARCHAR(45) NOT NULL,
  `participante_idparticipante` INT NOT NULL,
  PRIMARY KEY (`id_inscricao`),
  INDEX `fk_inscricao_participante_idx` (`participante_idparticipante` ASC) VISIBLE,
  CONSTRAINT `fk_inscricao_participante`
    FOREIGN KEY (`participante_idparticipante`)
    REFERENCES `sistema_oficinas`.`participante` (`id_participante`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

INSERT INTO participante (nome, cpf, email, telefone, data_nascimento)
VALUES ('Ricardo', '05380295010', 'ricardo@gmail.com', '519999', '21-03-2000'),
('Ricardo2', '63996705000', 'ricardo2@gmail.com', '519999', '21-03-2001'),
('Ricardo3', '99161809039', 'ricardo3@gmail.com', '519999', '21-03-2002'),
('Ricardo4', '00001789007', 'ricardo4@gmail.com', '519999', '21-03-2003'),
('Ricardo5', '47123407032', 'ricardo5@gmail.com', '519999', '21-03-2004'),
('Ricardo6', '56231051064', 'ricardo6@gmail.com', '519999', '21-03-2005'),
('Ricardo7', '71375767020', 'ricardo7@gmail.com', '519999', '21-03-2006'),
('Ricardo8', '03493281021', 'ricardo8@gmail.com', '519999', '21-03-2007'),
('Ricardo9', '97672214098', 'ricardo9@gmail.com', '519999', '21-03-2008'),
('Ricardo10', '03521702049', 'ricardo10@gmail.com', '519999', '21-03-2009');


INSERT INTO instrutor (nome, email, telefone, area_atuacao)
VALUES ('Rodrigo', 'rodrigo@gmail.com', '5193939341', 'TI'),
('Rodrigo2', 'rodrigo2@gmail.com', '5193939331', 'TI'),
('Rodrigo3', 'rodrigo3@gmail.com', '5193939331', 'Analista');

INSERT INTO oficina (titulo, descricao, categoria, carga_horaria, data_oficina, horario, total_vagas, instrutor_idinstrutor)
VALUES ('Informática', 'Oficina de informática', 'TI', 10, '21-03-2027', '10:00', 100, 1),
('Informática 2', 'Oficina de informática 2', 'TI', 10, '21-03-2028', '10:00', 100, 1),
('Informática 3', 'Oficina de informática 3', 'TI', 10, '21-03-2029', '10:00', 50, 1),
('Informática 4', 'Oficina de informática 4', 'TI', 10, '21-03-2030', '10:00', 100, 1),
('Informática 5', 'Oficina de informática 5', 'TI', 10, '21-03-2031', '10:00', 100, 1);



INSERT INTO inscricao (data_inscricao, status, participante_idparticipante, oficina_idoficina)
VALUES ('2000-11-03 10:00:00', 'inscrito', 1, 1),
 ('2000-09-03 11:00:00', 'inscrito', 2, 1),
  ('2000-12-06 10:00:00', 'inscrito', 2, 1),
   ('2000-12-10 10:00:00', 'inscrito', 2, 1),
    ('2000-12-22 12:00:00', 'cancelado', 1, 1),
     ('2000-08-03 10:00:00', 'inscrito', 1, 2),
      ('2000-12-08 10:00:00', 'cancelado', 2, 2),
       ('2000-12-01 10:00:00', 'inscrito', 1, 1),
        ('2000-12-11 10:00:00', 'inscrito', 1, 1),
         ('2000-12-27 10:00:00', 'inscrito', 1, 1);





SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

```

# Rotas: 

- Participante:

/participante - GET
/participante - POST
/participante?id_participante={id} - PUT
/participante?id_participante={id} - DELETE


--- 

- Instrutor:

/instrutor - GET
/instrutor - POST
/instrutor?id_instrutor={id} - PUT
/instrutor?id_instrutor={id} - DELETE

--- 


- Oficina:

/oficina - GET
/oficina - POST
/oficina?id_oficina={id} - PUT
/oficina?id_oficina={id} - DELETE


--- 

- Inscrição:

/inscricao - GET
/inscricao?id_participante={id} - GET
/inscricao?id_oficina={id} - GET
/inscricao - POST
/inscricao?id_oficina={id} - PUT
/inscricao?id_oficina={id} - DELETE





