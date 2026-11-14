-- Banco de dados do projeto MedTime

CREATE SCHEMA IF NOT EXISTS `banco`;
USE `banco`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `registro_dose`;
DROP TABLE IF EXISTS `prescricao_horario`;
DROP TABLE IF EXISTS `prescricao`;

DROP TABLE IF EXISTS `medicamento`;
DROP TABLE IF EXISTS `cuidado`;
DROP TABLE IF EXISTS `pessoa`;

CREATE TABLE `pessoa` (
  `pessoa_id` INT NOT NULL AUTO_INCREMENT,
  `pessoa_nome` VARCHAR(255) NOT NULL,
  `pessoa_email` VARCHAR(255) NOT NULL,
  `pessoa_senha` VARCHAR(255) NOT NULL,
  `pessoa_tipo` VARCHAR(255) NOT NULL,
  `pessoa_data_nascimento` DATE NOT NULL,
  `pessoa_peso` FLOAT NOT NULL,
  `pessoa_altura` FLOAT NOT NULL,
  PRIMARY KEY (`pessoa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cuidado` (
  `cuidado_id` INT NOT NULL AUTO_INCREMENT,
  `cuidado_nome` VARCHAR(255) NOT NULL,
  `cuidado_data_nas` VARCHAR(255) NOT NULL,
  `cuidado_foto` VARCHAR(200) NOT NULL,
  `fk_pessoa_cuidador` INT NOT NULL,
  `fk_pessoa_paciente` INT NOT NULL,
  PRIMARY KEY (`cuidado_id`),
  INDEX `fk_pessoa_cuidador_idx` (`fk_pessoa_cuidador`),
  INDEX `fk_pessoa_paciente_idx` (`fk_pessoa_paciente`),
  CONSTRAINT `fk_pessoa_cuidador`
    FOREIGN KEY (`fk_pessoa_cuidador`)
    REFERENCES `pessoa` (`pessoa_id`),
  CONSTRAINT `fk_pessoa_paciente`
    FOREIGN KEY (`fk_pessoa_paciente`)
    REFERENCES `pessoa` (`pessoa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `medicamento` (
  `medicamento_id` INT NOT NULL AUTO_INCREMENT,
  `medicamento_nome` VARCHAR(255) NOT NULL,
  `medicamento_foto` VARCHAR(200) NOT NULL,
  `medicamento_laboratorio` VARCHAR(255) NOT NULL,
  `medicamento_categoria` VARCHAR(255) NOT NULL,
  `medicamento_obervacao` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`medicamento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cada prescrição pertence diretamente a um paciente.
-- O cuidador é opcional: pode ficar NULL quando o próprio paciente
-- seleciona o medicamento.

CREATE TABLE `prescricao` (
  `prescricao_id` INT NOT NULL AUTO_INCREMENT,
  `medicamento_medicamento_id` INT NOT NULL,
  `pessoa_paciente_id` INT NOT NULL,
  `pessoa_cuidador_id` INT NULL,

  `prescricao_frequencia` INT NOT NULL,
  `prescricao_dosagem` FLOAT NOT NULL,
  `prescricao_horario_primeira_dose` DATETIME NULL,
  `prescricao_data_inicio` DATE NOT NULL,
  `prescricao_data_fim` DATE NULL,
  `prescricao_intervalo_horas` INT NULL,
  `prescricao_observacao` VARCHAR(255) NOT NULL DEFAULT '',

  PRIMARY KEY (`prescricao_id`),

  INDEX `idx_prescricao_medicamento` (`medicamento_medicamento_id`),
  INDEX `idx_prescricao_paciente` (`pessoa_paciente_id`),
  INDEX `idx_prescricao_cuidador` (`pessoa_cuidador_id`),

  CONSTRAINT `fk_prescricao_medicamento`
    FOREIGN KEY (`medicamento_medicamento_id`)
    REFERENCES `medicamento` (`medicamento_id`),

  CONSTRAINT `fk_prescricao_paciente`
    FOREIGN KEY (`pessoa_paciente_id`)
    REFERENCES `pessoa` (`pessoa_id`),

  CONSTRAINT `fk_prescricao_cuidador`
    FOREIGN KEY (`pessoa_cuidador_id`)
    REFERENCES `pessoa` (`pessoa_id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Permite cadastrar vários horários para uma mesma prescrição.

CREATE TABLE `prescricao_horario` (
  `prescricao_id` INT NOT NULL,
  `prescricao_horario` TIME NOT NULL,

  PRIMARY KEY (`prescricao_id`, `prescricao_horario`),

  CONSTRAINT `fk_prescricao_horario_prescricao`
    FOREIGN KEY (`prescricao_id`)
    REFERENCES `prescricao` (`prescricao_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Armazena o histórico individual de cada dose.

-- Armazena o histórico individual de cada dose.

CREATE TABLE `registro_dose` (
  `registro_dose_id` INT NOT NULL AUTO_INCREMENT,
  `prescricao_id` INT NOT NULL,
  `registro_dose_horario` DATETIME NOT NULL,

  `registro_dose_status` ENUM('pendente', 'tomada', 'nao_tomada')
    NOT NULL DEFAULT 'pendente',

  `registro_dose_data_tomada` DATETIME NULL,
  `pessoa_registrou_id` INT NULL,

  PRIMARY KEY (`registro_dose_id`),

  UNIQUE KEY `uk_dose_prescricao_horario` (
    `prescricao_id`,
    `registro_dose_horario`
  ),

  INDEX `idx_registro_dose_pessoa` (`pessoa_registrou_id`),

  CONSTRAINT `fk_registro_dose_prescricao`
    FOREIGN KEY (`prescricao_id`)
    REFERENCES `prescricao` (`prescricao_id`)
    ON DELETE CASCADE,

  CONSTRAINT `fk_registro_dose_pessoa`
    FOREIGN KEY (`pessoa_registrou_id`)
    REFERENCES `pessoa` (`pessoa_id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
